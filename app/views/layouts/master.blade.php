<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>BC</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="kGfEFmvh8xmM3nq7DcZQv5uprQcbqHnKwSNOFbIIvRw" />
        {{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/bootstrap-minimit.min.css') }}
        {{ HTML::script('js/jquery.min.js') }}
        {{ HTML::script('js/bootstrap.min.js') }}
        @yield('js')
        <style>
            .noselect {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            
            .editor{
                height: 500px;
            }
            @yield('style')
        </style>
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    statusCode: {
                        401: function(){
                            location.href = '{{ URL::action('HomeController@showWelcome') }}';
                        }
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }
                });
                @yield('ready_js')
            });
        </script>
    </head>
    <body>    
        <div class="container-fluid">
            @if(Auth::check())
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            {{ HTML::linkAction('HomeController@showWelcome', 'Home', array(), array('class' => 'navbar-brand')) }}
                        </div>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" id="lastSubjectDropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" {{ Auth::user()->lastSubject ? '' : 'style="color:#843534"' }}>
                                    @if(Auth::user()->lastSubject)
                                        {{{ Auth::user()->lastSubject->name }}}
                                    @else
                                        Nie je vybratý žiadny predmet
                                    @endif
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @if(!Subject::withoutselected()->get()->isEmpty())
                                        @foreach(Subject::withoutselected()->get() as $subject)
                                            <li>
                                                {{ HTML::linkAction('UserController@setSelectedSubject', $subject->name . ' ' . $subject->bothYears() . ' ' . $subject->sessionString(), array('id' => $subject->id)) }}
                                            </li>
                                        @endforeach
                                    @else
                                        <li><a href="javascript:void(0)">Žiadny predmet na výber</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>{{ HTML::linkAction('UserController@show', Auth::user()->getFullName()) }}</li>
                            <li>{{ HTML::linkAction('LoginController@getLogout', 'Odhlásenie') }}</li>
                        </ul>
                    </div>
                </nav>
                @yield('top_row')
            @endif
            @if(Auth::check())
                <div class="row">
                    <div class="col-md-2" id="leftMenu">
                        <ul class="nav nav-pills nav-stacked" style="text-align: center">
                            @if(Auth::user()->isTeacher())
                                <li>{{ HTML::linkAction('TaskController@create', 'Vytvor zadanie', array()) }}</li>
                            @endif
                            @if(!Auth::user()->isTeacher())
                                <li>{{ HTML::linkAction('SubjectController@join', 'Prihlás na predmet', array()) }}</li>
                            @endif
                            <li>{{ HTML::linkAction('TaskController@all', 'Všetky zadania', array()) }}</li>
                            @if(Auth::user()->isTeacher())
                                <li>{{ HTML::linkAction('SubjectController@manage', 'Správa študentov', array()) }}</li>
                                <li>{{ HTML::linkAction('SubjectController@create', 'Vytvor predmet', array()) }}</li>
                            @endif
                        </ul>
                        @if(Auth::user()->lastSubject)
                        <p class="lead"></p>
                        <div class="thumbnail">
                            <?php $points = 0 ?>
                            <?php $total_points = 0 ?>
                            @foreach(Auth::user()->lastSubject->task()->afterStart()->get() as $task)
                                <?php $points += $task->points() ?>
                                <?php $total_points += $task->groupPoints() ?>
                            @endforeach
                            Body: {{{ $total_points . ' / ' . $points }}}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">{{Session::get('error')}}</div>
                        @endif
                        @if(Session::has('warning'))
                        <div class="alert alert-warning" role="alert">{{Session::get('warning')}}</div>
                        @endif
                        @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            @else
                <div style="margin-top: 1em">
                    @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">{{Session::get('error')}}</div>
                    @endif
                    @if(Session::has('warning'))
                        <div class="alert alert-warning" role="alert">{{Session::get('warning')}}</div>
                    @endif
                    @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                    @endif
                </div>
                @yield('content')
            @endif
        </div>
        @yield('last')
    </body>
</html>
