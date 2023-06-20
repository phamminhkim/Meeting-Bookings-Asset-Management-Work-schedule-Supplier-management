<ol class="dd-list">
    @foreach($childs as $child)
    <li class="dd-item dd2-item" data-id="16">
        <div class="dd-handle dd2-handle">
            @if(count($child->childs)== 0)
            <input id="menu{{$child->id}}" name="menuids[]" value="{{$child->id}}" type="checkbox" class="form-group">
            @else
            <i class="normal-icon ace-icon {{$child->icon}} bigger-130"></i>
            @endif

            <i class="drag-icon ace-icon fa fa-arrows bigger-125"></i>
        </div>
        <div class="dd2-content">{{$child->title}}</div>
        @if(count($child->childs)> 0)
        @include('admin.role.tabs.sub_menu.create_menu',['childs'=>$child->childs])
        @endif
    </li>
    @endforeach
</ol>