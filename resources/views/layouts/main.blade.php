<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
    @include('partials._style')
    @yield('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="wrapper">

    @include('partials._nav')

        <div id="page-wrapper">
            <div class="container-fluid" id="conntent" style="margin-top: 53px;">
                        @yield('content')
            </div>
        </div>
    {{--@include('partials._footer')--}}
    {{--@include('partials._logout')--}}


    </div>
    @include('partials._javascript')
    @yield('script.register')
    @yield('script.edithour')
    @yield('script.addhour')
    @yield('script.dkjNotification')
    @yield('script')
</body>

</html>
