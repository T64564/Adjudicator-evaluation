<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Adjudicators evaludation</title>
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.css') }}">
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
        <script src="{{ URL::asset('js/jquery-1.12.1.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.tablesorter.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    </body>
</html>
