<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-user"></i> {{ $t(title) }} </h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                <!-- <div class="input-group mb-3" style="width:70%;float:right;">
                                    <input type="text" class="form-control" placeholder="Nhập @ để tag"
                                        aria-label="Nhập @ để tag" aria-describedby="basic-addon2"
                                        style="border-right:none;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="background:white;color:gainsboro"
                                            id="basic-addon2"><i class="fas fa-user"></i></span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
            <b-tab title="XÁC NHẬN" title-link-class="animation1" active id="tatca">
                <template #title>
                    <div class="tess">
                        <strong>XÁC NHẬN</strong>
                    </div>
                </template>
                <div class="card">
                    <div class="card-body">
                        <div class="header">
                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" small responsive hover headVariant
                                :items="list" :fields="fields" :current-page="current_page" :per-page="per_page"
                                :tbody-tr-class="hide" id="table">
                                <template #head(#)="data">
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(view)="data">
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(#)="data" @click="show_detail(data.item.id)">
                                    <span>{{ data.index + 1 }}</span>
                                </template>
                                <template #head(updated_at)="data">
                                    <div class="text-center">
                                        <span class="text-success">{{ data.label }}</span>
                                    </div>
                                </template>
                                <template #cell(updated_at)="data">
                                    <div class="text-center" v-if="data.item.confirm == 1">
                                        <span>{{ data.item.updated_at | formatDate }}</span>
                                    </div>
                                </template>

                                <template #head(trans_type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(trans_type)=data>
                                    <span @click="Confirm(data.item.id)" v-if="data.item.trans_type == 1"
                                        class="text-success font-weight-bold">
                                        <i id="show_transaction" class="fas fa-hands"
                                            style="color:#1fc700;border-radius:30px;background:repeating-linear-gradient(110deg, gray, transparent 100px);"></i>
                                        Bàn
                                        giao</span>

                                    <span @click="Confirm(data.item.id)" v-if="data.item.trans_type == 2"
                                        class="text-warning font-weight-bold">
                                        <i id="show_recovery" class="fas fa-hand-rock"
                                            style="color:darkred;border-radius:30px;"></i>
                                        Thu hồi </span>
                                        <span  @click="Confirm(data.item.id)" v-if="data.item.trans_type == 4">
                                            <i class="fas fa-wrench icon-repair"
                                                style="font-size:20px;"></i>
                                            <span class="text-repair"><b>Sửa chữa</b></span>
                                          
                                        </span>

                                </template>
                                <template #head(user_id)=data>
                                    <div class="text-center">
                                        <span class="text-success">{{ data.label }}</span>
                                    </div>
                                </template>
                                <template #cell(user_id)="data">
                                    <div class="text-center" @click="show_detail(data.item.id)">
                                        <span> {{ $t(getuserName(data.item.user_id)) }} </span>
                                    </div>
                                </template>
                                <template #head(confirm)=data>
                                    <div class="text-center">
                                        <span class="text-success ">{{ data.label }}</span>
                                    </div>
                                </template>
                                <template #head(danhsachAsset)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(danhsachAsset)="data">
                                    <div class="text-center" @click="show_detail(data.item.id)">
                                        <span> {{ data.item.danhsachAsset }} </span>
                                        <span v-if="!data.item.danhsachAsset"> 0 </span>
                                    </div>
                                </template>
                                <template #head(danhsachCCDC)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(danhsachCCDC)="data">
                                    <div class="text-center" @click="show_detail(data.item.id)">
                                        <span> {{ data.item.danhsachCCDC }} </span>
                                        <span v-if="!data.item.danhsachCCDC"> 0 </span>
                                    </div>
                                </template>
                                <template #head(date_transaction)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(date_transaction)="data">
                                    <div class="text-center" @click="show_detail(data.item.id)">
                                        <span> {{ data.item.date_transaction | formatDate }} </span>
                                    </div>
                                </template>
                                <template #head(note)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>

                                <template #cell(confirm)=data>
                                    <div class="text-center" @click="show_detail(data.item.id)">
                                        <span v-if="data.item.confirm == 0" class="badge bg-danger font-weight-bold">Chưa
                                            xác nhận</span>
                                        <span v-if="data.item.confirm == 1" class="badge bg-success font-weight-bold">Đã xác
                                            nhận</span>
                                        <span v-if="data.item.confirm == 3" class="badge bg-warning font-weight-bold">Không
                                            cần xác nhận</span>
                                    </div>
                                </template>
                                <template #head(action)=data>
                                    <div class="text-center">
                                        <span class="text-success ">{{ data.label }}</span>
                                    </div>
                                </template>
                                <template #cell(action)="data">
                                    <div class="text-center">
                                        <button @click="Confirm(data.item.id)" id="shadow_btn_my"
                                            class="btn btn-sm btn-success">Xác nhận <i
                                                class="fas fa-caret-right ml-2 mt-1 text-xs"></i></button> <br>

                                    </div>
                                </template>
                                <template #cell(view)="data">
                                    <div v-if="data.item.trans_type == 1 || data.item.trans_type == 4" id="file-p" class="text-center"
                                        @click="printRecord(data.item.id)">
                                        <i v-b-tooltip.hover title="Xem biên bản" class="fad fa-file-pdf"
                                            style="--fa-primary-color: #db0000; --fa-primary-opacity: 1; --fa-secondary-color: #080808; --fa-secondary-opacity: 0.1;font-size:40px;"></i>
                                    </div>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for="">Per
                                            page:</label>
                                        <div class="col-md-3">
                                            <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                            </b-form-select>
                                        </div>
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                                        <div class="col-md-3">
                                            <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page"
                                                align="fill" pills class="my-0"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </b-tab>
            <b-tab title="TÀI SẢN ĐÃ BÀN GIAO" title-link-class="animation1">
                <template #title>
                    <div class="tess">
                        <strong>TÀI SẢN ĐÃ BÀN GIAO</strong>
                    </div>
                </template>
                <div class="card">
                    <div class="card-header" style="background:gold;box-shadow:1px 1px 20px lightgrey;">
                        <label>Danh sách Tài sản - Công cụ dụng cụ</label>
                    </div>
                    <div class="card-body" style="background:ghostwhite">
                        <div class="backk mb-2" style="background:white;border-radius:10px;border: 1px solid lightgrey;">
                            <b-table small responsive :items="asset_held" :fields="tai_san_dang_nam"
                                :current-page="current_page" :per-page="per_page">
                                <template #head(#)="data">
                                    <span class="text-success"> {{ data.label }} </span>
                                </template>
                                <template #head(objectable.name)="data">
                                    <span class="text-success"> {{ data.label }} </span>
                                </template>
                                <template #head(objectable.attachment_image)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(quantity)="data">
                                    <div class="text-center">
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(quantity)="data">
                                    <div class="text-center">
                                        <span> {{ data.item.quantity }} </span>
                                    </div>
                                </template>
                                <template #cell(#)="data">
                                    <span> {{ data.index + 1 }} </span>
                                </template>
                                <template #cell(objectable.attachment_image)="data">
                                    <div class="text-center" v-for="img in data.item.objectable.attachment_image">
                                        <img style="max-width:70px;height:40px;width:100%;display:inline-block"
                                            v-if="img.url" :src="img.url" class="img-responsive img-fluid" rounded="lg" />
                                    </div>
                                </template>
                            </b-table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-md-1" style="text-align: left" for="">Per
                                        page:</label>
                                    <div class="col-md-3">
                                        <b-form-select size="sm" v-model="per_page" :options="pageOptionss">
                                        </b-form-select>
                                    </div>
                                    <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                                    <div class="col-md-3">
                                        <b-pagination v-model="current_page" :total-rows="rowss" :per-page="per_page"
                                            align="fill" pills class="my-0"></b-pagination>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </b-tab>
        </b-tabs>
        <div class="modal " id="addstockAsset" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4>Thông tin chi tiết</h4>
                            </div>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <b-avatar variant="info" src="/goods/hung.jpg" class="mr-2" size="2rem">
                                        </b-avatar>

                                        <span class="text-dark"> </span><br>
                                        <span class="text-danger">Chưa xác nhận</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="float-sm-right">
                                            <button type="submit" class="btn btn-success btn-sm">Xác nhận đã nhận sản
                                                phẩm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Hủy bỏ
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal record -->
    </div>
</template>

<script>


export default {
    props: {

        title: ""
    },
    data() {
        return {
            counter: 0,
            asset_mies: [],
            list: [],
            asset_transactions: [],
            asset_uses: [],
            url_asset_my: "api/asset/my",
            url_asset_transactions: "api/asset/transaction",
            url_asset_use: "api/asset/assetuse",
            page_url_users: "api/user/all",
            page_url_tai_san_dang_nam: "api/asset/taisandangnam",
            asset_held: [],
            attachment_image: [],
            users: [],
            token: '',
            loading: false,
            edit: false,
            fields: [
                {
                    key: '#',
                    label: '#'
                },
                {
                    key: 'trans_type',
                    label: 'Loại giao dịch',
                    class: "text-nowrap",
                },
                {
                    key: 'user_id',
                    label: 'Tên',
                    class: "text-nowrap",
                },
                {
                    key: 'confirm',
                    label: 'Confirm',
                    class: "text-nowrap",
                },
                {
                    key: 'danhsachAsset',
                    label: 'Tài sản',
                    class: "text-nowrap",
                },
                {
                    key: 'danhsachCCDC',
                    label: 'Công cụ dụng cụ',
                    class: "text-nowrap",
                },
                {
                    key: 'date_transaction',
                    label: 'Ngày giao dịch',
                    class: "text-nowrap",
                },
                {
                    key: 'updated_at',
                    label: 'Ngày xác nhận',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                },
                {
                    key: 'view',
                    label: 'Biên bản',
                    class: "text-nowrap",
                }
            ],
            tai_san_dang_nam: [
                {
                    key: '#',
                    label: '#'
                },
                {
                    key: 'objectable.name',
                    label: 'Tên tài sản ',
                    class: "text-nowrap",
                },
                {
                    key: 'objectable.attachment_image',
                    label: 'Hình ảnh',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng',
                    class: "text-nowrap",
                }
            ],
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            pageOptionss: [10, 50, 100, 500, { value: this.rowss, text: "All" }],
            doubale: 0,
            sortBy: 'id',
            sortDesc: true,
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchAssetMy();
        this.fetchTransaction();
        this.getAsset_use();
        this.getUser();
        this.getAssetHeld();
    },
    methods: {
        getuserName(id) {
            var obj = this.users.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        getAssetHeld() {
            var page_url = this.page_url_tai_san_dang_nam
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_held = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        getUser() {
            var page_url = this.page_url_users
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
        fetchAssetMy() {
            //$("#tbbody_id").html('');
            this.loading = true;

            var page_url = this.url_asset_my;
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
        },
        fetchTransaction() {
            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;

            var page_url = this.url_asset_transactions;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_transactions = data.data;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        ShowActive(id) {
            this.counter++;
            for (let index = 0; index < this.asset_transactions.length; index++) {
                if (this.counter == 2 && this.asset_transactions[index].id == id) {
                    console.log(this.counter == 2 && this.asset_transactions[index].id == id)
                    $('#addstockAsset').modal("show");
                    this.counter = 0;
                    console.log(id);
                }

            }

        },
        Confirm(id) {
            window.location.href = '/asset/my?type=confirm&id=' + id;
        },
        hide(item, type) {
            if (!item || type !== 'row') return
            if (item.trans_type == 3) return 'd-none';

        },
        show_detail(id) {
            this.doubale++;
            if (this.doubale == 2) {
                window.location.href = '/asset/my?type=confirm&id=' + id;
                this.doubale = 0;
            }
        },
        printRecord(id) {
            window.location.href = '/asset/print?id=' + id;
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            let news = [];
            for (let index = 0; index < this.list.length; index++) {
                if (this.list[index].trans_type !== 3) {
                    news.push(this.list[index]);
                }
            }
            return news.length;
        },
        rowss() {
            return this.asset_held.length;
        },
    },
}
</script>
<style lang="scss" scoped>
#shadow_btn_my:hover {
    color: white;
    box-shadow: 1px 1px 10px green;
}

#file-p>i:hover {
    color: darkcyan;
    cursor: pointer;
}

#show_recovery:hover {
    font-size: 25px;
    box-shadow: 1px 1px 10px blue;
}

#show_transaction:hover {
    font-size: 25px;
    box-shadow: 1px 1px 10px green;
}

.animation div {

    animation: color 2s infinite;
}

.animation1 div::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: #3c8dbc;
    transition: width .1s;
}

.animation1 div:hover::after {
    width: 100%;
    transition: width .1s;
}

.tess {
    color: gray;
}

@keyframes color {
    50% {
        color: #3c8dbc;
    }
}
.text-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.icon-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
