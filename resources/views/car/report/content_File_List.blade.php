 <table>
 <?php
 $i=1;
 ?>
 	@foreach($info['list_file'] as $key=>$value)
	 @if($key == 'file_attached' &&  count($value) > 0)
        @foreach($value as $file)
	<tr>
	<td>{{$i}}		
	</td>
	<td>{{$file['name']}} {{$file['value']}}</td>
	</tr>
	 <?php
	 $i++;
	 ?>
  @endforeach
     @endif
    @endforeach	
	</table>

