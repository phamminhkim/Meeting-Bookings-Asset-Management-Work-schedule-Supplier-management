@extends('document.report.print_document_temp')

@section('header_title')
{{ Str::upper($info['form_name'])}}
@endsection

@section('header_num')
    {{$document->serial_num}}
@endsection

@section('footer_left')
 <!-- TCKTP01F03 – 28/05/20 -->
@endsection

@section('footer_center')
<!-- Thời hạn lưu trữ: theo quy định của kế toán -->
@endsection

@section('body_content')
              <?php
                $layout =  $info['layout'];

              ?>
              <div class="row">
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
              <br/>

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Về việc: <div class="dot_underline" style="width:91%"><strong> {{$document->title}} </strong></div>
                  </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Mã ngân sách: <div class="dot_underline" style="width:87%"><strong> {{$document->budget_num}} </strong></div>
                  </div>
              </div>
              @if($layout->amount->isVisible == true)
              <br>
              <div class="row">
                  <div class="col-xs-5 col-sm-5 col-md-5 ">

                   @if($layout->amount && $layout->amount->has_custom_label) {{$layout->amount->custom_label_text}}:   @else Số tiền:  @endif  <div class="col-5 dot_underline" style="width:100%"><b >{{number_format($document->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$document->currency}}</b>  </div>
                  </div>
                  <div class="col-xs-7 col-sm-7 col-md-7"  >
                    ,Bằng chữ: <div class="col-11 dot_underline" style="width:82%"><b> {{$info['amount_word']}}</b></div>
                  </div>

              </div>
              @endif
              <br>

              <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                  Nội dung: <div class="no_ot_underline_custom" style="width:91%"> {!!$document->content!!}</div>
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
