<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">
                            <i class="nav-icon fas fa-file-contract"></i> Danh sách hàng hóa
                        </h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <button class="btn btn-info btn-sm" @click="addGoods()"> <i class="fa fa-plus"></i>
                                <span> {{ $t('form.create') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row mt-0">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">

                                            <span v-if="!show_search">{{ $t('form.show_search') }}</span>
                                            <span v-if="show_search">{{ $t('form.hide_search') }}</span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">

                                            <i v-if="show_search" class="fas fa-angle-up"></i>
                                            <i v-else class="fas fa-angle-down"></i>
                                        </button>
                                    </div>
                                    <button
                    @click="filter_data()"
                    :title="$t('form.filter')"
                    class="btn btn-secondary btn-xs ml-1"
                  >
                    <i class="fas fa-sync-alt" :title="$t('form.reload')"></i>
                  </button>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="row"></div>
                            </div>

                        </div>

                        <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                            <div class="col-sm-12 ">
                                <br>
                                <div class="form-group row">

                                    <label class="col-form-label-sm col-sm-1 col-form-label text-left text-md-right "
                                        style="text-align:left" :title="$t('form.employee_code')" for=""> Mã mặt
                                        hàng</label>
                                    <div class="col-sm-2">
                                        <input type="text" v-model="form_filter.code"
                                            class="form-control form-control-sm mt-1">
                                    </div>
                                    <label class="col-form-label-sm col-sm-1 col-form-label text-left text-md-right"
                                        style="text-align:left" :title="$t('form.employee_name')" for="">Tên mặt
                                        hàng</label>
                                    <div class="col-sm-2">
                                        <input type="text" v-model="form_filter.name"
                                            class="form-control form-control-sm mt-1">
                                    </div>
                                    <label class="col-form-label-sm col-sm-1 col-form-label text-left text-md-right"
                                        style="text-align:left" for="goodunit_id">
                                        Mã đơn vị</label>
                                    <div class="col-sm-2">
                                        <select name="" class="form-control form-control-sm mt-1" id="goodunit_id"
                                            v-model="form_filter.goodunit_id">
                                            <option v-for="goodunitss in goodunits" :key="goodunitss.id"
                                                v-bind:value="goodunitss.id">
                                                {{ goodunitss.name }}
                                            </option>
                                            <option value="">All</option>
                                        </select>
                                    </div>



                                    <div class="col-md-13">
                                        <button type="submit" class="btn btn-warning btn-sm mt-1"
                                            @click="filter_data()"> <i class="fa fa-search"></i> {{ $t('form.find')
                                            }}
                                        </button>
                                        <button type="reset" class="btn btn-secondary btn-sm mt-1"
                                            @click="clearFilter()"> <i class="fa fa-reset"></i>
                                            {{ $t('form.clear') }}</button>

                                    </div>
                                </div>
                                <br>


                            </div>


                        </div>


                        <div class="row mt-1" style="background-color: #f4f6f9">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"
                                        :title="$t('form.edit')" @click="editGoods()">
                                        <i class="fas fa-edit"></i>{{ $t("form.edit") }}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm ml-1 mt-1"
                                        @click.prevent="deleteGoods()">
                                        <i class="fas fa-window-close">{{ $t('form.delete') }}</i>

                                    </button>
                                    <!-- <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1">
                                        <i class="fas fa-bell"></i> {{ $t("form.reminder") }}
                                    </button> -->
                                    <download-excel class="btn btn-info btn-sm ml-1 mt-1" :name="fileName"
                                        :data="tableData" type="xls" :fields="fieldexcel">
                                        <i class="fas fa-file-excel"></i>
                                        Excel
                                    </download-excel>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row mt-1">
                                    <div class="input-group input-group-sm col-12">
                                        <input class="form-control form-control-navbar mb-1" id="search" type="search"
                                            :placeholder="$t('form.search')" aria-label="Search" v-model="filter" />
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-default btn-flat mb-1">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                        <!-- <download-excel
                          :data   = "contracts" title="Eport Excel"  class="ml-2" >
                        <span style="cursor: pointer;"><i class="fa fa-download"></i></span>
                      </download-excel> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="active tab-pane" id="">
                            <div class="row">
                                <b-table striped hover responsive :bordered="true" head-variant="light"
                                    :sticky-header="false" small :items="goods" :current-page="current_page"
                                    :per-page="per_page" :filter="filter" :fields="fields">
                                    <template #head(selected)>
                                        <!-- {{selected}} -->
                                        <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select">
                                        </b-form-checkbox>
                                    </template>
                                    <template v-slot:cell(newtab)="data">
                                        <a target="_blank" :href="viewGoods(data.item.id)"><i title="View in new tab"
                                                class="fas fa-external-link-alt"></i></a>
                                    </template>
                                    <template v-slot:cell(selected)="data">
                                        <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected">
                                        </b-form-checkbox>
                                    </template>
                                    <template #cell(goodunit_id)="data">
                                        <span>{{ goodunit_name(data.value) }}</span>
                                    </template>
                                    <!-- <template #cell(open_stock)="data">
                                        <span>vv</span>
                                    </template> -->
                                </b-table>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label-sm col-md-4" style="text-align: left"
                                                for="">Per page:</label>
                                            <div class="col-md-3">
                                                <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                                </b-form-select>
                                                <!-- <b-pagination v-model="current_page" :total-rows="rows"
                                                        :per-page="per_page" size="sm" class="ml-1"></b-pagination> -->
                                            </div>
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for=""></label>
                                            <div class="col-md-3">
                                                <b-pagination v-model="current_page" :total-rows="rows"
                                                    :per-page="per_page" size="sm" class="ml-1"></b-pagination>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <loading :loading="loading"></loading>

                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->

    </div>
</template>

<script>
import moment from "moment";

import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {

    data() {
        return {
            total: 0,
            fieldexcel: {
                
                "Mã mặt hàng": "Mã_mặt_hàng",
                "Tên mặt hàng": "Tên_mặt_hàng",
                "Mô tả": "Mô_tả",
                "Kích thước": "Kích_thước",
                "Màu sắc": "Màu_sắc",
                "Hình ảnh":"Hình_ảnh",
                "Trọng lượng": "Trọng_lượng",
                "Tồn kho": "Tồn_Kho",
            },
            fileName: "DanhSachMatHang_" + moment(Date()).format("MM/DD/YYYY"),
            goodss: {
                id: "",
                code: "",
                name: "",
                description: "",
                goodunit_id: "",
                size: "",
                color: "",
                weight: "",
                open_stock: "",
                index: "",
                goods: [],
                goods_del: [],
                gooddocus_details: [],
            },
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            paginationdata: {},
            errors: {},
            status: "",
            goods: [],
            show_search: false,
            selectAll: false,
            modules: [],
            fields: [
                {
                    key: "selected",
                    label: "All",
                    stickyColumn: true,
                }, {
                    key: "newtab",
                    label: "",
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "code",
                    label: "Mã mặt hàng",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "name",
                    label: "Tên mặt hàng",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "description",
                    label: "Mô tả",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "goodunit_id",
                    label: "Mã đơn vị",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "size",
                    label: "Kích thước",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "color",
                    label: "Màu sắc",
                    sortable: true,
                    stickyColumn: true,


                },
                
                {
                    key: "weight",
                    label: "Trọng lượng",
                    sortable: true,
                    stickyColumn: true,


                },

                {
                    key: "open_stock",
                    label: "Tồn kho",
                    sortable: true,
                    stickyColumn: true,


                },


            ],
            goodunits: [],
            selected: [],
            selectAll: false,
            paginationdata: {},
            tab: "",
            loading: false,
            edit: false,
            token: "",
            pagesNumber: [],
            tableData: [],
            filter: "",

            form_filter: {
                code: "",
                name: "",
                goodunit_id: "",

            },
            time: [],
            feedback: false,
            page_url_gooddocus: "api/sloc/gooddocuss",
            page_url_detail: "/api/sloc/goods",
            page_url_goodunits: "/api/sloc/goodunits",

        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.fetGoods();
        this.fetGoodunits();
        this.fetchGoodDocus();
    },
    methods: {
        fetchGoodDocus() {
            //$("#tbbody_id").html('');
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;

            var page_url = this.page_url_gooddocus  ;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.time = res.data;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
         viewGoods(id) {
            return '/sloc/goods?type=view&id=' + id;
        },
        goodunit_name(goodunit_id) {
            var obj = this.goodunits.find(x => x.id == goodunit_id);
            if (obj)
                return obj.name;
            else
                return "";
        },
        addGoods() {
            window.location.href = '/sloc/goods?type=add';
        },
        reloadPage() {
            window.location.reload();
        },
        showSearch() {
            this.show_search = !this.show_search;
        },
        filter_data() {
            this.fetGoods();

        },
        fetGoodunits() {
            var page_url = this.page_url_goodunits;
            fetch(page_url, {
                headers: {
                    Authorization: this.token
                }
            }).then(res => res.json())
                .then(data => {
                    this.goodunits = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        fetGoods() {
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.goods = Array();

            const params = new URLSearchParams({
                code: this.form_filter.code,
                name: this.form_filter.name,
                goodunit_id: this.form_filter.goodunit_id,

            });
            var page_url = this.page_url_detail + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.goods = res.data;
                        var list = [];
                        for (let i = 0; i < this.goods.length; i++) {
                            list.push({
                                STT: i,
                                Mã_mặt_hàng: this.goods[i].code,
                                Tên_mặt_hàng: this.goods[i].name,
                                Mô_tả: this.goods[i].description,
                                Kích_thước: this.goods[i].size,
                                Màu_sắc: this.goods[i].color,
                             
                                Trọng_lượng: this.goods[i].weight,
                                Tồn_Kho: this.goods[i].open_stock,

                            });
                        }
                        this.tableData = list;
                    }
                    this.loading = false;

                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;9
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.goodss) {
                this.goodss[field] = null;
            }
        },
        editGoods() {
            if (this.selected.length != 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }
            var id = this.selected;

            window.location.href = "sloc/goods?type=edit&id=" + id;
        },
        deleteGoods() {
            if (this.selected.length != 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }
            var id = this.selected;
            let index = this.goods.findIndex((i) => {
                console.log("id" + i.id);
                return i.id == id;
            });
            console.log("id=" + id);
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
                title: "",
                size: "sm",
                buttonSize: "sm",
                okVariant: "danger",
                okTitle: "OK",
                cancelTitle: "Cancel",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            })
                .then((value) => {
                    if (value) {
                        var page_url = this.page_url_detail + "/" + id;
                        fetch(page_url, {
                            method: "DELETE",
                            headers: {
                                Authorization: this.token,
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                        })
                            .then((res) => res.json())
                            .then((res) => {
                                if (res.statuscode == "403") {
                                    toastr.warning(this.$t(res.message), this.$t("form.warning"));
                                    return;
                                }
                                if (res.data.success == "1") {
                                    // this.requests = [];
                                    this.goods.splice(index, 1);
                                    // this.$refs.selectableTable.refresh()
                                    toastr.success(this.$t("form.success"), "");
                                    this.selected = [];
                                } else {
                                    toastr.warning(this.$t(res.data.message), this.$t("form.warning"));
                                }
                            })
                            .catch((err) => console.log(err));
                    }
                })
                .catch((err) => {
                    console.log(err);
                });

            // if(confirm("Xác nhận xoá ?")){

            // }
        },
        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.goods) {
                    this.selected.push(this.goods[i].id);
                }
            }
        },
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.status === 'awesome') return 'table-success'
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        // viewSloc(id) {
        //     return '/sloc/goods?type=view&id=' + id;
        // },
        showGood() {
            this.reset();
            $("#AddGood").modal("show");
        },

        clearAllError() {
            this.errors = {};
        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        },
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        getError(fieldName) {
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        }


    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.goods.length;
        },
        placeholder() {
            return this.$t('form.search');
        }
    },
    mounted() {
        console.log("Component mounted.");
    }
};
</script>

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}

.dropdown-submenu>.dropdown-menu {
    left: auto !important;
    margin-left: 10px;
    margin-top: 30px;
    top: 0;
    // right:auto !important;
    //   right: 100%;
}
</style>
