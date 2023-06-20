@component('mail::message')
# {{$data->title}} : {{$data->content}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p> 

 <table class="table table-responsive" style="margin-left: 10px;">
     <tr>
       <td>Người bình luận: <strong>{{$object->user->name}}</strong></td>
       <td>Thời gian: <strong>{{$object->created_at}}</td>
     </tr>
     <tr>
       <td><strong>Nội dung bình luận:</strong></td>
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

