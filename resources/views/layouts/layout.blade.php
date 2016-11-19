<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Adjudicators evaludation</title>
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/navbar.css') }}">
</head>
<body>
    @include('layouts.navbar')
    <div class="container">
        @if (Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if (Session::has('flash_info'))
            <?php
                $flash_info = Session::get('flash_info');
            ?>
            <div class="alert alert-info">
                <ul>
                    @foreach(Session::get('flash_info') as $inf)
                        <li>{{ $inf }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::has('flash_warning'))
            <div class="alert alert-warning">
                <ul>
                    @foreach(Session::get('flash_warning') as $warn)
                        <li>{{ $warn }}</li>
                    @endforeach
                </ul>
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
    <script src="{{ URL::asset('js/helper.js') }}"></script>
</body>
</html>
