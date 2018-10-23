@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/model_conversations/management.css')}}">
@endsection

@section('section')

    <div class="box">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            <li><a data-toggle="tab" href="#menu1">Kategorie</a></li>
            <li><a data-toggle="tab" href="#menu2">Rozmowy</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>HOME</h3>
                <p>Some content.</p>
            </div>
            <div id="menu1" class="tab-pane fade">
                <h3>Kategorie</h3>

                <table class="table table-stripped table-condensed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa</th>
                            <th>Zdjęcie</th>
                            <th>Status</th>
                            <th>Podkategoria</th>
                            <th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr data-id="{{$category->id}}">
                            <td>{{$category->id}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->img}} <a href="{{asset('storage/')}}/{{$category->img}}"><span class="glyphicon glyphicon-picture"></span></a></td>
                            <td>
                                <select class="category_status form-control" disabled>
                                    <option value="1" @if($category->status == 1) selected @endif>Aktywna</option>
                                    <option value="0" @if($category->status == 0) selected @endif>Nieaktywna</option>
                                </select>
                            <td>
                                <select class="category_subcategory form-control" disabled>
                                    <option value="0" @if($category->subcategory_id == 0) selected @endif>Główna</option>
                                    @foreach($categories as $category2)
                                        <option value="{{$category2->id}}" @if($category->subcategory_id == $category2->id) selected @endif>{{$category2->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @if($category->status == 1)
                                    <button class=" btn btn-warning" data-type="category" data-action="1">Wyłącz</button>
                                @else
                                    <button class="btn btn-success" data-type="category" data-action="2">Włącz </button>
                                @endif
                                <button class="btn btn-danger" data-type="category" data-action="0">Usuń</button>
                                <button class="btn btn-info" id="edit" data-type="category" data-action="4" data-toggle="modal" data-target="#myModal">Edytuj</button>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Dodaj nową kategorie</td>
                            <td><button class="btn btn-info" id="edit" data-type="category" data-action="5" data-toggle="modal" data-target="#myModal">Dodaj</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="menu2" class="tab-pane fade">
                <h3>Rozmowy</h3>
                <table class="table table-responsive table-condensed">
                    <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Rozmowa</th>
                        <th>Trener</th>
                        <th>Prezent</th>
                        <th>Klient</th>
                        <th>Kategoria</th>
                        <th>Status</th>
                        <th>Akcja</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr data-id="{{$item->id}}">
                                <td>{{$item->name}}</td>
                                <td>
                                    <a data-toggle="modal" class="modal_trigger2" href="#play">
                                        <span data-nameOfFile="{{$item->file_name}}" class="play-sound glyphicon glyphicon-play-circle"></span>
                                    </a>
                                </td>
                                <td>{{$item->trainer}}</td>
                                <td>{{$item->gift}}</td>
                                <td>{{$item->client}}</td>
                                <td>
                                    <select class="form-control item_category" disabled>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" @if($category->id == $item->model_category_id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="item_status form-control" disabled>
                                        <option value="1" @if($item->status == 1) selected @endif>Aktywna</option>
                                        <option value="0" @if($item->status == 0) selected @endif>Nieaktywna</option>
                                    </select>
                                    </td>
                                <td>
                                    @if($item->status == 1)
                                        <button class=" btn btn-warning" data-type="items" data-action="1">Wyłącz</button>
                                    @else
                                        <button class="btn btn-success" data-type="items" data-action="2">Włącz </button>
                                    @endif
                                    <button class="btn btn-danger" data-type="items" data-action="0">Usuń</button>
                                        <button class="btn btn-info" data-type="items" data-action="4" data-toggle="modal" data-target="#itemEdition">Edytuj</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Dodaj nową rozmowę</td>
                        <td><button class="btn btn-info" data-type="items" data-action="5" data-toggle="modal" data-target="#itemEdition">Dodaj</button></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
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
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
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
                categoryModal: document.querySelector('#myModal')
            },
            globalVariables: {
                categories: @json($categories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{ asset('/js/sweetAlert.js')}}"></script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/model_conversations_management.js') }}"></script>
@endsection