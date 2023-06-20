<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">  {{ $t(title)}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                        <!-- <a href="payment/rests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                        <button  class="btn btn-info btn-sm" @click="showGoodunits()"> <i class="fa fa-plus"></i>
                                            {{ $t('form.create')}}</button>
                        
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
                                        
                                        <label class="col-form-label-sm col-md-1" style="text-align:left"
                                            :title="$t('form.employee_name')" for="">Tên Mặt Hàng</label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="form_filter.name"
                                                class="form-control form-control-sm mt-1">
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
                                           <download-excel class="btn btn-info btn-sm ml-1 mt-1"  :data="tableData" type="xls">
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
                                    <b-table ref="table" striped hover bordered responsive head-variant="light" small
                                        :fields="fields" :items="goodunits" :filter="filter"
                                        :current-page="current_page" :per-page="per_page" :tbody-tr-class="rowClass">

                                        <template #cell(action)="data">
                                            <div class="margin">
                                                <button class="btn btn-xs" @click="editGoodunits(data.item)"><i
                                                        class="fas fa-edit text-green " title="Edit"></i></button>

                                                <button class="btn btn-xs" @click="deleteGoodunits(data.item.id)"><i
                                                        class="fas fa-trash text-red " title="Delete"></i></button>

                                            </div>
                                        </template>
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

            <!-- Modal -->
            <div class="modal fade" id="AddGoodunits" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="AddGoodunits">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Add Good units
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input class="form-control" v-model="goodunitss.name" />
                                </div>

                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Đóng
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue"
import vueJsonExcel from "vue-json-excel"

Vue.use(vueJsonExcel)
export default {
    props: {
        title: ""
    },
    components: {

    },
    data() {
        return {
            goodunits: [],
            pagesNumber: [],
            goodunitss: {
                id: "",

                name: "",

                index: ""
            },

            tableData: [],
            fieldexcel: {
                "so thu tu": "so_thu_tu",
                "Ten Hang": "Ten_Hang"


            },


            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            filter: "",
            modules: [],
            show_search: false,
            form_filter: {
                name: "",

            },
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            paginationdata: {},
            loading: false,
            edit: false,
            token: "",
            url_goodunits: "/api/sloc/goodunits",

            fields: [
                {
                    key: 'id',
                    label: '#',
                    sortable: true,
                    stickyColumn: true

                },
                {
                    key: 'name',
                    label: 'Tên Hàng',
                    sortable: true,
                    stickyColumn: true
                }, {
                    key: 'action',
                    label: 'Action',
                    sortable: true,
                    stickyColumn: true
                },
            ],
            selected: [],
            selectAll: false,

        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });

        this.fetchGoodunits();

    },
    mounted() {


    },
    methods: {
        showGoodunits() {
            this.reset();
            $("#AddGoodunits").modal("show");
        },
        filter_data() {
            this.fetchGoodunits();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;
        },
        exportTable() {

        },
        
        AddGoodunits() {
            var page_url = this.url_goodunits;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.goodunitss),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.goodunits.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#AddGoodunits").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Thông tin nhập không chính xác');
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.goodunitss.id, {
                    method: "PUT",
                    body: JSON.stringify(this.goodunitss),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {

                            this.$set(this.goodunits, this.goodunitss.index, { ...this.goodunitss });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#AddGoodunits").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        editGoodunits(goodunitss) {

            let index = this.goodunits.findIndex(i => {
                console.log("id" + i.id);
                return i.id == goodunitss.id;
            });
            this.edit = true;
            this.goodunitss.id = goodunitss.id;


            this.goodunitss.name = goodunitss.name;
            this.goodunitss.index = index;
            $("#AddGoodunits").modal("show");
        },
        deleteGoodunits(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_goodunits}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchGoodunits();
                    });
            }
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        fetchGoodunits(page,search) {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.teams = Array();
            const params = new URLSearchParams({
                name: this.form_filter.name,

            });
            var page_url = this.url_goodunits + '?' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                   if (res.success == '1') {
                        this.goodunits = res.data;
                        var list = [];
                        for (let i = 0; i < this.goodunits.length; i++) {
                            list.push({
                                So_thu_tu: i,
                                Ten_hang: this.goodunits[i].name,
                               

                            });

                        }
                        this.tableData = list;
                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        getError(fieldName) {
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.goodunitss) {
                this.goodunitss[field] = null;
            }
        },
        clearAllError() {
            this.errors = {};
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.status === 'awesome') return 'table-success'
        }
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.goodunits.length;
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
<style scoped>
/* fix khoảng cách bên dưới table so với phân trang */
.table {
    margin-bottom: 0px;
}
</style>
