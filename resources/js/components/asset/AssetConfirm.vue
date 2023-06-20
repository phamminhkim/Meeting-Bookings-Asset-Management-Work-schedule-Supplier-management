<template>
    <div>
        <form @submit.prevent="UpdateConfirm">
            <div class="modal-header">
                <div class="modal-title">
                    <h4> {{ $t(title) }} </h4>

                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <b-avatar class="mr-2" :src="list.user.avatar"></b-avatar>
                            <div style="display: inline-flex;">
                                <span> {{ list.user.name }} <br>
                                    <span class="badge bg-danger font-weight-bold" v-if="list.confirm == 0"> Chưa xác nhận
                                    </span>
                                    <span class="badge bg-success font-weight-bold" v-if="list.confirm == 1"> Đã xác nhận
                                    </span>
                                    <span class="badge bg-warning font-weight-bold" v-if="list.confirm == 3"> Không cần xác
                                        nhận
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-right">
                                <button v-if="list.confirm == 0" type="submit" id="shadow_btn_confirm"
                                    class="btn btn-sm btn-success">Xác nhận <i
                                        class="fas fa-caret-right ml-2 mt-1 text-xs"></i></button>
                                <button @click.prevent="Show_list()" v-if="list.confirm == 1" type="button"
                                    class="btn btn-sm btn-default "><i class="fas fa-caret-left mr-2"></i> Quay lại</button>
                                <button @click.prevent="Show_list()" v-if="list.confirm == 3" type="button"
                                    class="btn btn-sm btn-default "><i class="fas fa-caret-left mr-2"></i> Quay lại</button>
                                <!-- <button id="confirm_1" disabled v-if="list.confirm == 1" type="submit" style="opacity:0.3;" class="btn btn-success ">Xác nhận</button> -->
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group" v-if="list.assetdable_id">
                                <label class="text-uppercase text-repair">Sửa chữa:</label>
                                <span class="font-weight-bold"> {{ list.assetdable.name }} </span>


                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <p v-if="list.trans_type == 1">
                                                <span class="text-secondary"> Người bàn giao: </span>
                                                <span class="font-weight-bold"> {{ $t(getuserName(list.create_by)) }}</span>
                                            </p>
                                            <p v-if="list.trans_type == 2">
                                                <span class="text-secondary"> Người thu hồi: </span>
                                                <span class="font-weight-bold"> {{ $t(getuserName(list.create_by)) }} </span>
                                            </p>
                                            <p v-if="list.trans_type == 4">
                                                <span class="text-secondary"> Người sửa chữa: </span>
                                                <span class="font-weight-bold"> {{ $t(getuserName(list.create_by)) }}
                                                </span>
                                            </p>
                                            <p>
                                                <span class="text-secondary"> Ghi chú: </span>
                                                <span class="font-weight-bold">{{ list.note }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>
                                                <span class="text-secondary"> Người nhận: </span>
                                                <span class="font-weight-bold"> {{ list.user.name }}  </span>
                                            </p>
                                            <p v-if="list.trans_type == 1">
                                                <span class="text-secondary"> Ngày bàn giao: </span>
                                                <span class="font-weight-bold"> {{ list.date_transaction | formatDate }}</span>
                                            </p>
                                            <p v-if="list.trans_type == 2">
                                                <span class="text-secondary"> Ngày thu hồi: </span>
                                                <span class="font-weight-bold"> {{ list.date_transaction | formatDate }}</span>
                                            </p>
                                            <p v-if="list.trans_type == 4">
                                                <span class="text-secondary"> Ngày sửa chữa: </span>
                                                <span class="font-weight-bold"> {{ list.date_transaction | formatDate }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <label>Danh sách tài sản</label>
                                <div>
                                    <div>
                                        <span class="text-secondary"> </span>
                                        <table class="w-100 b-form-spinbutton">
                                            <thead>
                                                <tr class="text-success">
                                                    <td style="border-left:none;border-right:none"
                                                        class=" font-weight-bold border-bottom border-top-0 w-25 text-left ">
                                                        Tên</td>
                                                    <td style="border-left:none;border-right:none"
                                                        class=" font-weight-bold  border-bottom border-top-0 w-25 ">
                                                        Số lượng</td>
                                                    <td style="border-left:none;border-right:none"
                                                        class=" font-weight-bold border-bottom border-top-0 w-25 ">
                                                        Đơn giá</td>

                                                </tr>
                                            </thead>
                                            <tbody v-for="(danhsach, index) in list.asset_transaction_items" :key="index">

                                                <tr v-if="danhsach.objectable_type == 'App\\Models\\asset\\Asset'">
                                                    <td class="borde border-left-0 border-right-0 border-bottom text-left">
                                                        {{ danhsach.objectable.name }}
                                                    </td>
                                                    <td class="text-secondary border-left-0 border-right-0 border ">
                                                        <div class="bg w-75 text-center mx-auto rounded-pill mt-1 mb-1">
                                                            {{ danhsach.quantity }}</div>
                                                    </td>
                                                    <td class="border border-left-0 border-right-0 text-secondary">
                                                        <div class="bg w-75 text-center mx-auto rounded-pill">
                                                            {{ danhsach.objectable.amount | formatNumber_amount }} VND</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <label>Danh sách công cụ dụng cụ</label>
                            <div class="table-reponsive">
                                <table class="w-100 b-form-spinbutton">
                                    <thead>
                                        <tr class="text-success">
                                            <td style="border-left:none;border-right:none"
                                                class=" font-weight-bold  border-bottom border-top-0 w-25 text-left">
                                                Tên</td>
                                            <td style="border-left:none;border-right:none"
                                                class=" font-weight-bold  border-bottom border-top-0 w-25">Số lượng
                                            </td>
                                            <td style="border-left:none;border-right:none"
                                                class=" font-weight-bold  border-bottom border-top-0 w-25">Đơn giá
                                            </td>
                                            <td v-if="list.trans_type == 4" style="border-left:none;border-right:none"
                                                class=" font-weight-bold  border-bottom border-top-0 ">Nội dung</td>
                                            <td v-if="list.trans_type == 4" style="border-left:none;border-right:none"
                                                class=" font-weight-bold  border-bottom border-top-0 ">Ghi chú</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(danhsach, index) in list.asset_transaction_items" :key="index">
                                        <tr v-if="danhsach.objectable_type == 'App\\Models\\asset\\AssetTool'">

                                            <td class="borde border-left-0 border-right-0 border-bottom text-left"> {{
                                                danhsach.objectable.name }} </td>
                                            <td class="text-secondary border-left-0 border-right-0 border ">
                                                <div class="mt-1 mb-1">
                                                    {{ danhsach.quantity }}</div>
                                            </td>
                                            <td class="text-secondary border-left-0 border-right-0 border">
                                                <span>
                                                    {{ danhsach.objectable.amount | formatNumber_amount }} VND</span>
                                            </td>
                                            <td v-if="list.trans_type == 4"
                                                class="text-secondary border-left-0 border-right-0 border"> {{
                                                    danhsach.history.new_content }} </td>
                                            <td v-if="list.trans_type == 4"
                                                class="text-secondary border-left-0 border-right-0 border"> {{
                                                    danhsach.history.description }} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        id: String,
        title: ""
    },
    data() {
        return {
            counter: 0,
            asset_mies: [],
            list: [],
            mycount: [],
            asset_transactions: [],
            asset_uses: [],
            url_asset_my: "api/asset/my",
            url_asset_transactions: "api/asset/transaction",
            url_asset_mycount: "api/asset/mycount",
            url_asset_use: "api/asset/assetuse",
            page_url_users: "api/user/all",
            page_url_notification: "api/asset/notification",
            token: '',
            loading: false,
            edit: false,
            fields: [
                {
                    key: '#',
                    label: 'ID'
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
                    label: 'Tài sản bàn giao',
                    class: "text-nowrap",
                },
                {
                    key: 'danhsachCCDC',
                    label: 'Công cụ dụng cụ bàn giao',
                    class: "text-nowrap",
                },
                {
                    key: 'tongtien',
                    label: 'Tổng tiền',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                }
            ],
            users: [],
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchAssetMy();
        this.fetchAssetMyCount();
        this.fetchTransaction();
        this.getAsset_use();
        this.getUser();
    },
    methods: {
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
        getuserName(id) {
            var obj = this.users.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        fetchAssetMy(page) {
            //$("#tbbody_id").html('');
            if (this.id != null) {
                this.loading = true;

                var page_url = this.url_asset_my + "/" + this.id + '/edit';
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
        fetchAssetMyCount() {
            //$("#tbbody_id").html('');
            this.loading = true;

            var page_url = this.url_asset_mycount;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.mycount = data.tess;
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
        UpdateConfirm() {
            var page_url = this.url_asset_my;
            //update
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
                        toastr.success("Xác nhận thành công");
                        window.location.href = "/asset/my";

                    } else {
                        this.errors = data.data.message;
                        toastr.warning(this.errors);
                    }
                    this.loading = false;

                })
                .catch(err => {
                    this.loading = false;

                });

        },
        Show_list() {
            window.location.href = "/asset/my";
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
    },
}
</script>
<style lang="scss" scoped>
#confirm_1:hover {
    box-shadow: 1px 1px 5px gray;
}

#shadow_btn_confirm {
    color: white;

}

#shadow_btn_confirm:hover {
    color: white;
    box-shadow: 1px 1px 10px green;
    font-size: 15px;
}

.text-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.background-status-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    color: white;
}</style>