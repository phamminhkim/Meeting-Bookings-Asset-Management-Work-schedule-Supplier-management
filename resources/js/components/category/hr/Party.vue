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

                                    <button class="btn btn-info btn-sm" @click="showParty()"> <i class="fa fa-plus"></i>
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
                                        :fields="fields" :items="parties" :filter="filter" :current-page="current_page"
                                        :per-page="per_page">
                                        <template #cell(company_id)="data">
                                            <span>{{ getCompanyName(data.value) }}</span>
                                        </template>
                                        <template #cell(department_id)="data">
                                            <span>{{ getDepartmentName(data.value) }}</span>
                                        </template>
                                        <template #cell(workshop_id)="data">
                                            <span>{{ getWorkshopName(data.value) }}</span>
                                        </template>
                                        <template #cell(active)="data">
                                            <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                            <span class="badge bg-warning" v-if="data.value == 0">Ngưng hoạt động</span>
                                        </template>
                                        <template #cell(action)="data">
                                            <div class="margin">
                                                <button class="btn btn-xs" @click="editParty(data.item)"><i
                                                        class="fas fa-edit text-green " title="Edit"></i></button>

                                                <button class="btn btn-xs" @click="deleteParty(data.item.id)"><i
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
            <div class="modal fade" id="AddParty" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="addParty" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span v-if="!edit">{{ $t('form.add') }}</span>
                                    <span v-if="edit">{{ $t('form.update') }}</span>


                                    {{ $t('form.party') }}
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="company_id">{{ $t('form.company') }}</label>
                                    <select name="company_id" class="form-control"
                                        v-bind:class="hasError('company_id') ? 'is-invalid' : ''"
                                        v-model="party.company_id" @change="clearError($event)">
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
                                    <select name="department_id" class="form-control"
                                        v-bind:class="hasError('department_id') ? 'is-invalid' : ''"
                                        v-model="party.department_id" @change="clearError($event)">
                                        <option v-for="department in filteredDepartments" :key="department.id"
                                            v-bind:value="department.id">
                                            {{ department.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('department_id') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="workshop_id">{{ $t('form.workshop') }}</label>
                                    <select name="workshop_id" class="form-control"
                                        v-bind:class="hasError('workshop_id') ? 'is-invalid' : ''"
                                        v-model="party.workshop_id" @change="clearError($event)">
                                        <option v-for="workshop in filteredWorkshops" :key="workshop.id"
                                            v-bind:value="workshop.id">
                                            {{ workshop.name }}
                                        </option>
                                    </select>
                                    <span v-if="hasError('workshop_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('workshop_id') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="code">{{ $t('form.code') }}</label>
                                    <input v-model="party.code" type="text" class="form-control"
                                        v-bind:class="hasError('code') ? 'is-invalid' : ''" id="code" name="code"
                                        placeholder="Party code" :rules="['rules.required,rules.min']" required />

                                    <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('code') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ $t('form.name') }}</label>
                                    <input v-model="party.name" type="text" class="form-control"
                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                                        placeholder="Party name" required />
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
            companies: [],
            departments: [],
            workshops: [],
            parties: [],
            party: {
                id: "",
                code: "",
                name: "",
                company_id: "",
                department_id: "",
                workshop_id: "",
                index: ""
            },
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "Tất cả" }],
            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_company: "/api/category/companies",
            page_url_department: "/api/category/departments",
            page_url_workshop: "/api/category/workshops",
            page_url_party: "/api/category/parties",
            fields: [
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
                    key: 'workshop_id',
                    label: this.$t('form.workshop'),
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
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchCompany();
        this.fetchDepartment();
        this.fetchWorkshop();
        this.fetchParty();
    },
    methods: {
        fetchCompany() {
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
        fetchDepartment() {
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
        fetchWorkshop() {
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
        fetchParty() {
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
        showParty() {
            this.reset();
            $("#AddParty").modal("show");
        },
        addParty() {
            var page_url = this.page_url_party;// "/api/category/departments";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.party),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.data.errors) {
                            this.reset();
                            this.parties.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#AddParty").modal("hide");

                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.party.id, {
                    method: "PUT",
                    body: JSON.stringify(this.party),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.parties, this.party.index, { ...this.party });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#AddParty").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        editParty(party) {
            let index = this.parties.findIndex(i => {
                return i.id == party.id;
            });

            this.edit = true;
            this.party.id = party.id;
            this.party.company_id = party.company_id;
            this.party.department_id = party.department_id;
            this.party.workshop_id = party.workshop_id;
            this.party.code = party.code;
            this.party.name = party.name;
            this.party.index = index;
            $("#AddParty").modal("show");
        },
        deleteParty(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_party}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchParty();
                    });
            }
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
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        getError(fieldName) {
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.party) {
                this.party[field] = null;
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
            return this.workshops.length;
        },
        filteredDepartments() {
            let company_id = this.party.company_id;

            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                    return true;
                }
            });
        },
        filteredWorkshops() {
            let department_id = this.party.department_id;

            return this.workshops.filter(function (item) {
                if (item.department_id == department_id) {
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