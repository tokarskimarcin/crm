<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/model_conversations/styles.css') }}">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <header class="main_part">
            <div class="logo">
                Tutaj jest logo verona
            </div>
            <div class="links">
                <div class="link link1">
                    TeamBox
                </div>
                <div class="link link2">
                    Inna strona
                </div>
            </div>
        </header>
        <main class="main_part">
            {{--<div class="category category_1"> Kategoria 1</div>--}}
            {{--<div class="category category_2"> Kategoria 2</div>--}}
            {{--<div class="category category_3"> Kategoria 3</div>--}}

            {{--<div class="category category_1"> Kategoria 4</div>--}}
            {{--<div class="category category_2"> Kategoria 5</div>--}}
            {{--<div class="category category_3"> Kategoria 6</div>--}}

            {{--<div class="category category_3"> Kategoria 7</div>--}}
        </main>
        <footer class="main_part">

        </footer>
    </div>
</body>
</html>

<script>
    //In this script we define global variables and php variables
    let APP = {
        DOMElements: {
            main: document.querySelector('main')
        },
        globalVariables: {
            categories: @json($categories)
        }
    };
</script>
<script src="{{ asset('js/model_conversations/script.js') }}"></script>
<script src="{{ asset('js/data_structures/queue.js') }}"></script>