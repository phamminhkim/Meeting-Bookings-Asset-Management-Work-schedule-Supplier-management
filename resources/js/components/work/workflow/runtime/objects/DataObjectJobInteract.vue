<template>
  <div :class="'card card-outline card-' + job_color_style()">
    <div class="card-body">
      <table class="table table-borderless table-sm">
        <th width="25%"></th>
        <tbody>
          <tr>
            <td>
              <span v-if="!job.viewable" class="badge badge-dark">NV riêng tư</span>
              <span v-else-if="job.is_completed" class="badge badge-success">
                <span v-if="job.job_type_id == 1">Đã hoàn thành</span>
                <span v-else-if="job.job_type_id == 2">Đã xác nhận</span>
                <span v-else-if="job.job_type_id == 3">Đã điều phối</span>
              </span>
              <span v-else-if="!job.is_completed && job.job_type_id == 2 && job.is_denied" class="badge badge-danger">
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
              <strong :class="'text-' + job_color_style()">{{ job.name }}</strong>
              <b-popover v-if="job.description" :target="'descriptionJob_' + job.id" placement="auto"
                :title="'Mô tả công việc'" triggers="hover focus">
                <template #title>Mô tả công việc</template>
                <span> {{ job.description }} </span>
              </b-popover>
              <i v-if="job.description" v-bind:id="'descriptionJob_' + job.id" class="fa fa-info-circle text-info"></i>
            </td>

          </tr>
          <tr v-if="job.date_received">
            <td><small>Ngày nhận việc:</small></td>
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
            <td><small>Kế hoạch:</small></td>
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
          <tr>
            <td>
              <small v-if="job.job_type_id == 1">Người thực hiện: </small>
              <small v-else-if="job.job_type_id == 2">Người xác nhận: </small>
              <small v-else-if="job.job_type_id == 3">Người điều phối: </small>
            </td>
            <td>
              <small>
                <span style="color: #869009; font-weight: bold">
                  <span v-if="!job.viewable">Bí mật</span>
                  <span v-else-if="job.user">{{ job.user.name }} ({{ job.user.username }})</span>
                  <span v-else>Chưa có</span>
                </span>
              </small>
              <b-avatar v-if="job.user" :title="job.user.name" size="sm" :src="job.user.avatar"></b-avatar>
            </td>
          </tr>
          <tr v-if="job.note && job.viewable">
            <td colspan="2">
              <h6 class="text-gray"> <i class="fas fa-file mr-1"></i>
                <strong><i> <u>Nội dung chi tiết</u> </i></strong>
              </h6>
              <b-card bg-variant="light">
                <span> {{ job.note }} </span>
              </b-card>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-borderless table-sm">
        <tbody>
          <tr v-if="job.actions.can_submit_job">
            <td colspan="2">
              <button type="button" class="btn btn-success btn-sm btn-block"
                @click.prevent="interactJob(job, 'finish_job')" href="#">
                <i class="fas fa-check"></i> Hoàn thành công việc
              </button>
            </td>
          </tr>
          <tr v-if="job.actions.can_approve_with_dependencies_job">
            <td colspan="2">
              <button type="button" class="btn btn-success btn-sm btn-block"
                @click.prevent="interactJob(job, 'confirm_job')" href="#">
                <i class="fas fa-check"></i> Xem chi tiết và phản hồi
              </button>
            </td>
          </tr>
          <tr v-if="job.actions.can_approve_no_dependencies">
            <td colspan="2">
              <button type="button" class="btn btn-success btn-sm btn-block"
                @click.prevent="interactJob(job, 'accept_job')" href="#">
                <i class="fas fa-check"></i> Xác nhận
              </button>
            </td>
          </tr>
          <tr v-if="job.actions.can_approve_no_dependencies">

            <td colspan="2">
              <button type="button" class="btn btn-danger btn-sm btn-block"
                @click.prevent="interactJob(job, 'deny_job')" href="#">
                <i class="fas fa-times"></i> Từ chối & Phản hồi
              </button>
            </td>
          </tr>
          <tr v-if="job.actions.can_navigate_job">
            <td colspan="2">
              <button type="button" class="btn btn-success btn-sm btn-block"
                @click.prevent="interactJob(job, 'navigate_jobs')" href="#">
                <i class="fas fa-compass"></i> Điều phối công việc
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <button type="button" class="btn btn-danger btn-sm btn-block" v-if="job.actions.can_abandon_job"
                @click.prevent="interactJob(job, 'abandon_job')" href="#">
                <i class="fas fa-times"></i> Từ bỏ công việc
              </button>
              <button type="button" class="btn btn-info btn-sm btn-block" v-if="job.actions.can_self_assign_job"
                @click.prevent="interactJob(job, 'self_assign_job')" href="#">
                <i class="fas fa-calendar-check"></i> Đảm nhận công việc
              </button>
              <button type="button" class="btn btn-primary btn-sm btn-block" v-if="job.actions.can_assign_user"
                @click.prevent="interactJob(job, 'assign_job_user')" href="#">
                <span v-if="job.action_user > 0"><i class="fas fa-user-edit"></i>Chuyển công việc</span>
                <span v-else><i class="fas fa-user-plus"></i> Giao công việc</span>
              </button>
              <button type="button" class="btn btn-warning btn-sm btn-block" v-if="job.actions.can_unassign_user"
                @click.prevent="interactJob(job, 'unassign_job_user')" href="#">
                <span><i class="fas fa-undo"></i>Bỏ chỉ định công việc</span>
              </button>
            </td>

          </tr>
        </tbody>
      </table>
    </div>


  </div>
</template>

<script>
// import AddJob from '../define/AddJob.vue';
import DataObjectControl from "./DataObjectControl.vue";
export default {
  components: { DataObjectControl },
  props: {
    job: Object,
    users: Array,
    tree_users: Array
  },
  methods: {
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
};
</script>
<style scoped>
.card-success.card-outline {
  border-top: 6px solid #28a745;
}

.card-dark.card-outline {
  border-top: 6px solid #343a40;
}

.card-secondary.card-outline {
  border-top: 6px solid #6c757d;
}

.card-warning.card-outline {
  border-top: 6px solid #ffc107;
}
</style>