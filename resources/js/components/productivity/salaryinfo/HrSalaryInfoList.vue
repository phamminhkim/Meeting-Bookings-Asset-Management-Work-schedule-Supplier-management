<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5 class="m-0 text-dark">
              <i class="nav-icon fas fa-file-contract"></i> {{ $t(title) }}
            </h5>
          </div>
          <div class="col-sm-6">

            <div class="float-sm-right">
              <div class="btn-group-vertical ">
                <button class="btn btn-primary" @click="uploadDaysSalary()"> Cập nhật hàng loạt </button>
              </div>
              <div class="btn-group-vertical ">
                <button class="btn btn-primary" @click="newHrAddMark()"> {{ $t('form.create')}} </button>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row mt-0">
              <div class="col-md-9">
                <div class="form-group row">
                  <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                  <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                      <span v-if="!show_search">{{ $t("form.show_search") }}</span>
                      <span v-if="show_search">{{ $t("form.hide_search") }}</span>
                    </button>
                    <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                      <i v-if="show_search" class="fas fa-angle-up"></i>
                      <i v-else class="fas fa-angle-down"></i>
                    </button>
                  </div>
                  <!-- <button type="button" :title="$t('form.filter')" onclick="location.reload(true)" class="btn btn-secondary  btn-xs ml-1" ><i class="fas fa-redo-alt" title="Refresh"></i></button> -->
                  <button @click="filter_data()" :title="$t('form.filter')" class="btn btn-secondary btn-xs ml-1">
                    <i class="fas fa-sync-alt" :title="$t('form.reload')"></i>
                  </button>
                  <span v-if="save_filter" :title="$t('form.filter')" class="ml-1"><i class="fas fa-filter"
                      :title="$t('form.filter')"></i></span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="row"></div>
              </div>
            </div>
            <div class="row" v-if="show_search" style="border: 1px solid #e5e5e5; border-radius: 5px">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="start_date" class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right">{{
                  $t("form.from_date") }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1" />
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                  $t("form.to_date") }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.end_date" class="form-control form-control-sm mt-1" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">Công nhân</label>
                  <div class="col-sm-10">
                    <treeselect name="staff_id" id="staff_id" placeholder="Tất cả" v-model="form_filter.staff_id"
                      :disable-branch-nodes="true" :multiple="true" :options="tree_employees"></treeselect>
                  </div>


                </div>

                <div class="col-md-12" style="text-align: center">
                  <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1" @click="filter_data()">
                    <i class="fa fa-search"></i> {{ $t("form.find") }}
                  </button>
                  <button type="reset" class="btn btn-secondary btn-sm mt-1 mb-1" @click="clearFilter()">
                    <i class="fa fa-reset"></i> {{ $t("form.clear") }}
                  </button>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" v-model="save_filter" />
                    <label class="form-check-label" for="save_filter">
                      <i>{{ $t("form.save_filter") }}</i>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-1" style="background-color: #f4f6f9">
              <div class="col-md-9">
                <div class="form-group row">
                  <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->

                  <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editDocument()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="editHrAddMark()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->
                  <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1"  title="In" @click="printRequest()"><i class="fas fa-print" title="In hợp đồng"></i></button> -->

                  <button type="button" class="btn btn-warning btn-sm ml-1 mt-1" :title="$t('form.edit')"
                    @click="editDocument()">
                    <i class="fas fa-edit" :title="$t('form.edit')"></i>{{ $t("form.edit") }}
                  </button>
                  <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.delete')"  @click.prevent="editHrAddMark()" ><i class="fas fa-trash-alt" :title="$t('form.delete')"></i> {{$t('form.delete')}} </button> -->
                  <button type="button" class="btn btn-danger btn-sm ml-1 mt-1" :title="$t('form.cancel')"
                    @click.prevent="editHrAddMark()">
                    <i class="fas fa-window-close" :title="$t('form.delete')"></i>
                    {{ $t("form.delete") }}
                  </button>
                  <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.paid')}}</button> -->
                  <!-- <button
                    type="button"
                    class="btn btn-secondary btn-sm ml-1 mt-1"
                    @click.prevent="notification_show()"
                  >
                    <i class="fas fa-bell"></i> {{ $t("form.reminder") }}
                  </button> -->
                  <!-- <download-excel
                    class="btn btn-info btn-sm ml-1 mt-1"
                    :fields="fieldexcel"
                    :data="tableData"
                    type="xls"
                    :name="fileName"
                  >
                    <i class="fas fa-file-excel"></i>
                    Excel
                  </download-excel> -->
                </div>
              </div>
              <div class="col-md-3">
                <div class="row mt-1">
                  <div class="input-group input-group-sm col-12">
                    <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                    <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter"
                      :placeholder="$t('form.search')" aria-label="Search" />
                    <span class="input-group-append">
                      <button type="button" class="btn btn-default btn-flat mb-1">
                        <i class="fas fa-search"></i>
                      </button>
                      <!-- <button type="button" @click="searchContact()" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button> -->
                    </span>
                    <!-- <download-excel
                          :data   = "contracts" title="Eport Excel"  class="ml-2" >
                        <span style="cursor: pointer;"><i class="fa fa-download"></i></span>
                      </download-excel> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="active tab-pane" id="yeucaumoi">
              <div class="row">
                <b-table striped hover responsive :bordered="true" head-variant="light" :sticky-header="false" small
                  :items="hraddmarks" :current-page="current_page" :per-page="per_page" :filter="filter" selectable
                  ref="selectableTable" :fields="fields">
                  <template #head(selected)>
                    <!-- {{selected}} -->
                    <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"></b-form-checkbox>
                  </template>
                  <template v-slot:cell(newtab)="data">
                    <a target="_blank" :href="viewRequest(data.item.id)"><i title="View in new tab"
                        class="fas fa-external-link-alt"></i></a>
                  </template>
                  <template v-slot:cell(monthyear)="data">
                    {{ data.item.year}}-{{ data.item.month}}

                  </template>
                  <template v-slot:cell(selected)="data">
                    <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected"></b-form-checkbox>
                  </template>


                  <!-- <template v-slot:cell(date)="data">
                    {{ data.item.year}}-{{ data.item.month}}-{{ data.item.day}}
                  
                  </template>
                    -->


                  <template #cell(created_at)="data">
                    <span>{{ data.value | formatDateDB }}</span>
                  </template>


                </b-table>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-form-label-sm col-md-4" style="text-align: left" for="">Per page:</label>
                      <div class="col-md-3">
                        <b-form-select size="sm" v-model="per_page" :options="pageOptions"></b-form-select>
                      </div>
                      <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                      <div class="col-md-3">
                        <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page" align="fill"
                          size="sm" class="my-0"></b-pagination>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <loading :loading="loading"></loading>

        </div>

        <!-- /.card -->
      </div>
    </div>

    <!-- dialog -->
    <HrSalaryInfoCreateDialog :id="selected[0]" v-on:fromhraddmark="fromhraddmark"></HrSalaryInfoCreateDialog>
    <dialog-salary-info-upload title="Upload dữ liệu ngày công" v-on:onUploadData="onUploadData">
    </dialog-salary-info-upload>
  </div>
