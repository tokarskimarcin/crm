@extends('model_conversations.model_conversations_menu')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/model_conversations/playlist.css')}}">
@endsection
@section('section')
    <main class="main_part">
        <div class="playlist">
            <div class="playlist-header">
                <h3>{{$playlistObject->name}}</h3>
                <hr>
            </div>
            <div class="playlist-control">
                <div class="controls">
                    <span class="glyphicon glyphicon-play"></span>
                    <span class="glyphicon glyphicon-pause"></span>
                    <span class="glyphicon glyphicon-stop"></span>
                    <span class="glyphicon glyphicon-forward"></span>
                    <span class="glyphicon glyphicon-backward"></span>
                </div>
                <div class="counter">

                </div>
            </div>
            <div class="playlist-body">
                <table id="playlist-table" class="table table-stripped">
                    <thead>
                    <tr>
                        <th>Numer</th>
                        <th>Odtwarzanie</th>
                        <th>Nazwa</th>
                        <th>Prezent</th>
                        <th>Trener</th>
                        <th>Klient</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </main>
@endsection


@section('script')
    <script>
        //In this script we define global variables and php variables
        let PLAYLIST = {
            DOMElements: {
                categoriesBox: document.querySelector('.categories-box'),
                playlistTable: document.querySelector('#playlist-table')
            },
            globalVariables: {
                playlist: @json($playlist),
                url: `{{asset('storage/')}}`
            }
        };
    </script>
    <script src="{{asset('js/model_conversations/playlist.js')}}"></script>
    <script src="{{asset('js/model_conversations/model_conversations_playlist.js')}}"></script>
@endsection