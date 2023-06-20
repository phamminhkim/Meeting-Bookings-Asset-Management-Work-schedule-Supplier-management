<template>
  <div class="modal fade" id="dialogFinishPhase" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Hoàn thành giai đoạn</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            Tất cả công việc trong giai đoạn hiện tại đã thực hiện xong, bạn có muốn chuyển sang giai đoạn tiếp theo ngay?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="button" @click.prevent="finishPhase()" class="btn btn-primary"> <i
                class="fas fa-check-circle"></i>Hoàn thành</button>
          </div>
        </div>
      </b-overlay>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    workflow_id: Number,
    phase_id: Number,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  data() {
    return {

      locale_format: "de-DE",

      edit: false,
      errors: {},
      loading: false,
      token: "",
      tabIndex: 0,
    };
  },
  methods: {
    finishPhase() {
      this.loading = true;
      var apiAddress = "/api/works/confirm-finish-phase";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify({ "workflow_id": this.workflow_id, "phase_id": this.phase_id }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      }).then((res) => res.json())
        .then((res) => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          if (res.success == 1) {
            toastr.success("Hoàn thành giai đoạn thành công", "thông báo");
            this.$emit('fromFinishPhase');
            $('#dialogFinishPhase').modal('hide');
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Hoàn thành giai đoạn bị lỗi");
          }

          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
  }
}
</script>

<style>
</style>