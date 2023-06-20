<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5 class="m-0 text-dark">
              <i class="nav-icon fas fa-file-contract"></i>{{ $t(title) }}
            </h5>
          </div>

          <div class="col-sm-6">
            <div class="float-sm-right">
              <div v-if="creatable_workflows.length > 0" class="btn-group-vertical ">
                <button class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                  {{ $t('form.create') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                  <div v-for="(workflow, index) in creatable_workflows" v-bind:key="index">
                    <a href="#" @click.prevent="showCreateWorkflowDialog(workflow.id)" class="dropdown-item">{{
                        workflow.name
                    }}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <b-overlay :show="loading" rounded="sm">


          <div class="card">
            <div class="card-body">
              <div class="row mt-0">
                <div class="col-md-12">
                  <div class="form-group row">
                    <div class="btn-group ">
                      <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                        <span v-if="!show_search">{{ $t('form.show_search') }}</span>
                        <span v-if="show_search">{{ $t('form.hide_search') }}</span>
                      </button>
                      <button type="button" class="btn btn-warning btn-xs " @click="showSearch()">
                        <i v-if="show_search" class="fas fa-angle-up"></i>
                        <i v-else class="fas fa-angle-down"></i>
                      </button>
                    </div>
                    <button @click="filter_data()" :title="$t('form.filter')" class="btn btn-secondary  btn-xs ml-1"><i
                        class="fas fa-sync-alt" :title="$t('form.reload')"></i></button>
                    <span v-if="save_filter" :title="$t('form.filter')" class="ml-1"><i class="fas fa-filter"
                        :title="$t('form.filter')"></i></span>
                  </div>
                </div>
              </div>

              <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                <div class="col-sm-12">
                  <div class="form-group row">
                    <label for="start_date" class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right">
                      Tạo từ ngày
                    </label>
                    <div class="col-sm-2">
                      <input type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1">
                    </div>
                    <label class="col-form-label-sm col-sm-1 col-form-label text-left  text-md-right" for="">
                      đến ngày
                    </label>
                    <div class="col-sm-2">
                      <input type="date" v-model="form_filter.end_date" class="form-control form-control-sm mt-1">
                    </div>

                    <label class="col-form-label-sm col-sm-1 col-form-label text-left  text-md-right"
                      :title="$t('form.serial_num')" for="">{{ $t('form.serial_num') }}</label>
                    <div class="col-sm-4">
                      <treeselect placeholder="Tất cả" :multiple="true" :disable-branch-nodes="false"
                        v-model="form_filter.serial_numbers" :options="tree_serials" />
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1" for="">
                      Phạm vi
                    </label>
                    <div class="col-sm-5 mt-1 mb-1">
                      <treeselect placeholder="Tất cả" :multiple="true" :disable-branch-nodes="false"
                        v-model="form_filter.scopes" :options="tree_departments" />
                    </div>
                    <label class="col-form-label-sm col-sm-1 col-form-label text-left  text-md-right mt-1" for="">
                      Người tạo
                    </label>
                    <div class="col-sm-4 mt-1 mb-1">
                      <treeselect placeholder="Tất cả" :multiple="true" :disable-branch-nodes="true"
                        v-model="form_filter.users" :options="tree_users" />
                    </div>

                  </div>

                  <div class="form-group row">
                    <label class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1" for="">
                      Loại yêu cầu
                    </label>
                    <div class="col-sm-5 mt-1 mb-1">
                      <treeselect placeholder="Tất cả" :multiple="true" :disable-branch-nodes="false"
                        v-model="form_filter.base_workflow_ids" :options="tree_workflowtypes" />
                    </div>
                    <label class="col-form-label-sm col-sm-1 col-form-label text-left  text-md-right mt-1" for="">{{
                        $t('form.status')
                    }}</label>
                    <div class="col-sm-4 mt-1 mb-1">
                      <treeselect placeholder="Tất cả" :multiple="true" :disable-branch-nodes="false"
                        v-model="form_filter.status" :options="tree_statusoptions" />
                    </div>

                  </div>
                  <div class="col-md-12" style="text-align:center">
                    <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1" @click="filter_data()"> <i
                        class="fa fa-search"></i> {{ $t('form.find') }} </button>
                    <button type="reset" class="btn btn-secondary btn-sm mt-1 mb-1" @click="clearFilter()"> <i
                        class="fa fa-reset"></i> {{ $t('form.clear') }}</button>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" v-model="save_filter">
                      <label class="form-check-label" for="save_filter"> <i>{{ $t('form.save_filter') }}</i> </label>
                    </div>
                  </div>
                </div>


              </div>

              <div class="row mt-1 " style="background-color:#F4F6F9">

                <div class="col-md-9">
                  <div class="form-group row">
                    <button type="button" :disabled="disableEditDocument()" class="btn btn-warning btn-sm ml-1 mt-1"
                      :title="$t('form.edit')" @click="editDocument()"><i class="fas fa-edit"
                        :title="$t('form.edit')"></i>{{ $t('form.edit')
                        }}</button>
                    <button type="button" :disabled="disableCancelDocument()" class="btn btn-danger  btn-sm ml-1 mt-1"
                      :title="$t('form.cancel')" @click.prevent="cancelDocument()"> <i class="fas fa-window-close"
                        :title="$t('form.cancel')"></i>
                      {{ $t('form.cancel') }}</button>
                    <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1"
                      @click.prevent="notification_show()"><i class="fas fa-bell"></i> {{ $t('form.reminder')
                      }}</button>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="row mt-1">
                    <div class="input-group input-group-sm  col-12">
                      <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter"
                        :placeholder="$t('form.search')" aria-label="Search">
                      <span class="input-group-append">
                        <button type="button" class="btn btn-default btn-flat mb-1"><i
                            class="fas fa-search"></i></button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="active tab-pane" id="yeucaumoi">
                <div class="row">
                  <b-table striped hover responsive :bordered="true" head-variant="light" :sticky-header="false" small
                    :items="workflows" :current-page="pagination.current_page" :per-page="pagination.item_per_page"
                    :filter="filter" selectable ref="selectableTable" :fields="fields" @filtered="onFiltered">
                    <template #head(selected)>
                      <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"></b-form-checkbox>
                    </template>
                    <template v-slot:cell(newtab)="data">
                      <a target="_blank" :href="viewRequest(data.item.id)"><i title="View in new tab"
                          class="fas fa-external-link-alt"></i></a>
                    </template>
                    <template v-slot:cell(selected)="data">
                      <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected"></b-form-checkbox>
                    </template>
                    <template v-slot:cell(serial_num)="data">
                      <div>
                        <b-badge variant="danger" v-if="data.item.is_new"><i class="fas fa-fire text-white"></i>Mới
                        </b-badge>

                        <a :href="viewRequest(data.item.id)">{{ data.item.serial_num }}</a>
                        <i v-if="data.item.reminders && data.item.reminders.length > 0" class="far fa-bell"
                          :id="`popover-${data.item.id}`" :title="$t('form.reminder')"></i>


                        <b-popover v-if="data.item.reminders && data.item.reminders.length > 0"
                          :target="`popover-${data.item.id}`" placement="auto" :title="$t('form.reminder')"
                          triggers="hover focus">
                          <template #title>{{ $t('form.reminder') }}</template>
                          <span v-for="reminder in data.item.reminders " v-bind:key="reminder.id">
                            <li>{{ reminder.content }}</li>

                          </span>
                        </b-popover>
                      </div>

                    </template>
                    <template v-slot:cell(title)="data">
                      <span> <a :href="viewRequest(data.item.id)">{{ data.item.title }}</a> </span>
                    </template>
                    <template #cell(user_id)="data">
                      <span>{{ getUsernameById(data.value) }}</span>
                    </template>
                    <template #cell(company_id)="data">
                      <span>{{ getCompanyNameById(data.value) }}</span>
                    </template>
                    <template #cell(original_id)="data">
                      <span>{{ getWorkflowNameById(data.value) }}</span>
                    </template>
                    <template #cell(created_at)="data">
                      <span>{{ data.value | formatDateDB }}</span>
                    </template>

                    <template #cell(status)="data">
                      <b-badge :id="'status_' + data.item.id" :variant="getStatusVariant(data.item)" style="width:100%">
                        {{ getStatusText(data.item) }}
                        <i :class="data.item.is_release ? 'fas fa-exclamation-triangle' : 'fas fa-question-circle'"></i>
                      </b-badge>

                      <b-popover :target="'status_' + data.item.id" placement="right" :title="getStatusTitle(data.item)"
                        triggers="hover focus">
                        <strong v-if="data.value == 2">{{ data.item.current_phase }}</strong>
                        <span v-if="data.value == 1">
                          <table v-if="data.item.is_release">
                            <tr>
                              <td width="40%"><strong>Người từ chối:</strong> </td>
                              <td>{{ data.item.feedback.name }}</td>
                            </tr>
                            <tr>
                              <td><strong>Thời gian: </strong></td>
                              <td>{{ data.item.feedback.date }}</td>
                            </tr>
                            <tr>
                              <td><strong>Lí do: </strong></td>
                              <td>{{ data.item.feedback.note }}</td>
                            </tr>
                          </table>
                          <table v-else>
                            <tr>
                              <td width="40%"><strong>Chờ duyệt:</strong> </td>
                              <td>{{ data.item.waiting_approval }}</td>
                            </tr>
                            <tr>
                              <td><strong>Gửi duyệt lúc: </strong></td>
                              <td>{{ data.item.send_date }}</td>
                            </tr>
                          </table>
                        </span>
                      </b-popover>
                    </template>

                  </b-table>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label class="col-form-label-sm col-md-6" style="text-align:left" for="">Số phần tử mỗi
                          trang:</label>
                        <div class="col-md-3">
                          <b-form-select size="sm" v-model="pagination.item_per_page"
                            :options="pagination.page_options">
                          </b-form-select>
                        </div>

                        <div class="col-md-3">
                          <b-pagination v-model="pagination.current_page" :total-rows="rows"
                            :per-page="pagination.item_per_page" align="fill" size="sm" class="my-0" first-number
                            last-number></b-pagination>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-overlay>

        <!-- /.card -->
      </div>
    </div>

    <!-- dialog -->
    <create-event-dialog :object_id="selected" v-on:fromReminder="fromReminder" :id="reminder_id" module="REPORT">
    </create-event-dialog>
    <dialog-create-workflow :wlworkflow_id="create_workflow_id" :current_workflow_code="current_workflow_code"
      :document_id='edit_workflow_document_id' @onUpdateWorkflow="onUpdateWorkflow">
    </dialog-create-workflow>
  </div>

</template>

<script>
import Treeselect from '@riophae/vue-treeselect'
import moment from 'moment';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import DialogCreateWorkflow from "./dialogs/DialogCreateWorkflow.vue";

export default {
  props: {
    title: String,
    current_workflow_code: String,
    available_workflows: [],
    creatable_workflows: [],
  },
  components: {
    Treeselect,
    DialogCreateWorkflow
  },
  data() {
    return {
      workflows: [],
      pagination: {
        item_per_page: 10,
        current_page: 1,
        page_options: [10, 50, 100, 500, { value: this.rows, text: "All" }],
      },
      filter: "",
      users: [],
      tree_users: [],
      fields: [
        {
          key: 'selected',
          label: 'All',
        },
        {
          key: 'newtab',
          label: "",
          class: "text-nowrap"
        },
        {
          key: 'company_id',
          label: this.$t('form.company'),
          sortable: true,
          class: "text-center text-nowrap"
        },
        {
          key: 'serial_num',
          label: this.$t('form.document_num'),
          sortable: true,
          class: "text-center text-nowrap"
        },
        {
          key: 'original_id',
          label: 'Loại yêu cầu',
          sortable: true,
          class: "text-center text-nowrap"
        },
        {
          key: 'status',
          label: this.$t('form.status'),
          sortable: true,
          class: "text-center text-nowrap"
        },
        {
          key: 'title',
          label: this.$t('form.about'),
          thClass: "text-center text-nowrap",
          tdClass: "text-nowrap",
        },
        {
          key: 'user_id',
          label: 'Người tạo yêu cầu',
          sortable: true,
          class: "text-center text-nowrap"
        },
        {
          key: 'created_at',
          label: this.$t('form.create_date'),
          sortable: true,
          class: "text-center text-nowrap"
        },

      ],
      loading: false,
      token: "",
      page_url_users: "api/user/all",
      page_url_request: "/api/works/workflows",
      page_url_department: "/api/category/departments",
      page_url_company: "/api/category/companies",
      show_search: false,
      form_filter: {
        serial_numbers: [],
        start_date: "",
        end_date: "",
        status: [],
        base_workflow_ids: [],
        scopes: [],
        users: [],
      },
      create_workflow_id: null,
      edit_workflow_document_id: null,

      companies: [],
      tree_departments: [],
      variant_data: [],
      selected: [],
      selectAll: false,
      save_filter: false,
      variant_name: 'workflow_list_' + this.current_workflow_code,
    }
  },
  created() {

    this.token = "Bearer " + window.Laravel.access_token;

    $("#spinner-opts small").css({
      display: "inline-block",
      width: "60px"
    });
    //Thiết lập form tìm kiếm mặc định
    this.getVariant();
    this.init();
    //Lấy danh sách dựa vào form tìm kiếm
    this.fetCompany();
    this.fetDepartmentTree();
    this.getUser();
    this.getUserTree();
  },
  mounted: function () {
    var thElm;
    var startOffset;

    Array.prototype.forEach.call(
      document.querySelectorAll("table th"),
      function (th) {
        th.style.position = 'relative';

        var grip = document.createElement('div');
        grip.innerHTML = "&nbsp;";
        grip.style.top = 0;
        grip.style.right = 0;
        grip.style.bottom = 0;
        grip.style.width = '5px';
        grip.style.position = 'absolute';
        grip.style.cursor = 'col-resize';
        grip.addEventListener('mousedown', function (e) {
          thElm = th;
          startOffset = th.offsetWidth - e.pageX;
        });

        th.appendChild(grip);
      });

    document.addEventListener('mousemove', function (e) {
      if (thElm) {
        thElm.style.width = startOffset + e.pageX + 'px';
      }
    });

    document.addEventListener('mouseup', function () {
      thElm = undefined;
    });
  },
  methods: {
    onUpdateWorkflow(workflow) {
      let index = this.workflows.findIndex((item) => {
        return item.id == workflow.id;
      });

      if (index != -1) {
        for (const [key, value] of Object.entries(workflow)) {
          if (key in this.workflows[index]) {
            this.workflows[index][key] = value;
          }
        };
        this.workflows[index].is_new = true;
      } else {
        workflow.is_new = true;
        this.workflows.unshift(workflow);
      }
    },
    getStatusVariant(document) {
      if (document.status == 3)
        return 'success';
      else if (document.status == 2)
        return 'primary';
      else if (document.status == 1)
        return document.is_release ? 'danger' : 'secondary';
      else if (document.status == 0)
        return 'warning';
      else if (document.status == -1)
        return 'danger';
    },
    getStatusText(document) {
      if (document.status == 3)
        return 'Hoàn thành';
      else if (document.status == 2)
        return 'Đang xử lí';
      else if (document.status == 1)
        return document.is_release ? 'Bị từ chối duyệt' : 'Đang chờ duyệt';
      else if (document.status == 0)
        return 'Yêu cầu mới';
      else if (document.status == -1)
        return 'Đã hủy';
    },
    getStatusTitle(document) {
      if (document.status == 3)
        return 'YÊU CẦU ĐÃ HOÀN THÀNH';
      else if (document.status == 2)
        return 'GIAI ĐOẠN HIỆN TẠI';
      else if (document.status == 1)
        return document.is_release ? 'TỪ CHỐI YÊU CẦU' : 'ĐANG CHỜ DUYỆT';
      else if (document.status == 0)
        return 'YÊU CẦU MỚI';
      else if (document.status == -1)
        return 'YÊU CẦU ĐÃ BỊ HỦY';
    },
    getWorkflowNameById(id) {
      var workflow = this.available_workflows.find((x) => x.id == id);
      return workflow == null ? id : workflow.name;
    },
    getUsernameById(id) {
      var user = this.users.find((x) => x.id == id);
      return user == null ? id : user.name;
    },
    getCompanyNameById(id) {
      var company = this.companies.find((x) => x.id == id);
      return company == null ? "" : company.name;
    },
    getUser() {
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
    getUserTree() {
      var page_url = this.page_url_users + "?type=tree_combobox";
      console.log(page_url);
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.tree_users = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fromReminder(obj) {
      if (this.selected.length != 1) {
        return "";
      }
      var request_id = this.selected;
      let index = this.workflows.findIndex(item => {
        return item.id == request_id;
      });

      this.workflows[index].reminders.push(obj);
    },
    //Khởi tạo các giá trị trong form tìm kiếm
    init() {
      const start_date = new Date();
      const today = new Date();
      start_date.setMonth(start_date.getMonth() - 1);
      this.form_filter.start_date = start_date.getFullYear() + "-" + fixDigit(start_date.getMonth() + 1) + "-" + fixDigit(start_date.getDate());;
      this.form_filter.end_date = today.getFullYear() + "-" + fixDigit(today.getMonth() + 1) + "-" + fixDigit(today.getDate());

      this.form_filter.status = [];
      //variant:
      if (this.variant_data != 'undefined' && this.variant_data.length > 0) {
        this.variant_data.forEach(variant => {
          if (variant.name == 'form_filter') {
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
    },
    fetCompany() {
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          //console.log("Xin chao");
          this.companies = res.data;
        })
        .catch(err => console.log(err));
    },
    fetDepartmentTree() {
      var page_url = this.page_url_department + "?type=tree_combobox";;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.tree_departments = res.data;
        })
        .catch(err => console.log(err));
    },
    showCreateWorkflowDialog(workflow_id) {
      this.create_workflow_id = workflow_id;
      this.edit_workflow_document_id = null;
      $("#DialogCreateWorkflow").modal("show");
      //window.location.href = "/works/list/" + this.current_workflow_code + "?type=add&wlid=" + wlid;
    },
    fetchRequest() {

      this.loading = true;
      this.workflows = Array();
      const params = new URLSearchParams({
        serial_numbers: this.form_filter.serial_numbers,
        start_date: this.form_filter.start_date,
        end_date: this.form_filter.end_date,
        status: this.form_filter.status,
        base_workflow_ids: this.form_filter.base_workflow_ids,
        scopes: this.form_filter.scopes,
        users: this.form_filter.users,
        current_workflow_code: this.current_workflow_code,
      });
      var page_url = this.page_url_request + '?' + params.toString();

      fetch(page_url, {

        headers: {
          Authorization: this.token,
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        }
      })
        .then(res => res.json())
        .then(res => {
          if (res.statuscode == "403") {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }

          this.workflows = res.data;
          this.loading = false;
        }).catch(err => {
          console.log(err);
          this.loading = false;

        });
    },
    disableEditDocument() {
      if (this.selected.length != 1) {
        return true;
      }
      return false;
    },
    disableCancelDocument() {
      if (this.selected.length != 1) {
        return true;
      }
      return false;
    },
    editDocument() {
      if (this.selected.length != 1) {
        toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'), "");
        return;
      }
      const params = new URLSearchParams({
        id: this.selected[0],
      });

      var page_url = "/api/works/can-edit-document" + "?" + params.toString();
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          if (data == true) {
            this.edit_workflow_document_id = this.selected[0];
            $("#DialogCreateWorkflow").modal("show");
          }
          else {
            toastr.error("Bạn không có quyền chỉnh sửa", "Thông báo");
          }
        })
        .catch(err => console.log(err));
    },
    viewRequest(id) {
      return "/works/list/" + this.current_workflow_code + "?type=view&id=" + id;
    },
    cancelDocument() {
      if (this.selected.length != 1) {
        toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'), "");
        return;
      }
      const params = new URLSearchParams({
        id: this.selected[0],
      });

      var page_url = "/api/works/can-cancel-document" + "?" + params.toString();
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          if (data == true) {
            var id = this.selected;
            let index = this.workflows.findIndex(i => {
              return i.id == id;
            });
            this.$bvModal.msgBoxConfirm(this.$t('Xác nhận huỷ') + "?", {
              title: '',
              size: 'sm',
              buttonSize: 'sm',
              okVariant: 'danger',
              okTitle: 'OK',
              cancelTitle: 'Cancel',
              footerClass: 'p-2',
              hideHeaderClose: false,
              centered: true
            }).then(value => {

              if (value) {
                var page_url = "api/works/cancel-workflow";
                fetch(page_url, {
                  method: "POST",
                  body: JSON.stringify({ "id": "" + id }),
                  headers: {
                    Authorization: this.token,
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                  }
                })
                  .then(res => res.json())
                  .then(res => {
                    if (res.statuscode == "403") {
                      toastr.warning(this.$t(res.message), this.$t('form.warning'));
                      return;
                    }
                    if (res.data.success == '1') {
                      //  toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
                      toastr.success(this.$t('form.cancel_success'), "");
                      this.workflows[index].status = -1;
                      this.selected = [];

                    } else {

                      toastr.warning(this.$t(res.data.message), this.$t('form.warning'));
                    }

                  }).catch(err => console.log(err));
              }
            })
              .catch(err => {
                console.log(err);
              })
          }
          else {
            toastr.error("Bạn không có quyền hủy yêu cầu", "Thông báo");
          }
        })
        .catch(err => console.log(err));
    },
    filter_data() {
      this.saveVariant();
      this.fetchRequest();
    },
    getVariant() {
      var page_url = 'api/user/variant?url=' + this.variant_name + '&no-cache=' + new Date().getTime();
      fetch(page_url, {
        method: "GET",

        headers: {
          Authorization: this.token,
          'Content-Type': 'application/json',
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest"
        }
      }).then(res => res.json())
        .then(res => {
          if (res.statuscode == "403") {
            return;
          }
          if (res.success == '1') {
            this.variant_data = res.data;
            this.init();

          }

          this.fetchRequest();

        }).catch(err => {
          console.log(err);
        })
    },
    saveVariant() {
      var page_url = 'api/user/variant';

      var data = {
        url: this.variant_name,
        name: 'form_filter',
        save_filter: this.save_filter ? 1 : 0,
        properties: this.form_filter
      }

      fetch(page_url, {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          Authorization: this.token,
          'Content-Type': 'application/json',
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest"
        }
      }).then(res => res.json())
        .then(res => {
          console.log(res);
        }).catch(err => {
          console.log(err);
        })

    },
    showSearch() {
      this.show_search = !this.show_search;
    },
    clearFilter() {
      for (let field in this.form_filter) {
        this.form_filter[field] = "";
      }
      this.init();
    },
    select() {
      this.selected = [];
      if (this.selectAll) {
        for (let i in this.workflows) {
          this.selected.push(this.workflows[i].id);

        }
      }
    },
    notification_show() {
      if (this.selected.length != 1) {
        toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'));
        return;

      }
      var id = this.selected;
      let index = this.workflows.findIndex(item => {
        return item.id == id;
      });

      $('#createEventModal').modal('show');
    },
    onFiltered(filteredItems) {
      this.pagination.current_page = 1;
    },
  },
  computed: {
    reminder_id() {
      var id = "";
      if (this.selected.length != 1) {

        return "";
      }
      var request_id = this.selected;
      let index = this.workflows.findIndex(item => {
        return item.id == request_id;
      });
      if (this.workflows[index].reminder_one) {
        id = this.workflows[index].reminder_one.id;
      }
      return id;
    },
    rows() {
      this.pagination.page_options = [10, 50, 100, 500, { value: this.workflows.length, text: "All" }];
      return this.workflows.length;
    },
    tree_workflowtypes() {
      let list = [];
      if (this.available_workflows) {
        list = this.available_workflows.map(workflow_type =>
        ({
          id: workflow_type.id,
          label: workflow_type.name,
        }))
        return list;
      }
      return list;
    },
    tree_statusoptions() {
      return [
        { id: '0', label: this.$t('Yêu cầu mới') },
        { id: '1', label: this.$t('Đang chờ duyệt') },
        { id: '2', label: this.$t('Đang xử lí') },
        { id: '3', label: this.$t('Đã hoàn thành') },
        { id: '-1', label: this.$t('Đã huỷ') },
      ];
    },
    tree_serials() {
      return this.workflows.map(workflow =>
      ({
        id: workflow.serial_num,
        label: workflow.serial_num,
      }));
    }
  }
}
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 1px !important;
}
</style>
