<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> {{ $t(title) }}</h5>
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
                                <h3 class="card-title"> {{ $t(title) }}</h3>
                                <div class="card-tools">
                                    <button class="btn btn-primary btn-sm" @click="showImportForm()"> <i
                                            class="fa fa-upload"></i>
                                        <span> Cập nhật hàng loạt</span>
                                    </button>
                                    <button class="btn btn-info btn-sm" @click="showCreateNewEmployeeForm()"> <i
                                            class="fa fa-plus"></i>
                                        <span> {{ $t('form.create') }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group row">
                                            <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    @click="showFilter()">
                                                    <span v-if="show_filter">{{ $t("form.hide_search") }}</span>
                                                    <span v-else>{{ $t("form.show_search") }}</span>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    @click="showFilter()">
                                                    <i v-if="show_filter" class="fas fa-angle-up"></i>
                                                    <i v-else class="fas fa-angle-down"></i>
                                                </button>
                                            </div>
                                            <!-- <button type="button" :title="$t('form.filter')" onclick="location.reload(true)" class="btn btn-secondary  btn-xs ml-1" ><i class="fas fa-redo-alt" title="Refresh"></i></button> -->
                                            <button @click="fetchEmployees()" :title="$t('form.filter')"
                                                class="btn btn-secondary btn-xs ml-1">
                                                <i class="fas fa-sync-alt" :title="$t('form.reload')"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row"></div>
                                    </div>
                                </div>
                                <div class="row" v-if="show_filter"
                                    style="border: 1px solid #e5e5e5; border-radius: 5px">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label for="scope"
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right">Phạm
                                                vi</label>
                                            <div class="col-sm-4">
                                                <treeselect id="scope" placeholder="Tất cả" v-model="form_filter.scopes"
                                                    :options="scopes" :multiple="true" />
                                            </div>
                                            <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">Loại lao động</label>
                                            <div class="col-sm-4">
                                                <treeselect id="direct_type" :disabled="edit" placeholder="Tất cả"
                                                    :disable-branch-nodes="true" v-model="form_filter.direct_type"
                                                    :options="filter_direct_types" :multiple="true" required />
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">Loại nhân viên</label>
                                            <div class="col-sm-4">
                                                <treeselect id="employee_type" :disabled="edit" placeholder="Tất cả"
                                                    :disable-branch-nodes="true" v-model="form_filter.employee_type"
                                                    :options="filter_employee_types" :multiple="true" required />
                                            </div>
                                            <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">Phương thức làm việc</label>
                                            <div class="col-sm-4">
                                                <treeselect id="employment_type" :disabled="edit" placeholder="Tất cả"
                                                    :disable-branch-nodes="true" v-model="form_filter.employment_type"
                                                    :options="filter_employment_types" :multiple="true" required />
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">Ngày nhận việc</label>
                                            <div class="col-sm-4">
                                                <date-range-picker minDate="01/01/2020" maxDate="31/12/2030"
                                                    style="width: 100%;" v-model="form_filter.date_in_range">
                                                    <template v-slot:input="picker" style="min-width: 350px;">
                                                        {{ picker.startDate | formatDate }} - {{ picker.endDate |
                                                        formatDate
                                                        }}
                                                    </template>
                                                </date-range-picker>
                                            </div>
                                            <label for="scope"
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right">Trạng
                                                thái</label>
                                            <div class="col-sm-4">
                                                <treeselect id="scope" placeholder="Tất cả"
                                                    v-model="form_filter.is_working" :options="filter_status"
                                                    :multiple="true" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="scope"
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right">Giới
                                                tính</label>
                                            <div class="col-sm-2">
                                                <treeselect id="scope" placeholder="Tất cả" v-model="form_filter.gender"
                                                    :options="filter_gender" :multiple="true" />
                                            </div>



                                        </div>

                                        <div class="col-md-12" style="text-align: center">
                                            <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1"
                                                @click="fetchEmployees()">
                                                <i class="fa fa-search"></i> {{ $t("form.find") }}
                                            </button>
                                            <button type="reset" class="btn btn-secondary btn-sm mt-1 mb-1"
                                                @click="clearFilter()">
                                                <i class="fa fa-reset"></i> {{ $t("form.clear") }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                        :fields="fields" :items="employees" :filter="filter"
                                        :current-page="current_page" :per-page="per_page">
                                        <template #cell(company_id)="data">
                                            <span>{{ getCompanyName(data.value) }}</span>
                                        </template>
                                        <template #cell(department_id)="data">
                                            <span>{{ getDepartmentName(data.value) }}</span>
                                        </template>
                                        <template #cell(workshop_id)="data">
                                            <span>{{ getWorkshopName(data.value) }}</span>
                                        </template>
                                        <template #cell(party_id)="data">
                                            <span>{{ getPartyName(data.value) }}</span>
                                        </template>
                                        <template #cell(is_working)="data">
                                            <span class="badge bg-success" v-if="data.value">Đang làm việc</span>
                                            <span class="badge bg-warning" v-else>Đã nghỉ việc</span>
                                        </template>
                                        <template #cell(action)="data">
                                            <div class="margin">
                                                <button class="btn btn-xs" @click="editData(data.item)"><i
                                                        class="fas fa-edit text-green " title="Edit"></i></button>

                                                <button class="btn btn-xs" @click="deleteData(data.item.id)"><i
                                                        class="fas fa-trash text-red " title="Delete"></i></button>

                                            </div>
                                        </template>
                                    </b-table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="margin">
                                                <div class="btn-group">
                                                    <label class="col-form-label-sm text-nowrap mr-1">Hiển thị mỗi
                                                        trang: </label>
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

            <dialog-create-new-employee 
            :companies="companies"
            :departments="departments"
            :workshops="workshops"
            :parties="parties"
            :directtypes="directtypes"
            :employeetypes="employeetypes"
            :employmenttypes="employmenttypes"
            :employee="employee"
            :edit="edit"
            @onCreateEmployee="onCreateHandler"
            @onUpdateEmployee="onUpdateHandler">
            </dialog-create-new-employee>

            <dialog-import-employee 
            @onUploadData="onUploadHandler">

            </dialog-import-employee>
        </div>
    </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';
import DialogCreateNewEmployee from './dialogs/DialogCreateNewEmployee.vue';
import DialogImportEmployee from './dialogs/DialogImportEmployee.vue';
// import Pagination from "../shared/Pagination.vue";
export default {

    props: {
        title: ""
    },
    components: {
        // Pagination
        Treeselect,
        DateRangePicker,
        DialogCreateNewEmployee,
        DialogImportEmployee,
    },
    data() {
        return {
            companies: [],
            departments: [],
            workshops: [],
            parties: [],
            employees: [],
            employeetypes: [],
            employmenttypes: [],
            directtypes: [],
            employee: {
                id: "",
                company_id: "",
                department_id: "",
                workshop_id: "",
                party_id: "",
                employee_id: "",
                is_resign: false,
                date_in: "",
                date_out: "",
                gender: "",
                employee_id: "",
                name: "",
                index: "",
            },
            form_filter: {
                scopes: [],
                date_in_range: { startDate: null, endDate: null },
                gender: [],
                employee_type: [],
                employment_type: [],
                direct_type: [],
                is_resign: false
            },
            show_filter: false,
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "Tất cả" }],
            loading: false,
            edit: false,
            token: "",
            page_url_employees: "/api/category/employees",
            page_url_employee_types: "/api/category/employeetypes",
            page_url_employment_types: "/api/category/employmenttypes",
            page_url_direct_types: "/api/category/directtypes",
            page_url_company: "/api/category/companies",
            page_url_departments: "/api/category/departments",
            page_url_workshops: "/api/category/workshops",
            page_url_parties: "/api/category/parties",
            fields: [
                {
                    key: 'employee_id',
                    label: this.$t('form.code'),
                    stickyColumn: true,
                    class: "text-nowrap text-bold",
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'name',
                    label: 'Họ và tên',
                    stickyColumn: true,
                },
                {
                    key: 'company_id',
                    label: this.$t('form.company'),
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'department_id',
                    label: this.$t('form.department'),
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'workshop_id',
                    label: this.$t('form.workshop'),
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'party_id',
                    label: this.$t('form.party'),
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },

                {
                    key: 'date_in',
                    label: 'Ngày vào làm',
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'is_working',
                    label: 'Trạng thái',
                    sortable: true,
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    key: 'action',
                    label: 'Hành động',
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },

            ],
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchWorkshops();
        this.fetchParties();
        this.fetchEmployeeTypes();
        this.fetchEmploymentTypes();
        this.fetchDirectTypes();
        this.fetchScopes();
        this.fetchEmployees();
    },
    methods: {
        fetchEmployees() {
            this.loading = true;
            const params = new URLSearchParams({
                scopes: this.form_filter.scopes,
            });
            var page_url = this.page_url_employees + "?" + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.employees = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchCompanies() {
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchDepartments() {
            var page_url = this.page_url_departments;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchWorkshops() {
            var page_url = this.page_url_workshops;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.workshops = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchParties() {
            var page_url = this.page_url_parties;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.parties = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchScopes() {
            var page_url = this.page_url_parties + "?type=tree_fullscopes";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.scopes = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchEmployeeTypes() {
            var page_url = this.page_url_employee_types;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.employeetypes = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchEmploymentTypes() {
            var page_url = this.page_url_employment_types;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.employmenttypes = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchDirectTypes() {
            var page_url = this.page_url_direct_types;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.directtypes = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getCompanyName(company_id) {
            var obj = this.companies.find(x => x.id == company_id);
            return obj != null ? obj.name : '';
        },
        getDepartmentName(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            return obj != null ? obj.name : '';
        },
        getWorkshopName(workshop_id) {
            var obj = this.workshops.find(x => x.id == workshop_id);
            return obj != null ? obj.name : '';
        },
        getPartyName(party_id) {
            var obj = this.parties.find(x => x.id == party_id);
            return obj != null ? obj.name : '';
        },
        getUserName(user_id) {
            var obj = this.users.find(x => x.username == user_id);
            return obj != null ? obj.name : '';
        },
        showImportForm() {
            $("#DialogImportEmployee").modal("show");
        },
        showCreateNewEmployeeForm() {
            this.reset();
            $("#DialogCreateNewEmployee").modal("show");
        },
        showFilter() {
            this.show_filter = !this.show_filter;
        },
        editData(item) {
            let index = this.employees.findIndex(i => {
                return i.id == item.id;
            });

            this.edit = true;
            this.employee.id = item.id;
            this.employee.company_id = item.company_id;
            this.employee.department_id = item.department_id;
            this.employee.workshop_id = item.workshop_id;
            this.employee.party_id = item.party_id;
            this.employee.employee_id = item.employee_id;
            this.employee.is_resign = !item.is_working;
            this.employee.date_in = item.date_in;
            this.employee.date_out = item.date_out;
            this.employee.gender = item.gender;
            this.employee.name = item.name;
            this.employee.index = index;
            $("#DialogCreateNewEmployee").modal("show");
        },
        onCreateHandler(employee) {
            this.employees.push(employee);
        },
        onUpdateHandler(index, employee) {
            this.employees.splice(index, 1, employee);
        },
        onUploadHandler() {
            this.fetchEmployees();
        },
        deleteData(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_employees}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, 'Thành công');
                            this.fetchEmployees();
                        } else {
                            toastr.error(data.message, 'Thất bại');
                        }
                    });
            }
        },
        reset() {
            this.edit = false;
            this.employee = {
                id: "",
                company_id: "",
                department_id: "",
                workshop_id: "",
                party_id: "",
                employee_id: "",
                is_resign: false,
                date_in: "",
                date_out: "",
                employee_id: "",
                name: "",
                index: "",
            };
        },
    },
    computed: {
        rows() {
            return this.employees.length;
        },
        filter_employee_types() {
            let filters = [];
            this.employeetypes.forEach(element => {
                let filter = {
                    id: element.id,
                    label: element.name
                };
                filters.push({ ...filter });
            });
            return filters;
        },
        filter_direct_types() {
            let filters = [];
            this.directtypes.forEach(element => {
                let filter = {
                    id: element.id,
                    label: element.name
                };
                filters.push({ ...filter });
            });
            return filters;
        },
        filter_employment_types() {
            let filters = [];
            this.employmenttypes.forEach(element => {
                let filter = {
                    id: element.id,
                    label: element.name
                };
                filters.push({ ...filter });
            });
            return filters;
        },
        filter_gender() {
            return [
                { id: 1, label: 'Nam' },
                { id: 2, label: 'Nữ' },
            ]
        },
        filter_status() {
            return [
                { id: 1, label: 'Còn làm việc' },
                { id: 0, label: 'Đã nghỉ việc' },
            ]
        },
        filter_department_form() {
            let company_id = this.employee.company_id;
            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                    return true;
                }
            });
        },
        filter_workshop_form() {
            let department_id = this.employee.department_id;
            return this.workshops.filter(function (item) {
                if (item.department_id == department_id) {
                    return true;
                }
            });
        },
        filter_party_form() {
            let workshop_id = this.employee.workshop_id;
            return this.parties.filter(function (item) {
                if (item.workshop_id == workshop_id) {
                    return true;
                }
            });
        },
    },
};

</script>
<style scoped>
/* fix khoảng cách bên dưới table so với phân trang */
.table {
    margin-bottom: 0px;
}
</style>