@extends('layouts.appfrontnew')

@section('css')


<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
@endsection
@section('content')

<div class="page-content">


        @if($type == 'add')
          <docus-create title="{{$menucurr->title}}" 
          ></docus-create>
        @elseif($type == 'edit')
            <docus-create title="{{$menucurr->title}}"  id={{$id}}
             
            ></docus-create>
        @elseif($type == 'view')
        <docus-details title="{{$menucurr->title}}" notification_id="{{$notification_id}}"  id={{$id}}></docus-details>
       
        @else
        <docus-good title="{{$menucurr->title}}"></docus-good>
        @endif
</div>

@endsection
@section('script')

<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="js/ultils.js"></script>
<script src="plugins/jquery-number/jquery.number.min.js"></script>
<script src="js/autonumeric.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->
 <!-- page script -->
 <script>

try {

  const autoNumericOptionsEuro = {
    digitGroupSeparator        : '.',
    decimalCharacter           : ',',
    minimumValue: 0,

    };
    const autoNumericOptionsEuro_negative = {
    digitGroupSeparator        : '.',
    decimalCharacter           : ',',


    };
  new AutoNumeric('#amount',autoNumericOptionsEuro);
  new AutoNumeric('#amount_exchanged', autoNumericOptionsEuro);
  new AutoNumeric('#exchange_rate',autoNumericOptionsEuro);
  new AutoNumeric('#voucher_amount',autoNumericOptionsEuro_negative);
   new AutoNumeric('#amount_out_budget',autoNumericOptionsEuro);
   new AutoNumeric('#amount_out_exchanged',autoNumericOptionsEuro);
} catch (error) {

}



 $(function () {

  $('.select2').select2();



    // $("#example1").DataTable({
    //   "responsive": true,
    //   "autoWidth": false,
    // });
    // $('#example2').DataTable({
    //   "paging": false,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": false,
    //   "autoWidth": false,
    //   "responsive": true,
    // });
  });
</script>

@endsection
