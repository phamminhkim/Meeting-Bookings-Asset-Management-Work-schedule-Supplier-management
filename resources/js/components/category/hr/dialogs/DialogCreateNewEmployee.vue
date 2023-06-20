<template>
  <div class="modal fade" id="DialogCreateNewEmployee" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form @submit.prevent="submitDataForm" @keydown="clearError">
          <div class="modal-header">
            <h4 class="modal-title">
              <span v-if="!edit">{{ $t("form.create") }}</span>
              <span v-else>{{ $t("form.update") }}</span>
              công/nhân viên
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group" v-if="employee.id">
              <label for="id">{{ $t("form.id") }}</label>
              <input v-model="employee.id" type="text" class="form-control"
                v-bind:class="hasError('id') ? 'is-invalid' : ''" id="id" name="id" readonly />

              <span v-if="hasError('id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("id") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="employee_id">Mã nhân viên
                <small class="text-red">( * )</small>
              </label>
              <input v-model="employee.employee_id" type="text" class="form-control" minlength="7" :readonly="this.edit"
                maxlength="10" v-bind:class="hasError('employee_id') ? 'is-invalid' : ''" id="employee_id"
                name="employee_id" placeholder="Nhập mã nhân viên (ví dụ: 1001010)"
                :rules="['rules.required,rules.min']" required />

              <span v-if="hasError('employee_id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("employee_id") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="name">Họ và tên nhân viên
                <small class="text-red">( * )</small>
              </label>
              <input v-model="employee.name" type="text" class="form-control" maxlength="100"
                v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                placeholder="Nhập họ và tên nhân viên" required />
              <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                <strong>{{ getError("name") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="gender">Giới tính
                <small class="text-red">( * )</small>
              </label>
              <select id="gender" class="form-control form-control-sm" v-model="employee.gender"
                v-bind:class="hasError('gender')?'is-invalid':''" required>
                <option value="" disabled selected>Chọn giới tính</option>
                <option :value="1">Nam</option>
                <option :value="0">Nữ</option>
              </select>
              <span v-if="hasError('gender')" class="invalid-feedback" role="alert">
                <strong>{{ getError("gender") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="company_id">{{ $t("form.company") }}
                <small class="text-red">( * )</small>
              </label>
              <select id="company_id" class="form-control form-control-sm" v-model="employee.company_id"
                v-bind:class="hasError('company_id')?'is-invalid':''" required>
                <option key="" value="" disabled selected>Chọn công ty</option>
                <option v-for="company in companies" v-bind:key="company.id" v-bind:value="company.id">
                  {{ company.id }} - {{ company.name }}
                </option>
              </select>

              <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("company_id") }}</strong>
              </span>
            </div>
            <div class="form-group" v-if="employee.company_id">
              <label for="department_id">{{ $t("form.department") }}
                <small class="text-red">( * )</small></label>
              <select id="department_id" class="form-control form-control-sm" v-model="employee.department_id"
                v-bind:class="hasError('department_id')?'is-invalid':''" required>
                <option key="" value="" disabled selected>Chọn phòng ban</option>
                <option v-for="department in filter_department_form" v-bind:key="department.id"
                  v-bind:value="department.id">
                  {{ department.name }}
                </option>
              </select>

              <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("department_id") }}</strong>
              </span>
            </div>
            <div class="form-group" v-if="filter_workshop_form.length > 0">
              <label for="workshop_id">{{ $t("form.workshop") }}</label>
              <select id="workshop_id" class="form-control form-control-sm" v-model="employee.workshop_id"
                v-bind:class="hasError('workshop_id')?'is-invalid':''">
                <option key="" value="" disabled selected>Chọn xưởng sản xuất</option>
                <option v-for="workshop in filter_workshop_form" v-bind:key="workshop.id" v-bind:value="workshop.id">
                  {{ workshop.name }}
                </option>
              </select>

              <span v-if="hasError('workshop_id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("workshop_id") }}</strong>
              </span>
            </div>
            <div class="form-group" v-if="filter_party_form.length > 0">
              <label for="party_id">{{ $t("form.party") }}</label>
              <select id="party_id" class="form-control form-control-sm" v-model="employee.party_id"
                v-bind:class="hasError('party_id')?'is-invalid':''">
                <option key="" value="" disabled selected>Chọn tổ sản xuất</option>
                <option v-for="party in filter_party_form" v-bind:key="party.id" v-bind:value="party.id">
                  {{ party.name }}
                </option>
              </select>

              <span v-if="hasError('party_id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("party_id") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="date_in">Ngày bắt đầu làm việc
                <small class="text-red">( * )</small>
              </label>
              <input v-model="employee.date_in" type="date" class="form-control"
                v-bind:class="hasError('date_in') ? 'is-invalid' : ''" id="date_in" name="date_in"
                placeholder="Nhập ngày làm việc chính thức" required />
              <span v-if="hasError('date_in')" class="invalid-feedback" role="alert">
                <strong>{{ getError("date_in") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <div class="form-group">
                <label for="is_resign">Đã nghỉ việc</label>
                <input v-model="employee.is_resign" type="checkbox" class="form-control" id="is_resign"
                  name="is_resign" />
              </div>
              <div class="form-group" v-if="employee.is_resign">
                <label for="date_in">Ngày chính thức nghỉ việc </label>
                <input v-model="employee.date_out" type="date" class="form-control"
                  v-bind:class="hasError('date_out') ? 'is-invalid' : ''" id="date_out" name="date_out"
                  placeholder="Nhập ngày nghỉ việc chính thức" />
                <span v-if="hasError('date_out')" class="invalid-feedback" role="alert">
                  <strong>{{ getError("date_out") }}</strong>
                </span>
              </div>
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              {{ $t("form.back") }}
            </button>
            <button type="submit" class="btn btn-primary">
              {{ $t("form.save") }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
  props: {
    companies: Array,
    departments: Array,
    workshops: Array,
    parties: Array,
    employees: Array,
    employeetypes: Array,
    employmenttypes: Array,
    directtypes: Array,
    employee: Object,
    edit: Boolean,
  },
  components: {
    // Pagination
    Treeselect,
  },
  data() {
    return {
      errors: {},
      loading: false,
      token: "",
      page_url_employees: "/api/category/employees",
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  watch: {
    employee() {
      this.errors = {};
    }
  },
  methods: {
    submitDataForm() {
      this.$emit('def');

      var page_url = this.page_url_employees;
      if (this.edit === false) {
        fetch(page_url, {
          method: "POST",
          body: JSON.stringify(this.employee),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              this.$emit('onCreateEmployee', data.data);

              toastr.success(data.message, "Thành công");
              $("#DialogCreateNewEmployee").modal("hide");
            } else {
              toastr.error(data.message, "Thất bại");
              this.errors = data.errors;
            }
          })
          .catch((err) => console.log(err));
      } else {
        //update
        fetch(page_url + "/" + this.employee.id, {
          method: "PUT",
          body: JSON.stringify(this.employee),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              let employee = data.data;
              employee["is_resign"] = !employee["is_working"];
              
              this.$emit('onUpdateEmployee', this.employee.index, employee);

              toastr.success(data.message, "Thành công");
              $("#DialogCreateNewEmployee").modal("hide");
            } else {
              toastr.error(data.message, "Thất bại");
              this.errors = data.errors;
            }
          })
          .catch((err) => console.log(err));
      }
    },
    hasError(fieldName) {
      return fieldName in this.errors;
    },
    getError(fieldName) {
      return this.errors[fieldName][0];
    },
    clearError(event) {
      Vue.delete(this.errors, event.target.name);
    },
    clearAllError() {
      this.errors = {};
    },
  },
  computed: {
    filter_employee_types() {
      let filters = [];
      this.employeetypes.forEach((element) => {
        let filter = {
          id: element.id,
          label: element.name,
        };
        filters.push({ ...filter });
      });
      return filters;
    },
    filter_direct_types() {
      let filters = [];
      this.directtypes.forEach((element) => {
        let filter = {
          id: element.id,
          label: element.name,
        };
        filters.push({ ...filter });
      });
      return filters;
    },
    filter_employment_types() {
      let filters = [];
      this.employmenttypes.forEach((element) => {
        let filter = {
          id: element.id,
          label: element.name,
        };
        filters.push({ ...filter });
      });
      return filters;
    },
    filter_gender() {
      return [
        { id: 1, label: "Nam" },
        { id: 2, label: "Nữ" },
      ];
    },
    filter_status() {
      return [
        { id: 1, label: "Còn làm việc" },
        { id: 0, label: "Đã nghỉ việc" },
      ];
    },
    filter_department_form() {
      let company_id = this.employee.company_id;
      return this.departments.filter(function (item) {
        if (item.company_id == company_id) {
          return true;
        }
      });
    },
    filter_workshop_form() {
      let department_id = this.employee.department_id;
      return this.workshops.filter(function (item) {
        if (item.department_id == department_id) {
          return true;
        }
      });
    },
    filter_party_form() {
      let workshop_id = this.employee.workshop_id;
      return this.parties.filter(function (item) {
        if (item.workshop_id == workshop_id) {
          return true;
        }
      });
    },
  },
};
</script>
