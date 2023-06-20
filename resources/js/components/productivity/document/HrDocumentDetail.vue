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
                <span>{{$t('form.detail')}}
                  <demo></demo>
                </span>
              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <div class="float-sm-right " style="margin-top:-5px; ">

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ----------------- -->
    <b-overlay :show="loading" rounded="sm">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="">
              <div class="p-2" style="text-align:center;background-color:#28659C;color:white" v-if="form_title">
                {{form_title}}</div>
              <div class="d-flex  justify-content-between m-3" style="border-bottom:1px solid #CEE2EE">
                <div>
                  <span class="mute small" v-if="document.company_id"><i>{{$t('form.company')}}: <strong>
                        {{getCompanyName(document.company_id)}}</strong></i></span>
                  <br v-if="document.company_id">
                  <span class="mute small" v-if="document.department_id"><i>{{$t('form.department')}}: <strong>
                        {{getDepartmentName(document.department_id)}}</strong></i></span>
                  <br v-if="document.department_id">
                  <span class="mute small" v-if="document.workshop_id"><i>{{$t('form.workshop')}}: <strong>
                        {{getWorkshopName(document.workshop_id)}}</strong></i></span>
                  <br v-if="document.workshop_id">
                  <span class="mute small" v-if="document.party_id"><i>{{$t('form.party')}}: <strong>
                        {{getPartyName(document.party_id)}}</strong></i></span>
                </div>
                <div>
                  <span class="mute small" v-if="document.year"><i>{{$t('form.year')}}: <strong>
                        {{document.year}}</strong></i></span>
                  <br v-if="document.year">
                  <span class="mute small" v-if="document.month"><i>{{$t('form.month')}}: <strong>
                        {{document.month}}</strong></i></span>
                  <br v-if="document.month">
                </div>
                <div>
                  <span class="mute small" v-if="document.productivity_type"><i>Loại dữ liệu: <strong>
                        {{getRecordName(document.productivity_type)}}</strong></i></span>
                  <br v-if="document.productivity_type">
                  <span class="mute small" v-if="document.status"><i>Trạng thái:</i> <span v-if="document.status == 5"
                      class="badge bg-primary">Đã hoàn thành</span>
                    <span v-else-if="document.status == 4" class="badge bg-success">Đã duyệt dữ liệu</span>
                    <span v-else-if="document.status == 3" class="badge bg-danger">Phản hồi</span>
                    <span v-else-if="document.status == 2" class="badge bg-info">Đã gửi duyệt</span>
                    <span v-else-if="document.status == 1" class="badge bg-warning">Đã nhập dữ liệu</span>
                    <span v-else-if="document.status == 0" class="badge bg-secondary">Chưa có dữ liệu</span>
                  </span>
                  <br v-if="document.status">
                </div>
              </div>
            </div>

            <div class="card-body">
              <b-table v-if="document.records" striped hover responsive :bordered="true" head-variant="light" small
                :items="document.records" :fields="columnData" ref="selectableTable">
                <template #cell(index)="data">
                  {{data.index + 1}}
                </template>
                <template v-slot:cell(selected)="data">
                  <b-form-checkbox class="ml-1" :value="data.item.staff_code" v-model="selected"></b-form-checkbox>
                </template>
                <template #cell(user_name)="data">
                  {{getEmployeeName(data.item.staff_code)}}
                </template>
                <template #cell(final_mark)="data">
                  <b-popover v-if="data.item.additional_mark" :target="'help_' + data.item.staff_code" placement="auto"
                    :title="'Điều chỉnh điểm năng suất'" triggers="hover focus">
                    <template #title>Điều chỉnh điểm năng suất</template>
                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>Điểm ban đầu</td>
                          <td>{{data.item.original_mark}}</td>
                        </tr>
                        <tr>
                          <td>Điểm tăng/giảm</td>
                          <td>{{ data.item.additional_mark > 0 ? '+' : '' }}{{ data.item.additional_mark }}</td>
                        </tr>
                        <tr>
                          <td>Điểm cuối cùng</td>
                          <td>{{data.item.final_mark}}</td>
                        </tr>
                        <tr>
                          <td>Lí do</td>
                          <td>{{data.item.update_mark_reason}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật lúc</td>
                          <td>{{data.item.update_mark_at}}</td>
                        </tr>
                        <tr>
                          <td>Cập nhật bởi</td>
                          <td>{{data.item.update_mark_by}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-popover>
                  <span v-if="data.item.additional_mark"
                    :class="data.item.additional_mark > 0 ? 'badge bg-success' : 'badge bg-danger'">
                    {{data.item.final_mark}}
                    <i :id="'help_' + data.item.staff_code" class="fas fa-question-circle"></i>
                  </span>
                  <span v-else>
                    {{data.item.final_mark}}
                  </span>
                </template>

                <template #cell(final_rank)="data">
                  <b-popover v-if="data.item.original_rank != data.item.final_rank"
                    :target="'help_rank_' + data.item.staff_code" placement="auto"
                    :title="'Điều chỉnh xếp loại năng suất'" triggers="hover focus">
                    <template #title>Điều chỉnh xếp loại năng suất</template>
                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>Xếp loại ban đầu</td>
                          <td>{{data.item.original_rank}}</td>
                        </tr>
                        <tr>
                          <td>Xếp loại cuối cùng</td>
                          <td>{{data.item.final_rank}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </b-popover>
                  <span v-if="data.item.original_rank != data.item.final_rank"
                    :class="data.item.additional_mark > 0 ? 'badge bg-success' : 'badge bg-danger'">
                    {{data.item.final_rank}}
                    <i :id="'help_rank_' + data.item.staff_code" class="fas fa-question-circle"></i>
                  </span>
                  <span v-else>
                    {{data.item.final_rank}}
                  </span>
                </template>
              </b-table>


            </div>
          </div>
        </div>
        <div class="col-md-4 ">
          <approve-form v-bind:object="document" :showstep="''" :type="'HR'" :user_id="user_id"></approve-form>
        </div>
      </div>
    </b-overlay>

  </div>
</template>

<script>
import demo from './demo.vue';
export default {
  components: { demo },

  props: {
    id: String,
    user_id: String,
    filter: Object,
    form_title: String
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchCompany();
    this.fetchDepartment();
    this.fetchWorkshop();
    this.fetchParty();
    this.getEmployees();
    this.getDocument();
  },
  data() {
    return {
      document: {},
      record: [],
      companies: [],
      departments: [],
      workshops: [],
      parties: [],
      employees: [],
      edit: false,
      errors: {},
      loading: false,
      token: "",
      page_url_document: "/api/productivity/documents",
      page_url_employees: "api/category/employees",
      page_url_company: "/api/category/companies",
      page_url_department: "/api/category/departments",
      page_url_workshop: "/api/category/workshops",
      page_url_party: "/api/category/parties",
    }
  },
  methods: {
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
    getDocument() {
      this.loading = true;
      var page_url = this.page_url_document + "/" + this.id;
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
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
        class: "text-center text-bold",
        sortable: false,
      },
      {
        key: "final_mark",
        label: "Điểm trung bình (tháng)",
        class: "text-center text-bold",
        sortable: true,
      },
      {
        key: "final_rank",
        label: "Xếp loại (tháng)",
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
