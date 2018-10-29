document.addEventListener('DOMContentLoaded', function(event) {

    let playlists = PLAYLISTS.globalVariables.playlists;

    //This function at begging sets containers with categories
    (function init() {
        let queue = new Queue();

        //Adding categories to queue
        playlists.forEach(playlist => {
            let playlist_category = new Playlist(playlist.id, playlist.name, playlist.img);
            queue.enqueue(playlist_category);
        });

        while(!queue.isEmpty()) {
            let queue_item = queue.front();
            PLAYLISTS.DOMElements.categoriesBox.appendChild(queue_item.createDOMElement());
            queue.dequeue();
        }
    })();

    function globalClickHandler(e) {
        let clickedElement = e.target;

        //User clicks on category div
        if(clickedElement.matches('.playlist-category') || clickedElement.matches('.center')) {
            let id = clickedElement.dataset.id; //This variable holds database id of playlist
            window.location.href = `/modelConversationsPlaylist/${id}`;
        }
    }

    document.addEventListener('click', globalClickHandler);

});