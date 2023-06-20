@extends('layouts.appadmin')

@section('content')


<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="admin/home">Admin</a>
        </li>
        <li class="active">Menu</li>
    </ul><!-- /.breadcrumb -->

</div>
<div id="load"></div>
<div class="page-content">


    <div class="page-header">
        <h1>
            Danh sách menu

        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="label" class="col-sm-3 control-label no-padding-right">
                        Tiêu đề
                    </label>
                    <div class="col-sm-9"><input id='label' name="label" required type="text" class="col-xs-10 col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="link" class="col-sm-3 control-label no-padding-right">
                        Liên kết
                    </label>
                    <div class="col-sm-9">
                        <input id='link' name="link" type="text" required class="col-xs-10 col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="icon" class="col-sm-3 control-label no-padding-right">
                        Icon
                    </label>
                    <div class="col-sm-9">
                        <input id='icon' name="icon" type="text" class="col-xs-10 col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="icon" class="col-sm-3 control-label no-padding-right">
                        Áp dụng với Domain
                    </label>
                    <div class="col-sm-9">
                        <input id='domain' text="*" name="domain" type="text" class="col-xs-10 col-sm-5"></div>
                </div>
                <div class="clearfix ">
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="button" id="submit">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Lưu
                        </button>

                        &nbsp; &nbsp; &nbsp;
                        <button class="btn" type="reset" id="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hr hr-24"></div>
    <input type="hidden" id="nestable-output">
    <input type="hidden" id="id">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <menu id="nestable-menu">
        <button type="button" class="btn btn-default" data-action="expand-all">Expand All</button>
        <button type="button" class="btn btn-default" data-action="collapse-all">Collapse All</button>
        <button id='update-all' type="button" class="btn btn-default">Update All</button>
    </menu>

    <div class="row">
        <div class="col-xs-12">
            @include('partials.alert')
            <div class="dd dd-draghandle" id="nestable">
                <ol class="dd-list" id="menu-id">
                    @foreach($list as $menu)
                    <li class="dd-item dd2-item" data-id="{{$menu->id}}">
                        <div class="dd-handle dd2-handle">
                            <i id="icon_show{{$menu->id}}" class="{{$menu->icon}}"></i>
                            <i class="drag-icon ace-icon fa fa-arrows bigger-125"></i>
                        </div>
                        <div class="dd2-content">
                            <span id="label_show{{$menu->id}}">{{$menu->title}}</span>
                            <span class="pull-right action-buttons">/<span id="link_show{{$menu->id}}">{{$menu->link}}</span>
                                <a class="edit-button" id="{{$menu->id}}" label="tieu de" link="{{$menu->link}}" icon="{{$menu->icon}}"><i class="ace-icon fa fa-pencil blue bigger-130"></i></a>
                                <a class="del-button" id="{{$menu->id}}"><i class="ace-icon fa fa-trash-o red bigger-130"></i></a></span>
                        </div>
                        @if(count($menu->childs)>0)
                        @include('admin.menu.menuchilds',['childs' => $menu->childs])
                        @endif
                    </li>
                    @endforeach

                </ol>
            </div>
        </div>

    </div>
</div>
<style>
    /* fix khoảng cách bên dưới table so với phân trang */

    .table {
        margin-bottom: 0px;
    }
</style>

@endsection

@section('script')
<script src="assets/js/jquery.nestable.min.js"></script>
<script type="text/javascript">
    jQuery(function($) {

        $('.dd').nestable();

        $('.dd-handle a').on('mousedown', function(e) {
            e.stopPropagation();
        });

        $('[data-rel="tooltip"]').tooltip();

    });
</script>
<script>
    $(document).ready(function() {

        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                console.log(list.nestable('serialize'));
            } else {
                output.val('JSON browser support required.');
            }
        };

        // activate Nestable for list 1
        $('#nestable').nestable({
                group: 1
            })
            .on('change', updateOutput);



        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));

        $('#nestable-menu').on('click', function(e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });


    });
</script>

<script>
    $(document).ready(function() {
        $("#load").hide();
        $("#submit").click(function() {
            $("#load").show();

            if ($("#label").val().trim() == '' || $("#link").val().trim() == '') {
                alert('Chưa nhập Tiêu đề và liên kết');
                return;
            }
            var dataString = {
                label: $("#label").val(),
                link: $("#link").val(),
                icon: $("#icon").val(),
                id: $("#id").val(),
                _token: $('input#_token').val(),
            };

            $.ajax({
                type: "POST",
                url: "{{route('adminmenus.store')}}",
                data: dataString,
                dataType: "json",
                cache: false,
                success: function(data) {

                    // alert(data.menu);
                    if (data.type == 'add') {
                        $("#menu-id").append(data.menu);
                    } else if (data.type == 'edit') {
                        $('#label_show' + data.id).html(data.label);
                        $('#link_show' + data.id).html(data.link);
                        $('#icon_show' + data.id).removeClass();
                        var icon = "normal-icon ace-icon " + data.icon + " bigger-130";
                        $('#icon_show' + data.id).addClass(icon);
                    } else if (data.type == 'err') {
                        alert(data.error);
                    }
                    $('#label').val('');
                    $('#link').val('');
                    $('#id').val('');
                    $('#icon').val('');
                    $("#load").hide();
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
        });

        $('.dd').on('change', function() {
            $("#load").show();

            var dataString = {
                data: $("#nestable-output").val(),
                _token: $('input#_token').val(),
            };
            // alert($("#nestable-output").val());

            $.ajax({
                type: "POST",
                url: "{{route('adminupdate_tree')}}",
                data: dataString,
                cache: false,
                success: function(data) {
                    // alert(data.menu);
                    $("#load").hide();
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
        });

        $("#save").click(function() {
            $("#load").show();

            var dataString = {
                data: $("#nestable-output").val(),
            };

            $.ajax({
                type: "POST",
                url: "{{route('adminupdate_tree')}}",
                data: dataString,
                cache: false,
                success: function(data) {
                    $("#load").hide();
                    alert('Data has been saved');

                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
        });

        $(document).on("click", ".del-button", function() {
            var x = confirm('Delete this menu?');
            var id = $(this).attr('id');
            var dataString = {
                id: id,
                _token: $('input#_token').val(),
            };
            if (x) {
                $("#load").show();
                $.ajax({
                    type: "DELETE",
                    url: "{{route('adminmenus.destroy','id')}}",
                    data: dataString,
                    cache: false,
                    success: function(data) {

                        $('#label').val('');
                        $('#link').val('');
                        $('#id').val('');
                        $('#icon').val('');

                        $("#load").hide();
                        $("li[data-id='" + id + "']").remove();
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    },
                });
            }
        });

        $(document).on("click", ".edit-button", function() {
            var id = $(this).attr('id');
            //alert(id);
            var label = $('#label_show' + id).html(); //   $(this).attr('label');
            var link = $('#link_show' + id).html(); // $(this).attr('link');
            var icon = $('#icon_show' + id).attr('class').replace('normal-icon ace-icon', '');
            icon = icon.replace('bigger-130', '');
            $("#id").val(id);
            $("#label").val(label);
            $("#link").val(link);
            $("#icon").val(icon == null ? "" : icon);


        });

        $(document).on("click", "#reset", function() {
            $('#label').val('');
            $('#link').val('');
            $('#id').val('');
            $('#icon').val('');
        });

    });
</script>
@endsection