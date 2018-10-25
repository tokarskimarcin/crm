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

    }

    document.addEventListener('click', globalClickHandler);

});