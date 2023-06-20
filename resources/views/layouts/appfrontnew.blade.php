<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Thiên Long Digital</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- No Cache -->
    <!-- <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0"/> -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{{ App::getLocale() }}" />
    <base href="{{ asset('') }}" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- flag-icon-css -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

    <!-- <link href="css/app.css?v=1.0.5" rel="stylesheet"> -->


    <script>
        try {
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
                'access_token' => $access_token,
                'current_user' => $user,
                'expand_menu_value' => $expand_menu_value,
            ]) !!};
        } catch (err) {

        }
    </script>
    <!-- Scripts -->

    <link href="css/app.css?v={{ $version }}" rel="stylesheet">
    <style>
        .dot {

            height: 6px;
            width: 6px;
            background-color: #dee2e6;
            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
            margin-right: 10px;

        }

        .active .dot {
            height: 6px;
            width: 6px;

            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
            margin-right: 8px;
            background-color: #3C8DBC;
        }

        .main-sidebar.sidebar-light-lightblue {

            background-color: white;
        }

        .main-header {
            border-bottom: 1px solid #c6c8c9;

        }

        .bosung-menu {
            background-color: #3C8DBC !important;
            color: white !important;
        }

        .show-hotline {
            display: block;
        }

        .hide-hotline {
            display: none;
        }

        /* canh chỉnh logo */
        /* .brand-link .brand-image {
          float: left;
          line-height: 0.8;
          margin-left: 0.8rem;
          margin-right: 0.5rem;
          margin-top: -2px;
          max-height: 40px;
          width: auto;
      } */
        .brand-link .brand-image {
            float: left;
            /* line-height: 0.8;
            margin-left: 0.8rem;
            margin-right: 0.5rem; */
            margin-top: -14px;
            margin-bottom: 52px;
            max-height: 60px;
            width: auto;
        }

        .btn-function_top {
            border-radius: 50%;
            background-color: #fff;
            border-color: #fff;
            box-shadow: 1px 1px 10px #aaa !important;
            color: black !important;
            padding-top: 12px;
            width: 50px;
            height: 50px;
        }


        /* .os-scrollbar .os-scrollbar-horizontal .os-scrollbar-unusable .os-scrollbar-auto-hidden:hover{
        background: gray ;
      }
      .os-dragging .os-scrollbar-vertical{
        background: gray ;
      }
      .os-scrollbar .os-scrollbar-vertical{
        background: gray ;
      } */
        .os-theme-light .os-scrollbar .os-scrollbar-track .os-scrollbar-handle:hover {
            background: gray;
        }

        /* .main-sidebar.sidebar-light-lightblue{
        background-color: #34829a24;;

      } */
        /* .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link{
        background-color: #b0b3b6;
      } */
    </style>
    @yield('css')
</head>

