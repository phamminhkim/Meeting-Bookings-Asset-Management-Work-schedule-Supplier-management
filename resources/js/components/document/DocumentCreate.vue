<template>
  <div>
    <form @submit.prevent="AddDocument" @keydown.enter.prevent="clearError">
      <div class="content-header">
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
              <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <h5 class="m-0 text-dark">
                    <i class="nav-icon fas fa-file-contract"></i>
                    <a href="documents">{{ $t(pre_title) }}</a>
                  </h5>
                </li>

                <li class="breadcrumb-item active">
                  <span v-if="edit">{{ $t("form.edit") }}</span>
                  <span v-else>{{ $t("form.create") }}</span>
                </li>
              </ol>
            </div>
            <div class="col-md-6">
              <div class="float-sm-right" style="margin-top: -5px">
                <a href="/documents" class="btn btn-default">{{ $t("form.cancel") }}</a>

                <button
                  type="submit"
                  :disabled="loading"
                  name="0"
                  class="btn btn-primary"
                >
                  {{ $t("form.save") }}
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  name="1"
                  class="btn btn-primary"
                >
                  {{ $t("form.saveandsend") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div v-if="hasAnyError > 0">
            <div class="alert alert-warning">
              <button type="button" class="close" @click.prevent="clearAllError()">
                <i class="ace-icon fa fa-times"></i>
              </button>
              <ul>
                <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label
                      for=""
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.document_num") }}</label
                    >
                    <div class="col-md-8">
                      <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="document.serial_num"
                        readonly
                      />
                    </div>
                  </div>
                  <div class="form-group row" v-if="showControl('docbrowser_type_id')">
                    <label
                      for="docbrowser_type_id"
                      class="col-form-label-sm col-sm-2 text-md-right"
                      >{{ $t("form.docbrowsertype")
                      }}<small v-if="showValidate('docbrowser_type_id')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-8">
                      <select
                        name="docbrowser_type_id"
                        id="docbrowser_type_id"
                        v-model="document.docbrowser_type_id"
                        v-bind:class="hasError('docbrowser_type_id') ? 'is-invalid' : ''"
                        :required="showValidate('docbrowser_type_id')"
                        @click="clearError($event)"
                        @change="clearError($event)"
                        class="form-control form-control-sm"
                      >
                        <option key=""></option>
                        <option
                          v-for="docbrowser_type in docbrowser_type_active"
                          v-bind:key="docbrowser_type.id"
                          v-bind:value="docbrowser_type.id"
                        >
                          {{ docbrowser_type.name }}
                        </option>
                      </select>
                      <span
                        v-if="hasError('docbrowser_type_id')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("docbrowser_type_id") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('title')">
                    <label
                      for="title"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.about")
                      }}<small v-if="showValidate('title')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-8">
                      <input
                        type="text"
                        v-model="document.title"
                        class="form-control form-control-sm"
                        v-bind:class="hasError('title') ? 'is-invalid' : ''"
                        name="title"
                        id="title"
                        :required="showValidate('title')"
                        maxlength="150"
                      />
                      <span
                        v-if="hasError('title')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("title") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('budget_num')">
                    <label
                      for="budget_num"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.budget_code")
                      }}<small v-if="showValidate('budget_num')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-3">
                      <input
                        type="text"
                        v-model="document.budget_num"
                        class="form-control form-control-sm"
                        v-bind:class="hasError('budget_num') ? 'is-invalid' : ''"
                        name="budget_num"
                        id="budget_num"
                        :required="showValidate('budget_num')"
                      />
                      <span
                        v-if="hasError('budget_num')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("budget_num") }}</strong>
                      </span>
                    </div>
                  </div>

                  <div class="form-group row" v-if="showControl('amount')">
                    <label
                      for="amount"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                    >
                      <span v-html="showLabel('amount', $t('form.amount'))"></span>
                      <small v-if="showValidate('amount')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-3">
                      <!-- <vue-numeric    name="amount"   id="amount"  separator="," required    v-model="document.amount"   v-bind:class="hasError('amount')?' form-control form-control-sm is-invalid':'form-control form-control-sm'"  ></vue-numeric>              -->
                      <vue-numeric
                        style="text-align: right"
                        v-bind:precision="2"
                        :separator="separator"
                        :required="showValidate('amount')"
                        v-model="document.amount"
                        @click="clearError($event)"
                        @change="clearError($event)"
                        v-bind:class="
                          hasError('amount')
                            ? ' form-control form-control-sm is-invalid'
                            : 'form-control form-control-sm'
                        "
                      ></vue-numeric>
                      <span
                        v-if="hasError('amount')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("amount") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="showControl('amount')">
                    <label
                      for=""
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.currency")
                      }}<small v-if="showValidate('amount')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-2">
                      <select
                        class="form-control form-control-sm"
                        name="currency"
                        id="currency"
                        v-model="document.currency"
                        v-bind:class="hasError('currency') ? 'is-invalid' : ''"
                        :required="showValidate('amount')"
                      >
                        <option v-for="cur in currencies" :key="cur.id" :value="cur.id">
                          {{ cur.id }}
                        </option>
                      </select>
                      <span
                        v-if="hasError('currency')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("currency") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('budget_type')">
                    <label
                      for=""
                      class="col-form-label-sm col-sm-2 col-form-label"
                    ></label>
                    <div class="col-sm-8">
                      <b-form-radio-group
                        v-model="document.budget_type"
                        :options="options"
                        class="mb-3"
                        size="sm"
                        value-field="item"
                        text-field="name"
                        disabled-field="notEnabled"
                      ></b-form-radio-group>
                    </div>
                  </div>
                  <div class="form-group row" v-if="showControl('doc_type_id')">
                    <label
                      for="doc_type_id"
                      class="col-form-label-sm col-sm-2 text-md-right"
                      >{{ $t("form.doc_type")
                      }}<small v-if="showValidate('doc_type_id')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-3">
                      <select
                        name="doc_type_id"
                        id="doc_type_id"
                        v-model="document.doc_type_id"
                        v-bind:class="hasError('doc_type_id') ? 'is-invalid' : ''"
                        @click="clearError($event)"
                        @change="clearError($event)"
                        class="form-control form-control-sm"
                      >
                        <option key=""></option>
                        <option
                          v-for="doc_type in doc_types"
                          v-bind:key="doc_type.id"
                          v-bind:value="doc_type.id"
                        >
                          {{ doc_type.name }}
                        </option>
                      </select>
                      <span
                        v-if="hasError('doc_type_id')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("doc_type_id") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="content"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.content")
                      }}<small v-if="showValidate('content')" class="text-red"
                        >( * )</small
                      ></label
                    >
                    <div class="col-sm-8">
                      <ckeditor
                        v-model="document.content"
                        :required="showValidate('content')"
                        v-bind:config="editorConfig"
                        v-bind:class="editorClass"
                        name="content"
                        id="content"
                      ></ckeditor>

                      <span
                        v-if="hasError('content')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("content") }}</strong>
                      </span>
                    </div>
                  </div>

                  <div class="form-group row" v-if="showControl('filesign')">
                    <label
                      for="content"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.sign_file") }}(PDF)</label
                    >
                    <div class="col-sm-8">
                      <button
                        type="button"
                        title="chọn file"
                        class="btn btn-xs btn-outline"
                        v-on:click="handleClickInputFileApprove()"
                      >
                        <i title="chọn file" class="fas fa-paperclip"></i>
                      </button>
                      <div class="d-flex justify-content-between">
                        <ul class="list-unstyled">
                          <li
                            v-for="(filesign, index) in document.filesigns"
                            v-bind:key="index"
                            class="itemfile"
                            title=""
                          >
                            <div class="btn-group">
                              <button type="button" class="btn btn-default btn-xs">
                                {{ filesign.attachment_file[0].name }}
                              </button>
                              <button
                                type="button"
                                class="btn btn-default btn-xs text-green"
                                @click="showPdf(index)"
                                title="Xác định vị trí"
                              >
                                <i class="fas fa-file-signature ml-1 mr-1"></i>
                              </button>
                              <button
                                type="button"
                                class="btn btn-default btn-xs text-red"
                                title="Delete"
                                @click.prevent="deleteFileApprove(filesign, index)"
                              >
                                <i class="far fa-times-circle"></i>
                              </button>
                              <button
                                type="button"
                                v-if="filesign.id"
                                class="btn btn-default btn-xs"
                                @click.prevent="
                                  downloadFile(
                                    filesign.attachment_file[0].id,
                                    filesign.attachment_file[0].name
                                  )
                                "
                              >
                                <i class="fas fa-download"></i>
                              </button>
                            </div>
                          </li>
                        </ul>
                        <input
                          type="file"
                          accept=".pdf"
                          @input="emitEvent_Approve($event)"
                          @change="emitEvent_Approve($event)"
                          id="ThemFileApprove"
                          style="display: none"
                          ref="fileApprove"
                        />
                      </div>
                      <span
                        v-if="hasError('content')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("content") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="content"
                      class="col-form-label-sm col-sm-2 col-form-label text-md-right"
                      >{{ $t("form.attached_file") }}<small class="text-red"></small
                    ></label>
                    <div class="col-sm-8">
                      <button
                        type="button"
                        title="chọn file"
                        class="btn btn-xs btn-outline"
                        v-on:click="handleClickInputFile()"
                      >
                        <i title="chọn file" class="fas fa-paperclip"></i>
                      </button>
                      <div class="d-flex justify-content-between">
                        <ul class="list-unstyled">
                          <li
                            v-for="(file, index) in document.attachment_file"
                            v-bind:key="index"
                            class="itemfile"
                          >
                            <div class="btn-group">
                              <button type="button" class="btn btn-default btn-xs">
                                {{ file.name }}
                              </button>
                              <button
                                type="button"
                                class="btn btn-default btn-xs text-red"
                                @click.prevent="deleteFile(file, index)"
                              >
                                <i class="far fa-times-circle"></i>
                              </button>
                              <button
                                type="button"
                                v-if="file.id"
                                class="btn btn-default btn-xs"
                                @click.prevent="downloadFile(file.id, file.name)"
                              >
                                <i class="fas fa-download"></i>
                              </button>
                            </div>
                          </li>
                        </ul>
                        <input
                          type="file"
                          accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.msg,.eml"
                          @input="emitEvent_voucher($event)"
                          @change="emitEvent_voucher($event)"
                          id="ThemFileVoucher"
                          style="display: none"
                          ref="fileVoucher"
                          multiple
                        />
                      </div>
                      <span
                        v-if="hasError('content')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("content") }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="group_id" class="col-form-label-sm col-sm-2 text-md-right"
                      >{{ $t("form.group") }}<small class="text-red">( * )</small></label
                    >
                    <div class="col-sm-3">
                      <select
                        name="group_id"
                        id="group_id"
                        v-model="document.group_id"
                        v-bind:class="hasError('group_id') ? 'is-invalid' : ''"
                        @click="clearError($event)"
                        @change="clearError($event)"
                        class="form-control form-control-sm"
                      >
                        <option
                          v-for="group in group_filter"
                          v-bind:key="group.id"
                          v-bind:value="group.id"
                        >
                          {{ group.company_id }}-{{ group.name }}
                        </option>
                      </select>

                      <span
                        v-if="hasError('group_id')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("group_id") }}</strong>
                      </span>
                    </div>
                    <label
                      for="group_id"
                      class="col-form-label-sm col-sm-2 text-md-right"
                      >{{ $t("form.viewers") }}</label
                    >
                    <div class="col-sm-3">
                      <!-- <select name="group_id" id="group_id"
                        v-model="document.group_id"
                          v-bind:class="hasError('group_id')?'is-invalid':''"

                          @click="clearError($event)"
                          @change="clearError($event)"
                        class="form-control form-control-sm">
                        <option v-for="group in group_filter" v-bind:key="group.id"   v-bind:value="group.id">{{group.name}}</option>
                      </select> -->
                      <treeselect
                        placeholder=""
                        :disable-branch-nodes="true"
                        :multiple="true"
                        v-model="document.shared_groups"
                        :options="tree_groups"
                      />

                      <span
                        v-if="hasError('group_id')"
                        class="invalid-feedback"
                        role="alert"
                      >
                        <strong>{{ getError("group_id") }}</strong>
                      </span>
                    </div>
                  </div>

                  <approve-add-user
                    v-on:updateUser="updateUser"
                    :team="document.team"
                    :user_id="user_id"
                    eventname="updateUser"
                    :layout="layout"
                    :tree_user="tree_users"
                    :list_user="users"
                    v-if="showControl('add_user')"
                  ></approve-add-user>
                </div>
              </div>
            </div>
            <loading :loading="loading"></loading>
          </div>
        </div>
      </div>
    </form>
    <pdf-view
      v-on:updateDoc="updateDoc"
      :manySign="false"
      :fileInfo="display_file_obj"
      eventname="updateDoc"
    ></pdf-view>
  </div>
