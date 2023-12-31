<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>App Thiên Long</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
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

    @yield('css')
</head>

<body class="no-skin">
    <div id="navbar" class="navbar navbar-default ace-save-state">
        <div class="navbar-container ace-save-state" id="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>
            </button>

            <div class="navbar-header pull-left">
                <a href="admin/home" class="navbar-brand">
                    <small>
                        <!-- <i class="fa fa-leaf"></i> -->
                        Trang Admin
                    </small>
                </a>
            </div>

            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">
                     <li>
                     <a >
                    <small>V.{{$version}}</small>
                    </a>
                     </li>

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
                        <li>
                            <a href="/">
                                <i class="ace-icon fa fa-cog"></i>
                                WebSite
                            </a>
                        </li>

                        <!-- <li>
                            <a href="profile.html">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li> -->

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
        <script type="text/javascript">
            try {
                ace.settings.loadState('main-container')
            } catch (e) {}
        </script>

        <div id="sidebar" class="sidebar                  responsive                    ace-save-state">
            <script type="text/javascript">
                try {
                    ace.settings.loadState('sidebar')
                } catch (e) {}
            </script>

            <!-- /.sidebar-shortcuts -->

            <ul class="nav nav-list">

                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-tachometer"></i>
                        <span class="menu-text">
                            Quản trị
                        </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu" style="display: block;">


                        <li class="">
                            <a href="/admin/users">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Danh sách User
                            </a>

                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="/admin/menus">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Danh sách Menu
                            </a>

                            <b class="arrow"></b>
                        </li>

                        <li class="">
                            <a href="/admin/roles">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Danh sách Roles
                            </a>

                            <b class="arrow"></b>
                        </li>

                        <li class="">
                            <a href="/admin/permissions">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Danh sách Permissions
                            </a>

                            <b class="arrow"></b>
                        </li>

						 <li class="">
                            <a href="/admin/companies">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Danh sách Công ty
                            </a>

                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="/admin/maillogs">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Mail logs
                            </a>

                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="/admin/configsyss">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Config sys
                            </a>

                            <b class="arrow"></b>
                        </li>

                    </ul>
                </li>

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
    <!-- ace scripts -->
    <!-- <script src="assets/js/ace-elements.min.js"></script> -->
    <script src="assets/js/ace.min.js"></script>


</body>

</html>
@yield('script')
