<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/vnd.microsoft.icon" href="{{URL::asset('/images/favicon.ico')}}">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="{{URL::asset('/images/favicon.ico')}}">
    <title>McGraw Hill</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script type="text/javascript">
        function preventBack() {
            window.history.forward()
        };
        setTimeout("preventBack()",0);
        window.onload=function() {
            null;
        }
    </script>

</head>
<body>
    <div id="app">
        <br/>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
