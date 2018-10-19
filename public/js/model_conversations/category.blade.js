//This constructor hold info about each category container.
function Category(name, imageName, id) {
    this.name = name;
    this.image = imageName;
    this.id = id;
}

Category.prototype.createDOMElement = function() {
    let main_div = document.createElement('div');
    main_div.classList.add('category');
    main_div.setAttribute('data-id', this.id);

    let name_div = document.createElement('div');
    name_div.classList.add('center');
    name_div.textContent = this.name;
    name_div.setAttribute('data-id', this.id);

    main_div.appendChild(name_div);

    main_div.style.backgroundImage = 'url(' + APP.globalVariables.url2 + '/' + this.image + ')';
    return main_div;
}