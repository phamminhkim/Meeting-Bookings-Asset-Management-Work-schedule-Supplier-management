<ul class="nav nav-treeview">
    @foreach ($childs as $child)
        <li
            class="nav-item  @if (!$child->has_childrens) has-treeview @else @endif 

                @if ($is_opening || ($child->left > $menucurr_root->left && $child->right < $menucurr_root->right)) menu-open @endif ">
            <a href="@if (!$child->has_childrens) {{ $child->link }}@else# @endif"
                class="nav-link @if ($child->id == $menucurr->id) active @endif ">
                <i class="dot"></i>
                <p>
                    {{ __($child->title) }}
                    @if ($child->has_childrens)
                        <i class="fas fa-angle-right right"></i>
                    @endif
                </p>
            </a>
            @if ($child->has_childrens)
                @include('layouts.appfront_menu_new', ['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
