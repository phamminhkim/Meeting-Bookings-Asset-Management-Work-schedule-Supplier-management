<template>
  <div>
    <div class="content-header">
      <div class="container-fluid ml-0">

        <div class="row">
          <div class="col-md-6">
            <!-- <work-breadcrumbs></work-breadcrumbs> -->
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->


            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark">
                  <i class="nav-icon fas fa-file-contract"></i>
                  <a :href="getUrlBack">{{ $t(pre_title) }}</a>
                </h5>
              </li>

              <li class="breadcrumb-item active">

                <span>{{ $t("form.detail") }}</span>
              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <!-- <div class="float-sm-left">
              <button v-if="true" class="btn btn-danger" @click.prevent="configWorkflow()">
                <i class="fas fa-user-cog"></i> Cấu hình
              </button>
            </div> -->
            <div class="float-sm-right" style="margin-top: -5px">
              <button @click.prevent="showFinishedPhase()" class="btn btn-primary" title="Hoàn thành giai đoạn"
                v-if="document && document.is_ready_to_complete_phase && document.status != 3">
                Hoàn thành <i class="fas fa-angle-double-right"></i>
              </button>
              <button v-if="document.editable" class="btn btn-default" @click.prevent="editDocument()">
                <i class="fas fa-edit"></i>{{ $t("form.edit") }}
              </button>
              <button v-if="document.printable" class="btn btn-default" @click.prevent="print()">
                <i class="fas fa-print"></i>
              </button>
              <a v-if="document.configurable" title="Cấu hình" class="btn btn-default" target="_blank"
                :href="configWorkflow()">
                <i class="fas fa-user-cog"></i>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="row">

      <div class="col-sm-8">

        <work-breadcrumbs :document="document" :wlphases="wlphases"></work-breadcrumbs>

      </div><!-- /.col -->
    </div>
    <!-- ----------------- -->
    <div class="row" v-if="this.document">
      <div class="col-md-8">
        <div class="card">
          <div class="">
            <div class="p-2"
              :style="document.currentPhase.index == 999 ? 'text-align:center;color:white;background-color:#28a745;' : 'text-align:center;color:white;background-color:#28659C;'">
              {{ pre_title }} - {{ document.currentPhase.name }}</div>
            <div class="d-flex justify-content-between ml-4 mr-2" style="border-bottom: 1px solid #cee2ee">
              <div>
                <span class="mute small"><i>{{ $t("form.create_by") }}:
                    <strong v-if="document.user">{{ document.user.name }} - {{ document.user.username }}
                    </strong>
                  </i></span><br />
                <span class="mute small"><i>{{ $t("form.company") }}:
                    <strong v-if="document.user && document.company">
                      {{ document.company.name }}</strong></i></span>
              </div>
              <div>
                <span class="mute small">
                  <i>
                    {{ $t("form.serial_num") }} :<strong>
                      {{ document.serial_num }}
                    </strong></i></span><br />
                <span class="mute small"><i>{{ $t("form.send_date") }}:
                    <strong class="ml-4">{{ document.send_date | formatDate }}</strong></i></span>
              </div>
            </div>
          </div>

          <b-overlay :show="loading" rounded="sm">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.title") }}:</span>
                    <div class="col-sm-9">
                      <label class="col-form-label-sm">{{ document.title }}</label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <!-- <span class="col-form-label-sm col-sm-2 ">{{$t('form.title')}}:</span> -->
                    <div class="col-sm-12">
                      <data-object-control :workflow_id="document.wlworkflow_id" :controls="document.controls"
                        :users="users" :tree_users="tree_users" :editmode="false" :control_width="9" :text_width="3">
                      </data-object-control>
                    </div>
                  </div>

                  <div class="form-group row" v-if="showControl('doc_type_id')">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.doc_type") }}:</span>
                    <div class="col-sm-10">
                      <label class="col-form-label-sm" v-if="document.doc_type">{{
                      document.doc_type.name
                      }}</label>
                    </div>
                  </div>
                  <div class="form-group row" v-if="false">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.content") }}:</span>
                    <div class="col-sm-10">
                      <p class="col-form-label-sm" v-html="document.content"></p>
                    </div>
                  </div>

                  <div class="form-group row" v-if="false">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.attached_file") }}:</span>
                    <div class="col-sm-10">
                      <div class="d-flex justify-content-between">
                        <ul class="list-unstyled">
                          <li v-for="(file, index) in document.attachment_file" v-bind:key="index" class="itemfile">
                            <div class="btn-group">
                              <button type="button" class="btn btn-default btn-xs"
                                @click.prevent="downloadFile(file.id, file.name)">
                                {{ file.name }}
                              </button>

                              <button type="button" v-if="file.id" class="btn btn-default btn-xs"
                                @click.prevent="downloadFile(file.id, file.name)">
                                <i class="fas fa-download"></i>
                              </button>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row" v-if="document && document.shareds && document.shareds.length > 0">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.viewers") }}
                    </span>
                    <div class="col-sm-9">
                      <p class="col-form-label-sm">
                        <span v-for="(shared, index) in document.shareds" v-bind:key="index">
                          <b-badge v-if="shared.group">{{ shared.group.company_id }}-{{
                          shared.group.name
                          }}</b-badge>
                        </span>
                      </p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-3">{{ $t("form.shared") }} </span>
                    <div class="col-sm-9">
                      <shared-list v-bind:object="document" :items_props="document.shareds"
                        v-on:sharedAction="sharedAction" :type="'WORKFLOW'"></shared-list>
                    </div>
                  </div>
                  <b-tabs card align="center" small v-model="activetab">
                    <b-tab :key="'jobreport'" :title="!this.canViewJobTab ? 'Tiến độ quy trình' : 'Tiến độ giai đoạn'"
                      v-show="this.canViewJobTab" :active="this.canViewJobTab" style="padding-top: 0px">

                      <div class="card" style="box-shadow: unset;">
                        <div class="card-header" style="padding-bottom: 0.8rem">
                          <h6 class="card-title">
                            <b-dropdown v-show="this.document.currentPhase.allow_send_response" right
                              variant="outline-primary" block>
                              <template #button-content>
                                <i class="fas fa-comment"></i> <b>Tương tác</b>
                              </template>

                              <div v-for="(item, index) in this.response_job_types" v-bind:key="index">
                                <b-dropdown-item @click.prevent="showAddResponseDialog(item.id)">
                                  <i :class="item.icon"></i> <strong>{{ item.name }}</strong>
                                </b-dropdown-item>
                              </div>
                            </b-dropdown>
                          </h6>
                          <div class="card-tools">

                            <b-dropdown v-show="this.document.currentPhase.allow_add_job" right
                              variant="outline-success" block>
                              <template #button-content>
                                <i class="fas fa-plus"></i> <b>Tạo yêu cầu</b>
                              </template>

                              <div v-for="(item, index) in this.action_job_types" v-bind:key="index">
                                <b-dropdown-item @click.prevent="showAddJobDialog(item.id)">
                                  <i :class="item.icon"></i> <strong>{{ item.name }}</strong>
                                </b-dropdown-item>
                              </div>
                            </b-dropdown>
                          </div>
                        </div>
                        <div class="card-body" style="padding: 0px">
                          <phase-list-view v-if="!this.canViewJobTab" :document="document" :phases="wlphases"
                            :current_phaseid="document.currentPhase.id" :users="users" :tree_users="tree_users">
                          </phase-list-view>

                          <div v-for="(item, index) in this.viewable_jobs" v-bind:key="index">
                            <data-object-job :tree_users="tree_users" :job="item" :workflow_id="document.workflow_id"
                              :users="users" v-on:interactJob="interactJob" :readonly="false">
                            </data-object-job>
                          </div>
                        </div>

                      </div>


                    </b-tab>
                    <b-tab :key="'generalinfo'" title="Tiến độ quy trình" v-if="this.document && this.canViewJobTab">
                      <phase-list-view :document="document" :phases="wlphases"
                        :current_phaseid="document.currentPhase.id" :users="users" :tree_users="tree_users">
                      </phase-list-view>
                    </b-tab>
                  </b-tabs>


                  <!-- <b-form-group   >
                        <span v-if="document.budget_type == 0" class="col-form-label-sm col-sm-2 "><i class="far fa-check-square"></i> {{ $t('Ngoài / Vượt ngân sách') }}</span>
                        <span v-if="document.budget_type != 0" class="col-form-label-sm col-sm-2 "><i class="far fa-square"></i> {{ $t('Ngoài / Vượt ngân sách') }}</span>
                        <span v-if="document.budget_type == 1" class="col-form-label-sm col-sm-2 "><i class="far fa-check-square"></i> {{ $t('Trong ngân sách') }}</span>
                        <span v-if="document.budget_type != 1" class="col-form-label-sm col-sm-2 "><i class="far fa-square"></i>  {{ $t('Trong ngân sách') }}</span>
                        </b-form-group> -->
                </div>
              </div>
            </div>
          </b-overlay>

        </div>

        <workflow-reminder v-bind:object="document" :items_props="document.reminders"
          v-on:reminderAction="reminderAction" :type="'WORKFLOW'"></workflow-reminder>
        <workflow-timeline :list="document.timelines" :showicon="true"></workflow-timeline>
      </div>

      <div class="col-md-4">
        <approve-workflow v-show="document.has_approve_list" v-bind:object="document" :showstep="'showstep'"
          :type="'WORKFLOW'" :user_id="user_id" v-on:onApproveAgree="onApproveAgree" v-on:onLoadedInfo="onLoadedInfo">
        </approve-workflow>
        <data-object-job-interact-list
          v-show="document.currentPhase.index != -999 && document.currentPhase.index != 999" :loading="loading"
          :tree_users="tree_users" :interactableJobs="interactableJobs" :users="users" v-on:interactJob="interactJob">
        </data-object-job-interact-list>

      </div>
    </div>

    <create-event-dialog :object_id="object_id" v-on:fromReminder="fromReminder" :id="reminder_id" module="WORKFLOW">
    </create-event-dialog>
    <shared-dialog :doc_id="object_id" v-on:fromShared="fromShared" module="WORKFLOW"></shared-dialog>
    <dialog-add-job v-on:updateJob="updateJob" :wljob_id="wljob_id" :phase_id="document.currentPhase.id"
      :tree_users="tree_users" :jobs="document.jobs" :job_type_id="add_job_type_id">
    </dialog-add-job>

    <dialog-add-response v-on:updateJob="updateJob" :wljob_id="wljob_id" :phase_id="document.currentPhase.id"
      :tree_users="tree_users" :jobs="document.jobs" :job_type_id="add_job_type_id">
    </dialog-add-response>
    <dialog-finish-job v-bind:workflow_id="id" :is_completed="isFinishedJob" :wljob="currentInteractingJob"
      :tree_users="tree_users" :users="users" v-on:fromFinishedJob="fromFinishedJob"
      v-on:fromFinishedPhase="fromFinishedPhase"></dialog-finish-job>
    <assign-job-user v-on:fromAssignUser="fromAssignUser" :wljob="currentInteractingJob"
      :document_user_id="document.user_id" :tree_users="jobLimitedTreeUsers"></assign-job-user>
    <unassign-job-user v-on:fromAssignUser="fromAssignUser" :wljob="currentInteractingJob"
      :tree_users="jobLimitedTreeUsers">
    </unassign-job-user>
    <dialog-finish-phase v-on:fromFinishPhase="fromFinishPhase" :workflow_id="id" :phase_id="document.currentPhase.id">
    </dialog-finish-phase>
    <dialog-confirm-job :workflow_id="id" :current_job="currentInteractingJob" :users="users" :tree_users="tree_users"
      :relevant_jobs="relevant_jobs" v-on:onConfirmJob="fromFinishedJob">
    </dialog-confirm-job>

    <dialog-navigate-job :workflow_id="id" :current_job="currentInteractingJob" :users="users" :tree_users="tree_users"
      :navigate_jobs="navigate_jobs" v-on:onNavigatedJobs="fromFinishedJob">
    </dialog-navigate-job>

    <dialog-feedback-approvement v-bind:workflow_id="id" :wljob="currentInteractingJob"
      v-on:fromFinishedJob="fromFinishedJob" v-on:fromFinishedPhase="fromFinishedPhase">
    </dialog-feedback-approvement>
  </div>
