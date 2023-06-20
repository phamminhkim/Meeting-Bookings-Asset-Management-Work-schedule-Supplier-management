@component('mail::message')
# {{$data->title}} : {{$data->content}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> {{$data->content_full}} {{$data->content}} đã được duyệt. Thông tin từ hệ thống  <strong>Thiên Long Digital</strong>:</p> 
    
 <table style="margin-left:50px;">
   <tr>
     <td>Công ty:</td>
     <td><strong>{{$payrequest->company->name}}</strong></td>
   </tr>
   <tr>
     <td>Phòng ban:</td>
     <td><strong>{{$payrequest->department->code}}/{{$payrequest->group->name}}</strong></td>
   </tr>
   <tr>
     <td>Người đề nghị:</td>
     <td><strong>{{$payrequest->user->name}}</strong></td>
   </tr>
   <tr>
     <td>Số tiền đề nghị:</td>
     <td><strong>{{number_format($payrequest->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} ({{$payrequest->currency}})</strong></td>
   </tr>
   <tr>
     <td>Mục đích:</td>
     <td><strong>{{$payrequest->content}}</strong></td>
   </tr>
   <tr>
     <td>Đơn vị/Cá nhân nhận tiền:</td>
     <td><strong>@if($payrequest->vendor){{$payrequest->vendor->comp_name}} @else {{$payrequest->money_receiver}}@endif</strong></td>
   </tr>
 </table>
@component('mail::button', ['url' => $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
