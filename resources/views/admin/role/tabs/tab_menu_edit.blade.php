<div class="col-sm-12">
    <div class="dd dd-draghandle">
        <ol class="dd-list">
            @foreach($menus as $menu)
            <li class="dd-item dd2-item" data-id="15">
                <div class="dd-handle dd2-handle">
                    @if(count($menu->childs)== 0)
                    <input <?php
                            for ($i = 0; $i < count($rolemenu); $i++) {
                                if ($rolemenu[$i]->menu_id == $menu->id) {
                                    echo 'checked';
                                }
                            }
                            ?> id="menu{{$menu->id}}" name="menuids[]" value="{{$menu->id}}" type="checkbox" class="form-group">
                    @else
                    <i class="normal-icon ace-icon {{$menu->icon}} bigger-130"></i>
                    @endif
                    <i class="drag-icon ace-icon fa fa-arrows bigger-125"></i>
                </div>
                <div class="dd2-content">{{$menu->title}}</div>
                @if(count($menu->childs)> 0)
                @include('admin.role.tabs.sub_menu.edit_menu',['childs'=>$menu->childs])
                @endif

            </li>
            @endforeach


        </ol>
    </div>
</div>