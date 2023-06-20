@extends('layouts.appfrontnew')

@section('css')
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

@endsection
@section('content')

<div class="page-content">
    @if($type == 'addchange')
    <add-change title="{{$menucurr->title}}"></add-change>
    @elseif($type == 'edit')
    <add-change title="{{$menucurr->title}}" id={{$id}} ></add-change>
    @elseif($type == 'trichxuat')
    <detail-change title="{{$menucurr->title}}"></detail-change>
    @elseif($type == 'repair')
    <add-change-repair  title="{{$menucurr->title}}"> </add-change-repair>
    @else
    <asset-change title="{{$menucurr->title}} " ></asset-change>
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
