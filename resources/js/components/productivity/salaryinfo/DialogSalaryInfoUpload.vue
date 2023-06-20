<template>
  <div class="modal fade" id="modalDaySalaryUpload" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">

      <div class="modal-content card">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">
            <span>{{ title }}</span>
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
              <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Tải lên file ngày lương</label>
              <div class="col-sm-8">
                <button class="btn btn-success btn-sm mt-1 mb-1" @click.prevent="importData">
                  <i class="fa fa-upload"></i> (*.xlsx)
                </button>
                <button class="btn btn-warning btn-sm mt-1 mb-1"
                  @click.prevent="exportDataAsFile()">
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
              <vue-excel-editor ref="dataTable"  v-model="uploadData" :disable-panel-setting="true"
                :disable-panel-filter="true" :no-footer="true" :no-num-col="true">
                <vue-excel-column v-for="(column, findex) in template" v-bind:key="findex" :field="column.field"
                  :label="column.label" :type="column.type" :width="column.width" :options="column.options"
                  :text-align="column.textAlign"
                  :mandatory="column.required ? 'Trường này không thể bỏ trống' : null" />

              </vue-excel-editor>
            </div>
          </b-overlay>
        </div>

        <div class="modal-footer">
          <button type="button" @click.prevent="uploadDaySalary()" :disabled="uploadData.length == 0"
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
  props: {
    title: String,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  data() {
    return {
      template: [
        { field: 'staff_code', label: 'MSNV', type: 'number', width: "65px", sticky: true, },
        { field: 'year', label: 'Năm', type: 'number', width: "50px", sticky: true },
        { field: 'month', label: 'Tháng', type: 'number', width: "50px", sticky: true },
        { field: 'totalday_calc', label: 'Số ngày tính lương', type: 'number', width: "100px", sticky: true },
        { field: 'totalday_salary', label: 'Số ngày theo lịch', type: 'number', width: "100px", sticky: true },
        { field: 'note', label: 'Ghi chú', type: 'string', width: "160px", sticky: true }
      ],
      uploadData: [],
      errors: {},
      loading: false,
      token: "",
      page_url_daysalary: "/api/productivity/daysalaryUpload",
    };
  },
  methods: {
    uploadDaySalary() {
      this.loading = true;
      var page_url = this.page_url_daysalary;
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
          if (data.data.success == 1) {
            let createdCount = data.data.createdCount;
            let updatedCount = data.data.updatedCount;
            toastr.success('Cập nhật thành công ' + updatedCount + ' records. Bổ sung thành công ' + createdCount + ' records.', 'Thông báo');
            $("#modalDaySalaryUpload").modal("hide");
            this.$emit("onUploadData");
          } else {
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
      var fileName = "Template ngày công";
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
