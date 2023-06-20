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

      <a href="admin/configsyss">{{ __('form.configsys') }}</a>
    </li>
    <li class="active">{{ __('form.update') }}</li>
  </ul><!-- /.breadcrumb -->

</div>

    <div class="page-content">


        <div class="page-header">
            <h1>
                {{ __('form.configsys') }}: {{$configsys->id}}

            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')
                <div class="row">

                    <div class="col-xs-10">
                        <div class="card">
                            <div class="card-header">

                            </div>
                            <div class="card-body">

                                <form action="{{ route('adminconfigsyss.update',$configsys) }}" method="POST">

									 <div class="form-group row">
                                        <label for="id" class="col-md-4 col-form-label text-md-right">{{__('form.code')}}</label>

                                        <div class="col-md-6">
                                            <input id="id" type="text" class="form-control @error('id') is-invalid @enderror" name="id" readonly value="{{$configsys->id }}" required autofocus>

                                            @error('id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="value" class="col-md-4 col-form-label text-md-right">{{__('form.value')}}</label>

                                        <div class="col-md-6">
                                            <input id="value" type="text" class="form-control @error('value') is-invalid @enderror" name="value" value="{{$configsys->value }}" required autofocus>

                                            @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="form-group row">

                                        <div class="col-md-6">
                                            @csrf
                                            {{method_field('PUT')}}

                                            <a href="{{ url()->previous() }}" class="btn btn-default">{{__('form.back')}}</a>
                                            <button type="submit" class="btn btn-primary">{{__('form.update')}}</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- /.col -->
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
    $('#doimatkhau').click(function() {

        if ($(this).is(':checked')) {
            $('#password').removeAttr('readonly');
        } else {
            $('#password').attr('readonly', true);
        }

    });
</script>
@endsection
