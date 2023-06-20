@extends('layouts.appadmin')

@section('content')


<div class="breadcrumbs ace-save-state" id="breadcrumbs">
  <ul class="breadcrumb">
    <li>
      <i class="ace-icon fa fa-home home-icon"></i>
      <a href="admin/admin_dashboard">Admin</a>
    </li>
    <li>

      <a href="admin/roles">Roles</a>
    </li>
    <li class="active">Tạo mới</li>
  </ul><!-- /.breadcrumb -->

</div>


<div class="page-content">
  <div class="page-header">
    <h1>
      Tạo Role</h1>
  </div><!-- /.page-header -->
  <form class="form-horizontal" action="{{route('adminroles.store')}}" method="POST" role="form">
    <div class="row">
      <div class="col-xs-12">

        @include('partials.alert')

        @csrf
        <div class="form-group">
          <label class="col-sm-3 control-label no-padding-right" for="name"> Tên Role </label>

          <div class="col-sm-9">
            <input type="text" id="name" name="name" placeholder="Nhập tên Role" required class="col-xs-10 col-sm-5">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label no-padding-right" for="description"> Mô tả </label>

          <div class="col-sm-9">
            <input type="text" id="description" name="description" placeholder="Nhập Mô tả" class="col-xs-10 col-sm-5">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label no-padding-right" for="name"> Trạng thái </label>

          <div class="col-sm-9">
            <div class="radio">
              <label>
                <input name="active" value="1" checked checked type="radio" class="ace">
                <span class="lbl"> Đang hoạt động</span>
              </label>
              <label>
                <input name="active" value="0" type="radio" class="ace">
                <span class="lbl"> Ngưng hoạt động</span>
              </label>
            </div>


          </div>
        </div>

        <div class="space-4"></div>
        <div class="clearfix">
          <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn btn-info " type="button">
              <i class="ace-icon fa fa-check bigger-110"></i>
              Lưu
            </button>

            &nbsp; &nbsp; &nbsp;
            <button class="btn" type="reset">
              <i class="ace-icon fa fa-undo bigger-110"></i>
              Xóa
            </button>
            &nbsp; &nbsp; &nbsp;
            <a class="btn" href="{{route('adminroles.index')}}" type="button">
              <i class="ace-icon fa fa-close bigger-110"></i>
              Đóng
            </a>
          </div>
        </div>

        <div class="hr hr-18 dotted hr-double"></div>


      </div><!-- /.col -->
      <div class="col-sm-12">
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs" id="myTab3">
            <li class="active">
              <a data-toggle="tab" href="#menu" aria-expanded="true">
                <i class="pink ace-icon fa fa-tachometer bigger-110"></i>
                Menu
              </a>
            </li>

            <li class="">
              <a data-toggle="tab" href="#permission" aria-expanded="false">
                <i class="blue ace-icon fa fa-user bigger-110"></i>
                Quyền
              </a>
            </li>

            <li class="">
              <a data-toggle="tab" href="#sloc" aria-expanded="false">
                <i class="ace-icon fa fa-rocket"></i>
                Kho
              </a>
            </li>
          </ul>

          <div class="tab-content">
            <div id="menu" class="tab-pane active">
              @include('admin.role.tabs.tab_menu')
            </div>

            <div id="permission" class="tab-pane">
              @include('admin.role.tabs.tab_permission')

            </div>

            <div id="sloc" class="tab-pane">
              @include('admin.role.tabs.tab_sloc')

            </div>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </form>

</div>
@include('admin.role.dialogs.dialogpermission')
@include('admin.role.dialogs.dialogsloc')

@endsection

@section('script')

