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
              <div class="btn-group-vertical">
                <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->

                <!--  <button class="btn btn-primary dropdown-toggle dropdown-icon"  data-toggle="dropdown"> {{ $t('form.create')}} </button>

                      <div class="dropdown-menu dropdown-menu-right" role="menu" >

                        <a href="#" v-for="doc in document_types" v-bind:key="doc.id" @click.prevent="showAdd(doc.code)" class="dropdown-item">{{ $t(doc.name)}}</a>

                      </div> -->
              </div>
              <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
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
                      $t("form.from_date")
                  }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1" />
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.to_date")
                  }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.end_date" class="form-control form-control-sm mt-1" />
                  </div>

                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                    :title="$t('form.serial_num')" for="">{{ $t("form.serial_num") }}</label>
                  <div class="col-sm-2">
                    <input type="text" v-model="form_filter.serial_num" class="form-control form-control-sm mt-1" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.document_type")
                  }}</label>
                  <div class="col-sm-2">
                    <select name="" id="" v-model="form_filter.document_type_id"
                      class="form-control form-control-sm mt-1">
                      <option v-for="document_type in document_types" v-bind:key="document_type.id"
                        v-bind:value="document_type.id">
                        {{ $t(document_type.name) }}
                      </option>

                      <option value="">All</option>
                    </select>
                  </div>

                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.company")
                  }}</label>
                  <div class="col-sm-2">
                    <select name="" id="" v-model="form_filter.company_id" class="form-control form-control-sm mt-1">
                      <option v-for="company in companies" :key="company.id" v-bind:value="company.id">
                        {{ company.name }}
                      </option>
                      <option value="">All</option>
                    </select>
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="id_bophan">{{
                      $t("form.department")
                  }}</label>
                  <div class="col-sm-2">
                    <select name="" id="id_bophan" v-model="form_filter.department_id"
                      class="form-control form-control-sm mt-1">
                      <option v-for="department in department_filter" :key="department.id" v-bind:value="department.id">
                        {{ department.name }}
                      </option>
                      <option value="">All</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.group")
                  }}</label>
                  <div class="col-md-4">
                    <treeselect placeholder="Tất cả" :value-consists-of="'LEAF_PRIORITY'" :multiple="true"
                      v-model="form_filter.group_id" :options="tree_groups" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.vendor")
                  }}</label>
                  <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.vendor_id" class="form-control form-control-sm mt-1">
                      <option v-for="vendor in vendor_filter" :key="vendor.id" v-bind:value="vendor.id">
                        {{ vendor.comp_name }}
                      </option>
                      <option value="">All</option>
                    </select>
                  </div>

                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.payment_method")
                  }}</label>
                  <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.method_payment"
                      class="form-control form-control-sm mt-1">
                      <option selected value="1">{{ $t("form.transfer") }}</option>
                      <option value="2">{{ $t("form.cash") }}</option>

                      <option value="">All</option>
                    </select>
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="miss_invoice">{{
                      $t("form.miss_invoice")
                  }}</label>
                  <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.miss_invoice" class="form-control form-control-sm mt-1">
                      <option selected value="1">{{ $t("form.miss_invoice") }}</option>
                      <option value="0">{{ $t("form.full_invoice") }}</option>
                      <option value="">All</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right mt-1" for="">{{
                      $t("form.status")
                  }}</label>
                  <div class="col-sm-6 mt-1 mb-1">
                    <treeselect placeholder="All" :multiple="multiple" :disable-branch-nodes="false"
                      v-model="form_filter.status" :options="status_option" />
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="miss_invoice">{{
                      $t("form.document_by")
                  }}</label>
                  <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.visibility" class="form-control form-control-sm mt-1">
                      <option value="0">{{ $t("form.owner") }}</option>
                      <option value="">All</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right mt-1"
                    :title="$t('form.serial_num')" for="">{{ $t("form.invoice_contract") }}</label>
                  <div class="col-sm-2 mt-1 mb-1">
                    <input type="text" v-model="form_filter.payment_voucher_doc_num"
                      class="form-control form-control-sm mt-1" />
                  </div>
                  <label for="start_date"
                    class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right mt-1">{{
                        $t("form.from_date")
                    }} {{ $t("form.invoice_contract") }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.payment_voucher_doc_date_from"
                      class="form-control form-control-sm mt-1" />
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right mt-1" for="">{{
                      $t("form.to_date")
                  }} {{ $t("form.invoice_contract") }}</label>
                  <div class="col-sm-2">
                    <input type="date" v-model="form_filter.payment_voucher_doc_date_to"
                      class="form-control form-control-sm mt-1" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">Cost
                    center</label>
                  <div class="col-md-2 mt-1 mb-1">
                    <input type="text" v-model="form_filter.payment_voucher_costcenter"
                      class="form-control form-control-sm" />
                  </div>

                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">PR/PO</label>
                  <div class="col-md-2">
                    <input type="text" v-model="form_filter.payment_voucher_prpo_num"
                      class="form-control form-control-sm" />
                  </div>
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.document_reference")
                  }}</label>
                  <div class="col-md-2">
                    <input type="text" v-model="form_filter.doc_reference" class="form-control form-control-sm" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right" for="">{{
                      $t("form.feedback")
                  }}</label>
                  <div class="col-md-2 mt-1 mb-1">
                    <input type="checkbox" v-model="form_filter.feedback" class="form-check-input"
                      style="margin-left: 0px" />
                  </div>

                  <div class="col-md-2"></div>

                  <div class="col-md-2"></div>
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

                  <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editRequest()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->
                  <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1"  title="In" @click="printRequest()"><i class="fas fa-print" title="In hợp đồng"></i></button> -->

                  <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  :title="$t('form.edit')" @click="editRequest()"><i class="fas fa-edit" :title="$t('form.edit')"></i>{{$t('form.edit')}}</button> -->
                  <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.delete')"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" :title="$t('form.delete')"></i> {{$t('form.delete')}} </button> -->
                  <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.cancel')"  @click.prevent="cancelRequest()" > <i class="fas fa-window-close" :title="$t('form.cancel')"></i>  {{$t('form.cancel')}}</button> -->
                  <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.paid')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1" @click.prevent="notification_show()" ><i class="fas fa-bell"></i> {{$t('form.reminder')}}</button> -->
                  <download-excel class="btn btn-default btn-sm ml-1 mt-1" :fields="fieldexcel" :data="tableData"
                    type="xls" :name="fileName">
                    <i class="fa fa-download"></i>
                    Excel
                  </download-excel>
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
                <b-table striped hover responsive :bordered="true" head-variant="light" :sticky-header="true" small
                  :items="requests" primary-key="id" :current-page="current_page" :per-page="per_page" :filter="filter"
                  :filter-included-fields="[
                    'content',
                    'serial_num',
                    'receiver',
                    'amount',
                  ]" selectable ref="selectableTable" filter-debounce="1000" :fields="fields">
                  <template #head(selected)>
                    <!-- {{selected}} -->
                    <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"></b-form-checkbox>
                  </template>

                  <template v-slot:cell(selected)="data">
                    <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected"></b-form-checkbox>
                  </template>
                  <template v-slot:cell(contract_num)="data">
                    <span v-if="data.item.contract">
                      {{ data.item.contract.contract_num }}</span>
                  </template>
                  <template v-slot:cell(newtab)="data">
                    <a target="_blank" :href="viewRequest(data.item.id)"><i title="View in new tab"
                        class="fas fa-external-link-alt"></i></a>
                  </template>
                  <template v-slot:cell(serial_num)="data">
                    <div class="d-flex justify-content-between">
                      <a :href="viewRequest(data.item.id)">{{ data.item.serial_num }}</a>

                      <b-button style="padding: 0" class="ml-2" :id="`popover-${data.item.id}`"
                        v-if="data.item.reminders && data.item.reminders.length > 0" variant="link"
                        :title="$t('form.reminder')">
                        <i class="far fa-bell small"></i>
                      </b-button>
                      <b-popover v-if="data.item.reminders && data.item.reminders.length > 0"
                        :target="`popover-${data.item.id}`" placement="auto" :title="$t('form.reminder')"
                        triggers="hover focus">
                        <template #title>{{ $t("form.reminder") }}</template>
                        <span v-for="reminder in data.item.reminders" v-bind:key="reminder.id">
                          <li>{{ reminder.content }}</li>
                        </span>
                      </b-popover>
                    </div>
                  </template>
                  <template v-slot:cell(content)="data">
                    <span>
                      <a :href="viewRequest(data.item.id)">{{ data.item.content }}</a>
                    </span>
                  </template>
                  <!-- <template v-slot:cell(content)="data">
                                <span  > <a href="#" @click.prevent="printRequest(data.item.id)">{{data.item.content}}</a> </span>
                           </template> -->
                  <template v-slot:cell(feedback)="data">
                    <span v-if="data.item.isRelease == 1" class="badge bg-warning">
                      X
                    </span>
                  </template>
                  <template v-slot:cell(miss_invoice)="data">
                    <div style="text-align: center">
                      <input type="checkbox" :disabled="true" v-model="data.item.miss_invoice" />
                    </div>
                  </template>
                  <template v-slot:cell(printed)="data">
                    <div style="text-align: center">
                      <input type="checkbox" :disabled="true" v-model="data.item.printed" />
                    </div>
                  </template>
                  <template #cell(created_at)="data">
                    <span>{{ data.value | formatDateDB }}</span>
                  </template>
                  <template #cell(send_date)="data">
                    <span>{{ data.value | formatDateDB }}</span>
                  </template>
                  <template #cell(date_receive_hard_doc)="data">
                    <div class="d-flex justify-content-between">
                      <span>{{ data.value | formatDateDB }}</span>
                      <i class="far fa-calendar"></i>
                    </div>
                  </template>
                  <template #cell(amount)="data">
                    <span><strong>{{ data.value.toLocaleString(locale_format) }}
                      </strong></span>
                  </template>
                  <template #cell(document_type_id)="data">
                    <span v-if="data.item.document_type">{{ $t(data.item.document_type.name) }}
                    </span>
                  </template>
                  <!-- <template #cell(receiver)="data">
                                <span  v-if="data.item.vendor" >{{ data.item.vendor.comp_name}} </span>
                                <span  v-else >{{ data.item.money_receiver}} </span>
                           </template>                              -->
                  <template #cell(status)="data">
                    <span v-if="data.value == 3" class="badge bg-success">{{
                        $t("Đã thanh toán")
                    }}</span>
                    <span v-else-if="data.value == 2" class="badge bg-primary">{{
                        $t("Duyệt hoàn tất")
                    }}</span>
                    <span v-else-if="data.value == 1" class="badge badge-secondary">{{ $t("Đang xử lý") }}
                    </span>
                    <span v-else-if="data.value == -1" class="badge bg-danger">{{ $t("Đã huỷ") }}
                    </span>
                    <span v-else class="badge bg-warning">{{ $t("Yêu cầu mới") }} </span>
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
    <create-event-dialog :object_id="selected" v-on:fromReminder="fromReminder" :id="reminder_id" module="PAYREQUEST">
    </create-event-dialog>
    <dialog-update-hard-doc :object_id="selected" :obj="update_date" v-on:fromUpdateHardDoc="fromUpdateHardDoc">
    </dialog-update-hard-doc>
  </div>
