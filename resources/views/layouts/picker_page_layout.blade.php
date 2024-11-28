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
    <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/jquery-ui1_13_2.css') }}">
    <script src="{{ URL::asset('/js/jquery-ui1_13_2.js') }}"></script>
    <link href="{{ URL::asset('/css/asset_listing.css') }}" rel="stylesheet">
    <style>
        .error_colour {
            color: #dc3545;
        }

        #ui-id-1 {
            max-height: 200px;
            overflow: auto;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @yield('content')
    </div>
</body>

</html>