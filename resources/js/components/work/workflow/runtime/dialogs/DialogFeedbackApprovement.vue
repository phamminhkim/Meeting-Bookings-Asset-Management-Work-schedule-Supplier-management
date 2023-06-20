<template>
  <div class="modal fade" id="DialogFeedbackApprovement" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div class="modal-content card">
          <form @submit.prevent="sendDenyAndFeedback" @keydown="clearError">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">
                <span>Từ chối & Phản hồi</span>
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
                <div class="form-group row">
                  <label for="" class="col-form-label-sm col-sm-4 col-form-label">Chi tiết phản hồi<small class="text-red">(*)</small></label>
                  <div class="col-sm-8">
                    <textarea class="form-control form-control-sm" v-model="note" required></textarea>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="submit" title="Lưu" class="btn btn-primary mr-auto">
                <i class="fas fa-save"></i> Lưu
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
    wljob: Object,
    is_completed: Boolean,
  },
  data() {
    return {
      note: "",
      loading: false,
      token: "",
      errors: {},
    };
  },
  methods: {
    sendDenyAndFeedback() {
      this.loading = true;

      var apiAddress = "api/works/send-approvement";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify({
          'job_id': this.wljob.id,
          'is_accepted': false,
          'note': this.note,
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
            toastr.success("Phản hồi thành công", "Thông báo");

            this.$emit("fromFinishedPhase", res.is_ready_to_complete_phase);
            this.$emit("fromFinishedJob", res.data);

            $("#DialogFeedbackApprovement").modal("hide");
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Phản hồi bị lỗi");
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
