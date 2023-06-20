@extends('payment.report.print_payment_temp')

@section('header_title')
    PHIẾU ĐỀ NGHỊ THANH TOÁN
@endsection

@section('header_num')
    {{$payrequest->serial_num}}
@endsection

@section('footer_left')
 TCKTP01F02 – 28/05/20
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
                      <div class="d-flex w-100 justify-content-between">
                          <div class="col"> Đơn vị/ Cá nhân nhận tiền:  <div class="dot_underline_custom" style="width:75%">
                          <b >
                          @if($payrequest->vendor) {{$payrequest->vendor->comp_name}}
                          @else {{$payrequest->money_receiver}}
                                @if($payrequest->method_payment == 1 )
                                   - STK: {{$payrequest->bank_account}}
                                   - Tại ngân hàng: @if($payrequest->bank){{$payrequest->bank->name}}@elseif($payrequest->bank_name) {{$payrequest->bank_name}}@endif
                                   @if($payrequest->bank_branch) - {{$payrequest->bank_branch}} @endif
                                @endif
                          @endif
                          </b>  </div>  </div>

                          @if($payrequest->method_payment == 2)
                          <div class="mr-2"><b>TM <i class="far fa-check-square"></i> /CK <i class="far fa-square"></i> </b>  </div>
                          @else
                          <div class="mr-2"><b>TM <i class="far fa-square"></i> /CK <i class="far fa-check-square"></i> </b>  </div>
                          @endif
                      </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Nội dung thanh toán: <div class="dot_underline_custom" style="width:84%"><b>{{$payrequest->content}}</b></div>
                  </div>
              </div>
              <br/>
              <div class="row" >

                  <div class="col-xs-12 col-sm-12 col-md-12">
                      Chi phí đã có ngân sách

                          @if($payrequest->budget_type == 1)
                              <i class="far fa-check-square"></i>
                          @else
                              <i class="far fa-square"></i>
                          @endif
                      Chi phí được trình duyệt bổ sung
                          @if($payrequest->budget_type == 0 || $payrequest->budget_type == 2 || $payrequest->out_budget == 1 )
                              <i class="far fa-check-square"></i>
                          @else
                              <i class="far fa-square"></i>
                          @endif
                      <b> Thời hạn thanh toán: </b><div class="dot_underline_custom" style="width:37%;">
                      @if($payrequest->finish_date)
                      <strong>{{ \Carbon\Carbon::parse($payrequest->finish_date)->format('d/m/Y')}} </strong>
                      @endif
                    </div>
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
                          <?php $total = $item->amount + $total;?>
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

                        @for  ($i = 0; $i <= count($signs) -1 ;$i++ )

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