</template>

<script>
import Loading from "../../shared/Loading.vue";
import DialogUpdateHardDoc from "./DialogUpdateHardDoc.vue";
import Treeselect from "@riophae/vue-treeselect";
import moment from "moment";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
  props: {
    title: "",
  },
  components: { Treeselect, Loading, DialogUpdateHardDoc },
  data() {
    return {
      tableData: [],
      fileName: "Danh_sach_" + moment(Date()).format("MM/DD/YYYY"),
      fieldexcel: {
        "Số chứng từ": "Số_chứng_từ",
        "Trạng thái": "Trạng_thái",
        "Chờ duyệt": "Chờ_duyệt",
        "Nợ HĐ": "Nợ_HĐ",
        "Ngày nhận bản cứng": "Ngày_nhận_bản_cứng",
        In: "In",
        "Phản hồi": "Phản_hồi",
        "Nội dung": "Nội_dung",
        "Số tiền": "Số_tiền",
        "Tiền tệ": "Tiền_tệ",
        "Đơn vị/cá nhân nhận tiền": "Đơn_vị_cá_nhân_nhận_tiền",
        "Thời hạn thanh toán": "Thời_hạn_thanh_toán",
        "Hình thức thanh toán": "Hình_thức_thanh_toán",
        "Ngày gửi": "Ngày_gửi",
        "Loại chứng từ": "Loại_chứng_từ",
        "Người tạo": "Người_tạo",
        "Mã số nhân viên": "Mã_số_nhân_viên",
        "Mã nhóm": "Mã_nhóm",
        "Bộ phận": "Bộ_phận",
        "Mã bộ phận": "Mã_bộ_phận",
        "Công ty": "Công_ty",
      },
      variant_name: "payment_request_PayRequestStatistics",
      requests: [],
      total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter: "",
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
      document_types: [],
      errors: {},
      status: "",
      locale_format: "de-DE",

      fields: [
        {
          key: "selected",
          label: "All",
          stickyColumn: true,
        },
        {
          key: "newtab",
          label: "",

          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "serial_num",
          label: this.$t("form.document_num"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "status",
          label: this.$t("form.status"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "waiting_for_approval",
          label: this.$t("form.waiting_for_approval"),
          sortable: true,
          stickyColumn: true,
          sortByFormatted: true,
          class: "text-nowrap",
          formatter: (value, key, item) => {
            return item.waitingApproval;
          },
        },
        {
          key: "miss_invoice",
          label: this.$t("form.miss_invoice"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "date_receive_hard_doc",
          label: this.$t("form.date_receive_hard_doc"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "printed",
          label: this.$t("form.print"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "feedback",
          label: this.$t("form.feedback"),
          sortable: true,
          stickyColumn: true,
          tdClass: "text-center",
          class: "text-nowrap",
        },
        {
          key: "content",
          label: this.$t("form.content"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
        {
          key: "amount",
          label: this.$t("form.amount"),
          sortable: true,
          stickyColumn: true,
          class: "text-right",
        },
        {
          key: "currency",
          label: this.$t("form.currency"),
          sortable: true,
          stickyColumn: true,
          class: "text-center text-nowrap",
        },
        {
          key: "receiver",
          label: this.$t("form.unit_individual_receiving_money"),
          sortable: true,
          stickyColumn: true,
          filterByFormatted: true,
          class: "text-nowrap",
          formatter: (value, key, item) => {
            let nameUser = "";
            if (item.vendor != null) {
              nameUser = item.vendor.comp_name;
              return nameUser;
            } else {
              return item.money_receiver;
            }
          },
        },
        {
          key: "finish_date",
          label: this.$t("form.advance_payment_period"),
          sortable: true,
          stickyColumn: true,
          class: "text-center text-nowrap",
        },
        {
          key: "user.name",
          label: this.$t("form.create_by"),
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
        {
          key: "send_date",
          label: this.$t("form.send_date"),
          sortable: true,
          stickyColumn: true,
          class: "text-center text-nowrap",
        },
        {
          key: "document_type_id",
          label: this.$t("form.document_type"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap",
        },
      ],
      tab: "",
      loading: false,
      edit: false,
      token: "",
      page_url_request: "/api/payment/statistics",
      page_url_contracts: "api/payment/contracts",
      page_url_vendors: "/api/category/vendors",
      page_url_group: "/api/category/groups",
      page_url_department: "/api/category/departments",
      page_url_company: "/api/category/companies",
      page_url_document_type: "/api/category/documenttypes",
      page_url_payment_update_paid: "/api/payment/update_paid",
      page_url_payment_update_hard_doc: "/api/payment/update_hard_doc",
      page_url_payment_miss_invoice_check: "/api/payment/miss_invoice_check",
      page_url_payment_printed_check: "/api/payment/printed_check",
      show_search: false,
      form_filter: {
        contract_num: "",
        serial_num: "",
        start_date: "",
        end_date: "",
        status: [],
        contract_type: "",
        document_type_id: "",
        vendor_id: "",
        company_id: "",
        group_id: [],
        department_id: "",
        method_payment: "",
        miss_invoice: "",
        visibility: "",
        payment_voucher_doc_num: "",
        payment_voucher_doc_date_from: "",
        payment_voucher_doc_date_to: "",
        payment_voucher_costcenter: "",
        payment_voucher_prpo_num: "",
        doc_reference: "",
        feedback: false,
      },
      variant_data: [],
      update_date: {
        id: "",
        date: "",
      },
      status_option: [
        { id: "0", label: this.$t("Yêu cầu mới") },
        { id: "1", label: this.$t("Đang xử lý") },
        { id: "2", label: this.$t("Duyệt hoàn tất") },
        { id: "3", label: this.$t("Đã thanh toán") },
        { id: "-1", label: this.$t("Đã huỷ") },
        // {id: '',label: 'All'},
      ],
      multiple: true,
      companies: [],
      departments: [],
      groups: [],
      tree_groups: [],
      vendors: [],
      selected: [],
      selectAll: false,
      save_filter: false,
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;

    $("#spinner-opts small").css({
      display: "inline-block",
      width: "60px",
    });
    //Thiết lập form tìm kiếm mặc định

    this.getVariant();
    this.init();

    //Lấy danh sách dựa vào form tìm kiếm
    this.fetCompany();
    this.fetDocumentType();
    this.fetDepartment();
    this.fetVendor();
    this.fetGroup();
    this.fetGroup_Tree();
    // this.fetchRequest();
  },
  methods: {
    getReminderContent(reminders) {
      var str = "<ul>";
      reminders.forEach((element) => {
        str += "<li>" + element.content + "</li>";
      });
      str += "</ul>";
      return str;
    },
    fromReminder(obj) {
      if (this.selected.length != 1) {
        return "";
      }

      var request_id = this.selected;
      //alert(request_id);
      let index = this.requests.findIndex((item) => {
        return item.id == request_id;
      });

      this.requests[index].reminders.push(obj);
    },

    change_date_receive_hard_doc(item) {
      this.selected = [];
      this.selected.push(item.id);
      this.update_date.id = item.id;
      this.update_date.date =
        item.date_receive_hard_doc == null ? "" : item.date_receive_hard_doc;

      $("#UpdateDateModal").modal("show");
    },
    fromUpdateHardDoc(data) {
      var page_url = this.page_url_payment_update_hard_doc;
      fetch(page_url, {
        method: "POST",
        body: JSON.stringify({ id: "" + data.id, date_receive_hard_doc: "" + data.date }),
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
            toastr.warning(res.message, "");
            return;
          }
          if (res.data.success == "1") {
            toastr.success(this.$t("form.update_success"), "");
            let index = this.requests.findIndex((item) => {
              return item.id == data.id;
            });
            this.requests[index].date_receive_hard_doc = data.date;
            this.selected = [];
          } else {
            toastr.warning(this.$t("form.update_error"), "");
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fetGroup() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_group;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.groups = res.data;
        })
        .catch((err) => console.log(err));
    },
    fetGroup_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_group + "?type=tree_combobox"; 
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.tree_groups = res.data;
                })
                .catch((err) => console.log(err));
        },
    get_document_type_name(id) {
      var doc = this.document_types.find((x) => x.id == id);
      return doc == null ? "" : doc.name;
    },
    get_department_name(id) {
      var department = this.departments.find((x) => x.id == id);
      return department == null ? "" : department.name;
    },
    get_department_code(id) {
      var department = this.departments.find((x) => x.id == id);
      return department == null ? "" : department.code;
    },
    get_group_name(id) {
      var group = this.groups.find((x) => x.id == id);
      return group == null ? "" : group.name;
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
      this.form_filter.status = [];
      this.form_filter.miss_invoice = "";

      //variant:
      if (this.variant_data != "undefined" && this.variant_data.length > 0) {
        this.variant_data.forEach((variant) => {
          if (variant.name == "form_filter") {
            // console.log(variant.properties.serial_num);
            this.save_filter = variant.save_filter;
            //nếu có nhấn lưu điều kiện lọc thì mới load vào form
            if (this.save_filter) {
              this.form_filter = variant.properties;

              for (let field in this.form_filter) {
                if (this.form_filter[field] == null) {
                  this.form_filter[field] = "";
                }
              }
              if (this.form_filter.status == null) {
                this.form_filter.status = [];
              }
            }
          }
        });
      }

      //   this.form_filter.start_date = start_date.getFullYear()+"-"+ fixDigit(start_date.getMonth()+1)+"-"+fixDigit(start_date.getDate());;
      // this.form_filter.end_date = today.getFullYear()+"-"+ fixDigit(today.getMonth()+1)+"-"+fixDigit(today.getDate());
    },
    fetDocumentType() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_document_type + "?module=PAYMENT";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.document_types = res.data;
        })
        .catch((err) => console.log(err));
    },
    fetVendor() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_vendors;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.vendors = res.data;
        })
        .catch((err) => console.log(err));
    },
    fetCompany() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.companies = res.data;
        })
        .catch((err) => console.log(err));
    },
    fetDepartment() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_department;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.departments = res.data;
        })
        .catch((err) => console.log(err));
    },
    showAdd(code) {
      window.location.href = "payment/requests?type=add&doctype=" + code;
    },
    fetchRequest(page, search) {
      this.loading = true;
      this.requests = Array();
      const params = new URLSearchParams({
        contract_num: this.form_filter.contract_num,
        serial_num: this.form_filter.serial_num,
        start_date: this.form_filter.start_date,
        end_date: this.form_filter.end_date,
        status: this.form_filter.status,
        contract_type: this.form_filter.contract_type,
        vendor_id: this.form_filter.vendor_id,
        company_id: this.form_filter.company_id,
        document_type_id: this.form_filter.document_type_id,
        department_id: this.form_filter.department_id,
        method_payment: this.form_filter.method_payment,
        miss_invoice: this.form_filter.miss_invoice,
        visibility: this.form_filter.visibility,
        payment_voucher_doc_num: this.form_filter.payment_voucher_doc_num,
        payment_voucher_doc_date_from: this.form_filter.payment_voucher_doc_date_from,
        payment_voucher_doc_date_to: this.form_filter.payment_voucher_doc_date_to,
        payment_voucher_costcenter: this.form_filter.payment_voucher_costcenter,
        payment_voucher_prpo_num: this.form_filter.payment_voucher_prpo_num,
        doc_reference: this.form_filter.doc_reference,
        feedback: this.form_filter.feedback,
      });

      var page_url =
        this.page_url_request +
        "?" +
        params.toString() +
        "&no-cache=" +
        new Date().getTime();

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

          if (res.success == "1") {
            this.requests = res.data;
            var list = [];
            for (let i = 0; i < this.requests.length; i++) {
              var is_status = "";
              if (this.requests[i].status == "1") {
                is_status = "Đang xử lý";
              } else if (this.requests[i].status == "2") {
                is_status = "Duyệt hoàn tất";
              } else if (this.requests[i].status == "-1") {
                is_status = "Đã huỷ";
              } else if (this.requests[i].status == "3") {
                is_status = "Đã thanh toán";
              } else {
                is_status = "Yêu cầu mới";
              }

              var miss_invoice = "";
              if (this.requests[i].miss_invoice == 0) miss_invoice = "Không";
              else miss_invoice = "Có";

              var hard_doc = "";
              if (this.requests[i].date_receive_hard_doc == 0) hard_doc = "Không";
              else hard_doc = "Có";
              var printed = "";
              if (this.requests[i].printed == 0) printed = "Chưa in";
              else printed = "Đã in";

              var send_date = "";
              if (this.requests[i].send_date != null) {
                send_date = moment(this.requests[i].send_date).format("MM/DD/YYYY");
              } else {
                send_date = "";
              }
              var method_payment = "";
              if (this.requests[i].method_payment == 1) {
                method_payment = "Tiền mặt";
              } else {
                method_payment = "Chuyển khoản";
              }

              list.push({
                Số_chứng_từ: this.requests[i].serial_num,
                Trạng_thái: is_status,
                Chờ_duyệt: this.requests[i].waitApprove,
                Nợ_HĐ: miss_invoice,
                Ngày_nhận_bản_cứng: hard_doc,
                In: printed,
                Phản_hồi: this.requests[i].noting,
                Nội_dung: this.requests[i].content,
                Số_tiền: this.requests[i].amount,
                Tiền_tệ: this.requests[i].currency,
                Đơn_vị_cá_nhân_nhận_tiền: this.requests[i].money_receiver,
                Thời_hạn_thanh_toán: this.requests[i].finish_date,
                Hình_thức_thanh_toán: method_payment,
                Ngày_gửi: send_date,
                Loại_chứng_từ: this.get_document_type_name(
                  this.requests[i].document_type_id
                ),
                Người_tạo: this.requests[i].user.name,
                Mã_số_nhân_viên: this.requests[i].user.username,
                Mã_nhóm: this.get_group_name(this.requests[i].group_id),
                Bộ_phận: this.get_department_name(this.requests[i].department_id),
                Mã_bộ_phận: this.get_department_code(this.requests[i].department_id),
                Công_ty: this.requests[i].company_id,
              });
            }
            this.tableData = list;
          }
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    editRequest() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
        return;
      }
      var id = this.selected;
      window.location.href = "payment/requests?type=edit&id=" + id;
    },
    viewRequest(id) {
      //window.location.href= "payment/requests?type=view&id="+id;
      return "payment/statistics?type=view&id=" + id;
    },

    printRequest() {
      if (this.selected.length != 1) {
        toastr.info("Vui lòng chọn 1 dòng dữ liệu", "");
        return;
      }
      var id = this.selected;
      window.location.href = "payment/requests?type=print&id=" + id;
    },

    cancelRequest() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
        return;
      }
      var id = this.selected;
      let index = this.requests.findIndex((i) => {
        //console.log("id"+i.id);
        return i.id == id;
      });
      console.log("id=" + id);
      this.$bvModal
        .msgBoxConfirm(this.$t("Xác nhận huỷ") + "?", {
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
            var page_url = "/api/payment/update_cancel";
            fetch(page_url, {
              method: "POST",
              body: JSON.stringify({ id: "" + id }),
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
                if (res.data.success == "1") {
                  //  toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
                  toastr.success(this.$t("form.cancel_success"), "");
                  this.requests[index].status = -1;
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
    },
    deleteRequest() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
        return;
      }
      var id = this.selected;
      let index = this.requests.findIndex((i) => {
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
                if (res.data.success == "1") {
                  // this.requests = [];
                  this.requests.splice(index, 1);
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
      this.fetchRequest(null, $("#search").val());
    },
    setMissInvoice(event, payrequest_id) {
      var miss_invoice = event.target.checked ? 1 : 0;
      var page_url = this.page_url_payment_miss_invoice_check;
      fetch(page_url, {
        method: "post",
        body: JSON.stringify({ id: payrequest_id, miss_invoice: miss_invoice }),
        headers: {
          Authorization: this.token,
          "Content-Type": "applocation/json",
          Accept: "applocation/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.statuscode == "403") {
            toastr.warning(res.message, "");
            return;
          }
          if (res.data.success == "1") {
            if (res.data.miss_invoice == "1") {
              toastr.success(this.$t("form.check_success"), "");
            } else {
              toastr.success(this.$t("form.uncheck_success"), "");
            }
          } else {
            toastr.warning(this.$t("form.check_error"), "");
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    setPrinted(event, payrequest_id) {
      var printed = event.target.checked ? 1 : 0;
      var page_url = this.page_url_payment_printed_check;
      fetch(page_url, {
        method: "post",
        body: JSON.stringify({ id: payrequest_id, printed: printed }),
        headers: {
          Authorization: this.token,
          "Content-Type": "applocation/json",
          Accept: "applocation/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.statuscode == "403") {
            toastr.warning(res.message, "");
            return;
          }
          if (res.data.success == "1") {
            if (res.data.printed == "1") {
              toastr.success(this.$t("form.check_success"), "");
            } else {
              toastr.success(this.$t("form.uncheck_success"), "");
            }
          } else {
            toastr.warning(this.$t("form.check_error"), "");
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    filter_data() {
      this.saveVariant([this.form_filter]);
      //console.log(this.form_filter);
      this.fetchRequest();
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

          this.fetchRequest();
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
        for (let i in this.requests) {
          this.selected.push(this.requests[i].id);
        }
      }
    },
    changeStatus(status) {
      this.status = status;
      this.fetchRequest();
    },
    notification_show() {
      if (this.selected.length != 1) {
        toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"));
        return;
      }
      var id = this.selected;
      let index = this.requests.findIndex((item) => {
        return item.id == id;
      });
      //this.$bvModal.show("create_event_dialog")
      //$("#create_event_dialog").dialog('show');
      //  $('#create_event_dialog').modal('show');
      $("#createEventModal").modal("show");
    },
    //cập nhật trạng thái đã thanh toán
    update_paid() {
      if (this.selected.length < 1) {
        toastr.info(this.$t("Vui lòng chọn dòng dữ liệu"));
        return;
      }
      var ids = this.selected;
      //  let index = this.requests.findIndex(item=>{
      //    return item.id == id;
      //  });

      this.$bvModal
        .msgBoxConfirm(this.$t("Đã thanh toán") + "?", {
          okVariant: "danger",
          okTitle: "OK",
          cancelTitle: "Cancel",
          centered: true,
        })
        .then((value) => {
          if (value) {
            var page_url = this.page_url_payment_update_paid;
            fetch(page_url, {
              method: "POST",
              body: JSON.stringify({ ids: "" + ids }),
              headers: {
                Authorization: this.token,
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
              },
            })
              .then((res) => res.json())
              .then((res) => {
                //console.log(res);
                if (res.statuscode == "403") {
                  toastr.warning(res.message, "");
                  return;
                }
                if (res.data.success == "1") {
                  toastr.success(this.$t("form.update_success"), "");
                  console.log(res.data.ids);
                  var paids = res.data.ids;
                  paids.forEach((paid_id) => {
                    let index = this.requests.findIndex((item) => {
                      return item.id == paid_id;
                    });
                    this.requests[index].status = 3;
                  });
                  //this.requests[index].status = 3;
                  this.selected = [];
                } else {
                  toastr.warning(this.$t("form.update_error"), "");
                }
              })
              .catch((err) => {
                console.log(err);
              });
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    currentDate() {
      const current = new Date();
      const date =
        "${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()}";
      return date;
    },
  },
  computed: {
    reminder_id() {
      var id = "";

      return id;
    },
    rows() {
      this.pageOptions = [10, 50, 100, 500, { value: this.requests.length, text: "All" }];
      return this.requests.length;
    },
    department_filter() {
      let company_id = this.form_filter.company_id;
      // this.contract.department_id = "";
      return this.departments.filter(function (item) {
        if (item.company_id == company_id) {
          return true;
        }
      });
    },
    vendor_filter() {
      let company_id = this.form_filter.company_id;
      // this.contract.vendor_id = "";
      return this.vendors.filter(function (item) {
        if (item.company_id == company_id) {
          return true;
        }
      });
    },
    currentDateTime() {
      const current = new Date();
      const date =
        current.getFullYear() + "-" + (current.getMonth() + 1) + "-" + current.getDate();
      const time =
        current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
      const dateTime = date + " " + time;

      return dateTime;
    },
  },
};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 1px !important;
}
</style>
