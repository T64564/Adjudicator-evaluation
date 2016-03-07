<nav class="navbar navbar-default">
<div class="container">
    <div class="navbar-header">
        <!-- スマホやタブレットで表示した時のメニューボタン -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li>
                {!! link_to_route('feedbacks.index', 'Feedback') !!}
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" 
                            data-toggle="dropdown" role="button" aria-expanded="false">
                    Results
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        {!! link_to_route('results.ranking', 'Ranking') !!}
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" 
                            data-toggle="dropdown" role="button" aria-expanded="false">
                    Control Panel
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        {!! link_to_route('adjudicators.index', 'Adjudicators') !!}
                    </li>
                    <li>
                        {!! link_to_route('teams.index', 'Teams') !!}
                    </li>
                    <li>
                        {!! link_to_route('rounds.index', 'Rounds') !!}
                    </li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
