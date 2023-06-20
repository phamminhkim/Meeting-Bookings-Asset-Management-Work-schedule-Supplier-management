@extends('layouts.appfrontnew')

@section('content')
         <div class="content-header">
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">
                        <!-- <i class="nav-icon fas fa-file-contract"></i> -->
                        Dashboard
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
            <main-dashboard></main-dashboard>
        </div>



@endsection
