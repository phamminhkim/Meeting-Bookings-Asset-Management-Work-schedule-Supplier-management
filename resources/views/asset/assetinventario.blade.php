@extends('layouts.appfrontnew')

@section('css')
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

@endsection
@section('content')

<div class="page-content">
    @if($type == 'product')
    <inventario-product title="{{$menucurr->title}}" notification_id="{{$notification_id}}" id={{$id}} time="{{$time}}" ></inventario-product>
    @elseif ($type == 'detail')
    <inventario-detail title="{{$menucurr->title}} " user_id={{$user_id}} ware_house={{$ware_house}} inven={{$inven}}  time="{{$time}}" ></inventario-detail>
    @elseif ($type == 'success')
    <inventario-success title="{{$menucurr->title}} " notification_id="{{$notification_id}}" id={{$id}} time="{{$time}}" ></inventario-success>
    @else
    <asset-inventario title="{{$menucurr->title}} " ></asset-inventario>
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
