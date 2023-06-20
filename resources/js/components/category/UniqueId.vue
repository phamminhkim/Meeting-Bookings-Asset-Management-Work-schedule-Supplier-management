<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> {{ $t(title)}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                            <button class="btn btn-info btn-sm" @click="showCreateForm()"> <i class="fa fa-plus"></i>
                                {{ $t('form.create')}}</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="background-color:#F4F6F9">
                            <div class="col-md-9">

                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-sm mt-1 mb-1">
                                    <input type="search" class="form-control form-control-navbar" v-model="filter"
                                        :placeholder="$t('form.search')" aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <b-table striped hover bordered responsive head-variant="light" small :fields="fields"
                                :items="uniqueIds" :filter="filter" :current-page="current_page" :per-page="per_page">
                                <template #cell(auto)="data">
                                    <span class="badge bg-success" v-if="data.value == 1">Tự động</span>
                                    <span class="badge bg-warning" v-if="data.value == 0">Thủ công</span>
                                </template>
                                <template #cell(company_id)="data">
                                    {{getCompanyName(data.item.company_id)}}


                                </template>
                                <template #cell(action)="data">
                                    <div class="margin">
                                        <button class="btn btn-xs" @click="editItem(data.item)"><i
                                                class="fas fa-edit text-green " title="Edit"></i></button>

                                        <button class="btn btn-xs" @click="deleteItem(data.item.id)"><i
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
                                            <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page"
                                                size="sm" class="ml-1"></b-pagination>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal dialog -->
        <div class="modal  fade" id="form_edit" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="save_and_update" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span v-if="!edit">{{$t('form.add')}}</span>
                                Unique <Input:datetime></Input:datetime>
                            </h4>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="company">{{$t('form.company')}}</label>
                                <select class="form-control" required v-model="uniqueId.company_id" name="company"
                                    id="company">

                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{company.name}}</option>
                                </select>


                                <span v-if="hasError('company')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('company')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="module">{{$t('form.module')}}</label>
                                <select class="form-control" required v-model="uniqueId.module" name="module"
                                    id="module">
                                    <option v-for="mod in modules" :key="mod.id" :value="mod.id">{{mod.name}}</option>
                                </select>


                                <span v-if="hasError('module')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('module')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="document_type">{{$t('form.document_type')}}</label>
                                <select class="form-control" required v-model="uniqueId.document_type_code"
                                    name="document_type" id="document_type">
                                    <option v-for="document_type in document_types" :key="document_type.code"
                                        :value="document_type.code">{{document_type.code}} - {{document_type.name}}
                                    </option>
                                </select>


                                <span v-if="hasError('document_type')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('document_type')}}</strong>
                                </span>
                            </div>


                            <div class="form-group">
                                <label for="" class="">Kí hiệu</label>

                                <input type="text" class="form-control " v-model="uniqueId.letter"
                                    v-bind:class="hasError('letter')?'is-invalid':''" required>
                                <span v-if="hasError('letter')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('letter')}}</strong>
                                </span>

                            </div>
                            <div class="form-group">
                                <label for="">Serial</label>
                                <input type="text" class="form-control " v-model="uniqueId.serial" minlength="6"
                                    maxlength="6" :readonly="edit" v-bind:class="hasError('serial')?'is-invalid':''" required>
                                <span v-if="hasError('serial')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('serial')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="chkPrivate" v-model="uniqueId.auto"
                                        v-bind:value="uniqueId.auto" v-bind:class="hasError('auto')?'is-invalid':''"
                                        class="form-check-input">
                                    <label class="form-checkbox-label" for="chkPrivate">Auto serial</label>
                                </div>

                                <span v-if="hasError('auto')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('auto')}}</strong>
                                </span>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-lg-between">
                            <button type="button" data-dismiss="modal"
                                class="btn btn-default">{{$t('form.back')}}</button>
                            <button type="submit" class="btn  btn-primary">{{$t('form.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from '../shared/Pagination.vue';
export default {
    components: { Pagination },
    props: {
        title: ""
    },
    data() {
        return {
            uniqueIds: [],
            modules: [],
            companies: [],
            document_types: [],
            uniqueId: {
                document_type_code: "",
                module: "",
                company_id: "",
                serial: "",
                letter: "",
                year: "",
                auto: 0,
            },
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [2, 10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_unique_id: "/api/category/uniqueids",
            page_url_module: "/api/category/modules",
            page_url_company: "/api/category/companies",
            page_url_document_type: "/api/category/documenttypes",
            fields: [
                {
                    key: 'company_id',
                    label: this.$t('form.company'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'module',
                    label: this.$t('form.module'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'document_type_code',
                    label: 'Loại chứng từ',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'letter',
                    label: this.$t('form.code'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'year',
                    label: this.$t('form.year'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'serial',
                    label: 'Serial',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'auto',
                    label: this.$t('form.auto'),
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
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetUniqueIds();
        this.fetModule();
        this.fetCompanies();
        this.fetchDocumentType();
    },
    methods: {
        fetCompanies() {
            var page_url = this.page_url_company;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
        fetModule() {
            var page_url = this.page_url_module;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    this.modules = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchDocumentType() {
            var page_url = this.page_url_document_type;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    this.document_types = res.data;
                })
                .catch(err => console.log(err));
        },
        fetUniqueIds() {
            this.loading = true;
            var page_url = this.page_url_unique_id;

            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.uniqueIds = res.data;

                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        save_and_update() {
            var page_url = this.page_url_unique_id;// "/api/category/teams";
            fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.uniqueId),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.data.errors) {
                        if (data.data.created) {
                            this.uniqueIds.push(data.data);
                            toastr.success("Lưu thành công", 'Thông báo');
                        }
                        else {
                            this.$set(this.uniqueIds, this.uniqueId.index, { ...this.uniqueId });
                            toastr.success("Cập nhật thành công", 'Thông báo')
                        }
                        $("#form_edit").modal("hide");
                        this.reset();
                    } else {
                        // this.reset();
                        this.errors = data.data.errors;
                        //  toastr.error(this.errors,'Lưu bị lỗi');

                    }
                })
                .catch(err => console.log(err));
        },
        editItem(item) {

            let index = this.uniqueIds.findIndex(i => {

                return i.id == item.id;
            });
            // var team={..._obj};
            this.edit = true;
            this.uniqueId.document_type_code = item.document_type_code;
            this.uniqueId.module = item.module;
            this.uniqueId.company_id = item.company_id;
            this.uniqueId.serial = item.serial;
            this.uniqueId.letter = item.letter;
            this.uniqueId.year = item.year;
            this.uniqueId.auto = item.auto;
            this.uniqueId.index = index;

            $("#form_edit").modal("show");
        },
        deleteItem(id) {

            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_unique_id}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.data.success == '1') {
                            toastr.success('Xoá thành công', 'Thông báo');
                            this.fetUniqueIds();
                        } else {
                            toastr.warning('Xoá lỗi', 'Thông báo');
                        }

                    });
            }

        },
        showCreateForm() {
            this.edit = false;
            this.uniqueId = {
                document_type_code: "",
                module: "",
                company_id: "",
                serial: "000000",
                letter: "",
                year: "2022",
                auto: 0,
            },
                $('#form_edit').modal('show');
        },
        getCompanyName(companyID) {
            if (this.companies != null) {
                for (var company of this.companies) {
                    if (company.id == companyID) {
                        return company.name;
                    }
                }
            }
            return "";
        },
        getDocumentType(document_type_id) {
            if (this.document_types != null) {
                for (var document_type of this.document_types) {
                    if (document_type.id == document_type_id) {
                        return document_type.name;
                    }
                }
            }
            return "";
        },
        hasError(fieldname) {
            return (fieldname in this.errors);
        },
        getError(fieldname) {
            return this.errors[fieldname][0];
        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.uniqueId) {
                this.uniqueId[field] = null;
            }
        },
        clearAllError() {
            this.errors = {};
        },
    },

    computed: {
        rows() {
            return this.uniqueIds.length;
        },
        placeholder() {
            return this.$t('form.search');
        }
    }

}
</script>

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
