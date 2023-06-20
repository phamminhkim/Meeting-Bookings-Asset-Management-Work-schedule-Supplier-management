@if(session('success'))

<div class="alert alert-block alert-success">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>
    <p>
        <strong>
            <i class="ace-icon fa fa-check"></i>
            Well done!
        </strong>
        {{session('success')}}
    </p>

    <!-- <p>
        <button class="btn btn-sm btn-success">Do This</button>
        <button class="btn btn-sm">Or This One</button>
    </p> -->
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>

    <strong>
        <i class="ace-icon fa fa-times"></i>
        Oh!
    </strong>
    {{session('error')}}
    <br />
</div>
@endif

@if(count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>
    <strong>Warning!</strong>

    {{session('warning')}}
    <br />
</div>
@endif

@if(session('info'))
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>
    <strong>Info!</strong>

    {{session('info')}}
    <br />
</div>
@endif