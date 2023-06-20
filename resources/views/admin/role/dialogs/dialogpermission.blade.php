<div  id="QuyenDialog" class="modal in" tabindex="-1" style="display: none;" padding-right: 17px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="blue bigger">Danh sách quyền</h4>
                    </div>

                    <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-10">
                            <input type="text" name="QuyenDialog_txtsearch" id="QuyenDialog_txtsearch" class="form-control" placeholder="Tìm">
                        </div>
                        <button id="QuyenDialog_btntim" type="button" class="btn btn-white btn-default btn-round">Tìm</button>
                    </div>
                            <br>
                        <div class="row">
                            <div class="col-xs-12 ">
                            <div class="form-group">
                      <div class="grid-table">

                          <table class="table table-bordered" id="QuyenDialog_tablemodel">
                              <thead>
                                  <tr>
                                      <th style="text-align:center;"><input type="checkbox" id="chkallquyen"></th>
                                      <th>id </th>
                                      <th>Tên</th>
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody id="QuyenDialog_body">

                              </tbody>
                          </table>
                          <div id="QuyenDialog_modalpaging" style="display: block;text-align: center;">
                          </div>
                      </div>

                  </div>

                    </div>
                </div>
                    </div>

                    <div class="modal-footer center">

                        <a href="#" id="btncloseQuyen" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>Đóng</a>

                        <a href="#" class="btn btn-sm btn-primary" id="enterQuyen"> <i class="ace-icon fa fa-check"></i> Chọn</a>
                    </div>
                </div>
            </div>
        </div>
  <!-- mode-dialog -->
