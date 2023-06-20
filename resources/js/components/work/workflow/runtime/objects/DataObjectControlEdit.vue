<template>
  <div :id="control.control_id">
    <b-tooltip v-if="control.is_read_only" v-bind:target="control.control_id" triggers="hover">
      Không được chỉnh sửa
    </b-tooltip>
    <b-tooltip v-else-if="control.require_validation" v-bind:target="control.control_id" triggers="hover">
      Bắt buộc điền
    </b-tooltip>

    <b-form-input v-if="control.type == 1" type="text" v-model="control.value" :disabled="control.is_read_only"
      class="form-control form-control-sm" :state="control.require_validation ? control.iscompleted : null"
      :required="control.require_validation"></b-form-input>
    <b-form-textarea v-if="control.type == 2" :state="control.require_validation ? control.iscompleted : null"
      :required="control.require_validation" v-model="control.value" v-bind:id="control.control_id"
      :name="control.control_id" class="form-control form-control-sm" :disabled="control.is_read_only" cols="30" rows="4" />
    <b-form-input v-if="control.type == 3" type="number" step="1" :disabled="control.is_read_only" :readonly="control.is_read_only" v-model="control.value"
      :state="control.require_validation ? control.iscompleted : null" :required="control.require_validation" >
    </b-form-input>
    <Treeselect v-if="control.type == 5" style="font-size: 15px" placeholder="Chọn.." v-model="control.value" :readonly="control.is_read_only"
      :disabled="control.is_read_only" :disable-branch-nodes="true" :options="options" :required="validateSingleSelect"
      v-bind:id="control.control_id" :state="control.require_validation ? control.iscompleted : null"
      :name="control.control_id" />
    <Treeselect v-if="control.type == 6" style="font-size: 15px" placeholder="Chọn.."  multiple v-model="selecteds" :readonly="control.is_read_only"
      :disabled="control.is_read_only" :disable-branch-nodes="true" :options="options" :required="validateMultiSelect"
      v-bind:id="control.control_id" :state="control.require_validation ? control.iscompleted : null"
      :name="control.control_id" @input="updateValue" @change="updateValue" />
    <b-form-input v-if="control.type == 7" type="date" class="form-control form-control-sm" data-date=""
      :disabled="control.is_read_only" data-date-format="DD/MM/YYYY" v-model="control.value"
      :state="control.require_validation ? control.iscompleted : null" :required="control.require_validation" 
      />
    <Treeselect v-if="control.type == 9" :state="control.require_validation ? control.iscompleted : null"
      :required="control.require_validation" style="font-size: 15px" :disabled="control.is_read_only"
      v-model="control.value" :disable-branch-nodes="true" placeholder="Chọn.." :options="tree_users" />
    <Treeselect v-if="control.type == 16" :multiple="true" :state="control.require_validation ? control.iscompleted : null" 
      :required="validateMultiSelect" style="font-size: 15px" placeholder="Chọn.." :disabled="control.is_read_only"
      v-model="selecteds" :disable-branch-nodes="true" :options="tree_users"  @input="updateValue" @change="updateValue" 
     />
    <b-form-input v-if="control.type == 11" type="url" v-model="control.value"
      :state="control.require_validation ? control.iscompleted : null" :required="control.require_validation"
      :disabled="control.is_read_only" class="form-control form-control-sm" />
    <div v-if="control.type == 12">
      <b-button-group v-if="control.value == null" :state="control.require_validation ? control.iscompleted : null">
        <b-button variant="success" :disabled="control.is_read_only" @click.prevent="setApprove(1)">Đồng ý</b-button>
        <b-button variant="danger" :disabled="control.is_read_only" @click.prevent="setApprove(2)">Từ chối</b-button>
      </b-button-group>
      <h5 v-else>
        <b-badge v-if="control.value == '1'" variant="success"><strong>Đã đồng ý</strong></b-badge>
        <b-badge v-else-if="control.value == '2'" variant="danger"><strong>Đã từ chối</strong></b-badge>
        {{ new Date(control.subvalue).toLocaleString() }}
        <span type="button" class="text-danger" :disabled="control.is_read_only" @click.prevent="setApprove(null)"><i
            class="fas fa-trash"></i></span>
      </h5>
    </div>

    <div v-if="control.type == 10" :state="control.require_validation ? control.iscompleted : null">
      <button type="button" title="chọn file" class="btn btn-xs btn-outline" :disabled="control.is_read_only"
        v-on:click="handleClickInputFile()">
        <i title="chọn file" class="fas fa-paperclip"> </i>
      </button>

      <input class="visuallyhidden" type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
        style="display: block" ref="fileUpload" :required="validateFile" @change="emitEvent_fileupload($event)"
        multiple />

      <div class="d-flex justify-content-between">
        <ul class="list-unstyled">
          <li v-for="(file, index) in attachment_file" v-bind:key="index" class="itemfile">
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id, file.name)">
                {{ file.name }}
              </button>
              <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile(file, index)">
                <i class="far fa-times-circle"></i>
              </button>
              <button type="button" v-if="file.id" class="btn btn-default btn-xs"
                @click.prevent="downloadFile(file.id, file.name)">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <ckeditor v-if="control.type == 13" v-model="control.value" :disabled="control.is_read_only"
      :state="control.require_validation ? control.iscompleted : null" :required="control.require_validation"
      v-bind:config="editorConfig" v-bind:id="control.control_id" :name="control.id"
     ></ckeditor>
    <b-form-input v-if="control.type == 14" type="time" class="form-control form-control-sm" v-model="control.value"
      :disabled="control.is_read_only" :state="control.require_validation ? control.iscompleted : null"
      :required="control.require_validation"
      />
  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import BadgePlugin from "bootstrap-vue";
