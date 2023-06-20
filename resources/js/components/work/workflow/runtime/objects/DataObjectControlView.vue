<template>
  <div :id="control.control_id">
    <!-- <span v-if="control.type == 1" class="col-form-label-sm col-sm-3">{{ control.value }}:</span> -->
    <label v-if="control.type == 1" class="col-form-label-sm">
      {{ control.value ? control.value : "Không có nội dung" }}
    </label>
    <label v-else-if="control.type == 2 || control.type == 13" class="col-form-label-sm"
      v-html="control.value ? control.value : 'Không có nội dung'"></label>
    <label v-else-if="control.type == 3" class="col-form-label-sm">
      {{ control.value ? formatNum(control.value) : "Không có nội dung" }}
    </label>
    <b-badge v-else-if="control.type == 5" pill variant="primary"
      v-html="control.value ? this.getOptionValue(control.value) : ''" />
    <span v-else-if="control.type == 6">
      <span v-if="control.items.length > 0">
        <span v-for="(item, index) in control.items" v-bind:key="index">
          <b-badge style="margin-right: 5px" v-if="item.select == 'X'" pill variant="warning">{{ item.value }}</b-badge>
        </span>
      </span>
      <span v-else>Không có nội dung</span>
    </span>
    <b-badge v-else-if="control.type == 7" pill variant="danger"
      v-html="control.value ? control.value : 'Không có nội dung'" />
    <b-badge v-else-if="control.type == 14" pill variant="warning"
      v-html="control.value ? control.value : 'Không có nội dung'" />
    <b-badge v-else-if="control.type == 9" pill variant="info">{{
        control.value ? this.getUsernameById(control.value) : 'Không có nội dung'
    }}</b-badge>
    <span v-else-if="control.type == 16">
      <span v-if="control.items.length > 0">
        <span v-for="(item, index) in control.items" v-bind:key="index">
          <b-badge style="margin-right: 5px" v-if="item.select == 'X'" pill variant="info">{{
              item.value ? getUsernameById(item.value) : '{' + item.value + '}'
          }}</b-badge>
        </span>
      </span>
      <span v-else>Không có nội dung</span>
    </span>
    <div v-else-if="control.type == 10" class="d-flex justify-content-between">
      <ul v-if="control.attachment_file.length > 0" class="list-unstyled">
        <li v-for="(file, index) in control.attachment_file" v-bind:key="index" class="itemfile">
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id, file.name)">
              {{ file.name }}
            </button>

            <button type="button" v-if="file.id" class="btn btn-default btn-xs"
              @click.prevent="downloadFile(file.id, file.name)">
              <i class="fas fa-download"></i>
            </button>
          </div>
        </li>
      </ul>
      <span v-else>Không có file đính kèm nào</span>
    </div>
    <label v-else-if="control.type == 11" @click="openLink(control.value)"
      :style="control.value ? 'color: blue; cursor: pointer' : ''">{{
          control.value ? control.value : "Không có nội dung"
      }}
    </label>
    <div v-if="control.type == 12">
      <b-button-group v-if="control.value == null">
        <label>Chưa xác nhận</label>
      </b-button-group>
      <h5 v-else-if="control.value == '1'">
        <b-badge variant="success"><strong>Đã đồng ý</strong></b-badge>
      </h5>
      <h5 v-else-if="control.value == '0'">
        <b-badge variant="danger"><strong>Đã từ chối</strong></b-badge>
      </h5>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    control: Object,
    users: Array,
    tree_users: Array,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
  },
  methods: {
    getOptionValue(id) {
      var returnValue = "";
      this.control.items.forEach(element => {
        if (element.refer_id == id || element.id == id) {
          returnValue = element.value;
        }
      });
      return returnValue;
    },
    getUsernameById(id) {
      var user = this.users.find((x) => x.id == id);
      return user == null ? "" : user.name;
    },
    formatNum(str) {
      return Number(str).toLocaleString("de-DE");
    },
    openLink(url) {
      if (url) {
        window.open("https://" + url, "_blank").focus();
      }
    },
    downloadFile(idfile, filename) {
      var page_url = 'api/payment/downloadFile/' + idfile;
      toastr.info("Đang download....", "");
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        },
        responseType: 'arraybuffer',
        method: 'post'
      })
        .then(res => res.blob())
        .then(res => {

          var newBlob = new Blob([res], { type: "octet/stream" });
          if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            toastr.info("Download thành công", "");
            return;
          }
          // For other browsers:
          // Create a link pointing to the ObjectURL containing the blob.
          const data = URL.createObjectURL(newBlob);
          var link = document.createElement('a');
          link.href = data;
          link.download = filename;
          link.click();
          toastr.info("Download thành công", "");
          setTimeout(function () {
            // For Firefox it is necessary to delay revoking the ObjectURL
            URL.revokeObjectURL(data)
          }, 100);
        }).catch(err => console.log(err));

    },
  },
};
</script>
<style lang="scss" scoped>
//Class Ẩn nút chọn file trong <input type="file" >
.visuallyhidden {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

.form-group {
  margin-bottom: 1px !important;
}
</style>
