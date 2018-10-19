<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/model_conversations/styles.css') }}">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <header class="main_part">
            <div class="logo">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd">
            </div>
            <div class="links">
                <div class="link link1">
                    <a href="/">TeamBox</a>
                </div>
                <div class="link link1">
                    <a href="/modelConversationMenu">Kategorie</a>
                </div>
                <div class="link link3">
                    <a href="/modelConversationsManagement">Panel zarządzania</a>
                </div>
                <div class="link link4">
                    <a href="/modelConversationsPlaylist">Play lista</a>
                </div>
            </div>
        </header>
        @yield('section')
        {{--<main class="main_part">--}}
           {{----}}
            {{--<div class="category category_1"> Kategoria 1</div>--}}
            {{--<div class="category category_2"> Kategoria 2</div>--}}
            {{--<div class="category category_3"> Kategoria 3</div>--}}

        {{--</main>--}}
        <footer class="main_part">
            <p>Posted by: Paweł Chmielewski</p>
            <p>Contact information: <a href="mailto:someone@example.com">
                    someone@example.com</a>.</p>
        </footer>
    </div>
</body>
</html>
<script src="{{ asset('/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/data_structures/queue.js') }}"></script>
@yield('script')

