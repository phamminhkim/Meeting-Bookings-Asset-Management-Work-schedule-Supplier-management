@if($menucurr->parent !=0 && $menuparent->parents()->get()->first()!=null)
<li>
      <a href="{{$menuparent->parents()->get()->first()->link}}">{{$menuparent->parents()->get()->first()->title}}</a>

</li>
@endif