export default {
  components: {
    Treeselect,
    BadgePlugin,
  },
  props: {
    control: Object,
    users: Array,
    tree_users: Array,
  },
  data() {
    return {
      selecteds: this.control.value_list,
      locale_format: "de-DE",
      attachment_file: this.control.attachment_file,
      attachment_file_removed: [],
      editorConfig: {
        toolbarGroups: [
          // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
          { name: "clipboard", groups: ["clipboard", "undo"] },
          { name: "editing", groups: ["find", "selection", "spellchecker", "editing"] },
          // { name: 'forms', groups: [ 'forms' ] },
          // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
          {
            name: "paragraph",
            groups: ["list", "indent", "blocks", "align", "bidi", "paragraph"],
          },
          // { name: 'links', groups: [ 'links' ] },
          { name: "insert", groups: ["insert"] },
          // { name: 'styles', groups: [ 'styles' ] },
          { name: "colors", groups: ["colors"] },
          // { name: 'tools', groups: [ 'tools' ] },
          // { name: 'others', groups: [ 'others' ] },
          // { name: 'about', groups: [ 'about' ] }
        ],
        removeButtons:
          "NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image",
      },
    };
  },
  methods: {
    setApprove(value) {
      if (value != null) {
        this.control.value = value;
        this.control.subvalue = new Date();
      }
      else {
        this.control.value = null;
        this.control.subvalue = null;
      }

    },
    deleteFile(item, index) {
      if (confirm(this.$t("form.confirm_delete") + "?")) {
        if (this.attachment_file_removed == null) {
          this.attachment_file_removed = [];
        }
        this.attachment_file_removed.push({ ...item });
        this.attachment_file.splice(index, 1);
        this.updateValue();
      }
    },
    getusernamebyid($id) {
      let user = this.users.find((x) => x.id == $id);
      return user.name;
    },
    formatNum(str) {
      return Number(str).toLocaleString("de-DE");
    },
    openLink(url) {
      window.open("https://" + url, "_blank").focus();
    },
    emitEvent_fileupload(event) {
      for (let index = 0; index < event.target.files.length; index++) {
        const file = event.target.files[index];

        //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = () => {
          const docs = {
            name: file.name,
            size: file.size,
            ext: file.type.split("/").pop(),
            lastModifiedDate: file.lastModifiedDate,
            base64: reader.result,
          };

          this.attachment_file.push({ ...docs });

          // console.log( this.control.attachment_file);
          this.updateValue();
        };
      }
      //reset file control
      event.target.value = "";
    },
    updateValue() {
      switch (this.control.type) {
        case "6":
        case "16":
          this.control.value_list = [...this.selecteds];
          break;
        case "10":
          this.control.attachment_file = [...this.attachment_file];
          this.control.attachment_file_removed = [...this.attachment_file_removed];
          break;
        default:
          break;
      }

      //this.$emit('updateValueControl', value, type, index);
    },
    handleClickInputFile() {
      this.$refs.fileUpload.click();
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
  },
  computed: {
    validateSingleSelect() {
      if (this.control.require_validation) {
        if (this.control.value > 0) {
          return false;
        }
        return true;
      }

      return false;
    },
    validateMultiSelect() {
      if (this.control.require_validation) {
        if (this.selecteds && this.selecteds.length > 0) {
          return false;
        }
        return true;
      }

      return false;
    },
    validateFile() {
      if (this.control.require_validation) {
        if (this.attachment_file.length > 0) {
          return false;
        }
        return true;
      }

      return false;
    },

    options() {
      var list = [];
      this.control.items.forEach((element, index) => {
        let item = [];
        item.id = element.id;
        if (this.control.type == 5) {
          item.label = (index + 1) + '. ' + element.value;
        }
        else {
          item.label = element.value;
        }

        list.push(item);
      });
      return list;
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
