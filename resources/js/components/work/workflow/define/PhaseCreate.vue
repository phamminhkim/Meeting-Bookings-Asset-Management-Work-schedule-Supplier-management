<template>
    <div>

        <div class="card sm">

            <b-tabs card small v-if='phase_id > 0'>

                <b-overlay :show="loading" rounded="sm">
                    <b-tab title="Thông tin giai đoạn">
                        <form @submit.prevent="AddPhase" @keydown.enter.prevent="clearError">
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 text-md-right">ID giai đoạn</label>
                                <div class="col-sm-8">
                                    <input type="text" v-model="wlphase.id" readonly required
                                        class="form-control form-control-sm">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 text-md-right">Unique name</label>
                                <div class="col-sm-8">
                                    <input type="text" :value="getPhaseUniqueName()" readonly required
                                        class="form-control form-control-sm">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 text-md-right">Tên giai đoạn</label>
                                <div class="col-sm-8">
                                    <input type="text" v-model="wlphase.name" required
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                            <div class="form-group row" v-if='false'>
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Thời
                                    gian
                                    thực hiện</label>

                                <div class="col-sm-8">
                                    <input type="number" class="form-control form-control-sm" name="title" id="title"
                                        v-model="wlphase.time_execution" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Quản
                                    trị
                                    giai đoạn</label>

                                <div class="col-sm-8">
                                    <treeselect style="font-size: 15px;" placeholder="" v-model="wlphase.admin_values"
                                        :disable-branch-nodes="true" :multiple="true" :options="tree_users">
                                    </treeselect>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Thành
                                    viên giai đoạn</label>

                                <div class="col-sm-8">
                                    <treeselect style="font-size: 15px;" placeholder="" v-model="wlphase.member_values"
                                        :disable-branch-nodes="true" :multiple="true" :options="tree_users">
                                    </treeselect>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Mô tả
                                    giai đoạn</label>

                                <div class="col-sm-8">
                                    <textarea v-model="wlphase.description"
                                        class="form-control form-control-sm"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Cho
                                    phép bổ sung công việc</label>

                                <div class="col-sm-8">
                                    <b-form-checkbox v-model="wlphase.allow_add_job" class="form-control form-control-sm">
                                    </b-form-checkbox>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Cho
                                    phép tương tác công việc</label>

                                <div class="col-sm-8">
                                    <b-form-checkbox v-model="wlphase.allow_send_response" class="form-control form-control-sm">
                                    </b-form-checkbox>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Tự
                                    thêm
                                    người tạo vào danh sách quản trị</label>

                                <div class="col-sm-8">
                                    <b-form-checkbox v-model="wlphase.addOwnerToPhaseAdmins"
                                        class="form-control form-control-sm"></b-form-checkbox>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Giới
                                    hạn
                                    người thực hiện trong danh sách thành viên</label>

                                <div class="col-sm-8">
                                    <b-form-checkbox v-model="wlphase.limitJobUserByPhaseMember"
                                        class="form-control form-control-sm"></b-form-checkbox>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                            <label for="title" class="col-form-label-sm col-sm-4 col-form-label text-md-right">Phê duyệt</label>

                            <div class="col-sm-8">
                               <select name="" id="" v-model="wlphase.approve_type" class="form-control form-control-sm">
                                <option value=""></option>
                                <option value="0">Manual</option>
                                <option value="1">Cấu hình cây duyệt</option>
                               </select>
                            </div>
                        </div> -->

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Unique name</th>
                                        <th scope="col">Tên công việc</th>
                                        <th scope="col" colspan="3" width="10%">Cài đặt</th>
                                    </tr>
                                    <tr>
                                        <th scope="col"></th>
                                        <th colspan="2" scope="col"><input type="text" v-model="wljob.name"
                                                class="form-control form-control-sm" placeholder="Tên công việc"></th>
                                        <th scope="col" colspan="2"><button class="btn btn-secondary btn-sm"
                                                @click.prevent.enter="AddJob()" @keydown.enter.prevent="AddJob()"
                                                title="Thêm công việc"><i class="fas fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody v-for="(job, index) in wlphase.wljobs" v-bind:key="index">
                                    <tr>
                                        <td scope="row">{{ index + 1 }}</td>
                                        <td><input type="text" :value="getUniqueName(job)"
                                                class="form-control form-control-sm" placeholder="Unique name" readonly>
                                        </td>
                                        <td><input type="text" v-model="job.name" class="form-control form-control-sm"
                                                placeholder="Tên công việc"></td>
                                        <td><span type="button" class="text-primary"
                                                @click.prevent="showDialogJobInfo(job)"><i
                                                    class="fas fa-list"></i></span>
                                        </td>
                                        <td><span class="text-info"
                                                v-b-toggle="'collapse-' + wlphase.id + 'job' + job.id"><i
                                                    class="fas fa-cog"></i></span></td>
                                        <td><span type="button" class="text-danger" @click.prevent="deleteJob(index)"><i
                                                    class="fas fa-trash"></i></span></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="7">
                                            <b-collapse v-bind:id="'collapse-' + wlphase.id + 'job' + job.id"
                                                class="mt-2">
                                                <data-object-list
                                                    v-bind:unique_id="'modal_Phase' + wlphase.id + 'job' + job.id"
                                                    title="Thêm tùy chỉnh giai đoạn"
                                                    :wlworkflow_id="wlphase.wlworkflow_id" :job_id="job.id"
                                                    :phase_id="wlphase.id"
                                                    :workflow_structure="workflow_structure"></data-object-list>
                                                <!-- <data-object-new-list
                                                    v-bind:unique_id="'modal_Phase' + wlphase.id + 'job' + job.id"
                                                    title="Thêm tùy chỉnh công việc"
                                                    :wlworkflow_id="wlphase.wlworkflow_id" :job_id="job.id"
                                                    :phase_id="wlphase.id">
                                                </data-object-new-list> -->
                                            </b-collapse>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div style="text-align:center" class="mt-1">
                                <button type="submit" :disabled="loading" class="btn btn-primary"> {{
                                $t('form.save')
                                }}</button>
                                <button @click.prevent="deletePhase(wlphase.id)" class="btn btn-danger sm"> {{
                                $t('form.delete')
                                }}</button>
                            </div>
                        </form>

                    </b-tab>
                </b-overlay>


            </b-tabs>
        </div>

        <dialog-job-info v-bind:id="'dialogJobInfo'" :workflow_structure="workflow_structure" v-bind:currentjob="dialogJobInfo" v-bind:jobs="wlphase.wljobs"
            v-bind:treeusers="tree_users" v-on:updateJob="updateJob">
        </dialog-job-info>
    </div>


