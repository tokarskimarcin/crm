document.addEventListener('DOMContentLoaded', function(event) {
    let categories = APP.globalVariables.categories;

    function Category(name) {
        this.name = name;
        this.image = null;
    }

    Category.prototype.createDOMElement = function() {
        let main_div = document.createElement('div');
        main_div.classList.add('category');
        main_div.textContent = this.name;
        return main_div;
    }

    let queue = new Queue();

    //Adding categories to queue
    categories.forEach(category => {
       let category_item = new Category(category.name);
       queue.enqueue(category_item);
    });

    while(!queue.isEmpty()) {
        let queue_item = queue.front();
        console.log(APP.DOMElements.main);
        APP.DOMElements.main.appendChild(queue_item.createDOMElement());
        queue.dequeue();
    }
});