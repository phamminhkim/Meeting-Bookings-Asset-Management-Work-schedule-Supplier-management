<div class="card">
    <div class="card-header">
        <span class="btn btn-white btn-info btn-bold" data-toggle="modal" id="btnchonsloc" data-target="#dialogsloc">Chọn Kho</span>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
    <div class="card-body" id="tbsloc">
        <table class="table table-bordered">
            <thead>
                <th>id</th>
                <th>Mô tả</th>
                <th>Xóa</th>
            </thead>
            <tbody>
                @foreach($rolesloc as $sloc)
                <tr>
                    <td><input type="hidden" name="slocids[]" value="{{$sloc->id}}"><span>{{$sloc->id}}</span></td>

                    <td>{{$sloc->description}}</td>
                    <td><span style="cursor:pointer;" onclick="xoaitemsloc(this)" value="{{$sloc->id}}"><i class="fa fa-trash"></i></span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>