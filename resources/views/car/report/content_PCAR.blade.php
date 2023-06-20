@extends('car.report.print_car_temp')

@section('header_title')
{{ Str::upper($info['form_name'])}}
@endsection

@section('header_num')
    {{$car->serial_num}}
@endsection

@section('footer_left')
 <!-- TCKTP01F03 – 28/05/20 -->
@endsection

@section('footer_center')
<!-- Thời hạn lưu trữ: theo quy định của kế toán -->
@endsection

@section('body_content')
              <?php
			    use App\Ultils\Ultils;
                $layout =  $info['layout'];
              ?>
				<tr>
				<td>Số lần tái diễn: {{$car->issue_count}} lần</td>
				<td>Ngày: {{ \Carbon\Carbon::parse($car->issue_date)->format('d-m-Y')}}</td>
				<td>Công ty: {{$car['company']['name']}}</td>
				<td>Phòng ban: {{$car['department']['name']}} 
				</td>
				</tr>
				<tr>
				<td colspan="4" style="border-bottom:none;"><b>Mô tả chi tiết sự không phù hợp:</b></td>
				</tr>
				<tr>
				<td colspan="4" style="border-bottom:none;border-top:none;"> {!!$car->content!!}</td>
				</tr>
				</tr>
				@if($info['list_file']['file_attached'])
				<tr>
				<td colspan="4"><b>Tệp đính kèm:</b><br/>
				 @include('car.report.content_File_List')
			  </td>
			  </tr>
			  @endif
			  <tr>
				<td <?php if($car->car_error_id == null) echo 'colspan="4"'; else echo 'colspan="2"';?>><b>Mức độ lỗi:</b>
				<table>
				<tr>
				@foreach($car_error_qc as $error)
				<td style="border:none;">
				<div class="custom-control custom-radio">
					<input class="custom-control-input mucdoloiapdung" type="radio" id="loailoi{{$error->id}}" value="{{$error->id}}" <?php if($error->id== $car->car_error_id) echo 'checked'; else echo'disabled';?> name="loailoi">
					<label for="loailoi{{$error->id}}" class="custom-control-label">{{$error->name}}</label>
				 </div>
				 </td>
				@endforeach
				 </tr>
				<tr>
				<td style="border:none;" <?php if($car->car_error_id == null) echo 'colspan="4"'; else echo 'colspan="2"';?>>
				<label class="label">Phân tích nguyên nhân và đưa BP KPPN</label>
				<table>
				<tr>
				<td style="border:none;">
				  <div class="custom-control custom-radio">
				  <input class="custom-control-input" type="radio" id="causemeasure1" name="is_cause_measure"  <?php if($car->is_cause_measure== 1) echo 'checked'; else echo'disabled';?>>
				  <label for="causemeasure1" class="custom-control-label">Cần</label>
				 </div>
				</td>
				<td style="padding-left:10px;border:none;">
				<div class="custom-control custom-radio">
				<input class="custom-control-input" type="radio" id="causemeasure2" name="is_cause_measure" <?php if($car->is_cause_measure== 0) echo 'checked'; else echo'disabled';?>>
				 <label for="causemeasure2" class="custom-control-label">Không cần</label>
				 </div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
			<td><b>Xác định rủi ro:</b>
			<table>
			<tr>
			<td style="border:none;">
              <div class="custom-control custom-radio">
              <input class="custom-control-input" type="radio" id="xacdinhruiro1" name="cause"  <?php if($car->cause== 1) echo 'checked'; else echo'disabled';?>>
              <label for="xacdinhruiro1" class="custom-control-label">Có</label>
             </div>
			</td>
			<td style="padding-left:10px;border:none;">
			<div class="custom-control custom-radio">
            <input class="custom-control-input" type="radio" id="xacdinhruiro2" name="cause" <?php if($car->cause== 0) echo 'checked'; else echo'disabled';?>>
             <label for="xacdinhruiro2" class="custom-control-label">Không</label>
             </div>
			</td>
			</tr>
			</table>
			</td>
			<td><b>Nhận diện cơ hội:</b>
			<table>
			<tr>
			<td style="border:none;">
            <div class="custom-control custom-radio">
            <input class="custom-control-input" type="radio" id="nhandiencohoi1" name="risk" <?php if($car->risk== 1) echo 'checked'; else echo'disabled';?>>
            <label for="nhandiencohoi1" class="custom-control-label">Có</label>
            </div>
			</td>
			<td style="padding-left:10px; border:none;">
			<div class="custom-control custom-radio">
            <input class="custom-control-input" type="radio" id="nhandiencohoi2" name="risk" <?php if($car->risk== 0) echo 'checked'; else echo'disabled';?>>
            <label for="nhandiencohoi2" class="custom-control-label">Không</label>
            </div>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
	

			<td  colspan="4" style="border:none;">
		
			<table style="float:right;border:none;">
			<tr>
			<td style="border:none;">
			<div style="background-image: url(img/notickxn.png); no-repeat;  border: 1px dotted #ced4da;">
             <p style="text-align:center;"> <strong>Người Tạo Phiếu</strong></p>
              <p style="text-align:center; line-height:25px;">
			{{$car->user->name}}
			</p>
			<p style="text-align:center;">{{$car->send_date}}</p>
             </div>
		    </td>
			@for($j=0;$j<$result['is_count_step1'];$j++)
				<td style="border:none;">
				 <div @if($car->status==-2 && $result['is_step1'][$j]['checkout']!=null)
					style="background-image: url(img/deny.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@elseif($car->status!=-2 && $result['is_step1'][$j]['checkout']!=null && $result['is_step1'][$j]['finished']==1)
					style="background-image: url(img/tickxn.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@else
					style="background-image: url(img/notickxn.png); no-repeat;  border: 1px dotted #ced4da;"
					@endif">
                    <p style="text-align:center;"> <strong>{{ Ultils::is_position($result['is_step1'][$j]['user']['id']) }}</strong></p>
					
					<p style="text-align:center; line-height:25px;">
					{{$result['is_step1'][$j]['user']['name']}}
					</p>
					
					<p style="text-align:center;">
					{{$result['is_step1'][$j]['checkout']}}
					</p>
                </div>
				</td>
			@endfor
			</tr>
			</table>
		



				</td>
			
			</tr>
			<tr>
			<td colspan="4">
			<div class="callout callout-info" >	
			<h6> <strong>XỬ LÝ TỨC THỜI</strong></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
					<th>STT</th>
                    <th>Hành động xử lý tức thời</th>
					<th>Thời hạn</th>
					<th>Người phụ trách</th>
					<th>Tệp đính kèm</th>
                  </tr>
                  </thead>
                  <tbody>
				  @for($i=0;$i<count($car['fast_process']);$i++)
                  <tr>
				    <td>{{$i+1}}</td>
                    <td>
					{!!$car['fast_process'][$i]['content']!!}
					</td>
					<td>
					{{ \Carbon\Carbon::parse($car['fast_process'][$i]['date'])->format('d-m-Y')}}
                    </td>
					<td>
					{!!$car['fast_process'][$i]['person_in_charge']!!}
					</td>
					<td>
					@if($car['fast_process'][$i]['other_attacheds'])
					<table>
					@for($j=0;$j<count($car['fast_process'][$i]['other_attacheds']);$j++)
					@foreach($car['fast_process'][$i]['other_attacheds'][$j]['attachment_file'] as $key=>$value)
							<tr>
							<td>{{$j+1}}</td>
							<td>{{$value['name']}}</td>
							</tr>
					@endforeach
					@endfor
					</table>
					@endif			
					</td>
				
                  </tr>
                  @endfor
                  </tbody>
                </table>
				</div>
			  </div>
			  </td>
			  </tr>
			 <tr>
			<td colspan="4" style="border:none;">
			<table style="float:right;border:none;">
			<tr>
			@for($j=0;$j<$result['is_count_step2'];$j++)
				<td style="border:none;">
				 <div @if($car->status==-2 && $result['is_step2'][$j]['checkout']!=null)
					style="background-image: url(img/deny.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@elseif($car->status!=-2 && $result['is_step2'][$j]['checkout']!=null && $result['is_step2'][$j]['finished']==1)
					style="background-image: url(img/tickxn.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@else
					style="background-image: url(img/notickxn.png); no-repeat;  border: 1px dotted #ced4da;"
					@endif">
                    <p style="text-align:center;"> <strong>{{ Ultils::is_position($result['is_step2'][$j]['user']['id']) }}</strong></p>
					
					<p style="text-align:center; line-height:25px;">
					{{$result['is_step2'][$j]['user']['name']}}
					</p>
					
					<p style="text-align:center;">
					{{$result['is_step2'][$j]['checkout']}}
					</p>
                </div>
				</td>
			@endfor
			</tr>
			</table>
			</td>
			</tr>
			@if($car->is_cause_measure==1)
			<tr>
			<td colspan="4">
			<div class="callout callout-info" >	
			<h6 > <strong>NGUYÊN NHÂN VÀ BIỆN PHÁP KHẮC PHỤC</strong></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
					<th>STT</th>
                    <th>Nguyên nhân</th>
					<th>Biện pháp khắc phục</th>
					<th>Thời gian thực hiện</th>
					<th>Người phụ trách</th>
					<th>Tệp đính kèm</th>
                  </tr>
                  </thead>
                  <tbody>
				  @for($i=0;$i<count($car['cause_measure']);$i++)
                  <tr>
				  <td>{{$i+1}}</td>
                    <td>
					{{$car['cause_measure'][$i]['cause']}}
					</td>
                     <td>
					{{$car['cause_measure'][$i]['measure']}}
                    </td>
					 <td>
					{{ \Carbon\Carbon::parse($car['cause_measure'][$i]['date'])->format('d-m-Y')}}
                    </td>
					<td>
					{{$car['cause_measure'][$i]['person_in_charge']}}
                    </td>
					<td>
					@if($car['cause_measure'][$i]['other_attacheds'])
					<table>
					@for($j=0;$j<count($car['cause_measure'][$i]['other_attacheds']);$j++)
					@foreach($car['cause_measure'][$i]['other_attacheds'][$j]['attachment_file'] as $key=>$value)
							<tr>
							<td>{{$j+1}}</td>
							<td>{{$value['name']}}</td>
							</tr>
					
					@endforeach
					@endfor
					</table>
					@endif
					</td>
                  </tr>
                  @endfor
                  </tbody>
                </table>
				</div>
			  </div>
			  </td>
			  </tr>
			<tr>
			<td colspan="4" style="border:none;">
			<table style="float:right;border:none;">
			<tr>
			@for($j=0;$j<$result['is_count_step3'];$j++)
				<td style="border:none;">
				 <div @if($car->status==-2 && $result['is_step3'][$j]['checkout']!=null)
					style="background-image: url(img/deny.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@elseif($car->status!=-2 && $result['is_step3'][$j]['checkout']!=null && $result['is_step3'][$j]['finished']==1)
					style="background-image: url(img/tickxn.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;"
					@else
					style="background-image: url(img/notickxn.png); no-repeat;  border: 1px dotted #ced4da;"
					@endif">
                    <p style="text-align:center;"> <strong>{{ Ultils::is_position($result['is_step3'][$j]['user']['id']) }}</strong></p>
					
					<p style="text-align:center; line-height:25px;">
					{{$result['is_step3'][$j]['user']['name']}}
					</p>
					
					<p style="text-align:center;">
					{{$result['is_step3'][$j]['checkout']}}
					</p>
                </div>
				</td>
			@endfor
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			<td colspan="4">
			<div class="callout callout-info" >	
				<h6> <strong>GIÁM SÁT THỰC HIỆN</strong></h6>
				<span class="description"></span> 
					<div class="card-body table-responsive">
					<table class="table table-striped table-valign-middle">
					  <thead>
					  <tr>
						<th>STT</th>
						<th>Biện pháp khắc phục</th>
						<th>Đánh giá</th>
						<th>Ngày đánh giá</th>
						<th>Ngày hoàn thành</th>
					  </tr>
					  </thead>
					  <tbody>
					  @for($k=0;$k<count($car['monitor_implementation']);$k++)
					  <tr>
						<td>{{$k+1}}</td>
						 <td>
						  {{ Ultils::is_cause_measure($car['monitor_implementation'][$k]['cause_measure_id']) }}
						</td>
						<td>
						  @if($car['monitor_implementation'][$k]['result']==0)
							 Hoàn thành
						  @else
							  Không hoàn thành
						  @endif
						</td>
						 <td>
						{{ \Carbon\Carbon::parse($car['monitor_implementation'][$k]['date'])->format('d-m-Y')}}
						</td>
						<td>
						{{ \Carbon\Carbon::parse($car['monitor_implementation'][$k]['finished_date'])->format('d-m-Y')}}
						</td>
					  </tr>
					  @endfor
					  </tbody>
					</table>
					</div>
				  </div>
				  </td>
				</tr>
				<tr>
				<td colspan="4" style="border-bottom:none;">
				<div class="callout callout-info" >	
					<h6> <strong>ĐÁNH GIÁ KẾT QUẢ</strong></h6>
					<span class="description"></span> 
						<div class="card-body table-responsive">
						<table class="table table-striped table-valign-middle">
						  <tr>
							<th>STT</th>
							<th>Mô tả</th>
							<th>Kết quả</th>
							<th>Ngày</th>
							<th>Tệp đính kèm</th>
						  </tr>
						  @for($l=0;$l<count($car['result_evaluation']);$l++)
						  <tr>
						  <td>{{$l+1}}</td>
						  <td>{!!$car['result_evaluation'][$l]['content']!!}</td>
						  <td>
						   @if($car['result_evaluation'][$l]['result']==0)
							  Đạt
						  @elseif($car['result_evaluation'][$l]['result']==1)
							  Không đạt
						  @else
							  Không có dữ liệu
						  @endif
						  </td>
						  <td>{{ \Carbon\Carbon::parse($car['result_evaluation'][$l]['date'])->format('d-m-Y')}}</td>
						  <td>
						  @if($car['result_evaluation'][$l]['other_attacheds'])
						  <table>
						  @for($j=0;$j<count($car['result_evaluation'][$l]['other_attacheds']);$j++)
						  @foreach($car['result_evaluation'][$l]['other_attacheds'][$j]['attachment_file'] as $key=>$value)
							<tr>
							<td>{{$j+1}}</td>
							<td>{{$value['name']}}</td>
							</tr>
							@endforeach
							@endfor
							</table>
							@endif
							</td>
						  </tr>
						  @endfor
						</table>
						</div>
					  </div>
					</td>
					</tr>
					<tr>
					 <td colspan="4">
					 <table style="float:right;border:none;">
					 <tr>
					
					 <td style="border:none;">
					<div @if($car->status==-2 && $result['is_step4']==4)
							style="background-image: url(img/deny.png); background-repeat: no-repeat;  border: 1px dotted #ced4da; padding:5px;"
							@elseif($car->status!=-2 && $result['is_step4']==4)
							style="background-image: url(img/tickxn.png); background-repeat: no-repeat;  border: 1px dotted #ced4da;  padding:5px;"
							@else
							style="background-image: url(img/notickxn.png); no-repeat;  border: 1px dotted #ced4da; padding:5px;"
							@endif">
							<p style="text-align:center;"> <strong>{{ Ultils::is_position($result['is_user_id_step4']) }}</strong></p>
							<p style="text-align:center; line-height:25px;">
							{{$result['is_user_step4'] }}
							</p>
							<p style="text-align:center;">
							{{ $result['is_checkout_step4']}}
							</p>
					</div>
					</td>
					 </tr>
					 </table>
					 </td>
					</tr>
					@endif
				</table>
@endsection
