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
                url: `{{Storage::url('app/public')}}`,
                url2: `{{asset('image/')}}`
            }
        };
    </script>

    <script src="{{ asset('js/model_conversations/category.blade.js') }}"></script>
    <script src="{{ asset('js/model_conversations/script.blade.js') }}"></script>
@endsection