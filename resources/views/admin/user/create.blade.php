@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="/admin/users">User</a>
            </li>
            <li class="active">Tạo mới User</li>
        </ul><!-- /.breadcrumb -->

        <!-- <div class="nav-search" id="nav-search">
            <form class="form-search">
                <span class="input-icon">
                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                    <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
            </form>
        </div> -->
        <!-- /.nav-search -->
    </div>

    <div class="page-content">
        <!-- <div class="ace-settings-container" id="ace-settings-container">
            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="ace-icon fa fa-cog bigger-130"></i>
            </div>

            <div class="ace-settings-box clearfix" id="ace-settings-box">
                <div class="pull-left width-50">
                    <div class="ace-settings-item">
                        <div class="pull-left">
                            <select id="skin-colorpicker" class="hide">
                                <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                            </select>
                        </div>
                        <span>&nbsp; Choose Skin</span>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
                        <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
                        <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
                        <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                        <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
                        <label class="lbl" for="ace-settings-add-container">
                            Inside
                            <b>.container</b>
                        </label>
                    </div>
                </div>

                <div class="pull-left width-50">
                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                        <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                        <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                    </div>

                    <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                        <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- /.ace-settings-container -->

        <div class="page-header">
            <h1>
                Quản lý User
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Tạo mới User
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
                                <form method="POST" action="{{ route('adminusers.store') }}">
                                    @csrf

                                    <div class="form-group row">
                                            <label for="company_id" class="col-md-4 col-form-label text-md-right">{{ __('Mã công ty') }}</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="company_id" id="company_id"  autocomplete="company_id" autofocus>
                                                <option value="">---</option>
                                                    @foreach($company as $com)
                                                    <option value="{{$com->id}}">{{$com->name}}</option>
                                                    @endforeach
                                                </select>

                                                @error('company_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="company_id" class="col-md-4 col-form-label text-md-right">{{ __('Bộ phận') }}</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="department_id" id="department_id"  autocomplete="department_id" autofocus>

                                                    <option value="">---</option>

                                                </select>

                                                @error('department_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="manager" class="col-md-4 col-form-label text-md-right">{{ __('Quản lý trực tiếp') }}</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="manager" id="manager"  autocomplete="manager" autofocus>
                                                <option value="">---</option>
                                                    @foreach($list_user as $u)
                                                    <option value="{{$u->id}}">{{$u->name}}</option>
                                                    @endforeach
                                                </select>

                                                @error('manager')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="sloc_id" class="col-md-4 col-form-label text-md-right">{{ __('Mã kho') }}</label>
                                            <div class="col-md-6">
                                            <select class="form-control" name="sloc_id" id="sloc_id"  autocomplete="sloc_id" autofocus>
                                            <option value="">---</option>
                                            @foreach($sloc as $slo)
                                                    <option value="{{$slo->id}}">{{$slo->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('sloc_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>
                                    <div class="form-group row">
                                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Tên đầy đủ') }}</label>
                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="username" autofocus>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>
                                    <div class="form-group row">
                                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Mã nhân viên') }}</label>
                                            <div class="col-md-6">
                                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                                @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>
                                    <div class="form-group row">
                                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Giới tính') }}</label>
                                            <div class="col-md-6">
                                            <select class="form-control" name="gender" id="gender"  autocomplete="gender" autofocus>
                                            <option value="">---</option>
                                                <option value="0">Nữ</option>
                                                <option value="1">Nam</option>
                                                </select>
                                                @error('gender')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $gender }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>
								<div class="form-group row">
								 <label for="active" class="col-md-4 col-form-label text-md-right"> Trạng thái</label>


								  <div class="col-sm-6">
									<div class="radio">
									  <label>
										<input name="active" value="1" checked checked type="radio" class="ace">
										<span class="lbl"> Đang hoạt động</span>
									  </label>
									  <label>
										<input name="active" value="0" type="radio" class="ace">
										<span class="lbl"> Ngưng hoạt động</span>
									  </label>
									</div>


								  </div>
								</div>

                                <div class="form-group row">
                                    <label for="notify" class="col-md-4 col-form-label text-md-right"> </label>


                                     <div class="col-sm-6">
                                       <div class="radio">
                                         <label>
                                           <input name="notify" value="1" checked checked type="checkbox" class="ace">
                                           <span class="lbl">Gửi thông báo qua Email</span>
                                         </label>
                                       </div>


                                     </div>
                                   </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">

                                             <a href="{{ url()->previous() }}" class="btn btn-default">Quay lại</a>
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Lưu') }}
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

        @section('script')
        <script type="text/javascript">
            $("#company_id").change(function(){
                $.ajax({
                    url: "admin/get_by_company?company_id=" + $(this).val(),
                    method: 'GET',
                    success: function(data) {
                        $('#department_id').html(data.html);
                    }
                });
            });
        </script>
        @endsection
