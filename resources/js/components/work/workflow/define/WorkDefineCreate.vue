<template>
  <div>
    <!-- <form @submit.prevent="AddWorkflow"  @keydown.enter.prevent="clearError" > -->
    <div class="content-header ">
      <div class="container-fluid ml-0">
        <div class="row">
          <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
            <ol class="breadcrumb ">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/workdefines">{{
                $t(pre_title)
                }}</a> </h5>
              </li>

              <li class="breadcrumb-item active">
                <span v-if="edit">{{ $t('form.edit') }}</span>
                <span v-else>{{ $t('form.create') }}</span>

              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <div class="float-sm-right " style="margin-top:-5px; ">
              <!-- <a href="/workdefines" class="btn btn-default ">{{ $t('form.cancel')}}</a>
                        <button type="submit"   :disabled="loading"  class="btn btn-primary"> {{ $t('form.save')}}</button> -->
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
        <div class="card card-default">
          <b-overlay :show="loading" rounded="sm">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">

                  </div>

                </div>

              </div>
              <div class="row mt-1">
                <div class="col-md-12">

                  <b-card no-body>
                    <b-tabs card small>
                      <b-tab :title="$t('form.general_information')" active>

                        <div class="row mt-2 mb-2">
                          <div class="col-md-12">
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.name')
                              }}</label>
                              <div class="col-sm-7">
                                <input class="form-control form-control-sm" type="text" v-model="workflow.name"
                                  required>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{
                              $t('form.group')
                              }}</label>
                              <div class="col-sm-7">
                                <select v-model="workflow.wlworkflow_type_id" class="form-control form-control-sm">
                                  <option value=""></option>
                                  <option v-for="type in wlworkflow_types" v-bind:key="type.id" :value="type.id">{{
                                  type.name }}</option>
                                </select>

                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-form-label-sm col-sm-5 col-form-label" title="Group" for="">Phạm vi sử
                                dụng</label>
                              <div class="col-md-7">
                                <treeselect placeholder="Tất cả" :multiple="true" v-model="workflow.scope"
                                  :options="tree_groups" />
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Quản trị</label>
                              <div class="col-sm-7">
                                <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.admin_values"
                                  :disable-branch-nodes="true" :multiple="true" :options="tree_users"></treeselect>

                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Thành viên</label>
                              <div class="col-sm-7">
                                <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.member_values"
                                  :disable-branch-nodes="true" :multiple="true" :options="tree_users"></treeselect>

                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Theo dõi</label>
                              <div class="col-sm-7">
                                <treeselect style="font-size: 15px;" placeholder="" v-model="workflow.follow_values"
                                  :disable-branch-nodes="true" :multiple="true" :options="tree_users"></treeselect>

                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{
                              $t('form.description')
                              }}</label>
                              <div class="col-sm-7">
                                <textarea v-model="workflow.description" class="form-control form-control-sm">

                                 </textarea>

                              </div>
                            </div>
                          </div>
                        </div>
                      </b-tab>
                      <b-tab title="Cấu hình giai đoạn">
                        <b-card-text>
                          <phase-list v-bind:workflow_id="id" :workflow_structure="workflow_structure"></phase-list>
                        </b-card-text>
                      </b-tab>
                      <b-tab title="Trường tùy chỉnh khởi tạo">
                        <b-card-text>
                          <!-- <div class="row mt-2">
                          <data-object-list v-bind:unique_id="'modal_Workflow' + workflow.id" title="Thêm tùy chỉnh"
                            :wlworkflow_id="workflow.id" :phase_id="null"></data-object-list>
                        </div> -->
                          <data-object-new-list v-if="workflow" v-bind:unique_id="'modal_Workflow' + workflow.id"
                            title="Thêm tùy chỉnh" :wlworkflow_id="workflow.id" :phase_id="null"></data-object-new-list>
                        </b-card-text>
                      </b-tab>
                      <b-tab title="Cấu hình phê duyệt">
                        <b-card-text>

                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right">Cây duyệt đầu</label>
                            <div class="col-sm-8">
                              <select class="form-control form-control-sm" v-model="workflow.sub_approve_type">
                                <option value='0'>Không có cây duyệt</option>
                                <option value='1'>Cây duyệt theo nhóm người tạo</option>
                                <option value='2'>Cây duyệt theo nhóm cấu hình sẵn</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row" v-show="workflow.sub_approve_type == 2">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right">Nhóm duyệt mặc định</label>
                            <div class="col-sm-8">
                              <treeselect style="font-size: 15px;" placeholder="Chọn nhóm duyệt mặc định"
                                v-model="workflow.sub_approve_value" :disable-branch-nodes="true" :multiple="false"
                                :options="tree_groups"></treeselect>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right"></label>
                            <div class="col-sm-8"> </div>
                          </div>

                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right">Cây duyệt sau</label>
                            <div class="col-sm-8">
                              <select class="form-control form-control-sm" v-model="workflow.approve_type"
                                name="approve_type" id="active">
                                <option value='0'>Không có cây duyệt</option>
                                <option value='1'>Cây duyệt người tạo chọn</option>
                                <option value='2'>Cây duyệt theo workflow cấu hình sẵn</option>
                              </select>
                            </div>
                          </div>
                          <approve-add-user v-show="workflow.approve_type == 2" v-on:updateUser="updateUser"
                            :team="workflow.team" eventname="updateUser" :layout="layout" :tree_user="tree_users"
                            :list_user="users" :user_id="'0'"></approve-add-user>

                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right"></label>
                            <div class="col-sm-8"> </div>
                          </div>
                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right">Nhóm mặc định</label>
                            <div class="col-sm-8">
                              <treeselect style="font-size: 15px;" placeholder="Chọn nhóm mặc định"
                                v-model="workflow.default_group" :disable-branch-nodes="true" :multiple="false"
                                :options="tree_groups"></treeselect>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="" class="col-form-label-sm col-sm-2 text-md-right"></label>
                            <div class="col-sm-8 text-md-right">
                              <button class="btn btn-primary" @click.prevent="saveApprove()">Lưu</button>

                            </div>
                          </div>
                        </b-card-text>
                      </b-tab>
                      <b-tab title="Cấu hình nâng cao">
                        <b-card-text>
                          <dynamic-config-list v-if="workflow" :wlworkflow_id="workflow.id"></dynamic-config-list>
                        </b-card-text>
                      </b-tab>
                      <b-tab title="Cấu trúc workflow">
                        <div class="form-group row">
                          <div class="col-sm-10">
                            <treeselect v-model="jsonPickerPath" placeholder="Chọn field.."
                              :options="convertJsonToTree(this.workflow_structure)" :flatten-search-results="true">
                            </treeselect>
                          </div>
                          <div class="col-sm-2 text-md-right">
                            <button class="btn btn-primary" @click.prevent="getDataFromPath(jsonPickerPath)">Get data</button>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{ jsonPickerPath }}</label>
                          <div class="col-sm-7">
                            {{ structure_result }}

                          </div>
                        </div>

                      </b-tab>
                    </b-tabs>
                  </b-card>

                </div>
              </div>
            </div>
          </b-overlay>
        </div>
      </div>
    </div>

    <!-- </form> -->


    <!-- Modal dialog -->

    <!-- Modal dialog add note -->



  </div>

