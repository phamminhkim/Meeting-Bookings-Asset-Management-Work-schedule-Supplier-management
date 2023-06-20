@component('mail::message')
# {{$data->title}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> @if($user->gender === '0')Chị  @else Anh  @endif có {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p> 
    <p>Phiếu car số <strong>{{$data->content}}</strong> đã quá thời gian mong đợi cần xử lý.</p>
    <p>Hệ thống tự động <strong>Đồng ý thay</strong> cho @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}}) để chuyển qua bước tiếp theo</p>

@component('mail::button', ['url' =>  $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
