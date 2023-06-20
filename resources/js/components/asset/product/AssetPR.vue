<template>
    <div>
        <div class="modal" id="listAsset" tabindex="-1">
            <div class="modal-dialog full_modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header" id="title_header">
                            <h5 class="modal-title font-weight-bold">CHỌN TÀI SẢN</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <FilterAsset ref="filter" :fetchAsset="fetchAsset" :asset="asset"></FilterAsset>
                                    </div>
                                    <div class="col-md-10 p-4" style="background:rgb(0 0 0 / 4%);">

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div>
                                                    <h4 class="font-weight-bold">Tài sản</h4>
                                                    <span class="font-italic" style="color:darkgray">Thiên Long > Tất cả tài sản</span><br>
                                                    <span> {{ paginate_assets.total }} items </span>
                                                    <button v-if="asset_type_name != ''"
                                                        class="btn btn-sm search-item text-secondary font-weight-bold bg-white"
                                                        @click.prevent="resetFilter()">
                                                        {{ asset_type_name }}
                                                        <i class="fas fa-times ml-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input v-model="asset_query" class="form-control form-control query-search"
                                                    placeholder="Tìm kiếm tài sản..." />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="bg-white" style="border-radius:5px">
                                                <b-table responsive hover :items="assets" :fields="fields" 
                                                    :selected-row-class="'selected-row'" ref="selectableTable" selectable
                                                    :select-mode="'single'" @row-selected="onRowSelected">
                                                   
                                                    <template #cell(id)="data">
                                                        <span> {{ data.index + 1 }} </span>
                                                    </template>
                                                    <template #cell(asset_status_id)="data">
                                                        <p v-if="data.item.asset_status_id == 1"
                                                            class="bg bg-success font-weight-bold text-sm p2 text-center">
                                                            Tốt </p>
                                                        <p v-if="data.item.asset_status_id == 2"
                                                            class="bg bg-waring font-weight-bold text-sm p2 text-center">
                                                            Sửa chữa </p>
                                                        <p v-if="data.item.asset_status_id == 3"
                                                            class="bg bg-danger font-weight-bold text-sm p2 text-center">
                                                            Thanh lý </p>
                                                        <p v-if="data.item.asset_status_id == 4"
                                                            class="bg text-repair font-weight-bold text-sm p2 text-center">
                                                            Đang sửa chữa </p>
                                                    </template>
                                                    <template #cell(check)="{ rowSelected }">
                                                        <template v-if="rowSelected">
                                                            <div class="text-center">
                                                                <div style="text-align:center;font-size:25px;">
                                                                    <div class="text-success" aria-hidden="true"
                                                                        style="font-weight:700"><i
                                                                            class="fas fa-check-circle text-green  rounded-lg font-italic"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template v-else>
                                                            <div class="text-center">
                                                                <div class="bg text-center"
                                                                    style="font-weight:700;font-size:25px;">
                                                                    <div id="hover_select" class="text-gray"
                                                                        aria-hidden="true">
                                                                        <i
                                                                            class="fas fa-check-circle text-gray rounded-lg"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>

                                                    </template>
                                                    <template #cell(asset_warehouse.name)="data">
                                                        <span> <i class="fas fa-cube mr-2"></i>
                                                            {{ data.item.asset_warehouse.name }}
                                                        </span>                                                  
                                                    </template>
                                                    <template #cell(name)="data">
                                                        <span class="text-success"><i class="fas fa-box mr-2 text-dark"></i>
                                                            {{ data.item.name }}
                                                        </span>
                                                    </template>

                                                </b-table>
                                            </div>
                                            <div class="text-center overlay" v-if="loading">
                                                <div class="position-loading">
                                                    <i class="fad fa-spinner fa-pulse "
                                                        style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                                                    <br>
                                                    <span class="text-secondary font-weight-bold text-xs font-italic">Vui
                                                        lòng
                                                        chờ giây lát...</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="margin">
                                                        <div class="btn-group">
                                                            <label class="col-form-label-sm text-nowrap mr-1">Per page:
                                                            </label>
                                                            <b-form-select size="sm" v-model="perPage"
                                                                :options="perPageOptions" @change="fetchAsset">
                                                            </b-form-select>
                                                            <b-pagination @input="fetchAsset"
                                                                v-model="paginate_assets.current_page"
                                                                :total-rows="paginate_assets.total" :per-page="perPage"
                                                                :last-page="paginate_assets.last_page" pills
                                                                class="ml-1"></b-pagination>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import FilterAsset from '../search/FilterAsset.vue';

