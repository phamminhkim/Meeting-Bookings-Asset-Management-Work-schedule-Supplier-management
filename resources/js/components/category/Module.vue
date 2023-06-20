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
                                :items="modules" :filter="filter" :current-page="current_page"
                                :per-page="per_page">
                                <template #cell(active)="data">
                                    <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                    <span class="badge bg-warning" v-if="data.value==0">Ngưng hoạt động</span>
                                </template>
                                <template #cell(document_group)="data">

                                    <span v-if="data.item.document_group">{{data.item.document_group.name}}</span>


                                </template>
                                <template #cell(private)="data">

                                    <span v-if="data.value == 1">X</span>
                                    <span v-else></span>

                                </template>
                                <template #cell(approve_manual)="data">

                                    <span v-if="data.value == 1">X</span>
                                    <span v-else></span>

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
                                <span v-if="edit">{{$t('form.update')}}</span>
                                {{$t('form.module')}}
                            </h4>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="">Mã</label>

                                <input type="text" class="form-control " v-model="module.id"
                                    v-bind:class="hasError('code')?'is-invalid':''" required :readonly="edit">
                                <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('code')}}</strong>
                                </span>

                            </div>
                            <div class="form-group">
                                <label for="">Tên</label>
                                <input type="text" class="form-control " v-model="module.name"
                                    v-bind:class="hasError('name')?'is-invalid':''" required>
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('name')}}</strong>
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
            modules: [],
            module: {
                id: "",
                name: "",
            },
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [2, 10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_module: "/api/category/modules",
            fields: [

                {
                    key: 'id',
                    label: this.$t('form.module'),
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
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetModule();
    },
    methods: {
        fetModule() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_module;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.modules = res.data;
                })
                .catch(err => console.log(err));
        },
        save_and_update() {
            var page_url = this.page_url_module;// "/api/category/teams";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.module),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.reset();
                            this.modules.push(data.data);
                            toastr.success("Lưu thành công", 'Thông báo');
                            $("#form_edit").modal("hide");

                        } else {
                            // this.reset();
                            this.errors = data.data.errors;
                            //  toastr.error(this.errors,'Lưu bị lỗi');

                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.module.id, {
                    method: "PUT",
                    body: JSON.stringify(this.module),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.modules, this.module.index, { ...this.module });
                            toastr.success("Cập nhật thành công", 'Thông báo')
                            $("#form_edit").modal("hide");
                            this.reset();
                        } else {

                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        editItem(item) {
            let index = this.modules.findIndex(i => {
                return i.id == item.id;
            });
            this.edit = true;
            this.module.id = item.id;
            this.module.name = item.name;
            this.module.index = index;

            $("#form_edit").modal("show");
        },
        deleteItem(id) {

            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.page_url_module}/${id}`, {
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
                            this.fetModule();
                        } else {
                            toastr.warning('Xoá lỗi', 'Thông báo');
                        }

                    });
            }

        },
        showCreateForm() {
            this.edit = false;
            this.module = {
                id: "",
                name: "",
            };
            $('#form_edit').modal('show');

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
            for (let field in this.module) {
                this.module[field] = null;
            }
        },
        clearAllError() {
            this.errors = {};
        },
    },

    computed: {
        rows() {
            return this.modules.length;
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
