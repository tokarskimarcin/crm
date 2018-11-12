document.addEventListener('DOMContentLoaded', (event) => {
    $('.select2').select2();

    const firstSubmitButton = document.querySelector('#first-submit-button');
    const secondSubmitButton = document.querySelector('#second-submit-button');
    const thirdSubmitButton = document.querySelector('#third-submit-button');

    function globalClickHandler(e) {
        const clickedElement = e.target;
        if(clickedElement.matches('#first-submit-button')) {
            e.preventDefault();

            swal({
                title: 'Jesteś pewien?',
                text: "Brak możliwości cofnięcia zmian! Dostępy z jednej grupy zostaną przypisane innej",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tak, przepisz!'
            }).then((result) => {
                if (result.value) {
                    const relatedForm = document.querySelector('#form1');
                    relatedForm.submit();
                }
            })
        }
        else if(clickedElement.matches('#second-submit-button')) {
            e.preventDefault();

            swal({
                title: 'Jesteś pewien?',
                text: "Brak możliwości cofnięcia zmian! Dostępy z grupy zostaną przypisane dla użytkownika",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tak, przepisz!'
            }).then((result) => {
                if (result.value) {
                    const relatedForm = document.querySelector('#form2');
                    relatedForm.submit();
                }
            })
        }
        else if(clickedElement.matches('#third-submit-button')) {
            e.preventDefault();

            swal({
                title: 'Jesteś pewien?',
                text: "Brak możliwości cofnięcia zmian! Dostępy z jednego użytkownika zostaną przypisane drugiemu",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tak, przepisz!'
            }).then((result) => {
                if (result.value) {
                    const relatedForm = document.querySelector('#form3');
                    relatedForm.submit();
                }
            })
        }
    }

    document.addEventListener('click', globalClickHandler);
})
