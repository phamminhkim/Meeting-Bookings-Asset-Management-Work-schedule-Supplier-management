@extends('layouts.appfront')

@section('content')

    @include('layouts.breadcrumb')

    <div class="page-content">

        <div class="page-header">
            <h1>
                 Cập Nhật Mã Vận Chuyển
            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')


                <div class="card bg-light mt-3">
                    <div class="card-header">
                        Import Excel
                    </div>
                    <div class="card-body">
                        <form action="{{ route('importmavanchuyen') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="select_file" class="form-control">
                            <br>
                            <button class="btn btn-success">Import Mã Vận Chuyển</button>

                        </form>
                    </div>
                </div>

                <div class="hr hr22 hr-dotted"></div>

                <!-- /.row -->
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