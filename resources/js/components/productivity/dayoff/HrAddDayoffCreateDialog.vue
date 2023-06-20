<template>
  <!-- Modal -->
  <div class="modal fade" id="hrAddDayoffDialogID" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="" @submit.prevent="AddEditMark()">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="hrAddDayoffDialogID">
              <span v-if="edit==true">Sửa ngày nghỉ</span>
              <span v-else>Thêm ngày nghỉ</span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body card">
            <div class="form-group row">
              <label class="col-form-label-sm col-sm-4 col-form-label">Công nhân <small class="text-red">( *
                  )</small></label>
              <div class="col-sm-8">

                <treeselect @select="clearErrorByName('staff_id')" v-bind:class="hasError('staff_id')?'is-invalid':''"
                  name="staff_id" id="staff_id" placeholder="Tất cả" v-model="hraddmark.staff_id"
                  :disable-branch-nodes="true" :multiple="false" :options="tree_employees"></treeselect>


                <span v-if="hasError('staff_id')" class="invalid-feedback" role="alert">
                  <strong>{{getError('staff_id')}}</strong>
                </span>
              </div>
            </div>


            <div class="form-group row">
              <label class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.date_off')}} <small class="text-red">(
                  * )</small></label>
              <div class="col-sm-8">


                <input type="date" class="form-control form-control-sm" data-date="" data-date-format="DD/MM/YYYY"
                  v-model="hraddmark.date_off" v-bind:class="hasError('date_off')?'is-invalid':''" name="date_off"
                  id="date_off" @click="clearError($event)" @change="clearError($event)" placeholder="" />
                <span v-if="hasError('date_off')" class="invalid-feedback" role="alert">
                  <strong>{{getError('date_off')}}</strong>
                </span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.reason')}} <small class="text-red">( *
                  )</small></label>
              <div class="col-sm-8">
                <textarea rows="" name="reason" id="reason" @click="clearError($event)"
                  v-bind:class="hasError('reason')?'is-invalid':''" cols="" v-model="hraddmark.reason"
                  class="form-control"></textarea>
                <span v-if="hasError('reason')" class="invalid-feedback" role="alert">
                  <strong>{{getError('reason')}}</strong>
                </span>
              </div>
            </div>

            <div class="form-group row" v-if="false">
              <label for="content" class="col-form-label-sm col-sm-2 col-form-label text-md-right">{{
              $t("form.attached_file") }}<small class="text-red"></small></label>
              <div class="col-sm-8">
                <button type="button" title="chọn file" class="btn btn-xs btn-outline"
                  v-on:click="handleClickInputFile()">
                  <i title="chọn file" class="fas fa-paperclip"></i>
                </button>
                <div class="d-flex justify-content-between">
                  <ul class="list-unstyled">
                    <li v-for="(file, index) in hraddmark.attachment_file" v-bind:key="index" class="itemfile">
                      <div class="btn-group">
                        <button type="button" @click.prevent="downloadFile(file.id, file.name)"
                          class="btn btn-default btn-xs">
                          {{ file.name }}
                        </button>
                        <button type="button" class="btn btn-default btn-xs text-red"
                          @click.prevent="deleteFile(file, index)">
                          <i class="far fa-times-circle"></i>
                        </button>
                        <button type="button" v-if="file.id" class="btn btn-default btn-xs"
                          @click.prevent="downloadFile(file.id, file.name)">
                          <i class="fas fa-download"></i>
                        </button>
                      </div>
                    </li>
                  </ul>
                  <input type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.msg,.eml"
                    @input="emitEvent_File($event)" @change="emitEvent_File($event)" id="ThemfileAttach"
                    style="display: none" ref="fileAttach" multiple />
                </div>
                <span v-if="hasError('content')" class="invalid-feedback" role="alert">
                  <strong>{{ getError("content") }}</strong>
                </span>
              </div>
            </div>
            <loading :loading="loading"></loading>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import Loading from '../../shared/Loading.vue';
