document.addEventListener('DOMContentLoaded', function(event) {

    const firstPlaylistItem = PLAYLIST.globalVariables.playlist.length > 0 ? PLAYLIST.globalVariables.playlist[0] : null;
    let playlist = null;
    let playlist2 = null;

    if(firstPlaylistItem) {
        playlist = new Playlist(firstPlaylistItem.id, firstPlaylistItem.playlist_name, firstPlaylistItem.playlist_img);
        playlist2 = new Playlist(firstPlaylistItem.id, firstPlaylistItem.playlist_name, firstPlaylistItem.playlist_img);
    }

    (function init() {
        if(playlist) {

            //Enqueing playlist items
            PLAYLIST.globalVariables.playlist.forEach(item => {
                playlist.items.enqueue(item);
                playlist2.items.enqueue(item);
            });

            let tbody = PLAYLIST.DOMElements.playlistTable.querySelector('tbody');

            while(!playlist.items.isEmpty()) {
                let item = playlist.items.front();
                var tr = document.createElement('tr');

                var td_number = document.createElement('td');
                td_number.textContent = item.order;

                var td_name = document.createElement('td');
                td_name.textContent = item.conv_name;

                var td_gift = document.createElement('td');
                td_gift.textContent = item.gift;

                var td_trainer = document.createElement('td');
                td_trainer.textContent = item.trainer;

                var td_client = document.createElement('td');
                td_client.textContent = item.client;

                var td_audio = document.createElement('td');
                td_audio.innerHTML = "<audio controls><source src=" + PLAYLIST.globalVariables.url + '/' + item.file_name + " type='audio/wav'>Twoja przeglądarka nie obsługuje tego formatu pliku.</audio>";

                tr.appendChild(td_number);
                tr.appendChild(td_audio);
                tr.appendChild(td_name);
                tr.appendChild(td_gift)
                tr.appendChild(td_trainer)
                tr.appendChild(td_client)

                tbody.appendChild(tr);
                playlist.items.dequeue();
            }
        }
    })();

    //This is conter from controls section
    let counter = {
        max:  playlist2.items.size(),
        actual: 1
    }

    function adjustCounter() {

    }

});