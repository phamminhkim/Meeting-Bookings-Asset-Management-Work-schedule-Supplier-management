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
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
@endsection
@section('content')
    <div class="page-content">
        <div class="row" style="padding: 30px">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <search-textbox />
            </div>
            <div class="col-md-1"></div>
        </div>
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
    <!-- <script src="plugins/jquery-number/jquery.number.min.js"></script> -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="js/autonumeric.js"></script>
    <!-- page script -->
    <script>
        try {
            const autoNumericOptionsEuro = {
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                minimumValue: 0,

            };
            new AutoNumeric('#amount', autoNumericOptionsEuro);
            new AutoNumeric('#term_amount', autoNumericOptionsEuro);

        } catch (error) {

        }

        $(function() {
            // Summernote
            $('.textarea').summernote({
                toolbar: true,
                height: 80,
                disableDragAndDrop: true,

            })
        })
        $(".textarea").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        function auto_grow(element) {

            //element.style.height = "auto";
            // element.style.height = (element.scrollHeight)+"px";
            // console.log("abc="+element.scrollHeight);
        }

        //  $('#amount').number( true, 0 );
        //  $('#term_amount').number( true, 0 );


        $(function() {

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
