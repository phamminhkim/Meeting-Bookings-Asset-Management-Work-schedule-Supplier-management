<template>
  <div>
    <div class="content-header ">
      <div class="container-fluid ml-0">
        <div class="row">
          <div class="col-md-6">
            <ol class="breadcrumb ">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark">
                  <i class="nav-icon fas fa-industry"></i>
                </h5>
              </li>

              <li class="breadcrumb-item active">
                <span>{{$t('form.detail')}}</span>
              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <div class="float-sm-right " style="margin-top:-5px; ">
              <download-excel class="btn btn-info btn-sm ml-1 mt-1" :fields="fieldexcel" :fetch="fetchDataExcel"
                :before-generate="startDownload" :before-finish="finishDownload" type="xls"
                :name="fetchExcelFileName()">
                <i class="fas fa-file-excel"></i>
                Xuất bảng lương
              </download-excel>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ----------------- -->
    <b-overlay :show="loading" rounded="sm">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">

                <label for="title" class="col-form-label-sm col-sm-2   col-form-label text-md-right">Công ty</label>
                <div class="col-sm-4">
                  <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="Chọn công ty"
                    v-model="filter.company_id" :disable-branch-nodes="true" :multiple="false"
                    :options="tree_companies">
                  </treeselect>
                </div>

                <div v-if="canShowYearField" class="col-sm-2">
                  <treeselect :onchange="updateFilter()" style="font-size: 15px;" placeholder="Chọn năm"
                    v-model="filter.year" :disable-branch-nodes="true" :multiple="false" :options="filterYears">
                  </treeselect>
                </div>

                <div v-if="canShowMonthField && filter.year" class="col-sm-2">
                  <treeselect style="font-size: 15px;" placeholder="Chọn tháng" v-model="filter.month"
                    :disable-branch-nodes="true" :multiple="false" :options="filterMonths">
                  </treeselect>
                </div>
              </div>


              <div class="form-group row" v-if="filter.year && filter.month">
                <div class="col-md-12" style="text-align: center">
                  <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1" @click="startSearching()">
                    <i class="fa fa-search"></i> Tra tìm dữ liệu
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row" v-if="document.records && document.records.length">
        <div class="col-md-12">
          <div class="card">
            <div class="">
              <div class="p-2" style="text-align:center;background-color:#28659C;color:white" v-if="form_title">
                {{form_title}} tháng {{filter.month}} năm {{filter.year}}</div>

            </div>

            <div class="card-body">
              <b-table striped hover responsive :bordered="true" head-variant="light" small :items="document.records"
                :fields="columnData" ref="selectableTable">
                <template #cell(index)="data">
                  {{data.index + 1}}
                </template>
                <template v-slot:cell(selected)="data">
                  <b-form-checkbox class="ml-1" :value="data.item.staff_code" v-model="selected"></b-form-checkbox>
                </template>
                <template #cell(user_name)="data">
                  {{getEmployeeName(data.item.staff_code)}}
                </template>
                <template #cell(department_id)="data">
                  {{getDepartmentName(data.item.department_id)}}
                </template>
                <template #cell(workshop_id)="data">
                  {{getWorkshopName(data.item.workshop_id)}}
                </template>
                <template #cell(party_id)="data">
                  {{getPartyName(data.item.party_id)}}
                </template>

                <template #cell(final_day)="data">
                  <b-popover
                    v-if="data.item.hrupdate_day && data.item.hrupdate_day.total_day != data.item.original.total_day"
                    :target="'total_day_' + data.item.staff_code" placement="auto" :title="'Điều chỉnh ngày năng suất'"
                    triggers="hover focus">
                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>Ngày năng suất (hành chính tổng hợp)</td>
                          <td>{{data.item.original.total_day}}</td>
                        </tr>
                        <tr>
                          <td>Ngày năng suất (nhân sự)</td>
                          <td>{{data.item.hrupdate_day.total_day}}</td>
                        </tr>
                        <tr>
                          <td>Ghi chú</td>
                          <td>{{data.item.hrupdate_day.note}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật lúc</td>
                          <td>{{data.item.hrupdate_day.updated_at}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật bởi</td>
                          <td>{{data.item.hrupdate_day.updated_by}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-popover>
                  <span v-if="!data.item.hrupdate_day">
                    Thiếu dữ liệu nhân sự
                  </span>
                  <span
                    v-else-if="data.item.hrupdate_day && data.item.hrupdate_day.total_day != data.item.original.total_day"
                    class="badge bg-warning">
                    {{data.item.hrupdate_day.total_day}}  / {{data.item.hrupdate_day.salary_day}}
                    <i :id="'total_day_' + data.item.staff_code" class="fas fa-question-circle"></i>
                  </span>
                  <span v-else class="badge bg-success">
                    {{data.item.original.total_day}}  / {{data.item.hrupdate_day.salary_day}}
                  </span>
                </template>

                <template #cell(final_mark)="data">
                  <b-popover v-if="data.item.final_mark && data.item.hrupdate_mark"
                    :target="'help_' + data.item.staff_code" placement="auto" :title="'Điều chỉnh điểm năng suất'"
                    triggers="hover focus">
                    <template #title>Điều chỉnh điểm năng suất</template>
                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>Điểm ban đầu</td>
                          <td>{{data.item.original.mark}}</td>
                        </tr>
                        <tr>
                          <td>Điểm tăng/giảm</td>
                          <td>{{ data.item.hrupdate_mark.additional_mark > 0 ? '+' : '' }}{{
                          data.item.hrupdate_mark.additional_mark }}</td>
                        </tr>
                        <tr>
                          <td>Điểm cuối cùng</td>
                          <td>{{data.item.final_mark}}</td>
                        </tr>
                        <tr>
                          <td>Lí do</td>
                          <td>{{data.item.hrupdate_mark.update_reason}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật lúc</td>
                          <td>{{data.item.hrupdate_mark.update_at}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật bởi</td>
                          <td>{{data.item.hrupdate_mark.update_by}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-popover>
                  <span v-if="!data.item.final_mark">
                    Không đủ dữ liệu
                  </span>
                  <span v-else-if="data.item.hrupdate_mark"
                    :class="data.item.hrupdate_mark.additional_mark > 0 ? 'badge bg-success' : 'badge bg-danger'">
                    {{data.item.final_mark}}
                    <i :id="'help_' + data.item.staff_code" class="fas fa-question-circle"></i>
                  </span>
                  <span v-else>
                    {{data.item.final_mark}}
                  </span>
                </template>

                <template #cell(final_rank)="data">
                  <b-popover v-if="data.item.final_rank && data.item.original.rank != data.item.final_rank"
                    :target="'help_rank_' + data.item.staff_code" placement="auto"
                    :title="'Điều chỉnh xếp loại năng suất'" triggers="hover focus">
                    <template #title>Điều chỉnh xếp loại năng suất</template>
                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>Xếp loại ban đầu</td>
                          <td>{{data.item.original.rank}}</td>
                        </tr>
                        <tr>
                          <td>Xếp loại cuối cùng</td>
                          <td>{{data.item.final_rank}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-popover>
                  <span v-if="!data.item.final_rank">
                    Không đủ dữ liệu
                  </span>
                  <span v-else-if="data.item.original.rank != data.item.final_rank"
                    :class="data.item.hrupdate_mark.additional_mark > 0 ? 'badge bg-success' : 'badge bg-danger'">
                    {{data.item.final_rank}}
                    <i :id="'help_rank_' + data.item.staff_code" class="fas fa-question-circle"></i>
                  </span>
                  <span v-else>
                    {{data.item.final_rank}}
                  </span>
                </template>

                <template #cell(final_salary)="data">
                  <span><strong>{{ data.value.toLocaleString("de-DE").replaceAll('.', ',') }}
                    </strong></span>
                </template>
              </b-table>


            </div>
          </div>
        </div>
      </div>
      <div v-else-if="document.records">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              Không có dữ liệu
            </div>

          </div>
        </div>

      </div>
    </b-overlay>

  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
  components: {
    Treeselect
  },
  props: {
    id: String,
    user_id: String,
    input_filter: Object,
    form_title: String
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchCompany();
    this.fetTreeCompany();
    this.fetchDepartment();
    this.fetchWorkshop();
    this.fetchParty();
    this.getEmployees();
    //this.getDocument();
  },
  data() {
    return {
      filter: this.input_filter,
      document: {},
      record: [],
      companies: [],
      departments: [],
      workshops: [],
      parties: [],
      tree_companies: [],
      fieldexcel: {
        "STT": "STT",
        "MSNV": "MSNV",
        "HỌ TÊN": "HO_TEN",
        "CÔNG TY": "CONG_TY",
        "BP": "BP",
        "XƯỞNG": "XUONG",
        "TỔ": "TO",
        "NGÀY NĂNG SUẤT": "NGAYNANGSUAT",
        "NGÀY THEO LỊCH": "NGAYTHEOLICH",
        "TỔNG ĐIỂM": "TONGDIEM",
        "ĐIỂM": "DIEM",
        "LOẠI": "LOAI",
        "LƯƠNG": "LUONG",
      },
      employees: [],
      errors: {},
      loading: false,
      token: "",
      page_url_final: "/api/productivity/final",
      page_url_employees: "api/category/employees",
      page_url_company: "/api/category/companies",
      page_url_department: "/api/category/departments",
      page_url_workshop: "/api/category/workshops",
      page_url_party: "/api/category/parties",
    }
  },
  methods: {

    //Excel function 
    fetchExcelFileName() {
      return "Lương năng suất_" + this.getCompanyName(this.filter.company_id) + "_" + this.filter.year + "_" + this.filter.month;
    },
    finishDownload() {
      toastr.success("Download thành công", "");
    },
    startDownload() {
      if (this.document && this.document.records && this.document.records.length > 0) {
        toastr.info("Đang download....", "");
      }
    },
    fetchDataExcel() {
      if (this.document && this.document.records && this.document.records.length > 0) {
        let data = [];
        let index = 0;
        this.document.records.forEach(row => {
          index++;
          data.push({
            'STT': index,
            'MSNV': row.staff_code,
            'HO_TEN': this.getEmployeeName(row.staff_code),
            'CONG_TY': this.getCompanyName(row.company_id),
            'BP': this.getDepartmentName(row.department_id),
            'XUONG': this.getWorkshopName(row.workshop_id),
            'TO': this.getPartyName(row.party_id),
            'NGAYNANGSUAT': row.hrupdate_day ? row.hrupdate_day.total_day : 'Không đủ dữ liệu',
            'NGAYTHEOLICH': row.hrupdate_day ? row.hrupdate_day.salary_day : 'Không đủ dữ liệu',
            'TONGDIEM': row.total_mark ? row.total_mark : 'Không đủ dữ liệu',
            'DIEM': row.final_mark ? row.final_mark : 'Không đủ dữ liệu',
            'LOAI': row.final_rank ? row.final_rank : 'Không đủ dữ liệu',
            'LUONG': row.final_salary,
          })
        });

        return data;
      } else {
        toastr.warning("Không tìm thấy dữ liệu", "");
      }
    },
    updateFilter() {
      if (!this.canShowYearField) {
        this.filter.year = null;
      }
      if (!this.canShowMonthField) {
        this.filter.month = null;
      }
    },
    fetchCompany() {
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.companies = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetchDepartment() {
      var page_url = this.page_url_department;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.departments = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetchWorkshop() {
      var page_url = this.page_url_workshop;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.workshops = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetchParty() {
      var page_url = this.page_url_party;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.parties = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getEmployees() {
      var page_url = this.page_url_employees;
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.employees = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fetTreeCompany() {
      var page_url = this.page_url_company + "?type=tree_combobox";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.tree_companies = res.data;
        })
        .catch(err => console.log(err));
    },
    getCompanyName(company_id) {
      var obj = this.companies.find(x => x.id == company_id);
      return obj != null ? obj.name : '';
    },
    getDepartmentName(department_id) {
      var obj = this.departments.find(x => x.id == department_id);
      return obj != null ? obj.name : '';
    },
    getWorkshopName(workshop_id) {
      var obj = this.workshops.find(x => x.id == workshop_id);
      return obj != null ? obj.name : '';
    },
    getPartyName(party_id) {
      var obj = this.parties.find(x => x.id == party_id);
      return obj != null ? obj.name : '';
    },
    getEmployeeName(employee_id) {
      var obj = this.employees.find(x => x.employee_id == employee_id);
      return obj != null ? obj.name : '';
    },
    getRecordName(record_type) {
      if (record_type == 1) {
        return "Năng suất công nhân";
      }
      else if (record_type == 1) {
        return "Năng suất QC";
      }
      return '';
    },
    startSearching() {
      const params = new URLSearchParams(this.filter);

      this.loading = true;
      var page_url = this.page_url_final + "?" + params.toString();
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        }
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.statuscode == "403") {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          this.document = res.data;
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
  },
  computed: {
    canShowYearField() {
      return this.filter.company_id !== null && this.filter.company_id !== undefined;
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

    columnData() {
      let columns = [{
        key: "index",
        label: "STT",
        class: "text-center text-bold",
        sortable: false,
      },
      {
        key: "staff_code",
        label: "Mã NV",
        class: "text-center text-bold",
        sortable: true,
      },
      {
        key: "user_name",
        label: "Họ tên",
        class: "text-center",
        sortable: false,
      },
      {
        key: "department_id",
        label: "Phòng ban",
        class: "text-center",
        sortable: true,
      },
      {
        key: "workshop_id",
        label: "Xưởng SX",
        class: "text-center",
        sortable: true,
      },
      {
        key: "party_id",
        label: "Tổ SX",
        class: "text-center",
        sortable: true,
      },
      {
        key: "final_day",
        label: "Ngày công / Ngày tính lương",
        class: "text-center text-bold",
        sortable: true,
      },
      {
        key: "total_mark",
        label: "Tổng điểm",
        class: "text-center text-bold",
        sortable: true,
      },
      {
        key: "final_mark",
        label: "Điểm trung bình",
        class: "text-center text-bold",
        sortable: true,
      },
      {
        key: "final_rank",
        label: "Xếp loại",
        class: "text-center text-bold",
        sortable: false,
      },
      {
        key: "final_salary",
        label: "Lương năng suất",
        class: "text-center text-bold",
        sortable: false,
      }];



      return columns;
    }
  }
}
</script>

<style  scoped>
.form-group {
  margin-bottom: 1px !important;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-weight: normal !important;
  ;
  line-height: 1.2 !important;
  ;
}

h3,
.h3 {
  font-size: 1.15rem !important;
}

h3 {
  display: block !important;
  ;
  font-size: 1.17em !important;
  margin-block-start: 1em !important;
  ;
  margin-block-end: 1em;
  margin-inline-start: 0px !important;
  ;
  margin-inline-end: 0px !important;
  ;
  font-weight: bold !important;
  ;
}
</style>
