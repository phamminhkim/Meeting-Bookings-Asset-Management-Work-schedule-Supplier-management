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
                            <button class="btn btn-info btn-sm" @click="showDialogCreateRole()"> <i
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
                                    <div class="col-md-9"></div>
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
                                        :sticky-header="false" small :items="roles" :current-page="current_page"
                                        :per-page="per_page" :filter="filter" :fields="fields">
                                        <template #head(selected)>
                                            <!-- {{selected}} -->
                                            <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll"
                                                @change="select"></b-form-checkbox>
                                        </template>

                                        <template v-slot:cell(selected)="data">
                                            <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected">
                                            </b-form-checkbox>
                                        </template>

                                        <template v-slot:cell(permissions_count)="data">
                                            <a @click.prevent="showPermissionsEdit(data.item)" class="btn btn-xs mr-3"
                                                title="Xem danh sách quyền">
                                                <i class="ace-icon text-blue fa fa-eye bigger-120"></i>
                                                <span v-if="data.item.permissions_list.length > 0"
                                                    class="badge badge-danger navbar-badge">{{
                                                            data.item.permissions_list.length
                                                    }}</span>
                                                <span v-else class="badge badge-default navbar-badge">&nbsp;</span>
                                            </a>
                                        </template>

                                        <template v-slot:cell(permissions_user_count)="data">
                                            <a @click.prevent="showUsersEdit(data.item)" class="btn btn-xs mr-3"
                                                title="Xem danh sách User">
                                                <i class="ace-icon text-blue fa fa-users bigger-120"></i>
                                                <span v-if="data.item.users_list.length > 0"
                                                    class="badge badge-danger navbar-badge">{{
                                                            data.item.users_list.length
                                                    }}</span>
                                                <span v-else class="badge badge-default navbar-badge">&nbsp;</span>
                                            </a>
                                        </template>

                                        <template #cell(action)="data">
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <button @click="showRoleEdit(data.item)" class="btn btn-xs  mr-3"
                                                    title="Edit">
                                                    <i class="fa fa-edit  text-green bigger-120"></i>
                                                </button>

                                                <button @click="deleteRole(data.item.id)" class="btn btn-xs "
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
                <dialog-create-role :role="role" :edit="edit" @onCreateRole="onCreateRole" @onUpdateRole="onUpdateRole">
                </dialog-create-role>

                <dialog-assign-permissions-to-role :role="role" @onUpdateRole="onUpdateRole">
                </dialog-assign-permissions-to-role>

                <dialog-assign-users-to-role :role="role" @onUpdateRole="onUpdateRole">
                </dialog-assign-users-to-role>
            </div>
        </div>
    </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import DialogCreateRole from './dialogs/DialogCreateRole.vue';
import DialogAssignPermissionsToRole from './dialogs/DialogAssignPermissionsToRole.vue';
import DialogAssignUsersToRole from './dialogs/DialogAssignUsersToRole.vue';


export default {
    props: {
        title: ""
    },
    components: {
        Treeselect,
        DialogCreateRole,
        DialogAssignPermissionsToRole,
        DialogAssignUsersToRole
    },
    data() {
        return {
            roles: [],
            role: {
                id: "",
                name: "",
                description: "",
                permissions_list: [],
                permissions_remove_list: [],
            },
            per_page: 10,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors: {},
            loading: false,
            edit: false,
            token: "",
            page_url_role: "/api/category/roles",
            fields: [
                {
                    key: 'selected',
                    label: 'All',
                    class: "text-center text-nowrap"
                },
                {
                    key: 'name',
                    label: this.$t('form.name'),
                    thClass: "text-center text-nowrap",
                    tdClass: "text-nowrap",
                },
                {
                    key: 'description',
                    label: this.$t('form.description'),
                    thClass: "text-center text-nowrap",
                    tdClass: "text-nowrap",
                },
                {
                    key: 'permissions_count',
                    label: 'Tổng số quyền',
                    class: "text-center text-nowrap"
                },
                {
                    key: 'permissions_user_count',
                    label: 'Tổng số User',
                    class: "text-center text-nowrap"
                },
                {
                    key: 'action',
                    label: 'Tương tác',
                    class: "text-center text-nowrap"
                },

            ],
            selected: [],
            selectAll: false,
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetchRoles();
    },

    methods: {
        onCreateRole(role) {
            this.roles.push(role);
        },
        onUpdateRole(index, role) {
            this.roles.splice(index, 1, role);
        },
        fetchRoles() {
            this.loading = true;

            var page_url = this.page_url_role;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.roles = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;
        },
        showDialogCreateRole() {
            this.reset();

            $("#DialogCreateRole").modal("show");
        },
        showPermissionsEdit(role) {
            this.editRole(role);

            $("#DialogAssignPermissionsToRole").modal("show");
        },
        showUsersEdit(role) {
            this.editRole(role);

            $("#DialogAssignUsersToRole").modal("show");
        },
        showRoleEdit(role) {
            this.editRole(role);

            $("#DialogCreateRole").modal("show");
        },
        editRole(role) {
            let index = this.roles.findIndex(i => {
                return i.id == role.id;
            });

            this.edit = true;
            this.role.id = role.id;
            this.role.name = role.name;
            this.role.description = role.description;
            this.role.permissions_list = role.permissions_list;
            this.role.permissions_add_list = role.permissions_list;
            this.role.permissions_remove_list = [];
            this.role.users_list = role.users_list;
            this.role.users_add_list = role.users_list;
            this.role.users_remove_list = [];
            this.role.index = index;
        },
        deleteRole(id) {
            if (confirm(this.$t('form.confirm_delete') + '?')) {
                fetch(`${this.page_url_role}/${id}`, {
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

                            this.fetchRoles();
                        } else {
                            toastr.error(data.message, "Thất bại");
                        }
                    });
            }

        },
        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.roles) {
                    this.selected.push(this.roles[i].id);
                }
            }
        },
        reset() {
            this.edit = false;
            for (let field in this.role) {
                this.role[field] = null;
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
            return this.roles.length;
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
