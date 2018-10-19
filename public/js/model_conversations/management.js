document.addEventListener('DOMContentLoaded', function(event) {

    // let categories = APP.globalVariables.categories;
    // console.log(categories);

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
                        }
                    })
                }
                else if(action == 2 || action == 1) { //activation
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
        }

    }

    document.addEventListener('click', globalClickHandler);

});