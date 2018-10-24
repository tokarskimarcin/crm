function Playlist(id, name, imageName, itemsArr) {
    this.name = name;
    this.state = 0; // 0 - initial state, 1 - pause, 2 - play
    this.image = imageName;
    this.itemsArr = itemsArr;
    this.id = id;
    this.items = setItems(itemsArr);
    this.counter = {
        max:  itemsArr.length,
        actual: 1
    }

    function setItems(itemsArr) {
        console.assert(Array.isArray(itemsArr), 'itemsArr in Playlists items function is not array!');
        let queue = new Queue();
        itemsArr.forEach(item => {
            queue.enqueue(item);
        });
        return queue;
    }
}

/**
 * This method update counter property of playlist
 * @returns {boolean}
 */
Playlist.prototype.updateCounter = function() {
    if(this.counter.actual < this.counter.max) {
        this.counter.actual++;
        return true;
    }
    else if(this.counter.actual === this.counter.max) {
        this.counter.actual = 1;
        return null;
    }
    else {
        return false;
    }
}

Playlist.prototype.setState = function(state_id) {
    console.assert(!isNaN(state_id), 'state_id in setState function is not a number!');
    let state = null;
    switch(state_id) {
        case 0: {
            state = state_id;
            break;
        }
        case 1: {
            state = state_id;
            break;
        }

    }

    if(state !== null) { //State id was correct and it has been changed
        this.state = state;
        return true;
    }
    else { //state id wasn't correct
        return false;
    }

}

/**
 * This method creates playlist dom element.
 * @returns {HTMLDivElement}
 */
Playlist.prototype.createDOMElement = function() {
    let main_div = document.createElement('div');
    main_div.classList.add('playlist-category');
    main_div.setAttribute('data-id', this.id);

    let name_div = document.createElement('div');
    name_div.classList.add('center');
    name_div.textContent = this.name;
    name_div.setAttribute('data-id', this.id);

    main_div.appendChild(name_div);

    main_div.style.backgroundImage = 'url(' + PLAYLISTS.globalVariables.url + '/' + this.image + ')';
    return main_div;
}

Playlist.prototype.createDOMCounter = function() {
    let counter_div = document.createElement('div');
    counter_div.classList.add('counter-item');
    counter_div.textContent = `${this.counter.actual}/${this.counter.max}`

    return counter_div;
}

/**
 * This method is used when playlist is started.
 * @param counter
 */
Playlist.prototype.play = function() {
    const actualRowNumber = this.counter.actual;
    const lastRowNumber = this.counter.max;
    const tbody = PLAYLIST.DOMElements.playlistTable.querySelector('tbody');
    let previousRow = null;
    if(actualRowNumber - 1 > 0) {
        previousRow = tbody.querySelectorAll(`tr:nth-of-type(${actualRowNumber - 1})`)[0];
    }
    let actualRow = tbody.querySelectorAll(`tr:nth-of-type(${actualRowNumber})`)[0];
    let audioElement = actualRow.querySelector('audio');

    if(lastRowNumber > actualRowNumber) {
        if(this.state == 1) { //there is played element

        }
        else { //there isn't any played element
            console.log(audioElement);
            if(previousRow) {
                previousRow.style.backgroundColor = 'white';
            }
            actualRow.style.backgroundColor = 'lightgreen';
            audioElement.play();
            this.state = 2; //playing
            return true;

        }
    }
    else { //there will be played last row
        if(previousRow) {
            previousRow.style.backgroundColor = 'white';
        }
        actualRow.style.backgroundColor = 'lightgreen';
        audioElement.play();
        this.state = 0;
        return null;
    }
}