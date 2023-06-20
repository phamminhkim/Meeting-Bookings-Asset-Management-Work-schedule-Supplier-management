<template>
    <div>
        <div class="content-header">
            <!-- <div class="container-fluid">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark"> Sửa chữa tài sản </h5>

                </div>
            </div> -->
            <div class="card">
                <form @submit.prevent="AddTransactionRepair">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="float-left">
                                    <h5 class="m-0 text-dark text-uppercase font-weight-bold header-repair"> Sửa chữa tài
                                        sản </h5>
                                </div>
                                <div class="float-right">
                                    <button @click.stop="exitPage()" class="btn btn-secondary btn-sm"><i
                                            class="fas fa-arrow-alt-circle-left mr-1"></i> Quay lại</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-md-3 ml-1 mt-1">Tài sản</label>
                                    <div class="col-md-6">
                                        <input class="form-control" v-model="asset.name" readonly>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span class="mt-1" v-if="asset.asset.user_id">
                                                    <span class="font-weight-bold"><i
                                                            class="fas fa-user mr-2 fa-sm text-secondary"></i>User:
                                                        <u>
                                                            {{ asset.asset.user.name }} - {{ asset.asset.user.username }}
                                                        </u>
                                                    </span>
                                                    <span class="badge badge-warning">Đang sử dụng</span>
                                                </span>
                                                <span class="mt-1" v-if="asset.asset.department_id">
                                                    <span class="font-weight-bold">
                                                        <i class="fas fa-laptop-house fa-sm mr-2 text-secondary"></i>
                                                        Phòng ban:
                                                        <u>
                                                            {{ asset.asset.department.name }}
                                                        </u>
                                                    </span>
                                                    <span class="badge badge-warning">Đang sử dụng</span>
                                                </span>
                                                <span class="mt-1 font-weight-bold">
                                                    <span v-if="asset.asset.asset_status_id == 4" class="badge text-repair">
                                                        Đang sửa chữa </span>
                                                    <span v-if="asset.asset.asset_status_id == 3"
                                                        class="badge badge-danger"> Thanh lý </span>
                                                    <span v-if="asset.asset.asset_status_id == 1"
                                                        class="badge badge-success"> Tốt </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button @click.prevent="showListAsset()" class="btn btn-sm btn-info float-left"><i
                                                class="fas fa-cart-plus mr-1" style="color: white"></i> Chọn tài
                                            sản</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-md-3 ml-1 mt-1">Cập nhật trạng thái tài sản <small
                                            class="text-danger">(*)</small></label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="asset.status" required>
                                            <option value="4"> Đang sửa chữa</option>
                                            <option value="1"> Tốt</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-md-3 ml-1 mt-1">Ngày cập nhật <small
                                            class="text-danger">(*)</small></label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" v-model="asset.date_transaction" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-md-3 ml-1 mt-1">Ghi chú <small
                                            class="text-danger">(*)</small></label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" v-model="asset.description" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bảng cập nhật tài sản: </label>
                            <div >
                                <b-table responsive hover striped :sticky-header="true" :items="asset.asset_fixed_histories" :fields="fields">
                                    <template #cell(id)="data">
                                        <span>{{ data.index + 1 }}</span>
                                    </template>
                                    <template #cell(action)="data">
                                        <button v-if="data.item.action == false"
                                            @click.prevent="deleteTransactionRepair(data.index)"
                                            class="btn btn-sm btn-outlite"> <i class="fas fa-trash-alt text-danger"></i>
                                        </button>

                                    </template>
                                </b-table>
                            </div>
                        </div>
                        <div class="form-group">
                            <button @click.prevent="showFormRefercens()" class="btn btn-sm btn-success"><i
                                    class="fas fa-plus mr-1"></i>Thêm mới</button>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-sm font-weight-bold btn-repair"> <i class="fas fa-wrench mr-1"></i> Sửa chữa
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <!-- Popup Asset -->
        <AssetPR ref="assetPR" @selected="getAsset"></AssetPR>

        <AssetFormReferences ref="formReferences" v-on:submitAssetTransactionRepair="getAssetFormReferences">
        </AssetFormReferences>

    </div>
