<template>
  <div>
    <form @submit.prevent="AddDocument">
      <div class="content-header">
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
              <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <h5 class="m-0 text-dark">
                    <i class="nav-icon fas fa-file-contract"></i>
                    <a :href="getUrlBack">{{ $t(pre_title) }}</a>
                  </h5>
                </li>

                <li class="breadcrumb-item active">
                  <span v-if="edit">{{ $t("form.edit") }}</span>
                  <span v-else>{{ $t("form.create") }}</span>
                </li>
              </ol>
            </div>
            <div class="col-md-6">
              <div class="float-sm-right" style="margin-top: -5px">
                <a :href="getUrlBack" class="btn btn-default">{{ $t("form.cancel") }}</a>

                <button type="submit" :disabled="loading" name="0" class="btn btn-primary">
                  {{ $t("form.save") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div v-if="hasAnyError > 0">
            <div class="alert alert-warning">
              <button type="button" class="close" @click.prevent="clearAllError()">
                <i class="ace-icon fa fa-times"></i>
              </button>
              <ul>
                <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
              </ul>
            </div>
          </div>
          <b-overlay :show="loading" rounded="sm">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <div class="col-sm-12  text-md-center">
                        <span>
                          <strong>{{ workflow.description }}</strong>
                        </span>
                      </div>
                    </div>

                    <div class="form-group row" v-if="document.serial_num != ''">
                      <label for="" class="col-form-label-sm col-sm-2 col-form-label text-md-right">{{
                          $t("form.document_num")
                      }}</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control form-control-sm" v-model="document.serial_num"
                          readonly />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">{{
                          $t("form.title")
                      }}<small class="text-red">( * )</small></label>
                      <div class="col-sm-8">
                        <input type="text" v-model="document.title" class="form-control form-control-sm"
                          v-bind:class="hasError('title') ? 'is-invalid' : ''" name="title" id="title" :required="true"
                          maxlength="150" />
                        <span v-if="hasError('title')" class="invalid-feedback" role="alert">
                          <strong>{{ getError("title") }}</strong>
                        </span>
                      </div>
                    </div>

                    <data-object-control :workflow_id="wlworkflow_id" :controls="this.controls" :users="users"
                      :tree_users="tree_users" :editmode="true" :canedit="true" :control_width="8" :text_width="2">
                    </data-object-control>

                    <div class="form-group row" v-if="workflow.default_group == null">
                      <label for="group_id" class="col-form-label-sm col-sm-2 text-md-right">{{ $t("form.group")
                      }}<small class="text-red">( * )</small></label>
                      <div class="col-sm-3">
                        <select name="group_id" id="group_id" v-model="document.group_id"
                          v-bind:class="hasError('group_id') ? 'is-invalid' : ''" @click="clearError($event)"
                          @change="clearError($event)" class="form-control form-control-sm">
                          <option v-for="group in group_filter" v-bind:key="group.id" v-bind:value="group.id">
                            {{ group.company_id }}-{{ group.name }}
                          </option>
                        </select>

                        <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                          <strong>{{ getError("group_id") }}</strong>
                        </span>
                      </div>
                      <label for="group_id" class="col-form-label-sm col-sm-2 text-md-right">{{ $t("form.viewers")
                      }}</label>
                      <div class="col-sm-3">
                        <treeselect placeholder="" :disable-branch-nodes="true" :multiple="true"
                          v-model="document.shared_groups" :options="tree_groups" />

                        <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                          <strong>{{ getError("group_id") }}</strong>
                        </span>
                      </div>
                    </div>

                    <approve-add-user v-on:updateUser="updateUser" :team="document.team" :user_id="user_id"
                      eventname="updateUser" :layout="layout" :tree_user="tree_users" :list_user="users"
                      v-if="showControl('add_user')"></approve-add-user>
                  </div>
                </div>
              </div>
            </div>
          </b-overlay>

        </div>
      </div>
    </form>
  </div>
</template>

