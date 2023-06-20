@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="center">
                <h1>
                    <!-- <i class="ace-icon fa fa-leaf green"></i> -->
                    <img src="logo.png" alt="">

                </h1>
                <!-- <h4 class="blue" id="id-company-text">APP ỨNG DỤNG</h4> -->
                <!-- <span class="grey" id="id-text2">APP ỨNG DỤNG </span> -->
                <span class="red"></span>

            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title mt-2" >
                   <span style="font-size: 14px;">{{__('form.login')}}</span> 
                    </div>
                    
                    <div class="card-tools">
                      <strong> PHẦM MỀM E-REQUEST </strong> <br>
                     <strong> Tạo và duyệt yêu cầu online</strong>
                 </div>
                </div>
               
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{__('form.employee_code')}}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{__('form.password')}}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        Nhớ mật khẩu
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="form-group row">
                            <label for="language" class="col-md-4 col-form-label text-md-right">{{__('form.language')}}</label>

                            <div class="col-md-6">
                               <select name="language" id="language" class="form-control form-select">
                               <option value="vn" >Tiếng Việt</option>
                                    <option value="en" >English</option>           
                                 </select>
                            </div>
                        </div> -->
                         

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                {{__('form.login')}}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif -->
                            </div>
                        </div>
                    </form>
                    <!-- <changlanguage></changlanguage> -->
                   
                   @include('hotline.infologin')
                </div>
             
                <div style="text-align:center;">
                    <img src="footer.png" width="80%" style="padding:10px;">
                </div>
            </div>
        </div>
    </div> @endsection
    @section('script')
        <script>
            $('#language').val( document.head.querySelector('meta[name="locale"]').content);
        </script>
    @endsection