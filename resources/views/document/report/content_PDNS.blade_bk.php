@extends('document.report.print_document_temp')

@section('header_title')
    {{$info['form_name']}}
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
<div class="row" style="width:280mm">
        <div class="col-md-12">
           <div class="card" style="border:1px solid gray;">
               <div class="" >
                    <div class="p-2" style="text-align:center;background-color:#28659C;color:white" > {{ Str::upper($info['form_name'])}}</div>
                   <div class="d-flex  justify-content-between m-2" style="border-bottom:1px solid #CEE2EE">
                    <div   >
                        <span class="mute small"><i>{{__('form.create_by')}}: <strong >{{$document->user->name}} - {{$document->user->username}}  </strong> </i></span><br>
                        <span class="mute small"><i>{{__('form.company')}}: <strong> {{$document->user->company->name}}</strong></i></span>
                    </div>
                    <div   >
                        <span class="mute small" > <i> {{__('form.serial_num')}} :<strong> {{$document->serial_num}} </strong></i></span><br>
                        <span class="mute small" ><i>{{__('form.send_date')}}: <strong class="ml-4" > {{$document->send_date}}</strong></i></span>
                    </div>
                   </div>
                    
                   
               </div>
               
               <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <span class="col-form-label col-sm-2 ">{{__('form.about')}}:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label">{{$document->title}}</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <span class="col-form-label col-sm-2 ">{{__('form.budget_code')}}:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label">{{$document->budget_num}}</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <span class="col-form-label col-sm-2 ">{{__('form.excess_amount')}}:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label">{{number_format($document->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} {{$document->currency}}</label>
                            
                            </div>
                        </div>
                   
                        <div class="form-group row">
                            <span class="col-form-label col-sm-2 ">{{__('form.content')}}:</span>
                            <div class="col-sm-10 mt-2">
                                    {!!$document->content!!}
                            </div>
                        </div>
                        <!-- @if($document->budget_type == 0)
                            <span ><i class="far fa-check-square"></i> {{ __('Ngoài / Vượt ngân sách') }}</span>
                            &nbsp;<span ><i class="far fa-square"></i> {{ __('Trong ngân sách') }}</span>
                        @elseif($document->budget_type == 1)
                            <span ><i class="far fa-square"></i> {{ __('Ngoài / Vượt ngân sách') }}</span>
                            &nbsp;<span ><i class="far fa-check-square"></i> {{ __('Trong ngân sách') }}</span>
                        @else
                            <span ><i class="far fa-square"></i> {{ __('Ngoài / Vượt ngân sách') }}</span>
                            &nbsp;<span ><i class="far fa-square"></i> {{ __('Trong ngân sách') }}</span>
                        @endif -->
                        
                        
 
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
                            <span >{{ \Carbon\Carbon::parse($document->send_date)->format('d/m/Y H:m:s')}} </span>
                        </div>
                        @endif
                        
                        <br>

                        <p><b  >{{$document->user->name}}</b></p>


                        </div>
                        <div class="col" style="text-align: center;">
                        <br><p><b  >Người duyệt</b></p>
                        @if( isset($signs['1']) )
                        <div class="approved">
                            <span>Đã duyệt: </span><br>
                            <span >{{ \Carbon\Carbon::parse($signs['1']['checkout'])->format('d/m/Y H:m:s')}} </span> 
                        </div>
                        
                        <!-- <i class="fas fa-check-circle text-success"></i> -->
                        <br>
                        
                        <p><b >{{$signs['1']['name']}}</b></p>
                        @endif
                        </div>
                        <div class="col" style="text-align: center;">
                        <br><p><b  >Kế toán trưởng</b></p>
                        @if( isset($signs['2']) )
                        <div class="approved" >
                            <span>Đã duyệt: </span><br>
                            <span>{{ \Carbon\Carbon::parse($signs['2']['checkout'])->format('d/m/Y H:m:s')}} </span> 
                        </div>
                        
                        <!-- <i class="fas fa-check-circle text-success"></i> -->
                        <br>
                        
                        <p><b >{{$signs['2']['name']}}</b></p>
                        @endif
                        </div>
                    </div> 
                    <div class="row">
                        @include('document.report.content_File_List')
                     </div>
               </div>
             
           </div>
        </div>
 
               

@endsection