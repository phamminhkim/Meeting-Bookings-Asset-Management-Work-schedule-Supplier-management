@component('mail::message')
# {{$data->title}} : {{$data->content}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> @if($user->gender === '0')Chị  @else Anh  @endif có {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p> 

 <table class="table table-responsive" style="margin-left: 10px;">
     <tr>
       <td >Công ty: <strong>{{$object->company->name}}</strong></td>
       <td> Số lần tái diễn: <strong>{{$object->issue_count}} lần</td>
     </tr>
     <tr>
       <td>Phòng ban: <strong>{{$object->department->name}}</strong></td>
       <td>Ngày: <strong>{{ \Carbon\Carbon::parse($object->issue_date)->format('d/m/Y')}}</strong></td>
     </tr>
     <tr>
       <td>Người tạo: <strong>{{$object->user->name}}</strong></td>
       <td></td>
     </tr>
     <tr>
       <td><strong>Mô tả chi tiết sự không phù hợp:</strong></td>
       <td></td>
     </tr>
     <tr>
       <td colspan="2" style="text-align: justify;">{!!$object->content!!}</td>
     </tr>
 </table>

@component('mail::button', ['url' => $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent

