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
                            <button class="btn btn-info btn-sm" @click="showUser()"> <i class="fa fa-plus"></i>
                                {{ $t('form.create') }}</button>
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
                            <div class="card-body">
                                <div class="row mt-0">

                                    <div class="col-md-9">
                                        <div class="form-group row">

                                            <div class="btn-group ">
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    @click="showSearch()">
                                                    <span v-if="!show_search">{{ $t('form.show_search') }}</span>
                                                    <span v-if="show_search">{{ $t('form.hide_search') }}</span>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-xs "
                                                    @click="showSearch()">
                                                    <i v-if="show_search" class="fas fa-angle-up"></i>
                                                    <i v-else class="fas fa-angle-down"></i>
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="row" v-if="show_search"
                                    style="border: 1px solid #E5E5E5;border-radius:5px;">
                                    <div class="col-md-12 ">
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">{{ $t("form.company") }}</label
                                                >
                                                <div class="col-sm-4">
                                                    <select
                                                    name=""
                                                    id=""
                                                    v-model="form_filter.company_id"
                                                    class="form-control form-control-sm mt-1"
                                                    >
                                                    <option
                                                        v-for="company in companies"
                                                        :key="company.id"
                                                        v-bind:value="company.id"
                                                    >
                                                        {{ company.name }}
                                                    </option>
                                                    <option value="">All</option>
                                                    </select>
                                                </div>
                                                <label
                                                    class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                    for="id_bophan"
                                                    >{{ $t("form.department") }}</label
                                                >
                                                <div class="col-sm-4">
                                                    <select
                                                    name=""
                                                    id="id_bophan"
                                                    v-model="form_filter.department_id"
                                                    class="form-control form-control-sm mt-1"
                                                    >
                                                    <option
                                                        v-for="department in department_filter"
                                                        :key="department.id"
                                                        v-bind:value="department.id"
                                                    >
                                                        {{ department.name }}
                                                    </option>
                                                    <option value="">All</option>
                                                    </select>
                                                 </div>

                                            </div>
                                           <div class="form-group row">
                                              <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">{{ $t("form.username") }}</label
                                                >
                                                <div class="col-sm-4">
                                                    <input type="text"  v-model="form_filter.username" class="form-control form-control-sm mt-1">                                                
                                                </div>
                                                <label
                                                    class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                    for=""
                                                    >{{ $t("form.name") }}</label
                                                >
                                                <div class="col-sm-4">
                                                      <input type="text"  v-model="form_filter.name" class="form-control form-control-sm mt-1"> 
                                                 </div>

                                            </div>
                                            <div class="form-group row">
                                                    
                                                
                                        <div class="form-group row">
                                                <label 
                                                    class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right mt-1"
                                                    for=""
                                                    >{{ $t("form.status") }}</label
                                                >
                                                <div  class="col-sm-4 mt-1">
                                                    <treeselect
                                                    placeholder="All"
                                                    
                                                    :disable-branch-nodes="false"
                                                    v-model="form_filter.active"
                                                    :options="status_option"
                                                    />
                                                </div>
                                                 <label
                                                class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                                                for="">{{ $t("form.email") }}</label
                                                >
                                                <div class="col-sm-4">
                                                    <input type="text"  v-model="form_filter.email" class="form-control form-control-sm mt-1">                                                
                                                </div>
                                            </div>
                                    <div class="col-md-12" style="text-align: center">
                                    <button
                                        type="submit"
                                        class="btn btn-warning btn-sm mt-1 mb-1"
                                        @click="filter_data()"
                                    >
                                        <i class="fa fa-search"></i> {{ $t("form.find") }}
                                    </button>
                                    <button
                                        type="reset"
                                        class="btn btn-secondary btn-sm mt-1 mb-1"
                                        @click="clearFilter()"
                                    >
                                        <i class="fa fa-reset"></i> {{ $t("form.clear") }}
                                    </button>
                               
                                    </div>
                                          
                                       </div>
                                      
                                    </div>
 
                                </div>
                                <div class="row mt-1 " style="background-color:#F4F6F9">

                                    <div class="col-md-9">

                                        <div class="form-group row">
                                            <button class="btn btn-success btn-sm"
                                                @click=" $bvModal.show('modalImportUser')"> <i class="fa fa-upload"></i>
                                                Import users</button>

                                            <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editRequest()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                                <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->

                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="row mt-1">
                                            <div class="input-group input-group-sm  col-12">

                                                <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                                <input class="form-control form-control-navbar mb-1" id="search"
                                                    type="search" v-model="filter" :placeholder="$t('form.search')"
                                                    aria-label="Search">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-default btn-flat mb-1"><i
                                                            class="fas fa-search"></i></button>
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
                                <div class="row">
                                    <b-table striped hover responsive :bordered="true" head-variant="light"
                                        :sticky-header="false" small :items="users" :current-page="current_page"
                                        :per-page="per_page" :filter="filter" :fields="fields" selectable
                                        ref="selectableTable">
                                        <template #head(selected)>
                                            <!-- {{selected}} -->
                                            <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll"
                                                @change="select"></b-form-checkbox>
                                        </template>

                                        <template v-slot:cell(selected)="data">
                                            <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected">
                                            </b-form-checkbox>
                                        </template>
                                        <template #cell(company_id)="data">
                                            {{ getCompanyName(data.item.company_id) }}
                                        </template>
                                        <template #cell(department_id)="data">
                                            {{ getDepartmentName(data.item.department_id) }}
                                        </template>
                                        <template #cell(workshop_id)="data">
                                            {{ getWorkshopName(data.item.workshop_id) }}
                                        </template>
                                        <template #cell(party_id)="data">
                                            {{ getPartyName(data.item.party_id) }}
                                        </template>
                                        <template #cell(gender)="data">
                                            <span class="badge bg-primary" v-if="data.item.gender == 1">{{
                                            $t('form.male')
                                            }}</span>
                                            <span class="badge bg-primary" v-if="data.item.gender == 0">{{
                                            $t('form.female')
                                            }}</span>
                                        </template>
                                        <template #cell(active)="data">
                                            <span class="badge bg-success" v-if="data.item.active == 1">{{
                                            $t('form.active')
                                            }}</span>
                                            <span class="badge bg-warning" v-if="data.item.active == 0">{{
                                            $t('form.inactive')
                                            }}</span>
                                        </template>
                                        <template #cell(action)="data">
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <button @click="viewGroups(data.item)" class="btn btn-xs  mr-3"
                                                    title="View">
                                                    <i class="fa fa-eye  text-blue bigger-120"></i>
                                                </button>

                                                <button @click="editUser(data.item)" class="btn btn-xs  mr-3"
                                                    title="Edit">
                                                    <i class="fa fa-edit  text-green bigger-120"></i>
                                                </button>

                                                <button @click="deleteUser(data.item.id)" class="btn btn-xs "
                                                    title="Delete">
                                                    <i class="ace-icon text-red fa fa-trash bigger-120"></i>
                                                </button>


                                            </div>
                                        </template>
                                    </b-table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-md-4" style="text-align:left"
                                                    for="">Per page:</label>
                                                <div class="col-md-3">
                                                    <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                                    </b-form-select>
                                                </div>
                                                <label class="col-form-label-sm col-md-1" style="text-align:left"
                                                    for=""></label>
                                                <div class="col-md-3">
                                                    <b-pagination v-model="current_page" :total-rows="rows"
                                                        :per-page="per_page" align="fill" size="sm" class="my-0">
                                                    </b-pagination>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

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
            <div class="modal fade" id="AddUser" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="AddUser" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span v-if="!edit">{{ $t('form.add') }} </span>
                                    <span v-if="edit">{{ $t('form.update') }} </span>
                                    {{ $t('form.user') }}
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click.prevent="clearAllError()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="company_id">{{ $t('form.company') }}</label>
                                    <small style="color:red">(*)</small>
                                    <select name="company_id" class="form-control"
                                        v-bind:class="hasError('company_id') ? 'is-invalid' : ''"
                                        v-model="user.company_id" @change="clearError($event)" required>
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
                                    <label for="department_id">{{ $t('form.department') }}</label>
                                    <small style="color:red">(*)</small>
                                    <select name="department_id" class="form-control"
                                        v-bind:class="hasError('department_id') ? 'is-invalid' : ''"
                                        v-model="user.department_id" @change="clearError($event)" required>
                                        <option v-for="department in filteredDepartments(user.company_id)"
                                            :key="department.id" v-bind:value="department.id">
                                            {{ department.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('department_id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="workshop_id">{{ $t('form.workshop') }}</label>
                                    <small style="color:red">(*)</small>
                                    <select name="workshop_id" class="form-control"
                                        v-bind:class="hasError('workshop_id') ? 'is-invalid' : ''"
                                        v-model="user.workshop_id" @change="clearError($event)">
                                        <option v-for="workshop in filtered_workshops(user.department_id)"
                                            :key="workshop.id" v-bind:value="workshop.id">
                                            {{ workshop.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('workshop_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('workshop_id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="party_id">{{ $t('form.party') }}</label>
                                    <small style="color:red">(*)</small>
                                    <select name="party_id" class="form-control"
                                        v-bind:class="hasError('party_id') ? 'is-invalid' : ''"
                                        v-model="user.party_id" @change="clearError($event)">
                                        <option v-for="party in filtered_parties(user.workshop_id)"
                                            :key="party.id" v-bind:value="party.id">
                                            {{ party.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('party_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('party_id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="username">{{ $t('form.username') }}</label>
                                    <small style="color:red">(*)</small>
                                    <input v-model="user.username" type="text" class="form-control"
                                        v-bind:class="hasError('username') ? 'is-invalid' : ''" id="username"
                                        name="username" placeholder="" />
                                    <span v-if="hasError('username')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('username') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ $t('form.name') }}</label>
                                    <small style="color:red">(*)</small>
                                    <input v-model="user.name" type="text" class="form-control"
                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                                        placeholder="" required />

                                    <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('name') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ $t('form.email') }}</label>
                                    <input v-model="user.email" type="text" class="form-control"
                                        v-bind:class="hasError('email') ? 'is-invalid' : ''" id="email" name="email"
                                        placeholder="" />
                                    <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('email') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="gender">{{ $t('form.gender') }}</label>
                                    <select v-model="user.gender" class="form-control" name="gender" id="gender"
                                        autocomplete="gender" autofocus>
                                        <option value="">---</option>
                                        <option value="0">Nữ</option>
                                        <option value="1">Nam</option>
                                    </select>
                                    <span v-if="hasError('gender')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('gender') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="manager">Người quản lí</label>
                                    <treeselect v-model="user.manager" :disable-branch-nodes="true"
                                        :options="tree_users"></treeselect>
                                    <span v-if="hasError('manager')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('manager') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="avatar">Avatar</label>
                                    <input v-model="user.avatar" type="text" class="form-control"
                                        v-bind:class="hasError('avatar') ? 'is-invalid' : ''" id="avatar" name="avatar"
                                        placeholder="" />
                                    <span v-if="hasError('avatar')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('avatar') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="active">{{ $t('form.active') }}</label>
                                    <select v-model="user.active" class="form-control" name="active" id="active"
                                        autocomplete="active" autofocus>
                                        <option value="0">Không hoạt động</option>
                                        <option value="1">Hoạt động</option>
                                    </select>
                                    <span v-if="hasError('active')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('active') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    @click.prevent="clearAllError()">
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
        <table-import-user :datas="users"></table-import-user>
    </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import TableImportUser from '../excel/TableImportUser.vue';

// import Pagination from "../shared/Pagination.vue";

export default {
    props: {
        title: ""
    },
    components: {
        Treeselect,
        TableImportUser
    },
    data() {
        return {
            users: [],
            companies: [],
            departments: [],
            pagesNumber: [],
            user: {
                id: "",
                name: "",
                username: "",
                company_id: "",
                department_id: "",
                workshop_id: "",
                party_id: "",
                email: "",
                active: "",
                avatar: "",
                manager: "",
                gender: ""
            },
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors: {},
            tree_users: [],
            loading: false,
            edit: false,
            token: "",
            page_url_user: "/api/category/users",
            page_url_company: "/api/category/companies",
            page_url_department: "/api/category/departments",
            page_url_workshop: "/api/category/workshops",
            page_url_party: "/api/category/parties",
            show_search: false,
            form_filter: {
                company_id: "",
                department_id: "",
                name:"",
                username:"",
                email:"",
                active: "",
            },
             status_option: [
                { id: "1", label: "Đang hoạt dộng"},
                { id: "0", label: "Ngưng hoạt động" },
                 {id: '',label: 'All'},
            ],
            fields: [
                {
                    key: 'selected',
                    label: 'All',
                    stickyColumn: true
                },
                {
                    key: 'username',
                    label: this.$t('form.username'),
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
                    key: 'company_id',
                    label: this.$t('form.company'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'department_id',
                    label: this.$t('form.department'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'email',
                    label: this.$t('form.email'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'gender',
                    label: this.$t('form.gender'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'active',
                    label: this.$t('form.active'),
                    sortable: true,
                    stickyColumn: true
                },
                {
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
        this.fetCompany();
        this.fetDepartment();
        this.fetWorkshop();
        this.fetParty();
        this.fetchUser();
        // this.getUserTree();

    },

    methods: {
        getUserTree() {
            var page_url = "/api/user/all" + "?type=tree_combobox";
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.tree_users = data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        fetCompany() {
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
        getCompanyName(companyID) {
            if (this.companies != null) {
                for (const company of this.companies) {
                    if (company.id == companyID) {
                        return company.name;
                    }
                }
            }
            return "";
        },
        fetDepartment() {
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetWorkshop() {
            var page_url = this.page_url_workshop;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.workshops = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetParty() {
            var page_url = this.page_url_party;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.parties = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getDepartmentName(id) {
            var department = this.departments.find((x) => x.id == id);
            return department == null ? '' : department.name;
        },
        getWorkshopName(id) {
            var workshop = this.workshops.find((x) => x.id == id);
            return workshop == null ? '' : workshop.name;
        },
        getPartyName(id) {
            var party = this.parties.find((x) => x.id == id);
            return party == null ? '' : party.name;
        },
        fetchUser() {
            //$("#tbbody_id").html('');
            
            this.loading = true;
            this.users = Array();
            const params = new URLSearchParams({
                company_id: this.form_filter.company_id,
                department_id: this.form_filter.department_id,
                name: this.form_filter.name,
                username: this.form_filter.username,
                email: this.form_filter.email,
                active: this.form_filter.active,


            });
            var page_url = this.page_url_user + '?' + params.toString();

            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    this.users = res.data;

                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        showImportUserDialog() {
            $bvModal.show('modalImportUser');
            $("#modalImportUser").modal("show");
        },
        showUser() {
            this.reset();
            this.user.active = 1;
            $("#AddUser").modal("show");
        },
        AddUser() {
            var page_url = this.page_url_user;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.user),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.users.splice(0, 0, data.data);
                            this.showMessage(this.$t('form.message'), this.$t('form.save_success'));
                            $("#AddUser").modal("hide");

                        } else {

                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch('/api/user' + "/" + this.user.id, {
                    method: "PUT",
                    body: JSON.stringify(this.user),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {

                            this.$set(this.users, this.user.index, { ...this.user });
                            this.showMessage(this.$t('form.message'), this.$t('form.update_success'));
                            $("#AddUser").modal("hide");
                            this.reset();
                        } else {

                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        editUser(user) {

            let index = this.users.findIndex(i => {
                console.log("id" + i.id);
                return i.id == user.id;
            });

            this.edit = true;
            this.user.id = user.id;
            this.user.username = user.username;
            this.user.name = user.name;
            this.user.company_id = user.company_id;
            this.user.department_id = user.department_id;
            this.user.sloc_id = user.sloc_id;
            this.user.email = user.email;
            this.user.active = user.active;
            this.user.avatar = user.avatar;
            this.user.manager = user.manager ?? null;
            this.user.gender = user.gender;
            this.user.index = index;

            $("#AddUser").modal("show");
        },
        deleteUser(id) {

            if (confirm(this.$t('form.confirm_delete') + '?')) {
                fetch(`${this.page_url_user}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage(this.$t('form.message'), this.$t('form.delete_success'));
                        this.fetchUser();
                    });
            }

        },
        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.users) {
                    this.selected.push(this.users[i].id);
                }
            }
        },
        filter_data() {
             
            this.fetchUser();
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;
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
            for (let field in this.user) {
                this.user[field] = null;
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
        filteredDepartments(companyID) {
            if (this.departments != null && this.user != null) {
                return this.departments.filter(function (e) {
                    return e.company_id == companyID;
                });
            }
            return [];
        },
        filtered_workshops(department_id) {
            if (this.workshops != null) {
                return this.workshops.filter(function (e) {
                    return e.department_id == department_id;
                });
            }
            return [];
        },
        filtered_parties(workshop_id) {
            if (this.parties != null) {
                return this.parties.filter(function (e) {
                    return e.workshop_id == workshop_id;
                });
            }
            return [];
        }
    },
    computed: {
            department_filter() {
            let company_id = this.form_filter.company_id;
            // this.contract.department_id = "";
            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                return true;
                }
            });
            },
        rows() {
            return this.users.length;
        },
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
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

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
