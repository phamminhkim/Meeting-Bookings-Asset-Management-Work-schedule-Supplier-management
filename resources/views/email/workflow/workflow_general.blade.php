@component('mail::message')
# {{ $title }} : {{ $serial_num }}


### Chào {{ $recipient_gender }} {{ $recipient_name }} ({{ $recipient_id }}),
### {{ str_replace('{gender}', $recipient_gender, $content) }}.

@if (count($detail_data) > 0)
@component('mail::panel')
<table class="table table-responsive" style="margin-left: 15px;">
    <tbody>
        @foreach ($detail_data as $key => $value)
            <tr>
                <td width="35%"><strong>{{ $key }}<strong></td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endcomponent
@endif

@component('mail::button', ['url' => $url])
    Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
