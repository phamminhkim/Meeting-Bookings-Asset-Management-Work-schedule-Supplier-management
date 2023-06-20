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

<body>
    <div class="wrapper" id="app">

        <div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
       
            </section>
        
        </div>
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
