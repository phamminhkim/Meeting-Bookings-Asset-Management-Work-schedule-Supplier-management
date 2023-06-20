<template>
  <div v-if="job" :class="'callout callout-' + color_style()" style="cursor:pointer"
    @click.prevent="job.is_navigated = !job.is_navigated">
    <table class="table table-borderless table-sm" style="margin-bottom: 0px">
      <tr>
        <td rowspan="2" width="2%">
          <b-form-checkbox v-model="job.is_navigated" />
        </td>
        <td width="30%" colspan="2">
          <strong class="text text-primary">{{ job.name }}</strong>
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
      is_accepted: true
    };
  },
  methods: {
    color_style() {
      if (this.job && this.job.is_navigated) {
        return 'success';
      }
      else {
        return 'danger';
      }
    },
  },
  computed: {

  }
};
</script>
<style scoped>

</style>