<script>
import ApproveAddUser from "../../../approve/ApproveAddUser.vue";
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import DataObjectControl from './objects/DataObjectControl.vue';
export default {
  components: { ApproveAddUser, Treeselect, DataObjectControl },
  props: {
    id: String,
    user_id: String,
    doctype: String,
    doctype_id: String,
    pre_title: String,
    layout: Object,
    controls: Array,
    current_workflow_code: "",
    wlworkflow_id: "",
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.getUser();
    this.getTreeUser();
    this.getTreeGroup();
    this.getDocumentData();
    this.getWorkflowData();
  },
  watch: {},
  data() {
    return {
      tree_groups: [],

      workflow: [],
      document: {
        title: "",
        serial_num: "",
        group_id: "",
        attachment_file: [],
        attachment_file_removed: [],
        wlworkflow_type_id: this.current_workflow_code,
        wlworkflow_id: this.wlworkflow_id,
        document_type_id: this.doctype_id,
        team_users: [],
        team: {},
        shareds: [],
        shared_groups: [],
      },

      users: [],
      tree_users: [],
      edit: false,
      errors: {},
      loading: false,

      token: "",
      page_url_users: "api/user/all",
      page_url_group: "/api/category/groups",
    };
  },

  methods: {
    getTreeGroup() {
      var page_url = this.page_url_group + "?type=tree_combobox";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          this.tree_groups = res.data;
        })
        .catch((err) => console.log(err));
    },

    //sự kiện add user
    updateUser(data) {
      this.document.team_users = [...data];
    },
    getDocumentData() {
      if (this.id != "") {
        this.loading = true;

        var apiAddress = "/api/works/workflows/" + this.id + "/edit";
        fetch(apiAddress, { headers: { Authorization: this.token } })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              this.document = res.data;
              this.document.shared_groups = [];
              this.document.shareds.forEach((shared) => {
                if (shared.type == 0 && shared.object_id) {
                  this.document.shared_groups.push(shared.object_id);
                }
              });
              this.document.attachment_file_removed = [];
              this.edit = true;
            }
            else {
              this.errors = res.errors;
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
            console.log(err);
          });
      }
    },
    getWorkflowData() {
      this.loading = true;

      var apiAddress = "/api/works/workflowsdefine/" + this.document.wlworkflow_id;
      fetch(apiAddress, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.workflow = res.data;
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          console.log(err);
        });
    },
    getTreeUser() {
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
    getUser() {
      var page_url = this.page_url_users;
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
    AddDocument() {
      //this.document.saveandsend = $test.submitter.name;
      this.document.controls = this.controls;

      this.loading = true;
      var apiAddress = "/api/works/workflows";
      if (this.edit == false) {
        fetch(apiAddress, {
          method: "post",
          body: JSON.stringify(this.document),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              toastr.success("", this.$t("form.save_success"));

              window.location.href = "/works/list/" + this.current_workflow_code + "?type=view&id=" + res.data.id;//"/works/view/" + data.data.id;
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Tạo mới chứng từ bị lỗi");
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
          });
      }
      else { //update
        fetch(apiAddress + '/' + this.document.id, {
          method: "PUT",
          body: JSON.stringify(this.document),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {

            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              toastr.success(this.$t("form.update_success"), "");

              window.location.href = "/works/list/" + this.current_workflow_code + "?type=view&id=" + res.data.id;//"/works/view/" + data.data.id;
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Cập nhật chứng từ bị lỗi");
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
          });
      }
    },

    showControl(fieldName) {
      if (fieldName in this.layout) {
        return this.layout[fieldName]["isVisible"];
      }
      return false;
    },
    clearAllError() {
      this.errors = {};
    },
    hasError(fieldName) {
      return fieldName in this.errors;
    },
    getError(fieldName) {
      return this.errors[fieldName][0];
    },
    clearError(event) {
      Vue.delete(this.errors, event.target.name);
    },
  },
  computed: {
    getUrlBack() {
      return "works/list/" + this.current_workflow_code;
    },
    group_filter() {
      var list = [];
      let user = this.users.find((x) => x.id == this.user_id);
      if (user) {
        list = user.groups;
      }
      return list;
    },
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>