<script>
  function goBack() {
    window.history.back()
  }

  $('#slocDialog').on('click', '#slocDialog_modalpaging a', function() {
    var url = $(this).attr('href');

    var page = $(this).attr('href').split('page=')[1];
    var search = $("#slocDialog_txtsearch").val();
    var row = "";
    var newurl = "admin/search_sloc?data=" + search + "&page=" + page;
    $.ajax({
      url: newurl,
      type: "get",
      dataType: "text",
      success: function(res) {
        // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
        $("#slocDialog_tablemodel tbody").html('');
        var jsonobj = JSON.parse(res);
        var data = jsonobj['data'].list;
        var data = JSON.parse(data);
        //console.log(jsonobj.data.paging);
        $("#slocDialog_tablemodel tbody").html('');
        if (data.data.length > 0) {
          var list = data.data;
          for (const [key, value] of Object.entries(list)) {
            var html = '';
            html += '<tr>';
            html += '<td style="text-align:center;">';
            html += "<input type='checkbox' name='slocchonnew[]' value='" + value.id + "'>";
            html += '</td>';
            html += '<td>';
            html += value.name;
            html += '</td>';
            html += '<td>';
            html += value.description;
            html += '</td>';
            html += '<td  >';
            html += '';
            html += '</td>';

            html += '<tr>';

            $("#slocDialog_tablemodel tbody").append(html);

            $("#slocDialog_modalpaging").html(jsonobj.data.paging);
          }
        }
        //window.history.pushState({path:url},'',url);
      }
    });

    return false;
  });

  var slocids = [];

  function xoaitemsloc(ui, e) {
    if (confirm('Xóa item?') == true) {

      for (let index = 0; index < slocids.length; index++) {
        if (slocids[index] == ui.getAttribute("value"))
          slocids.splice(index, 1);
      }
      $(ui.parentNode.parentNode).remove();
    } else {
      //console.log('NO');
    }
  }

  //modal dialg user : nút chọn all

  $('#chkallsloc').click(function() {
    var checked = $(this).prop('checked')
    $('#slocDialog_body input[type=\'checkbox\']').prop('checked', checked)
  });

  $("#entersloc").click(function() {

    var list = $('input[name="slocchonnew[]"]');

    //console.log(slocids);
    $('input[name="slocchonnew[]"]').each(function() {
      if ($(this).is(':checked') == true) {
        //kiểm tra đã add chưa, nếu chưa thì add vào , nếu có rồi thì không add
        // alert(jQuery.inArray($(this).val(), slocids));
        if (jQuery.inArray($(this).val(), slocids) === -1) {
          slocids.push($(this).val());
          var url = "admin/get_sloc?idsloc=" + $(this).val();

          $.ajax({
            url: url,
            type: "get",
            dataType: "text",
            success: function(res) {
              // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
              var jsonobj = JSON.parse(res);
              var data = jsonobj['data'].list;
              var data = JSON.parse(data);
              // console.log(jsonobj.data);
              if (data.data.length > 0) {
                var list = data.data;
                for (const [key, value] of Object.entries(list)) {

                  //toastr.success('Đã thêm:' + value.description);
                  var html = '';
                  html += '<tr>';
                  html += '<td>';
                  html += "<input type='hidden' name='slocids[]' value='" + value.id + "'>";
                  html += '<span>';
                  html += value.id;
                  html += '</span>';
                  html += '</td>';
                  html += '<td>';
                  html += value.description;
                  html += '</td>';
                  html += '<td  >';
                  html += '<span style="cursor:pointer;"  onclick="xoaitemsloc(this)" value=' + value.id + '>';
                  html += '<i class="fa fa-trash"></i>';
                  html += '</span>';

                  html += '</td>';
                  html += '<tr>';
                  $("#tbsloc tbody").append(html);
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log("lỗi");
            }
          });
        }
      }
    });
    return false;
  });


  $(document).ready(function() {

    $("#btnchonsloc").click(function() {

      var row = "";
      $('#chkallsloc').prop('checked', false);
      var search = $("#slocDialog_txtsearch").val();
      var url = "admin/search_sloc?data=" + search;

      $.ajax({
        url: url, //"../ajaxhelper/helper.php?action=get_list_foods&txtsearch=" + search,
        type: "get",
        dataType: "text",
        success: function(res) {
          // Kiểm tra xem thông tin gửi lên có bị lỗi hay không

          var jsonobj = JSON.parse(res);
          var data = jsonobj['data'].list;
          var data = JSON.parse(data);
          // console.log(jsonobj.data.paging);
          $("#slocDialog_tablemodel tbody").html('');
          if (data.data.length > 0) {
            var list = data.data;
            for (const [key, value] of Object.entries(list)) {
              var html = '';
              html += '<tr>';
              html += '<td style="text-align:center;">';
              html += "<input type='checkbox' name='slocchonnew[]' value='" + value.id + "'>";
              html += '</td>';
              html += '<td>';
              html += value.id;
              html += '</td>';
              html += '<td>';
              html += value.description;
              html += '</td>';
              html += '<td  >';
              html += '';
              html += '</td>';
              html += '<tr>';
              $("#slocDialog_tablemodel tbody").append(html);

              $("#slocDialog_modalpaging").html(jsonobj.data.paging);
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log("lỗi");
        }
      });

    });

  });


  $("#slocDialog_btntim").click(function(e) {
    var row = "";
    var search = $("#slocDialog_txtsearch").val();
    var url = "admin/search_sloc?data=" + search;
    $.ajax({
      url: url, //"../ajaxhelper/helper.php?action=get_list_foods&txtsearch=" + search,
      type: "get",
      dataType: "text",
      success: function(res) {
        // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
        var jsonobj = JSON.parse(res);
        var data = jsonobj['data'].list;
        var data = JSON.parse(data);
        //   console.log(jsonobj.data.paging);
        $("#slocDialog_tablemodel tbody").html('');
        $("#slocDialog_modalpaging").html('');
        if (data.data.length > 0) {
          var list = data.data;
          for (const [key, value] of Object.entries(list)) {
            var html = '';
            html += '<tr>';
            html += '<td style="text-align:center;">';
            html += "<input type='checkbox' name='slocchonnew[]' value='" + value.id + "'>";
            html += '</td>';
            html += '<td>';
            html += value.id;
            html += '</td>';
            html += '<td>';
            html += value.description;
            html += '</td>';
            html += '<td  >';
            html += '';
            html += '</td>';
            html += '<tr>';
            $("#slocDialog_tablemodel tbody").append(html);

            $("#slocDialog_modalpaging").html(jsonobj.data.paging);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("lỗi");
      }
    });
  });
</script>
<script>
  $('#QuyenDialog').on('click', '#QuyenDialog_modalpaging a', function() {
    var url = $(this).attr('href');

    var page = $(this).attr('href').split('page=')[1];
    var search = $("#QuyenDialog_txtsearch").val();
    var row = "";
    var newurl = "admin/search_quyen?data=" + search + "&page=" + page;
    $.ajax({
      url: newurl,
      type: "get",
      dataType: "text",
      success: function(res) {
        // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
        $("#QuyenDialog_tablemodel tbody").html('');
        var jsonobj = JSON.parse(res);
        var data = jsonobj['data'].list;
        var data = JSON.parse(data);
        //console.log(jsonobj.data.paging);
        $("#QuyenDialog_tablemodel tbody").html('');
        if (data.data.length > 0) {
          var list = data.data;
          for (const [key, value] of Object.entries(list)) {
            var html = '';
            html += '<tr>';
            html += '<td style="text-align:center;">';
            html += "<input type='checkbox' name='quyenchonnew[]' value='" + value.id + "'>";
            html += '</td>';
            html += '<td>';
            html += value.name;
            html += '</td>';
            html += '<td>';
            html += value.description;
            html += '</td>';
            html += '<td  >';
            html += '';
            html += '</td>';

            html += '<tr>';

            $("#QuyenDialog_tablemodel tbody").append(html);

            $("#QuyenDialog_modalpaging").html(jsonobj.data.paging);
          }
        }
        //window.history.pushState({path:url},'',url);
      }
    });

    return false;
  });

  var quyenids = [];

  function xoaitemquyen(ui, e) {
    if (confirm('Xóa item?') == true) {

      for (let index = 0; index < quyenids.length; index++) {
        if (quyenids[index] == ui.getAttribute("value"))
          quyenids.splice(index, 1);
      }
      $(ui.parentNode.parentNode).remove();
    } else {
      //console.log('NO');
    }
  }

  //modal dialg user : nút chọn all

  $('#chkallquyen').click(function() {
    var checked = $(this).prop('checked')
    $('#QuyenDialog_body input[type=\'checkbox\']').prop('checked', checked)
  });

  $("#enterQuyen").click(function() {


    var list = $('input[name="quyenchonnew[]"]');
    // console.log(quyenids);
    $('input[name="quyenchonnew[]"]').each(function() {
      if ($(this).is(':checked') == true) {
        //kiểm tra đã add chưa, nếu chưa thì add vào , nếu có rồi thì không add

        if (jQuery.inArray($(this).val(), quyenids) === -1) {
          quyenids.push($(this).val());
          var url = "admin/get_quyen?idquyen=" + $(this).val();
          $.ajax({
            url: url,
            type: "get",
            dataType: "text",
            success: function(res) {

              // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
              var jsonobj = JSON.parse(res);
              var data = jsonobj['data'].list;
              var data = JSON.parse(data);
              // console.log(jsonobj.data);
              if (data.data.length > 0) {
                var list = data.data;
                for (const [key, value] of Object.entries(list)) {
                  //toastr.success('Đã thêm:' + value.description);
                  var html = '';
                  html += '<tr>';
                  html += '<td>';
                  html += "<input type='hidden' name='quyenids[]' value='" + value.id + "'>";
                  html += '<span>';
                  html += value.id;
                  html += '</span>';
                  html += '</td>';
                  html += '<td>';
                  html += value.description;
                  html += '</td>';
                  html += '<td  >';
                  html += '<span style="cursor:pointer;"  onclick="xoaitemquyen(this)" value=' + value.id + '>';
                  html += '<i class="fa fa-trash"></i>';
                  html += '</span>';

                  html += '</td>';
                  html += '<tr>';
                  $("#tbquyen tbody").append(html);
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log("lỗi");
            }
          });
        }
      }
    });
    return false;
  });

  $(document).ready(function() {


    $("#btnchonquyen").click(function() {
      var row = "";
      $('#chkallquyen').prop('checked', false);
      var search = $("#QuyenDialog_txtsearch").val();
      var url = "admin/search_quyen?data=" + search;

      $.ajax({
        url: url, //"../ajaxhelper/helper.php?action=get_list_foods&txtsearch=" + search,
        type: "get",
        dataType: "text",
        success: function(res) {
          // Kiểm tra xem thông tin gửi lên có bị lỗi hay không

          var jsonobj = JSON.parse(res);
          var data = jsonobj['data'].list;
          var data = JSON.parse(data);
          // console.log(jsonobj.data.paging);
          $("#QuyenDialog_tablemodel tbody").html('');
          if (data.data.length > 0) {
            var list = data.data;
            for (const [key, value] of Object.entries(list)) {
              var html = '';
              html += '<tr>';
              html += '<td style="text-align:center;">';
              html += "<input type='checkbox' name='quyenchonnew[]' value='" + value.id + "'>";
              html += '</td>';
              html += '<td>';
              html += value.id;
              html += '</td>';
              html += '<td>';
              html += value.description;
              html += '</td>';
              html += '<td  >';
              html += '';
              html += '</td>';
              html += '<tr>';
              $("#QuyenDialog_tablemodel tbody").append(html);

              $("#QuyenDialog_modalpaging").html(jsonobj.data.paging);
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log("lỗi");
        }
      });

    });

  });


  $("#QuyenDialog_btntim").click(function(e) {
    var row = "";
    var search = $("#QuyenDialog_txtsearch").val();
    var url = "admin/search_quyen?data=" + search;
    $.ajax({
      url: url, //"../ajaxhelper/helper.php?action=get_list_foods&txtsearch=" + search,
      type: "get",
      dataType: "text",
      success: function(res) {
        // Kiểm tra xem thông tin gửi lên có bị lỗi hay không
        var jsonobj = JSON.parse(res);
        var data = jsonobj['data'].list;
        var data = JSON.parse(data);
        //   console.log(jsonobj.data.paging);
        $("#QuyenDialog_tablemodel tbody").html('');
        $("#QuyenDialog_modalpaging").html('');
        if (data.data.length > 0) {
          var list = data.data;
          for (const [key, value] of Object.entries(list)) {
            var html = '';
            html += '<tr>';
            html += '<td style="text-align:center;">';
            html += "<input type='checkbox' name='quyenchonnew[]' value='" + value.id + "'>";
            html += '</td>';
            html += '<td>';
            html += value.id;
            html += '</td>';
            html += '<td>';
            html += value.description;
            html += '</td>';
            html += '<td  >';
            html += '';
            html += '</td>';
            html += '<tr>';
            $("#QuyenDialog_tablemodel tbody").append(html);

            $("#QuyenDialog_modalpaging").html(jsonobj.data.paging);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("lỗi");
      }
    });
  });
</script>



@endsection