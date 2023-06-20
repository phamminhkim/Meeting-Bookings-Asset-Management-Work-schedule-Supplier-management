@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">

	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
  <ul class="breadcrumb">
    <li>
      <i class="ace-icon fa fa-home home-icon"></i>
      <a href="admin/admin_dashboard">Admin</a>
    </li>
    <li>

      <a href="admin/companies">{{ __('form.company') }}</a>
    </li>
    <li class="active">{{ __('form.create') }}</li>
  </ul><!-- /.breadcrumb -->

</div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{ __('form.company') }}
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    {{ __('form.create') }}
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
                                <form method="POST" action="{{ route('adminconfigsyss.store') }}">
                                    @csrf

									<div class="form-group row">
                                            <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('form.code') }}</label>
                                            <div class="col-md-6">
                                                <input id="id" type="text" class="form-control @error('id') is-invalid @enderror" name="id" value="{{ old('id') }}" required autocomplete="id" autofocus>

                                                @error('id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>

                                    <div class="form-group row">
                                            <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('form.value') }}</label>
                                            <div class="col-md-6">
                                                <input id="value" type="text" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autocomplete="value" autofocus>
                                                @error('value')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>



								<div class="space-4"></div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">

                                             <a href="{{ url()->previous() }}" class="btn btn-default">{{__('form.status')}}</a>
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('form.save') }}
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
