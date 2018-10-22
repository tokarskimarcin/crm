document.addEventListener('DOMContentLoaded', function(event) {

    function globalClickHandler(e) {
        const clickedElement = e.target;

        //User clicks on category div
        if(clickedElement.matches('.btn')) {
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
                    //At first we are assigning category id to form input
                    let name = thisRow.cells[1].textContent;

                    let statusSelect = thisRow.cells[3].querySelector('.category_status');
                    let selectedStatus = getSelectedValue(statusSelect);

                    let subcategorySelect = thisRow.cells[4].querySelector('.category_subcategory');
                    let selectedSubcategory = getSelectedValue(subcategorySelect);

                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_toAdd').value = 1; //edition
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_name').value = name;
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_status').value = selectedStatus;
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_subcategory').value = selectedSubcategory;
                }
                else if(action == 5) {
                    MANAGEMENT.DOMElements.categoryModal.querySelector('.category_toAdd').value = 0; //adding new item
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
                    let name = thisRow.cells[0].textContent;
                    let trainer = thisRow.cells[2].textContent;
                    let gift = thisRow.cells[3].textContent;
                    let client = thisRow.cells[4].textContent;

                    let categorySelect = thisRow.cells[5].querySelector('.item_category');
                    let selectedCategory = getSelectedValue(categorySelect);

                    let statusSelect = thisRow.cells[6].querySelector('.item_status');
                    let selectedStatus = getSelectedValue(statusSelect);

                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_toAdd').value = 1;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.id').value = id;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_name').value = name;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_trainer').value = trainer;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_gift').value = gift;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_client').value = client;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_category_id').value = selectedCategory;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_status').value = selectedStatus;
                }
                else if(action == 5) { //adding new item
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_toAdd').value = 0;
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.id').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_name').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_trainer').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_gift').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_client').value = '';
                    MANAGEMENT.DOMElements.itemEditionModal.querySelector('.item_status').value = 1;
                }
            }
        }
        else if(clickedElement.matches('.play-sound')) {
            let nameOfFile = clickedElement.dataset.nameoffile;
            MANAGEMENT.DOMElements.modal2body.innerHTML = "<audio controls style='width:100%;'> <source src=" + MANAGEMENT.globalVariables.url + '/' + nameOfFile + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";
        }

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


    document.addEventListener('click', globalClickHandler);

            // document.getElementsByClassName('modal2-body')[0].innerHTML = "<audio controls style='width:100%;'> <source src='/api/getAuditScan/" + e.target.dataset.nameoffile + "'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";

});