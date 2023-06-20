<template>
  <div>
    <b-overlay :show="loading">
      <div class="p-2" @click.prevent="showhide()"
        style="cursor:pointer;text-align:center;background-color:#28659C;color:white">
        Công việc của tôi {{ interactableJobs ? '('+ interactableJobs.length +')' : '' }}
        <i id="job_collapse_caret" class="fas fa-caret-down"></i>
      </div>
      <b-collapse id="job_collapse" visible role="tabpanel">
        <b-card>
          <div v-if="interactableJobs && interactableJobs.length > 0">
            <div v-for="(job, index) in interactableJobs" v-bind:key="index"
              :style="index < interactableJobs.length - 1 ? 'margin-bottom: 30px;' : ''">
              <data-object-job-interact :tree_users="tree_users" :job="job" :users="users"
                v-on:interactJob="interactJob">
              </data-object-job-interact>
            </div>
          </div>
          <div v-else class="d-flex justify-content-center">Hiện tại bạn không có công việc nào</div>
        </b-card>
      </b-collapse>

    </b-overlay>
  </div>
</template>

<script>
// import AddJob from '../define/AddJob.vue';
import DataObjectJobInteract from "./DataObjectJobInteract.vue";
export default {
  components: { DataObjectJobInteract },
  props: {
    interactableJobs: Array,
    users: Array,
    tree_users: Array,
    loading: Boolean
  },
  methods: {
    showhide() {
      $('#job_collapse').toggle();
      var compid = "#job_collapse_caret";
      if ($(compid).hasClass("fas fa-caret-right")) {
        $(compid).removeClass("fas fa-caret-right");
        $(compid).addClass("fas fa-caret-down");
      } else {
        $(compid).removeClass("fas fa-caret-down");
        $(compid).addClass("fas fa-caret-right");
      }
    },
    interactJob(obj, type) {
      this.$emit("interactJob", { ...obj }, type);
    },
  },
};
</script>
