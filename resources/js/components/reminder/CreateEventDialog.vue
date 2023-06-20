<template>
  <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="" @submit.prevent="AddReminder()">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createEventModal"><i class="fas fa-bell"></i> {{$t('form.reminder')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>
          <div class="modal-body">
            <div class="">
              <div class="form-group">
                <label for=""> {{$t('form.type')}}</label>
                <select name="" id="" v-model="reminder.type_id" class="form-control">
                  <option value="1">{{$t('form.reminder')}}</option>
                  <option value="0">{{$t('form.note')}}</option>
                </select>
              </div>
              <div class="form-group">
                <label for="date_due">{{$t('form.date')}}</label> <small class="text-red">( * )</small>
                <b-form-datepicker :required="true" v-bind:class="hasError('date_due')?'is-invalid':''"
                  @click="clearError($event)" aria-required="true" id="example-datepicker" v-model="reminder.date_due"
                  :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }">

                </b-form-datepicker>
                <span v-if="hasError('date_due')" class="invalid-feedback" role="alert">
                  <strong>{{getError('date_due')}}</strong>

                </span>
              </div>
              <div class="form-group">
                <label for=""> {{$t('form.content')}}</label> <small class="text-red">( * )</small>
                <!-- <input type="text" required v-model="reminder.content" class="form-control" maxlength="255"> -->

                <textarea name="" id="" required v-model="reminder.content" class="form-control" cols="30"
                  rows="4"></textarea>
              </div>
              

              <div class="form-group">
                <label for=""> {{$t('form.attached_file')}}</label>
                <button type="button" title="chọn file" class="btn btn-xs btn-outline "
                  v-on:click="handleClickInputFile()">
                  <i title="chọn file" class="fas fa-paperclip"></i></button>
                <div class="d-flex justify-content-between">

                  <ul class="list-unstyled">
                    <li v-for="(file,index_file) in reminder.attachment_file" v-bind:key="index_file" class="itemfile">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                        <button type="button" class="btn btn-default btn-xs text-red"
                          @click.prevent="deleteFile(file,index_file)"><i class="far fa-times-circle "></i></button>
                        <button type="button" v-if="file.id" class="btn btn-default btn-xs"
                          @click.prevent="downloadFile(file.id,file.name)"><i class="fas fa-download"></i></button>
                      </div>
                    </li>
                  </ul>
                  <input type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                    @input="emitEvent_attached($event)" @change="emitEvent_attached($event)" id="ThemFile"
                    style="display:none" ref="file" multiple>

                </div>
              </div>
              <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="checkbox" v-model="reminder.print" checked name="type" value="0">
                <label class="form-check-label" for=""><strong>{{$t('form.print')}}</strong> </label>
              </div>

              <!-- <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio"  v-model="reminder.type_id" checked name="type"  value="1">
                 <label class="form-check-label" for="" ><strong>{{$t('form.one_time')}}</strong> </label>
              </div>
              <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" v-model="reminder.type_id" name="type"   value="2">
                 <label class="form-check-label" ><strong>{{$t('form.periodical')}}</strong> </label>
              </div>  -->
              <!-- <div class="card"  v-if="reminder.type_id == 2">


                  <div class="form-group row  mt-2">
                        <label for="duration_value" class="col-3 ml-2  mt-2" >{{$t('form.period')}}</label>
                        <input type="number" id="duration_value"  name="duration_value" required v-model="reminder.duration_value" class="form-control col-3 ml-2  mt-2" >
                        <select name="" id=""  v-model="reminder.duration" required class="form-control col-3 ml-2  mt-2">
                          <option value="1">{{$t('form.day')}}</option>
                          <option value="2">{{$t('form.week')}}</option>
                          <option value="3">{{$t('form.month')}}</option>
                          <option value="4">{{$t('form.year')}}</option>
                        </select>
                    </div>
                   <div class="form-group row mt-2">
                        <label for="start_date" class="col-3 ml-2  mt-2 mb-2" >{{$t('form.start_date')}}</label>

                        <b-form-datepicker :required="true"  class="form-control col-6 ml-2  mt-2 mb-2"  id="start_date" name="start_date" v-model="reminder.start_date"  :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" ></b-form-datepicker>
                    </div>

                </div>



               <div class="form-group row">
                <label class="col-4 mt-2" for="reminded_before_day">{{$t('form.pre_reminder')}}</label>
                 <select name="reminded_before_day" id="reminded_before_day"  v-model="reminder.reminded_before_day" class="form-control col-6 ">
                           <option value="0">{{$t('form.no_reminder')}}</option>
                          <option value="1">1 {{$t('form.day')}}</option>
                          <option value="7">1 {{$t('form.week')}}</option>
                          <option value="31">1 {{$t('form.month')}}</option>

                        </select>

              </div> -->

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{$t('form.close')}}</button>
            <button type="submit" class="btn btn-primary">{{$t('form.save')}}</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</template>

