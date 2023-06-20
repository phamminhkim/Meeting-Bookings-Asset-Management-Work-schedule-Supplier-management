@extends('payment.report.print_payment_temp')

@section('header_title')
    PHIẾU QUYẾT TOÁN TẠM ỨNG
@endsection

@section('header_num')
    {{$payrequest->serial_num}}
@endsection

@section('footer_left')
 TCKTP01F04 – 28/05/20
@endsection

@section('footer_center')
Thời hạn lưu trữ: theo quy định của kế toán
@endsection

@section('body_content')

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 ">
                   Họ tên:   <div class="col-10 dot_underline_custom"  ><b >{{$payrequest->user->name}}</b>  </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                      MSNV:  <div class="dot_underline"><b>{{$payrequest->user->username}}</b></div>
                </div>
                 <div class="col-xs-3 col-sm-3 col-md-3">

                      Khối/BP: <b class="dot_underline col-7">{{$payrequest->department->code}}/{{$payrequest->group->name}}</b>
                </div>
            </div>
              <br/>
              <?php $index = 1; ?>
              @foreach($payrequest->payment_advance_moneys as $payment_advance_money)
              <div class="row">
                    <div class="col-xs-5 col-sm-5 col-md-5 ">
                    @if($index == 1)
                    <span>1). Số tiền đã tạm ứng </span>:   <div class="dot_underline_custom" style="width:56%"  ><b >{{number_format($payment_advance_money->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}}</b>  </div>
                    @else
                    <span  class="ml-3">   Số tiền đã tạm ứng </span>:   <div class="dot_underline_custom" style="width:56%"  ><b >{{number_format($payment_advance_money->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}}</b>  </div>
                    @endif
                </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        ,ngày:  <div class="dot_underline" style="width:70%"><b>{{ \Carbon\Carbon::parse($payment_advance_money->serial_date)->format('d/m/Y') }}</b></div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">

                        ,theo phiếu chi số: <b class="dot_underline "  style="width:50%">{{$payment_advance_money->serial_num}}</b>
                    </div>
               </div>
               <?php $index++ ;?>

              <br/>
              @endforeach

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  2). Các khoản chi thực tế:
                  </div>
              </div>

              <br>
              <div>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th style="text-align: center;width:10px">STT</th>
                              <th style="text-align: center;">Khoản mục thanh toán</th>
                              <th style="text-align: center;">Mã NS/TS</th>
                              <th style="text-align: center;">Cost center</th>
                              <th style="text-align: center;">Số PR
                                  hoặc
                                  PO
                              </th>

                              <th style="text-align: center;">Số tiền</th>
                          </tr>

                      </thead>
                      <tbody>
                          <!-- {{$payrequest->payment_vouchers}} -->
                          <?php
                              $index = 0;
                              $total = 0;
                          ?>

                          @foreach($payrequest->payment_vouchers as $item   )
                          <tr>
                              <td scope="row">{{++$index}}</td>
                              <td>{{$item->content}}</td>
                              <td style="text-align: center;">{{$item->gl_account}}</td>
                              <td style="text-align: center;">{{$item->costcenter}}</td>
                              <td style="text-align: center;">{{$item->prpo_num}}</td>
                              <td style="text-align: right;"> {{number_format($item->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}}</td>

                          </tr>
                          <?php $total = $item->amount + $total; ?>
                          @endforeach
                          <tr>
                              <td colspan ="5" style="text-align: center;"><strong>CỘNG</strong></td>
                              <td style="text-align: right;">{{number_format($total,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}}</td>

                          </tr>

                      </tbody>
                  </table>
              </div>

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  <i><strong>Thành tiền (bằng chữ): <div class="dot_underline" style="width:81%">  {{$info['amount_voucher_word']}}</strong> </i></div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  3). Số tiền tạm ứng phải hoàn lại (1 > 2): <div class="dot_underline" >
                      @if($info['amount_refund'] > 0)
                      <strong> {{number_format( $info['amount_refund'],$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}} </strong>
                       @else
                        &nbsp;
                       @endif
                    </div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">

                  <span class="ml-4">Bằng chữ:</span>  <div class="dot_underline" style="width:89%">
                  @if($info['amount_refund'] > 0)
                  <strong>{{ $info['amount_refund_word']}}  </strong>
                   @else
                     &nbsp;
                   @endif
                 </div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  4). Số tiền Công ty phải chi thêm (1 < 2): <div class="dot_underline" style="width:69%">
                    @if($info['amount_refund'] < 0)
                      <strong> {{number_format(  abs($info['amount_refund']),$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$payrequest->currency}} </strong>
                       @else
                        &nbsp;
                    @endif

                 </div>
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  <span class="ml-4">Bằng chữ:</span>  <div class="dot_underline" style="width:89%">
                  @if($info['amount_refund'] < 0)
                    <strong>   {{ $info['amount_refund_word']}}  </strong>
                   @else
                    &nbsp;
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
