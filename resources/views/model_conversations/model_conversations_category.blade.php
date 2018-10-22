@extends('model_conversations.model_conversations_menu')
@section('section')

    <main class="main_part">
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
                        <td><span class="glyphicon glyphicon-play-circle"></span></td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->gift}}</td>
                        <td>{{$item->trainer}}</td>
                        <td>{{$item->client}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endisset
    </main>

@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let APP = {
            DOMElements: {
                main: document.querySelector('main')
            },
            globalVariables: {
                categories: @json($categories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/categories.js') }}"></script>
@endsection