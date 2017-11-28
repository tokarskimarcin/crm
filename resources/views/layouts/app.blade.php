<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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

<!----
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		    <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
-->
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
  <!-- Top content -->
         <div class="top-content">

             <div class="inner-bg">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-8 col-sm-offset-2 text">
                             <h1><strong>Y-desing</strong> logowanie</h1>
                             <div class="description">
                             	<p>
 	                            	Zaloguj sie
                             	</p>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-sm-6 col-sm-offset-3 form-box">

                               <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                 <div class="form-bottom">
                             {{ csrf_field() }}
       			                    	<div class="form-group">
                                    <input id="username" type="text" class="form-control form-username" name="username" value="{{ old('username') }}" required autofocus placeholder="Login...">

                                       @if ($errors->has('username'))
                                           <span class="help-block">
                                               <strong>{{ $errors->first('username') }}</strong>
                                           </span>
                                       @endif
       			                        </div>
       			                        <div class="form-group">
                                      <input id="password" type="password" class="form-control" name="password" required placeholder="Hasło...">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
       			                        </div>
       			                        <button style="width: 100%" type="submit" class="btn">Zaloguj!</button>
                                  </div>
 			                          </form>

                         </div>
                     </div>
                 </div>
             </div>

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