</template>

<script>
import PdfView from "../shared/PdfView.vue";
import ApproveAddUser from "../approve/ApproveAddUser.vue";
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
  components: { ApproveAddUser, Treeselect, PdfView },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.getUser();
    this.getUserTree();
    this.fetGroup_Tree();
    this.getDocType();
    this.getDocbrowserType();
    this.fetCurrency();
    this.getDocument();
    this.defaultValue();
  },
  watch: {},
  data() {
    return {
      tree_groups: [],

      value: [],
      document: {
        title: "",
        content: "",
        amount: "0",
        budget_type: "1",
        currency: "VND",
        serial_num: "",
        budget_num: "",
        group_id: "",
        payment_type_id: "0",
        attachment_file: [],
        attachment_file_removed: [],

        document_type_id: this.doctype_id,
        team_users: [],
        team: {},
        shareds: [],
        shared_groups: [],
        filesigns: [],
        filesigns_del: [],
      },
      filesign: {
        signed: "",
        attachment_file: [],
        attachment_file_removed: [],
      },

      display_file_index: -1,
      display_file_obj: {},
      separator: ".",

      options: [
        { item: "1", name: this.$t("Trong ngân sách") },
        { item: "0", name: this.$t("Ngoài / Vượt ngân sách") },
      ],
      users: [],
      tree_users: [],
      doc_types: [],
      docbrowser_types: [],
      currencies: [],
      edit: false,
      errors: {},
      loading: false,

      editorClass: "col-lg-12",
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
      token: "",
      tabIndex: 0,
      page_url_documents: "api/documents",
      page_url_users: "api/user/all",
      page_url_doc_types: "/api/category/doctypes",
      page_url_docbrowser_types: "/api/category/docbrowsertypes",
      page_url_group: "/api/category/groups",
      page_url_currency: "/api/category/currencies",
    };
  },
  props: {
    id: String,
    user_id: String,
    doctype: String,
    doctype_id: String,
    pre_title: String,
    layout: Object,
  },
  methods: {
    fetCurrency() {
      // const this.token = "Bearer " + window.Laravel.access_this.token;
      var page_url = this.page_url_currency;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then((res) => res.json())
        .then((res) => {
          //console.log("Xin chao");
          this.currencies = res.data;
        })
        .catch((err) => console.log(err));
    },
    showPdf(index) {
      this.display_file_index = index;
      var filesign = this.document.filesigns[index];
      //nếu file đã lưu thì đọc file từ server, còn nếu chưa lưu thì đọc từ local

      if (filesign.attachment_file[0].id) {
        var page_url = "api/payment/downloadFile/" + filesign.attachment_file[0].id;
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
            var file = new Blob([res], { type: "octet/stream" });
            let reader = new FileReader();
            reader.readAsDataURL(file);
            //   reader.readAsArrayBuffer(file);

            reader.onload = () => {
              //console.log(reader.result);
              filesign.attachment_file[0].base64 = reader.result;
              this.document.filesigns[index] = filesign;
              this.display_file_obj = {
                ...this.document.filesigns[index].attachment_file[0],
              };
            };
          })
          .catch((err) => console.log(err));
      } else {
        this.display_file_obj = { ...this.document.filesigns[index].attachment_file[0] };
      }

      this.$bvModal.show("modal-xl");
    },
    updateDoc(docInfo) {
      // this.document.docInfo = {...docInfo};
      this.document.filesigns[this.display_file_index].attachment_file[0] = {
        ...docInfo,
      };
      this.display_file_index = -1;
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

    //sự kiện add user
    updateUser(data) {
      this.document.team_users = [...data];
    },
    //Sự kiện chọn file voucher
    emitEvent_voucher(event) {
      for (let index = 0; index < event.target.files.length; index++) {
        const file = event.target.files[index];
        //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = () => {
          // console.log(event.target.files[0]);
          const docs = {
            name: file.name,
            size: file.size,
            ext: file.type.split("/").pop(),
            lastModifiedDate: file.lastModifiedDate,
            base64: reader.result,
          };
          //console.log(docs);
          this.document.attachment_file.push({ ...docs });
        };
      }
      //reset file control
      event.target.value = "";
    },
    emitEvent_Approve(event) {
      for (let index = 0; index < event.target.files.length; index++) {
        const file = event.target.files[index];
        //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
        let reader = new FileReader();
        reader.readAsDataURL(file);
        //   reader.readAsArrayBuffer(file);

        reader.onload = () => {
          // console.log(event.target.files[0]);
          const docs = {
            name: file.name,
            size: file.size,
            ext: file.type.split("/").pop(),
            lastModifiedDate: file.lastModifiedDate,
            base64: reader.result,
          };

          this.filesign.signed = "";
          this.filesign.attachment_file = [];
          this.filesign.attachment_file_removed = [];
          this.filesign.attachment_file.push({ ...docs });
          this.document.filesigns.push({ ...this.filesign });
        };
      }
      //reset file control
      event.target.value = "";
    },
    handleClickInputFile() {
      this.$refs.fileVoucher.click();
    },
    handleClickInputFileApprove() {
      this.$refs.fileApprove.click();
    },
    deleteFile(item, index) {
      if (confirm(this.$t("form.confirm_delete") + "?")) {
        //  console.log("index="+index);

        this.document.attachment_file_removed.push({ ...item });
        this.document.attachment_file.splice(index, 1);
      }
    },
    deleteFileApprove(item, index) {
      if (confirm(this.$t("form.confirm_delete") + "?")) {
        console.log(this.document.filesigns);
        this.document.filesigns_del.push({ ...item });
        this.document.filesigns.splice(index, 1);
      }
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
    getDocument() {
      if (this.id != "") {
        this.loading = true;

        var page_url = this.page_url_documents + "/" + this.id + "/edit";
        fetch(page_url, { headers: { Authorization: this.token } })
          .then((res) => res.json())
          .then((res) => {
            if (res.statuscode == "403") {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            if (res.data.success == "1") {
              this.document = res.data;
              this.document.shared_groups = [];
              this.document.shareds.forEach((shared) => {
                if (shared.type == 0 && shared.object_id) {
                  this.document.shared_groups.push(shared.object_id);
                }
              });
              this.document.attachment_file_removed = [];
              this.document.filesigns_del = [];
              this.edit = true;
              this.loading = false;
            }
          })
          .catch((err) => {
            this.loading = false;
            console.log(err);
          });
      }
    },
    getDocType() {
      var page_url = this.page_url_doc_types;
      //console.log(page_url);
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.doc_types = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getDocbrowserType() {
      var page_url = this.page_url_docbrowser_types;
      //console.log(page_url);
      fetch(page_url, {
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          this.docbrowser_types = data.data;
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
    getUser() {
      var page_url = this.page_url_users;
      console.log(page_url);
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
    AddDocument($test) {
      this.document.saveandsend = $test.submitter.name;

      this.loading = true;
      var page_url = this.page_url_documents;
      if (this.edit == false) {
        fetch(page_url, {
          method: "post",
          body: JSON.stringify(this.document),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.statuscode == "403") {
              window.location.href = "/errorpage?statuscode=" + data.statuscode;
            }
            if (data.statuscode == "422") {
              window.location.href = "/errorpage?statuscode=" + data.statuscode;
            }
            this.loading = false;
            if (!data.data.errors) {
              // this.resetPayementVoucher();
              //  this.serviceCategorys.push(data.data);
              if (this.document.saveandsend) {
                toastr.success(this.$t("form.saveandsend_success"), "");
              } else {
                toastr.success(this.$t("form.save_success"), "");
              }

              window.location.href = "/documents?type=view&id=" + data.data.id;
              //$("#AddServiceCategory").modal("hide");
            } else {
              //console.log(this.errors);

              this.errors = data.data.errors;
            }
          })
          .catch((err) => {
            this.loading = false;
          });
      } else {
        //update

        fetch(page_url + "/" + this.document.id, {
          method: "PUT",
          body: JSON.stringify(this.document),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((data) => {
            //  console.log(data);
            if (!data.data.errors) {
              if (this.document.saveandsend) {
                toastr.success(this.$t("form.updateandsend_success"), "");
              } else {
                toastr.success(this.$t("form.update_success"), "");
              }
              window.location.href = "/documents?type=view&id=" + data.data.id;
            } else {
              this.errors = data.data.errors;
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
          });
      }
    },
    //KHởi tạo giá trị mặc định
    defaultValue() {
      // let fieldName = "budget_type";
      //  //console.log( "this.layout[fieldName]['has_default_value']");
      //   if(fieldName in this.layout){
      //     // console.log( this.layout[fieldName]['default_value']);
      //      this.budget_type =  this.layout[fieldName]['has_default_value'];
      //   }
    },
    showLabel(fieldName, value) {
      if (fieldName in this.layout) {
        if (this.layout[fieldName]["has_custom_label"]) {
          return this.layout[fieldName]["custom_label_text"];
        }
      }
      return value;
    },
    showValidate(fieldName) {
      if (fieldName in this.layout) {
        return this.layout[fieldName]["require_validation"];
      }
      return false;
    },
    showControl(fieldName) {
      if (fieldName in this.layout) {
        return this.layout[fieldName]["isVisible"];
      }
      return false;
    },
    clearAllError() {
      this.errors = {};
    },
    hasError(fieldName) {
      return fieldName in this.errors;
    },
    getError(fieldName) {
      //console.log(fieldName+"="+ this.errors[fieldName][0]);
      return this.errors[fieldName][0];
    },
    clearError(event) {
      Vue.delete(this.errors, event.target.name);
      //console.log(event.target.name);
    },
  },
  computed: {
    docbrowser_type_active(){
      
       let list = [];
      this.docbrowser_types.forEach((doc) => {
        //console.log(document_type.document_group);
        if (doc.active == 1) {
          list.push(doc);
        }
      });
      return list;
    },
    group_filter() {
      var list = [];
      let user = this.users.find((x) => x.id == this.user_id);
      if (user) {
        list = user.groups;
        // this.payrequest.group_id = user.groups[0]['id'];
      }
      return list;
    },
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
};
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 5px !important;
}
</style>
