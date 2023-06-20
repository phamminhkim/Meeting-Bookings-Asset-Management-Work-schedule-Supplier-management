@component('mail::message')
# {{$data->title}} : {{$data->content}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p>
    <p> @if($user->gender === '0')Chị  @else Anh  @endif có {{$data->title}} gửi đến từ hệ thống  <strong>Thiên Long Digital</strong> như sau:</p>


 <table class="table table-responsive" style="margin-left: 50px;">

   <tbody>
     <tr>
       <td >Công ty:</td>
       <td><strong>{{$object->company->name}}</strong></td>
     </tr>
     <tr>
       <td>Phòng ban:</td>
       <td><strong>{{$object->department->code}}/{{$object->group->name}}</strong></td>
     </tr>
     <tr>
       <td>Người đề nghị:</td>
       <td><strong>{{$object->user->name}}</strong></td>
     </tr>
     @if ($object->docbrowser_type)
     <tr>
       <td>Loại trình ký:</td>
       <td><strong>{{$object->docbrowser_type->name}}</strong></td>
     </tr>
     @endif
     <tr>
       <td>Số tiền :</td>
       <td><strong>{{number_format($object->amount,$info['decimal'],$info['decimalpoint'],$info['separator'])}} ({{$object->currency}})</strong></td>
     </tr>
     <tr>
       <td>Về việc:</td>
       <td><strong>{{$object->title}}</strong></td>
     </tr>

   </tbody>
 </table>

@component('mail::button', ['url' => $data->url])
Xem chi tiết
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
