@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="admin/home">Admin</a>
            </li>
            <li class="active">{{__('form.user')}}</li>
        </ul><!-- /.breadcrumb -->

        @include('admin.user.search')
    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Thông tin liên quan : {{__('form.user')}}

            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')

                <div class="row">

                    <div class="col-xs-10">
                        <table class=" table table-bordered table-hover"">
                            <thead>
                                <th>MSNV</th>
                                <th>Tên nhân viên</th>
                                <th>Phòng ban</th>
                                <th>Công ty</th>
                                <th>Mã nhóm</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </thead>
                            <tbody>
                                @foreach($groups as $g)
                                <tr>

                            <td>
                                {{$user->username}}
                            </td>
                            <td>
                                {{$user->name}}
                            </td>
                            <td>
                                {{$user->department_id}}
                            </td>
                            <td>
                                {{$g->company_id}}
                            </td>

                            <td>
                                {{$g->name}}
                            </td>
							 <td>
							@if($g->active == '1') on
							@else
							off
							@endif
							</td>
                            <td>
                                <a href="category/group?type=assign&id={{$g->id}}"><button class="btn btn-link" style="float:left;"> <i class="ace-icon fa fa-eye blue bigger-130"></i></button></a>
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div><!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.page-content -->
        </div>
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
<script>

</script>
@endsection
