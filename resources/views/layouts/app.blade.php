<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>McGrawHill</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="{{asset('/js/jquery.min.js') }}"></script>

    <script type='text/javascript' src="{{asset('/js/bootstrapJQuery2.js')}}"></script>
    <script type='text/javascript' src="{{asset('/js/bootstrap3.js')}}"></script>
    
    <style>
        .error_colour {
            color: #dc3545;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @include('layouts.header')
        @yield('content')
        @include('layouts.footer')
    </div>
</body>

</html>