<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        إدارة التشغيل و الصيانة
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <!--<li><a href="{{ url('/register') }}">Register</a></li>-->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a id="logoutbtn" href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            تسجيل خروج
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <?php // var_dump(Route::getCurrentRoute()->getPath()); ?>
            <div class="container sub_menu">
                <div class="panel-heading sub_menu">
                    <ul class="header_ul">
                        <li><a class="<?php echo (strpos(Route::getCurrentRoute()->getPath(), 'home') !== false) ? "bordered_nav_btn" : ""; ?>" href="<?php echo URL::action('HomeController@index', 0); ?>">الطلبات</a></li>
                        <li><a class="<?php echo ((strpos(Route::getCurrentRoute()->getPath(), 'users') !== false) && strpos(Route::getCurrentRoute()->getPath(), 'settings') == false) ? "bordered_nav_btn" : ""; ?>" href="<?php echo URL::action('UsersController@index', 0); ?>">المستخدمين</a></li>
                        <li><a class="<?php echo (strpos(Route::getCurrentRoute()->getPath(), 'settings') !== false) ? "bordered_nav_btn" : ""; ?>" href="<?php echo URL::action('UsersController@userSettings', 0); ?>">إعدادات الحساب</a></li>
                    </ul>
                    
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js/all.js"></script>
    <script src="/js/jquery.fancybox.pack.js"></script>
    <!--<script src="/js/main.js"></script>-->
<!--        <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />

    <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>-->
    
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</body>
</html>
