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
                                <h3 class="card-title">{{ $t('form.list') }}</h3>
                                <div class="card-tools">

                                    <button class="btn btn-info btn-sm" @click="showDataForm()"> <i
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
                                        :fields="fields" :items="directtypes" :filter="filter"
                                        :current-page="current_page" :per-page="per_page">
                                        <template #cell(company_id)="data">
                                            <span>{{ getCompanyName(data.value) }}</span>
                                        </template>
                                        <template #cell(department_id)="data">
                                            <span>{{ getDepartmentName(data.value) }}</span>
                                        </template>
                                        <template #cell(active)="data">
                                            <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                            <span class="badge bg-warning" v-if="data.value == 0">Ngưng hoạt động</span>
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

            <!-- Modal -->
            <div class="modal fade" id="dataFormDialog" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="submitDataForm" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span v-if="!edit">{{ $t('form.add') }}</span>
                                    <span v-if="edit">{{ $t('form.update') }}</span>

                                    {{ $t('form.directtype') }}
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group" v-if="directtype.id">
                                    <label for="id">{{ $t('form.id') }}</label>
                                    <input v-model="directtype.id" type="text" class="form-control"
                                        v-bind:class="hasError('id') ? 'is-invalid' : ''" id="id" name="id"
                                        readonly />

                                    <span v-if="hasError('id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="code">{{ $t('form.code') }}</label>
                                    <input v-model="directtype.code" type="text" class="form-control"
                                        v-bind:class="hasError('code') ? 'is-invalid' : ''" id="code" name="code"
                                        placeholder="Mã" :rules="['rules.required,rules.min']" required />

                                    <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('code') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ $t('form.name') }}</label>
                                    <input v-model="directtype.name" type="text" class="form-control"
                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                                        placeholder="Tên" required />
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
            directtypes: [],
            directtype: {
                id: "",
                code: "",
                name: "",
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
            
            page_url_directtypes: "/api/category/directtypes",
            fields: [
                {
                    key: 'id',
                    label: 'ID',
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
        this.fetchDirectType();

    },
    methods: {
        fetchDirectType() {
            var page_url = this.page_url_directtypes;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.directtypes = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        showDataForm() {
            this.reset();
            $("#dataFormDialog").modal("show");
        },
        submitDataForm() {
            var page_url = this.page_url_directtypes;// "/api/category/departments";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.directtype),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.data.errors) {
                            this.reset();
                            this.directtypes.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#dataFormDialog").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.directtype.id, {
                    method: "PUT",
                    body: JSON.stringify(this.directtype),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.directtypes, this.directtype.index, { ...this.directtype });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#dataFormDialog").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        editData(item) {
            let index = this.directtypes.findIndex(i => {
                return i.id == item.id;
            });

            this.edit = true;
            this.directtype.id = item.id;
            this.directtype.code = item.code;
            this.directtype.name = item.name;
            this.directtype.index = index;
            $("#dataFormDialog").modal("show");
        },
        deleteData(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_directtypes}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchDirectType();
                    });
            }
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
            for (let field in this.directtype) {
                this.directtype[field] = null;
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
            return this.directtypes.length;
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