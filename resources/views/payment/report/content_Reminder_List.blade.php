
 @if (count($info['list_reminder'])>0)
 <div>
 <h5> <i> <u> <i class="fas fa-pen"></i> Ghi chú/Nhắc nhở:</u> </i> </h5>
<ul>

  @foreach($info['list_reminder'] as $key=>$value)
     <li>
        <pre style="font-family:none;font-size: inherit;padding: 0px; margin: 0px; white-space: pre-wrap;">{{$value->content}}</pre>
     </li>

    @endforeach

</ul>
 </div>
@endif
