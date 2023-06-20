@component('mail::message')
# {{$data->title}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p> 
    <p> @if($user->gender === '0')Chị  @else Anh  @endif có {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p> 
    <p>{{$data->content}}</p>

@component('mail::button', ['url' =>  $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
