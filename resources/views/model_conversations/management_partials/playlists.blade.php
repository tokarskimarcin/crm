<div id="playlists" class="tab-pane fade" style="margin-top: 1em">
    <div class="left-playlist-table">
        <table class="table table-stripped playlist-table">
            <thead>
            <tr>
                {{--<th>ID</th>--}}
                <th>Nazwa</th>
                <th>Właściciel</th>
                <th>Zdjęcie</th>
                <th>Akcja</th>
            </tr>
            </thead>
            <tbody>
            @foreach($playlists as $playlist)
                <tr data-id="{{$playlist->id}}" data-userid="{{$playlist->user_id}}" class="playlist-category-row">
                    <td>{{$playlist->name}}</td>
                    <td>{{$playlist->first_name}} {{$playlist->last_name}}</td>
                    <td>{{$playlist->img}} <a href="{{asset('storage/')}}/{{$playlist->img}}"><span class="glyphicon glyphicon-picture"></span></a></td>
                    <td>
                        <button class="btn btn-danger" data-type="playlists" data-action="0">Usuń</button>
                        <button class="btn btn-info" data-type="playlists" data-action="4" data-toggle="modal" data-target="#playlistEdition">Edytuj</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>Dodaj</td>
                <td><button class="btn btn-info" data-type="playlists" data-action="5" data-toggle="modal" data-target="#playlistEdition">Dodaj</button></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>

    <span class="glyphicon glyphicon-arrow-down"></span>

    <div class="right-playlist-table">
        <table class="table">
            <thead>
            <tr>
                <th>Kolejność</th>
                <th>Nazwa</th>
                <th>Rozmowa</th>
                <th>Klient</th>
                <th>Prezent</th>
                <th>Trener</th>
                <th>Akcja</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>