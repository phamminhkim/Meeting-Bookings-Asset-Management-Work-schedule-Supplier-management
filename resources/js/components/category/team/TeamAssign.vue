<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">{{ $t("form.config_team") }}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <a class="btn btn-default" href="javascript:history.back()" type="button">
                                <i class="ace-icon fa fa-close bigger-110"></i>
                                {{ $t("form.close") }}
                            </a>
                            <a href="#" @click.prevent="saveConfigTeam()" class="btn btn-primary"><i
                                    class="fa fa-save"></i> {{ $t("form.save") }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-default">
            <!-- <div class="card-header">
              <h3 class="card-title">Cấu hình Team</h3>
             </div> -->

            <div class="form-horizontal">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-md-right">
                            {{ $t("form.name") }}</label>

                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control col-xs-10 col-sm-6" id="name"
                                v-model="team.name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-md-right">
                            Ngày tạo cây duyệt</label>

                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control col-xs-10 col-sm-6" id="name"
                                v-model="team.created_at" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-md-right">
                            Ngày cập nhật cuối</label>

                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control col-xs-10 col-sm-6" id="name"
                                v-model="team.updated_at" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-md-right">
                            Người cập nhật cuối</label>

                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control col-xs-10 col-sm-6" id="name"
                                v-model="currentUsername" />
                        </div>
                    </div>

                    <div class="space-4"></div>
                    <div class="clearfix">
                        <div class="col-md-9 offset-md-3"></div>
                    </div>
                    <hr />
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-5 col-sm-2">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill"
                                            href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home"
                                            aria-selected="true">
                                            <i class="blue ace-icon fa fa-user bigger-110"></i>
                                            Cây duyệt
                                        </a>
                                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill"
                                            href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile"
                                            aria-selected="false">
                                            Info
                                        </a>
                                    </div>
                                </div>

                                <div class="col-7 col-sm-10">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home"
                                            role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                            <button type="button" v-on:click="popupUser()"
                                                class="btn btn-block btn-outline-secondary btn-sm col-2"
                                                id="btnChonUser">
                                                {{ $t("form.add") }} User
                                            </button>
                                            <button type="button" v-on:click="copyData()"
                                                class="btn btn-outline-warning btn-sm col-2" id="btnCopyData">
                                                Copy Data
                                            </button>
                                            <button type="button" v-on:click="pasteData()"
                                                class="btn btn-outline-success btn-sm col-2" id="btnPasteData">
                                                Paste Data
                                            </button>

                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <th width="40%">{{ $t("form.employee_code") }}</th>
                                                    <th>{{ $t("form.step") }}</th>
                                                    <th>{{ $t("form.level") }}</th>
                                                    <th>{{ $t("form.sign") }}</th>
                                                    <th>Review</th>
                                                    <th>{{ $t("form.delete") }}</th>
                                                    <th>{{ $t("form.add") }}</th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(user, index) in team.users" v-bind:key="index">
                                                        <td>
                                                            <treeselect v-model="user.id" :disable-branch-nodes="true"
                                                                :options="tree_users" required>
                                                            </treeselect>
                                                        </td>
                                                        <td>
                                                            <b-select :options="steps" v-model="user.pivot.step"
                                                                size="sm"></b-select>
                                                        </td>

                                                        <td>
                                                            <select name="" id="" v-model="user.pivot.level"
                                                                @change="updateTreeLevel($event.target.value)"
                                                                class="form-control form-control-sm">
                                                                <option v-for="i in 30" :value="i" v-bind:key="i">
                                                                    Level {{ i }}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="" id="" v-model="user.pivot.sign"
                                                                class="form-control form-control-sm">
                                                                <option v-for="i in 5" :value="i" v-bind:key="i">
                                                                    Sign {{ i }}
                                                                </option>
                                                                <option value="">-----</option>
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center">
                                                            <b-form-checkbox v-model="user.pivot.review" value="1"
                                                                unchecked-value="0"></b-form-checkbox>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-xs"
                                                                v-on:click.prevent="detachUser(user, index)">
                                                                <i class="fa fa-trash text-red bigger-120"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-xs"
                                                                v-on:click.prevent="addNewUserAbove(user, index)">
                                                                <i class="fa fa-arrow-up text-green bigger-120"></i>
                                                            </button>
                                                            <button class="btn btn-xs"
                                                                v-on:click.prevent="addNewUserBelow(user, index)">
                                                                <i class="fa fa-arrow-down text-green bigger-120"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="col-7 col-sm-10" v-if="current_approval_tree">
                                                <b-button style="margin-bottom: 10px" v-b-toggle.collapse-2
                                                    variant="primary">Hiện trạng thái duyệt
                                                </b-button>
                                                <b-collapse id="collapse-2" class="mt-2">
                                                    <b-card>
                                                        <div class="form-group row">
                                                            <label for="name"
                                                                class="col-sm-4 col-form-label text-md-right">
                                                                Người duyệt hiện tại</label>

                                                            <div class="col-md-6">
                                                                <treeselect placeholder="" v-model="current_approval"
                                                                    :disable-branch-nodes="true"
                                                                    :options="current_approval_tree"></treeselect>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-6">
                                                                <input type="checkbox" id="checkbox"
                                                                    v-model="notify_new_approver">
                                                                Thông báo đến người duyệt mới

                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-6">
                                                                <button v-on:click="changeApproveUser()" type="button"
                                                                    class="btn btn-secondary btn-sm">
                                                                    Thay đổi
                                                                </button>
                                                            </div>
                                                        </div>


                                                    </b-card>
                                                </b-collapse>
                                            </div>

                                            <div class="col-7 col-sm-10" v-if="original_team">
                                                <b-button v-b-toggle.collapse-1 variant="primary">Hiện cây duyệt gốc
                                                </b-button>

                                                <b-collapse id="collapse-1" class="mt-2">

                                                    <b-card>

                                                        <div class="form-group row">
                                                            <label for="name"
                                                                class="col-sm-4 col-form-label text-md-right">
                                                                Cây duyệt gốc</label>

                                                            <div class="col-sm-6">
                                                                <input type="text" readonly class="form-control"
                                                                    id="name" v-model="original_team.name" />
                                                            </div>
                                                        </div>
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <th width="40%">{{ $t("form.employee_code") }}</th>
                                                                <th>{{ $t("form.step") }}</th>
                                                                <th>{{ $t("form.level") }}</th>
                                                                <th>{{ $t("form.sign") }}</th>
                                                                <th>Review</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(user, index) in original_team.users"
                                                                    v-bind:key="index">
                                                                    <td>
                                                                        <treeselect v-model="user.id"
                                                                            :disable-branch-nodes="true"
                                                                            :options="tree_users" required
                                                                            :disabled="true">
                                                                        </treeselect>
                                                                    </td>
                                                                    <td>
                                                                        <b-select :options="steps"
                                                                            v-model="user.pivot.step" size="sm"
                                                                            :disabled="true"></b-select>
                                                                    </td>

                                                                    <td>
                                                                        <b-select v-model="user.pivot.level" size="sm"
                                                                            :disabled="true">
                                                                            <option v-for="i in 30" :value="i"
                                                                                v-bind:key="i">
                                                                                Level {{ i }}
                                                                            </option>
                                                                        </b-select>
                                                                    </td>
                                                                    <td>
                                                                        <b-select v-model="user.pivot.sign" size="sm"
                                                                            :disabled="true">
                                                                            <option v-for="i in 30" :value="i"
                                                                                v-bind:key="i">
                                                                                Sign {{ i }}
                                                                            </option>
                                                                            <option value="">-----</option>
                                                                        </b-select>
                                                                    </td>
                                                                    <td style="text-align: center">
                                                                        <b-form-checkbox v-model="user.pivot.review"
                                                                            value="1" unchecked-value="0"
                                                                            :disabled="true"></b-form-checkbox>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="col-sm-10">

                                                            <button v-on:click="copyDataOriginal()"
                                                                class="btn btn-outline-warning btn-sm col-5"
                                                                id="btnCopyDataOriginal">
                                                                Copy data
                                                            </button>
                                                            <button v-on:click="viewOriginal()"
                                                                class="btn btn-outline-success btn-sm col-5"
                                                                id="btnViewOriginal">
                                                                Xem cây duyệt gốc
                                                            </button>

                                                        </div>
                                                    </b-card>

                                                </b-collapse>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel"
                                            aria-labelledby="vert-tabs-profile-tab">
                                            Info
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <dialogSearch v-on:selectedItem="selectedItem"></dialogSearch>
    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import dialogSearch from "./DialogSearch.vue";
export default {
    components: {
        dialogSearch,
        Treeselect,
    },
    props: {
        id: "",
        title: "",
    },
    data() {
        return {
            steps: [],
            team: {
                id: "",
                name: "",
                description: "",
                active: "",
                index: "",
                users: [],
                users_del: [],
            },
            users: [],
            userSelected: [],
            original_team: null,
            current_approval: null,
            notify_new_approver: null,
            current_approval_tree: null,
            doctype: null,
            docid: null,
            errors: {},
            paginationdata: {},
            loading: false,
            edit: false,
            token: "",
            tree_users: [],
            page_url_team: "/api/category/teams",
            page_url_assign_user: "api/category/assign_user_team",
            page_url_detach_user: "api/category/detach_user_team",
            page_url_users: "api/user/all",
            page_url_step: "api/category/steps",
            page_url_change_approve_user: "api/approve/changeuser",
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.get_step();
        this.getTeam();
        this.getUsers();
        this.getUserTree();
    },
    computed: {
        currentUsername() {
            if (this.team) {
                return this.getUsernameById(this.team.updated_by);
            }
        },
    },
    methods: {
        getUsernameById(id) {
            var user = this.users.find((x) => x.id == id);
            return user == null ? id : user.name;
        },
         getUsers() {
            var page_url = this.page_url_users ;
            console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.users = data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getUserTree() {
            var page_url = this.page_url_users + "?type=tree_combobox";
            console.log(page_url);
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
        get_step() {
            this.loading = true;
            var page_url = this.page_url_step;
            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.loading = false;
                    console.log(data);
                    if (data.success == 1) {
                        var obj = {};
                        data.data.forEach((element) => {
                            obj = {};
                            obj.value = element.id;
                            obj.text = element.name;
                            this.steps.push({ ...obj });
                        });
                    }
                })
                .catch((err) => {
                    this.loading = false;
                    console.log(err);
                });
        },
        getTeam() {
            var page_url = this.page_url_team + "/" + this.id; // "/api/category/teams";
            if (this.edit === false) {
                fetch(page_url, {
                    method: "GET",
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        console.log(data.data);
                        if (!data.data.errors) {
                            this.team = data.data;
                            this.team.users_del = [];
                            //this.users = this.team.users;
                            //  this.reset();
                            // this.teams.push(data.data);
                            // this.showMessage('Thông báo','Lưu thành công');
                            // $("#AddTeam").modal("hide");
                            this.original_team = data.data.original_team;
                            this.current_approval = data.data.current_approval;
                            this.current_approval_tree = data.data.current_approval_tree;
                            this.doctype = data.data.doctype;
                            this.docid = data.data.docid;
                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch((err) => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.team.id, {
                    method: "PUT",
                    body: JSON.stringify(this.team),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        console.log(data);
                        if (!data.data.errors) {
                            // this.teams[this.team.index]=this.team;
                            this.$set(this.teams, this.team.index, { ...this.team });
                            this.showMessage(this.$t("form.message"), this.$t("form.update_sucess"));
                            $("#AddTeam").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch((err) => console.log(err));
            }
        },
        showMessage(title, message) {
            if (!title) title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right",
            };

            toastr.success(message, title);
        },
        showFailMessage(title, message) {
            if (!title) title = "Error";
            toastr.options = {
                positionClass: "toast-bottom-right",
            };

            toastr.error(message, title);
        },
        popupUser() {
            $("#modal-user").modal("show");
        },
        copyData() {
            navigator.clipboard.writeText(JSON.stringify(this.team.users));
            this.showMessage(this.$t("form.message"), this.$t("form.copy_success"));
        },
        copyDataOriginal() {
            navigator.clipboard.writeText(JSON.stringify(this.original_team.users));
            this.showMessage(this.$t("form.message"), this.$t("form.copy_success"));
        },
        changeApproveUser() {
            fetch(this.page_url_change_approve_user, {
                method: "post",
                body: JSON.stringify({
                    userid: this.current_approval,
                    doctype: this.doctype,
                    docid: this.docid,
                    notify: this.notify_new_approver
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    toastr.options = {
                        positionClass: "toast-bottom-right",
                    };
                    toastr.success(this.$t("form.update_sucess"), this.$t("form.message"));
                })
                .catch((err) => console.log(err));
        },
        viewOriginal() {
            window
                .open("category/team?type=assign&id=" + this.original_team.id, "_blank")
                .focus();
        },
        pasteData() {
            navigator.clipboard
                .readText()
                .then((text) => {
                    this.team.users = JSON.parse(text);
                    this.showMessage(this.$t("form.message"), this.$t("form.paste_success"));
                })
                .catch((err) => {
                    this.showFailMessage(this.$t("form.message"), this.$t("form.paste_fail"));
                });
        },
        selectedItem(data) {
            this.userSelected = data;

            this.userSelected.forEach((u) => {
                //  var isnew = this.users.filter(item=>{
                //      return (item.id == u.id)
                //  });
                //  if(isnew == ''){

                let user = { ...u };
                user.pivot = {
                    step: 1,
                    level: 1,
                    team_id: this.team.id,
                    user_id: user.id,
                };
                // this.saveConfigTeam(this.team.id,user.id,1);
                this.team.users.push(user);
                //  }
            });
        },
        //  updateLevel(event,userId,teamId){
        //      this.saveConfigTeam(teamId,userId,event.target.value);
        //      console.log(event.target.value);
        //  },
        detachUser(item, index) {
            this.$bvModal
                .msgBoxConfirm(this.$t("form.confirm_delete") + "?", {
                    title: "",
                    size: "sm",
                    buttonSize: "sm",
                    okVariant: "danger",
                    okTitle: "Ok",
                    cancelTitle: "Cancel",
                    footerClass: "p-2",
                    centered: true,
                })
                .then((value) => {
                    if (value) {
                        this.team.users_del.push({ ...item });
                        this.team.users.splice(index, 1);
                    }
                });
        },
        updateTreeLevel(newLevel) {
            this.team.users = this.team.users;

            console.log(newLevel);
            //console.log(this.team.users);
        },
        addNewUserAbove(item, index) {
            let currentIndex = index;
            let user = {
                id: null,
                pivot: {
                    step: item.pivot.step,
                    level: currentIndex + 1,
                    team_id: this.team.id,
                    user_id: null,
                }
            };

            this.team.users.forEach((u) => {
                if (u.pivot.level >= user.pivot.level) {
                    u.pivot.level++;
                }
                currentIndex++;
            });

            this.team.users.splice(index, 0, user);
        },
        addNewUserBelow(item, index) {
            let currentIndex = index;
            let user = {
                id: null,
                pivot: {
                    step: item.pivot.step,
                    level: currentIndex + 2,
                    team_id: this.team.id,
                    user_id: 0,
                }
            };

            console.log(user);

            this.team.users.forEach((u) => {
                if (u.pivot.level >= user.pivot.level) {
                    u.pivot.level++;
                }
                currentIndex++;
            });

            this.team.users.splice(index + 1, 0, user);
        },
        saveConfigTeam() {
            let notValid = false;

            let checkDuplicateID = [];
            this.team.users.forEach((u) => {
                if (u.id == 0) {
                    notValid = true;
                    toastr.warning('Vui lòng cấu hình tất cả ID', this.$t("form.message"));
                }
                if (checkDuplicateID.includes(u.id)) {
                    notValid = true;
                    toastr.warning('Trùng người duyệt', this.$t("form.message"));
                }
                checkDuplicateID.push(u.id);
            });

            if (!notValid) {
                fetch(this.page_url_assign_user, {
                    method: "post",
                    body: JSON.stringify({ team: this.team }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        toastr.options = {
                            positionClass: "toast-bottom-right",
                        };
                        toastr.success(this.$t("form.update_sucess"), this.$t("form.message"));
                    })
                    .catch((err) => console.log(err));
            }

        },
    },
};
</script>

<style>
</style>
