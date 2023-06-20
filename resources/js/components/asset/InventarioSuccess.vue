<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-9 mb-4">
                        <div class="float-left">
                            <i class="fas fa-caret-left"></i>
                        </div>
                        <div class="text-left ml-4">
                            <h5 class="m-0 text-dark text-uppercase"><b>Kiểm kê</b></h5>
                        </div>
                    </div>
                    <div class="col-sm-3">


                        <div class="float-left">
                            <b-button @click.prevent="quailai()" class="btn btn-default btn-sm "> Quay
                                lại</b-button>
                        </div>
                        <div class="float-right">
                            <b-button v-if="list.complete == 0" @click.prevent="delay" class="btn btn-success btn-sm">
                                Hoàn
                                thành <i class="fas fa-flag ml-2" :disabled="disabled"></i></b-button>
                            <b-button v-if="list.complete == 1" class="btn btn-success btn-sm float-right"> Đã hoàn
                                thành<i class="fal fa-check-circle ml-2"></i></b-button>
                        </div>



                        <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->


                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <b-tabs content-class="mt-1" small>
                    <b-tab title="DỮ LIỆU" id="bangiao">
                        <div class="header">
                            <div class="bh">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card" style="border:1px solid lightgray;">
                                            <div class="card-body" id="user_confirm"
                                                style="opacity:0.5;">
                                                <p class="text-success">01. Người sử dụng xác nhận
                                                </p>
                                                <!-- <span>{{this.id}}</span> -->

                                                <span class="text-secondary"><i class="fas fa-clock"></i>Hạn chót:
                                                    {{ this.getdeadline(this.id) }}</span>
                                            </div>

                                        </div>


                                    </div>
                                    <!-- <div class="col-sm-3" style="border-right:4px solid white;background: whitesmoke;">
                                        <p class="text-secondary" style="padding: 5px;">02. Người giám sát xác nhận</p>
                                        <span class="text-secondary">Người giám sát xác nhận tình trạng tài sản của
                                            người dùng</span>
                                    </div> -->
                                    <div class="col-lg-4">
                                        <div class="card " style="border:1px solid lightgray">
                                            <div class="card-body " id="step_2">
                                                <p class="font-weight-bold" id="color">02. Đánh giá cuối cùng</p>
                                                <span class="text-secondary">Tài sản phải được cập nhập</span>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card " style="border:1px solid lightgray">
                                            <div class="card-body text-center " id="sum_user">
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <p><span class="badge bg-warning">{{
                                                                this.getQuantityAsset(this.id)
                                                        }}</span><br>
                                                            <span class="badge bg-secondary">TÀI SẢN</span>
                                                        </p>

                                                    </div>

                                                    <div class="col-sm-6">

                                                        <p><span class="badge bg-warning">{{
                                                                this.getQuantityTools(this.id)
                                                        }}</span><br>
                                                            <span class="badge bg-secondary">CÔNG CỤ DỤNG CỤ</span>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-sm-12">
                                        <div class="card ">
                                            <div class="card-header title_headers">
                                                <label  class="text-uppercase">Tài sản</label>
                                            </div>
                                            <div class="card-body" style="text-align:center;">
                                                <b-table small responsive hover headVariant :items="assetview"
                                                    :fields="fields_taisan" id="table">
                                                    <template #cell(#)=data>
                                                        <span class="badge bg-secondary">{{ data.index + 1 }}</span>
                                                    </template>
                                                    <template #head(#)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(objectable_id)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(user_id)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(asset_status_id)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(user_confirm_status)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(stocker_confirm_status)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(image)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #cell(objectable_id)=data>
                                                        <span>{{ getNameAssets(data.item.objectable_id) }}</span>
                                                    </template>
                                                    <template #cell(asset_status_id)=data>
                                                        <div v-if="data.item.asset_status_id == 1"
                                                            class="badge bg-success text-center">Tốt</div>

                                                    </template>
                                                    <template #cell(user_confirm_status)=data>
                                                        <div v-if="data.item.user_confirm_status == 1"
                                                            class="badge bg-success text-center">Tốt</div>
                                                        <div v-if="(data.item.user_confirm_status == 9)"
                                                            class="badge bg-danger text-center">Xấu</div>

                                                    </template>
                                                    <template #cell(stocker_confirm_status)=data>
                                                        <div v-if="data.item.stocker_confirm_status == 1"
                                                            class="badge bg-success text-center">Tốt</div>
                                                        <div v-if="(data.item.stocker_confirm_status == 9)"
                                                            class="badge bg-danger text-center">Xấu</div>

                                                    </template>

                                                    <template #cell(user_id)=data>

                                                        <div v-for="(user, index) in users" :key="index">

                                                            <div v-if="data.item.user_id == user.id">
                                                                <b-avatar :src="user.avatar" size="2rem">
                                                                </b-avatar> <span class="badge bg-outline-secondary">{{ getNameUser(data.item.user_id) }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                    </template>
                                                    
                                                    <template #cell(image)=data>
                                                        <div v-for="(attack, index) in assets" :key="index">
                                                            <div v-for="(imgg, index) in attack.attachment_image"
                                                                :key="index">
                                                                <div v-if="data.item.objectable_id == attack.id"
                                                                    class="card m-auto mt-2" id="card_img"
                                                                    style="float:center;width:50px;height:50px;">
                                                                    <img v-if="imgg.base64" :src="imgg.base64"
                                                                        class="img-responsive img-fluid" rounded="lg" />
                                                                    <img v-if="imgg.url" :src="imgg.url"
                                                                        class="img-responsive img-fluid" rounded="lg" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </b-table>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header title_header">
                                                <label class="text-uppercase">Công cụ dụng cụ</label>
                                            </div>
                                            <div class="card-body" style="text-align:center;">
                                                <b-table small responsive hover headVariant :items="stockview"
                                                    :fields="fields_ccdc" id="table">
                                                    <template #cell(#)=data>
                                                        <span class="badge bg-secondary">{{ data.index + 1 }}</span>
                                                    </template>
                                                    <template #head(#)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(objectable_id)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(user_id)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>

                                                    <template #head(user_confirm_quantity)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(stocker_confirm_quantity)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(image)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #head(user_quantity)=data>
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </template>
                                                    <template #cell(objectable_id)=data>
                                                        <span>{{ getNameStocks(data.item.objectable_id) }}</span>
                                                    </template>
                                                    <template #cell(user_quantity)=data>
                                                        <span
                                                            v-if="getcomplete(id) === 1">{{ getlisthistory(data.item.id) }}</span>

                                                        <div v-if="getcomplete(id) === 0"
                                                            v-for="(use, index) in asset_uses" :key="index">

                                                            <div
                                                                v-if="(data.item.objectable_id == use.objectable_id && data.item.user_id == use.user_id && use.objectable_type == 'App\\Models\\asset\\AssetTool')">

                                                                <span>{{ use.quantity }}</span>

                                                            </div>
                                                        </div>

                                                    </template>
                                                  
                                                    <template #cell(user_id)=data>

                                                    <div v-for="(user, index) in users" :key="index">

                                                        <div v-if="data.item.user_id == user.id">
                                                            <b-avatar :src="user.avatar" size="2rem">
                                                            </b-avatar> <span class="badge bg-outline-secondary">{{ getNameUser(data.item.user_id) }}</span>
                                                        </div>
                                                    </div>

                                                    </template>
                                                    <template #cell(image)=data>
                                                        <div v-for="(attack, index) in asset_tools" :key="index">
                                                            <div v-for="(imgg, index) in attack.attachment_image"
                                                                :key="index">
                                                                <div v-if="data.item.objectable_id == attack.id"
                                                                    class="card m-auto mt-2" id="card_img"
                                                                    style="float:center;width:50px;height:50px;">
                                                                    <img v-if="imgg.base64" :src="imgg.base64"
                                                                        class="img-responsive img-fluid" rounded="lg" />
                                                                    <img v-if="imgg.url" :src="imgg.url"
                                                                        class="img-responsive img-fluid" rounded="lg" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </b-table>
                                            </div>
                                        </div>
                                        <!-- <div class="card">
                                            <div class="card-header">
                                                <label>Tài sản đang sử dụng nhưng chưa bàn giao</label>
                                            </div>
                                            <div class="card-body">
                                                <b-table small hover headVariant :items="items" :fields="fields"
                                                    id="table">
                                                    <template v-slot:cell(#)=data>
                                                        {{ data.index + 1 }}
                                                    </template>
                                                    <template #head(#)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #head(item)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #cell(item)=data>

                                                        <span>{{ data.item.item}}</span><br>
                                                        <span class="text-secondary"
                                                            v-if="data.item.Tên == 'MT-01 - bàn phím'">001-600</span>

                                                    </template>

                                                    <template #cell(System)=data>
                                                        <span class="text-success"
                                                            v-if="data.item.System == 'Tốt'">{{data.item.System}}</span>
                                                        <span class="text-danger"
                                                            v-if="data.item.System == 'Mất'">{{data.item.System}}</span>
                                                    </template>
                                                    <template #head(Người_sử_dụng)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #cell(Người_sử_dụng)=data>
                                                        <b-avatar variant="info" src="/goods/hung.jpg" class="mr-2" size="2rem">
                                                        </b-avatar>
                                                       <span>{{data.item.Người_sử_dụng}}</span>
                                                    </template>
                                                    <template #head(Loại)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #cell(Loại)=data>
                                                       <span><i class="fas fa-cube"></i> {{data.item.Loại}}</span>
                                                    </template>
                                                    <template #head(Số_lượng)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #cell(Số_lượng)=data>
                                                        <span class="text-success">{{ data.item.Số_lượng}}</span>
                                                    </template>
                                                    <template #head(Trạng_thái)=data>
                                                        <span class="text-success">{{ data.label}}</span>
                                                    </template>
                                                    <template #cell(Trạng_thái)=data>
                                                        <span class="text-success">{{ data.item.Trạng_thái}}</span>
                                                    </template>
                                                </b-table>
                                            </div>
                                        </div> -->
                                    </div>

                                </div>
                            </div>

                        </div>


                    </b-tab>
                    <!-- <b-tab title="BÁO CÁO" id="sudung">
                        <div class="header" style="background-color:rgb(244, 246, 249);">

                        </div>

                    </b-tab> -->
                </b-tabs>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        id: String,
        notification_id: String,
        time: "",
        title: ""
    },
    data() {
        return {
            fields_taisan: [
                {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'user_id',
                    label: 'Người sử dụng',
                    class: 'text-nowrap'
                }, {
                    key: 'objectable_id',
                    label: 'Tên tài sản',
                    class: 'text-nowrap'
                },
                {
                    key: 'image',
                    label: 'Hình ảnh',
                    class: 'text-nowrap'
                }, {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    class: 'text-nowrap'
                }, {
                    key: 'user_confirm_status',
                    label: 'Người dùng đánh giá',
                    class: 'text-nowrap'
                }, {
                    key: 'stocker_confirm_status',
                    label: 'Thủ kho đánh giá',
                    class: 'text-nowrap'
                },
            ],

            fields_ccdc: [
            {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap'
                },{
                key: 'user_id',
                label: 'Người sử dụng',
                class: 'text-nowrap'
            },
            {
                key: 'objectable_id',
                label: 'Tên CCDC',
                class: 'text-nowrap'
            }, {
                key: 'image',
                label: 'Hình ảnh',
                class: 'text-nowrap'
            }, {
                key: 'user_quantity',
                label: 'Người dùng sở hữu',
                class: 'text-nowrap'
            }, {
                key: 'user_confirm_quantity',
                label: 'Người dùng đánh giá',
                class: 'text-nowrap'
            }, {
                key: 'stocker_confirm_quantity',
                label: 'Thủ kho đánh giá',
                class: 'text-nowrap'
            },
            ],
            url_inventario: "api/asset/inventario",
            url_asset_history: "api/asset/inventario_history",

            url_asset_assets: "api/asset/assets",
            url_asset_tool: "api/asset/stock",
            url_asset_inventario: "api/asset/detail",
            page_url_users: "api/user/all",
            users: [],
            list_details_inventario: [],
            asset_tools: [],
            assets: [],
            asset_uses: [],
            url_asset_use: "api/asset/assetuse",
            asset_inventarios: [],
            edit_inven: false,
            list: [],
            errors: {},
            disabled: false,
            list_history: [],
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchinven();
        this.getUser();
        this.fetchinventario();
        this.fetchAssets();
        this.fetchTools();
        this.getAsset_use();
        this.fetchAssetHis();
        this.fetchAssetHisIndex();
    },
    methods: {
        getlisthistory(id) {
            var obj = this.list_history.find(x => x.id == id);
            return obj ? obj.quantity_use : '';
        },
        getcomplete(id) {

            var obj = this.asset_inventarios.find(x => x.id == id);
            return obj ? obj.complete : '';
        },
        quailai() {
            window.location.href = "/asset/inventario";
        },
        fetchAssetHisIndex(page) {
            //$("#tbbody_id").html('');
            if (this.id != null) {
                this.loading = true;

                var page_url = this.url_asset_history;
                //  console.log(page_url);
                fetch(page_url, {
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                }).then(res => res.json())
                    .then(data => {

                        this.list_history = data.data;

                        this.loading = false;
                    }).catch(err => {
                        console.log(err);
                        this.loading = false;
                    });
            }

        },
        fetchAssetHis(page) {
            //$("#tbbody_id").html('');
            if (this.id != null) {
                this.loading = true;

                var page_url = this.url_asset_history + "/" + this.id + '/edit';
                //  console.log(page_url);
                fetch(page_url, {
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                }).then(res => res.json())
                    .then(data => {

                        this.list = data.data;

                        this.loading = false;
                    }).catch(err => {
                        console.log(err);
                        this.loading = false;
                    });
            }

        },
        delay() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.AddHistory();
        },
        AddHistory() {
            var page_url = this.url_asset_history;
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận hoàn thành") + "?", {
                title: "",
                size: "sm",
                buttonSize: "sm",
                okVariant: "success",
                okTitle: "OK",
                cancelTitle: "Cancel",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            })
                .then((value) => {
                    if (value) {
                        fetch(page_url + "/" + this.list.id, {
                            method: "PUT",
                            body: JSON.stringify(this.list),
                            headers: {
                                Authorization: this.token,
                                "content-type": "application/json"
                            }
                        })
                            .then(res => res.json())
                            .then(data => {


                                if (data.data.success == 1) {
                                    toastr.success("Thành công");
                                    window.location.href = "/asset/inventario";


                                } else {


                                    // this.showError('Thông báo','Phát hiện sai số');
                                    this.errors = data.data.errors;
                                    // toastr.warning(this.errors.quantity[0]);


                                }
                                this.loading = false;

                            })
                            .catch(err => console.log(err));
                    }
                })
                .catch((err) => {
                    console.log(err);
                });

        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
        getQuantityTools(id) {
            var amount = 0;
            this.list_details_inventario.forEach(detail => {


                if (detail.objectable_type == 'App\\Models\\asset\\AssetTool' && detail.asset_inventario_id == id) {
                    amount += detail.stocker_confirm_quantity;
                }



            });
            return amount;


        },
        getQuantityAsset(id) {
            // var amount = 0;
            var count = 0;
            this.list_details_inventario.forEach(detail => {


                if (detail.objectable_type == 'App\\Models\\asset\\Asset' && detail.asset_inventario_id == id) {
                    count++;
                    //   amount += detail.asset_status_id;
                }



            });
            return count;


        },
        getdeadline(id) {

            var obj = this.asset_inventarios.find(x => x.id == id);
            return obj ? obj.deadline_confirm : '';
        },
        getNameAssets(objectable_id) {

            var obj = this.assets.find(x => x.id == objectable_id);
            return obj ? obj.name : '';
        },
        getNameStocks(objectable_id) {
            var obj = this.asset_tools.find(x => x.id == objectable_id);
            return obj ? obj.name : '';
        },
        getNameUser(user_id) {
            var obj = this.users.find(x => x.id == user_id);
            return obj ? obj.name : '';
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
        getUser() {

            var page_url = this.page_url_users;
            //  console.log(page_url);
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
        fetchAssets() {
            //$("#tbbody_id").html('');
            const params = new URLSearchParams({
            });
            var page_url = this.url_asset_assets + '?' + params.toString();
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
        fetchTools() {
            const params = new URLSearchParams({
            });
            //$("#tbbody_id").html('');
            var page_url = this.url_asset_tool + '?' + params.toString();
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
        fetchinventario(page) {
            if (this.id !== null) {
                this.loading = true;
                var page_url = this.url_asset_inventario;
                fetch(page_url, {
                    headers: {
                        Authorization: this.token,

                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        this.list_details_inventario = res.data;
                        // if (res.success ==  1) {

                        // }
                        this.loading = false;
                    }).catch(err => {
                        console.log(err);
                        this.loading = false;

                    });
            }


        },
    },
    computed: {
        assetview() {
            var news = [];
            for (var index = 0; index < this.list_details_inventario.length; index++) {

                if (this.list_details_inventario[index].objectable_type == 'App\\Models\\asset\\Asset' && this.list_details_inventario[index].asset_inventario_id == this.id) {
                    news.push(this.list_details_inventario[index]);

                }
            }
            return news;
        },
        stockview() {

            var news = [];
            for (var index = 0; index < this.list_details_inventario.length; index++) {

                if (this.list_details_inventario[index].objectable_type == 'App\\Models\\asset\\AssetTool' && this.list_details_inventario[index].asset_inventario_id == this.id) {
                    news.push(this.list_details_inventario[index]);

                }
            }
            return news;
        },

    },
}
</script>
<style  lang="scss" scoped>
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

#drop__BV_toggle_ {
    background: white;
    color: black;
}

#user_confirm::after {
    content: '';
    display: block;
    width: 0;
    height: 10px;
    background: gray;
    transition: width .5s;
}

#user_confirm::after {
    width: 100%;
    transition: width .5s;

}

#user_confirm {
    background: whitesmoke;

    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
    transition: width .5s;
}

#step_2 {
    background: linear-gradient(-45deg, #23a6d5, #23d5ab);
    color: #28a745;
    box-shadow: 0px 0px 4px 3px linear-gradient(-45deg, #23a6d5, #23d5ab);
}

#step_2::after {
    content: '';
    display: block;
    width: 0;
    height: 10px;
    background: #28a745;

    transition: width .5s;
}

#step_2::after {
    width: 100%;
    transition: width .5s;
}

#sum_user::after {
    content: '';
    display: block;
    width: 0;
    height: 3px;
    margin-top: 5px;
    background: lightgray;
    transition: width .5s;
}

#sum_user {
    background: whitesmoke;
}

#sum_user::after {
    width: 100%;
    transition: width .5s;
}

#color {
    animation: anima_color 2s infinite;
}

@keyframes anima_color {
    50% {
        color: white
    }
}
</style>