</template>

<script>
import WorkflowTimeline from "../../../timeline/WorkflowTimelineList.vue"
import WorkflowReminder from "../../../reminder/WorkflowReminderList.vue"
import DataObjectControl from "./objects/DataObjectControl.vue";
import PhaseListView from "../define/PhaseListView.vue";
import DataObjectJob from "./objects/DataObjectJob.vue";
import DataObjectJobInteractList from "./objects/DataObjectJobInteractList.vue";
import DialogFinishJob from "./dialogs/DialogFinishJob.vue";
import DialogConfirmJob from "./dialogs/DialogConfirmJob.vue";
import DialogNavigateJob from "./dialogs/DialogNavigateJob.vue";
import DialogFeedbackApprovement from "./dialogs/DialogFeedbackApprovement.vue";
import DialogAddJob from "./dialogs/DialogAddJob.vue";
import DialogAddResponse from "./dialogs/DialogAddResponse.vue";
import AssignJobUser from "./dialogs/DialogAssignJobUser.vue";
import UnassignJobUser from "./dialogs/DialogUnassignJobUser.vue";
import DialogFinishPhase from "./dialogs/DialogFinishPhase.vue";
import ApproveWorkflow from '../../../approve/ApproveWorkflow.vue';
import ApproveDialog from '../../../approve/ApproveDialog.vue';
import WorkBreadcrumbs from './WorkBreadcrumbs.vue';
import WorkflowInfo from './WorkflowInfo.vue';

