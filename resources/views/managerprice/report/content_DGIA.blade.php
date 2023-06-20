@extends('managerprice.report.print_document_temp')

@section('header_title')
{{ Str::upper($info['form_name'])}}
@endsection

@section('header_num')
    {{$document->serial_num}}
@endsection

@section('footer_left')
 MHP01F04 – 15/11/19
@endsection

@section('footer_center')
Thời gian lưu trữ: đến khi có bản trình duyệt giá mới
@endsection

@section('body_content')
              <?php
                $layout =  $info['layout'];

              ?>
              <!-- <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6 ">
                  Họ tên người đề nghị:   <div class="col-8 dot_underline"><b >{{$document->user->name}}</b>  </div>
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3">
                      MSNV:  <div class="dot_underline"><b>{{$document->user->username}}</b></div>
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3">

                      Khối/BP: <b class="dot_underline col-7">{{$document->department->code}}/{{$document->group->name}}</b>
                  </div>
              </div>
              <br/> -->
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  <strong>I. THÔNG TIN NHÀ CUNG ỨNG:</strong>
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  1. Tên nhà cung ứng:  {{$document->vendor->comp_name}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  2. Địa chỉ: {{$document->vendor->address}}
                  </div>
              </div>

              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 ">
                  3. Điện thoại: {{$document->vendor->phone}}    <span class="ml-5"></span><span class="ml-5">Fax: {{$document->vendor->fax}}</span>
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  4. Người đại diện giao dịch:  {{$document->vendor->contact}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  5. Sản phẩm ( Chủng loại):  {{$document->material_type_name}}
                  </div>
              </div>
              <br>

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  6. Giá: ({{$document->currency}})
                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                <?php $has_attach_file = false;?>
                  @foreach($info['list_file'] as $key=>$value)
                    @if($key == 'file_approve_attached' &&  count($value) > 0)
                        <?php $has_attach_file = true; ?>
                        @foreach($value as $file)
                        <li>{{$file['name']}} {{$file['value']}}</li>
                        @endforeach
                    @endif

                    @endforeach
                    @if ($has_attach_file == true)
                      (File giá đính kèm)
                    @else
                     @if ($info['vendor_count'] == 1)
                        <price-approve-request-review :data ="{{json_encode($info['data'])}}"   ></price-approve-request-review>
                     @elseif( !$info['show_spec'] )
                        <price-approve-request-review-vendor  :data ="{{json_encode($info['data'])}}"></price-approve-request-review-vendor>
                     @elseif( $info['show_spec'] )
                        <price-approve-request-review-spec-vendor :data ="{{json_encode($info['data'])}}"   ></price-approve-request-review-spec-vendor>
                     @endif
                    @endif


                  </div>
              </div>
              <br/>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  7. Phương thức thanh toán:  {{$document->method_payment_name}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  8. Số hợp đồng nguyên tắc: {{$document->contract_num}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  9. Thông tin khác: {{$document->diff_info}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  <STRONG>II. XEM XÉT CỦA KHỐI MUA HÀNG:</STRONG> (xem xét về chất lượng sản phẩm, so sánh giá…)
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    {!!$document->purchase_note!!}

                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  <STRONG>III. PHẦN ĐỀ NGHỊ:</STRONG> (xem xét về chất lượng sản phẩm, so sánh giá…)
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  @if($document->is_order == 1)
                  <div class="mr-2"> <i class="far fa-check-square"></i> Đặt hàng:  </div>
                    @else
                    <div class="mr-2"> <i class="far fa-square"></i> Đặt hàng:  </div>

                    @endif

                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Ý kiến khác:  {{$document->another_idea}}
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">

                  </div>
              </div>
              <br/>
              <div class="row">
                <div class="col" style="text-align: center;">
                    <!-- <i>Ngày {{ \Carbon\Carbon::parse($document->created_at)->format('d')}} tháng {{ \Carbon\Carbon::parse($document->created_at)->format('m')}} năm {{ \Carbon\Carbon::parse($document->created_at)->format('Y')}} </i><br> -->
                    <br><p><b  >Người đề nghị</b></p>
                    @if( $document->send_date )
                    <div class="approved">
                        <span>Đã ký: </span><br>
                        <span >{{ \Carbon\Carbon::parse($document->send_date)->format('d/m/Y H:i:s')}} </span>
                    </div>
                    @endif

                    <br>

                    <p><b  >{{$document->user->name}}</b></p>


                    </div>

                    <div class="col" style="text-align: center;">
                    <br><p><b  >Người duyệt</b></p>
                    @if( isset($signs['1']) )
                    <div class="approved" >
                        <span>Đã duyệt: </span><br>
                        <span>{{ \Carbon\Carbon::parse($signs['1']['checkout'])->format('d/m/Y H:i:s')}} </span>
                    </div>

                    <!-- <i class="fas fa-check-circle text-success"></i> -->
                    <br>

                    <p><b >{{$signs['1']['name']}}</b></p>
                    @endif
                    </div>
            </div>
            <div class="row">
                        @include('document.report.content_File_List')
            </div>

@endsection
