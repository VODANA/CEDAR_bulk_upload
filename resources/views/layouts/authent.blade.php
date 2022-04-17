<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VODAN | Africa</title>


    <link rel="stylesheet" href="{{asset('css/app.css')}}">

</head>

<body class="hold-transition login-page">

        <main class="py-1">
            @yield('content')
        </main>


    <script src="{{asset('js/app.js')}}" defer></script>
    @stack('scripts')

</body>

</html>