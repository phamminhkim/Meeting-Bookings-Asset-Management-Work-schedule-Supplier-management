@extends('payment.report.print_payment_temp')

@section('header_title')
    PHIẾU ĐỀ NGHỊ TẠM ỨNG
@endsection

@section('header_num')
    {{$payrequest->serial_num}}
@endsection

@section('footer_left')
 TCKTP01F03 – 28/05/20
@endsection

@section('footer_center')
Thời hạn lưu trữ: theo quy định của kế toán
@endsection

@section('body_content')

              <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6 ">
                  Họ tên người đề nghị:   <div class="col-8 dot_underline"><b >{{$payrequest->user->name}}</b>  </div>
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3">
                      MSNV:  <div class="dot_underline"><b>{{$payrequest->user->username}}</b></div>
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3">

                      Khối/BP: <b class="dot_underline col-7">{{$payrequest->department->code}}/{{$payrequest->group->name}}</b>
                  </div>
              </div>
              <br/>


              <div class="row">
                  <div class="col-xs-5 col-sm-5 col-md-5 ">
                  Đề nghị tạm ứng số tiền: <div class="col-5 dot_underline"><b >{{number_format($payrequest->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}}</b>  </div>
                  </div>
                  <div class="col-xs-7 col-sm-7 col-md-7">
                    ,Bằng chữ: <div class="col-11 dot_underline"><b> {{$info['amount_word']}}</b></div>
                  </div>

              </div>
              <br>

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Lý do: <div class="dot_underline" style="width:91%"><strong> {{$payrequest->content}} </strong></div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Thời hạn thanh toán: <div class="dot_underline" style="width:81%">
                  @if($payrequest->finish_date)
                    <strong> {{ \Carbon\Carbon::parse($payrequest->finish_date)->format('d/m/Y')}} </strong>
                   @endif

                </div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">

                  </div>
              </div>
              <br/>
              <div class="row">
              <div class="col" style="text-align: center;">
                  <!-- <i>Ngày {{ \Carbon\Carbon::parse($payrequest->created_at)->format('d')}} tháng {{ \Carbon\Carbon::parse($payrequest->created_at)->format('m')}} năm {{ \Carbon\Carbon::parse($payrequest->created_at)->format('Y')}} </i><br> -->
                  <br><p><b  >Người đề nghị</b></p>
                  @if( $payrequest->send_date )
                  <div class="approved">
                    <span>Đã ký: </span><br>
                    <span >{{ \Carbon\Carbon::parse($payrequest->send_date)->format('d/m/Y H:i:s')}} </span>
                  </div>
                  @endif

                  <br>

                  <p><b  >{{$payrequest->user->name}}</b></p>
                  </div>

                    <!-- list chữ ký -->
                    @if( count($signs) > 0 )

                        @for  ($i = 0; $i <= count($signs) -1;$i++ )

                            <div class="col" style="text-align: center;">
                            <br><p><b>
                                Người duyệt

                            </b></p>
                            @if( isset($signs[$i]) )
                                <div class="approved">
                                    <span>Đã duyệt: </span><br>
                                    <span >{{ \Carbon\Carbon::parse($signs[$i]['checkout'])->format('d/m/Y H:i:s')}} </span>
                                </div>
                                <!-- <i class="fas fa-check-circle text-success"></i> -->
                                <br>
                                <p><b >{{$signs[$i]['name']}}</b></p>
                            @endif
                            </div>
                        @endfor

                        @else
                        @for  ($i = 0; $i <= count($signviews) -1;$i++ )
                        <div class="col" style="text-align: center;">
                        <br><p><b>
                            Người duyệt

                        </b></p>
                        @if( isset($signviews[$i]) )

                            <br>
                            <p><b >{{$signviews[$i]['name']}}</b></p>
                        @endif
                        </div>
                        @endfor
                        @endif
                    <!-- end list chữ ký -->

              </div>
              <div class="row">
                @include('payment.report.content_File_List')
              </div>
              <div class="row">
                @include('payment.report.content_Reminder_List')
              </div>
@endsection