</template>

<script>
import ListChoose from '../../../shared/ListChoose.vue';
// import dialogContractSearch from "./DialogSearchContract.vue";
// import dialogPaycatsetSearch from "./DialogSearchPaycatset.vue";
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import PhaseList from './PhaseList.vue';
import DataObjectList from './DataObjectList.vue';
import DataObjectNewList from './DataObjectNewList.vue';
import DynamicConfigList from './DynamicConfigList.vue';

//import DataObjectListOther from './DataObjectListOther.vue';

export default {
  components: {
    ListChoose,
    Treeselect,
    PhaseList,
    DataObjectList,
    DataObjectNewList,
    DynamicConfigList,
    //DataObjectListOther,
  },
  props: {
    id: Number,
    user_id: String,
    doctype: String,
    doctype_id: String,
    pre_title: String,
    layout: Object,
  },

  data() {
    return {
      workflow: {
        id: null,
        name: "",
        description: "",
        wlworkflow_id: "",
        wlworkflow_type: {},
        admin_values: [],
        member_values: [],
        follow_values: [],
        wlphase: [],
        wlphase_del: [],
        document_types: [],
        type: "0",
        team: {},
        team_users: []
      },
      wlphase: {
        name: "",
        time_execution: "",
        description: "",
      },
      structure_result: "",
      workflow_structure: {},
      document_types: [],
      wlworkflow_types: [],
      users: [],
      tree_users: [],
      tree_groups: [],
      errors: {},
      loading: false,
      edit_workflow: false,
      token: "",
      edit: false,
      tabIndex: 0,
      page_url_workflow_types: "/api/category/workflowtypes",
      page_url_users: "api/user/all",
      page_url_groups: "/api/category/groups",
      page_url_document_type: "/api/category/documenttypes",
      jsonPickerPath: ''
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.getUsers();
    this.getGroupTree();
    this.getDocumentType();
    this.getWorkflowType();
    this.getWorkflowType();
    this.getUserTree();
    this.fetWorkflow();
    this.fetWorkflowStructure();

  },

  methods: {
    saveApprove() {
      this.loading = true;
      var apiAddress = "api/works/update-workflow-approve-setting";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify(
          {
            workflowid: this.workflow.id,
            approve_type: this.workflow.approve_type,
            approve_team: this.workflow.approve_team,
            teamusers: this.workflow.team_users,
            sub_approve_type: this.workflow.sub_approve_type,
            sub_approve_value: this.workflow.sub_approve_value,
            default_group: this.workflow.default_group
          }
        ),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest"
        }
      })
        .then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success === 1) {
            toastr.success(this.$t('form.save_success'), "");

            this.workflow.team_id = res.data;
            this.refresh();
          }
          else {
            this.errors = res.errors;
            toastr.warning(this.$t("form.update_error"), "");
          }

          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });

    },
    updateUser(data) {
      this.workflow.team_users = data;
    },
    getGroupTree() {
      var page_url = this.page_url_groups + "?type=tree_combobox";

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
    getUsers() {
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
    AddWorkflow() {

    },
    AddPhase() {
      if (this.wlphase.name != '') {

        if (this.workflow.wlphase == null)
          this.workflow.wlphase = [];
        this.workflow.wlphase.push({ ...this.wlphase })
        this.wlphase.name = "";
      }

    },
    ClickPhase(item, index) {
      this.wlphase = this.workflow.wlphase[index]
    },
    deletePhase(item, index) {


      if (confirm(this.$t('form.confirm_delete') + '?')) {

        if (this.workflow.wlphase == null)
          this.workflow.wlphase = [];

        this.workflow.wlphase_del.push({ ...item });
        this.workflow.wlphase.splice(index, 1);
      }
    },
    getDocumentType() {
      var page_url = this.page_url_document_type;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.document_types = [];
          this.document_types = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getWorkflowType() {
      var page_url = this.page_url_workflow_types;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.wlworkflow_types = [];
          this.wlworkflow_types = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetWorkflow() {
      var apiAddress = "/api/works/workflowsdefine/" + this.id + "/edit";
      fetch(apiAddress, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.workflow = res.data;

            this.workflow.wlphase_del = [];
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
            this.workflow.document_types = [];
            this.workflow.wldoctypes.forEach(element => {
              this.workflow.document_types.push({ "id": element.document_type_id, "label": element.document_type_id });
            });
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetWorkflowStructure() {
      var apiAddress = "/api/works/get-workflow-structure?workflow_id=" + this.id;
      fetch(apiAddress, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.workflow_structure = res.workflow;
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch(err => {
          console.log(err);
        });
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
    // Nhập trên lưới
    changeGridEdit(event) {
      let span = $(event.target).children('span');
      $(span).hide();
      console.log($(event.target).children('input').show());

    },
    changeGridView(event) {
      console.log($(event.target).hide());
      console.log($(event.target.parentElement).children('span').show());
    },
    changeGridViewToEdit(event) {
      console.log($(event.target).hide());
      console.log($(event.target.parentElement).children('input').show());
    },

    convertJsonToTree(container, parentKey = 'workflow') {
      var data = [];
      if (container != null) {
        for (var key in container) {
          var id = isNaN(key) ? parentKey + '.' + key : parentKey + '[' + key + ']';
          var value = container[key];
          var label = isNaN(key) ? parentKey + '.' + key : parentKey + '[' + key + ']';

          var output = {};
          if (value !== null && typeof (value) == "object") {
            output = {
              id: id,
              label: label,
              children: this.convertJsonToTree(value, id)
            };
          } else {
            output = {
              id: id,
              label: label
            };
          }
          data.push(output);
        }
      }

      return data;
    },

    getDataFromPath(path) {
      const params = new URLSearchParams({
        workflow_id: this.workflow.id,
        value_path: path,
      });

      var apiAddress = "/api/works/get-workflow-value" + "?" + params.toString();
      fetch(apiAddress, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.structure_result = res.value ?? 'null';
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch(err => {
          console.log(err);
        });
    },

    getJsonValue(jsonData, jsonPath) {
      var jsonPaths = jsonPath.split(".").splice(1);
      var currentIndex = 0;

      var currentValue = jsonData;
      while (true) {
        var currentJsonName = jsonPaths[currentIndex];

        var currentValue = currentValue[currentJsonName];

        // Đã duyệt xong
        if (currentIndex >= jsonPaths.length - 1) {
          return currentValue;
        }
        else if (currentValue !== null && typeof (currentValue) == "object") {
          currentIndex++;
        }
        else return null;
      }
    },
  },
  computed: {


    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },

    // tree_workflow() {
    //   var list = [];
    //   this.control.items.forEach((element, index) => {
    //     let item = [];
    //     item.id = element.id;
    //     if (this.control.type == 5) {
    //       item.label = (index + 1) + '. ' + element.value;
    //     }
    //     else {
    //       item.label = element.value;
    //     }

    //     list.push(item);
    //   });
    //   return list;
    // },
  }

}

</script>

<style lang="scss" scoped >
.form-group {
  margin-bottom: 5px !important;
}

.itemfile:hover {

  cursor: pointer;
}

.itemfile span {
  display: none;
}

.itemfile:hover span {

  display: inline-block;
  cursor: pointer;
}

/* fix select css bị thiếu */
.select2-selection--single {
  height: 38px !important;
}

a#thongtinchung-tab.nav-link.active {
  border-left: 1px solid gray;
}
</style>
