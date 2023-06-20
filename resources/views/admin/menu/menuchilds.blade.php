<ol class="dd-list">
@foreach($childs as $child)

                <li class="dd-item dd2-item" data-id="{{$child->id}}">
                    <div class="dd-handle dd2-handle">
                        <i  id="icon_show{{$child->id}}" class="normal-icon ace-icon {{$child->icon}} bigger-130"></i>
                        <i class="drag-icon ace-icon fa fa-arrows bigger-125"></i>
                    </div>
                    <div class="dd2-content">
                    <span id="label_show{{$child->id}}">{{$child->title}}</span>
                    <span class="pull-right action-buttons">/<span id="link_show{{$child->id}}">{{$child->link}}</span>
                            <a class="edit-button" id="{{$child->id}}" label="{{$child->title}}" link="{{$child->link}}" icon="{{$child->icon}}"><i class="ace-icon fa fa-pencil blue bigger-130"></i></a>
                            <a class="del-button" id="{{$child->id}}"><i class="ace-icon fa fa-trash-o red bigger-130"></i></a></span>
                    </div>
                    @if(count($child->childs)>0)
                          @include('admin.menu.menuchilds',['childs' => $child->childs])
                     @endif
                </li>


 @endforeach
 </ol>
