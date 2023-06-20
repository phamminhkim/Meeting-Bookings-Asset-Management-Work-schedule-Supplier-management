<template>
  <div class="modal fade" id="DialogConfirmJob" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div class="modal-content card">
          <form @submit.prevent="sendResponse" @keydown="clearError">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">
                <span>Xác nhận công việc</span>
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
              <!-- <div class="form-group row">
                <label class="col-form-label-sm col-sm-12 col-form-label">
                   Bấm "Xác nhận" khi bạn đồng ý với nội dung phía trên, hoặc bấm "Từ chối" và điền thêm ghi chú:
                </label>
              </div> -->

              <div class="form-group row" v-for="(item, index) in this.relevant_jobs" v-bind:key="index">
                <div class="col-sm-12">
                  <data-object-job-confirm-view-data :tree_users="tree_users" :job="item" :workflow_id="workflow_id"
                    :users="users">
                  </data-object-job-confirm-view-data>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" title="Xác nhận"
                class="btn btn-primary mr-auto">
                Xác nhận
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
import DataObjectJobConfirmViewData from "../objects/DataObjectJobConfirmViewData.vue";
export default {
  components: { DataObjectControl, DataObjectJobConfirmViewData },
  props: {
    workflow_id: Number,
    current_job: Object,
    relevant_jobs: Array,
    users: Array,
    tree_users: Array,
  },
  data() {
    return {
      loading: false,
      token: "",
      errors: {},
    };
  },
  methods: {
    sendResponse() {
      this.loading = true;

      this.current_job.responses = [];

      this.relevant_jobs.forEach(job => {
        this.current_job.responses.push(job.response);
      });

      var apiAddress = "api/works/submit-approvements";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify(this.current_job),
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
            this.$emit("onConfirmJob", res.data);

            $("#DialogConfirmJob").modal("hide");

            toastr.success("Xác nhận công việc thành công", "Thông báo");

            if (res.is_ready_to_complete_phase) {
              this.$emit("fromFinishedPhase", true);
            } else {
              this.$emit("fromFinishedPhase", false);
            }
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Xác nhận công việc bị lỗi");
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    clearError(event) {
      Vue.delete(this.errors, event.target.name);
    },
    reset() {
      this.clearAllError();
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