</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import DataObjectList from './DataObjectList.vue';
import DialogJobInfo from "./dialogs/DialogJobInfo.vue";
import DataObjectNewList from './DataObjectNewList.vue';

export default {
    components: { DataObjectList, Treeselect, DialogJobInfo, DataObjectNewList },
    props: {
        phase_id: Number,
        wlworkflow_id: Number,
        workflow_structure: Object,
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.getUserTree();
        $("#dialogJobInfo").modal();
    },
    data() {
        return {
            wlphase: {
                allow_add_job: false,
                allow_send_response: false,
                description: "",
                id: 0,
                name: "",
                unique_name: "",
                selected: "",
                time_execution: "",
                wlworkflow_id: null,
                wljobs: [],
                wljobs_del: [],
                wldataobjects: [],
                wldataobjects_del: [],
                admin_values: [],
                member_values: [],
                approve_type: "",
            },
            wljob: {
                unique_name: "",
                name: "",
                time_execution: "",
                action_user: null,
                scores: "",
                allow_add_job: false,
                allow_send_response: false,
                is_action_user_by_ref: false,
                action_user_path_ref: null,
                is_assign_user_by_ref: false,
                assign_user_path_ref: null,
                description: "",
                user_name: "",
                user: {
                    name: "",
                }

            },

            tree_users: [],
            errors: {},
            loading: false,

            token: "",
            edit: false,
            tabIndex: 0,
            page_url_users: "api/user/all",

            dialogJobInfo: null
        };
    },
    methods: {
        showDialogJobInfo(job) {
            this.dialogJobInfo = job;
            $("#dialogJobInfo").modal("show");
        },
        deletePhase(id) {

            if (confirm('Bạn muốn xoá?')) {
                var apiAddress = "/api/works/phases/" + id;
                fetch(apiAddress, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        toastr.success(this.$t('form.delete_success'), "");
                        //$this.$emit('event-name', param);
                        this.$emit('actionRefresh', { ...this.wlphase }, 'DELETE');

                    });
            }

        },
        getNameUser(user_id) {
            var name = "";
            this.tree_users.forEach(comp => {
                if (comp.children && comp.children.length > 0) {
                    comp.children.forEach(user => {
                        if (user.id == user_id) {
                            name = user.label;
                        }
                    }
                    );
                }

            });
            return name;
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

        //Job - công việc
        deleteJob(index) {
            if (confirm(this.$t('form.confirm_delete') + "?")) {
                var job = this.wlphase.wljobs[index];
                this.wlphase.wljobs_del.push(job);
                this.wlphase.wljobs.splice(index, 1);
            }

        },
        AddJob() {
            if (this.wljob.name != "") {
                if (this.wlphase.wljobs == null) {
                    this.wlphase.wljobs = [];
                }
                this.wljob.unique_name = this.getUniqueName(this.wljob);

                var name = "";
                this.tree_users.forEach(comp => {
                    if (comp.children && comp.children.length > 0) {
                        comp.children.forEach(user => {
                            if (user.id == this.wljob.action_user) {
                                name = user.label;
                            }
                        }
                        );
                    }

                });
                this.wljob.wlphase_id = this.wlphase.id;
                this.wljob.user_name = name;
                this.wljob.id = null;

                this.loading = true;
                var apiAddress = "/api/works/jobs";
                fetch(apiAddress, {
                    method: "POST",
                    body: JSON.stringify(this.wljob),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                }).then(res => res.json())
                    .then(res => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.wljob = res.data;
                            this.wlphase.wljobs.push({ ...this.wljob });
                            this.resetJob();

                            toastr.success("Thêm công việc thành công", "Thông báo");
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Thêm công việc bị lỗi");
                        }
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        updateJob(job) {
            this.wlphase.wljobs.forEach((currentJob, index) => {
                if (currentJob.id == job.id) {
                    this.wlphase.wljobs[index] = job;
                    this.dialogJobInfo = job;
                }
            });
        },
        resetJob() {
            this.wljob.unique_name = "";
            this.wljob.name = "";
            this.wljob.time_execution = "";
            this.wljob.action_user = null;
            this.wljob.scores = "";
            this.wljob.description = "";
            this.wljob.allow_add_job = false;
            this.wljob.allow_send_response = false;
            this.wljob.is_action_user_by_ref = false;
            this.wljob.action_user_path_ref = null;
            this.wljob.is_assign_user_by_ref = false;
            this.wljob.assign_user_path_ref = null;
            this.wljob.approve_type = "";
        },
        AddPhase() {
            this.loading = true;
            var apiAddress = "api/works/phases/" + this.phase_id;
            fetch(apiAddress, {
                method: "PUT",
                body: JSON.stringify(this.wlphase),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    if (!data.data.errors) {

                        this.wlphase = { ...data.data };
                        this.wlphase.admin_values = [];
                        this.wlphase.member_values = [];
                        this.wlphase.wljobs_del = [];
                        this.wlphase.wldataobjects_del = [];

                        this.wlphase.admins.forEach(element => {
                            this.wlphase.admin_values.push(element.user_id);
                        });
                        this.wlphase.members.forEach(element => {
                            this.wlphase.member_values.push(element.user_id);
                        });

                        toastr.success(this.$t('form.save_success'), "");
                        this.$emit('actionRefresh', { ...this.wlphase }, 'EDIT');
                        //this.resetPhase();

                    } else {
                        this.errors = data.data.errors;
                        toastr.warning(data.data.errors, "Lưu bị lỗi");
                    }
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        fetPhase() {
            this.loading = true;

            var apiAddress = "/api/works/phases/" + this.phase_id + "/edit";
            fetch(apiAddress, {
                headers: { Authorization: this.token }
            }).then(res => res.json())
                .then(data => {
                    //  console.log(data);
                    this.wlphase = data.data;
                    this.wlphase.wljobs_del = [];
                    this.wlphase.wldataobjects_del = [];
                    this.wlphase.admin_values = [];
                    this.wlphase.member_values = [];
                    this.wlphase.admins.forEach(element => {
                        this.wlphase.admin_values.push(element.user_id);
                    });
                    this.wlphase.members.forEach(element => {
                        this.wlphase.member_values.push(element.user_id);
                    });
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        getPhaseUniqueName() {
            if (this.wlphase) {
                this.wlphase.unique_name = this.wlphase.name.normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                    .toLowerCase()
                    .replace(/[^a-zA-Z0-9 ]/g, "")
                    .replaceAll(' ', '_');

                return this.wlphase.unique_name;
            }
            return '';
        },
        getUniqueName(currentjob) {
            if (currentjob) {
                currentjob.unique_name = currentjob.name.normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                    .toLowerCase()
                    .replace(/[^a-zA-Z0-9 ]/g, "")
                    .replaceAll(' ', '_');

                return currentjob.unique_name;
            }
            return '';
        },
        showLabel(fieldName, value) {
            if (fieldName in this.layout) {
                if (this.layout[fieldName]['has_custom_label']) {
                    return this.layout[fieldName]['custom_label_text'];
                }
            }
            return value;
        },
        showValidate(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]['require_validation'];
            }
            return false;
        },
        readOnly(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]['is_read_only'];
            }
            return false;
        },
        showControl(fieldName) {
            if (fieldName in this.layout) {

                return this.layout[fieldName]['isVisible'];
            }
            return false;
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
            //console.log(event.target.name);
        },

        clearAllError() {
            this.errors = {};
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
    },
    watch: {
        phase_id() {
            this.fetPhase();
        },
    },
}
</script>

<style>

</style>
