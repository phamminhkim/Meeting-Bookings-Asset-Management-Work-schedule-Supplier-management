@component('mail::message')
# {{$data->title}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p>
    <p> @if($user->gender === '0')Chị  @else Anh  @endif đã bị vô hiệu hóa trên hệ thống <strong>Thiên Long Digital</strong>:</p>


 <table class="table table-responsive" style="margin-left: 50px;">

   <tbody>
        <tr>
        <td>Tên nhân viên:</td>
        <td><strong>{{$user->name}}</strong></td>
      </tr>
     <tr>
       <td >Công ty:</td>
       <td><strong>{{$company->name}}</strong></td>
     </tr>
     <tr>
       <td>Phòng ban:</td>
       <td><strong>{{$department->name}}</strong></td>
     </tr>
     <tr>
       <td>Tên đăng nhập:</td>
       <td><strong>{{$user->username}}</strong></td>
     </tr>
   </tbody>
 </table>

 <p> Kể từ {{$datetime}}, @if($user->gender === '0')chị  @else anh  @endif sẽ không thể đăng nhập {{config('app.name')}} bằng tài khoản này!</strong></p>

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