export default {
  components: {
    DataObjectControl,
    PhaseListView,
    DataObjectJob,
    DialogAddJob,
    DialogFinishJob,
    AssignJobUser,
    UnassignJobUser,
    DialogFinishPhase,
    ApproveWorkflow,
    ApproveDialog,
    WorkBreadcrumbs,
    WorkflowInfo,
    DataObjectJobInteractList,
    WorkflowTimeline,
    WorkflowReminder,
    DialogConfirmJob,
    DialogNavigateJob,
    DialogFeedbackApprovement,
    DialogAddResponse
  },
  props: {
    id: Number,
    user_id: String,
    pre_url: "",
    pre_title: "",
    notification_id: String,
    layout: Object,
    current_workflow_code: "",
  },
  data() {
    return {
      document: {
        title: "",
        content: "",
        amount: "",
        budget_type: "0",
        currency: "VND",
        serial_num: "",
        budget_num: "",
        group_id: "",
        payment_type_id: "0",
        document_type_id: this.doctype_id,
        currentPhase: {
          allow_add_job: false,
          allow_send_response: false,
        },
        users: [],
      },
      interacting_job_filter: {
        show_interact: true,
        show_manage: true
      },
      activetab: 0,
      isFinishedJob: false,
      wljob_id: null,
      add_job_type_id: null,
      currentInteractingJob: null,
      jobLimitedTreeUsers: null,
      relevant_jobs: [],
      navigate_jobs: [],
      tree_users: [],
      reminder_id: "",
      wlphases: [],
      users: [],
      action_job_types: [],
      response_job_types: [],
      page_url_job_type: "/api/category/workflowjobtypes",
      object_id: [],
      edit: false,
      errors: {},
      loading: false,
      token: "",
      tabIndex: 0,
      page_url_document_change_phase: "api/works/manual-change-phase",
      page_url_users: "api/user/all",
      page_url_document_print: "/document/print",
      page_url_wljob_selfassignjob: "api/works/take-job",
      page_url_wljob_abandon_job: "api/works/abandon-job",
    };
  },
  methods: {
    fromFinishPhase() {
      window.location.href = "/works/list/" + this.current_workflow_code + "?type=view&id=" + this.id;
    },
    showFinishedPhase() {
      $("#dialogFinishPhase").modal("show");
    },
    fromFinishedPhase(flag) {
      this.document.is_ready_to_complete_phase = flag;
      if (flag) {
        this.showFinishedPhase();
      }
    },
    fromFinishedJob(jobs) {
      jobs.forEach(returnJob => {
        for (let index = 0; index < this.document.jobs.length; index++) {
          const currentJob = this.document.jobs[index];
          if (currentJob.id == returnJob.id) {
            this.document.jobs[index].reports = [...returnJob.reports];
            this.document.jobs[index].actions = returnJob.actions;
            this.document.jobs[index].action_user = returnJob.action_user;
            this.document.jobs[index].assign_user = returnJob.assign_user;
            this.document.jobs[index].assigning_user = returnJob.assigning_user;
            this.document.jobs[index].undone_jobs = [...returnJob.undone_jobs];
            this.document.jobs[index].dependencies = [...returnJob.dependencies];
            this.document.jobs[index].details = [...returnJob.details];
            this.document.jobs[index].date_finished = returnJob.date_finished;
            this.document.jobs[index].is_completed = returnJob.is_completed;
            this.document.jobs[index].is_denied = returnJob.is_denied;
            this.document.jobs[index].is_available = returnJob.is_available;
          }
        }
      });
    },
    fromAssignUser(obj) {
      let index = this.document.jobs.findIndex((item) => {
        return item.id == obj.id;
      });

      if (index != -1) {
        this.document.jobs[index].action_user = obj.user != null ? obj.user.id : null;
        this.document.jobs[index].assign_user = obj.assigning_user != null ? obj.assigning_user.id : null;
        this.document.jobs[index].date_received = obj.date_received;
        this.document.jobs[index].user = obj.user;
        this.document.jobs[index].note = obj.note;
        this.document.jobs[index].actions = obj.actions;
      }
    },
    updateJob(obj) {
      let index = this.document.jobs.findIndex((item) => {
        return item.id == obj.id;
      });
      // alert("index="+index);
      if (index != -1) {
        //this.document.jobs[index] = {...obj};
        this.document.jobs[index].name = obj.name;
        this.document.jobs[index].action_user = obj.action_user;
        this.document.jobs[index].datedue = obj.datedue;
        this.document.jobs[index].user = { ...obj.user };
        this.document.jobs[index].description = obj.description;
        this.document.jobs[index].reports = [...obj.reports];
        this.document.jobs[index].date_finished = obj.date_finished;
        this.document.jobs[index].is_completed = obj.is_completed;
        this.document.jobs[index].is_denied = obj.is_denied;

        //this.$root.$emit('bv::refresh::table', 'reminderRef');//refresh data trong danh sách reminder -> ở form khác
      } else {
        this.document.jobs.push(obj);
      }
      this.wljob_id = null;
    },
    fetchJobTypes() {
      var page_url = this.page_url_job_type + "?can_be_created=true";//"/api/category/companies";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.action_job_types = res.data.action_job_types;
          this.response_job_types = res.data.response_job_types;
        })
        .catch(err => console.log(err));
    },
    interactJob(obj, type) {
      switch (type) {
        case "delete":
          this.loading = true;
          var apiAddress = "api/works/jobs/" + obj.id;
          this.$bvModal
            .msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
              okVariant: "danger",
              okTitle: "Ok",
              cancelTitle: "Cancel",
              centered: true,
            })
            .then((value) => {
              if (value) {
                fetch(apiAddress, {
                  method: "DELETE",
                  body: JSON.stringify({ id: obj.id }),
                  headers: {
                    Authorization: this.token,
                    "Content-Type": "application/json",
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
                      toastr.success("Xóa công việc thành công", "Thông báo");

                      let index = this.document.jobs.findIndex((item) => {
                        return item.id == obj.id;
                      });

                      if (index != -1) {
                        this.document.jobs.splice(index, 1);
                      }
                    }
                    else {
                      this.errors = res.errors;
                      toastr.warning(res.errors, "Xóa công việc bị lỗi");
                    }
                    this.loading = false;
                  })
                  .catch((err) => {
                    console.log(err);
                    toastr.warning(this.$t("form.delete_error"), "");
                    this.loading = false;
                  });
              }
            });
          ///dd

          break;
        case "edit":
          this.wljob_id = obj.id;
          $("#modalAddJob").modal("show");
          break;
        case "finish_job":
          this.isFinishedJob = true;
          this.currentInteractingJob = this.copyObject(obj);
          $("#modalFinishedJob").modal("show");
          break;
        case "revert_job":
          this.isFinishedJob = false;
          this.currentInteractingJob = this.copyObject(obj);

          $("#modalFinishedJob").modal("show");
          break;
        case "confirm_job":
          this.currentInteractingJob = this.copyObject(obj);
          this.relevant_jobs = [];
          this.viewable_jobs.forEach(job => {
            this.currentInteractingJob.dependencies.forEach(depend_job => {
              if (job.id == depend_job.dependjobid) {
                let new_job = { ...job };
                new_job.response = {
                  job_id: new_job.id,
                  is_accepted: true,
                  feedback: '',
                }

                this.relevant_jobs.push(new_job);
              }
            });
          });
          $("#DialogConfirmJob").modal("show");
          break;

        case "navigate_jobs":
          this.currentInteractingJob = this.copyObject(obj);

          this.navigate_jobs = [];
          this.document.jobs.forEach(job => {
            this.currentInteractingJob.navigates.forEach(navigate_job => {
              if (job.id == navigate_job.navigate_job_id) {
                let new_job = { ...job };

                this.navigate_jobs.push(new_job);
              }
            });
          });

          $("#DialogNavigateJob").modal("show");
          break;
        case "assign_job_user":
          this.currentInteractingJob = this.copyObject(obj);

          if (this.document.currentPhase != null && this.document.currentPhase.limitJobUserByPhaseMember) {
            let mergedUsers = this.document.currentPhase.members.concat(this.document.currentPhase.admins);
            this.jobLimitedTreeUsers = this.tree_users.map((element) => {
              return {
                ...element, children: element.children.filter((children) =>
                  mergedUsers.some(member => member.user_id === children.id)
                )
              };
            })
          }
          else {
            this.jobLimitedTreeUsers = this.tree_users;
          }
          $("#modalAssignJobUser").modal("show");
          break;
        case "unassign_job_user":
          this.currentInteractingJob = this.copyObject(obj);
          if (this.document.currentPhase != null && this.document.currentPhase.limitJobUserByPhaseMember) {
            let mergedUsers = this.document.currentPhase.members.concat(this.document.currentPhase.admins);
            this.jobLimitedTreeUsers = this.tree_users.map((element) => {
              return {
                ...element, children: element.children.filter((children) =>
                  mergedUsers.some(member => member.user_id === children.id)
                )
              };
            })
          }
          else {
            this.jobLimitedTreeUsers = this.tree_users;
          }
          $("#modalUnassignJobUser").modal("show");
          break;
        case "self_assign_job":
          this.selfAssignJob(obj);
          break;
        case "abandon_job":
          this.abandonJob(obj);
          break;
        case 'accept_job':
          this.sendApprovement(obj);
          break;
        case 'deny_job':
          this.currentInteractingJob = this.copyObject(obj);

          $("#DialogFeedbackApprovement").modal("show");
          break;
      }
    },
    sendApprovement(obj) {
      this.loading = true;

      var apiAddress = "api/works/send-approvement";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify({
          'job_id': obj.id,
          'is_accepted': true,
        }),
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
            toastr.success("Xác nhận công việc thành công", "Thông báo");
            this.fromFinishedJob(res.data);
            this.fromFinishedPhase(res.is_ready_to_complete_phase);
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Xác nhận công việc bị lỗi");
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    selfAssignJob(obj) {
      var page_url = this.page_url_wljob_selfassignjob;
      this.loading = true;
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify({ 'wljob_id': obj.id }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        }
      }).then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            toastr.success(this.$t("form.receive_mission_success"), "Thông báo");
            this.fromAssignUser(res.data);
            this.currentInteractingJob = returnJob;
          }
          else {
            this.errors = res.errors;
            toastr.warning(this.$t("form.receive_mission_fail"), "Thông báo");
          }

          this.loading = false;
        }).catch(err => {
          this.loading = false;
          console.log(err);
        })
    },
    abandonJob(obj) {
      var page_url = this.page_url_wljob_abandon_job;
      this.loading = true;
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify({ 'wljob_id': obj.id }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        }
      }).then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            toastr.success(this.$t("form.abandon_mission_success"), "Thông báo");
            this.fromAssignUser(res.data);
            this.currentInteractingJob = returnJob;
          }
          else {
            this.errors = res.errors;
            toastr.warning(this.$t("form.abandon_mission_fail"), "Thông báo");
          }

          this.loading = false;
        }).catch(err => {
          this.loading = false;
          console.log(err);
        })
    },
    copyObject(obj) {
      return JSON.parse(JSON.stringify(obj));
    },
    showAddResponseDialog(job_type_id) {
      this.wljob_id = 0;
      this.add_job_type_id = job_type_id;
      $("#DialogAddResponse").modal("show");
    },
    showAddJobDialog(job_type_id) {
      this.wljob_id = 0;
      this.add_job_type_id = job_type_id;
      $("#DialogAddJob").modal("show");
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
    getUser() {
      var page_url = this.page_url_users;
      //  console.log(page_url);
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
    onApproveAgree() {
      this.getDocument();
    },
    onLoadedInfo(info) {
      //if (info.is_approve || info.is_send) {
      //  $("#approveDialog").modal("show");
      //}
    },
    fromShared(obj) {
      this.document.shareds = obj;
    },
    fromReminder(obj) {
      let index = this.document.reminders.findIndex((item) => {
        return item.id == obj.id;
      });

      if (index != -1) {
        this.document.reminders[index] = obj;

        this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
      } else {
        this.document.reminders.push(obj);
      }
    },
    sharedAction(obj, type) {
      var index = "";
      switch (type) {
        case "DELETE":
          //gọi ham delete
          index = this.document.shareds.findIndex((data) => data.id == obj.id);
          this.document.shareds.splice(index, 1);

          break;

        default:
          break;
      }
    },
    reminderAction(obj, type) {
      var index = "";
      switch (type) {
        case "EDIT":
          index = this.document.reminders.findIndex((data) => data.id == obj.id);
          this.reminder_id = obj.id;
          $("#createEventModal").modal("show");
          break;
        case "DELETE":
          index = this.document.reminders.findIndex((data) => data.id == obj.id);
          this.document.reminders.splice(index, 1);
          this.reminder_id = "";
          //gọi ham delete
          break;
        case "SHOW":
          this.reminder_id = "";
          $("#createEventModal").modal("show");
          //gọi ham delete
          break;
        default:
          break;
      }
    },
    editDocument() {
      // window.location.href= "works?type=edit&id="+this.id;
      // window.location.href= "/works/edit/"+this.id;
      window.location.href = "works/list/" + this.current_workflow_code + "?type=edit&id=" + this.id;
    },
    configWorkflow() {
      // window.location.href= "works?type=edit&id="+this.id;
      // window.location.href= "/works/edit/"+this.id;

      return "workdefines?type=edit&id=" + this.document.wlworkflow_id;
    },
    showStep() {
      return this.showControl("showstep") ? "X" : "";
    },
    downloadFile(idfile, filename) {
      var page_url = "api/payment/downloadFile/" + idfile;
      toastr.info("Đang download....", "");
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
        responseType: "arraybuffer",
        method: "post",
      })
        .then((res) => res.blob())
        .then((res) => {
          var newBlob = new Blob([res], { type: "octet/stream" });
          if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            toastr.info("Download thành công", "");
            return;
          }
          // For other browsers:
          // Create a link pointing to the ObjectURL containing the blob.
          const data = URL.createObjectURL(newBlob);
          var link = document.createElement("a");
          link.href = data;
          link.download = filename;
          link.click();
          toastr.info("Download thành công", "");
          setTimeout(function () {
            // For Firefox it is necessary to delay revoking the ObjectURL
            URL.revokeObjectURL(data);
          }, 100);
        })
        .catch((err) => console.log(err));
    },
    backToList() {
      //console.log("this.pre_url="+this.pre_url);
      window.location.href = getUrlBack(); // this.pre_url;
    },
    print() {
      window.location.href = this.page_url_document_print + "/" + this.document.id;
    },
    updatephase() {
      let newphase_id = document.getElementById("changePhaseValue").value;
      var page_url = this.page_url_document_change_phase;
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify({
          document_id: this.document.id,
          wlworkflow_id: this.document.wlworkflow_id,
          newphase_id: newphase_id,
        }),
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
            this.getDocument();
            this.edit = true;
          }
          else {
            this.errors = data.data.errors;
          }
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          console.log(err);
        });
    },
    getDocument() {
      if (this.id != "") {
        this.loading = true;

        var apiAddress = "/api/works/workflows/" + this.id + "?notification_id=" + this.notification_id;
        fetch(apiAddress, { headers: { Authorization: this.token } })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              this.document = res.data;
              this.object_id.push(this.document.id);
              this.fetchPhases();
              this.edit = true;
            }
            else {
              this.errors = data.data.errors;
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
            console.log(err);
          });
      }
    },
    fetchPhases() {
      //  console.log("Test phase list :"+ this.workflow_id);
      this.loading = true;

      this.wlphases = [];
      const params = new URLSearchParams({
        wlworkflow_id: this.document.wlworkflow_id,
      });
      var apiAddress = "/api/works/phases?" + params.toString();
      fetch(apiAddress, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          var wlphases = res.data.data;
          for (let index = 0; index < wlphases.length; index++) {
            this.wlphases.push(wlphases[index]);
          }

          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    showLabel(fieldName, value) {
      if (fieldName in this.layout) {
        if (this.layout[fieldName]["has_custom_label"]) {
          return this.layout[fieldName]["custom_label_text"];
        }
      }
      return value;
    },
    showControl(fieldName) {
      if (fieldName in this.layout) {
        return this.layout[fieldName]["isVisible"];
      }
      return false;
    },
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    //   this.getUser();
    this.getDocument();
    this.getUser();
    this.getUserTree();
    this.fetchJobTypes();
  },

  computed: {
    canViewJobTab() {
      if (this.document) {
        return (this.document.jobs != null && this.document.jobs.length > 0) || this.document.currentPhase.allow_add_job || this.document.currentPhase.allow_send_response;
      }
      return false;
    },
    getUrlBack() {
      return "works/list/" + this.current_workflow_code;
    },
    viewable_jobs() {
      if (this.document && this.document.jobs) {
        return this.document.jobs.filter(function (job) {
          if (job.viewable && job.is_available) {
            return true;
          }
        });
      }
      return null;
    },
    interactableJobs() {
      if (this.document && this.document.jobs) {
        return this.document.jobs.filter(function (job) {
          if (job.viewable && !job.is_completed && job.is_available) {
            if ((job.actions.can_interact_job))
              return true;
          }
        });
      }
      return null;
    },
  },

  watch: {
    document() {
      var totalTab = 0;
      var hasJobTab = (this.document.jobs != null && this.document.jobs.length > 0) || this.document.currentPhase.allow_add_job || this.document.currentPhase.allow_send_response;
      var hasInfoTab = true;

      totalTab += hasJobTab ? 1 : 0;
      totalTab += hasInfoTab ? 1 : 0;

      if (hasJobTab) {
        this.activetab = totalTab - 1;//"jobreport";
      }
      else {
        this.activetab = 0;//"generalinfo";
      }
    }
  }
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1px !important;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-weight: normal !important;
  line-height: 1.2 !important;
}

h3,
.h3 {
  font-size: 1.15rem !important;
}

h3 {
  display: block !important;
  font-size: 1.17em !important;
  margin-block-start: 1em !important;
  margin-block-end: 1em;
  margin-inline-start: 0px !important;
  margin-inline-end: 0px !important;
  font-weight: bold !important;
}
</style>