<body class="{{ $expand_menu }}">
    <div class="wrapper" id="app">

        <!-- Navbar main-sidebar elevation-4 sidebar-light-lightblue -->
        {{-- <nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue "  > --}}
        <nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" onclick="showHideLeftMenu()" href="#"
                        role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">{{ __('menu.home') }}</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/help" class="nav-link">{{ __('menu.help') }}</a>
                </li>
            </ul>


            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

               
                <!-- Language Dropdown Menu -->
                <language-menu-top></language-menu-top>

                
                <notification-new v-bind:count="{{ count(auth()->user()->unreadNotifications) }}"
                  v-bind:userid="'{{ auth()->user()->id }}'"
                  v-bind:unreads="{{ auth()->user()->unreadNotifications->take(5) }}">
              </notification-new>
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }} -
                            {{ Auth::user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                        style="left: inherit; right: -8px; border: 0; margin-top: 8px">
                        <!-- User image -->
                        <li class="user-header bg-primary" style="background-color: #3c8dbc !important;">
                            <img src="{{ Auth::user()->avatar }}" class="img-circle elevation-2" alt="User Image">

                            <p>
                                {{ Auth::user()->name }} - {{ Auth::user()->username }}
                                <small>Thành viên từ {{ Auth::user()->created_at }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        @can('manage-systems')
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-12 text-center">

                                        <a href="{{ url('/admin/users') }}">
                                            <i class="fas fa fa-cog mr-2"></i>
                                            {{ __('form.manegement_admin') }}
                                        </a>



                                    </div>

                                </div>
                                <!-- /.row -->
                            </li>
                        @endcan
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="{{ route('profile.index') }}" class="btn btn-default btn-flat">
                                {{ __('form.profile') }}</a>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn btn-default btn-flat float-right">{{ __('form.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-light-lightblue">
            {{-- main-sidebar sidebar-light-lightblue  --}}
            <!-- Brand Logo -->
            <a href="#" class="brand-link logo-switch"
                style="background-color: white;padding: 12.2px; border-bottom: 2px solid white;">

                <img src="img/logo_onethienlong.png" style="padding-left: 40px;" alt="TLG Logo large"
                    class="brand-image  logo-xl">
                <img src="img/logo_onethienlong.png" alt="TLG Logo small" class="brand-image-xl logo-xs"
                    style="left:10px">
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <side-menu 
                        v-bind:menus="{{ $menus }}"
                        v-bind:menu_current_root="{{ json_encode($menucurr_root) }}"
                        v-bind:menu_current="{{ json_encode($menucurr) }}">
                    </side-menu>
                    <hotline-contact></hotline-contact>

                </nav>

                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- <div id="back-to-top"   class="btn btn-default back-to-top btn-function_top" role="button" aria-label="Edit">
      <i   class="fas fa-edit"></i>
    </div> -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- <footer class="main-footer">
    <strong>Copyright &copy; 2020-2021 <a href="#">Portal TLG</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer> -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <script src="js/ultils.js"></script>
    <!-- jQuery -->

    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <script src="js/app.js?v={{ $version }}"></script>
    <!-- <script src="{{ mix('/js/app.js') }}"></script> -->
    <script>
        // if(window.Laravel.expand_menu == 1){
        //     $('body').addClass('sidebar-collapse');
        //   }else{
        //     $('body').removeClass('sidebar-collapse');
        //   }
        var flag = document.head.querySelector('meta[name="locale"]').content;
        if (flag == 'vn') {
            flag = "flag-icon flag-icon-vn ";

        } else if (flag == 'en') {
            flag = "flag-icon flag-icon-us";
        }
        //sau khi load xong các files thì thêm class layout-fixed nhằm không hiển thị crollbar của hệ thống
        // mà chỉ hiển thị scrollbar custom
        document.onreadystatechange = () => {
            if (document.readyState == "complete") {
                $("body").addClass("layout-fixed");
            }
        }
        $("#curr_flag").attr('class', flag);
        //default loading
        if (window.Laravel.expand_menu_value == 1) {
            $('#menu_hotline').removeClass("show-hotline");
            $('#menu_hotline').addClass("hide-hotline");
        } else {
            $('#menu_hotline').addClass("show-hotline");;
            $('#menu_hotline').removeClass("hide-hotline")
        }

        function goBack() {
            window.history.back();
        }

        function showHideLeftMenu() {
            // alert(window.Laravel.expand_menu);
            if (window.Laravel.expand_menu_value == 1) {
                $('body').addClass('sidebar-collapse');
                //$('#menu_hotline').removeClass("show-hotline")
                $('#menu_hotline').addClass("show-hotline");;
                $('#menu_hotline').removeClass("hide-hotline")
                window.Laravel.expand_menu_value = 0;

            } else {
                $('body').removeClass('sidebar-collapse');
                $('#menu_hotline').removeClass("show-hotline");
                $('#menu_hotline').addClass("hide-hotline");
                window.Laravel.expand_menu_value = 1;

            }
            var data = {
                'code': 'expand_menu',
                'value': window.Laravel.expand_menu_value
            };
            var page_url = '/api/user_config';

            $.ajax({
                type: 'post',
                url: page_url,
                data: data,
                dataType: 'json',
                headers: {
                    "Authorization": 'Bearer ' + window.Laravel.access_token,
                },
                success: callback,
            });


        }

        function callback(data, status) {
            //Chưa xử lý
        }
    </script>
</body>

</html>
@yield('script')
