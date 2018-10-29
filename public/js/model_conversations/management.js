document.addEventListener('DOMContentLoaded', function() {
    let selectedTr = [];
    let selectedPlaylistId = null;

    /**
     * This method is called as page loads
     */
    (function init() {
        if(sessionStorage.getItem('activeElementName')) {
            const activeElementName = sessionStorage.getItem('activeElementName');
            const navElement = document.querySelector('.nav-tabs');
            let allAElements = navElement.querySelectorAll('a');

            //assigning class active to given element
            allAElements.forEach(element => {
               if(element.textContent == activeElementName) {
                   let hrefAttribute = element.attributes.href.textContent;
                   let tabPane = document.querySelector(`${hrefAttribute}`);
                   console.log(tabPane);
                   tabPane.classList.add('active');
                   tabPane.classList.add('in');
                   let tabElement = element.closest('li');

                   tabElement.classList.add('active');
                   element.setAttribute('aria-expanded', true);
               }
            });
            sessionStorage.removeItem('activeElementName');
        }
    })();

    /* EVENT LISTENERS FUNCTIONS */

    /**
     * This method saves to session storage name of active tab
     * @param e
     */
    function submitHandler(e) {
        e.preventDefault();
        const navElement = document.querySelector('.nav-tabs');
        const activeTab = navElement.querySelector('.active');
        const activeElement = activeTab.querySelector('a');
        const activeElementName = activeElement.textContent;
        sessionStorage.setItem('activeElementName', activeElementName);
        e.target.submit();
    }

    function globalClickHandler(e) {
        const clickedElement = e.target;


        if(clickedElement.matches('.arrowButtonAfter') || clickedElement.matches('.arrowButtonBefore')){
            modelConversationsManagementChangeOrder($(clickedElement).parent().parent().parent().data('playlist_order'));
        }
        else if(clickedElement.matches('.btn')) {
            const type = clickedElement.dataset.type;

            if(type == 'category') { //Button from category page
                const thisRow = clickedElement.closest('tr');
                const id = thisRow.dataset.id;
                const action = clickedElement.dataset.action;

                if(action == 0) { //permanent delete
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Usunięcie kategorii!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Usuń!'
                    }).then((result) => {
                        if (result.value) {
                            permanentDeleteFetch(type, id);
                        }
                    })
                }
                else if(action == 2 || action == 1) { //activation / deactivation
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Zmiana statusu kategorii!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Zmień!'
                    }).then((result) => {
                        if (result.value) {
                            changeStatusFetch(type, id);
                        }
                    })
                }
                else if(action == 4) { //Edition
                    let header = MANAGEMENT.DOMElements.categoryModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.categoryModal.querySelector('.category_save');
                    header.textContent = 'Edycja!';
                    saveButton.value = 'Edytuj!';

                    //At first we are assigning category id to form input
                    let name = thisRow.cells[1].textContent;

                    let statusSelect = thisRow.cells[3].querySelector('.category_status');
                    let selectedStatus = getSelectedValue(statusSelect);

                    let subcategorySelect = thisRow.cells[4].querySelector('.category_subcategory');
                    let selectedSubcategory = getSelectedValue(subcategorySelect);

                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_toAdd').value = 0; //edition
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_id').value = id; //edition
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_name').value = name;
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_status').value = selectedStatus;
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_subcategory').value = selectedSubcategory;
                }
                else if(action == 5) { //adding new category
                    let header = MANAGEMENT.DOMElements.categoryModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.categoryModal.querySelector('.category_save');
                    header.textContent = 'Dodawanie nowej kategori!';
                    saveButton.value = 'Dodaj!';

                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_toAdd').value = 1; //adding new item
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_name').value = '';
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_status').value = 1;
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_subcategory').value = 0;
                }
            }
            else if(type == 'items') {
                const thisRow = clickedElement.closest('tr');
                const id = thisRow.dataset.id;
                const action = clickedElement.dataset.action;

                if(action == 0) { //permanent delete
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Usunięcie rozmowy!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Usuń!'
                    }).then((result) => {
                        if (result.value) {
                            permanentDeleteFetch(type, id);
                        }
                    })
                }
                else if(action == 2 || action == 1) { //activation / deactivation
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Zmiana statusu rozmowy!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Zmień!'
                    }).then((result) => {
                        if (result.value) {
                            changeStatusFetch(type, id);
                        }
                    })
                }
                else if(action == 4) { //edition of existing item
                    let header = MANAGEMENT.DOMElements.itemEditionModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_save');
                    header.textContent = 'Edycja rozmowy!';
                    saveButton.value = 'Edytuj!';

                    let name = thisRow.cells[0].textContent;
                    let trainer = thisRow.cells[2].textContent;
                    let gift = thisRow.cells[3].textContent;
                    let client = thisRow.cells[4].textContent;

                    let categorySelect = thisRow.cells[5].querySelector('.item_category');
                    let selectedCategory = getSelectedValue(categorySelect);

                    let statusSelect = thisRow.cells[6].querySelector('.item_status');
                    let selectedStatus = getSelectedValue(statusSelect);

                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_toAdd').value = 0;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_id').value = id;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_name').value = name;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_trainer').value = trainer;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_gift').value = gift;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_client').value = client;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_category_id').value = selectedCategory;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_status').value = selectedStatus;
                }
                else if(action == 5) { //adding new item
                    let header = MANAGEMENT.DOMElements.itemEditionModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_save');
                    header.textContent = 'Dodawanie nowej rozmowy!';
                    saveButton.value = 'Dodaj!';

                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_toAdd').value = 1;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_id').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_name').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_trainer').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_gift').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_client').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_status').value = 1;
                }
            }
            else if(type == 'playlists') {
                const thisRow = clickedElement.closest('tr');
                const id = thisRow.dataset.id;
                const action = clickedElement.dataset.action;

                if(action == 0) {
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Usuń playlistę!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Usuń!'
                    }).then((result) => {
                        if (result.value) {
                            deletePlaylistCategory(id);
                        }
                    });

                }
                else if(action == 4) {
                    let header = MANAGEMENT.DOMElements.playlistModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_save');
                    header.textContent = 'Edycja playlisty!';
                    saveButton.value = 'Edytuj!';

                    let name = thisRow.cells[0].textContent;

                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_name').value = name;
                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_toAdd').value = 0;
                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_id').value = id;
                }
                else if(action == 5) {
                    let header = MANAGEMENT.DOMElements.playlistModal.querySelector('h4');
                    let saveButton = MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_save');
                    header.textContent = 'Dodaj playliste!';
                    saveButton.value = 'Dodaj!';

                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_name').value = '';
                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_toAdd').value = 1;
                    MANAGEMENT.DOMElements.playlistModal.querySelector('.playlist_id').value = '';
                }
            }
            else if(type == 'playlist-item') {
                const thisRow = clickedElement.closest('tr');
                const id = thisRow.dataset.id;
                const action = clickedElement.dataset.action;

                if(action == 0) {
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Usuń rozmowę z playlisty!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Usuń!'
                    }).then((result) => {
                        if (result.value) {
                            console.log('id', id);
                            deletePlaylistItem(id);
                        }
                    });
                }
            }
        }
        else if(clickedElement.matches('.play-sound')) {
            let nameOfFile = clickedElement.dataset.nameoffile;
            MANAGEMENT.DOMElements.modal2body.innerHTML = "<audio controls style='width:100%;'> <source src=" + MANAGEMENT.globalVariables.url + '/' + nameOfFile + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";
        }
        else if(clickedElement.matches('.playlist-table td')) {
            console.log(clickedElement);
            const clickedRow = clickedElement.closest('tr');
            const playlistId = clickedRow.dataset.id;
            // const userId = clickedRow.dataset.userid;
            console.log(clickedRow);
            console.log(playlistId);

            selectedPlaylistId = playlistId;
            //tutaj kolorowanie wiersza

            let items = null;
            getPlaylistItems(playlistId)
                .then(resp => {
                    items = resp;
                    if(items) {
                        let itemsTable = document.querySelector('.right-playlist-table > table');

                        appendPlaylistItems(items, itemsTable);
                    }

                })
                .catch(err => console.log(err));
        }
        else if(clickedElement.matches('td')){
            let tr = $(clickedElement).parent();
            if($('.right-playlist-table tbody').has(tr)){
                if(tr.hasClass('selectedTr')){
                    selectedTr = [];
                    tr.removeClass('selectedTr');
                }else{
                    $('.right-playlist-table tbody tr').removeClass('selectedTr');
                    tr.addClass('selectedTr');
                    selectedTr = [];
                    selectedTr.push(tr.data('playlist_order'));
                }
                $('.arrow').remove();
                if(selectedTr.length > 0){
                    $.each($('.right-playlist-table tbody tr'), function (index, trElement) {
                        if($(trElement).data('playlist_order') !== tr.data('playlist_order')){
                            let button = $('<button>').addClass('btn btn-default')
                                .append( $('<span>').addClass('glyphicon glyphicon-arrow-right')
                                );
                            if($(trElement).data('playlist_order') > tr.data('playlist_order')){
                                button.addClass('arrowButtonAfter');
                            }else{
                                button.addClass('arrowButtonBefore');
                            }
                            $($(trElement).children()[0])
                                .append($('<div>')
                                    .addClass('arrow')
                                    .append(button)
                                );
                        }

                    });
                }
            }
        }

    }

    /* END OF EVENT LISTENERS FUNCTIONS */

    /**
     * This method appends playlist items to table item.
     * @param items
     * @param table
     */
    function appendPlaylistItems(items, table) {
        let tbody = table.querySelector('tbody');
        tbody.innerHTML = ''; //clearing prev content

        items.forEach(item => {
            console.log(item);
           let tr = document.createElement('tr');

           $(tr).attr('data-playlist_order',item.playlist_order);
           $(tr).attr('data-id',item.id);

           let td1 = document.createElement('td');
           td1.textContent = item.playlist_order;

           let td2 = document.createElement('td');
           td2.textContent = item.item_name;

           let td3 = document.createElement('td');
           td3.innerHTML = "<audio controls style='width:100%;'> <source src=" + MANAGEMENT.globalVariables.url + '/' + item.item_file_name + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";

           let td4 = document.createElement('td');
           td4.textContent = item.item_client;

           let td5 = document.createElement('td');
           td5.textContent = item.item_gift;

           let td6 = document.createElement('td');
           td6.textContent = item.item_trainer;

           let td7 = document.createElement('td');
           td7.innerHTML = `<button class="btn btn-danger" data-type="playlist-item" data-action="0">Usuń</button>`;

           tr.appendChild(td1);
           tr.appendChild(td2);
           tr.appendChild(td3);
           tr.appendChild(td4);
           tr.appendChild(td5);
           tr.appendChild(td6);
           tr.appendChild(td7);

           tbody.appendChild(tr);
        });
    }

    /**
     * This method return collection of playlist items
     * @param id
     */
    function getPlaylistItems(id) {
        console.log(id);
        console.assert(!isNaN(id), 'id in getPlaylistItems is not number!');
        const ourHeaders = new Headers();
        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

        return fetch(`/modelConversationsGetPlaylistItems/${id}`, {
            method: 'get',
            headers: ourHeaders,
            credentials: "same-origin"
        })
            .then(resp => resp.json());
    }

    /**
     * This method returns selected by user from list item's value or null.
     */
    function getSelectedValue(element) {
        console.assert(element.tagName === 'SELECT', 'Argument of getSelectedValue is not select element');
        if(element.options[element.selectedIndex]) {
            return element.options[element.selectedIndex].value;
        }
        else {
            return null;
        }
    }

    /**
     *
     * @param type
     * @param id
     * This method sends Fetch request to server to change status of given type item.
     */
    function changeStatusFetch(type, id) {
        switch(type) {
            case 'category': {
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                fetch(`/modelConversationCategory/${id}`, {
                    method: 'put',
                    headers: ourHeaders,
                    credentials: "same-origin"
                })
                    .then(resp => {
                        window.location.reload();
                    })
                    .catch(err => {
                        swal(
                            err
                        )
                    });
                break;
            }
            case 'items': {
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                fetch(`/modelConversationItems/${id}`, {
                    method: 'put',
                    headers: ourHeaders,
                    credentials: "same-origin"
                })
                    .then(resp => {
                        window.location.reload();
                    })
                    .catch(err => {
                        swal(
                            err
                        )
                    });
                break;
            }
        }
    }

    function modelConversationsManagementChangeOrder(selectedOrder){
        let formData = new FormData();
        formData.append('selectedTr', selectedTr);
        formData.append('selectedOrder', selectedOrder);
        formData.append('selectedPlaylistId', selectedPlaylistId);

        const ourHeaders = new Headers();
        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

        fetch(`/modelConversationsManagementChangeOrder`, {
            method: 'post',
            headers: ourHeaders,
            credentials: "same-origin",
            body: formData,
        })
            .then(resp => {
                getPlaylistItems(selectedPlaylistId)
                    .then(resp => {
                        items = resp;
                        console.log(items);
                        if(items) {
                            let itemsTable = document.querySelector('.right-playlist-table > table');

                            appendPlaylistItems(items, itemsTable);
                        }

                    })
                    .catch(err => console.log(err));
            })
            .catch(err => {
                swal(
                    err
                )
            });
    }

    /* DELETE FUNCTIONS PART */

    function permanentDeleteFetch(type, id) {
        switch(type) {
            case 'category': {
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                fetch(`/modelConversationCategory/${id}`, {
                    method: 'delete',
                    headers: ourHeaders,
                    credentials: "same-origin"
                })
                    .then(resp => {
                        window.location.reload();
                    })
                    .catch(err => {
                        swal(
                            err
                        )
                    });
                break;
            }
            case 'items': {
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                fetch(`/modelConversationItems/${id}`, {
                    method: 'delete',
                    headers: ourHeaders,
                    credentials: "same-origin"
                })
                    .then(resp => {
                        window.location.reload();
                    })
                    .catch(err => {
                        swal(
                            err
                        )
                    });
                break;
            }
        }
    }

    /**
     * This function sends fetch for deleting playlist category
     * @param $id
     */
    function deletePlaylistCategory(id) {
        console.assert(!isNaN(id), 'id in getPlaylistItems is not number!');
        const ourHeaders = new Headers();
        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

        fetch(`/modelConversationsGetPlaylist/${id}`, {
            method: 'delete',
            headers: ourHeaders,
            credentials: "same-origin"
        })
            .then(resp => window.location.reload());
    }

    function deletePlaylistItem(id) {
        console.assert(!isNaN(id), 'id in deletePlaylistItem is not number!');
        const ourHeaders = new Headers();
        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

        fetch(`/modelConversationsGetPlaylistItems/${id}`, {
            method: 'delete',
            headers: ourHeaders,
            credentials: "same-origin"
        })
            .then(resp => window.location.reload());
    }

    /* END OF DELETE FUNCTIONS PART */

    document.addEventListener('click', globalClickHandler);
    MANAGEMENT.DOMElements.allForms.forEach(form => {
        form.addEventListener('submit', submitHandler);
    });

});