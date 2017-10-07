<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
    @include('partials._style')
    @yield('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

    @include('partials._nav')

    <div class="content-wrapper">
        <div class="container-fluid">
            @include('partials._Breadcrumbs')
            <div class="row">
             <div class="col-12">
                @yield('content')
             </div>
            </div>
    </div>
        @include('partials._footer')
        @include('partials._logout')
        @include('partials._javascript')
        @yield('script')
    </div> <!-- end of .container -->
</body>

</html>