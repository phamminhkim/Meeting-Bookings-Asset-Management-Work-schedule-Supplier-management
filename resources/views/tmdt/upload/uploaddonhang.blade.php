@extends('layouts.appfrontnew')

@section('content')


    <div class="page-content">


        <div class="page-header">
            <h4>
            Upload Đơn Hàng
            </h4>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')

                <div class="card bg-light mt-3">
                    <div class="card-header">
                        Import Excel
                    </div>
                    <div class="card-body">
                        <form action="{{ route('importdonhang') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="select_file" class="form-control">
                            <br>
                            <button class="btn btn-success">Import Đơn Hàng</button>

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
