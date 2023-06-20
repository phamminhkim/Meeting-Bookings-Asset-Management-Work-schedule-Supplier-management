<template>
  <div>
    <div class="content-header ">
      <div class="container-fluid ml-0">
        <div class="row">
          <div class="col-md-6">
            <ol class="breadcrumb ">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark"><i class="nav-icon fas fa-industry"></i></h5>
              </li>

              <li class="breadcrumb-item active">
                <span>{{$t('form.detail')}}</span>
              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <div v-if="record && record.actions" class="float-sm-right " style="margin-top:-5px;">
              <button v-if="record.actions.update_data" class="btn btn-default" @click.prevent="uploadRecord()">
                <i v-if="record.total_records == 0" class="fas fa-upload text-success">Tải lên dữ liệu</i>
                <i v-else class="fas fa-sync text-warning">Cập nhật dữ liệu</i>
              </button>
              <button v-if="record.actions.export_data" class="btn btn-default" @click.prevent="exportDocument()">
                <i class="fas fa-file-export text-info">Xuất trình duyệt (tháng)</i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ----------------- -->
    <b-overlay :show="loading" rounded="sm">
      <div class="row">
        <div class="col-12">
          <div v-if="hasAnyError >0">

            <div class="alert alert-warning">
              <button type="button" class="close" @click.prevent="clearAllError()">
                <i class="ace-icon fa fa-times"></i>
              </button>
              <ul>
                <li v-for="(err,index) in errors" v-bind:key="index">{{err}}</li>

              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="">
              <div class="p-2" style="text-align:center;background-color:#28659C;color:white" v-if="form_title">
                {{form_title}}</div>
              <div class="d-flex  justify-content-between m-3" style="border-bottom:1px solid #CEE2EE">
                <div>
                  <span class="mute small" v-if="record.company_id"><i>{{$t('form.company')}}: <strong>
                        {{getCompanyName(record.company_id)}}</strong></i></span>
                  <br v-if="record.company_id">
                  <span class="mute small" v-if="record.department_id"><i>{{$t('form.department')}}: <strong>
                        {{getDepartmentName(record.department_id)}}</strong></i></span>
                  <br v-if="record.department_id">
                  <span class="mute small" v-if="record.workshop_id"><i>{{$t('form.workshop')}}: <strong>
                        {{getWorkshopName(record.workshop_id)}}</strong></i></span>
                  <br v-if="record.workshop_id">
                  <span class="mute small" v-if="record.party_id"><i>{{$t('form.party')}}: <strong>
                        {{getPartyName(record.party_id)}}</strong></i></span>
                </div>
                <div>
                  <span class="mute small" v-if="record.year"><i>{{$t('form.year')}}: <strong>
                        {{record.year}}</strong></i></span>
                  <br v-if="record.year">
                  <span class="mute small" v-if="record.year"><i>{{$t('form.month')}}: <strong>
                        {{record.month}}</strong></i></span>
                  <br v-if="record.month">
                  <span class="mute small" v-if="record.last_updated"><i>Ngày cập nhật cuối: <strong>
                        {{record.last_updated}}</strong></i></span>
                  <br v-if="record.last_updated">
                  <span class="mute small" v-if="record.last_updated_by"><i>Người cập nhật cuối: <strong>
                        {{record.last_updated_by.name}} - {{record.last_updated_by.username}}</strong></i></span>
                  <br v-if="record.last_updated_by">
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-2 ">Loại dữ liệu:</span>
                    <div class="col-sm-10">
                      <label class="col-form-label-sm">{{getRecordName(record.record_type)}}</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-2 ">Trạng thái:</span>
                    <div class="col-sm-10">
                      <span v-if="record.status == 5" class="badge bg-primary">Đã hoàn thành</span>
                      <span v-else-if="record.status == 4" class="badge bg-success">Đã duyệt dữ liệu</span>
                      <span v-else-if="record.status == 3" class="badge bg-danger">Phản hồi</span>
                      <span v-else-if="record.status == 2" class="badge bg-info">Đã gửi duyệt</span>
                      <span v-else-if="record.status == 1" class="badge bg-warning">Đã nhập dữ liệu</span>
                      <span v-else-if="record.status == 0" class="badge bg-secondary">Chưa có dữ liệu</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-2 ">Tổng số bản ghi:</span>
                    <div class="col-sm-10">
                      <label class="col-form-label-sm">{{record.total_records}}</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <span class="col-form-label-sm col-sm-2 ">Tổng số người:</span>
                    <div class="col-sm-10">
                      <label class="col-form-label-sm">{{record.total_users}}</label>
                    </div>
                  </div>
                  <b-table striped hover responsive :bordered="true" head-variant="light" small :items="record.records"
                    :fields="columnData" ref="selectableTable">
                    <template #thead-top>
                      <b-tr>
                        <b-th class="text-center text-bold" colspan="3">{{getRecordName(record.record_type)}}</b-th>
                        <b-th v-if="record.month" class="text-center text-bold" colspan="33" variant="primary">Tháng {{
                        record.month }} / {{
                          record.year }}</b-th>
                      </b-tr>
                    </template>
                    <template #cell(index)="data">
                      {{data.index + 1}}
                    </template>
                    <template #cell(total_dayoff)="data">
                      <b-popover v-if="data.item.total_days_off > 0" :target="'dayoff_' + data.item.staff_code"
                        placement="auto" :title="'Thông tin ngày nghỉ'" triggers="hover focus">
                        <table class="table table-borderless">
                          <tbody>
                            <tr>
                              <th> Ngày nghỉ </th>
                              <th> Lí do </th>
                              <th> Cập nhật lúc </th>
                              <th> Cập nhật bởi </th>
                            </tr>
                            <tr v-for="dayoff in data.item.days_off" :key="dayoff.day">
                              <td>{{dayoff.day}}/{{dayoff.month}}</td>
                              <td>{{dayoff.reason ? dayoff.reason : 'Không có'}}</td>
                              <td>{{getDateAndTimeString(dayoff.updated_at)}}</td>
                              <td>{{getUserNameById(dayoff.updated_by)}}</td>
                            </tr>
                          </tbody>
                        </table>

                      </b-popover>

                      <i v-if="data.item.total_days_off > 0" :id="'dayoff_' + data.item.staff_code"
                        class="fas fa-exclamation-triangle text-warning"></i>
                    </template>

                    <template #cell(user_name)="data">
                      {{getEmployeeName(data.item.staff_code)}}
                    </template>

                    <template #cell(total_day)="data">
                      <b-popover v-if="data.item.hr_total_day && data.item.base_total_day != data.item.hr_total_day" :target="'total_day_' + data.item.staff_code"
                        placement="auto" :title="'Điều chỉnh ngày năng suất'" triggers="hover focus">
                        <table class="table table-borderless">
                          <tbody>
                            <tr>
                              <td>Ngày năng suất (hành chính tổng hợp)</td>
                              <td>{{data.item.base_total_day}}</td>
                            </tr>
                            <tr>
                              <td>Ngày năng suất (nhân sự)</td>
                              <td>{{data.item.hr_total_day}}</td>
                            </tr>
                            <tr>
                              <td>Ghi chú</td>
                              <td>{{data.item.hr_note}}</td>
                            </tr>
                            <tr>
                              <td>Cập nhật lúc</td>
                              <td>{{data.item.hr_total_day_updated_at}}</td>
                            </tr>
                            <tr>
                              <td>Cập nhật bởi</td>
                              <td>{{data.item.hr_total_day_updated_by}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </b-popover>
                      <span v-if="data.item.hr_total_day && data.item.base_total_day != data.item.hr_total_day"
                        class="badge bg-warning">
                        {{data.item.hr_total_day}}
                        <i :id="'total_day_' + data.item.staff_code" class="fas fa-question-circle"></i>
                      </span>
                      <span v-else class="badge bg-success">
                        {{data.item.base_total_day}}
                      </span>
                    </template>

                  </b-table>
                </div>
              </div>


            </div>
          </div>
        </div>
        <div class="col-md-4 ">

        </div>
      </div>
    </b-overlay>
    <dialog-record-upload :input_filter="this.filter" v-on:onUploadData="onUploadData"
      :title="record.total_records == 0 ? 'Tải lên dữ liệu' : 'Cập nhật dữ liệu'"></dialog-record-upload>
  </div>
</template>

<script>
import DialogRecordUpload from './DialogRecordUpload.vue';
export default {
  components: {
    DialogRecordUpload
  },
  props: {
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
    this.getUsers();
    this.getEmployees();
    this.getRecordList();
  },
  data() {
    return {
      record: [],
      companies: [],
      departments: [],
      workshops: [],
      parties: [],
      users: [],
      employees: [],
      edit: false,
      errors: {},
      loading: false,
      token: "",
      page_url_record: "/api/productivity/records",
      page_url_document: "/api/productivity/documents",
      page_url_employees: "api/category/employees",
      page_url_users: "api/user/all",
      page_url_company: "/api/category/companies",
      page_url_department: "/api/category/departments",
      page_url_workshop: "/api/category/workshops",
      page_url_party: "/api/category/parties",
    }
  },
  methods: {
    getDateAndTimeString(date) {
      let objDate = new Date(date);
      return objDate.toLocaleTimeString('en-GB') + ' ' + objDate.toLocaleDateString('en-GB')
    },
    onUploadData(updatedData) {
      if (this.record.id == updatedData.id) {
        this.getRecordList();
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
    getUsers() {
      var page_url = this.page_url_users;
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.users = data.data;
        })
        .catch((err) => {
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
    getUserNameById(id) {
      var obj = this.users.find(x => x.id == id);
      return obj != null ? obj.name : 'ADMIN';
    },
    getEmployeeName(employee_id) {
      var obj = this.employees.find(x => x.employee_id == employee_id);
      return obj != null ? obj.name : '';
    },
    getRecordName(record_type) {
      if (record_type == 1) {
        return "Năng suất công nhân";
      }
      else if (record_type == 2) {
        return "Năng suất QC";
      }
      return '';
    },
    getRecordList() {
      const params = new URLSearchParams(this.filter);

      this.loading = true;
      var page_url = this.page_url_record + "?" + params.toString();
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
          this.record = res.data;
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    uploadRecord() {
      $('#modalRecordUpload').modal('show');
      //window.location.href = "productivity/recordupload?" + new URLSearchParams(cloneFilter);
    },
    exportDocument() {
      this.loading = true;
      var page_url = this.page_url_document;
      fetch(page_url, {
        method: "post",
        body: JSON.stringify({
          'filter': this.filter,
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
            window.location.href = "/productivity/document?type=view&id=" + data.data.document_id;
          } else {
            this.errors = data.data.errors;
            //console.log(data.data);
            toastr.warning('Cập nhật bị lỗi', 'Thông báo');
          }
        }).catch(err => {
          this.loading = false;
          console.log(err);
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
    columnData() {
      if (this.record) {
        let columns = columns = [{
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
        }];

        if (this.record.record_type == 1) {
          columns.push({
            key: "total_day",
            label: "Ngày năng suất",
            class: "text-center",
            sortable: false,
          }, {
            key: "total_dayoff",
            label: "Ngày nghỉ",
            class: "text-center",
            sortable: false,
          });

          for (let day = 1; day <= 31; day++) {
            let dayColumn = {
              key: "d" + day,
              label: '' + day,
              class: "text-center",
              tdClass: (value) => {
                if (value != null) {
                  if (value == 'X') {
                    return 'text-danger'
                  }
                }
                else {
                  return 'text-secondary';
                }
              }
            }
            columns.push(dayColumn);
          }
        }
        else if (this.record.record_type == 2) {
          columns.push({
            key: "value",
            label: "Điểm",
            class: "text-center",
            sortable: true,
            tdClass: (value) => {
              if (value != null) {
                return 'text-success';
              }
              else {
                return 'text-secondary';
              }
            }
          });

          columns.push({
            key: "note",
            label: "Ghi chú",
            class: "text-center",
            sortable: false,
          });
        }

        return columns;
      }

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
