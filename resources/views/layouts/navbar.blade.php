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
                    <a href={{ route('feedbacks.index') }}>
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Feedback
                    </a>
                </li>
                <li>
                    <a href={{ route('results.ranking') }}>
                    <i class="fa fa-trophy" aria-hidden="true"></i>
                    Results
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" 
                                data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-asterisk" aria-hidden="true"></i>
                        Control Panel
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href={{ route('adjudicators.index') }}>
                                <i class="fa fa-gavel" aria-hidden="true"></i>
                                Adjudicators
                            </a>
                        </li>
                        <li>
                            <a href={{ route('teams.index') }}>
                                <i class="fa fa-users" aria-hidden="true"></i>
                                Teams
                            </a>
                        </li>
                        <li>
                            <a href={{ route('rounds.index') }}>
                                <i class="fa fa-comments" aria-hidden="true"></i>
                                Rounds
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href={{ route('backup') }}>
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Backup
                    </a>
                </li>
                <li>
                    <a href={{ route('restore') }}>
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        Restore
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
