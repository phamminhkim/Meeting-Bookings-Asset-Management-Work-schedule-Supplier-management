<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">
                            <i class="nav-icon fas fa-file-contract"></i> Nhập xuất kho
                        </h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <button class="btn btn-info btn-sm" @click="view()"> <i class="fa fa-plus"></i>
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
                                    <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                                            <span  v-if="!show_search">{{ $t("form.show_search") }}</span>
                                            <span  v-if="show_search">{{ $t("form.hide_search") }}</span>
                                        </button>
                                         <button
                      type="button"
                      class="btn btn-warning btn-xs"
                      @click="showSearch()"
                    >
                      <i v-if="show_search" class="fas fa-angle-up"></i>
                      <i v-else class="fas fa-angle-down"></i>
                    </button>
                                    </div>
                                    <!-- <button type="button" :title="$t('form.filter')" onclick="location.reload(true)" class="btn btn-secondary  btn-xs ml-1" ><i class="fas fa-redo-alt" title="Refresh"></i></button> -->
                                    <button class="btn btn-secondary btn-xs ml-1" @click.prevent="reloadPage()" >
                                        <i class="fas fa-sync-alt" ></i>
                                    </button>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row"></div>
                            </div>
                        </div>
                        <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                            <div class="col-sm-12 ">
                               
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-sm-1.5 col-form-label   "
                                          for="" style="padding:10px"> Mã kho</label>
                                        <div class="col-sm-2">
                                            <select name="" class="form-control form-control-sm mt-1" id="sloc_id"
                                        v-model="form_filter.sloc_id">
                                        <option v-for="slocc in asset_warehouses" v-bind:value="slocc.id">
                                            {{ slocc.code }}
                                        </option>
                                        <option value="">All</option>

                                    </select>
                                        <!-- <input type="text" v-model="form_filter.sloc_id" class="form-control form-control-sm mt-1"> -->
                                        </div>
                                    <label class="col-form-label-sm col-sm-1.5 col-form-label" 
                                    for="" style="padding:10px">Mã Chứng Từ</label>
                                    <div class="col-sm-2">
                                        <!-- <input type="text" v-model="form_filter.serial_num"
                                            class="form-control form-control-sm mt-1"> -->
                                            <select name="" class="form-control form-control-sm mt-1" 
                                        v-model="form_filter.serial_num">
                                        <option value="Tạo Mới Chứng Từ">Tạo Mới Chứng Từ</option>
                                        <option value="Cập Nhật Chứng Từ">Cập Nhật Chứng Từ</option>

                                        <option value="">All</option>

                                    </select>
                                    </div>
                                        
                                    <label class="col-form-label-sm col-sm-1.5 col-form-label "
                                        style="padding:10px" >Người tạo</label>
                                        <div class="col-sm-2">
                                        <!-- <input type="text" v-model="form_filter.user_id"
                                            class="form-control form-control-sm mt-1"> -->

                                            <treeselect placeholder="Tất cả" @click="filter_data()" 
                                                     :disable-branch-nodes="true"
                                                     :show-count="true"
                                                    :multiple="false" v-model="form_filter.user_id"
                                                    :options="tree_users" required></treeselect>
                                        </div>
                                        <br>
                                    <label class="col-form-label-sm col-sm-1.5 col-form-label "
                                        style="padding:10px" >Loại phiếu</label>
                                        <div class="col-sm-2">
                                        <!-- <input type="text" v-model="form_filter.type"
                                            class="form-control form-control-sm mt-1"> -->
                                            <select name="" class="form-control form-control-sm mt-1"
                                        v-model="form_filter.type" >
                                      
                                            <option value="0">Nhập</option>
                                            <option value="1">Xuất</option>
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
                               


                            </div>


                        </div>

                        <div class="row mt-1" style="background-color: #f4f6f9">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    
                                    <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->

                                    <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editDocument()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->
                                    <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1"  title="In" @click="printRequest()"><i class="fas fa-print" title="In hợp đồng"></i></button> -->

                                    <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"
                                        :title="$t('form.edit')" @click="editRequest()">
                                        <i class="fas fa-edit"></i>{{ $t("form.edit") }}
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.delete')"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" :title="$t('form.delete')"></i> {{$t('form.delete')}} </button> -->
                                    <button type="button" class="btn btn-danger btn-sm ml-1 mt-1"
                                        @click.prevent="deletedetails()">
                                        <i class="fas fa-window-close"> Hủy bỏ</i>

                                    </button>
                                    <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.paid')}}</button> -->
                                    <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1">
                                        <i class="fas fa-bell"></i> {{ $t("form.reminder") }}
                                    </button>
                                    <download-excel class="btn btn-info btn-sm ml-1 mt-1"  :name="fileName" :data="tableData" :fields="fieldexel"   >
                                        <i class="fas fa-file-excel"></i>
                                        Excel
                                    </download-excel>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row mt-1">
                                    <div class="input-group input-group-sm col-12">
                                        <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                        <input class="form-control form-control-navbar mb-1" id="search" type="search"
                                            :placeholder="$t('form.search')" aria-label="Search" v-model="filter" />
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-default btn-flat mb-1">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <!-- <button type="button" @click="searchContact()" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button> -->
                                        </span>
                                        <!-- <download-excel
                          :data   = "contracts" title="Eport Excel"  class="ml-2" >
                        <span style="cursor: pointer;"><i class="fa fa-download"></i></span>
                      </download-excel> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="active tab-pane" id="yeucaumoi">
                            <div class="row">
                                <b-table striped hover responsive :bordered="true" head-variant="light"
                                    :sticky-header="false" small :items="gooddocus" :current-page="current_page"
                                    :per-page="per_page" :filter="filter" primary-key="id" :fields="fields">
                                    <template #head(selected)>
                                        <!-- {{selected}} -->
                                        <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select">
                                        </b-form-checkbox>
                                    </template>
                                    <template v-slot:cell(newtab)="data">
                                        <a target="_blank" :href="viewSloc(data.item.id)"><i title="View in new tab"
                                                class="fas fa-external-link-alt"></i></a>
                                    </template>
                                    <template v-slot:cell(selected)="data">
                                        <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected">
                                        </b-form-checkbox>
                                    </template>
                                    <template #cell(type)="data">
                                        <span class="badge bg-success" v-if="data.item.type == 0">Nhập</span>
                                        <span class="badge bg-red" v-if="data.item.type == 1">Xuất</span>
                                    </template>
                                    <template #cell(sloc_id)="data">
                                        <span>{{ getwarehouseNameName(data.value) }}</span>
                                    </template>
                                    
                                    <!-- <template #cell(active)="data">
                                        <div class="margin">
                                            <button class="btn btn-xs" @click="editGood(data.item)"><i
                                                    class="fas fa-edit text-green " title="Edit"></i></button>

                                            <button class="btn btn-xs" @click="deleteGood(data.item.id)"><i
                                                    class="fas fa-trash text-red " title="Delete"></i></button>

                                        </div> -->
                                    <!-- </template> -->
                                </b-table>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label-sm col-md-4" style="text-align: left"
                                                for="">Per page:</label>
                                            <div class="col-md-3">
                                                <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                                </b-form-select>
                                            </div>
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for=""></label>
                                            <div class="col-md-3">
                                                <b-pagination align="fill" size="sm" class="my-0"></b-pagination>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->

    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import moment from "moment";
