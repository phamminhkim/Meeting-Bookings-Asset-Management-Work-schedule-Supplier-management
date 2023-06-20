<template>
  <div class="modal fade" id="modalFinishedJob" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div class="modal-content card">
          <form @submit.prevent="submitReports" @keydown="clearError">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">
                <span v-if="is_completed">Hoàn thành công việc</span>
                <span v-else>Thu hồi</span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-for="(err, index) in this.errors" v-bind:key="index">
                <ul>
                  <li class="text-red">{{ err }}</li>
                </ul>
              </div>
              <div v-if="wljob">

                <data-object-control v-if="wljob.reports && wljob.reports.length > 0" :workflow_id="workflow_id" :users="users"
                  :controls="wljob.reports" :tree_users="tree_users" :editmode="is_completed" :canedit="is_completed"
                  :control_width="9" :text_width="3" />
                
              </div>

            </div>
            <div class="modal-footer">
              <button type="submit" :title="is_completed ? 'Lưu' : 'Thu hồi'"
                class="btn btn-primary mr-auto">
                <span v-if="is_completed"><i class="fas fa-save"></i> Lưu</span>
                <span v-else><i class="fas fa-undo"></i> Thu hồi</span>
                
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
import DataObjectControl from "../objects/DataObjectControl.vue";
export default {
  components: { DataObjectControl },
  props: {
    workflow_id: Number,
    wljob: Object,
    users: Array,
    tree_users: Array,
    is_completed: Boolean,
  },
  data() {
    return {
      status: false,
      job: {},
      loading: false,
      token: "",
      edit: false,
      errors: {},
    };
  },
  methods: {
    submitReports() {
      this.loading = true;

      var apiAddress = "api/works/submit-job-reports";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify(this.wljob),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          this.reset();
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.$emit("fromFinishedJob", res.data);
            $("#modalFinishedJob").modal("hide");

            if (this.is_completed) {
              toastr.success("Hoàn thành công việc thành công", "Thông báo");

              if (res.is_ready_to_complete_phase) {
                this.$emit("fromFinishedPhase", true);
              } else {
                this.$emit("fromFinishedPhase", false);
              }
            }
            else {
              toastr.success("Thu hồi thành công", "Thông báo");
            }
          }
          else {
            this.errors = res.errors;
            if (this.is_completed) {
              toastr.warning(res.errors, "Hoàn thành công việc bị lỗi");
            }
            else {
              toastr.warning(res.errors, "Thu hồi bị lỗi");
            }
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
        });
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
    reset() {
      this.clearAllError();
      this.edit = false;
    },
    clearAllError() {
      this.errors = {};
    },
  },
  computed: {
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
};
</script>

<style>

</style>
