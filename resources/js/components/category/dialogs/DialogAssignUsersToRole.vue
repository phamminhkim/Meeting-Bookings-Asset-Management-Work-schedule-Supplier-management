<template>
  <div class="modal fade" id="DialogAssignUsersToRole" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form @submit.prevent="submitDataForm" @keydown="clearError">
          <div class="modal-header">
            <h4 class="modal-title">
              Phân quyền cho role {{ role.name }}
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="row mt-1 " style="background-color:#F4F6F9">

              <div class="col-md-9">
              </div>

              <div class="col-md-3">
                <div class="row mt-1">
                  <div class="input-group input-group-sm  col-12">
                    <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter"
                      :placeholder="$t('form.search')" aria-label="Search">
                    <span class="input-group-append">
                      <button type="button" class="btn btn-default btn-flat mb-1"><i class="fas fa-search"></i></button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" v-if="role">
              <b-table striped hover responsive :bordered="true" head-variant="light" sticky-header small
                :items="role.users_add_list" :filter="filter" :fields="fields">
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>

                <template v-slot:cell(username)="data">
                  {{ getUserUsernameById(data.item) }}
                </template>

                <template v-slot:cell(name)="data">
                  {{ getUserNameById(data.item) }}
                </template>

                <template v-slot:cell(company_id)="data">
                  {{ getCompanyNameById(getUserCompanyIdById(data.item)) }}
                </template>

                <template v-slot:cell(department_id)="data">
                  {{ getDepartmentNameById(getUserDepartmentIdById(data.item)) }}
                </template>

                <template #cell(action)="data">
                  <div class="hidden-sm hidden-xs btn-group">
                    <button @click.prevent="removeUser(data.index)" class="btn btn-xs " title="Delete">
                      <i class="ace-icon text-red fa fa-trash bigger-120"></i>
                    </button>
                  </div>
                </template>
              </b-table>
            </div>
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-6">
                <div class="form-group row">
                  <treeselect placeholder="Chọn danh sách người dùng muốn thêm.." v-model="list_add_users"
                    :multiple="true" :options="tree_users" value-consists-of="LEAF_PRIORITY"></treeselect>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group row">
                  <button type="button" class="btn btn-success btn-sm ml-1 mt-1" :title="$t('form.add')"
                    @click="addUser()"> <i class="fas fa-plus-circle" :title="$t('form.add')"></i>
                    Thêm người dùng
                  </button>
                </div>
              </div>
              <div class="col-md-1"></div>
            </div>

          </div>

          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary mr-auto">Cập
              nhật</button>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $t('form.close')
            }}</button>
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
    role: Object,
  },
  components: {
    Treeselect,
  },
  data() {
    return {
      all_users: Map,
      all_companies: Map,
      all_departments: Map,
      tree_users: [],
      list_add_users: [],
      errors: {},
      loading: false,
      token: "",
      filter: "",
      page_url_role: "/api/category/roles",
      page_url_user: "api/category/users",
      page_url_department: "/api/category/departments",
      page_url_company: "/api/category/companies",
      fields: [
        {
          key: 'index',
          label: 'STT',
          class: "text-center text-nowrap",
        },
        {
          key: 'username',
          label: this.$t('form.username'),
          class: "text-center text-nowrap",
        },
        {
          key: 'name',
          label: this.$t('form.name'),
          thClass: "text-center text-nowrap",
          tdClass: "text-nowrap",
        },
        {
          key: 'company_id',
          label: this.$t('form.company'),
          class: "text-center text-nowrap",
        },
        {
          key: 'department_id',
          label: this.$t('form.department'),
          class: "text-center text-nowrap",
        },
        {
          key: 'action',
          label: '',
          thClass: "text-center text-nowrap",
          tdClass: "text-nowrap",
        },
      ],
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchUsers();
    this.fetchTreeUsers();
    this.fetchCompanies();
    this.fetchDepartments();
  },
  watch: {
    role() {
      this.errors = {};
    }
  },
  methods: {
    getUserUsernameById(id) {
      let user = this.all_users.get(id);
      return user != null ? user.username : id;
    },
    getUserNameById(id) {
      let user = this.all_users.get(id);
      return user != null ? user.name : id;
    },
    getUserCompanyIdById(id) {
      let user = this.all_users.get(id);
      return user != null ? user.company_id : id;
    },
    getUserDepartmentIdById(id) {
      let user = this.all_users.get(id);
      return user != null ? user.department_id : id;
    },
    getCompanyNameById(id) {
      let company = this.all_companies.get(id);
      return company != null ? company.name : id;
    },
    getDepartmentNameById(id) {
      let department = this.all_departments.get(id);
      return department != null ? department.name : id;
    },
    addUser() {
      this.list_add_users.forEach(user_id => {
        if (!this.role.users_add_list.includes(user_id)) {
          this.role.users_add_list.push(user_id);
        }
        if (this.role.users_remove_list.includes(user_id)) {
          this.role.users_remove_list.splice(user_id);
        }
      });

      this.list_add_users = [];
    },
    removeUser(index) {
      let remove_id = this.role.users_add_list[index];
      if (remove_id) {
        this.role.users_remove_list.push(remove_id);
        this.role.users_add_list.splice(index, 1);
      }

    },
    fetchTreeUsers() {
      this.loading = true;

      var page_url = this.page_url_user + "?type=tree_combobox";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.tree_users = res.data;
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
      this.edit = false;
    },
    fetchUsers() {
      this.loading = true;

      var page_url = this.page_url_user;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          let users = res.data;

          this.all_users = new Map();
          users.forEach(user => {
            this.all_users.set(user.id, user);
          });

          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
    },
    fetchCompanies() {
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          let companies = res.data;

          this.all_companies = new Map();
          companies.forEach(company => {
            this.all_companies.set(company.id, company);
          });

          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
    },
    fetchDepartments() {
      this.loading = true;
      var page_url = this.page_url_department;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          let departments = res.data;

          this.all_departments = new Map();
          departments.forEach(department => {
            this.all_departments.set(department.id, department);
          });

          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
    },
    submitDataForm() {
      //update
      fetch(this.page_url_role + "/" + this.role.id, {
        method: "PUT",
        body: JSON.stringify(this.role),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            let role = data.data;

            this.$emit('onUpdateRole', this.role.index, role);

            toastr.success(data.message, "Thành công");
            $("#DialogAssignUsersToRole").modal("hide");
          } else {
            toastr.error(data.message, "Thất bại");
            this.errors = data.errors;
          }
        })
        .catch((err) => console.log(err));
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
};
</script>
