<template>
  <div>
    <div class="card small">
      <div class="card-header">
        <button class="btn btn-primary btn-sm" @click.prevent="showDataObject()">
          Thêm tùy chỉnh
        </button>
      </div>
      <div class="card-body">
        <table class="table table-sm">
          <thead v-if="wldataobjects.length > 0">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Tên trường dữ liệu</th>
              <th scope="col">Kiểu dữ liệu</th>
              <th scope="col">Mô tả</th>
              <th scope="col">Bắt buộc</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(dataobject, index) in wldataobjects" v-bind:key="index">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ dataobject.name }}</td>
              <td>{{ getTypeName(dataobject.wldatatype_id) }}</td>
              <td>{{ dataobject.description }}</td>
              <td>
                <span v-if="dataobject.require">Bắt buộc</span>
                <span v-else>Không bắt buộc</span>
              </td>
              <td>
                <div class="hidden-sm hidden-xs btn-group">
                  <button @click.prevent="editDataObject(dataobject)" class="btn btn-xs" title="Edit">
                    <i class="fa fa-edit text-green bigger-120"></i>
                  </button>

                  <button @click.prevent="deleteDataObject(dataobject, index)" class="btn btn-xs" title="Delete">
                    <i class="ace-icon text-red fa fa-trash bigger-120"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" v-bind:id="objunique_id" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="AddDataObject" @keydown="clearError">
            <div class="modal-header">
              <h4 class="modal-title">{{ title }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Unique name trường dữ liệu</label>
                <div class="col-sm-7">
                  <input :value="getUniqueName()" readonly class="form-control form-control-sm" type="text" required />
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Tên trường dữ liệu</label>
                <div class="col-sm-7">
                  <input v-model="wldataobject.name" class="form-control form-control-sm" type="text" required />
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Kiểu dữ liệu</label>
                <div class="col-sm-7">
                  <select name="" id="" v-model="wldataobject.wldatatype_id" class="form-control form-control-sm"
                    required>
                    <option value="1">Kiểu chữ (Một dòng)</option>
                    <option value="2">Kiểu chữ (Nhiều dòng)</option>
                    <option value="3">Kiểu số</option>
                    <!-- <option value="4">Kiểu logic</option> -->
                    <option value="5">Kiểu chọn một</option>
                    <option value="6">Kiểu chọn nhiều</option>
                    <option value="7">Kiểu ngày tháng</option>
                    <!-- <option value="8">Kiểu ngày giờ</option> -->
                    <option value="9">Kiểu người dùng</option>
                    <option value="10">Kiểu đính kèm</option>
                    <option value="11">Kiểu liên kết</option>
                    <option value="12">Kiểu xác nhận</option>
                    <option value="13">Kiểu nội dung dài</option>
                    <option value="14">Kiểu giờ</option>
                    <option value="16">Kiểu nhiều người dùng</option>
                  </select>
                </div>
              </div>
              <div class="form-group row" v-if="wldataobject.wldatatype_id == 5 || wldataobject.wldatatype_id == 6">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Dữ liệu</label>
                <div class="col-sm-7">
                  <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="width: 30px">#</th>
                        <th scope="col">Key</th>
                        <th scope="col">Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in wldataobject.items" v-bind:key="index">
                        <td scope="row">{{ index + 1 }}</td>
                        <td @click="changeGridEdit($event)" style="width: 40%">
                          <!-- <div class="d-flex justify-content-between"> -->
                          <span @click="changeGridViewToEdit($event)">{{
                          item.name
                          }}</span>
                          <input style="display: none" @blur.prevent.self="changeGridView($event)" v-model="item.name"
                            type="text" class="form-control form-control-sm" />
                          <!-- </div> -->
                          <div v-bind:class="
                            hasError('items.' + index + '.name') ? 'is-invalid' : ''
                          ">
                            <span v-if="hasError('items.' + index + '.name')" class="invalid-feedback" role="alert">
                              <strong>{{ getError("items." + index + ".name") }}</strong>
                            </span>
                          </div>
                        </td>
                        <td @click="changeGridEdit($event)" style="width: 40%">
                          <!-- <div class="d-flex justify-content-between"> -->
                          <span @click="changeGridViewToEdit($event)">{{
                          item.value
                          }}</span>
                          <input style="display: none" @blur.prevent.self="changeGridView($event)" v-model="item.value"
                            type="text" class="form-control form-control-sm" />
                          <!-- </div> -->
                        </td>
                        <td>
                          <span class="text-red sm" @click.prevent="delRow(index)"><i class="fas fa-trash"></i></span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4">
                          <a href="#" @click.prevent.stop="AddNewRow()">
                            <i class="fa fa-plus-circle"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Mô tả</label>
                <div class="col-sm-7">
                  <textarea v-model="wldataobject.description" class="form-control form-control-sm"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Tham chiếu giá trị</label>
                <div class="col-sm-7">
                  <reference-tree placeholder="Chọn giá trị tham chiếu.." :workflow_structure="workflow_structure" v-model="wldataobject.reference_path"></reference-tree>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Read only</label>
                <div class="col-sm-7">
                  <input type="checkbox" v-model="wldataobject.read_only" class="form-control form-control-sm" />
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Bắt buộc nhập</label>
                <div class="col-sm-7">
                  <input type="checkbox" v-model="wldataobject.require" class="form-control form-control-sm" />
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Vị trí sau trường tùy chỉnh</label>
                <div class="col-sm-7">
                  <select name="" id="" v-model="wldataobject.index_after_by" class="form-control form-control-sm">
                    <!-- <option :value="item" v-for="(item,index) in 20" v-bind:key="index">{{index}}</option> -->
                    <option value=""></option>
                    <option :value="obj.id" v-for="(obj, index) in wldataobjects" v-bind:key="index">
                      {{ obj.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("form.close") }}
              </button>
              <button type="submit" class="btn btn-primary">{{ $t("form.save") }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end- Modal -->
  </div>
</template>

<script>
import ReferenceTree from './ReferenceTree.vue';


export default {
  components: {
    ReferenceTree,
  },
  props: {
    title: String,
    wlworkflow_id: Number,
    phase_id: Number,
    job_id: Number,
    workflow_structure: Object,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  data() {
    return {
      wldataobjects: [],
      wldataobject: {
        id: "",
        unique_name: "",
        name: "",
        wlphase_id: "",
        wljob_id: "",
        wlworkflow_id: "",
        wldatatype_id: "",
        require: false,
        read_only: false,
        description: " ",
        reference_path: null,
        index_after_by: "0",
        index: "",
        items: [],
        itemsRemoved: [],
      },
      wldataobject_itm: {
        unique_name: "",
        name: "",
        value: "",
      },
      errors: {},

      loading: false,
      edit: false,
      token: "",
    };
  },
  methods: {
    delRow(index) {
      this.wldataobject.itemsRemoved.push({ ...this.wldataobject.items[index] });
      this.wldataobject.items.splice(index, 1);
    },
    AddNewRow() {
      this.wldataobject_itm.unique_name = "";
      this.wldataobject_itm.name = "";
      this.wldataobject_itm.value = "";

      this.wldataobject.items.push({ ...this.wldataobject_itm });
    },
    changeGridEdit(event) {
      let span = $(event.target).children("span");
      $(span).hide();
      console.log($(event.target).children("input").show());
    },
    changeGridView(event) {
      console.log($(event.target).hide());
      console.log($(event.target.parentElement).children("span").show());
    },
    changeGridViewToEdit(event) {
      console.log($(event.target).hide());
      console.log($(event.target.parentElement).children("input").show());
    },
    getUniqueName() {
      if (this.wldataobject) {
        this.wldataobject.unique_name = this.wldataobject.name.normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .replace(/đ/g, 'd').replace(/Đ/g, 'D')
          .toLowerCase()
          .replace(/[^a-zA-Z0-9 ]/g, "")
          .replaceAll(' ', '_');

        return this.wldataobject.unique_name;
      }
      return '';
    },
    editDataObject(wldataobject) {
      let index = this.wldataobjects.findIndex((i) => {
        return i.id == wldataobject.id;
      });
      // var group={..._obj};
      this.edit = true;
      this.wldataobject.id = wldataobject.id;

      this.wldataobject.description = wldataobject.description;
      this.wldataobject.unique_name = wldataobject.unique_name;
      this.wldataobject.name = wldataobject.name;
      this.wldataobject.wlworkflow_id = wldataobject.wlworkflow_id;
      this.wldataobject.wlphase_id = wldataobject.wlphase_id;
      this.wldataobject.wljob_id = wldataobject.wljob_id;
      this.wldataobject.wldatatype_id = wldataobject.wldatatype_id;
      this.wldataobject.reference_path = wldataobject.reference_path;
      this.wldataobject.require = wldataobject.require;
      this.wldataobject.read_only = wldataobject.read_only;
      this.wldataobject.index_after_by = wldataobject.index_after_by;

      this.wldataobject.items = [...wldataobject.items];

      this.wldataobject.index = index;

      $("#" + this.objunique_id).modal("show");
    },
    getTypeName(typeid) {
      var str = "";
      var id = typeid + "";
      switch (id) {
        case "1":
          str = "Kiểu chữ (Một dòng)";
          break;
        case "2":
          str = "Kiểu chữ (Nhiều dòng)";
          break;
        case "3":
          str = "Kiểu số";
          break;
        case "4":
          str = "Kiểu logic";
          break;
        case "5":
          str = "Kiểu chọn một";
          break;
        case "6":
          str = "Kiểu chọn nhiều";
          break;
        case "7":
          str = "Kiểu ngày tháng";
          break;
        case "8":
          str = "Kiểu ngày giờ";
          break;
        case "9":
          str = "Kiểu người dùng";
          break;
        case "10":
          str = "Kiểu đính kèm";
          break;
        case "11":
          str = "Kiểu liên kết";
          break;
        case "12":
          str = "Kiểu xác nhận";
          break;
        case "13":
          str = "Kiểu nội dung dài";
          break;
        case "14":
          str = "Kiểu giờ";
          break;

        default:
          break;
      }
      return str;
    },
    deleteDataObject(obj, index) {
      if (confirm("Bạn muốn xoá?")) {
        var apiAddress = "/api/works/dataobjects/" + obj.id;
        fetch(apiAddress, {
          method: "delete",
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              toastr.success("Xóa đối tượng thành công", "Thông báo");
              this.wldataobjects.splice(index, 1);
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Xóa đối tượng bị lỗi");
            }
            this.loading = false;
          });
      }
    },

    fetchDataObjects() {
      this.loading = true;

      this.wldataobjects = [];
      let params = "";
      if (this.job_id != null && this.job_id != 0) {
        params = new URLSearchParams({
          wlworkflow_id: this.wlworkflow_id,
          wlphase_id: this.phase_id,
          wljob_id: this.job_id,
        });
      } else if (this.phase_id != null && this.phase_id != 0) {
        params = new URLSearchParams({
          wlworkflow_id: this.wlworkflow_id,
          wlphase_id: this.phase_id,
        });
      } else if (this.wlworkflow_id != null && this.wlworkflow_id != 0) {
        params = new URLSearchParams({
          wlworkflow_id: this.wlworkflow_id,
        });
      }

      if (params != "") {
        var apiAddress = "/api/works/dataobjects?" + params.toString();
        fetch(apiAddress, { headers: { Authorization: this.token } })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              this.wldataobjects = res.data;
              this.wldataobject.itemsRemoved = [];
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Tải danh sách đối tượng bị lỗi");
            }
            this.loading = false;
          })
          .catch((err) => {
            console.log(err);
            this.loading = false;
          });
      }
    },

    AddDataObject() {
      this.loading = true;

      this.wldataobject.wljob_id = this.job_id;
      this.wldataobject.wlphase_id = this.phase_id;
      this.wldataobject.wlworkflow_id = this.wlworkflow_id;

      var apiAddress = "/api/works/dataobjects";
      if (this.edit === false) {
        fetch(apiAddress, {
          method: "POST",
          body: JSON.stringify(this.wldataobject),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              this.reset();
              this.fetchDataObjects();
              $("#" + this.objunique_id).modal("hide");
              toastr.success("Thêm đối tượng thành công", "Thông báo");
            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Thêm đối tượng bị lỗi");
            }
            this.loading = false;
          })
          .catch((err) => console.log(err));
      } else {
        //update
        fetch(apiAddress + '/' + this.wldataobject.id, {
          method: "PUT",
          body: JSON.stringify(this.wldataobject),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode > 0) {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            else if (res.success == 1) {
              this.reset();
              this.fetchDataObjects();
              $("#" + this.objunique_id).modal("hide");
              toastr.success("Cập nhật đối tượng thành công", "Thông báo");

            }
            else {
              this.errors = res.errors;
              toastr.warning(res.errors, "Cập nhật đối tượng bị lỗi");
            }
          })
          .catch((err) => console.log(err));
      }
    },
    //Các Trường thông tin tùy chỉnh
    showDataObject() {
      this.reset();
      $("#" + this.objunique_id).modal();
    },
    reset() {
      this.clearAllError();
      this.edit = false;
      this.wldataobject.id = "";
      this.wldataobject.unique_name = "";
      this.wldataobject.name = "";
      this.wldataobject.value = "";
      this.wldataobject.wljob_id = "";
      this.wldataobject.wlphase_id = "";
      this.wldataobject.wlworkflow_id = "";
      this.wldataobject.wldatatype_id = "";
      this.wldataobject.description = " ";
      this.wldataobject.index_after_by = "";
      this.wldataobject.require = false;
      this.wldataobject.read_only = false;
      this.wldataobject.reference_path = null;
      this.wldataobject.index = "";
      this.wldataobject.items = [];
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
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
    objunique_id() {
      if (this.wlworkflow_id != null) {
        this.fetchDataObjects();

        return this.wlworkflow_id + "phase" + this.phase_id + "job" + this.job_id;
      }
      return this.unique_id;
    },
  },
};
</script>

<style>

</style>
