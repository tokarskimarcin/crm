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
                else if(action == 4) { //change picture
                    //At first we are assigning category id to form input
                    let changePictureButton = document.querySelector('#changePictureButton');
                    let pictureForm = changePictureButton.closest('form');
                    let inputId = pictureForm.querySelector('input[name="id"]');
                    inputId.value = id;
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
            }
        }
        else if(clickedElement.matches('.play-sound')) {
            let nameOfFile = clickedElement.dataset.nameoffile;
            MANAGEMENT.DOMElements.modal2body.innerHTML = "<audio controls style='width:100%;'> <source src=" + MANAGEMENT.globalVariables.url + '/' + nameOfFile + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";
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