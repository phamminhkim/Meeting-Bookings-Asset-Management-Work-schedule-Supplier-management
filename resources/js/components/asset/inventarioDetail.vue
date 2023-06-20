<template>
    <div>
        <form @submit.prevent="Adddetail">
            <div class="content-header" style="width:100%;background:#3c8dbc;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="float-left">
                                <h5 class="m-0 text-white" @click="lose()"> <i class="fas fa-caret-left" style="cursor:pointer"></i></h5>
                            </div>
                            
                            <div class="" style="color: white; font-size: 20px;font-weight: 700;">
                                {{ title }}
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="card" style="width:100%;display:inline-block;">
                <div class="card-header" v-for="user in list_user">
                    <div class="row">
                        <div class="col-sm-9 mb-2">
                            
                            <b-avatar variant="info" :src="user.avatar" class="mr-2" size="3rem">
                            </b-avatar>
                            <span><b class="font-weight-bold"> {{ $t(getUserName(user_id)) }} </b>
                                <!-- <span class="text-secondary font-weight-bold">Mã nhân viên: {{ user.username }}
                                </span> -->
                                <!-- <span class="text-secondary font-weight-bold">Công ty: {{ user.company_id }} </span> -->
                            </span>
                        </div>
                       
                        <div v-if="getcomplete(inven) === 0" class="col-sm-3">
                           
                            <div class="float-right">
                                <b-button @click="speed_detail()" type="submit"
                                    class="btn btn-success btn-sm float-sm-right">Xác nhận</b-button>
                            </div>
                        </div>
                        <div v-if="getcomplete(inven) === 1" class="col-sm-3">
                           
                            <div class="float-right">
                                <b-button @click="speed_detail()" type="submit"
                                    class="btn btn-success btn-sm float-sm-right" :disabled="enabled">Đã xác
                                    nhận</b-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <div class="form-group" style="font-size:12px">
                        <div class="row">
                            <div class="col-sm-3"
                                style="border-bottom: 1px solid whitesmoke;border-right: 1px solid whitesmoke;">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <i class="fas fa-cube text-secondary"></i>
                                    </div>
                                    <div class="col-sm-10">
                                        <span class="text-secondary">Tài sản</span><br>
                                        <span><b>5</b> Tài sản</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"
                                style="border-bottom: 1px solid whitesmoke;border-right: 1px solid whitesmoke;">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <i class="fas fa-layer-group text-secondary"></i>
                                    </div>
                                    <div class="col-sm-10">
                                        <span class="text-secondary">CÔNG CỤ DỤNG CỤ</span><br>
                                        <span><b>0</b> Công cụ dụng cụ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"
                                style="border-bottom: 1px solid whitesmoke;border-right: 1px solid whitesmoke;">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div class="col-sm-10">
                                        <span class="text-secondary">TỰ XÁC NHẬN</span><br>
                                        <span><b>0/5</b> Tài sản được xác nhận</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"
                                style="border-bottom: 1px solid whitesmoke;border-right: 1px solid whitesmoke;">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div class="col-sm-10">
                                        <span class="text-secondary">NGƯỜI GIÁM SÁT XÁC NHẬN</span><br>
                                        <span><b>4/5</b> Tài sản được xác nhận</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group" style="font-size:12px">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="float-left mr-2">
                                    <i class="fas fa-clock text-secondary"></i>
                                </div>
                                <div class="float-left">
                                    <span class="text-secondary">THỜI GIAN KIỂM KÊ</span><br>
                                    <span>Trạng thái tài sản tại thời điểm {{ gettime(inven) }}</span>
                                </div>
                            </div>
                           
                            <div class="col-sm-6">
                                <div class="float-left mr-2">
                                    <i class="fas fa-clock text-secondary"></i>
                                </div>
                                <div class="float-left">
                                    <span class="text-secondary">THỜI GIAN XÁC NHẬN</span><br>
                                    <span>{{ time }}</span>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="form-group" style="font-size:12px">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="float-left mr-2">
                                    <i class="fas fa-bars text-secondary"></i>
                                </div>
                                <div class="float-left">

                                    <span class="text-secondary">KHO</span><br>
                                    <span> {{ $t(getWarehouse(ware_house)) }} </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <!-- <span class="text-secondary">NỘI DUNG KIỂM KÊ</span><br>
                                <span>Kiểm kê</span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btu" style="display:inline-block;float:right;">
                <!-- <b-button v-b-toggle.sidebar-right id="th" style="background:rgb(60, 141, 188);border:none"><i
                        class="fa fa-th" style="font-size:23px"></i>
                </b-button> -->
                <b-sidebar id="sidebar-right" title="TỔNG QUAN" right shadow>
                    <div class="px-3 py-2">
                        <div class="group" style="border-bottom:3px solid whitesmoke;height: 100px;">
                            <p class="text-success">KHO HÀNG</p>
                            <div class="row">
                                <div class="col-sm-1">
                                    <i class="fas fa-signal text-secondary"></i>
                                </div>
                                <div class="col-sm-11">
                                    <span><b>Kho hàng Trường Sơn</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="group" style="border-bottom:3px solid whitesmoke;margin-top:20px">
                            <p class="text-success">NGƯỜI SỬ DỤNG GHI CHÚ</p>
                        </div>
                        <div class="group" style="border-bottom:3px solid whitesmoke;margin-top:20px">
                            <p class="text-success">BÌNH LUẬN</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <b-avatar variant="info" src="/goods/hung.jpg" size="2rem">
                                    </b-avatar>
                                </div>
                                <div class="col-sm-9">
                                    <p><span class="text-primary">Nguyễn Ngọc Hùng</span> confirmed the asset
                                        [MIP-18-585] Iphone X Red import with status ready, update action [no]<br>
                                        <span class="text-secondary" style="font-size:13px;"><i
                                                class="fas fa-thumbs-up"></i> Like - trả lời (0) - 10:47 Feb 03</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <b-avatar variant="info" src="/goods/hung.jpg" size="2rem">
                                    </b-avatar>
                                </div>
                                <div class="col-sm-9">
                                    <p><span class="text-primary">Nguyễn Ngọc Hùng</span> confirmed the asset
                                        [MIP-18-585] Iphone X Red import with status ready, update action [no]<br>
                                        <span class="text-secondary" style="font-size:13px;"><i
                                                class="fas fa-thumbs-up"></i> Like - trả lời (0) - 10:47 Feb 03</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <b-avatar variant="info" src="/goods/hung.jpg" size="2rem">
                                    </b-avatar>
                                </div>
                                <div class="col-sm-9">
                                    <p><span class="text-primary">Nguyễn Ngọc Hùng</span> confirmed the asset
                                        [MIP-18-585] Iphone X Red import with status ready, update action [no]<br>
                                        <span class="text-secondary" style="font-size:13px;"><i
                                                class="fas fa-thumbs-up"></i> Like - trả lời (0) - 10:47 Feb 03</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <b-avatar variant="info" src="/goods/hung.jpg" size="2rem">
                                    </b-avatar>
                                </div>
                                <div class="col-sm-9">
                                    <p><span class="text-primary">Nguyễn Ngọc Hùng</span> confirmed the asset
                                        [MIP-18-585] Iphone X Red import with status ready, update action [no]<br>
                                        <span class="text-secondary" style="font-size:13px;"><i
                                                class="fas fa-thumbs-up"></i> Like - trả lời (0) - 10:47 Feb 03</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="group" style="border-bottom:3px solid whitesmoke;margin-top:20px">
                            <input v-model="comment" class="form-control" placeholder="Viết bình luận của bạn" />
                            <b-button @click="Addcomment()"> Đăng </b-button>
                        </div>
                    </div>
                </b-sidebar>
            </div>
            <div class="card" style="width:100%;">
                <div class="card-header title_header text-uppercase">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="font-weight-bold">Danh sách tài sản</label>
                        </div>
                    </div>
                </div>
                <div class="">
                    <b-table small responsive hover headVariant :items="list_asset" :fields="fields" id="table">
                        <template v-slot:cell(#)=data>
                            <span class="text-secondary font-weight-bold">{{ data.index + 1 }}</span>

                        </template>
                        <template #head(#)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #head(objectable.name)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>

                        <template #head(asset_status_id)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(asset_status_id)=data>
                            <div class="badge bg-success text-center">Tốt</div>
                            <!-- <span class="text-danger" v-if="data.item.System == 'Mất'">{{data.item.System}}</span> -->
                        </template>
                        <template #head(user_confirm_status)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(user_confirm_status)=data>
                            <div v-if="user_id!=data.item.role_id">
                                <select  v-model="data.item.user_confirm_status"
                                    :disabled="enabled"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>

                            </div>
                            <div v-if="user_id==data.item.role_id">
                                <select v-if="getcomplete(inven) === 1" v-model="data.item.user_confirm_status"
                                    :disabled="enabled"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>
                                <select v-model="data.item.user_confirm_status" v-if="getcomplete(inven) === 0"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>
                            </div>
                            <!-- <div  v-if="user_id==data.item.role_id">                           
                              

                                <select v-if="getcomplete(inven) === 1" v-model="data.item.user_confirm_status"
                                    :disabled="enabled"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill ">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>
                                <select v-model="data.item.user_confirm_status" v-if="getcomplete(inven) === 0"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill ">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>

                            </div>
                            <div  v-else>                           
                               
                                <div v-if="getcomplete(inven) === 1" >
                                <div v-if="data.item.user_confirm_status==1"> 
                                    <input class="form-control-sm form-control rounded-pill" placeholder="Tốt" readonly />
                                </div> 
                                <div v-if="data.item.user_confirm_status==9"> 
                                    <input class="form-control-sm form-control rounded-pill" placeholder="Xấu" readonly />
                                </div> 
                                </div> 
                                  
                                <div v-if="getcomplete(inven) === 0" >
                                    <div v-if="data.item.user_confirm_status==1"> 
                                    <input class="form-control-sm form-control rounded-pill" placeholder="Tốt" readonly />
                                </div> 
                                <div v-if="data.item.user_confirm_status==9"> 
                                    <input class="form-control-sm form-control rounded-pill" placeholder="Xấu" readonly />
                                </div> 
                                </div> 

                                 
                            

                            </div> -->
                        </template>
                        <template #head(stocker_confirm_status)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(stocker_confirm_status)=data>
                            <div>
                                <select v-if="getcomplete(inven) === 0" v-model="data.item.stocker_confirm_status"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>
                                <select v-if="getcomplete(inven) === 1" v-model="data.item.stocker_confirm_status"
                                    :disabled="enabled"
                                    class="form-control-sm form-control text-secondary font-weight-bold rounded-pill">
                                    <option class="text-success" value="1" selected>Tốt</option>
                                    <option value="9">Xấu</option>
                                </select>
                            </div>
                        </template>
                        <!-- <template #cell(Người_giám_hộ)=data>
                            <span class="text-success" v-if="data.item.Người_giám_hộ == 'Tốt'">{{data.item.System}}</span>
                            <span class="text-danger" v-if="data.item.Người_giám_hộ == 'Mất'">{{data.item.System}}</span>
                        </template>
                        <template #cell(Hành_động)=data>
                                <b-dropdown id="dropdown-1" right  variant="white">
                                    <template #button-content>
                                        <span class="text-secondary"><i class="fas fa-sticky-note"></i> No action</span>
                                    </template>
                                    <b-dropdown-item>Confirm</b-dropdown-item>
                                    <b-dropdown-item>Self confirm</b-dropdown-item>
                                </b-dropdown>
                        </template> -->
                    </b-table>
                </div>
            </div>
            <div class="card" style="width:100%;">
                <div class="card-header title_headers text-uppercase">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Danh sách công cụ dụng cụ</label>
                        </div>
                    </div>
                </div>
                <div class="">
                    <b-table small responsive hover headVariant :items="list_tool" :fields="fields_tool" id="table">
                        <template v-slot:cell(#)=data>
                            <span class="text-secondary font-weight-bold">{{ data.index + 1 }}</span>

                        </template>
                        <template #head(#)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #head(objectable.name)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>

                        <template #head(use_quantity)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(use_quantity)=data>
                            <div class="badge bg-secondary text-center">

                                <!-- <div v-for="detail in item_detail.asset_inventario_details">
                                  
                                        <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool' && data.item.objectable_id==detail.objectable_id && data.item.user_id==detail.user_id">{{detail.quantity_use}}</span> -->



                                <span v-if="data.item.use_quantity == null">{{ data.item.quantity }}</span>
                                <span v-else>{{ data.item.use_quantity }}</span>

                            </div>
                        </template>
                        <template #head(user_confirm_quantity)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(user_confirm_quantity)=data>

                            <div class="form-group row" v-if="user_id==data.item.role_id">
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                    <input v-if="getcomplete(inven) === 0" v-model="data.item.user_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill" />
                                    <input v-if="getcomplete(inven) === 1" v-model="data.item.user_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill"
                                        :disabled="enabled" />
                                </div>
                                <div class="col-md-3">

                                </div>
                            </div>
                            <div class="form-group row" v-else>
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                  
                                    <input v-if="getcomplete(inven) === 0" v-model="data.item.user_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill" readonly/>
                                    <input v-if="getcomplete(inven) === 1" v-model="data.item.user_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill" readonly
                                        :disabled="enabled" />
                                </div>
                                <div class="col-md-3">

                                </div>
                            </div>
                        </template>
                        <template #head(stocker_confirm_quantity)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #cell(stocker_confirm_quantity)=data>
                            <div class="form-group row">
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                    <input v-if="getcomplete(inven) === 0" v-model="data.item.stocker_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill" />
                                    <input v-if="getcomplete(inven) === 1" v-model="data.item.stocker_confirm_quantity"
                                        type="text" class="form-control-sm form-control rounded-pill"
                                        :disabled="enabled" />
                                </div>
                                <div class="col-md-3">

                                </div>
                            </div>

                        </template>

                    </b-table>
                </div>
            </div>
        </form>

    </div>
</template>
<script>
import moment from 'moment'
export default {
    props: {
        ware_house: String,
        inven: String,
        time: "",
        user_id: String,
        title: ""
    },
    data() {
        return {
            enabled: true,
            fields: [
                {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'objectable.name',
                    label: 'Tên',
                    class: 'text-nowrap font-weight-bold text-secondary'
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'user_confirm_status',
                    label: 'Người dùng đánh giá',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'stocker_confirm_status',
                    label: 'Thủ kho đánh giá',
                    class: 'text-nowrap text-center'
                },

            ],
            fields_tool: [
                {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'objectable.name',
                    label: 'Tên',
                    class: 'text-nowrap font-weight-bold text-secondary'
                },
                {
                    key: 'use_quantity',
                    label: 'Số lượng bàn giao',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'user_confirm_quantity',
                    label: 'Người dùng',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'stocker_confirm_quantity',
                    label: 'Thủ kho',
                    class: 'text-nowrap text-center'
                },
            ],
            url_asset_use: "api/asset/assetuse",
            asset_uses: [],
            token: '',
            loading: false,
            page_url_users: "api/user/all",
            url_asset_kiemke: "api/asset/kiemke",
            url_asset_kiemke_detail: "api/asset/inventario_detail",
            url_asset_inventario: "api/asset/inventario",
            page_url_slocs: "api/asset/warehouse",
            list: [],
            detail: {
                id: '',
                asset_warehouse_id: '',
                asset_inventario_id: '',
                user_id: '',
                objectable_id: '',
                objectable_type: '',
                asset_status_id: '',
                user_confirm_status: '',
                stocker_confirm_status: '',
                user_confirm_quantity: '',
                stocker_confirm_quantity: '',
                comment: '',

                index: ''
            },
            item_detail: {
                id: "",
                name: "",
                responsible: "",
                asset_warehouse_id: "",
                deadline_confirm: "",
                create_by: "",
                description: "",
                index: "",
                asset_inventarios: [],
                asset_inventario_details: [],
            },
            comment: '',
            list_user_warehouse: [],
            comments: [],
            status: [],
            users: [],
            errors: {},
            edit: false,
            edit_inven: false,
            tot: false,
            xau: false,
            asset_inventarios: [],
            url_inventario: "api/asset/inventario",
            asset_warehouses: [],
            kim: [],

        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.getAsset_use();
        this.listv();
        this.fetchinven_kiemke();
        this.getUser();
        this.fetchWarehouse();
        this.getDetails();
        this.fetchinven();

    },
    methods: {
        getcomplete(inven) {

            var obj = this.asset_inventarios.find(x => x.id == inven);
            return obj ? obj.complete : '';
        },
        gettime(inven) {

            var obj = this.asset_inventarios.find(x => x.id == inven);
            return moment(obj ? obj.created_at : '').format('YYYY-MM-DD [] h:mm:ss');
        },
        fetchinven() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;

            var page_url = this.url_inventario;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_inventarios = res.data;
                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        getDetails(page) {
            if (this.inven != null) {
                this.loading = true;
                var page_url = this.url_asset_kiemke_detail + "/" + this.inven + "/edit";
                fetch(page_url, { headers: { Authorization: this.token } })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        if (res.data.success == '1') {
                            this.item_detail = res.data;
                            console.log(this.item_detail);
                        }
                        this.edit_inven = true;
                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        getWarehouse(id) {
            var obj = this.asset_warehouses.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        fetchWarehouse() {

            var page_url = this.page_url_slocs;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            }).then(res => res.json())
                .then(res => {
                    this.asset_warehouses = res.data;

                }).catch(err => {

                    console.log(err);
                });

        },
        getUserName(user_id) {
            var obj = this.users.find(x => x.id == user_id);
            return obj != null ? obj.name : '';

        },
        getUser() {

            var page_url = this.page_url_users
            //console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.users = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        listv() {
            // console.log(this.user_id);
            console.log(this.even);
            var page_url = this.url_asset_kiemke + '/' + this.user_id + '/' + this.inven + '/' + this.ware_house;
            console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.list = data.data;

                }).catch(err => {
                    console.log(err);
                });
        },
        fetchinven_kiemke() {
            var page_url = this.url_asset_kiemke_detail;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.kim = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        getAsset_use() {
            var page_url = this.url_asset_use
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_uses = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        Adddetail() {
            var page_url = this.url_asset_kiemke_detail;
            if (this.edit_inven == false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.item_detail),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {


                        if (data.data.success == 1) {
                            toastr.success(this.$t('form.save_success'), "");
                        }
                        if (data.data.success == 3) {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.objectable_id);
                        }
                    })
                    .catch(err => console.log(err));
            }
            else {
                //update
                console.log(page_url + "/" + this.item_detail.id);
                fetch(page_url + "/" + this.item_detail.id, {
                    method: "PUT",
                    body: JSON.stringify(this.item_detail),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log('kim');

                        if (!data.data.errors) {


                            toastr.success(this.$t('form.update_success'), "");
                            // window.location.href = "/asset/change";
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Phát hiện sai số');
                            this.errors = data.data.errors;
                            toastr.warning(this.errors.quantityuser[0]);
                            toastr.warning(this.errors.quantitystocker[0]);
                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        speed_detail() {
            for (let index = 0; index < this.list.length; index++) {
                this.detail.asset_warehouse_id = this.list[index].asset_warehouse_id;
                this.detail.asset_inventario_id = this.list[index].foreign_key;
                this.detail.asset_status_id = 1;
                for (let cmt = 0; cmt < this.comments.length; cmt++) {
                    this.detail.comment = this.comments[cmt];
                }
                this.detail.objectable_id = this.list[index].objectable_id;
                this.detail.objectable_type = this.list[index].objectable_type;
                if (this.list[index].objectable_type == 'App\\Models\\asset\\AssetTool') {

                    this.detail.stocker_confirm_quantity = this.list[index].stocker_confirm_quantity;
                    this.detail.user_confirm_quantity = this.list[index].user_confirm_quantity;
                }
                if (this.list[index].objectable_type == 'App\\Models\\asset\\Asset') {

                    this.detail.stocker_confirm_status = this.list[index].stocker_confirm_status;
                    this.detail.user_confirm_status = this.list[index].user_confirm_status;
                }
                this.detail.user_id = this.list[index].user_id;
                let isexist = false;
                this.item_detail.asset_inventario_details.forEach(element => {
                    if (element.objectable_id == this.list[index].objectable_id && element.user_id == this.list[index].user_id) {
                        if (element.objectable_type == 'App\\Models\\asset\\AssetTool') {
                            element.user_confirm_quantity = this.detail.user_confirm_quantity;
                            element.stocker_confirm_quantity = this.detail.stocker_confirm_quantity;
                        }
                        if (element.objectable_type == 'App\\Models\\asset\\Asset') {
                            element.stocker_confirm_status = this.detail.stocker_confirm_status;
                            element.user_confirm_status = this.detail.user_confirm_status;
                        }

                        isexist = true;
                        // console.log(element.user_confirm_quantity);
                    }
                });
                if (!isexist) {
                    this.item_detail.asset_inventario_details.push({ ...this.detail });

                }
            }

        },
        Addcomment() {
            this.comments.push(this.comment);
            this.comment = '';

        },
        // successinventario() {
        //     window.location.href = '/asset/inventario?type=success';
        // },
        exit() {
            window.location.href = '/asset/inventario';
        },
        lose() {
            window.location.href = '/asset/inventario?type=product&id=' + this.inven + '&notification_id=' + this.ware_house + '&time=' + this.time;
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
        hasError(fieldName) {
            return fieldName in this.errors;
        },
    },
    computed: {
        list_asset() {
            let asset = [];
            for (let index = 0; index < this.list.length; index++) {
                if (this.list[index].objectable_type == 'App\\Models\\asset\\Asset') {
                    asset.push(this.list[index]);

                }
            }
            return asset;
        },
        list_tool() {
            let tool = [];
            for (let index = 0; index < this.list.length; index++) {
                if (this.list[index].objectable_type == 'App\\Models\\asset\\AssetTool') {
                    tool.push(this.list[index]);

                }
            }
            return tool;
        },
        list_user() {
            let user = [];
            for (let index = 0; index < this.users.length; index++) {
                if (this.user_id == this.users[index].id) {
                    user.push(this.users[index]);
                }

            }
            return user;
        },
    },
}

</script>
<style  lang="scss" scoped>
#th {
    animation: mymove 4s infinite;
}

.title_header {
    text-align: center;
    color: white;
    background: rgb(60, 141, 188);
    clip-path: polygon(100% 0, 99% 46%, 100% 100%, 0% 100%, 1% 50%, 0% 0%);
}

.title_headers {
    text-align: center;
    font-weight: 700;
    color: whitesmoke;
    background: rgb(60, 141, 188);
    clip-path: polygon(100% 0, 99% 46%, 100% 100%, 0% 100%, 1% 50%, 0% 0%);
}

@keyframes mymove {
    50% {
        transform: rotate(180deg);
    }
}
</style>