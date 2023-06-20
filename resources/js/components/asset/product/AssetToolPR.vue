<template>
    <div>
        <div class="modal" id="assetToolPR" tabindex="-1">
            <div class="modal-dialog  full_modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="submitAssetToolPR">
                        <div class="modal-header" id="title_header">
                            <h5 class="modal-title font-weight-bold text-white">CHỌN CÔNG CỤ DỤNG CỤ</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success btn-sm">Tiếp tục</button>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <FilterAssetTools ref="filter" :fetchAssetTools="fetchAssetTools"></FilterAssetTools>
                                </div>
                                <div class="col-md-10 p-4" style="background:rgb(0 0 0 / 4%);">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <h4 class="font-weight-bold">Tài sản</h4>
                                                    <span class="font-italic" style="color:darkgray">Thiên Long > Tất cả công cụ dụng cụ</span><br>
                                            <span> {{ paginate_stocks.total }} items </span>
                                            <button v-if="asset_type_name != ''"
                                                        class="btn btn-sm search-item text-secondary font-weight-bold bg-white"
                                                        @click.prevent="resetFilter()">
                                                        {{ asset_type_name }}
                                                        <i class="fas fa-times ml-2"></i>
                                                    </button>
                                        </div>
                                        <div class="col-md-6">
                                            <input v-model="asset_tool_query" class="form-control query-search"
                                                placeholder="Tìm kiếm công cụ dụng cụ..." />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="bg-white" style="border-radius:5px;">
                                            <b-table responsive hover :items="asset_tools" :fields="fields"
                                                ref="selectableTable" selectable @row-selected="onRowSelected">
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
                                                                <div id="hover_select" class="text-gray" aria-hidden="true">
                                                                    <i class="fas fa-check-circle text-gray rounded-lg"></i>
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
                                                        <label class="col-form-label-sm text-nowrap mr-1">Per
                                                            page: </label>
                                                        <b-form-select size="sm" v-model="perPage" :options="perPageOptions"
                                                            @change="fetchAssetTools">
                                                        </b-form-select>
                                                        <b-pagination @input="fetchAssetTools"
                                                            v-model="paginate_stocks.current_page"
                                                            :total-rows="paginate_stocks.total" :per-page="perPage"
                                                            :last-page="paginate_stocks.last_page" pills
                                                            class="ml-1"></b-pagination>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FilterAssetTools from '../search/FilterAssetTool.vue';
export default {
    components: {
        FilterAssetTools
    },

    data() {
        return {
            token: '',

            asset_tool_query: '',
            asset_type_name: '',

            loading: false,
            typingTimer: null,

            item_transaction: [],
            fixed_history: [],
            objectable: {
                asset_tool_id: '',
                objectable: {},
                objectable_id: '',
                old_component: {},
                old_content: '',
                quantity_new: '',
                quantity_sloc: '',
                quantity: '',
                note: '',
                new_component: {},
                action: false,
                recovery: false,
            },
            asset_tool_item: [],
            asset_tools: [],
            fields: [
                {
                    key: 'id',
                    label: '#',
                },
                {
                    key: 'name',
                    label: 'Tên',
                },
                {
                    key: 'asset_warehouse.name',
                    label: 'Kho',
                },
                {
                    key: 'quantity',
                    label: 'Số lượng còn lại',
                },
                {
                    key: 'check',
                    label: 'Chọn',
                },
            ],

            paginate_stocks: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            page_url_stockPage: "api/asset/stockPage",
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
       
    },
    methods: {
        fetchAssetTools() {
            this.loading = true;
            this.asset_type_name = this.$refs.filter.name;
            const queryParams = new URLSearchParams({
                search: this.asset_tool_query,
                asset_type_id: this.$refs.filter.asset_type_id,
            });
            var page_url = this.page_url_stockPage + "/" + this.paginate_stocks.current_page + "?per_page=" + this.perPage + '&' + queryParams.toString();
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_tools = data.data;
                    this.paginate_stocks = data.paginate;
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        onRowSelected(tool) {
            this.item_transaction = [...tool];
        },
        clearSelected() {
            this.$refs.selectableTable.clearSelected();
        },
        submitAssetToolPR() {
            // this.$emit('submitAssetToolPR', this.item_transaction);

            this.item_transaction.forEach(value => {
                this.objectable.asset_tool_id = value.id;
                // this.objectable.name = value.name;
                this.objectable.objectable_id = value.id;
                this.objectable.objectable = { ...value };
                this.objectable.action = false;
                this.objectable.recovery = false;
                this.objectable.quantity_sloc = value.quantity;
                this.objectable.quantity = "";
                if (this.fixed_history.length !== 0) {
                    this.objectable.old_component = this.fixed_history[this.fixed_history.length - 1].new_component;
                    this.objectable.old_content = this.fixed_history[this.fixed_history.length - 1].new_content;
                }

                this.objectable.new_component = { ...value };
                let exíst = false;
                this.asset_tool_item.forEach(item => {
                    if (item.asset_tool_id == value.id) {
                        exíst = true;
                    }
                });
                if (!exíst) {
                    // this.asset_tool_item.push({ ...this.tool_pr });
                    console.log('kim');
                    this.$emit('submitAssetToolPR', { ...this.objectable });
                    $('#assetToolPR').modal('hide');
                }

            });

        },
         resetFilter() {
            this.$refs.filter.resetFilter();
        },
        show(data) {
            this.fetchAssetTools();
            this.item_transaction = [];
            this.fixed_history = [...data];
            this.clearSelected();
            $('#assetToolPR').modal('show');
        }
    },
    watch: {
        asset_tool_query: function (newVal, oldVal) {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.fetchAssetTools()
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

#title_header {
    background: radial-gradient(rgb(20, 149, 255), steelblue 100%, steelblue);
    box-shadow: 1px 1px 10px steelblue;
    color: white;
}

.query-search {
    border-radius: 30px;
}

.position-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.text-repair{
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