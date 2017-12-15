<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TeamBox</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">




      <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/form-elements.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

      <link href="{{ asset('assets/ico/favicon.png') }}" rel="shortcut icon">
      <link href="{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}" sizes="144x144" rel="apple-touch-icon-precomposed">
      <link href="{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}" sizes="144x144" rel="apple-touch-icon-precomposed">
      <link href="{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}" sizes="72x72" rel="apple-touch-icon-precomposed">
      <link href="{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}" rel="apple-touch-icon-precomposed">

      <style>
        .white-color {
          color: #fff;
        }
      </style>
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
  <!-- Top content -->
<div class="wrapper">
    <form class="form-signin col-md-4 " style="margin-left: 33%" method="POST" action="{{ route('login') }}">
      {{ csrf_field() }}
        <h2 class="form-signin-heading white-color" >Zaloguj się</h2>
        @if (Session::has('message'))
           <div class="alert alert-danger">{{ Session::get('message') }}</div>
        @endif
        @if ($errors->has('username'))
                 <div class="alert alert-danger">{{ $errors->first('username') }}</div>
        @endif
        @if ($errors->has('password'))
                 <div class="alert alert-danger">{{ $errors->first('password') }}</div>
        @endif
            <div class="form-group">
                <input type="text" class="form-control" name="username" id="username" placeholder="Login..." value="{{ old('username') }}" required="" autofocus="" />

            </div>
            <div class="form-group">
              <input type="password" id="password" class="form-control" name="password" placeholder="Hasło..." required=""/>

            </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>
    </form>
</div>
<!-- @yield('content') -->
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.backstretch.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>

</body>
</html>
