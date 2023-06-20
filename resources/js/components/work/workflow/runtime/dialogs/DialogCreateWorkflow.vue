<template>
  <div class="modal fade" id="DialogCreateWorkflow" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <b-overlay :show="is_busy" rounded="sm">
        <div class="modal-content ">
          <form @submit.prevent="createWorkflow" @keydown="clearError">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">
                <span> {{ workflow.description }} </span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div v-if="!is_busy" class="modal-body">
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
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">

                  <div class="form-group row" v-if="document.serial_num != ''">
                    <label for="" class="col-form-label-sm col-sm-3 col-form-label text-md-right">{{
                        $t("form.document_num")
                    }}</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control form-control-sm" v-model="document.serial_num" readonly />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="title" class="col-form-label-sm col-sm-3 col-form-label text-md-right">{{
                        $t("form.title")
                    }}<small class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input type="text" v-model="document.title" class="form-control form-control-sm"
                        v-bind:class="hasError('title') ? 'is-invalid' : ''" name="title" id="title" :required="true"
                        maxlength="150" />
                      <span v-if="hasError('title')" class="invalid-feedback" role="alert">
                        <strong>{{ getError("title") }}</strong>
                      </span>
                    </div>
                  </div>

                  <data-object-control :workflow_id="document.wlworkflow_id" :controls="create_template.controls"
                    :users="users" :tree_users="tree_users" :editmode="true" :canedit="true" :control_width="7"
                    :text_width="3">
                  </data-object-control>

                  <div class="form-group row" v-if="workflow.default_group == null">
                    <label for="group_id" class="col-form-label-sm col-sm-3 text-md-right">{{ $t("form.group")
                    }}<small class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <treeselect placeholder="" :disable-branch-nodes="true" :multiple="false"
                        v-model="document.group_id" :options="tree_groups" />

                      <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                        <strong>{{ getError("group_id") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="workflow.default_group == null">
                    <label for="groups_shared" class="col-form-label-sm col-sm-3 text-md-right">{{ $t("form.viewers")
                    }}</label>
                    <div class="col-sm-7">
                      <treeselect id="groups_shared" placeholder="" :disable-branch-nodes="true" :multiple="true"
                        v-model="document.shared_groups" :options="tree_groups" />

                      <span v-if="hasError('groups_shared')" class="invalid-feedback" role="alert">
                        <strong>{{ getError("groups_shared") }}</strong>
                      </span>
                    </div>
                  </div>

                  <approve-workflow-add-user v-if="showControl('add_user')" v-on:updateUser="updateUser" :team="document.team"
                    :user_id="current_user.id" eventname="updateUser" :layout="create_template.layouts" :tree_user="tree_users"
                    :list_user="users"></approve-workflow-add-user>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary mr-auto">
                <i class="fas fa-save"></i> Lưu
              </button>

              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t('form.close') }}
              </button>

            </div>
          </form>

        </div>
      </b-overlay>
    </div>
  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import ApproveWorkflowAddUser from "../../../../approve/ApproveWorkflowAddUser.vue";
import DataObjectControl from '../objects/DataObjectControl.vue';
export default {
  components: {
    ApproveWorkflowAddUser,
    Treeselect,
    DataObjectControl
  },
  props: {
    document_id: Number,
    current_workflow_code: String,
    wlworkflow_id: Number,
  },
  watch: {
    wlworkflow_id() {
      this.reset();
      this.getCreateTemplate();
      this.getWorkflowData();
    },
    document_id() {
      this.reset();
      this.getDocumentData();
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.current_user = window.Laravel.current_user;
    this.getUser();
    this.getTreeUser();
    this.getTreeGroup();
  },
  data() {
    return {
      tree_groups: [],
      create_template: [],
      workflow: [],

      document: {
        title: "",
        serial_num: "",
        group_id: null,
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
      errors: {},
      loading: false,

      token: "",
      page_url_users: "api/user/all",
      page_url_group: "/api/category/groups",
    };
  },
  methods: {
    getCreateTemplate() {
      let template_data = {
        type: this.document_id ? 'edit' : 'add',
        id: this.document_id,
        current_workflow_code: this.current_workflow_code,
        wlid: this.document.wlworkflow_id
      };

      this.loading = true;
      var page_url = "/api/works/get-create-template";
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify(template_data),
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          this.create_template = data.data;
          this.loading = false;
        })
        .catch(err => { 
          this.loading = false;
          console.log(err);
        });

    },
    reset() {
      this.create_template = [];
      this.workflow = [];
      this.document = {
        title: "",
        serial_num: "",
        group_id: null,
        attachment_file: [],
        attachment_file_removed: [],
        wlworkflow_type_id: this.current_workflow_code,
        wlworkflow_id: this.wlworkflow_id,
        document_type_id: null,
        team_users: [],
        team: {},
        shareds: [],
        shared_groups: [],
      };
    },

    getTreeGroup() {
      var page_url = this.page_url_group + "?type=tree_combobox&self=true";
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
      if (this.document_id != null) {
        this.loading = true;

        var apiAddress = "/api/works/workflows/" + this.document_id + "/edit";

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

              this.getCreateTemplate();
              this.getWorkflowData();
            }
            else {
              this.errors = res.errors;
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
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
    createWorkflow() {
      //this.document.saveandsend = $test.submitter.name;
      this.document.controls = this.create_template.controls;
      this.document.document_type_id = this.create_template.doctype_id;

      this.loading = true;
      var apiAddress = "/api/works/workflows";
      if (!this.document_id) {
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
              toastr.success("Tạo yêu cầu thành công", "Thông báo");
              $('#DialogCreateWorkflow').modal('hide');
              this.$emit('onUpdateWorkflow', { ...res.data });
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Tạo mới yêu cầu bị lỗi");
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
              $('#DialogCreateWorkflow').modal('hide');
              this.$emit('onUpdateWorkflow', { ...res.data });
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
      if (this.create_template && this.create_template.layout) {
        if (fieldName in this.create_template.layout) {
          return this.create_template.layout[fieldName]["isVisible"];
        }
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
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
    is_busy() {
      return this.loading || !this.create_template.controls;
    }
  },
}
</script>

<style>
.form-group {
  margin-bottom: 5px !important;
}
</style>