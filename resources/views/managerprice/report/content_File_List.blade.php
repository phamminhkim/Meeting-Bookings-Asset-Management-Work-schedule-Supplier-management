 
 <div>
 <h5> <i> <u> <i class="fas fa-paperclip"></i> Danh sách file đính kèm:</u> </i> </h5>
<ul>
  
  @foreach($info['list_file'] as $key=>$value)
   
     <!-- @if($key == 'purchase_invoice' &&  $value > 0)
     <li>Hoá đơn: {{$value}}</li>
     @endif
     @if($key == 'purchase_voucher' &&  $value > 0)
     <li>Chứng từ: {{$value}}</li>
     @endif -->
     @if($key == 'file_attached' &&  count($value) > 0)
        @foreach($value as $file)
        <li>{{$file['name']}} {{$file['value']}}</li>
        @endforeach
     @endif

    @endforeach
  
</ul>  
 </div>
