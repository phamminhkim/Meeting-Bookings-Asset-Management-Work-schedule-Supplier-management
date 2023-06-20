<template>
  <div :class="'callout callout-' + job_color_style()">
    <table class="table table-borderless" style="margin-bottom: 0">
      <tbody>
        <tr>
          <table class="table table-borderless table-sm" style="margin-bottom: 0">
            <th width="25%"></th>
            <tbody>
              <tr>
                <td>
                  <span v-if="!job.viewable" class="badge badge-dark">Công việc bảo mật</span>
                  <span v-else-if="job.is_completed" class="badge badge-success">
                    <span v-if="job.job_type_id == 1">Đã hoàn thành</span>
                    <span v-else-if="job.job_type_id == 2">Đã xác nhận</span>
                    <span v-else-if="job.job_type_id == 3">Đã điều phối</span>
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
                  <strong :class="'text-' + job_color_style()">{{ job.name }}</strong>
                </td>
              </tr>
              <tr v-if="job.is_completed">
                <td>
                  <small v-if="job.job_type_id == 1">Thực hiện lúc: </small>
                  <small v-else-if="job.job_type_id == 2">Xác nhận lúc: </small>
                    <small v-else-if="job.job_type_id == 3">Điều phối lúc: </small>
                </td>
                <td>
                  <small><strong class="text-success">{{ job.date_finished
                  }}</strong>
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
                      <span v-else>Chưa chỉ định</span>
                    </span>
                  </small>
                  <b-avatar v-if="job.user" :title="job.user.name" size="sm" :src="job.user.avatar"></b-avatar>
                </td>
              </tr>
            </tbody>
          </table>
        </tr>
        <tr v-if="job.is_completed && job.viewable && job.reports.length > 0">
          <b-card bg-variant="light">
            <data-object-control v-if="job.is_completed" :workflow_id="workflow_id" :controls="job.reports"
              :users="users" :tree_users="tree_users" :editmode="false" :control_width="8" :text_width="4">
            </data-object-control>
          </b-card>
        </tr>
      </tbody>
    </table>
    <table class="table table-borderless" style="margin-bottom: 0">
      <tr>
        <td>
          <b-form-radio-group v-model="job.response.is_accepted" :state="state">
            <b-form-radio :value="true">
              <span class="text-success">ĐỒNG Ý</span>
            </b-form-radio>
            <b-form-radio :value="false">
              <span class="text-danger">KHÔNG ĐỒNG Ý</span>
            </b-form-radio>
          </b-form-radio-group>
        </td>
      </tr>
      <tr v-if="job.response.is_accepted == false">
        <td>
          <div class="form-group row">
            <label class="col-form-label-sm col-sm-4 col-form-label">
              Lí do từ chối
              <small class="text-red">( * )</small>
            </label>
            <div class="col-md-8">
              <input type="text" v-model="job.response.feedback" :required="true"
                class="form-control form-control-sm mt-1">
            </div>
          </div>
        </td>

      </tr>
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
  },
  data() {
    return {
      response: {
        is_accepted: true,
        feedback: "",
      },
    };
  },
  methods: {
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
    state() {
      return this.response.is_accepted != null;
    }
  }
};
</script>
