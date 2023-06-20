<template>
  <!-- Modal -->
  <div class="modal fade" id="hrAddMarkDialogID" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="" @submit.prevent="AddEditMark()">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="hrAddMarkDialogID">Cộng/trừ điểm năng suất</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body card">
            <!-- <div v-if="hasAnyError >0">

            <div class="alert alert-warning">
                <button type="button" class="close" @click.prevent="clearAllError()">
                <i class="ace-icon fa fa-times"></i>
            </button>
            <ul>
                <li v-for="(err,index) in errors" v-bind:key="index">{{err}}</li>

            </ul>
            </div>
            </div>        -->
            <!-- <div class="form-group row">
              <label for="payment_voucher_type" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.company')}}
                <small class="text-red">( * )</small></label>
              <div class="col-sm-8">

                <select name="company_id" id="company_id" v-model="hraddmark.company_id" required
                  class="form-control form-control-sm" v-bind:class="hasError('company_id')?'is-invalid':''"
                  @click="clearError($event)">
                  <option v-for="company in companies" :key="company.id" v-bind:value="company.id">
                    {{ company.name }}
                  </option>
                  <option value="">All</option>
                </select>
                <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                  <strong>{{getError('company_id')}}</strong>
                </span>
              </div>
            </div> -->
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
              <label class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.year')}} <small class="text-red">( *
                  )</small></label>
              <div class="col-sm-8">

                <input type="number" name="year" id="year" @change="clearError($event)"
                  v-bind:class="hasError('year')?'is-invalid':''" v-model="hraddmark.year" min="1900" max="2099"
                  class="form-control">
                <span v-if="hasError('year')" class="invalid-feedback" role="alert">
                  <strong>{{getError('year')}}</strong>
                </span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.month')}} <small class="text-red">( *
                  )</small></label>
              <div class="col-sm-8">

                <input type="number" name="month" id="month" @change="clearError($event)"
                  v-bind:class="hasError('month')?'is-invalid':''" v-model="hraddmark.month" min="1" max="12"
                  class="form-control">
                <span v-if="hasError('month')" class="invalid-feedback" role="alert">
                  <strong>{{getError('month')}}</strong>
                </span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.mark')}} <small class="text-red">( *
                  )</small></label>
              <div class="col-sm-8">

                <input type="number" name="mark_delta" id="mark_delta" @click="clearError($event)"
                  v-bind:class="hasError('mark_delta')?'is-invalid':''" v-model="hraddmark.mark_delta" min="0"
                  max="10" class="form-control" step=".01">
                <span v-if="hasError('mark_delta')" class="invalid-feedback" role="alert">
                  <strong>{{getError('mark_delta')}}</strong>
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

            <div class="form-group row">
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
      }else{
         //this.reset();
      }

    },

  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetCompany();
    this.getEmployeeTree();
  },

  data() {
    return {
      hraddmark: {

        company_id: "",
        staff_id: null,
        year: "",
        month: "",
        mark_delta: "",
        reason: "",
        attachment_file: [],
        attachmentFileRemove: [],

      },
      companies: [],
      tree_employees: [],
      errors: {},
      loading: false,
      edit: false,
      token: "",
      page_url_addmark: "api/productivity/addmark",
      page_url_company: "/api/category/companies",
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
              $("#hrAddMarkDialogID").modal("hide");


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
              $("#hrAddMarkDialogID").modal("hide");


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
      this.hraddmark.company_id = "";
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
    fetCompany() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.companies = res.data;
        })
        .catch((err) => console.log(err));
    },
  },
}
</script>

<style scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>