//Create queue for button clicking handling
function Queue() {
    let items = [];
    this.enqueue = function(element) { //add element to queue
        items.push(element);
    };
    this.dequeue = function() { //remove element from queue
        return items.shift();
    };
    this.front = function() { //what is first element
        return items[0];
    };
    this.isEmpty = function() { //sprawdza czy jest pusta true = tak/ false = nie
        return items.length == 0;
    };
    this.size = function() {  // return lenght of queue
        return items.length;
    };
    this.print = function() { //print queue elements
        console.log(items.toString());
    }
}

