@extends('model_conversations.model_conversations_menu')
@section('section')
    <main class="main_part">
        <div class="categories-box">

        </div>
    </main>
@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let CATEGORIES = {
            DOMElements: {
                categoriesBox: document.querySelector('.categories-box')
            },
            globalVariables: {
                categories: @json($categories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>

    <script src="{{ asset('js/model_conversations/category.js') }}"></script>
    <script src="{{ asset('js/model_conversations/model_conversations_menu.js') }}"></script>
@endsection