export default {
  components: {
    Treeselect,
    Loading,
  },
  props: {
    id: ""
  },
  watch: {
    id() {

      this.edit = false;
      this.reset();
      if (this.id != "" && this.id != undefined) {

        this.fetHrAddMark(this.id);
      }

    },

  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.getEmployeeTree();
  },

  data() {
    return {
      hraddmark: {
        staff_id: null,
        date_off: "",
        reason: "",
        attachment_file: [],
        attachmentFileRemove: [],

      },
      locale_format: 'de-DE',
      tree_employees: [],
      errors: {},
      loading: false,
      edit: false,
      token: "",
      page_url_addmark: "api/productivity/dayoff",
      page_url_employees: "api/category/employees",

    }
  },
  computed: {

    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
  methods: {
    handleClickInputFile() {
      this.$refs.fileAttach.click();
    },
    deleteFile(item, index) {
      if (confirm(this.$t("form.confirm_delete") + "?")) {
        this.hraddmark.attachmentFileRemove.push({ ...item });
        this.hraddmark.attachment_file.splice(index, 1);
      }
    },
    emitEvent_File(event) {
      for (let index = 0; index < event.target.files.length; index++) {
        const file = event.target.files[index];
        //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = () => {
          // console.log(event.target.files[0]);
          const docs = {
            name: file.name,
            size: file.size,
            ext: file.type.split("/").pop(),
            lastModifiedDate: file.lastModifiedDate,
            base64: reader.result,
          };
          //console.log(docs);
          this.hraddmark.attachment_file.push({ ...docs });
        };
      }
      //reset file control
      event.target.value = "";
    },
    downloadFile(idfile, filename) {
      var page_url = "api/payment/downloadFile/" + idfile;
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
        responseType: "arraybuffer",
        method: "post",
      })
        .then((res) => res.blob())
        .then((res) => {

          var newBlob = new Blob([res], { type: "octet/stream" });
          if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            return;
          }

          // For other browsers:
          // Create a link pointing to the ObjectURL containing the blob.
          const data = URL.createObjectURL(newBlob);
          var link = document.createElement("a");
          link.href = data;
          link.download = filename;
          link.click();

          setTimeout(function () {
            // For Firefox it is necessary to delay revoking the ObjectURL
            URL.revokeObjectURL(data);
          }, 100);
        })
        .catch((err) => console.log(err));
    },
    fetHrAddMark(id) {

      this.reset();
      //this.reminder = {};
      //this.reminder.type_id = 1;

      if (id != "" && id != "undefined") {
        this.loading = true;
        var page_url = this.page_url_addmark + "/" + id + '&no-cache=' + new Date().getTime();

        fetch(page_url, {
          method: "get",
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        }).then(res => res.json())
          .then(res => {
            console.log(res);

            if (res.statuscode == "403") {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            if (res.success == true) {
              //toastr.success(this.$t('form.save_success'),"");
              this.hraddmark = res.data;
              //this.mydate =  this.hraddmark.year+"-0"+this.hraddmark.month +"-0"+this.hraddmark.day;
              this.edit = true;
              this.hraddmark.attachmentFileRemove = [];
              this.loading = false;

            } else {

              this.errors = res.errors;
              this.loading = false;
            }

          }).catch(err => {
            console.log(err);
            this.loading = false;
          });
      }

    },
    AddEditMark() {

      var temp = { ...this.hraddmark };
      this.loading = true;
      var page_url = this.page_url_addmark;

      if (this.edit === false) {

        fetch(page_url, {
          method: "POST",
          body: JSON.stringify(this.hraddmark),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
          .then(res => res.json())
          .then(data => {
            //alert(data.statuscode);'
            this.loading = false;

            if (data.statuscode == "403") {
              toastr.warning(data.message, "");
              return;
            }
            if (data.success === true) {

              toastr.success(this.$t('form.save_success'), "");
              $("#hrAddDayoffDialogID").modal("hide");


              this.hraddmark = data.data;
              this.$emit('fromhraddmark', { ...this.hraddmark });
              this.hraddmark.attachmentFileRemove = [];
              this.reset();

            } else {

              this.errors = data.errors;

            }

          })
          .catch(err => {
            this.loading = false;

          });
      } else {
        //update
        this.hraddmark.id = this.id;

        fetch(page_url + "/" + this.hraddmark.id, {
          method: "PUT",
          body: JSON.stringify(this.hraddmark),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
          .then(res => res.json())
          .then(data => {
            //  console.log(data);
            if (data.statuscode == "403") {
              toastr.warning(data.message, "");
              return;
            }
            if (data.success === true) {

              toastr.success(this.$t('form.save_success'), "");
              $("#hrAddDayoffDialogID").modal("hide");


              this.hraddmark = data.data;
              this.$emit('fromhraddmark', { ...this.hraddmark });
              this.hraddmark.attachmentFileRemove = [];
              //this.reset();

            } else {

              this.errors = data.errors;

            }
            this.loading = false;
          })
          .catch(err => {
            this.loading = false;

          });
      }

    },
    reset() {
      this.hraddmark.staff_id = null;
      this.hraddmark.year = "";
      this.hraddmark.month = "";
      this.hraddmark.mark_delta = "";
      this.hraddmark.reason = "";
      this.hraddmark.id = "";
      this.hraddmark.attachmentFileRemove = [];
      this.hraddmark.attachment_file = [];

    },
    clearErrorByName(name) {
      Vue.delete(this.errors, name);
    },
    hasError(fieldName) {
      return (fieldName in this.errors);
    },
    getError(fieldName) {
      //console.log(fieldName+"="+ this.errors[fieldName][0]);
      return this.errors[fieldName][0];

    },
    // hasAnyError(){
    // return Object.keys(this.errors).length > 0;
    // },
    clearAllError() {
      this.errors = {};
    },
    clearError(event) {
      //console.log(event);
      Vue.delete(this.errors, event.target.name);

    },
    getEmployeeTree() {
      var page_url = this.page_url_employees + "?type=tree_combobox";
      console.log(page_url);
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.tree_employees = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
}
</script>

<style scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>