<template>
    <div>
    <div class="content-header" style="width:71%;background:#3c8dbc;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-5">
                        <h5 class="m-0 text-white"> <i class="fas fa-caret-left"></i></h5>
                        
                    </div>
                    <div class="col-sm-7" style="color: white; font-size: 20px;font-weight: 700;">
                       Giao dịch
                    </div>
                </div>
            </div>
    </div>
    <div class="card" style="width:71%">
        <div class="card-body">
            <div class="card-title" style="width:100%;border-bottom: 1px solid gray;">
                <div class="row">
                    <div class="col-sm-1">
                        <i class="fas fa-arrow-alt-circle-left"  style="font-size:20px;color:ghostwhite;background:darkred;border-radius:30px;"></i>
                    </div>
                    <div class="col-sm-3">
                        <span>Thu Hồi</span><br>
                        <span style="font-size: 13px;color: darkgray;">Giao dịch - BASE INC</span>
                    </div>
                    <div class="col-sm-6">
                        <b-button class="btn-success" v-b-toggle.sidebar-right style="float:right;color:white">Thêm bình luận</b-button>
                                    <b-sidebar id="sidebar-right" title="Tổng quan" right shadow>
                                    <div class="px-3 py-2">
                                        <label class="text-success">BÌNH LUẬN</label>
                                        <input class="form-control" placeholder="Viết bình luận của bạn" />
                                    </div>
                                    </b-sidebar>
                    </div>
                    <div class="col-sm-2">
                        <b-dropdown variant="outline-dark" id="dropdown-1" text="Tùy chọn" right style="float:right;background:white;color:black" >
                            <b-dropdown-item>First Action</b-dropdown-item>
                            <b-dropdown-item>Second Action</b-dropdown-item>
                            <b-dropdown-item>Third Action</b-dropdown-item>
                        </b-dropdown>
                    </div>
                </div>
               
            </div>
            <div class="">
                <div class="row">
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">NGƯỜI SỬ DỤNG</p>
                        <b-avatar variant="info" src="/goods/hung.jpg" size="2rem"></b-avatar>
                        <span>NGUYỄN NGỌC HÙNG</span>
                    </div>
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">TẠO BỞI</p>
                        <b-avatar variant="info" src="/goods/hung.jpg" size="2rem"></b-avatar>
                        <span>NGUYỄN NGỌC HÙNG</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">NGÀY THÁNG</p>
                        <span>05/12/2020 11:18</span>
                    </div>
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fas fa-clipboard"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">CHÍNH SÁCH</p>
                        
                        <span>Policy Recall 1</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fad fa-signal-3"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">KHO HÀNG</p>
                        <span>Thiên long</span>
                    </div>
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <!-- <i class="fas fa-clipboard"></i> -->
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <!-- <p style="color: darkgray;">CHÍNH SÁCH</p>
                        
                        <span>Policy Recall 1</span> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <p style="color: darkgray;">GHI CHÚ</p>
                        <span>Không có ghi chú</span>
                    </div>
                    <div class="col-sm-1" style="margin-top:10px;color: darkgray;">
                        <!-- <i class="fas fa-clipboard"></i> -->
                    </div>
                    <div class="col-sm-5" style="margin-top:10px;">
                        <label class="text-success">Tệp đính kèm: <span  class=text-secondary>Không có tệp đính kèm</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- body 2-->
    <div class="card" style="width:71%">
        <div class="card-body">
            <div class="form-group">
                                    <b-table small hover headVariant :items="items" :fields="fields" id="table">
                                        <template v-slot:cell(#)=data>
                                            {{ data.index + 1 }}
                                        </template>
                                        <template #head(#)=data>
                                            <span class="text-success">{{ data.label}}</span>
                                        </template>
                                        <template #head(Mã_tài_sản)=data>
                                            <span class="text-success">{{ data.label}}</span>
                                        </template>
                                        <template #head(Tên)=data>
                                            <span class="text-success">{{ data.label}}</span>
                                        </template>
                                        <template #head(Kiểu_tài_sản)=data>
                                            <span class="text-success">{{ data.label}}</span>
                                        </template>
                                        <template #cell(Kiểu_tài_sản)=data>
                                            <span v-if="data.item.Kiểu_tài_sản == 'Tài sản'" class="text-secondary"> <i class="fas fa-stop"></i></span>
                                            <span v-if="data.item.Kiểu_tài_sản == 'Công cụ dụng cụ'" class="text-secondary"> <i class="fas fa-suitcase"></i></span>
                                            <span>{{data.item.Kiểu_tài_sản}}</span>
                                        </template>
                                        <template #head(Số_lượng)=data>
                                            <span class="text-success">{{ data.label}}</span>
                                        </template>
                                        <template #cell(Số_lượng)=data>
                                            <div style="width:50%;background:darkgray;color:white"  class="btn btn-sm" >{{data.item.Số_lượng}}</div>
                                        </template>
                                    </b-table>                          
                                </div>
        </div>
    </div>
</div>
</template>

<script>
    export default{
        data(){
            return {
                fields: ['#','Mã_tài_sản','Tên','Kiểu_tài_sản','Số_lượng'],
                items: [
                { Mã_tài_sản: 'MIP-3-407', Tên: 'Iphone 5S', Kiểu_tài_sản: 'Tài sản',Số_lượng: 1 },
                { Mã_tài_sản: 'MIP-4-399', Tên: 'Samsung A7 (2018)', Kiểu_tài_sản: 'Tài sản',Số_lượng: 1 },
                { Mã_tài_sản: 'TCMLT-76', Tên: 'Chuột máy tính Logitech', Kiểu_tài_sản: 'Công cụ dụng cụ',Số_lượng: 1 },
               
                ],
            }
        }
    }
</script>