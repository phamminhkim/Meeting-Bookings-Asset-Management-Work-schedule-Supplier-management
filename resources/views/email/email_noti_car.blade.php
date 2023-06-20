@component('mail::message')
# {{$data->title}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> @if($user->gender === '0')Chị  @else Anh  @endif có {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p> 
    <p>Phiếu car số <strong>{{$data->content}}</strong> đã sắp hết thời gian mong đợi cần xử lý. @if($user->gender === '0')Chị  @else Anh  @endif cần phản hồi sớm cho người tạo phiếu.</p>

@component('mail::button', ['url' =>  $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
