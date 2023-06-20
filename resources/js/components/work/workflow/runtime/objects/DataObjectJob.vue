<template>
  <div :class="'callout callout-' + job_color_style()">
    <table class="table table-borderless" style="margin-bottom: 0">

      <tbody>
        <tr>
          <td width="90%">
            <table class="table table-borderless table-sm" style="margin-bottom: 0">
              <th width="30%"></th>
              <tbody>
                <tr>
                  <td>
                    <span v-if="!job.viewable" class="badge badge-dark">Công việc bảo mật</span>
                    <span v-else-if="job.is_completed" class="badge badge-success">
                      <span v-if="job.job_type_id == 1">Đã hoàn thành</span>
                      <span v-else-if="job.job_type_id == 2">Đã xác nhận</span>
                      <span v-else-if="job.job_type_id == 3">Đã điều phối</span>
                      <span v-else-if="job.job_type_id == 4">Đã phản hồi</span>
                    </span>
                    <span v-else-if="!job.is_completed && job.job_type_id == 2 && job.is_denied"
                      class="badge badge-danger">
                      Đã từ chối
                    </span>
                    <span v-else-if="job.undone_jobs.length > 0" class="badge badge-secondary">Chưa mở khóa</span>
                    <span v-else class="badge badge-warning">
                      <span v-if="job.job_type_id == 1">Đang thực hiện</span>
                      <span v-else-if="job.job_type_id == 2">Chờ xác nhận</span>
                      <span v-else-if="job.job_type_id == 3">Chờ điều phối</span>
                    </span>
                  </td>
                  <td>
                    <strong>{{ job.name }}</strong>
                    <b-popover v-if="job.description || job.undone_jobs.length > 0"
                      :target="'full_description_job_' + job.id + 'readonly' + readonly" placement="auto"
                      triggers="hover focus">
                      <template #title>
                        <span v-if="job.description"> {{ job.description }} </span>
                        <span v-else-if="job.undone_jobs.length > 0">
                          Hoàn thành các task sau để mở khóa
                        </span>
                      </template>
                      <span v-if="job.undone_jobs.length > 0">
                        <span v-if="job.description">
                          <strong> Hoàn thành các task sau để mở khóa: </strong>
                        </span>
                        <span v-for="jobUndone in job.undone_jobs" v-bind:key="jobUndone.id">
                          <li>{{ jobUndone.name }}</li>
                        </span>
                      </span>

                    </b-popover>

                    <i v-if="job.description || job.undone_jobs.length > 0"
                      v-bind:id="'full_description_job_' + job.id + 'readonly' + readonly"
                      :class="job.undone_jobs.length > 0 ? 'fa fa-exclamation-triangle text-primary' : 'fa fa-info-circle text-info'"></i>
                  </td>

                </tr>
                <tr v-if="job.is_completed">
                  <td>
                    <small v-if="job.job_type_id == 1">Thực hiện lúc: </small>
                    <small v-else-if="job.job_type_id == 2">Xác nhận lúc: </small>
                    <small v-else-if="job.job_type_id == 3">Điều phối lúc: </small>
                    <small v-else-if="job.job_type_id == 4">Phản hồi lúc: </small>
                  </td>
                  <td>
                    <small><strong class="text-success">{{ job.date_finished
                    }}</strong>
                    </small>
                  </td>
                </tr>

                <tr v-if="job.date_received">
                  <td><small>Nhận việc lúc:</small></td>
                  <td>
                    <small>
                      <span style="color: #869009; font-weight: bold">
                        <span v-if="!job.viewable">Bí mật</span>
                        <span v-else-if="job.date_received">{{ job.date_received }}</span>
                        <span v-else>Chưa xác định</span>
                      </span>
                    </small>
                  </td>
                </tr>

                <tr v-if="job.datedue">
                  <td><small>Dự kiến hoàn thành:</small></td>
                  <td>
                    <small>
                      <span style="color: #869009; font-weight: bold">
                        <span v-if="!job.viewable">Bí mật</span>
                        <span v-else-if="job.datedue">{{ job.datedue }}</span>
                        <span v-else>Chưa xác định</span>
                      </span>
                    </small>
                  </td>
                </tr>
                <tr v-if="job.assigning_user">
                  <td>
                    <small>Người yêu cầu: </small>
                  </td>
                  <td>
                    <small>
                      <span style="color: #869009; font-weight: bold">
                        <span>{{ job.assigning_user.name }} ({{ job.assigning_user.username }})</span>
                      </span>
                    </small>
                    <b-avatar v-if="job.assigning_user" :title="job.assigning_user.name" size="sm" :src="job.assigning_user.avatar"></b-avatar>
                  </td>
                </tr>
                <tr>
                  <td>
                    <small v-if="job.job_type_id == 1">Người thực hiện: </small>
                    <small v-else-if="job.job_type_id == 2">Người xác nhận: </small>
                    <small v-else-if="job.job_type_id == 3">Người điều phối: </small>
                    <small v-else-if="job.job_type_id == 4">Người phản hồi: </small>
                  </td>
                  <td>
                    <small>
                      <span style="color: #869009; font-weight: bold">
                        <span v-if="!job.viewable">Bí mật</span>
                        <span v-else-if="job.user">{{ job.user.name }} ({{ job.user.username }})</span>
                        <span v-else>Chưa chỉ định</span>
                      </span>
                    </small>
                    <b-avatar v-if="job.user" :title="job.user.name" size="sm" :src="job.user.avatar"></b-avatar>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td v-if="!readonly" width="10%">
            <!-- <button v-if="job.actions.can_view_report" v-b-toggle="'collapse-' + job.id" class="btn btn-info btn-sm my-2"> -->
            <!-- Xem -->
            <!-- </button> -->
            <button v-if="job.actions.can_withdraw_job" @click.prevent="interactJob(job, 'revert_job')" href="#"
              class="btn btn-danger btn-sm" type="button" style="width:120px;margin-bottom:10px">
              <b class="fas fa-ban"></b> Thu hồi
            </button>
            <button v-if="job.actions.can_assign_user" @click.prevent="interactJob(job, 'assign_job_user')" href="#"
              class="btn btn-primary btn-sm" type="button" style="width:120px;margin-bottom:10px">
              <span v-if="job.action_user > 0"><i class="fas fa-user-edit"></i> Chuyển user</span>
              <span v-else><i class="fas fa-user-plus"></i> Giao việc</span>
            </button>
            <button v-if="job.actions.can_unassign_user" @click.prevent="interactJob(job, 'unassign_job_user')" href="#"
              class="btn btn-warning btn-sm" type="button" style="width:120px;margin-bottom:10px">
              <span><i class="fas fa-undo"></i>Bỏ chỉ định</span>
            </button>
          </td>
        </tr>
        <tr v-if="job.note && job.viewable">
          <td colspan="2">
            <h6 class="text-gray"> <i class="fas fa-file mr-1"></i>
              <strong><i> <u>Nội dung chi tiết</u> </i></strong>
            </h6>
            <b-card bg-variant="light">
              <span style="white-space: pre-wrap;"> {{ job.note }} </span>
            </b-card>
          </td>
        </tr>
        <tr v-if="job.is_completed && job.viewable && job.reports.length > 0">
          <td colspan="2">
            <h6 class="text-gray"> <i class="fas fa-file mr-1"></i>
              <strong><i> <u>Kết quả</u> </i></strong>
            </h6>
            <b-card bg-variant="light">
              <data-object-control v-if="job.is_completed" :workflow_id="workflow_id" :controls="job.reports"
                :users="users" :tree_users="tree_users" :editmode="false" :control_width="9" :text_width="3">
              </data-object-control>
            </b-card>
          </td>
        </tr>
        <tr v-else-if="job.viewable && job.details && job.details.length > 0">
          <td colspan="2">
            <h6 class="text-gray"> <i class="far fa-comments mr-1"></i>
              <strong><i> <u>{{ $t('form.info_feedback') }}</u> </i></strong>
            </h6>
            <ul class="list-group">
              <li class="list-group-item disabled " style="background-color:rgb(235, 232, 232);"
                v-for="(detail, index) in job.details" v-bind:key="index">
                <strong>{{ getUsernameById(detail.user_id) }}:</strong>
                <small>
                  <span class="time"><i class="fas fa-clock  text-gray" />
                    {{ detail.created_at | formatDateTime }}
                  </span>
                </small>
                <br>
                {{ detail.content }}
              </li>
            </ul>
          </td>

        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
// import AddJob from '../define/AddJob.vue';
import DataObjectControl from "./DataObjectControl.vue";
export default {
  components: { DataObjectControl },
  props: {
    job: Object,
    workflow_id: Number,
    users: Array,
    tree_users: Array,
    readonly: Boolean,
  },
  methods: {
    getUsernameById(id) {
      var user = this.users.find((x) => x.id == id);
      return user == null ? id : user.name;
    },
    interactJob(obj, type) {
      this.$emit("interactJob", { ...obj }, type);
    },
    job_color_style() {
      if (this.job) {
        if (!this.job.viewable) {
          return 'dark';
        }
        else if (this.job.is_completed) {
          return 'success';
        }
        else if (this.job.undone_jobs.length > 0) {
          return 'secondary';
        }
        else {
          return 'warning';
        }
      }
      return '';
    },
  },
  computed: {
    title() {
      return this.job.is_completed ? "Hoàn thành" : "Chưa hoàn thành";
    },

  },
};
</script>
