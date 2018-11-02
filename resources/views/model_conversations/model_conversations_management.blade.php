@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/model_conversations/management2.css')}}">
@endsection

@section('section')

    <div class="box">
        <ul class="nav nav-tabs">
            <li><a data-toggle="tab" href="#home">Legenda</a></li>
            @if(in_array($user, $adminPanelAccessArr))
                <li><a data-toggle="tab" href="#menu1">Kategorie</a></li>
            @endif
            <li><a data-toggle="tab" href="#menu2">Rozmowy</a></li>
            <li><a data-toggle="tab" href="#playlists2">Playlisty</a></li>
        </ul>

        <div class="tab-content">
            @include('model_conversations.management_partials.legend')
            @if(in_array($user, $adminPanelAccessArr))
                @include('model_conversations.management_partials.categories')
            @endif
            @include('model_conversations.management_partials.conversations')
            @include('model_conversations.management_partials.playlists')
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Zmień zdjęcie</h4>
                </div>
                <div class="modal-body">

                    <form  method="post" action="/modelConversationCategory" enctype="multipart/form-data">
                        <input type="hidden" name="toAdd" value="1" class="category_toAdd">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="" class="category_id">

                        <div class="form-group">
                            <label for="name">Nazwa kategorii</label>
                            <input class="form-control category_name" type="text" placeholder="Nazwa kategorii" name="name">
                        </div>

                        <div class="form-group">
                            <label for="picture">Zdjęcie</label>
                            <input type="file" name="picture">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control category_status">
                                <option value="1">Aktywna</option>
                                <option value="0">Nieaktywna</option>
                            </select>
                        </div>

                        @if($showAvailableDepartmentTypes)
                            <div class="form-group">
                                <label for="department_type_id">Rodzaj oddziału</label>
                                <select class="form-control" name="department_type_id" id="department_type_id">
                                    @foreach($availableDepartmentTypes as $dep)
                                        <option value="{{$dep->id}}">{{$dep->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="subcategory">Kategoria</label>
                            <select name="subcategory" class="form-control category_subcategory">
                                <option value="0">Główna kategoria</option>
                                @foreach($categories as $category)
                                    @if($category->status == 1)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" class="btn btn-success category_save" value="Zapisz">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="itemEdition" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Zmień zdjęcie</h4>
                </div>
                <div class="modal-body2">
                    <form action="/modelConversationItems" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="toAdd" value="1" class="item_toAdd">
                            <input type="hidden" name="id" class="item_id">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="temp" @if(in_array($user, $adminPanelAccessArr)) value="0" @else value="1" @endif>
                        <div class="form-group">
                            <label for="name">Nazwa</label>
                            <input type="text" class="form-control item_name" placeholder="Podaj nazwę" name="name">
                        </div>

                        <div class="form-group">
                            <label for="sound">Plik z rozmową</label>
                            <input class="item_file" type="file" name="sound">
                        </div>

                        <div class="form-group">
                            <label for="trainer">Trener</label>
                            <input type="text" class="form-control item_trainer" name="trainer" placeholder="Trener">
                        </div>

                        <div class="form-group">
                            <label for="gift">Prezent</label>
                            <input type="text" class="form-control item_gift" name="gift" placeholder="Prezent">
                        </div>

                         <div class="form-group">
                             <label for="client">Klient</label>
                             <input type="text" class="form-control item_client" name="client" placeholder="Klient">
                         </div>

                        <div class="form-group">
                            <label for="category_id">Kategoria</label>
                            <select name="category_id" class="form-control item_category_id">
                                @if(in_array($user, $adminPanelAccessArr))
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @else
                                    <option value="1">Własne rozmowy</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control item_status">
                                    <option value="1">Aktywna</option>
                                    <option value="0">Nie Aktywna</option>
                            </select>
                        </div>

                        <input type="submit" class="btn btn-success item_save" value="Zapisz">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="playlistEdition" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edytuj playlisty</h4>
                </div>
                <div class="modal-body2">
                    <form action="/modelConversationsPlaylist" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="toAdd" value="1" class="playlist_toAdd">
                        <input type="hidden" name="id" class="playlist_id">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="name">Nazwa</label>
                            <input type="text" class="form-control playlist_name" placeholder="Podaj nazwę" name="name">
                        </div>

                        <div class="form-group">
                            <label for="picture">Zdjęcie</label>
                            <input class="playlist_file" type="file" name="picture">
                        </div>

                        <input type="submit" class="btn btn-success playlist_save" value="Zapisz">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

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

@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let MANAGEMENT = {
            DOMElements: {
                modal2body: document.querySelector('.modal2-body'),
                itemEditionModal: document.querySelector('#itemEdition'),
                categoryModal: document.querySelector('#myModal'),
                playlistModal: document.querySelector('#playlistEdition'),
                allForms: document.querySelectorAll('form')
            },
            globalVariables: {
                categories: @json($categories),
                playlists: @json($playlists),
                playlistItems: @json($playlistItems),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{ asset('/js/sweetAlert.js')}}"></script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/management.js') }}"></script>
@endsection