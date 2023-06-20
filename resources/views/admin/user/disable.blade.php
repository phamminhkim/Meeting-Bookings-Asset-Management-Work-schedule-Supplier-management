@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="/admin/users">User</a>
            </li>
            <li class="active">Vô hiệu hóa hàng loạt</li>
        </ul><!-- /.breadcrumb -->

    </div>

    <div class="page-content">


        <div class="page-header">
            <h1>
                Quản lý User
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Vô hiệu hóa hàng loạt
                </small>
            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
               @include('partials.alert')
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header"></div>

                            <div class="card-body">
                                <form action="{{ url('admin/disable_user') }} "  method="GET">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="usersid" class="col-md-4 col-form-label text-md-right">{{ __('Danh sách mã nhân viên: ') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="usersid" type="textarea" class="form-control @error('usersid') is-invalid @enderror" name="usersid" value="{{ old('usersid') }}" required autocomplete="usersid" autofocus></textarea>
                                            @error('usersid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
								<div class="form-group row">
								 <label for="active" class="col-md-4 col-form-label text-md-right"></label>


								  <div class="col-sm-6">
									<div class="radio">
									  <label>
										<input name="active" value="1" checked checked type="radio" class="ace">
										<span class="lbl"> Hoạt động</span>
									  </label>
									  <label>
										<input name="active" value="0" type="radio" class="ace">
										<span class="lbl"> Vô hiệu hóa</span>
									  </label>
									</div>


								  </div>
								</div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">

                                             <a href="{{ url('admin/users') }}" class="btn btn-default">Quay lại</a>
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Cập nhật') }}
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.row -->
            </div><!-- /.page-content -->
        </div>
        <style>
            /* fix khoảng cách bên dưới table so với phân trang */

            .table {
                margin-bottom: 0px;
            }
        </style>

        @endsection
