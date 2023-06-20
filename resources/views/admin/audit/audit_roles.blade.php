@extends('layouts.appfrontnew')

@section('css')
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="plugins/toastr/toastr.min.css">

<!-- <link rel="stylesheet" href="plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" type="text/css"/> -->
@endsection
@section('content')

<div class="page-content">
       
       <audit-roles title="{{$menucurr->title}}"></audit-roles>

@endsection
@section('script')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- <script type="text/javascript" src="plugins/bootstrap-multiselect/js/bootstrap-multiselect.js"></script> -->
 <!-- page script -->
 
<script>
  // $(function () {
  //   $("#example1").DataTable({
  //     "responsive": true,
  //     "autoWidth": false,
  //   });
  //   $('#example2').DataTable({
  //     "paging": true,
  //     "lengthChange": false,
  //     "searching": false,
  //     "ordering": true,
  //     "info": true,
  //     "autoWidth": false,
  //     "responsive": true,
  //   });
  // });
</script>
@endsection