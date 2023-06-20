<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-layer-group"></i>Nhóm tài sản</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-10">
                                <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                <div class="input-group mb-3" style="width:70%;float:right;" >
                                    <input type="text" class="form-control" placeholder="Lọc nhanh" aria-label="Lọc nhanh" aria-describedby="basic-addon2" style="border-right:none;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="background:white;color:gainsboro" id="basic-addon2"><i class="fas fa-search"></i></span>
                                    </div>
                                    </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="float-sm-left">
                                    <b-dropdown id="dropdown-1" right text="Tạo mới" variant="primary">
                                        <b-dropdown-item >Trích xuất ra file Excel</b-dropdown-item>
                                       
                                    </b-dropdown>
                            <!-- <button  class="btn btn-info btn-sm" @click="showAssetStock()"> <i class="fa fa-plus"></i>
                                                {{ $t('form.create')}}</button> -->
                            </div>
                            </div>
                        </div>

                        

                    </div>
                </div>
            </div>
        </div>
        <b-tabs content-class="mt-1" small>
            <b-tab title="NHÓM TÀI SẢN" active id="tatca">
              <div class="card" style="width:80%;margin:0 auto;">
                <div class="card-body">
                
                    <div class="row" style="border-bottom:1px solid whitesmoke">
                        <div class="col-sm-1">
                            <i class="fas fa-folder text-secondary"></i>
                        </div>
                        <div class="col-sm-9">
                            <b>Máy tính văn phòng kỹ thuật</b><br>
                            <span class="text-secondary">Không có nội dung</span><br>
                            <span class="text-secondary">Tạo bởi @huyenn at 14:42 29/12/2020</span>
                        </div>
                        <div class="col-sm-2">
                            <span><b>ID : 14</b></span>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;border-bottom:1px solid whitesmoke">
                        <div class="col-sm-1">
                            <i class="fas fa-folder text-secondary"></i>
                        </div>
                        <div class="col-sm-9">
                            <b>Thanh Huyen Corp - Default category</b><br>
                            <span class="text-secondary">This is a default category of system</span><br>
                            <span class="text-secondary">Tạo bởi @huyenn at 14:42 29/12/2020</span>
                        </div>
                        <div class="col-sm-2">
                            <span ><b>ID : 13</b></span>
                        </div>
                    </div>
                </div>
              </div>
               
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
    export default {
        data() {
            return {
               
            }
        },
        methods: {
            
        },
    }

</script>