export default {
    components: {
        Treeselect,
    },
    data() {
        return {
            document_code: "",
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},
            status: "",
            locale_format: "de-DE",
            gooddocus:[],
            selected: [],
            tableData: [],
            fileName: "Nhập xuất kho_" + moment(Date()).format("MM/DD/YYYY"),

            fieldexel: {
                " Mã Hàng Hóa":"Mã_Hàng_Hóa",
                " Mã Chứng Từ":"Mã_Chứng_Từ",
                " Người Giao Hàng":"Người_Giao_Hàng",
                " Người Tạo Chứng Từ":"Người_Tạo_Chứng_Từ",
                " Loại Phiếu":"Loại",
                " Lý Do Nhập":"Lý_Do_Nhập"
            },
            selectAll: false,
            fields: [
                {
                    key: "selected",
                    label: "All",
                    stickyColumn: true,
                },
                {
                    key: "newtab",
                    label: "",
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "sloc_id",
                    label: "Mã Kho",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "serial_num",
                    label: "Mã Chứng Từ",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "shipper_name",
                    label: "Người Giao Hàng",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "user.name",
                    label: "Người Tạo Chứng Từ",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "type",
                    label: "Loại Phiếu",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",

                },
                {
                    key: "reason",
                    label: "Lý Do Nhập",
                    sortable: true,
                    stickyColumn: true,


                },

            ],
            page_url_users:"api/user/all",

            page_url_slocs: "api/asset/warehouse",
            asset_warehouses: [],
            tab: "",
            loading: false,
            edit: false,
            token: "",
            feedback: false,
            page_url_detail: "api/sloc/gooddocuss",
            users:[],
            // page_url_tree_users: "api/user/all",
            show_search:false,
            form_filter: {
                sloc_id: "",
                serial_num: "",
                user_id: null,
                type:""
            },
            asset_groups:[],
            tree_users:[],
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.fetDetails();
        this.getUser();
        this.getSlocs();
        this.fetAssetGroup();
        this.getUserTree();



    },
    methods: {
        getUserTree(){
         var  page_url = this.page_url_users+"?type=tree_combobox"
          //console.log(page_url);
          fetch(page_url,{
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
          }).then(res=>res.json())
          .then(data=>{
              this.tree_users = data.data;
          }) .catch(err => {
                    console.log(err);
                });

      },
        fetAssetGroup() {
            var page_url = this.url_asset_group;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_groups = res.data;
                    console.log(res.data);
                })
                .catch(err => console.log(err));
        },
        getwarehouseNameName(asset_warehouse_id) {
            var obj = this.asset_warehouses.find(x => x.id == asset_warehouse_id);
            return obj != null ? obj.code : '';
        },
        getSlocs() {
            var page_url = this.page_url_slocs
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
        reloadPage() {
            window.location.reload();
        },
        view() {
            window.location.href = '/sloc/gooddocus?type=add';
        },
        fetDetails() {
            var page_url = this.page_url_detail;
            let vm = this;
            this.gooddocus = Array();
            const params = new URLSearchParams({
                sloc_id: this.form_filter.sloc_id,
                serial_num: this.form_filter.serial_num,
                user_id: this.form_filter.user_id,
                type: this.form_filter.type,
            });
            var page_url = this.page_url_detail + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "Content-Type": "application/json",
                }
            })
                .then((res) => res.json())
                .then((res) => {
                    if(res.success == '1'){
                    this.gooddocus = res.data;
                    var list =[];
                    for (let i = 0; i< this.gooddocus.length;i++){
                        list.push({
                            STT: i,
                            Mã_Hàng_Hóa: this.gooddocus[i].sloc_id,
                            Mã_Chứng_Từ:this.gooddocus[i].serial_num,
                            Người_Giao_Hàng: this.gooddocus[i].shipper_name,
                            Người_Tạo_Chứng_Từ: this.gooddocus[i].user.name,
                            Loại: this.gooddocus[i].type,
                            Lý_Do_Nhập: this.gooddocus[i].reason
                        });
                    }
                    
                    this.tableData= list;
                    }
                })
                .catch((err) => console.log(err));
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
        editRequest() {
            if (this.selected.length != 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }
            var id = this.selected;

            window.location.href = "sloc/gooddocus?type=edit&id=" + id;
        },
        deletedetails() {
            if (this.selected.length != 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }
            var id = this.selected;
            let index = this.gooddocus.findIndex((i) => {
                console.log("id" + i.id);
                return i.id == id;
            });
            console.log("id=" + id);
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận hủy chứng từ") + "?", {
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
                                    this.gooddocus.splice(index, 1);
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
        filter_data() {
            this.fetDetails();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;
        },
        
        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.gooddocus) {
                    this.selected.push(this.gooddocus[i].id);
                }
            }
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        viewSloc(id) {
            return '/sloc/gooddocus?type=view&id=' + id;
        },
        showGood() {
            this.reset();
            $("#AddGood").modal("show");
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.good) {
                this.good[field] = null;
            }
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

        },
        showSearch() {
      this.show_search = !this.show_search;
      //this.clearFilter();
    },


    },
    computed: {

    },
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
