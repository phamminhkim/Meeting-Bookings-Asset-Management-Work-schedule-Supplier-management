<div class="card">
    <div class="card-header">
        <span class="btn btn-white btn-info btn-bold" data-toggle="modal" id="btnchonquyen" data-target="#QuyenDialog">Chọn Quyền</span>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
    <div class="card-body" id="tbquyen">
        <table class="table table-bordered">
            <thead>
                <th>id</th>
                <th>Mô tả</th>
                <th>Xóa</th>
            </thead>
            <tbody>
                @foreach($rolepermission as $per)
                <tr>
                    <td><input type="hidden" name="quyenids[]" value="{{$per->id}}"><span>{{$per->id}}</span></td>

                    <td>{{$per->description}}</td>
                    <td><span style="cursor:pointer;" onclick="xoaitemquyen(this)" value="{{$per->id}}"><i class="fa fa-trash"></i></span></td>
                </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>