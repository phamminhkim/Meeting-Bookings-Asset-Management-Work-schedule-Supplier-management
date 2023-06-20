<template>

  <div>
    <div v-for="(phase, index) in document.phases" v-bind:key="index">
      <button class="btn btn-link mute btn-sm " style="color:#4e5154" @click.prevent="showhide(index)">
        <i :id="'iconup' + index" style="color:#5e5959;width:9px" class="fas fa-caret-right"></i>
        <!-- <i :id="'icondown'+index" style="color:#5e5959;"  class="fas fa-caret-down"></i>  -->

        <strong v-if="phase.finished_date" style="color:#28a745">{{ '[Đã xong] ' + phase.name }}</strong>
        <strong v-else-if="phase.id === document.currentPhase.id" style="color:#3c8dbc">{{ '[Đang thực hiện] ' + phase.name
        }}</strong>
        <strong v-else style="color:#4e5154">{{ phase.name }}</strong>
      </button>
      <div :id="'pp' + index" class="ml-3" style="display:none">
        <div >
          <span v-if="phase.index == -999" class="mute small">
            {{ "Khởi tạo quy trình lúc " + Date(document.created_at) }}
          </span>
          <span v-else-if="phase.index == 999" class="mute small">
             {{ document.finished ? "Quy trình đã hoàn thành lúc " +  phase.finished_date : "Quy trình chưa hoàn thành" }}
          </span>
          <span v-else-if="phase.jobs.length == 0" class="mute small">
            Không có nội dung
          </span>
          <span v-else>
            <data-object-control :workflow_id="document.wlworkflow_id" :controls="phase.controls" :users="users"
              :tree_users="tree_users" :editmode="false"></data-object-control>

            <div v-for="(item, index_job) in phase.jobs" v-bind:key="index_job">

              <data-object-job v-if="item.viewable && item.is_available" :tree_users="tree_users" :job="item" :workflow_id="document.workflow_id" :users="users"
                 :readonly="true"></data-object-job>
            </div>
          </span>
        </div>


      </div>
    </div>

  </div>


</template>

<script>
import DataObjectControl from "../runtime/objects/DataObjectControl.vue";
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import DataObjectJob from '../runtime/objects/DataObjectJob.vue';
export default {
  components: { Treeselect, DataObjectControl, DataObjectJob },
  props: {
    document: Object,
    phases: Array,
    current_phaseid: Number,
    users: Array,
    tree_users: Array
  },
  data() {
    return {
      options: [],
      optionCount: 0,
      currentPhase: {},
      choosingID: null,
      choosingItem: {},
      errors: [],
    };
  },
  methods: {
    showhide(id) {
      $('#pp' + id).toggle();
      var compid = "#iconup" + id;
      if ($(compid).hasClass("fas fa-caret-right")) {
        $(compid).removeClass("fas fa-caret-right");
        $(compid).addClass("fas fa-caret-down");
      } else {
        $(compid).removeClass("fas fa-caret-down");
        $(compid).addClass("fas fa-caret-right");
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.show {
  display: inline;
}

.hide {
  display: none;
}
</style>
