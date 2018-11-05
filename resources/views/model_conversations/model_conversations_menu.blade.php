<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/model_conversations/menu_default.css') }}">
    @yield('styles')
    <title>Rozmowy wzorcowe</title>
</head>
<body>
    <div class="wrapper">
        <header class="main_part">
            <div class="logo">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd">
            </div>
            <div class="links">
                <div class="link link4">
                    <a href="/modelConversationsPlaylist">Playlista</a>
                </div>
                <div class="link link1">
                    <a href="/modelConversationMenu">Kategorie</a>
                </div>
                <div class="link link3">
                    <a href="/modelConversationsManagement">Panel zarządzania</a>
                </div>
                <div class="link link1">
                    <a href="/">TeamBox</a>
                </div>
            </div>
        </header>
        @yield('section')
        <footer class="main_part">
            <p>Własność <u>Verona Consulting</u> </p>
            <p>Pomoc techniczna: <a href="mailto:pawel.chmielewski@veronaconsulting.pl">
                    programisci@veronaconsulting.pl</a>.</p>
        </footer>
    </div>
</body>
</html>
<script src="{{ asset('/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/data_structures/queue.js') }}"></script>
@yield('script')

