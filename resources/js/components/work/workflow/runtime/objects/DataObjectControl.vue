<template>
  <div>
    <b-overlay :show="loading" rounded="sm">
      <div v-for="(item, index) in this.controls" v-bind:key="index">
        <div class="form-group row">
          <b-popover v-show="item.description != ''" :target="'label' + item.control_id" variant="primary"
            placement="bottom" triggers="hover focus">
            {{ item.description }}
          </b-popover>

          <label v-if="editmode" :class="'col-form-label-sm text-md-right col-sm-' + text_width">
            {{ item.custom_label_text
              }}<small v-if="item.require_validation && editmode" class="text-red">( * )</small></label>
          <span v-if="!editmode" :class="'col-form-label-sm col-sm-' + text_width">{{ item.custom_label_text }}:</span>
          <div :class="'col-sm-' + control_width">
            <data-object-control-edit v-if="editmode" v-bind:control="item" :users="users" :tree_users="tree_users"
              ></data-object-control-edit>
            <data-object-control-view v-else :control="item" :users="users"></data-object-control-view>
          </div>
          <div v-if="editmode" class="col-sm-1"> 
            <i :id="'label' + item.control_id"
              class="fas fa-lightbulb text-green"></i>
          </div>
        </div>
      </div>
    </b-overlay>
  </div>
</template>

<script>
import DataObjectControlView from "./DataObjectControlView.vue";
import DataObjectControlEdit from "./DataObjectControlEdit.vue";

export default {
  components: { DataObjectControlView, DataObjectControlEdit },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  props: {
    workflow_id: Number,
    editmode: Boolean,
    controls: Array,
    users: Array,
    tree_users: Array,
    iscompleted: Boolean,
    read_only: Boolean,
    require_validation: Boolean,
    canedit: Boolean,
    text_width: Number,
    control_width: Number,
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    submitReport(index, report) {
      this.loading = true;
      var page_url = "api/works/submit-single-report/" + report.id;
      var document = {};
      document.value = report.value;
      document.selecteds = report.value_list;
      document.attachment_file = report.attachment_file;
      document.attachment_file_removed = report.attachment_file_removed;
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify(document),
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
            toastr.success(this.$t("form.save_success"), "");
            this.controls[index].value = res.data.value;
            this.controls[index].subvalue = res.data.subvalue;
            this.controls[index].iscompleted = res.data.iscompleted;

            if (res.is_ready_to_complete_phase) {
              this.$emit("fromFinishedPhase", true);
            } else {
              this.$emit("fromFinishedPhase", false);
            }
          }
          else {
            this.form_errors = res.errors;
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  computed: {},
};
</script>
<style lang="scss" scoped>
//Class Ẩn nút chọn file trong <input type="file" >
.visuallyhidden {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

.form-group {
  margin-bottom: 1px !important;
}
</style>
