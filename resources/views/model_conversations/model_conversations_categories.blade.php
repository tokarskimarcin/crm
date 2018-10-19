@extends('model_conversations.model_conversations_menu')
@section('section')
    <main class="main_part">

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
    <script src="{{ asset('js/model_conversations/menu.js') }}"></script>
@endsection