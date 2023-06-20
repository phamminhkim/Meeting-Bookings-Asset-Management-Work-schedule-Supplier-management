<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> {{ $t(title) }}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
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
                            <div class="card-header">
                                <h3 class="card-title">{{ $t('form.list') }}</h3>
                                <div class="card-tools">

                                    <button class="btn btn-info btn-sm" @click="showDepartment()"> <i
                                            class="fa fa-plus"></i>
                                        <span> {{ $t('form.create') }}</span>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row" style="background-color:#F4F6F9">
                                    <div class="col-md-9">

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
                                    <b-table striped hover bordered responsive head-variant="light" small
                                        :fields="fields" :items="departments" :filter="filter"
                                        :current-page="current_page" :per-page="per_page">
                                        <template #cell(company_id)="data">
                                            <span>{{ getCompanyName(data.value) }}</span>
                                        </template>
                                        <template #cell(active)="data">
                                            <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                            <span class="badge bg-warning" v-if="data.value == 0">Ngưng hoạt động</span>
                                        </template>
                                        <template #cell(action)="data">
                                            <div class="margin">
                                                <button class="btn btn-xs" @click="editDepartment(data.item)"><i
                                                        class="fas fa-edit text-green " title="Edit"></i></button>

                                                <button class="btn btn-xs" @click="deleteDepartment(data.item.id)"><i
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
                                <!-- <div class="row">
                                <div class="col-sm-12">
                                <table
                                id="simple-table"
                                class="table table-bordered table-striped"
                            >
                                <thead>
                                    <tr>
                                        <th>{{$t('form.code')}}</th>
                                        <th style="min-width: 30px">{{$t('form.name')}}</th>
                                        <th>{{ $t('form.company') }}</th>
                                        <th>action</th>
                                    </tr>
                                </thead>

                                <tbody id="tbbody_id">
                                    <tr
                                        v-for="(department,index) in departments"
                                        v-bind:key="department.id"
                                    >
                                        <td>
                                            {{ department.code }}
                                        </td>
                                        <td>{{ department.name }}</td>

                                        <td>{{ department.company_id }}</td>
                                        <td>
                                            <div
                                                class="hidden-sm hidden-xs btn-group"
                                            >
                                                <button @click="editDepartment(department,index)"
                                                        class="btn btn-xs btn-info mr-3">
                                                    <i
                                                        class="fa fa-edit bigger-120"
                                                    ></i>
                                                </button>
                                                <button @click="deleteDepartment(department.id)"
                                                    class="btn btn-xs btn-danger"
                                                >
                                                    <i
                                                        class="ace-icon fa fa-trash bigger-120"
                                                    ></i>
                                                </button>
                                                
                                                 
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-5"></div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="float-right mt-3">
                                        <pagination 
                                        :data="pagination" 
                                        :show-disabled="true" 
                                        @pagination-change-page="fetchDepartment" 
                                    
                                        :limit="5"> </pagination>
                                    </div>
                                    
                                </div>
                               
                            </div> -->

                            </div>
                            <!-- Loading (remove the following to stop the loading)-->

                            <div class="center overlay" v-if="loading">
                                <i class="fa fa-spinner fa-spin fa-4x fa-fw gray center"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                            <!-- end loading -->
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="AddDepartment" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="addDepartment" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span v-if="!edit">{{ $t('form.add') }}</span>
                                    <span v-if="edit">{{ $t('form.update') }}</span>


                                    {{ $t('form.department') }}
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="CompanyId">{{ $t('form.company') }}</label>
                                    <select name="company_id" class="form-control"
                                        v-bind:class="hasError('company_id') ? 'is-invalid' : ''"
                                        v-model="department.company_id" @change="clearError($event)">
                                        <option v-for="company in companies" :key="company.id"
                                            v-bind:value="company.id">
                                            {{ company.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('company_id') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="DepartmentCode">{{ $t('form.code') }}</label>
                                    <input v-model="department.code" type="text" class="form-control"
                                        v-bind:class="hasError('code') ? 'is-invalid' : ''" id="code" name="code"
                                        placeholder="Department code" :rules="['rules.required,rules.min']" required />

                                    <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('code') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="DepartmentName">{{ $t('form.name') }}</label>
                                    <input v-model="department.name" type="text" class="form-control"
                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                                        placeholder="Department Name" required />
                                    <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('name') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t('form.back') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t('form.save') }}
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


// import Pagination from "../shared/Pagination.vue";
export default {
    props: {
        title: ""
    },
    components: {
        // Pagination
    },
    data() {
        return {

            departments: [],
            pagesNumber: [],
            department: {
                id: "",
                code: "",
                name: "",
                company_id: "",
                index: ""
            },
            companies: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            filter: "",
            modules: [],
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            paginationdata: {},
            loading: false,
            edit: false,
            token: "",
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            fields: [
                {
                    key: 'company_id',
                    label: this.$t('form.company'),
                    sortable: true,
                    stickyColumn: true
                },

                {
                    key: 'code',
                    label: this.$t('form.code'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label: this.$t('form.name'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'action',
                    label: 'Hành động',
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
        this.fetCompany();
        this.fetchDepartment();

    },
    mounted() {

        // this.fetchDepartment(this.pagination.current_page);

    },
    methods: {
        fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                    //  console.length(res);
                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetchDepartment(page) {
            //$("#tbbody_id").html('');
            this.loading = true;
            var page_url = this.page_url_department;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
            this.edit = false;

        },
        showDepartment() {
            this.reset();
            $("#AddDepartment").modal("show");
        },
        addDepartment() {
            var page_url = this.page_url_department;// "/api/category/departments";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.department),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.departments.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#AddDepartment").modal("hide");

                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.department.id, {
                    method: "PUT",
                    body: JSON.stringify(this.department),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {

                            // this.departments[this.department.index]=this.department;     
                            this.$set(this.departments, this.department.index, { ...this.department });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#AddDepartment").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        editDepartment(department) {
            // var department={..._obj};
            let index = this.departments.findIndex(i => {

                return i.id == department.id;
            });
            this.edit = true;
            this.department.id = department.id;
            this.department.company_id = department.company_id;
            this.department.code = department.code;
            this.department.name = department.name;
            this.department.index = index;
            $("#AddDepartment").modal("show");
        },
        deleteDepartment(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_department}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchDepartment();
                    });
            }
        },
        getCompanyName(company_id) {
            var obj = this.companies.find(x => x.id == company_id);
            return obj != null ? obj.name : '';
        },
        hasError(fieldName) {
            return (fieldName in this.errors);
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
            for (let field in this.department) {
                this.department[field] = null;
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
        }
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.departments.length;
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