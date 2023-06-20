@component('mail::message')
# {{ $generalData->title }} : {{ $generalData->content }}

<p>Chào @if($userData->gender === '0')Chị  @else Anh  @endif {{$userData->name}} ({{$userData->username}})</p>
    <p> @if($userData->gender === '0')Chị  @else Anh  @endif có thông báo gửi đến từ hệ thống <strong>Thiên Long Digital</strong></p>

@if (count($detailData) > 0)
  <table class="table table-responsive" style="margin-left: 10px;">
      @foreach ($detailData as $data)
          <tr>
              <td>{{ $data->key }}</td>
              <td><strong>{{ $data->value }}</strong></td>
          </tr>
      @endforeach
  </table>
@endif

@component('mail::button', ['url' => $generalData->url])
    Xem chi tiết
@endcomponent
Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
