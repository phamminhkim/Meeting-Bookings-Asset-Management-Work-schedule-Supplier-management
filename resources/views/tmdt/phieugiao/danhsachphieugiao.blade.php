@extends('layouts.appfront')

@section('css')

@endsection
@section('content')

@include('layouts.breadcrumb')

<div class="page-content">
    <div class="page-header">
        <h1>
            Danh sách phiếu giao
            <!-- <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>

                </small> -->
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            @include('partials.alert')

            <div class="card bg-light mt-3">

                <div class="card-body">
                    <form action="{{ route('in_phieugiao') }}" method="POST" target="_blank">
                        @csrf
                        <div class="clearfix">
                            <div class="pull-left tableTools-container">
                                <div class="dt-buttons btn-overlap btn-group">

                                    <button class="btn btn-success"> <i class="fa fa-print bigger-110"> In Phiếu giao </i></button>
                                </div>
                            </div>
                        </div>


                        <!-- div.table-responsive -->

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                <!-- <div class="row">
                                        <div class="col-xs-6">
                                            <div class="dataTables_length" id="dynamic-table_length"><label>Display <select name="dynamic-table_length" aria-controls="dynamic-table" class="form-control input-sm">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select> records</label></div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div id="dynamic-table_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="dynamic-table"></label></div>
                                        </div>
                                    </div> -->
                                <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid" aria-describedby="dynamic-table_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="center sorting_disabled" rowspan="1" colspan="1" aria-label="
														">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace">
                                                    <span class="lbl"></span>
                                                </label>
                                            </th>
                                            <th>Thời gian</th>
                                            <th>Tổng số đơn</th>
                                            <th>Người tạo phiếu</th>
                                            <th>Đã In</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($dsphieugiao as $phieugiao)
                                        <tr role="row" class="odd">
                                            <td class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace" id="ids" name="ids[]" value="{{$phieugiao->id}}">
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>
                                                {{$phieugiao->created_at}}
                                            </td>
                                            <td>
                                                {{count(explode (',',$phieugiao->donghang_ids)) }}
                                            </td>
                                            <td> {{$phieugiao->username}}</td>
                                            <td>@if( $phieugiao->print !=null)X @endif </td>
                                            <!-- <td class="hidden-480"><a href="javascript:print_phieugiao('{{$phieugiao->id}}')"> <i class="fa fa-print bigger-110"> In </i></a></td> -->
                                        </tr>

                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><span class="select-item"></span><span class="select-item"></span></span></div>
                                    </div>

                                    <div class="col-xs-6">

                                        <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                            {{ $dsphieugiao->links() }}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /.page-content -->
    </div>
</div>


<style>
    /* fix khoảng cách bên dưới table so với phân trang */

    .table {
        margin-bottom: 0px;
    }
</style>
@endsection
@section('script')


<!-- <script>

            printDivCSS = new String('<link href="myprintstyle.css" rel="stylesheet" type="text/css">')

            function printDiv(divId) {
                window.frames["print_frame"].document.body.innerHTML = document.getElementById(divId).innerHTML;
                window.frames["print_frame"].window.focus();
                window.frames["print_frame"].window.print();
            }
        </script>

        <script>
            $('thead input[type="checkbox"]').click(function(){
                $('input[type="checkbox"]').prop('checked', $('thead input[type="checkbox"]').prop('checked'));
            });

            function print_phieugiao(id){
                    alert("heelo"+id);
            }
        </script> -->
@endsection