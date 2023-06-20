<template>
  <div class="modal fade" id="DialogAssignPermissionsToRole" tabindex="-1" role="dialog">

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
                :items="role.permissions_add_list" :filter="filter" :fields="fields">
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>

                <template v-slot:cell(name)="data">
                  {{ getPermissionById(data.item).name }}
                </template>

                <template v-slot:cell(description)="data">
                  {{ getPermissionById(data.item).description }}
                </template>

                <template #cell(action)="data">
                  <div class="hidden-sm hidden-xs btn-group">
                    <button @click.prevent="removePermission(data.index)" class="btn btn-xs " title="Delete">
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
                  <treeselect placeholder="Chọn danh sách quyền muốn thêm.." v-model="list_add_permissions"
                    :multiple="true" :disable-branch-nodes="true" :options="tree_permissions"></treeselect>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group row">
                  <button type="button" class="btn btn-success btn-sm ml-1 mt-1" :title="$t('form.add')"
                    @click="addPermission()"> <i class="fas fa-plus-circle" :title="$t('form.add')"></i>
                    Thêm quyền
                  </button>
                </div>
              </div>
              <div class="col-md-2"></div>
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
      all_permissions: Map,
      tree_permissions: [],
      list_add_permissions: [],
      errors: {},
      loading: false,
      token: "",
      filter: "",
      page_url_role: "/api/category/roles",
      page_url_permission: "/api/category/permissions",

      fields: [
        {
          key: 'index',
          label: 'STT',
          class: "text-center text-nowrap",
        },
        {
          key: 'name',
          label: this.$t('form.name'),
          thClass: "text-center text-nowrap",
          tdClass: "text-nowrap",
        },
        {
          key: 'description',
          label: this.$t('form.description'),
          thClass: "text-center text-nowrap",
          tdClass: "text-nowrap",
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
    this.fetchPermissions();
  },
  watch: {
    role() {
      this.errors = {};
    }
  },
  methods: {
    getPermissionById(id) {
      return this.all_permissions.get(id);
    },
    addPermission() {
      this.list_add_permissions.forEach(permission_id => {
        if (!this.role.permissions_add_list.includes(permission_id)) {
          this.role.permissions_add_list.push(permission_id);
        }
        if (this.role.permissions_remove_list.includes(permission_id)) {
          this.role.permissions_remove_list.splice(permission_id);
        }
      });

      this.list_add_permissions = [];
    },
    removePermission(index) {
      let remove_id = this.role.permissions_add_list[index];
      if (remove_id) {
        this.role.permissions_remove_list.push(remove_id);
        this.role.permissions_add_list.splice(index, 1);
      }

    },
    fetchPermissions() {
      this.loading = true;

      var page_url = this.page_url_permission;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          let permissions = res.data;

          this.all_permissions = new Map();
          permissions.forEach(permission => {
            this.all_permissions.set(permission.id, permission);
          });

          this.tree_permissions = permissions.map(permission =>
          (
            {
              id: permission.id,
              label: permission.name + ' (' + permission.description + ')',
            }));

          this.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.loading = false;
        });
      this.edit = false;
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
            $("#DialogAssignPermissionsToRole").modal("hide");
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