<script>
export default {
  watch: {
    object_id() {
      this.reminder.object_id = this.object_id[0];
    },
    id() {
      this.edit = false;

      this.fetReminder(this.id);
    },
  },
  props: {
    title: "form.create_event",
    object_id: "",
    module: "",
    id: "",


    //Mỗi đối tượng muốn sử dụng reminder thì phải cài đặt API để nó gọi vào
    //page_url_reminder:"api/reminders",
  },

  data() {
    return {
      reminder: {
        content: "",
        date_due: "",
        duration: "",
        start_date: "",
        duration_value: "",
        reminded_before_day: "",
        object_id: this.object_id[0],
        module: this.module,
        type_id: 1,
        attachment_file: [],
        attachment_file_removed: [],
        print: 0
      },

      errors: {},
      loading: false,
      edit: false,
      token: "",
      page_url_reminder: "api/reminders",
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetReminder('');
  },
  methods: {

    emitEvent_attached(event) {

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
            base64: reader.result
          };

          this.reminder.attachment_file.push({ ...docs });

        };
      }
      //reset file control
      event.target.value = '';

    },
    //Thêm file trong payment_attached
    handleClickInputFile() {

      this.$refs.file.click();
    },
    deleteFile(item, index) {
      if (confirm(this.$t('form.confirm_delete') + '?')) {

        //  console.log("index="+index);


        this.reminder.attachment_file_removed.push({ ...item });
        this.reminder.attachment_file.splice(index, 1);
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
    reset() {
      this.reminder.content = "";
      this.reminder.date_due = "";
      this.reminder.duration = "";
      this.reminder.start_date = "";
      this.reminder.duration_value = "";
      this.reminder.reminded_before_day = "";
      this.reminder.object_id = this.object_id[0];
      this.reminder.module = this.module;
      this.reminder.type_id = 1;
      this.reminder.id = "";
      this.reminder.print = 0;
      this.reminder.attachment_file_removed = [];
      this.reminder.attachment_file = [];
    },
    fetReminder(id) {

      this.reset();
      //this.reminder = {};
      //this.reminder.type_id = 1;

      if (id != "" && id != "undefined") {
        var page_url = this.page_url_reminder + "/" + id + '&no-cache=' + new Date().getTime();

        fetch(page_url, {
          method: "get",
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        }).then(res => res.json())
          .then(res => {
            //console.log(res.data.errors);

            if (res.statuscode == "403") {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            if (!res.data.errors) {
              //toastr.success(this.$t('form.save_success'),"");
              this.reminder = res.data;
              //  this.reminder.date_due = this.reminder.date_due.toLocalString();
              this.edit = true;
              this.reminder.attachment_file_removed = [];
              //   console.log("reminder");
              //  console.log(this.reminder);
            } else {
              console.log(this.errors);
              this.errors = res.data.errors;
            }

          }).catch(err => {
            console.log(err);
          });
      }

    },
    AddReminder() {

      var temp = { ...this.reminder };
      this.loading = true;
      var page_url = this.page_url_reminder;
      this.reminder.module = this.module;
      if (this.edit === false) {
        fetch(page_url, {
          method: "POST",
          body: JSON.stringify(this.reminder),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
          .then(res => res.json())
          .then(data => {
            //alert(data.statuscode);'
            this.loading = false;

            if (data.statuscode == "403") {
              //      //cập nhật lại file đã nhấn delete nhưng save bị lỗi phân quyền
              //   if (this.reminder.attachment_file_removed.length >0) {
              //       this.reminder.attachment_file_removed.forEach(element => {
              //           this.reminder.attachment_file.push(element);
              //       });
              //        this.reminder.attachment_file_removed = [];
              //   }
              toastr.warning(data.message, "");
              return;
            }


            if (!data.data.errors && data.data.success === 1) {

              // console.log(data.data.reminder);

              toastr.success(this.$t('form.save_success'), "");
              $("#createEventModal").modal("hide");

              this.$emit('fromReminder', { ...data.data.reminder });
              this.reminder = data.data.reminder;
              this.reminder.attachment_file_removed = [];
              this.reset();

            } else {
              console.log(this.errors);

              this.errors = data.data.errors;

            }

          })
          .catch(err => {
            this.loading = false;

          });
      } else {
        //update
        this.reminder.object_id = this.object_id[0];
        this.reminder.module = this.module;
        console.log(this.reminder);
        fetch(page_url + "/" + this.reminder.id, {
          method: "PUT",
          body: JSON.stringify(this.reminder),
          headers: {
            Authorization: this.token,
            "content-type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
          .then(res => res.json())
          .then(data => {
            //  console.log(data);
            if (data.statuscode == "403") {
              //      //cập nhật lại file đã nhấn delete nhưng save bị lỗi phân quyền
              //   if (this.reminder.attachment_file_removed.length >0) {
              //       this.reminder.attachment_file_removed.forEach(element => {
              //           this.reminder.attachment_file.push(element);
              //       });
              //       this.reminder.attachment_file_removed = [];
              //   }
              toastr.warning(data.message, "");
              return;
            }

            if (!data.data.errors && data.data.success === 1) {
              toastr.success(this.$t('form.update_success'), "");
              $("#createEventModal").modal("hide");
              this.$emit('fromReminder', { ...data.data.reminder });
              this.reminder = data.data.reminder;
              this.reminder.attachment_file_removed = [];
            } else {
              this.errors = data.data.errors;

            }
            this.loading = false;
          })
          .catch(err => {
            this.loading = false;

          });
      }

    },
    hasError(fieldName) {
      return (fieldName in this.errors);
    },
    getError(fieldName) {
      //console.log(fieldName+"="+ this.errors[fieldName][0]);
      return this.errors[fieldName][0];

    },
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
    clearAllError() {
      this.errors = {};
    },
    clearError(event) {
      Vue.delete(this.errors, event.target.name);
      //console.log(event.target.name);
    },
  },

}
</script>

<style>

</style>
