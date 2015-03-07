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
                        {{ HTML::linkAction('HomeController@showWelcome', '.Home', array(), array('class' => 'navbar-brand')) }}
                    </div>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" id="lastSubjectDropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" {{ Auth::user()->lastSubject ? '' : 'style="color:#843534"' }}>
                                @if(Auth::user()->lastSubject)
                                    {{{ Auth::user()->lastSubject->name }}}
                                @else
                                .Nie je vybratý žiadny predmet
                                @endif
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @forelse(Auth::user()->subjects()->withoutselected()->get() as $subject)
                                    <li>
                                        {{ HTML::linkAction('UserController@setSelectedSubject', $subject->name . ' ' . $subject->bothYears() . ' ' . $subject->sessionString(), array('id' => $subject->id)) }}
                                    </li>
                                @empty
                                    <li><a href="javascript:void(0)">.Žiadny predmet</a></li>
                                @endforelse
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>{{ HTML::linkAction('UserController@show', Auth::user()->getFullName()) }}</li>
                        <li>{{ HTML::linkAction('LoginController@getLogout', '.Odhlásenie') }}</li>
                    </ul>
                </div>
            </nav>
            @yield('top_row')
            @endif
            @if(Auth::check())
            <div class="row">
                <div class="col-md-2" id="leftMenu">
                    <ul class="nav nav-pills nav-stacked" style="text-align: center">
                        <li>{{ HTML::linkAction('SubjectController@create', '.Vytvor predmet', array()) }}</li>
                        <li>{{ HTML::linkAction('TaskController@create', '.Vytvor zadanie', array()) }}</li>
                        <li>{{ HTML::linkAction('GroupController@create', '.Vytvor skupinu', array()) }}</li>
                        <li>{{ HTML::linkAction('TaskController@all', '.Všetky zadania', array()) }}</li>
                    </ul>
                    <p class="lead"></p>
                    <div class="thumbnail">

                    </div>
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
            @endif
        </div>
        {{ shell_exec('g++ -I/home/pi/gtest-svn/include -L/home/pi/gtest-svn/ /home/pi/gtest-svn/src/gtest_main.cc /home/pi/gtest-svn/samples/sample1.cc /home/pi/gtest-svn/samples/sample1_unittest.cc -lgtest -lpthread -o /home/pi/gtest-svn/samples/main 2>&1 1>/dev/null') }}
        {{ shell_exec('/home/pi/gtest-svn/samples/main --gtest_color=yes | sh /home/pi/gtest-svn/samples/ansi2html.sh > /var/www/test.html') }}
        {{ File::get('/var/www/test.html') }}
        .Info o kompilátore
        {{ shell_exec('g++ --version') }}
        @yield('last')
    </body>
</html>
