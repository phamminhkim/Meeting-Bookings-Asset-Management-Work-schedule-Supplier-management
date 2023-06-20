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
                            <button class="btn btn-info btn-sm"> <i class="fa fa-upload"></i>
                                Tải lên</button>
                            <button class="btn btn-info btn-sm" @click="showWorkflow()"> <i class="fa fa-plus"></i>
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

                                    <div class="col-md-9">

                                        <div class="form-group row">
                                            <button type="button" class="btn btn-success btn-sm mt-1"><i
                                                    class="fas fa-download"></i>Tải xuống</button>
                                            <button type="button" class="btn btn-info btn-sm ml-2 mt-1"
                                                @click="cloneWorkflow()"><i class="fas fa-copy"></i>Clone quy
                                                trình</button>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="row mt-1">
                                            <div class="input-group input-group-sm  col-12">

                                                <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                                <input class="form-control form-control-navbar mb-1" id="search"
                                                    type="search" v-model="filter"
                                                    placeholder="Nhập thông tin tìm kiếm.." aria-label="Search">
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
                                        :sticky-header="false" small :items="workflows" :current-page="current_page"
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
                                            <span>{{ company_name(data.value) }}</span>
                                        </template>
                                        <template #cell(department_id)="data">
                                            <span>{{ department_name(data.value) }}</span>
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
                                                <button @click="editWorkflow(data.item)" class="btn btn-xs mr-3"
                                                    title="Điều chỉnh thông tin">
                                                    <i class="fa fa-edit  text-green bigger-120"></i>
                                                </button>
                                                <a v-bind:href="editWorkflowDetail(data.item)" class="btn btn-xs mr-3"
                                                    title="Điều chỉnh chi tiết">
                                                    <i class="ace-icon text-blue fa fa-cog bigger-120"></i>
                                                </a>
                                                <button @click="deleteWorkflow(data.item.id)" class="btn btn-xs "
                                                    title="Xóa">
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
            <div class="modal fade" id="AddWorkflow" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="AddWorkflow" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t('form.add') }} Qui trình </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click.prevent="clearAllError()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">


                                <div class="form-group">
                                    <label for="name" class="col-form-label-sm">{{ $t('form.name') }}</label>
                                    <input v-model="workflow.name" type="text" class="form-control form-control-sm"
                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                                        placeholder="" required />

                                    <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('name') }}</strong>

                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="department_id" class="col-form-label-sm">{{ $t('form.type') }}
                                        workflow</label>
                                    <select id="wlworkflow_type_id" class="form-control form-control-sm"
                                        v-model="workflow.wlworkflow_type_id"
                                        v-bind:class="hasError('wlworkflow_type_id') ? 'is-invalid' : ''">
                                        <option v-for="workflowtype in workflowtypes" v-bind:key="workflowtype.id"
                                            v-bind:value="workflowtype.id">
                                            {{ workflowtype.name }}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('wlworkflow_type_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('wlworkflow_type_id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label-sm" for="">Phạm vi sử dụng</label>
                                    <treeselect placeholder="Tất cả" :multiple="true" v-model="workflow.scope"
                                        :options="tree_groups" />
                                </div>
                                <div class="form-group">
                                    <label for="department_id" class="col-form-label-sm">Quản trị</label>

                                    <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.admin_values"
                                        :disable-branch-nodes="true" :multiple="true" :options="tree_users">
                                    </treeselect>
                                </div>
                                <div class="form-group">
                                    <label for="department_id" class="col-form-label-sm">Thành viên</label>

                                    <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.member_values"
                                        :disable-branch-nodes="true" :multiple="true" :options="tree_users">
                                    </treeselect>
                                </div>
                                <div class="form-group">
                                    <label for="department_id" class="col-form-label-sm">Theo dõi</label>

                                    <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.follow_values"
                                        :disable-branch-nodes="true" :multiple="true" :options="tree_users">
                                    </treeselect>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-form-label-sm">{{ $t('form.description')
                                    }}</label>
                                    <textarea v-model="workflow.description" type="text"
                                        class="form-control form-control-sm"
                                        v-bind:class="hasError('description') ? 'is-invalid' : ''" id="description"
                                        name="description" placeholder="" required />
                                    <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('description') }}</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label for="active" class="col-form-label-sm">{{ $t('form.active') }}</label>

                                    <select class="form-control form-control-sm" v-model="workflow.active" name="active"
                                        id="active">
                                        <option value="1" selected>{{ $t('form.active') }}</option>
                                        <option value="0">{{ $t('form.inactive') }}</option>
                                    </select>


                                    <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('level') }}</strong>
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
    </div>

