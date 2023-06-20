<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Phiếu Car</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <base href="{{asset('')}}" />
     
   <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
   <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
 
  <link href="css/app.css" rel="stylesheet">
  <link href="css/print_car.css" rel="stylesheet">
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

   <table style="margin: auto;width:50%;">
    <thead >
      <tr >
        <td >
          <!--place holder for the fixed-position header-->
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody  >
      <tr>
        <td>
          <!--*** CONTENT GOES HERE ***-->
         
          <div class="page" style="font-family: 'Times New Roman', Times, serif;" >
            
            <div style="width:280mm;">
				<table class="table table-bordered table-hover dataTable dtr-inline collapsed">
				<tr>
				<td > <img src="img/logo_print.png"></td>
				<td colspan="2" style="text-align:center;"><b >PHIẾU CAR</b></td>
				<td >Số:  @yield('header_num')</td>
				@yield('body_content')
                </div>
          </div>
        </td>
      </tr>
    </tbody>
    <tfoot >
      <tr >
        <td >
          <!--place holder for the fixed-position footer-->
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>

</body>
</html>