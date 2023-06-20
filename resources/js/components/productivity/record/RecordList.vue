<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5 class="m-0 text-dark">
              <i class="nav-icon fas fa-industry"></i> {{ $t(title) }}
            </h5>
          </div>
        </div>
      </div>
    </div>
    <b-overlay :show="loading" rounded="sm">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <record-filter v-model="filter" v-on:updateFilter="updateFilter" :requireMonth="false">
                  </record-filter>
                  <div v-if="this.filter.year" class="col-md-12" style="text-align: center">
                    <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1" @click="startFilter()">
                      <i class="fa fa-search"></i> Lọc dữ liệu
                    </button>
                  </div>
                </div>
              </div>
              <div class="active tab-pane">
                <div class="row">
                  <b-table v-show="records.length > 0" striped hover responsive :bordered="true" head-variant="light"
                    :sticky-header="false" small :items="records" ref="selectableTable" :fields="fields">
                    <template v-slot:cell(action)="data">
                      <div>
                        <a v-if="data.item.actions.view_data" target="_blank" :href="viewRecord(data.item.month)"><i
                            title="View in new tab" class="fas fa-list text-primary">Xem dữ liệu chi tiết</i></a>
                      </div>
                      <div>
                        <a v-if="data.item.actions.update_data" target="_blank" href="#"
                          @click.prevent="uploadRecord(data.item.month)">
                          <i v-if="data.item.total_records == 0" class="fas fa-upload text-success">Tải lên dữ liệu</i>
                          <i v-else class="fas fa-sync text-warning">Cập nhật dữ liệu</i>
                        </a>
                      </div>
                      <div>
                        <a v-if="data.item.actions.view_document" target="_blank"
                          :href="viewDocument(data.item.actions.view_document_id)"><i title="View in new tab"
                            class="fas fa-file text-info">Xem trình duyệt</i></a>
                      </div>
                    </template>
                    <template v-slot:cell(selected)="data">
                      <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected"></b-form-checkbox>
                    </template>
                    <template #cell(status)="data">
                      <span v-if="data.value == 5" class="badge bg-primary">Đã hoàn thành</span>
                      <span v-else-if="data.value == 4" class="badge bg-success">Đã duyệt dữ liệu</span>
                      <span v-else-if="data.value == 3" class="badge bg-danger">Phản hồi</span>
                      <span v-else-if="data.value == 2" class="badge bg-info">Đã gửi duyệt</span>
                      <span v-else-if="data.value == 1" class="badge bg-warning">Đã nhập dữ liệu</span>
                      <span v-else-if="data.value == 0" class="badge bg-secondary">Chưa có dữ liệu</span>
                    </template>
                  </b-table>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>

          <!-- /.card -->
        </div>
      </div>
    </b-overlay>
    <dialog-record-upload :input_filter="this.filterUploadDialog" v-on:onUploadData="onUploadData" :title="dialogTitle">
    </dialog-record-upload>
  </div>
</template>

<script>
import DialogRecordUpload from './DialogRecordUpload.vue';
import RecordFilter from "./RecordFilter.vue";
export default {
  props: {
    title: "",
  },
  components: {
    RecordFilter,
    DialogRecordUpload
  },
  data() {
    return {
      records: [],
      filter: {},
      filterUploadDialog: {},
      dialogTitle: "",
      errors: {},
      status: "",
      fields: [
        {
          key: "year",
          label: this.$t("form.year"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap text-bold",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "month",
          label: this.$t("form.month"),
          sortable: true,
          stickyColumn: true,
          class: "text-nowrap text-bold",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "total_records",
          label: 'Tổng số bản ghi',
          class: "text-nowrap",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "total_users",
          label: 'Tổng số người',
          class: "text-nowrap",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "status",
          label: this.$t("form.status"),
          class: "text-nowrap",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "last_updated",
          label: "Ngày cập nhật cuối",
          class: "text-center text-nowrap",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: "action",
          label: "Thao tác",
          stickyColumn: true,
          class: "text-nowrap",
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      loading: false,
      edit: false,
      token: "",
      page_url_record: "/api/productivity/records",
      selected: [],
      selectAll: false,
    };
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  methods: {
    onUploadData(updatedData) {
      this.records.forEach((record, index) => {
        if (record.id == updatedData.id) {
          this.records[index].status = updatedData.status;
          this.records[index].last_updated = updatedData.last_updated;
          this.records[index].total_records = updatedData.total_records;
          this.records[index].total_users = updatedData.total_users;
          this.records[index].actions = updatedData.actions;
          return;
        }
      });
    },
    updateFilter() {
      this.records = [];
    },
    fetchRecord() {
      this.records = [];

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
          this.records = res.data;
          this.loading = false;
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
    viewRecord(month) {
      let cloneFilter = JSON.parse(JSON.stringify(this.filter));
      cloneFilter.month = month;
      for (let param in cloneFilter) { /* You can get copy by spreading {...query} */
        if (cloneFilter[param] === undefined /* In case of undefined assignment */
          || cloneFilter[param] === null
          || cloneFilter[param] === ""
        ) {
          delete cloneFilter[param];
        }
      }
      return "productivity/recordlist?type=view&" + new URLSearchParams(cloneFilter);
    },
    viewDocument(id) {
      return "/productivity/document?type=view&id=" + id;
    },
    uploadRecord(month) {
      let cloneFilter = JSON.parse(JSON.stringify(this.filter));
      cloneFilter.month = month;
      for (let param in cloneFilter) { /* You can get copy by spreading {...query} */
        if (cloneFilter[param] === undefined /* In case of undefined assignment */
          || cloneFilter[param] === null
          || cloneFilter[param] === ""
        ) {
          delete cloneFilter[param];
        }
      }
      this.filterUploadDialog = cloneFilter;
      this.records.forEach((record, index) => {
        if (record.month == month) {
          this.dialogTitle = record.total_records == 0 ? 'Tải lên dữ liệu' : 'Cập nhật dữ liệu';
        }
      });

      $('#modalRecordUpload').modal('show');
    },
    startFilter() {
      this.fetchRecord();
    },
    selectAllTrigger() {
      this.selected = [];
      if (this.selectAll) {
        for (let i in this.records) {
          this.selected.push(this.records[i].id);
        }
      }
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
