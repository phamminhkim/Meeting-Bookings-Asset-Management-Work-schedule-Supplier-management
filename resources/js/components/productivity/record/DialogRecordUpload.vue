<template>
  <div class="modal fade" id="modalRecordUpload" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

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
            <record-filter v-model="filter" :showDownloadTemplate="true" :input_filter="input_filter"
              v-on:updateFilter="updateFilter" v-on:updateTemplate="updateTemplate" :requireMonth="true"
              :readOnly="true"></record-filter>
            <div class="form-group row" v-if="filter.month">
              <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Tải lên file năng
                suất</label>
              <div class="col-sm-8">
                <button class="btn btn-success btn-sm mt-1 mb-1" @click.prevent="importData">
                  <i class="fa fa-upload"></i> (*.xlsx)
                </button>

              </div>
            </div>
            <div class="form-group row" v-show="uploadData.length > 0">
              <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right"></label>
              <div class="col-sm-8">
                <strong> Đã tải lên {{ uploadData.length }} dòng dữ liệu </strong>
              </div>
            </div>
            <div class="form-group row" v-show="uploadData.length > 0">
              <vue-excel-editor ref="dataTable" v-model="uploadData" :disable-panel-setting="true"
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
          <button type="button" @click.prevent="uploadRecord()" 
              :disabled="uploadData.length == 0"
              :title="uploadData.length == 0 ? 'Chưa nhập dữ liệu' : 'Cập nhật'" class="btn btn-primary mr-auto">Cập nhật</button>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $t('form.close')
          }}</button>
        </div>
      </div>

    </div>



  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import VueExcelEditor from 'vue-excel-editor';
import RecordFilter from "./RecordFilter.vue";

export default {
  components: {
    Treeselect,
    RecordFilter
  },
  props: {
    title: String,
    input_filter: Object
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  data() {
    return {
      tree_companies: [],
      filter: this.input_filter || {
        record_type: null,
        department_id: null,
        party_id: null,
        year: null,
        month: null,
      },
      template: [],
      uploadData: [],
      record_types: [
        { id: 1, name: 'Năng suất công nhân' },
        { id: 2, name: 'Năng suất QC' },
      ],
      errors: {},
      loading: false,
      token: "",
      page_url_record: "/api/productivity/records",
    };
  },
  methods: {
    updateFilter() {
      this.uploadData = [];
    },
    updateTemplate(newTemplate) {
      this.template = newTemplate;
    },
    uploadRecord() {
      this.loading = true;
      var page_url = this.page_url_record;
      fetch(page_url, {
        method: "post",
        body: JSON.stringify({
          'filter': this.filter,
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
          if (data.success) {
            let createdCount = data.data.createdCount;
            let updatedCount = data.data.updatedCount;
            let deletedCount = data.data.deletedCount;

            $("#modalRecordUpload").modal("hide");
            this.$emit("onUploadData", data.data.updatedData);
            toastr.success('Cập nhật thành công ' + updatedCount + ' records. Bổ sung thành công ' + createdCount + ' records. Xóa thành công ' + deletedCount + ' records.', 'Thành công');
          } else {
            toastr.error(data.message, 'Thất bại');
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
  },
  computed: {
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
  watch: {
    input_filter() {
      this.filter = this.input_filter;
    }
  }

};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>
