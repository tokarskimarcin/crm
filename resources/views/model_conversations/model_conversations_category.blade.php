@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/model_conversations/category.css') }}">
@endsection
@section('section')

    <main class="main_part">
        <div class="categories-box">

        </div>
        <div class="items">
            @isset($items)
                <hr>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Odtwórz</th>
                        <th>Nazwa</th>
                        <th>Upominek</th>
                        <th>Trener</th>
                        <th>Klient</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <a data-toggle="modal" class="modal_trigger2" href="#play">
                                    <span data-nameOfFile="{{$item->file_name}}" class="play-sound glyphicon glyphicon-play-circle"></span>
                                </a>
                            </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->gift}}</td>
                            <td>{{$item->trainer}}</td>
                            <td>{{$item->client}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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

@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let CATEGORIES = {
            DOMElements: {
                categoriesBox: document.querySelector('.categories-box'),
                modal2body: document.querySelector('.modal2-body')
            },
            globalVariables: {
                categories: @json($categories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/model_conversations_categories.js') }}"></script>
@endsection