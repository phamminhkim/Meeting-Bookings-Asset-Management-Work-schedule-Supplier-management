<template>
  <div>
    <div class="form-group row">
      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Chọn loại năng
        suất</label>
      <div class="col-sm-8">
        <treeselect @select="updateRecordType()" @deselect="updateRecordType()" style="font-size: 15px;" placeholder=""
          v-model="filter.record_type" :disable-branch-nodes="true" :multiple="false" :options="record_types"
          :disabled="readOnly">
        </treeselect>
      </div>
      <button v-if="showDownloadTemplate && filter.record_type" class="btn btn-warning btn-sm mt-1 mb-1"
        @click.prevent="exportDataAsFile()">
        <i class="fa fa-download"></i>Tải template
      </button>
      <vue-excel-editor width="0px" height="0px" ref="dataTable" :disable-panel-setting="true"
        :disable-panel-filter="true" :no-footer="true" :no-num-col="true">
        <vue-excel-column v-for="(column, findex) in columnData" v-bind:key="findex" :field="column.field"
          :label="column.label" :type="column.type" :width="column.width" :options="column.options"
          :text-align="column.textAlign" :mandatory="column.required ? 'Trường này không thể bỏ trống' : null" />
      </vue-excel-editor>
    </div>
    <div class="form-group row" v-if="filter.record_type && filter.record_type == 1">
      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Chọn tổ sản
        xuất</label>
      <div class="col-sm-8">
        <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="" v-model="filter.party_id"
          :disable-branch-nodes="true" :multiple="false" :options="tree_parties" :disabled="readOnly">
        </treeselect>
      </div>
    </div>
    <div class="form-group row" v-if="filter.record_type && filter.record_type == 2">
      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Chọn phòng ban
      </label>
      <div class="col-sm-8">
        <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="" v-model="filter.department_id"
          :disable-branch-nodes="true" :multiple="false" :options="tree_departments" :disabled="readOnly">
        </treeselect>
      </div>
    </div>
    <div class="form-group row" v-if="canShowYearField">
      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Chọn năm</label>
      <div class="col-sm-8">
        <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="" v-model="filter.year"
          :disable-branch-nodes="true" :multiple="false" :options="filterYears" :disabled="readOnly">
        </treeselect>
      </div>
    </div>
    <div class="form-group row" v-if="canShowMonthField && filter.year && requireMonth">
      <label for="title" class="col-form-label-sm col-sm-2 col-form-label text-md-right">Chọn
        tháng</label>
      <div class="col-sm-8">
        <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="" v-model="filter.month"
          :disable-branch-nodes="true" :multiple="false" :options="filterMonths" :disabled="readOnly">
        </treeselect>
      </div>
    </div>

  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import VueExcelEditor from 'vue-excel-editor';

export default {
  components: {
    Treeselect
  },
  prop: ['value'],
  model: {
    prop: 'value',
    event: 'updateFilter'
  },
  props: {
    input_filter: Object,
    requireMonth: Boolean,
    readOnly: Boolean,
    showDownloadTemplate: Boolean,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetTreeDepartment();
    this.fetTreeParty();
  },
  data() {
    return {
      filter: this.input_filter || {
        record_type: null,
        department_id: null,
        party_id: null,
        year: null,
        month: null,
      },
      tree_departments: [],
      tree_parties: [],
      record_types: [
        { id: 1, label: 'Năng suất công nhân' },
        { id: 2, label: 'Năng suất QC' },
      ],
      edit: false,
      token: "",
      page_url_department: "/api/category/departments",
      page_url_party: "/api/category/parties",
    };
  },
  methods: {
    updateRecordType() {
      this.filter.department_id = null;
      this.filter.party_id = null;
      this.updateFilter();
    },
    updateFilter() {
      if (!this.canShowYearField) {
        this.filter.year = null;
      }
      if (!this.canShowMonthField) {
        this.filter.month = null;
      }
      this.$emit('updateFilter', this.filter);
      this.$emit("updateTemplate", this.columnData())
    },
    fetTreeDepartment() {
      var page_url = this.page_url_department + "?type=tree_combobox";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.tree_departments = res.data;
        })
        .catch(err => console.log(err));
    },
    fetTreeParty() {
      var page_url = this.page_url_party + "?type=tree_combobox";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.tree_parties = res.data;
        })
        .catch(err => console.log(err));
    },
    exportDataAsFile() {
      var fileName = "Template dữ liệu ";
      if (this.filter.record_type == 1) {
        fileName += "công nhân";
      }
      else if (this.filter.record_type == 2) {
        fileName += "QC";
      }
      this.$refs.dataTable.exportTable("xlsx", true, fileName);
    },
    columnData() {
      if (this.filter.record_type) {
        if (this.filter.record_type == 1) {
          let data = [
            { field: 'stt', label: 'STT', type: 'number', width: "40px", sticky: true, textAlign: 'center' },
            { field: 'staff_code', label: 'MSNV', type: 'number', width: "65px", sticky: true },
            { field: 'name', label: 'Họ và Tên', type: 'string', width: "160px", sticky: true },
            { field: 'totalday_calc', label: 'Số ngày NS', type: 'number', width: "80px", textAlign: 'center' }
          ];
          for (let i = 1; i <= 31; i++) {
            data.push({
              field: 'd' + i,
              label: i.toString(),
              type: 'number',
              width: '35px',
              textAlign: 'center',
            });
          }
          if (this.$refs.dataTable) {
            this.$refs.dataTable.fields = data;
          }

          return data;
        }
        else if (this.filter.record_type == 2) {
          let data = [
            { field: 'stt', label: 'STT', type: 'number', width: "40px", sticky: true, textAlign: 'center' },
            { field: 'staff_code', label: 'MSNV', type: 'number', width: "65px", sticky: true, },
            { field: 'name', label: 'Họ và Tên', type: 'string', width: "160px", sticky: true },
            { field: 'avgpoint', label: 'Điểm bình quân', type: 'number', width: "160px", textAlign: 'center' },
            { field: 'note', label: 'Ghi chú', type: 'string', width: "250px" },
          ];
          if (this.$refs.dataTable) {
            this.$refs.dataTable.fields = data;
          }
          return data;
        }
      }
      return null;
    }
  },
  watch: {
    input_filter() {
      this.filter = this.input_filter;
    }
  },
  computed: {
    canShowYearField() {
      if (this.filter.record_type) {
        if (this.filter.record_type == 1) {
          return this.filter.party_id !== null && this.filter.party_id !== undefined;
        }
        else if (this.filter.record_type == 2) {
          return this.filter.department_id !== null && this.filter.department_id !== undefined;
        }
      }
      return false;
    },
    canShowMonthField() {
      let chooseYear = this.filter.year;
      let currentYear = new Date().getFullYear();
      if (chooseYear <= currentYear) {
        return true;
      }
      return false;
    },
    filterYears() {
      let years = [];
      let currentYear = new Date().getFullYear();
      for (let i = 2022; i <= currentYear; i++) {
        years.push({ id: i, label: i });
      }
      return years;
    },
    filterMonths() {
      let chooseYear = this.filter.year;
      let currentYear = new Date().getFullYear();
      let totalMonth = 0;
      let months = [];
      if (chooseYear < currentYear) {
        totalMonth = 12;
      }
      else if (chooseYear == currentYear) {
        totalMonth = new Date().getMonth() + 1;
      }
      for (let i = 1; i <= totalMonth; i++) {
        months.push({ id: i, label: i });
      }
      return months;
    },
  },

};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>