</template>

<script>
import AssetPR from '../product/AssetPR.vue';
import AssetFormReferences from '../product/AssetFormReferences.vue';

export default {
    components: {
        AssetPR,
        AssetFormReferences
    },
    data() {
        return {
            token: '',
            errors:{},
            
            asset: {
                asset: {},
                status: '',
                date_transaction: '',
                description: '',
                detail_name: '',
                detail_value: '',
                name: '',
                asset_fixed_histories: [],
                asset_details: [],
                fixed_history: [],
            },
            fields: [
                {
                    key: 'id',
                    label: '#',
                },
                {
                    key: 'name',
                    label: 'Thuộc tính',
                    class: 'text-nowrap text-info'
                },
                {
                    key: 'objectable.name',
                    label: 'Tên',
                    class: 'text-nowrap text-success'
                },
                {
                    key: 'quantity',
                    label: 'Số lượng',
                    class: 'text-nowrap text-success'

                },
                {
                    key: 'old_component.name',
                    label: 'Tham chiếu cũ',
                    class: 'text-nowrap text-danger'

                },
                {
                    key: 'old_content',
                    label: 'Nội dung cũ',
                    class: 'text-nowrap text-danger'

                },
                {
                    key: 'new_component.name',
                    label: 'Tham chiếu mới',
                    class: 'text-nowrap text-success'

                },
                {
                    key: 'new_content',
                    label: 'Nội dung mới',
                    class: 'text-nowrap text-success'
                },
                {
                    key: 'description',
                    label: 'Ghi chú',
                    class: 'text-nowrap'
                },
                {
                    key: 'action',
                    label: 'Hành động',
                    class: 'text-nowrap'
                }
            ],
            page_url_create_transaction_repair: '/api/asset/transaction_repair',
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
    },
    methods: {
        getAssetFormReferences(data) {
            this.asset.asset_fixed_histories = [
                ...this.asset.asset_fixed_histories,
                ...this.$refs.formReferences.asset_transaction_repair.history.map(item => {
                    return {
                        ...item,
                        new_content: this.$refs.formReferences.asset_transaction_repair.detail_value
                    };
                })
            ];
            this.asset.detail_name = this.$refs.formReferences.asset_transaction_repair.detail_name;
        },
        showListAsset() {
            this.$refs.assetPR.show();
        },
        getAsset(asset) {
            this.asset = asset;
        },
        AddTransactionRepair() {
            var page_url = this.page_url_create_transaction_repair;
            fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.asset),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => {

                    return res.json();
                })
                .then(data => {
                    if (!data.data.errors) {
                        // this.transaction.asset_transactions.push(data.data);
                        toastr.success("Tạo phiếu sửa chữa thành công");
                        window.location.href = "/asset/change";
                    } else {
                        toastr.error("Tạo phiếu sửa chữa thất bại");
                        this.errors = data.data.errors;
                        toastr.warning(this.errors.quantity[0]);
                    }

                })
                .catch(err => console.log(err));

        },
        deleteTransactionRepair(index) {
            this.asset.asset_fixed_histories.splice(index, 1);
        },
        showFormRefercens() {
            this.$refs.formReferences.show(this.asset);
        },
        exitPage() {
            window.location.href = '/asset/change';
        },

    }
}
</script>

<style lang="scss" scoped>
.header-repair {
    background: linear-gradient(rgb(253, 126, 20), rgb(205, 6, 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.btn-repair {
    background: linear-gradient(#ff7400, rgb(255 9 9 / 90%));
    color: white;

    &:hover {
        background: linear-gradient(rgb(205, 6, 0), rgb(253, 126, 20));
        opacity: 0.8;
    }
}

.text-repair {
    background: linear-gradient(#ff7400, rgb(255 9 9 / 90%));
    color: white;
}
</style>