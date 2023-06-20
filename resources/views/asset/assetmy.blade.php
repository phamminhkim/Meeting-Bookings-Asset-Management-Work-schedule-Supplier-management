@extends('layouts.appfrontnew')

@section('css')
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

@endsection
@section('content')

<div class="page-content">
    @if ($type == 'confirm' )
    <asset-confirm title="{{$menucurr->title}}" id={{$id}} ></asset-my>
    @else
    <asset-my title="{{$menucurr->title}}"  ></asset-my>
    @endif

@endsection
@section('script')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
 
</script>
@endsection
