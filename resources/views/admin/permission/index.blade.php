@extends('layouts.appadmin')

@section('content')

<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="admin/admin_dashboard">Admin</a>
        </li>
        <li class="active">Permissions</li>
    </ul><!-- /.breadcrumb -->
    @include('admin.permission.search')
</div>

<div class="page-content">
    <div class="page-header">
        <h1>
            Danh sách Permissions
            <!-- <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>

                </small> -->
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">


            @include('partials.alert')

            <div class="card bg-light mt-3">

                <div class="clearfix">
                    <div class="pull-left tableTools-container">
                        <div class="dt-buttons btn-overlap btn-group">

                            <a href="{{ route('adminpermissions.create') }}" class="btn btn-success"> <i class=" fa fa-pencil-square-o bigger-110"> Tạo mới </i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

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
                                    <!-- <th class="center sorting_disabled" rowspan="1" colspan="1" aria-label="
														">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" class="ace">
                                                        <span class="lbl"></span>
                                                    </label>
                                                </th> -->
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Mô tả</th>
                                    <th>Ngày tạo</th>

                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($permissions as $permission)
                                <tr role="row" class="odd">
                                    <td>
                                        {{$permission->id}}
                                    </td>
                                    <td>
                                        {{$permission->name}}
                                    </td>
                                    <td>
                                        {{$permission->description}}
                                    </td>
                                    <td>
                                        {{$permission->created_at}}
                                    </td>
                                    <td>
                                        <a class="green" href="{{route('adminpermissions.edit',$permission->id)}}">
                                            <i style="float:left;margin-right:15px;" class=" ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <form action="{{route('adminpermissions.destroy',$permission->id)}}" method="POST">
                                            @csrf
                                            {{method_field('DELETE')}}
                                            <button type="submit" onclick="return confirm('Bạn có muốn xóa không?');" class="btn-mylink red"><i class=" fa fa-trash bigger-120"></i></button>
                                        </form>



                                    </td>
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
                                    {{ $permissions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div><!-- /.page-content -->
    </div>
</div>
@endsection