<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Adjudicators evaludation</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>
        @include('layouts.navbar')
        <div class="container">
        @if (Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if (Session::has('flash_danger'))
            <div class="alert alert-danger">
                {{ Session::get('flash_danger') }}
            </div>
        @endif
        @yield('content')
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
