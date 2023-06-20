<template>
    <div>

        <div class="modal" id="references" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true" style="overflow-y:scroll">
            <div class="modal-dialog modal-xl ">
                <div class="modal-content">
                    <form @submit.prevent="submitAssetTransactionRepair">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bold header-repair"> {{ asset_transaction_repair.name }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 label-sm">Chọn thuộc tính</label>
                                        <div class="col-md-6">
                                            <select class="form-control"
                                                @click="changeDetailValue(asset_transaction_repair.asset_details)"
                                                v-model="asset_transaction_repair.detail_name">
                                                <option v-for="(detail, index) in asset_transaction_repair.asset_details"
                                                    :key="index"> {{ detail.name }} </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 label-sm">Tên thuộc tính</label>
                                        <div class="col-md-6">
                                            <input v-model="asset_transaction_repair.detail_name" type="text"
                                                class="form-control" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 label-sm">Giá trị</label>
                                        <div class="col-md-6">
                                            <input v-model="asset_transaction_repair.detail_value" type="text"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="float-left">
                                        <label>Tham chiếu</label>
                                    </div>
                                    <div class="float-right">
                                        <button @click.prevent="showFormRefercens()"
                                            class="btn btn-sm btn-success float-left"><i
                                                class="fas fa-feather-alt mr-1"></i>Thêm tham
                                            chiếu</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <b-table hover responsive :items="asset_transaction_repair.history" :fields="fields">
                                        <template #cell(id)="data">
                                            <span> {{ data.index + 1 }} </span>
                                        </template>
                                        <template #cell(quantity_sloc)="data">
                                            <span class="badge badge-secondary p-2 px-5"> {{ data.item.quantity_sloc }}
                                            </span>
                                        </template>
                                        <template #cell(recovery)="data">
                                            <input type="checkbox" v-model="data.item.action" :value="data.item.action" />
                                        </template>
                                        <template #cell(description)="data">
                                            <input v-model="data.item.description" type="text" class="form-control form-control-sm"
                                                placeholder="Nhập ghi chú...." />
                                        </template>
                                        <template #cell(quantity)="data">
                                            <div class="repair-quantity-container">
                                                <!-- <span class="badge badge-secondary p-2 px-5" v-if="data.item.quantity"> {{
                                                    data.item.quantity }}
                                                </span> -->
                                                <input class="form-control form-control-sm repair-quantity" type="text" required=""
                                                    placeholder="Nhập số lượng..." v-model="data.item.quantity" />
                                                <div class="underline"></div>
                                            </div>

                                        </template>
                                        <template #cell(action)="data">
                                            <button class="btn btn-sm btn-outline"
                                                @click.prevent="deleteItemAssetToolPR(data.index)"
                                                v-if="data.item.action == false">
                                                <span class="text-danger"> <i class="fas fa-trash-alt"></i> </span>

                                            </button>
                                        </template>
                                    </b-table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary btn-sm">Lưu lại</button>


                        </div>
                    </form>

                </div>
            </div>
        </div>


        <AssetToolPR ref="assetToolPR" @submitAssetToolPR="getItemAssetToolPR"></AssetToolPR>
    </div>
</template>
<script>
import AssetToolPR from './AssetToolPR.vue';

export default {
    components: {
        AssetToolPR
    },
    data() {
        return {
            asset_transaction_repair: {
                name: '',
                detail_name: '',
                detail_value: '',
                old_content: '',
                fixed_history: [],
                history: [],
                asset_details: [],
                recovery: [],
            },

            fields: [
                {
                    key: 'id',
                    label: '#',
                },
                {
                    key: 'objectable.name',
                    label: 'Tên',
                    class: 'text-nowarp',
                },
                {
                    key: 'quantity_sloc',
                    label: 'Số lượng còn lại',
                    class: 'text-nowarp',
                },
                {
                    key: 'quantity',
                    label: 'Số lượng sửa chữa',
                    class: 'text-nowarp',
                },
                {
                    key: 'recovery',
                    label: 'Thu hồi',
                    class: 'text-nowarp',
                },
                {
                    key: 'description',
                    label: 'Ghi chú',
                    class: 'text-nowarp',
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: 'text-nowarp',
                },
            ],

        }
    },
    methods: {
        changeDetailValue(detail) {
            for (let index = 0; index < detail.length; index++) {
                if (detail[index].name == this.asset_transaction_repair.detail_name) {
                    this.asset_transaction_repair.detail_value = detail[index].value;
                    // this.asset_transaction_repair.old_content = detail[index].value;
                }
            }
        },
        getItemAssetToolPR(item) {
            let isexist = false;
            for (let index = 0; index < this.asset_transaction_repair.history.length; index++) {
                if (this.asset_transaction_repair.history[index].asset_tool_id == item.asset_tool_id) {
                    isexist = true;
                }
            }
            if (!isexist) {
                this.asset_transaction_repair.history.push({ ...item, name: this.asset_transaction_repair.detail_name });
            }
        },
        submitAssetTransactionRepair() {
            this.$emit('submitAssetTransactionRepair', { ...this.asset_transaction_repair });
            $('#references').modal('hide');
        },
        deleteItemAssetToolPR(index) {
            this.asset_transaction_repair.history.splice(index, 1);
        },
        show(asset) {
            this.asset_transaction_repair.name = asset.name;
            this.asset_transaction_repair.fixed_history = asset.asset_fixed_histories.map(function (item) {
                return { ...item, quantity_new: '' };
            });
            this.asset_transaction_repair.asset_details = asset.asset_details;
            $('#references').modal('show');
        },
        showFormRefercens() {
            this.$refs.assetToolPR.show(this.asset_transaction_repair.fixed_history);
        },
    },

}
</script>

<style lang="scss" scoped>
.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }

    50% {
        transform: scale(1.25);
    }

    100% {
        transform: scale(1);
    }
}

.header-repair {
    background: linear-gradient(rgb(253, 126, 20), rgb(205, 6, 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.repair-quantity-container {
    position: relative;
  }
.repair-quantity {
    border: none;
    border-bottom: 1px solid #ccc;
    background-color: transparent;
    outline: none;
}

.repair-quantity-container  .underline {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 1px;
    width: 100%;
    background: linear-gradient(rgb(253, 126, 20), rgb(205, 6, 0));
    transform: scaleX(0);
    transition: all 0.3s ease;
  }
  .repair-quantity-container input[type="text"]:focus ~ .underline,
  .repair-quantity-container input[type="text"]:valid ~ .underline {
    transform: scaleX(1);
  }
</style>