</template>

<script>


// import Pagination from "../shared/Pagination.vue";
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
    components: {
        Treeselect
    },
    props: {
        title: "",


    },

    data() {
        return {
            workflows: [],
            workflowtypes: [],
            pagesNumber: [],
            companies: [],
            departments: [],
            user_confirm: null,
            tree_users: [],
            tree_groups: [],
            workflow: {
                id: "",
                name: "",
                description: "",
                active: "",
                company_id: "",
                department_id: "",
                wlworkflow_type_id: "",
                admin_values: [],
                member_values: [],
                follow_values: [],
                document_types: [],
                wldoctypes: [],
                index: "",
                approve_type: "",
                sub_approve_type: "0"
            },
            document_types: [],
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            page_url_workflow_type: "/api/category/workflowtypes",
            page_url_users: "api/user/all",
            page_url_document_type: "/api/category/documenttypes",
            page_url_groups: "/api/category/groups",
            show_search: false,
            form_filter: {
                manv: "",
                tennv: "",
                active: "",
                company_id: "",
                department_id: ""
            },
            fields: [
                {
                    key: 'selected',
                    label: 'All',
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label: 'Tên',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'description',
                    label: 'Mô tả',
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
        this.getDocumentType();
        this.getUserTree();
        this.fetCompany();
        this.fetDepartment();
        this.fetWorkflowType();
        this.fetchWorkflow();
        this.getGroupTree();
    },
    mounted() {


        // this.fetchWorkflow(this.pagination.current_page);

    },

    methods: {
        getGroupTree() {
            var page_url = this.page_url_groups + "?type=tree_withusers";

            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.tree_groups = data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getDocumentType() {
            var page_url = this.page_url_document_type + "?module=WORKFLOW";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.document_types = [];
                    var item = {};

                    res.data.forEach(element => {
                        item.id = element.id
                        item.label = element.code + '-' + element.name;

                        this.document_types.push({ ...item });
                    });
                    // this.document_types = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getUserTree() {
            var page_url = this.page_url_users + "?type=tree_combobox";

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
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
        fetWorkflowType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_workflow_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.workflowtypes = res.data;
                })
                .catch(err => console.log(err));
        },
        fetDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },

        fetchWorkflow() {
            //$("#tbbody_id").html('');

            this.loading = true;

            this.workflows = Array();
            const params = new URLSearchParams({
                manv: this.form_filter.manv,
                tennv: this.form_filter.tennv,
                active: this.form_filter.active,
                company_id: this.form_filter.company_id,
                department_id: this.form_filter.department_id
            });

            var apiAddress = "/api/works/workflowsdefine?" + params.toString();
            fetch(apiAddress, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    if (res.statuscode > 0) {
                        window.location.href = "/errorpage?statuscode=" + res.statuscode;
                    }
                    else if (res.success == 1) {
                        this.workflows = res.data;
                    }
                    else {
                        this.errors = res.errors;
                    }
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        showWorkflow() {
            this.reset();
            this.workflow.active = 1;

            //    if(this.company_id == '' || this.department_id == ''){
            //          toastr.info("Vui lòng chọn công ty và phòng ban");
            //          return;
            //    }


            $("#AddWorkflow").modal("show");
        },
        cloneWorkflow() {
            if (this.selected.length != 1) {
                toastr.warning("Vui lòng chọn 1 dòng dữ liệu.");
            }
            else {
                var workflow_id = this.selected[0];

                var apiAddress = "/api/works/clone-workflow";

                fetch(apiAddress, {
                    method: "POST",
                    body: JSON.stringify({
                        workflow_id: workflow_id
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.reset();
                            this.workflows.push(res.data);
                       
                            window.location.href = "/workdefines?type=edit&id=" + res.data.id;
                            toastr.success("Clone quy trình thành công", "Thông báo");
                        }
                        else {
                            this.errors = res.errors;
                            toastr.danger(res.errors, "Clone quy trình bị lỗi");
                        }
                        this.loading = false;
                    })
                    .catch(err => console.log(err));
            }
        },
        AddWorkflow() {
            var apiAddress = "/api/works/workflowsdefine";
            if (this.edit === false) {
                fetch(apiAddress, {
                    method: "POST",
                    body: JSON.stringify(this.workflow),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.reset();
                            this.workflows.push(res.data);
                            $("#AddWorkflow").modal("hide");
                            window.location.href = "/workdefines?type=edit&id=" + res.data.id;
                            toastr.success("Tạo quy trình thành công", "Thông báo");
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Tạo quy trình bị lỗi");
                        }
                        this.loading = false;
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(apiAddress + '/' + this.workflow.id, {
                    method: "PUT",
                    body: JSON.stringify(this.workflow),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data.data);
                        if (!data.data.errors) {
                            var index = this.workflow.index;
                            this.workflow = { ...data.data };


                            this.$set(this.workflows, index, { ...this.workflow });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#AddWorkflow").modal("hide");
                            this.reset();
                        } else {

                            this.errors = data.errors;
                            this.showMessage('Thông báo', 'Cập nhật lỗi');

                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        editWorkflow(workflow) {

            let index = this.workflows.findIndex(i => {
                console.log("id" + i.id);
                return i.id == workflow.id;
            });
            // var group={..._obj};
            this.edit = true;
            this.workflow.id = workflow.id;
            this.workflow.active = workflow.active;
            this.workflow.description = workflow.description;
            this.workflow.name = workflow.name;
            this.workflow.department_id = workflow.department_id;
            this.workflow.company_id = workflow.company_id;
            this.workflow.admins = workflow.admins;
            this.workflow.members = workflow.members;
            this.workflow.follows = workflow.follows;
            this.workflow.wlworkflow_type_id = workflow.wlworkflow_type_id;
            this.workflow.index = index;
            this.workflow.wldoctypes = workflow.wldoctypes;
            this.workflow.type = workflow.type;
            this.workflow.approve_type = workflow.approve_type;

            this.workflow.document_types = [];

            this.workflow.wldoctypes.forEach(element => {
                this.workflow.document_types.push(element.document_type_id);
            });



            this.workflow.admin_values = [];
            this.workflow.member_values = [];
            this.workflow.follow_values = [];
            this.workflow.admins.forEach(element => {
                this.workflow.admin_values.push(element.user_id);
            });
            this.workflow.members.forEach(element => {
                this.workflow.member_values.push(element.user_id);
            });
            this.workflow.follows.forEach(element => {
                this.workflow.follow_values.push(element.user_id);
            });

            $("#AddWorkflow").modal("show");
        },
        deleteWorkflow(id) {

            if (confirm('Bạn muốn xoá?')) {
                this.loading = true;

                var apiAddress = "/api/works/workflowsdefine/" + id;
                fetch(apiAddress, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        this.reset();
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {

                            toastr.success("Xoá quy trình thành công", "Thông báo");
                            this.fetchWorkflow();
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Xóa quy trình bị lỗi");
                        }
                        this.loading = false;
                    });
            }

        },
        editWorkflowDetail(workflow) {
            return "/workdefines?type=edit&id=" + workflow.id;
        },

        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.workflows) {
                    this.selected.push(this.workflows[i].id);
                }
            }
        },
        filter_data() {
            this.fetchWorkflow();
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
        company_name(company_id) {
            var obj = this.companies.find(x => x.id == company_id);

            if (obj)
                return obj.name;
            else
                return "";
        },
        department_name(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            if (obj)
                return obj.name;
            else
                return "";
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
            for (let field in this.workflow) {
                this.workflow[field] = null;
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
        rows() {
            return this.workflows.length;
        },
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        department_filter() {
            let company_id = this.form_filter.company_id;
            // this.contract.department_id = "";
            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                    return true;
                }
            });
        },

        department_filter_form() {
            let company_id = this.workflow.company_id;
            // this.contract.department_id = "";
            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                    return true;
                }
            });
        },
        //  company_name(){

        //       var obj =  this.companies.find(x=>x.id == this.company_id);
        //       if(obj)
        //         return obj.name;

        //  },
        //   department_name(){

        //       var obj =  this.departments.find(x=>x.id == this.department_id);
        //       if(obj)
        //         return obj.name;

        //  },
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
