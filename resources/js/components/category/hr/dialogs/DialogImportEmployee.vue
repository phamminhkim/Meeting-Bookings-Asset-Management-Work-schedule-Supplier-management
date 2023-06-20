<template>
  <div class="modal fade" id="DialogImportEmployee" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

      <div class="modal-content card">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">
            <span>Cập nhật dữ liệu công/nhân viên</span>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <b-overlay :show="loading" rounded="sm">
            <div v-if="hasAnyError > 0">
              <div class="alert alert-warning">
                <button type="button" class="close" @click.prevent="clearAllError()">
                  <i class="ace-icon fa fa-times"></i>
                </button>
                <ul>
                  <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
                </ul>
              </div>
            </div>

            <div class="form-group row">
              <label for="title" class="col-form-label-sm col-sm-3 col-form-label text-md-right">Tải lên file dữ
                liệu</label>
              <div class="col-sm-7">
                <button class="btn btn-success btn-sm mt-1 mb-1" @click.prevent="importData">
                  <i class="fa fa-upload"></i> (*.xlsx)
                </button>
                <button class="btn btn-warning btn-sm mt-1 mb-1" @click.prevent="exportDataAsFile()">
                  <i class="fa fa-download"></i>Tải template
                </button>
                <vue-excel-editor ref="templateTable" width="0px" height="0px" :disable-panel-setting="true"
                  :disable-panel-filter="true" :no-footer="true" :no-num-col="true">
                  <vue-excel-column v-for="(column, findex) in template" v-bind:key="findex" :field="column.field"
                    :label="column.label" :type="column.type" :width="column.width" :options="column.options"
                    :text-align="column.textAlign"
                    :mandatory="column.required ? 'Trường này không thể bỏ trống' : null" />

                </vue-excel-editor>
              </div>
            </div>
            <div class="form-group row" v-show="uploadData.length > 0">
              <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right"></label>
              <div class="col-sm-8">
                <strong> Đã tải lên {{ uploadData.length }} dòng dữ liệu </strong>
              </div>
            </div>
            <div class="form-group row" v-show="uploadData.length > 0">
              <label for="title" class="col-form-label-sm col-sm-1 col-form-label text-md-right"></label>
              <div class="col-sm-11">
                <vue-excel-editor ref="dataTable" v-model="uploadData" :disable-panel-setting="true"
                  :disable-panel-filter="true" :no-footer="true" :no-num-col="true">
                  <vue-excel-column v-for="(column, findex) in template" v-bind:key="findex" :field="column.field"
                    :label="column.label" :type="column.type" :width="column.width" :options="column.options"
                    :text-align="column.textAlign"
                    :mandatory="column.required ? 'Trường này không thể bỏ trống' : null" />

                </vue-excel-editor>
              </div>

            </div>
          </b-overlay>
        </div>

        <div class="modal-footer">
          <button type="button" @click.prevent="uploadEmployees()" :disabled="uploadData.length == 0"
            :title="uploadData.length == 0 ? 'Chưa nhập dữ liệu' : 'Cập nhật'" class="btn btn-primary mr-auto">Cập
            nhật</button>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $t('form.close')
          }}</button>
        </div>
      </div>

    </div>



  </div>
</template>

<script>
import VueExcelEditor from 'vue-excel-editor';

export default {
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  data() {
    return {
      template: [
        { field: 'company_code', label: 'Mã công ty', type: 'string', width: "80px", textAlign: 'center' },
        { field: 'department_code', label: 'Mã phòng ban', type: 'string', width: "80px", textAlign: 'center' },
        { field: 'workshop_code', label: 'Mã xưởng', type: 'string', width: "80px", textAlign: 'center' },
        { field: 'party_code', label: 'Mã tổ', type: 'string', width: "80px", textAlign: 'center' },
        { field: 'employee_id', label: 'Mã nhân viên', type: 'number', width: "80px", textAlign: 'center' },
        { field: 'name', label: 'Tên nhân viên', type: 'string', width: "160px", textAlign: 'center' },
        { field: 'gender', label: 'Giới tính', type: 'select', width: "80px", options: ['Nam', 'Nữ'], textAlign: 'center' },
        { field: 'date_in', label: 'Ngày vào làm', type: 'date', width: "120px", textAlign: 'center' },
        { field: 'is_resign', label: 'Đã nghỉ việc', type: 'select', width: "80px", options: ['Không', 'Có'], textAlign: 'center' },
        { field: 'date_out', label: 'Ngày nghỉ việc', type: 'date', width: "120px", textAlign: 'center' },
      ],
      uploadData: [],
      errors: {},
      loading: false,
      token: "",
      page_url_employee_upload: "/api/category/employeesupload",
    };
  },
  methods: {
    uploadEmployees() {
      this.loading = true;
      var page_url = this.page_url_employee_upload;
      fetch(page_url, {
        method: "post",
        body: JSON.stringify({
          'data': this.uploadData
        }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          this.loading = false;
          if (data.success == 1) {
            let created_count = data.data.created_count;
            let updated_count = data.data.updated_count;
            toastr.success('Cập nhật thành công ' + updated_count + ' records. Bổ sung thành công ' + created_count + ' records.', 'Thông báo');
            $("#DialogImportEmployee").modal("hide");
            this.$emit("onUploadData");
          } else {
            this.errors = data.errors;
            toastr.warning('Cập nhật bị lỗi', 'Thông báo');
          }
        }).catch(err => {
          this.loading = false;
          console.log(err);
        });
    },
    importData() {
      this.uploadData = [];
      this.$refs.dataTable.importTable((callback) => {
        this.$refs.dataTable.selected = {};
      },
        (error) => {

        });
    },
    clearAllError() {
      this.errors = {};
    },
    exportDataAsFile() {
      var fileName = "Template công nhân viên";
      this.$refs.templateTable.exportTable("xlsx", true, fileName);
    }
  },
  computed: {
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },

};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>