<template>
  <div class="modal fade" id="DialogCreatePermission" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form @submit.prevent="submitDataForm" @keydown="clearError">
          <div class="modal-header">
            <h4 class="modal-title">
              <span v-if="!edit">{{ $t("form.create") }}</span>
              <span v-else>{{ $t("form.update") }}</span>
              phân quyền
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group" v-if="permission.id">
              <label for="id">{{ $t("form.id") }}</label>
              <input v-model="permission.id" type="text" class="form-control"
                v-bind:class="hasError('id') ? 'is-invalid' : ''" id="id" name="id" readonly />

              <span v-if="hasError('id')" class="invalid-feedback" role="alert">
                <strong>{{ getError("id") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="name">Tên phân quyền
                <small class="text-red">( * )</small>
              </label>
              <input v-model="permission.name" type="text" class="form-control"
                v-bind:class="hasError('name') ? 'is-invalid' : ''" id="name" name="name"
                placeholder="Nhập tên phân quyền" :rules="['rules.required,rules.min']" required />

              <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                <strong>{{ getError("name") }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label for="description">Mô tả phân quyền
                <small class="text-red">( * )</small>
              </label>
              <input v-model="permission.description" type="text" class="form-control" maxlength="100"
                v-bind:class="hasError('description') ? 'is-invalid' : ''" id="description" name="description"
                placeholder="Nhập mô tả phân quyền" required />
              <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                <strong>{{ getError("description") }}</strong>
              </span>
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary mr-auto">Lưu</button>

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
    permission: Object,
    edit: Boolean,
  },
  components: {
    Treeselect,
  },
  data() {
    return {
      errors: {},
      loading: false,
      token: "",
      page_url_permission: "/api/category/permissions",
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  watch: {
    permission() {
      this.errors = {};
    }
  },
  methods: {
    submitDataForm() {
      var page_url = this.page_url_permission;
      if (this.edit === false) {
        fetch(page_url, {
          method: "POST",
          body: JSON.stringify(this.permission),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              this.$emit('onCreatePermission', data.data);

              toastr.success(data.message, "Thành công");
              $("#DialogCreatePermission").modal("hide");
            } else {
              toastr.error(data.message, "Thất bại");
              this.errors = data.errors;
            }
          })
          .catch((err) => console.log(err));
      } else {
        //update
        fetch(page_url + "/" + this.permission.id, {
          method: "PUT",
          body: JSON.stringify(this.permission),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              let permission = data.data;

              this.$emit('onUpdatePermission', this.permission.index, permission);

              toastr.success(data.message, "Thành công");
              $("#DialogCreatePermission").modal("hide");
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
};
</script>
