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
                            <button class="btn btn-info btn-sm" @click="showDialogCreatePermission()"> <i
                                    class="fa fa-plus"></i>
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
                                <div class="row mt-1 " style="background-color:#F4F6F9">
                                    <div class="col-md-3">
                                        <div class="row mt-1">
                                            <div class="input-group input-group-sm  col-12">
                                                <input class="form-control form-control-navbar mb-1" id="search"
                                                    type="search" v-model="filter" :placeholder="$t('form.search')"
                                                    aria-label="Search">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-default btn-flat mb-1"><i
                                                            class="fas fa-search"></i></button>

                                                </span>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="row">
                                    <b-table striped hover responsive :bordered="true" head-variant="light"
                                        :sticky-header="false" small :items="permissions" :current-page="current_page"
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

                                        <template #cell(action)="data">
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <button @click="viewGroups(data.item)" class="btn btn-xs  mr-3"
                                                    title="View">
                                                    <i class="fa fa-eye  text-blue bigger-120"></i>
                                                </button>

                                                <button @click="editPermission(data.item)" class="btn btn-xs  mr-3"
                                                    title="Edit">
                                                    <i class="fa fa-edit  text-green bigger-120"></i>
                                                </button>

                                                <button @click="deletePermission(data.item.id)" class="btn btn-xs "
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
                            <div class="center overlay" v-if="loading">
                                <i class="fa fa-spinner fa-spin fa-4x fa-fw gray center"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <dialog-create-permission :permission="permission" :edit="edit" @onCreatePermission="onCreatePermission"
                    @onUpdatePermission="onUpdatePermission">
                </dialog-create-permission>
            </div>
        </div>
    </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import DialogCreatePermission from './dialogs/DialogCreatePermission.vue';

export default {
    props: {
        title: ""
    },
    components: {
        Treeselect,
        DialogCreatePermission
    },
    data() {
        return {
            permissions: [],
            permission: {
                id: "",
                name: "",
                description: "",
            },
            per_page: 10,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors: {},
            loading: false,
            edit: false,
            token: "",
            page_url_permission: "/api/category/permissions",
            fields: [
                {
                    key: 'selected',
                    label: 'All',
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label: this.$t('form.name'),
                },
                {
                    key: 'description',
                    label: this.$t('form.description'),
                },
                {
                    key: 'action',
                    label: 'Action',
                },

            ],
            selected: [],
            selectAll: false,
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetchPermissions();
    },

    methods: {
        onCreatePermission(permission) {
            this.permissions.push(permission);
        },
        onUpdatePermission(index, permission) {
            this.permissions.splice(index, 1, permission);
        },
        fetchPermissions() {
            this.loading = true;

            var page_url = this.page_url_permission;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.permissions = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;
        },
        showDialogCreatePermission() {
            this.reset();

            $("#DialogCreatePermission").modal("show");
        },
        editPermission(permission) {

            let index = this.permissions.findIndex(i => {
                return i.id == permission.id;
            });

            this.edit = true;
            this.permission.id = permission.id;
            this.permission.name = permission.name;
            this.permission.description = permission.description;
            this.permission.index = index;

            $("#DialogCreatePermission").modal("show");
        },
        deletePermission(id) {
            if (confirm(this.$t('form.confirm_delete') + '?')) {
                fetch(`${this.page_url_permission}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, "Thành công");

                            this.fetchPermissions();
                        } else {
                            toastr.error(data.message, "Thất bại");
                        }
                    });
            }

        },
        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.permissions) {
                    this.selected.push(this.permissions[i].id);
                }
            }
        },
        reset() {
            this.edit = false;
            for (let field in this.permission) {
                this.permission[field] = null;
            }
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
    },
    computed: {
        rows() {
            return this.permissions.length;
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

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
