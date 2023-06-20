@extends('layouts.appfrontnew')

@section('content')
         <div class="content-header">
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">
                        <!-- <i class="nav-icon fas fa-file-contract"></i> -->
                        Hướng dẫn sử dụng
                        </h5>
                    </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                  <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                </div>

            </div>
         </div>
        </div>
      </div>
        <div class="col-md-12"  >
            <div class="card">                <!-- <div class="card-header">{{ __('Dashboard') }}</div> -->

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h5><a href="tutorial/Huong_dan_su_dung_eRequestV1.pptx">Hướng dẫn sử dụng eRequest V1</a></h5>
                    

                </div>
            </div>
        </div>



@endsection
@section('script')
 
@endsection
