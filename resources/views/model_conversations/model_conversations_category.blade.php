@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/model_conversations/category.css') }}">
@endsection
@section('section')

    <main class="main_part">
        <div class="categories-box">

        </div>
        <div class="items">
            <hr>
            @isset($items)
                @if(count($items) > 0)
                <table class="table table-responsive items-table">
                    <thead>
                    <tr>
                        <th>Odtwórz</th>
                        <th>Nazwa</th>
                        <th>Upominek</th>
                        <th>Trener</th>
                        <th>Klient</th>
                        <th>Playlisty</th>
                        <th>Akcja</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr data-id="{{$item->id}}" data-playlist="{{$item->model_category_id}}">
                            <td>
                                <a data-toggle="modal" class="modal_trigger2" href="#play">
                                    <span data-nameOfFile="{{$item->file_name}}" class="play-sound glyphicon glyphicon-play-circle"></span>
                                </a>
                            </td>
                            <td><b>{{$item->name}}</b></td>
                            <td>{{$item->gift}}</td>
                            <td>{{$item->trainer}}</td>
                            <td>{{$item->client}}</td>
                            <td>
                                @php
                                $count = count($item->playlists);
                                $i = 0;
                                    foreach($item->playlists as $playlist) {
                                        if($playlist->user_id == \Illuminate\Support\Facades\Auth::user()->id) {
                                            if($i == $count - 1) {
                                                echo $playlist->name;
                                            }
                                            else {
                                                echo $playlist->name . ', ';
                                            }
                                            $i++;
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <button class="btn btn-info change-playlist" data-type="playlists" data-action="5" data-toggle="modal" data-target="#playlistAdd">Dodaj do playlisty</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <div class="alert alert-info">Brak rozmów w tej kategorii!</div>
                @endif
            @endisset
        </div>

    </main>

    <div id="play" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Odtwórz nagranie</h4>
                </div>
                <div class="modal2-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="playlistAdd" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Dodawanie rozmowy do playlisty!</h4>
                </div>
                <div class="modal-body2">
                    <form action="/modelConversationCategoryChangePlaylist" method="post">
                        <input type="hidden" name="id" class="item_id">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label for="playlists">Playlisty</label>
                            <select name="playlist" class="form-control playlists">
                                <option value="0">Wybierz</option>
                                @foreach($playlists as $playlist)
                                    @if($playlist->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                                        <option value="{{$playlist->id}}">{{$playlist->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <input type="submit" class="btn btn-success playlist_save" value="Dodaj do playlisty">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>

        </div>
    </div>

@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let CATEGORIES = {
            DOMElements: {
                categoriesBox: document.querySelector('.categories-box'),
                modal2body: document.querySelector('.modal2-body'),
                playlistAddModal: document.querySelector('#playlistAdd')
            },
            globalVariables: {
                categories: @json($categories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/categories3.js') }}"></script>
@endsection