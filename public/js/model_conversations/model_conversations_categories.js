document.addEventListener('DOMContentLoaded', function(event) {

    let categories = CATEGORIES.globalVariables.categories;

    //This function at begging sets containers with categories
    (function init() {
        let queue = new Queue();

        //Adding categories to queue
        categories.forEach(category => {
            let category_item = new Category(category.name, category.img, category.id);
            queue.enqueue(category_item);
        });

        while(!queue.isEmpty()) {
            let queue_item = queue.front();
            CATEGORIES.DOMElements.categoriesBox.appendChild(queue_item.createDOMElement());
            queue.dequeue();
        }
    })();

    function globalClickHandler(e) {
        let clickedElement = e.target;

        //User clicks on category div
        if(clickedElement.matches('.category') || clickedElement.matches('.center')) {
            let id = clickedElement.dataset.id; //This variable holds database id of category
            window.location.href = `/modelConversationCategory/${id}`;
        }
        else if(clickedElement.matches('.play-sound')) {
            let nameOfFile = clickedElement.dataset.nameoffile;
            CATEGORIES.DOMElements.modal2body.innerHTML = "<audio controls style='width:100%;'> <source src=" + CATEGORIES.globalVariables.url + '/' + nameOfFile + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";
        }
        else if(clickedElement.matches('.change-playlist')) {
            let currentRow = clickedElement.closest('tr');
            let itemId = currentRow.dataset.id;

            CATEGORIES.DOMElements.playlistAddModal.querySelector('.item_id').value = itemId;
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

    document.addEventListener('click', globalClickHandler);

});