@component('mail::message')
# {{$data->title}}

<p>Chào @if($user->gender === '0')Chị  @else Anh  @endif {{$user->name}} ({{$user->username}})</p>
    <p>Tài khoản của @if($user->gender === '0')chị  @else anh  @endif vừa được reset mật khẩu thành công</p>


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
       <td><b>Tên đăng nhập:</b></td>
       <td><strong>{{$user->username}}</strong></td>
     </tr>
     <tr>
        <td><b>Mật khẩu mới:</b></td>
        <td><strong>{{$password}}</strong></td>
      </tr>
   </tbody>
 </table>

 <p> Sau khi đăng nhập, @if($user->gender === '0')chị  @else anh  @endif hãy tiến hành đổi mật khẩu ngay!</strong></p>



 @component('mail::button', ['url' => $data->url])
Đăng nhập
@endcomponent

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
