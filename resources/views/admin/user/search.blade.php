<div class="nav-search" id="nav-search">
    <form class="form-search" action="{{url('admin/search_user')}}" method="GET">
        <span class="input-icon">
            <input type="text" value="@if(isset($searchTerm)){{$searchTerm}}@endif" placeholder="Tìm ..." class="nav-search-input" id="nav_search_input" name="nav_search_input" autocomplete="off">
            <i class="ace-icon fa fa-search nav-search-icon"></i>
        </span>
    </form>
</div>