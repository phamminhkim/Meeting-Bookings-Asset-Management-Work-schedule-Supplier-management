<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>App Thiên Long</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{asset('')}}" />
    <!-- bootstrap & fontawesome -->


    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
    <!-- <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="assets/css/ace-rtl.min.css" /> -->

    <!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <!-- <script src="assets/js/ace-extra.min.js"></script> -->

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
        <![endif]-->

    <!-- my css -->
    <link rel="stylesheet" href="assets/mycss/main.css" />



<script src="https://eoffice.thienlong.vn/Link/Scripts/Eoffice10/ckeditor/ckeditor.js"></script>
    <style>
        .alert,
        .thumbnail {
            margin-bottom: 0px;
        }
    </style>
        <!-- Scripts -->
        <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'access_token' => $access_token
        ]) !!};


    </script>

    @yield('css')
</head>

<body class="no-skin">
    <div id="app">
    <div id="navbar" class="navbar navbar-default  ace-save-state">
        <div class="navbar-container ace-save-state" id="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="navbar-header pull-left" style="text-align:center">

                <a href="#" class="navbar-brand">
                    <small>
                        <!-- <i class="fa fa-cloud"></i> -->
                        Tập Đoàn Thiên Long

                    </small>
                </a>
                <!-- <img src="logo.png" style="height:45px;width:140px; background:white;

                    -moz-box-shadow: 3px 3px 5px 0px #666;
                    -webkit-box-shadow: 3px 3px 5px 0px #666;
                    box-shadow: 3px 3px 5px 0px #666;"> -->
            </div>

            <div class=" navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">

                <notification

                        v-bind:userid="'{{auth()->user()->id}}'"
                        v-bind:unreads="{{auth()->user()->unreadNotifications}}">
                </notification>

                    <li class="light-blue dropdown-modal">

                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @else
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <!-- <img class="nav-user-photo" src="assets/images/avatars/user.jpg" alt="Jason's Photo" /> -->
                        <span class="user-info">
                            <small>Xin chào,</small>
                            {{Auth::user()->name}}
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        @can('manage-systems')
                        <li>
                            <a href="{{url('/admin/users')}}">
                                <i class="ace-icon fa fa-cog"></i>
                                Admin Management
                            </a>
                        </li>
                        <li class="divider"></li>
                        @endcan
                        <li>
                            <a href="{{route('profile.index')}}">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                    @endguest
                    </li>
                </ul>
            </div>
        </div><!-- /.navbar-container -->
    </div>

    <div class="main-container ace-save-state" id="main-container">
        <!-- <script type="text/javascript">
            try {
                ace.settings.loadState('main-container')
            } catch (e) {}
        </script> -->

        <div id="sidebar" class="sidebar responsive ace-save-state">
            <!-- <script type="text/javascript">
                try {
                    ace.settings.loadState('sidebar')
                } catch (e) {}
            </script> -->

            <!-- <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="ace-icon fa fa-signal"></i>
                    </button>

                    <button class="btn btn-info">
                        <i class="ace-icon fa fa-pencil"></i>
                    </button>

                    <button class="btn btn-warning">
                        <i class="ace-icon fa fa-users"></i>
                    </button>

                    <button class="btn btn-danger">
                        <i class="ace-icon fa fa-cogs"></i>
                    </button>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>
            </div> -->
            <!-- /.sidebar-shortcuts -->
            <!-- <div style="background:white;height:5px;border:solid #fff 1px"></div> -->
            <ul class="nav nav-list">
                <!-- <li>
                    <a href="#">
                        <i class="menu-icon "></i>
                        <span class="menu-text"> <img src="logo.png" style="height:35px;margin-top:-10px"></span>
                    </a>

                    <b class="arrow"></b>
                </li> -->
                @foreach($menus as $menu)
                <li class="">
                    <a href="@if(count($menu->childs) == 0){{$menu->link}}@else#@endif" @if(count($menu->childs) > 0) class="dropdown-toggle" @endif>
                        <i class="menu-icon {{$menu->icon}}"></i>
                        <span class="menu-text">
                            {{$menu->title}}
                        </span>

                        @if(count($menu->childs))
                        <b class="arrow fa fa-angle-down"></b>
                        @endif

                    </a>
                    <b class="arrow"></b>
                    @if(count($menu->childs))
                    @include('layouts.appfront_menu',['childs'=>$menu->childs])
                    @endif
                </li>

                @endforeach


            </ul><!-- /.nav-list -->

            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
            </div>
        </div>

        <div class="main-content">

            @yield('content')
        </div><!-- /.main-content -->

        <div class="footer">
            <div class="footer-inner">
                <div class="footer-content">
                    <!-- <a href="#"><img style="width:425px" src="footer.png"></a> -->
                </div>
            </div>
        </div>

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->
    </div>  <!-- end app -->
    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="assets/js/jquery-2.1.4.min.js"></script>
    <!-- <![endif]-->

    <!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
    <![endif]-->

    <!-- <script src="assets/js/jquery-ui.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="assets/js/jquery.easypiechart.min.js"></script>
    <script src="assets/js/jquery.sparkline.index.min.js"></script> -->
    <!-- <script src="assets/js/jquery.flot.min.js"></script>
    <script src="assets/js/jquery.flot.pie.min.js"></script>
    <script src="assets/js/jquery.flot.resize.min.js"></script> -->
    <script src="assets/js/spin.js"></script>
    <script src="assets/js/jquery.easypiechart.min.js"></script>
    <script src="assets/js/jquery.gritter.min.js"></script>
    <script src="assets/js/ace-elements.min.js"></script>
    <!-- ace scripts -->

    <script src="assets/js/ace.min.js"></script>
    <!--<script src="assets/js/app.js"></script> -->
    <!-- <script src="assets/js/app.js"></script>       -->
    <script src="{{ mix('js/app.js?v=1.0.1') }}"></script>

</body>

</html>
@yield('script')
