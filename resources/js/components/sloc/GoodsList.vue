<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="nav-icon fas fa-file-contract"></i> Thống Kê</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                        <!-- <a href="payment/rests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                       
                        
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
        
                            
                            <!-- /.card-header -->
                            <div class="card-body">

                                 <div class="row mt-0">

                                        <div class="col-md-9">
                                            <div class="form-group row">

                                                <div class="btn-group ">
                                                <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                                                <span v-if="!show_search">{{$t('form.show_search')}}</span>
                                                <span v-if="show_search">{{$t('form.hide_search')}}</span>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-xs " @click="showSearch()" >
                                                <i v-if="show_search" class="fas fa-angle-up"></i>
                                                <i v-else class="fas fa-angle-down"></i>
                                                </button>
                                                
                                            </div>

                                            </div>

                                        </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                                    <div class="col-md-12 ">
                                        <div class="form-group row">
                                            
                                            <label class="col-form-label-sm col-md-1.5" style="text-align:left"
                                                :title="$t('form.employee_name')" for=""> Tên Công cụ dụng cụ</label>
                                            <div class="col-md-3">
                                                <treeselect placeholder="Tất cả"
                                                     :disable-branch-nodes="true"
                                                     :show-count="true"
                                                    :multiple="false" v-model="form_filter.name"
                                                    :options="tree_tools" required></treeselect>
                                                <!-- <input type="text" v-model="form_filter.name"
                                                    class="form-control form-control-sm mt-1"> -->
                                            </div>
                                            <div class="col-md-3">
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
                                <div class="row" style="background-color:#F4F6F9">
                                    <div class="col-md-9">
                                          <download-excel class="btn btn-info btn-sm ml-1 mt-1" :name="fileName" :fields="fieldexel" :data="tableData" type="xls">
                                            <i class="outline-primary"></i>
                                            <i class="fas fa-file-excel"></i> Excel
                                        </download-excel>   
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm mt-1 mb-1">
                                            <input type="search" class="form-control form-control-navbar"
                                                v-model="filter" :placeholder="$t('form.search')" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <b-table striped hover responsive :bordered="true" head-variant="light"
                        :sticky-header="false" small :items="list" 
                         :filter="filter" primary-key="id" :fields="fields">
                </b-table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="margin">
                                                <div class="btn-group">
                                                    <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                                    <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                                    </b-form-select>
                                                    <b-pagination v-model="current_page" :total-rows="rows"
                                                        :per-page="per_page" size="sm" class="ml-1"></b-pagination>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="center overlay" v-if="loading">
                                <i class="fa fa-spinner fa-spin fa-4x fa-fw gray center"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <!-- <div class="active tab-pane" id="yeucaumoi">
            <div class="row">
               
                <b-table striped hover responsive :bordered="true" head-variant="light"
                        :sticky-header="false" small :items="list" 
                         :filter="filter" primary-key="id" :fields="fields">
                </b-table>
            </div>
        </div> -->
    </div>
    </div>
</template>

<script>
    import Vue from "vue"
import vueJsonExcel from "vue-json-excel"
import Treeselect from "@riophae/vue-treeselect";
import moment from "moment";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
Vue.use(vueJsonExcel)
export default {
    components: {
        Treeselect,
    },
    data() {
        return {
            fileName: "Thống kê_" + moment(Date()).format("MM/DD/YYYY"),

            list: [],
            show_search: false,
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            filter: "",
            modules: [],
            form_filter: {
                name: null,

            },
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},
            tableData: [],
            fieldexel: {
                "Tên Sản Phẩm": "Ten_San_Pham",
                "Tổng nhập": "Tong_Nhap",
                "Tổng xuất":"Tong_Xuat",
                "Tồn Kho":"Ton_Kho"

            },
            loading: false,
            current_page: 1,
            per_page: 10,
            token: '',
            filter:'',
            fields: [
                {
                    key:'name',
                    label:'Tên công cụ dụng cụ',
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key:'tongnhap',
                    label:'Tổng nhập',
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key:'tongxuat',
                    label:'Tổng xuất',
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key:'ton',
                    label:'Tồn kho',
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
            ],
            loading: false,
            page_url_list: "api/sloc/goodlist",
            tree_tools:[],
            url_asset_toolss:  "api/asset/all",

        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchlist();
        this.fetTools_Tree();

    },
    methods: {
        fetTools_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.url_asset_toolss + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_tools = data.data;
                }).catch(err => {

                console.log(err);
            });
        },
   
        filter_data() {
            this.fetchlist();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        fetchlist() {
            //$("#tbbody_id").html('');
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            this.list = Array();
            const params = new URLSearchParams({
                name: this.form_filter.name,

            });
            var page_url = this.page_url_list + '?' + params.toString();

            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                    if (res.success == '1') {
                        this.list = res.data;
                        var abc = [];
                        for (let i = 0; i < this.list.length; i++) {
                            abc.push({
                                So_thu_tu: i,
                                Ten_San_Pham: this.list[i].name,
                                Tong_Nhap: this.list[i].tongnhap,
                                Tong_Xuat: this.list[i].tongxuat,
                                Ton_Kho: this.list[i].ton,
                                

                            });

                        }
                        this.tableData = abc;
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
            return this.list.length;
        },
        placeholder() {
            return this.$t('form.search');
        }
    },
}
</script>

<style>
</style>