</template>

<script>

import Treeselect from "@riophae/vue-treeselect";
import moment from "moment";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import Loading from "../../shared/Loading.vue";
import HrSalaryInfoCreateDialog from "./HrSalaryInfoCreateDialog.vue";
import DialogSalaryInfoUpload from './DialogSalaryInfoUpload.vue';
export default {
  props: {
    title: "",
  },
  components: { Treeselect, Loading, HrSalaryInfoCreateDialog, DialogSalaryInfoUpload },
  data() {
    return {
      variant_name: "productivity_HrSalaryInfoList",

      hraddmarks: [],
      total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter: "",
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

      errors: {},
      status: "",
      locale_format: "de-DE",
      tableData: [],
      fileName: "DS_YCTK_" + moment(Date()).format("MM/DD/YYYY"),
      fieldexcel: {
        "Số chứng từ": "Số_chứng_từ",
        "Trạng thái": "Trạng_thái",
      },
      fields: [
        {
          key: "selected",
          label: "All",
          stickyColumn: true,
        },
        {
          key: "monthyear",
          label: "Kỳ",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "employee.employee_id",
          label: "Mã NV",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "employee.name",
          label: "Tên NV",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },

        {
          key: "totalday_calc",
          label: "Số ngày tính lương năng suất",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "totalday_salary",
          label: "Số ngày làm theo lịch tháng",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "note",
          label: "Ghi chú",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "updated_by.name",
          label: "Người tạo",
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "created_at",
          label: this.$t("form.create_date"),
          sortable: true,
          stickyColumn: true,
          class: "text-center text-nowrap",
        },

      ],
      tab: "",
      loading: false,
      edit: false,
      token: "",
      page_url_request: "/api/productivity/salaryinfo",
      // page_url_contracts: "api/payment/contracts",
      // page_url_vendors: "/api/category/vendors",
      page_url_department: "/api/category/departments",
      page_url_document_type: "/api/category/documenttypes",
      page_url_document_type_sub_menu: "/api/category/documenttype/submenu",
      page_url_payment_update_paid: "/api/payment/update_paid",
      page_url_payment_miss_invoice_check: "/api/payment/miss_invoice_check",
      page_url_employees: "api/category/employees",
      show_search: false,
      feedback: false,
      form_filter: {

        start_date: "",
        end_date: "",
        staff_id: [],


      },
      variant_data: [],
      tree_employees: [],
      selected: [],
      selectAll: false,
      multiple: true,
      save_filter: false,
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;

    //Thiết lập form tìm kiếm mặc định
    this.getVariant(this.variant_name);
    this.init();
    this.getEmployeeTree();


    // this.fetchHrAddMark();
  },
  methods: {
    onUploadData() {
      this.fetchHrAddMark();
    },
    getEmployeeTree() {
      var page_url = this.page_url_employees + "?type=tree_combobox";
      // console.log(page_url);
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.tree_employees = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    downloadFile(idfile, filename) {
      var page_url = "api/payment/downloadFile/" + idfile;
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
        responseType: "arraybuffer",
        method: "post",
      })
        .then((res) => res.blob())
        .then((res) => {

          var newBlob = new Blob([res], { type: "octet/stream" });
          if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            return;
          }

          // For other browsers:
          // Create a link pointing to the ObjectURL containing the blob.
          const data = URL.createObjectURL(newBlob);
          var link = document.createElement("a");
          link.href = data;
          link.download = filename;
          link.click();

          setTimeout(function () {
            // For Firefox it is necessary to delay revoking the ObjectURL
            URL.revokeObjectURL(data);
          }, 100);
        })
        .catch((err) => console.log(err));
    },
    fromhraddmark(obj) {

      let index = this.hraddmarks.findIndex(item => {
        return item.id == obj.id;
      });

      if (index != -1) {

        //this.$root.$emit("bv::refresh::table", "selectableTable"); //refresh data trong danh sách reminder -> ở form khác
        this.hraddmarks[index] = obj;
        this.$refs.selectableTable.refresh();
      } else {
        this.hraddmarks.push(obj);
      }

    },

    //Khởi tạo các giá trị trong form tìm kiếm
    init() {
      const start_date = new Date();
      const today = new Date();
      start_date.setMonth(start_date.getMonth() - 1);
      this.form_filter.start_date =
        start_date.getFullYear() +
        "-" +
        fixDigit(start_date.getMonth() + 1) +
        "-" +
        fixDigit(start_date.getDate());
      this.form_filter.end_date =
        today.getFullYear() +
        "-" +
        fixDigit(today.getMonth() + 1) +
        "-" +
        fixDigit(today.getDate());
      //variant:
      if (this.variant_data != "undefined" && this.variant_data.length > 0) {
        this.variant_data.forEach((variant) => {
          if (variant.name == "form_filter") {
            // console.log(variant.properties.serial_num);
            this.save_filter = variant.save_filter;
            //nếu có nhấn lưu điều kiện lọc thì mới load vào form
            if (this.save_filter) {
              this.form_filter = variant.properties;
              console.log(this.form_filter);
              console.log(variant.properties);
              for (let field in this.form_filter) {
                if (this.form_filter[field] == null) {
                  this.form_filter[field] = "";
                }
              }
              if (this.form_filter.staff_id == null) {
                this.form_filter.staff_id = [];
              }
            }
          }
        });
      }

    },

    fetchHrAddMark(page, search) {
      this.loading = true;
      this.hraddmarks = Array();
      const params = new URLSearchParams({
        start_date: this.form_filter.start_date,
        end_date: this.form_filter.end_date,
        staff_id: this.form_filter.staff_id,
      });
      var page_url = this.page_url_request + "?" + params.toString();

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
          if (res.canviewteam) {
            this.canviewteam = 1;
          }
          if (res.success == "1") {
            this.hraddmarks = res.data;
            var list = [];

            this.tableData = list;
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    editDocument() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
        return;
      }
      // var id = this.selected;
      // window.location.href = "documents?type=edit&id=" + id;
      $("#hrAddSalaryInfoDialogID").modal("show");
    },
    newHrAddMark() {

      this.selected = [];
      $("#hrAddSalaryInfoDialogID").modal("show");
    },
    uploadDaysSalary() {
      $('#modalDaySalaryUpload').modal('show');
    },
    editHrAddMark() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
        return;
      }
      var id = this.selected;
      let index = this.hraddmarks.findIndex((i) => {
        console.log("id" + i.id);
        return i.id == id;
      });
      console.log("id=" + id);
      this.$bvModal
        .msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
          title: "",
          size: "sm",
          buttonSize: "sm",
          okVariant: "danger",
          okTitle: "OK",
          cancelTitle: "Cancel",
          footerClass: "p-2",
          hideHeaderClose: false,
          centered: true,
        })
        .then((value) => {
          if (value) {
            var page_url = this.page_url_request + "/" + id;
            fetch(page_url, {
              method: "DELETE",
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
                  toastr.warning(this.$t(res.message), this.$t("form.warning"));
                  return;
                }
                if (res.success == true) {
                  // this.hraddmarks = [];
                  this.hraddmarks.splice(index, 1);
                  // this.$refs.selectableTable.refresh()
                  toastr.success(this.$t("form.success"), "");
                  this.selected = [];
                } else {
                  toastr.warning(this.$t(res.data.message), this.$t("form.warning"));
                }
              })
              .catch((err) => console.log(err));
          }
        })
        .catch((err) => {
          console.log(err);
        });

      // if(confirm("Xác nhận xoá ?")){

      // }
    },
    searchRequest() {
      //alert($('#search').val());
      this.fetchHrAddMark(null, $("#search").val());
    },


    filter_data() {
      this.saveVariant([this.form_filter]);
      this.fetchHrAddMark();
    },
    getVariant(name) {
      //   this.clearFilter();
      var page_url =
        "api/user/variant?url=" + this.variant_name + "&no-cache=" + new Date().getTime();
      var data = {
        url: this.variant_name,
      };
      //console.log(JSON.stringify(data));
      fetch(page_url, {
        method: "GET",

        headers: {
          Authorization: this.token,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          console.log(res);
          if (res.statuscode == "403") {
            return;
          }
          if (res.success == "1") {
            this.variant_data = res.data;

            this.init();
          }

          this.fetchHrAddMark();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    saveVariant(obj) {
      var page_url = "api/user/variant";
      // var form_json = {...this.form_filter};

      var data = {
        url: this.variant_name,
        name: "form_filter",
        save_filter: this.save_filter ? 1 : 0,
        properties: this.form_filter,
      };
      //console.log(JSON.stringify(data));
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          Authorization: this.token,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showSearch() {
      this.show_search = !this.show_search;
      //this.clearFilter();
    },
    clearFilter() {
      for (let field in this.form_filter) {
        this.form_filter[field] = "";
      }

      this.init();
      // this.contract_filter =this.contracts;
    },
    select() {
      this.selected = [];
      if (this.selectAll) {
        for (let i in this.hraddmarks) {
          this.selected.push(this.hraddmarks[i].id);
        }
      }
    },

  },
  computed: {


    rows() {
      this.pageOptions = [10, 50, 100, 500, { value: this.hraddmarks.length, text: "All" }];
      return this.hraddmarks.length;
    },

  },
};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 1px !important;
}

.dropdown-submenu>.dropdown-menu {
  left: auto !important;
  margin-left: 10px;
  margin-top: 30px;
  top: 0;
  // right:auto !important;
  //   right: 100%;
}
</style>
