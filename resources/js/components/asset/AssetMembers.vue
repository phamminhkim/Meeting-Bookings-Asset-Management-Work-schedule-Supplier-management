<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-users"></i>Thành viên</h5>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-3" style="width:70%;float:right;">
                                    <input type="text" class="form-control" placeholder="Nhập @ để tag"
                                        aria-label="Nhập @ để tag" aria-describedby="basic-addon2"
                                        style="border-right:none;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="background:white;color:gainsboro"
                                            id="basic-addon2"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div> -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="NHÂN VIÊN SỬ DỤNG"  title-link-class="animation1" active id="tatcaa">
                        <template #title>
                            <div class="tess">
                            <strong>NHÂN VIÊN SỬ DỤNG</strong>
                         </div>
                        </template>
                        <div class="header">
                            <b-table :items="memberss" responsive :fields="fields" :current-page="currentPage" :filter="filter"
                                :filter-included-fields="filterOn" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc"
                                :sort-direction="sortDirection"  show-empty small @filtered="onFiltered">

                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(tongtien)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sum_ts)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <!-- <template #cell(sum_ts)="data">
                                    <div class="text-center">
                                        <span> {{data.item.sum_ts}} </span>
                                    </div>
                                </template> -->
                                <template #head(sum_dsccdc)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <!-- <template #cell(sum_dsccdc)="data">
                                    <div class="text-center">
                                        <span> {{data.item.sum_ts}} </span>
                                    </div>
                                </template> -->
                                <template #head(actions)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(user_id)="data">
                                    <span><strong>{{ getUserName(data.value) }}</strong></span>

                                </template>

                                <template #cell(actions)="row">
                                    <!-- <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">
                                Info modal
                                </b-button> -->
                                    <button id="shadow_btn_my" @click="row.toggleDetails"
                                        class="btn btn-sm btn-success"> {{ row.detailsShowing ? 'Ẩn' : 'Hiện' }} chi
                                        tiết</button>
                                    <!-- <b-button size="sm" @click="row.toggleDetails">
                                {{ row.detailsShowing ? 'Ẩn' : 'Hiện' }} chi tiết
                                </b-button> -->
                                </template>

                                <template #row-details="row">

                                 
                                        <ul style="border: 2px solid lightgrey;box-shadow: 1px 3px 2px lightgray">
                                            <div style="overflow-x:auto;">
                                                <table class="w-100 table text-nowrap b-form-spinbutton">

                                                    <thead>
                                                        <tr class="text-success text-left">
                                                            <th scope="col">
                                                                Tên tài sản / CCDC</th>
    
                                                            <th scope="col">Loại</th>
                                                            <th scope="col">Hình ảnh</th>
                                                            <th scope="col"> Số lượng bàn giao</th>
                                                            <th scope="col">
                                                                Đơn giá</th>
                                                            <th>
                                                                Số lượng tồn</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="(danhsach, index) in asset_uses" :key="index" class="text-left">
    
                                                        <tr v-if="danhsach.user_id == row.item.user_id">
                                                           
                                                            <th scope="row">
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                    <span>{{ gettoolsName(danhsach.objectable_id) }}</span>
    
                                                                </div>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                    <span>{{ getassetsName(danhsach.objectable_id) }}</span>
    
                                                                </div>
                                                            </th>
                                                            <td>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                    <i class="fas fa-layer-group"></i><span>Công cụ dụng
                                                                        cụ</span>
    
                                                                </div>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                    <i class="fas fa-box-open"></i><span>Tài sản</span>
    
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                    <div v-for="(tools, index) in asset_tools" :key="index">
                                                                        <div v-for="(imgg, index) in tools.attachment_image"
                                                                            :key="index">
                                                                            <div v-if="danhsach.objectable_id == tools.id"
                                                                                class="card m-auto mt-2" id="card_img"
                                                                                style="float:center;width:50px;height:50px;display:inline-block;">
    
    
                                                                                <img v-if="imgg.base64" :src="imgg.base64"
                                                                                    class="img-responsive img-fluid"
                                                                                    rounded="lg" />
                                                                                <img v-if="imgg.url" :src="imgg.url"
                                                                                    class="img-responsive img-fluid"
                                                                                    rounded="lg" />
    
                                                                            </div>
                                                                        </div>
                                                                    </div>
    
                                                                </div>
                                                                <div
                                                                    sv-if="danhsach.objectable_type==='App\\Models\\asset\\Asset'">
                                                                    <div v-for="(attack, index) in assets" :key="index">
                                                                        <div v-for="(imgg, index) in attack.attachment_image"
                                                                            :key="index">
                                                                            <div v-if="danhsach.objectable_id == attack.id"
                                                                                class="card m-auto mt-2" id="card_img"
                                                                                style="float:center;width:50px;height:50px;">
                                                                                <img v-if="imgg.base64" :src="imgg.base64"
                                                                                    class="img-responsive img-fluid"
                                                                                    rounded="lg" />
                                                                                <img v-if="imgg.url" :src="imgg.url"
                                                                                    class="img-responsive img-fluid"
                                                                                    rounded="lg" />
    
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td> {{ danhsach.quantity }}
                                                            </td>
                                                            <td>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                    <span>{{ gettoolsamount(danhsach.objectable_id) }}</span>
    
                                                                </div>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                    <span>{{
                                                                            getassetsamount(danhsach.objectable_id)
                                                                    }}</span>
    
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                    <span>{{ gettoolsquan(danhsach.objectable_id) }}</span>
    
                                                                </div>
                                                                <div
                                                                    v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                    <span>0</span>
    
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            


                                            <!-- <button style="float:right" @click.prevent="bangiao()">Bàn giao thêm</button> -->
                                        </ul>
                                      
                                 

                                </template>
                            </b-table>
                            <!-- <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
                                        <pre>{{ infoModal.content }}</pre>
                                        </b-modal> -->
                        </div>
                    </b-tab>
                    <b-tab title="PHÒNG BAN SỬ DỤNG"  title-link-class="animation1" id="bangiao">
                        <template #title>
                            <div class="tess">
                            <strong>PHÒNG BAN SỬ DỤNG</strong>
                         </div>
                        </template>
                        <div class="header">
                            <b-table :items="pbsudung" :fields="fieldss" :current-page="currentPage"
                                :filter="filter" :filter-included-fields="filterOn" :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc" :sort-direction="sortDirection" responsive show-empty small
                                @filtered="onFiltered">
                                <template #cell(department_id)="data">
                                    <span><strong>{{ getdepartmentsName(data.value) }}</strong></span>

                                </template>
                                <template #head(department_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(tongtien)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sum_ts)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sum_dsccdc)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(actions)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(actions)="row">
                                    <!-- <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">
                                Info modal
                                </b-button> -->
                                    <button id="shadow_btn_my" @click="row.toggleDetails"
                                        class="btn btn-sm btn-success"> {{ row.detailsShowing ? 'Ẩn' : 'Hiện' }} chi
                                        tiết</button>
                                </template>

                                <template #row-details="row">

                                    <b-card>
                                        <ul style="border: 2px solid lightgrey;box-shadow: 1px 3px 2px lightgray">
                                            <div style="overflow-x:auto;">
                                                <table class="w-100 table text-nowrap b-form-spinbutton">
                                                    <thead>
                                                        <tr class="text-success text-left">
                                                            <th scope="col">
                                                                Tên tài sản / CCDC</th>
    
                                                            <th scope="col">Loại</th>
                                                            <th scope="col">Hình ảnh</th>
                                                            <th scope="col"> Số lượng bàn giao</th>
                                                            <th scope="col">
                                                                Đơn giá</th>
                                                            <th>
                                                                Số lượng tồn</th>
                                                        </tr>
                                                    </thead>
                                                <tbody v-for="(danhsach, index) in asset_uses" :key="index" class="text-left">

                                                    <tr v-if="danhsach.department_id == row.item.department_id">
                                                        <td>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                <span>{{ gettoolsName(danhsach.objectable_id) }}</span>

                                                            </div>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                <span>{{ getassetsName(danhsach.objectable_id) }}</span>

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                <i class="fas fa-layer-group"></i><span>Công cụ dụng
                                                                    cụ</span>

                                                            </div>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                <i class="fas fa-box-open"></i><span>Tài sản</span>

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                <div v-for="(tools, index) in asset_tools" :key="index">
                                                                    <div v-for="(imgg, index) in tools.attachment_image"
                                                                        :key="index">
                                                                        <div v-if="danhsach.objectable_id == tools.id"
                                                                            class="card m-auto mt-2" id="card_img"
                                                                            style="float:center;width:50px;height:50px;">
                                                                            <img v-if="imgg.base64" :src="imgg.base64"
                                                                                class="img-responsive img-fluid"
                                                                                rounded="lg" />
                                                                            <img v-if="imgg.url" :src="imgg.url"
                                                                                class="img-responsive img-fluid"
                                                                                rounded="lg" />

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div
                                                                sv-if="danhsach.objectable_type==='App\\Models\\asset\\Asset'">
                                                                <div v-for="(attack, index) in assets" :key="index">
                                                                    <div v-for="(imgg, index) in attack.attachment_image"
                                                                        :key="index">
                                                                        <div v-if="danhsach.objectable_id == attack.id"
                                                                            class="card m-auto mt-2" id="card_img"
                                                                            style="float:center;width:50px;height:50px;">
                                                                            <img v-if="imgg.base64" :src="imgg.base64"
                                                                                class="img-responsive img-fluid"
                                                                                rounded="lg" />
                                                                            <img v-if="imgg.url" :src="imgg.url"
                                                                                class="img-responsive img-fluid"
                                                                                rounded="lg" />

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td> {{ danhsach.quantity }}
                                                        </td>
                                                        <td>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                <span>{{ gettoolsamount(danhsach.objectable_id) }}</span>

                                                            </div>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                <span>{{
                                                                        getassetsamount(danhsach.objectable_id)
                                                                }}</span>

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\AssetTool'">
                                                                <span>{{ gettoolsquan(danhsach.objectable_id) }}</span>

                                                            </div>
                                                            <div
                                                                v-if="danhsach.objectable_type === 'App\\Models\\asset\\Asset'">
                                                                <span>{{ getassetsquan(danhsach.objectable_id) }}</span>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        </ul>
                                    </b-card>
                                </template>
                            </b-table>
                        </div>

                    </b-tab>
                </b-tabs>
            </div>
        </div>
    </div>
