<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Duyệt giá</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{{ App::getLocale() }}"/>
    <base href="{{asset('')}}" />

   <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
   <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

  <link href="css/app.css" rel="stylesheet">
  <link href="css/print.css" rel="stylesheet">



      <!-- flag-icon-css -->
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css">
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
   <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

  <link href="css/app.css" rel="stylesheet">
  <script>

try {
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        'access_token' => $access_token,
        'expand_menu_value' =>$expand_menu_value,
    ]) !!};
    }
    catch(err) {

    }

    </script>
 <style scoped>
        .content{
            background-color: white;
        }
        .dot_underline {
            border-bottom: 1px dotted #cccccc;
            display: inline-table;
            height: 1px;
            width: 70%;
            }
            .dot_underline_custom {
            border-bottom: 1px dotted #cccccc;
            display: inline-table;
            height: 1px;

            }
            .no_ot_underline_custom {

            display: inline-table;
            height: 1px;

            }
        .approved{
          display: block;
          border:1px solid red;
          width:170px;
          height:54px;
          margin:auto;
          background:url('img/check.png') no-repeat center center;
        }


 </style>
</head>

<body>

  <div class="page-header" style="text-align: center;z-index: 1000;">
  <button type="button" onclick="window.history.back()" style="border: 1px solid gray;"  title="Back"    >
      <i class="fas fa-arrow-circle-left"></i>
    </button>
    <button type="button" style="border: 1px solid gray;"  title="print"  onClick="window.print()" >
      <i class="fas fa-print"> </i>
    </button>
  </div>

  <div class="page-footer">
    <div class="row">
    <div class="col-3">
      <span> @yield('footer_left') </span>
     </div>
      <div class="col-6">
        <div  style="text-align: center;">
          <span> @yield('footer_center')</span>
      </div>
      </div>
     </div>
  </div>

  <table style="margin: auto;width:50%">

    <thead>
      <tr>
        <td>
          <!--place holder for the fixed-position header-->
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody id="app" >
      <tr >
        <td >
          <!--*** CONTENT GOES HERE ***-->

          <div class="page" style="font-size: 16px;font-family: 'Times New Roman', Times, serif;" >

            <div class="row ml-1 mr-1" style="border:1px solid gray;width:280mm">
                <div class="col-xs-2 col-sm-2 col-md-2" style="border-right: 1px solid gray;padding-top:22px;padding-bottom:22px" >
                    <img src="img/logo_tlg.jpg" style="max-width:150px;max-height:46px;display:block;width:auto;height:auto;">
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: center;padding-left:17px;padding-top:30px">
                    <span  ><h4><strong> @yield('header_title')</strong> </h4></span>
                </div>

                <div class="col-xs-2 col-sm-2 col-md-2" style="border-left: 1px solid gray;padding-left:17px;padding-top:35px">
                <i><strong >Số: <div style="display: inline;" class="dot_underline">
                    @yield('header_num')
                    </div> </strong> </i>
                </div>
                </div>
                <br/>
               @yield('body_content')

          </div>
        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td>
          <!--place holder for the fixed-position footer-->
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>

</body>

</html>
<script src="js/ultils.js"></script>
 <!-- jQuery -->

<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<script src="js/app.js"></script>
