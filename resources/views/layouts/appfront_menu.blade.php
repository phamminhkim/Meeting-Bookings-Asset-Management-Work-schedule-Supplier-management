<ul class="submenu" style="display: block;">
    @foreach ($childs as $child)
        <li class="">
            <a href="@if (count($child->childs) == 0) {{ $child->link }}  @else# @endif"
                @if (count($child->childs) > 0) class="dropdown-toggle" @endif>
                <i class="menu-icon {{ $child->icon }}"></i>
                {{ __($child->title) }}

                @if (count($child->childs) > 0)
                    <b class="arrow fa fa-angle-down"></b>
                @endif
            </a>

            <b class="arrow"></b>
            @if (count($child->childs) > 0)
                @include('layouts.appfront_menu', ['childs' => $child->childs])
            @endif

        </li>
    @endforeach

</ul>
