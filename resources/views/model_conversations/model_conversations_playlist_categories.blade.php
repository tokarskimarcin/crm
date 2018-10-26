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
        let PLAYLISTS = {
            DOMElements: {
                categoriesBox: document.querySelector('.categories-box')
            },
            globalVariables: {
                playlists: @json($playlistCategories),
                url: `{{asset('storage/')}}`
            }
        };
    </script>

    <script src="{{ asset('js/model_conversations/playlist.js') }}"></script>
    <script src="{{ asset('js/model_conversations/model_conversations_playlist_categories.js') }}"></script>
@endsection