</template>
<script>

export default {

    data() {
        return {

            memberss: [],
            show_search: false,
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            filter: "",


            per_page: 10,
            current_page: 1,
            errors: {},

            loading: false,
            current_page: 1,
            per_page: 10,
            token: '',
            filter: '',
            // fieldss:[
            // {
            //         key:'namee',
            //         label:'Tên tài sản ,CCDC',


            //     },
            //     {
            //         key:'amountt',
            //         label:'Giá bán',

            //     },
            //     {
            //         key:'quantityy',
            //         label:'Số lượng bàn giao',

            //     },
            //     {
            //         key:'quantitys',
            //         label:'Số lượng tồn',

            //     },
            // ],
            fieldss: [
                {
                    key: 'department_id',
                    label: 'Phòng ban',
                    class: 'text-nowrap'


                },
                {
                    key: 'tongtien',
                    label: 'Tổng tiền',
                    class: 'text-nowrap text-center'

                },
                {
                    key: 'sum_ts',
                    label: 'Số lượng tài sản ',
                    class: 'text-nowrap text-center'

                },
                {
                    key: 'sum_dsccdc',
                    label: 'Số lượng công cụ dụng cụ',
                    class: 'text-nowrap text-center'

                },
                ,
                {
                    key: 'actions',
                    label: 'Chi tiết',
                    class: 'text-nowrap text-center'

                },
            ],
            fields: [
                {
                    key: 'user_id',
                    label: 'Nhân viên',
                    class: 'text-nowrap'


                },
                {
                    key: 'tongtien',
                    label: 'Tổng tiền',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'sum_ts',
                    label: 'Số lượng tài sản ',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'sum_dsccdc',
                    label: 'Số lượng công cụ dụng cụ',
                    class: 'text-nowrap text-center'
                },
                ,
                {
                    key: 'actions',
                    label: 'Chi tiết',
                    class: 'text-nowrap text-center'
                },
            ],
            loading: false,
            page_url_list: "api/asset/members",

            totalRows: 1,
            currentPage: 1,
            pageOptions: [5, 10, 15, { value: 100, text: "Show a lot" }],
            sortBy: '',
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            filterOn: [],
            infoModal: {
                id: 'info-modal',
                title: '',
                content: ''
            },
            url_asset_tool: "api/asset/stock",

            page_url_users: "api/user/all",
            users: [],
            assets: [],
            asset_tools: [],
            url_asset_assets: "api/asset/assets",
            asset_uses: [],
            url_asset_use: "api/asset/assetuse",
            pbsudung: [],
            page_url_pbsudung: "api/asset/pbsudung",
            page_url_department : "/api/category/departments",
            departments:[],
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchMembers();
        this.getUser();
        this.fetchAssets();
        this.fetchTools();
        this.getAsset_use();
        this.fetchPhongbansudung();
        this.fetDepartment();
    },
    mounted() {
        // Set the initial number of items
        this.totalRows = this.memberss.length
    },
    methods: {
        getdepartmentsName(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            return obj != null ? obj.name : '';

        },
        fetDepartment() {
          // const this.token = "Bearer " + window.Laravel.access_this.token;
          var page_url = this.page_url_department;
          fetch(page_url, { headers: { Authorization: this.token } })
              .then(res => res.json())
              .then(res => {
                  //console.log("Xin chao");
                  this.departments = res.data;
              })
              .catch(err => console.log(err));
      },
       
        gettoolsName(objectable_id) {
            var obj = this.asset_tools.find(x => x.id == objectable_id);
            return obj != null ? obj.name : '';

        },
        getassetsName(objectable_id) {
            var obj = this.assets.find(x => x.id == objectable_id);
            return obj != null ? obj.name : '';

        },
        gettoolsamount(objectable_id) {
            var obj = this.asset_tools.find(x => x.id == objectable_id);
            return obj != null ? obj.amount : '';

        },
        getassetsamount(objectable_id) {
            var obj = this.assets.find(x => x.id == objectable_id);
            return obj != null ? obj.amount : '';

        },
        gettoolsquan(objectable_id) {
            var obj = this.asset_tools.find(x => x.id == objectable_id);
            return obj != null ? obj.quantity : '';

        },
        getassetsquan(objectable_id) {
            var obj = this.assets.find(x => x.id == objectable_id);
            return obj != null ? obj.quantity : '';

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
        fetchTools() {
            this.asset_tools = Array();

            //$("#tbbody_id").html('');
            var page_url = this.url_asset_tool;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_tools = data.data;
                }).catch(err => {

                    console.log(err);
                });
        },
        fetchAssets() {
            //$("#tbbody_id").html('');
            this.assets = Array();

            var page_url = this.url_asset_assets;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.assets = data.data;
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
        info(item, index, button) {
            this.infoModal.title = `Row index: ${index}`
            this.infoModal.content = JSON.stringify(item, null, 2)
            this.$root.$emit('bv::show::modal', this.infoModal.id, button)
        },
        resetInfoModal() {
            this.infoModal.title = ''
            this.infoModal.content = ''
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },
        fetchPhongbansudung() {

            this.list = Array();

            var page_url = this.page_url_pbsudung;

            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    if (res.success == '1') {
                        this.pbsudung = res.data;

                    }

                    this.loading = false;

                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchMembers() {

            this.list = Array();

            var page_url = this.page_url_list;

            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    if (res.statuscode == "403") {
                        window.location.href = "/errorpage?statuscode=" + res.statuscode;
                    }
                    if (res.success == '1') {
                        this.memberss = res.data;

                    }

                    this.loading = false;

                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },

    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.memberss.length;
        },
        placeholder() {
            return this.$t('form.search');
        }
    },
}
</script>

<style lang="scss" scoped>
.animation div{
   
    animation: color 2s infinite;
}
.animation1 div::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background:#3c8dbc;
    transition: width .1s;
}
.animation1 div:hover::after {
    width: 100%;
    transition: width .1s;
}
.tess{
    color: gray;
}
</style>
