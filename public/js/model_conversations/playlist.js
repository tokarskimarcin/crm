function Playlist(id, name, imageName) {
    this.name = name;
    this.state = 0;
    this.image = imageName;
    this.id = id;
    this.items = new Queue();
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

/**
 * This method is used when playlist is started.
 * @param counter
 */
Playlist.prototype.play = function(counter) {
    let subQueue = new Queue();
    while(!this.items.isEmpty()) {
        subQueue.enqueue(this.items.enqueue())
    }
    let iterator = 1;
    if(counter.actual > iterator) {
        // this.items
    }
}