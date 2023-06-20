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
                                        :fields="fields" :items="jobtitles" :filter="filter"
                                        :current-page="current_page" :per-page="per_page">
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

                                    {{ $t('form.jobtitle') }}
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group" v-if="jobtitle.id">
                                    <label for="id">{{ $t('form.id') }}</label>
                                    <input v-model="jobtitle.id" type="text" class="form-control"
                                        v-bind:class="hasError('id') ? 'is-invalid' : ''" id="id" name="id"
                                        readonly />

                                    <span v-if="hasError('id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="code">{{ $t('form.code') }}</label>
                                    <input v-model="jobtitle.code" type="text" class="form-control"
                                        v-bind:class="hasError('code') ? 'is-invalid' : ''" id="code" name="code"
                                        placeholder="Mã" :rules="['rules.required,rules.min']" required />

                                    <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('code') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="eng_name">{{ $t('form.eng_name') }}</label>
                                    <input v-model="jobtitle.eng_name" type="text" class="form-control"
                                        v-bind:class="hasError('eng_name') ? 'is-invalid' : ''" id="eng_name" name="eng_name"
                                        placeholder="Tên tiếng Anh" required />
                                    <span v-if="hasError('eng_name')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('eng_name') }}</strong>
                                    </span>
                                </div>
                                   <div class="form-group">
                                    <label for="vie_name">{{ $t('form.vie_name') }}</label>
                                    <input v-model="jobtitle.vie_name" type="text" class="form-control"
                                        v-bind:class="hasError('vie_name') ? 'is-invalid' : ''" id="vie_name" name="vie_name"
                                        placeholder="Tên tiếng Việt" required />
                                    <span v-if="hasError('vie_name')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('vie_name') }}</strong>
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
            jobtitles: [],
            jobtitle: {
                id: "",
                code: "",
                eng_name: "",
                vie_name: "",
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
            
            page_url_jobtitles: "/api/category/jobtitles",
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
                    key: 'eng_name',
                    label: this.$t('form.eng_name'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'vie_name',
                    label: this.$t('form.vie_name'),
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
        this.fetchJobTitle();

    },
    methods: {
        fetchJobTitle() {
            var page_url = this.page_url_jobtitles;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.jobtitles = res.data;
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
            var page_url = this.page_url_jobtitles;// "/api/category/departments";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.jobtitle),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.data.errors) {
                            this.reset();
                            this.jobtitles.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#dataFormDialog").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.jobtitle.id, {
                    method: "PUT",
                    body: JSON.stringify(this.jobtitle),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.jobtitles, this.jobtitle.index, { ...this.jobtitle });
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
            let index = this.jobtitles.findIndex(i => {
                return i.id == item.id;
            });

            this.edit = true;
            this.jobtitle.id = item.id;
            this.jobtitle.code = item.code;
            this.jobtitle.eng_name = item.eng_name;
            this.jobtitle.vie_name = item.vie_name;
            this.jobtitle.index = index;
            $("#dataFormDialog").modal("show");
        },
        deleteData(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_jobtitles}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchJobTitle();
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
            for (let field in this.jobtitle) {
                this.jobtitle[field] = null;
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
            return this.jobtitles.length;
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