export default {
    components: {
        FilterAsset
    },
    data() {
        return {
            token: '',
            loading: false,
            asset: '',

            asset_query: '',
            typingTimer: null,
            asset_type_name: '',

            item_transaction: [],
            transaction_repair: {
                id: '',
                asset: {},
                asset_id: '',
                name: '',
                asset_fixed_histories: [],
                fixed_history: [],
                asset_details: [],
                asset_warehouse_id: '',
                asset_status_id: '',
                user_id: '',
                department_id: '',
                note: '',
                date: '',
                created_at: '',
            },

          
            assets: [],
            fields: [
                {
                    key: 'id',
                    label: '#',
                    class: "text-nowrap"
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap"
                },
                {
                    key: 'asset_warehouse.name',
                    label: 'Kho',
                    class: "text-nowrap"
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    class: "text-nowrap"
                },
                {
                    key: 'user.name',
                    label: 'Người sử dụng',
                    class: "text-nowrap"
                },
                {
                    key: 'department.name',
                    label: 'Phòng ban',
                    class: "text-nowrap"
                },
                {
                    key: 'check',
                    label: 'Chọn',
                    class: "text-nowrap"
                },

            ],


            paginate_assets: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            page_url_assetPage: "api/asset/assetPage",
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

    },
    methods: {
        fetchAsset() {
            this.loading = true;
            this.asset_type_name = this.$refs.filter.name;
            const queryParams = new URLSearchParams({
                search: this.asset_query,
                asset_type_id: this.$refs.filter.asset_type_id,
            });
            var page_url = this.page_url_assetPage + "/" + this.paginate_assets.current_page + "?per_page=" + this.perPage + '&' + queryParams.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.assets = res.data;
                    this.paginate_assets = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        onRowSelected(assets) {
            // chặn lại chỉ cho chọn 1 thôi
            if (assets.length > 1) {
                assets.splice(0, 1);
            } else {
                this.item_transaction = [...assets];
                this.submitAsset();
            }

        },
        submitAsset() {
            this.transaction_repair.asset_id = this.item_transaction[0].id;
            this.transaction_repair.asset = { ...this.item_transaction[0] };
            this.transaction_repair.name = this.item_transaction[0].name;
            this.transaction_repair.asset_warehouse_id = this.item_transaction[0].asset_warehouse_id;
            this.transaction_repair.asset_status_id = this.item_transaction[0].asset_status_id;
            this.transaction_repair.asset_fixed_histories = this.item_transaction[0].asset_fixed_history;
            this.transaction_repair.asset_details = this.item_transaction[0].asset_details;
            this.$emit("selected", this.transaction_repair);
            $('#listAsset').modal('hide')
        },
        resetFilter() {
            this.$refs.filter.resetFilter();
        },
        show() {
            this.fetchAsset();   this.asset = 'asset';
            this.item_transaction = [];
            $('#listAsset').modal('show')
        }
    },
    watch: {
        asset_query: function (newVal, oldVal) {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.fetchAsset()
            }, 500);

        }
    },
}
</script>

<style lang="scss" scoped>
.full_modal-dialog {
    margin: 0 !important;
    width: 100% !important;
    height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    padding: 0 !important;
}

.position-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.query-search {
    border-radius: 30px;
}

#title_header {
    background: radial-gradient(rgb(20, 149, 255), steelblue 100%, steelblue);
    box-shadow: 1px 1px 10px steelblue;
    color: white;
}

.text-repair {
    background: linear-gradient(#ff7400, rgb(255 9 9 / 90%));
    color: white;
}

.search-item {
    border-radius: 30px;
    &:hover {
        border: 1px solid lightgray;
        color: rgb(0, 140, 255);
    }
    &:hover>i {
        color: red;
    }
}
</style>