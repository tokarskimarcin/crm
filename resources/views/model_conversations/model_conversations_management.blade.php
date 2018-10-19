@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/model_conversations/management.css')}}">
@endsection

@section('section')

    <div class="box">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            <li><a data-toggle="tab" href="#menu1">Kategorie</a></li>
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
                            <td>{{$category->img}} <a href="{{asset('image/')}}/{{$category->img}}"><span class="glyphicon glyphicon-picture"></span></a></td>
                            <td>@if($category->status == 1) <span class="active-category">Aktywna</span> @else <div class="inactive-category">Nieaktywna</div> @endif</td>
                            <td>@if($category->subcategory_id == 0) Głowne kategorie @else {{$categories->where('id', '=', $category->subcategory_id)->first()->name}} @endif</td>
                            <td>@if($category->status == 1) <button class=" btn btn-warning" data-type="category" data-action="1">Wyłącz</button> @else <button class="btn btn-success" data-type="category" data-action="2">Włącz </button> @endif <button class="btn btn-danger" data-type="category" data-action="0">Usuń</button> <button class="btn btn-info" id="changePicture" data-type="category" data-action="4" data-toggle="modal" data-target="#myModal">Zmien zdjęcie</button> </td>
                        </tr>
                        @endforeach
                        <form  method="post" action="/modelConversationCategory" enctype="multipart/form-data">
                            <input type="hidden" name="toAdd" value="1">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <tr style="background-color: gray;">
                                <td>Nowa kategoria</td>
                                <td><input class="form-control" type="text" placeholder="Nazwa kategorii" name="name"></td>
                                <td><input type="file" name="picture"></td>
                                <td><select name="status"></select></td>
                                <td>
                                    <select name="subcategory" class="form-control">
                                        <option value="0">Główna kategoria</option>
                                        @foreach($categories as $category)
                                            @if($category->status == 1)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="submit" class="btn btn-success" value="Dodaj nową kategorię"></td>
                            </tr>
                        </form>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Zmień zdjęcie</h4>
                </div>
                <div class="modal-body">
                    <p>Wgraj swoje zdjęcie (wymiary tutaj)</p>
                    <form method="post" action="/modelConversationCategory" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="toAdd" value="0">
                        <input type="file" name="picture">
                        <input class="btn btn-success" type="submit" id="changePictureButton" value="Zapisz!">
                    </form>
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
        let APP = {
            globalVariables: {
                categories: @json($categories)
            }
        };
    </script>
    <script src="{{ asset('/js/sweetAlert.js')}}"></script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/management.js') }}"></script>
@endsection