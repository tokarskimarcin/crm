    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Sebastian Cytawa && Konrad Jarzyna">

    <title>TeamBox</title>

    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/toogle.css')}}" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css"  rel="stylesheet"/>
    <!-- MetisMenu CSS -->
    <link href="{{ asset('/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <style type="text/css">
		.wrapper1, .wrapper2, .wrapper3, .wrapper4, .wrapper5, .wrapper6 {
			  width: 100%;
			  overflow-x: scroll;
			  overflow-y:hidden;
			}

			.wrapper1, .wrapper3, .wrapper5, {height: 20px; }
			.wrapper2, .wrapper4, .wrapper6, {height: auto; }

      .div1, .div3, .div5 {
			  min-width:120%;
			  height: 20px;
			}

			.div2, .div4, .div6 {
			  width:120%;
			  height: auto;
      }
      
      .alert-destroyer {
        background-color: #888c91;
        color: white;
      }

      .gray-nav {
        background-color: #7a7a7a;
        font-size: 150%;
        font-weight: bold;
        color: white;
      }
      .well-back {
        background-color: #fff;
      }
      .panel-info > .panel-heading {
        background-color: #666564;
        color: white;
        max-height: 41px;
        font-weight: bold;
      }

      .panel-info > .panel-heading > a {
        color: white;
        
      }

      .panel-default > .panel-heading {
        background-color: #666564;
        color: white;
        max-height: 41px;
        font-weight: bold;
      }

      .panel-default > .panel-heading > a {
        color: white;
      }
      .thead-inverse > thead {
        background-color: #666564;
        color: white;
        font-weight: bold;
      }

/*********************** DARK THEME START ******************************/
      {{--  .panel-info > .panel-heading {
        background-color: #666564;
        color: white;
        max-height: 41px;
        font-weight: bold;
      }

      .panel-info > .panel-heading > a {
        color: white;
        
      }

      .panel-default > .panel-heading {
        background-color: #666564;
        color: white;
        max-height: 41px;
        font-weight: bold;
      }

      .panel-default > .panel-heading > a {
        color: white;
      }

      .thead-inverse > thead {
        background-color: #666564;
        color: white;
        font-weight: bold;
      }

      .navbar-default {
        background-color: #666564;
        color: #fcfcfc;
        font-weight: bold;
      }

      .navbar-default li {
        background-color: #666564 !important;
        color: #fcfcfc !important;
      }

      .navbar-default a {
        color: #fcfcfc;
      }
      
      .container-fluid {
        background-color: #f3f3f4;
      }

      #page-wrapper {
        background-color: #f3f3f4;
      }

      .nav>li>a:focus, .nav>li>a:hover {
        text-decoration: none;
        background-color: #676767;
      }

      .sidebar ul li a.active {
        background-color: #676767;
      }

      .nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
        background-color: #676767;
        border-color: #676767;
      }

      #wrapper {
        background-color: #f3f3f4;
      }

      body {
       background-color: #f3f3f4;
      }

      .well-back {
        background-color: #fff;
      }

      .nav-tabs > li > a:hover {
        background-color: white;
      }  --}}
/********************* DARK THEME STOP ***********************************/
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="{{ asset('/js/sweetAlert.js')}}"></script>


    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
