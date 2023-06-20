<template>
<div class="modal fade" id="createCauseModal" tabindex="-1" role="dialog" aria-labelledby="createCauseModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 700px;">
      <form action=""  @submit.prevent="AddCauses()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCauseModal"><i class="fa fa-info-circle" style="color: #17a2b8;"></i> {{$t('form.causes_and_preventive_measures')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
        <div class="">
        	<table>
          <tr>
          <td >
						<label class="label">Nguyên nhân</label>
          </td>
          <td colspan="2">
         <ckeditor v-bind:config="editorConfig1"
                              v-bind:class="editorClass" :readonly="this.check_cause==true" v-model="infos.cause"                    
                        ></ckeditor>
          </td>
          </tr>
						<tr>
						<td>
             	<label class="label">Biện pháp khắc phục</label>
						</td>
						<td colspan="2">
               <ckeditor v-bind:config="editorConfig"
                              v-bind:class="editorClass" :readonly="this.check_cause==true" v-model="infos.measure" 
                          >
                </ckeditor>
						</td>
					
						</tr>
						<tr>
						<td style="padding-top:15px;">
						<label class="label">Tệp đính kèm</label>
						</td>
						<td colspan="2" style="padding-top:15px;">

            <button type="button"  title="chọn file" class="btn btn-xs btn-outline " v-on:click="handleClickInputFile(index)">
            <i  title="chọn file" class="fa fa-upload"></i></button>
            <div class="d-flex justify-content-between">
              <ul class="list-unstyled">
              <li v-for="(file,index) in infos.attachment_file" v-bind:key="index" class="itemfile">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                  <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile(file,index)" ><i class="far fa-times-circle "></i></button>                          
                  <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>                          
                </div>
                 </li>      
                 </ul>
                  <input type="file"
                                        accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" 
                                          @input="emitEvent_voucher($event)"
                                          @change="emitEvent_voucher($event)"
                                          id="ThemFileVoucher"
                                          style="display:none"
                                          ref="fileVoucher"
                                        multiple
                                  
                                  >        
                  </div>
                   <div class="d-flex justify-content-between" v-for="(other_attached,index) in infos.other_attacheds" v-bind:key="index">
                     <ul class="list-unstyled">
                    <li v-for="(file,findex) in other_attached.attachment_file" v-bind:key="findex" class="itemfile">
                     <div class="btn-group">
                      <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                       </div>
                      </li>
                       </ul>
                   </div>                        
						</td>
						</tr>
						<tr>
						<td style="padding-top:15px;">
						<label class="label">Ngày</label>
						</td>
						<td style="padding-top:15px;">
						<input type="date"  style="max-width: 170px;" v-model="infos.date" class="form-control"  />
						</td>
						</tr>
             <tr>
						<td style="padding-top:15px;">
						<label class="label">Người phụ trách</label>
						</td>
						<td style="padding-top:15px;">
						<input type="text" style="min-width: 400px;" v-model="infos.person_in_charge" class="form-control" />
            </td>
						</tr>
						</table>
            </div>
    
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> {{$t('form.close')}}</button>
        <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> {{$t('form.save')}}</button>
      </div>
    </div>
      </form>
  </div>
</div>

</template>

<script>
import moment from 'moment';
export default {
  
  watch: {
    object_id(){
      this.cause.object_id = this.object_id[0];      
    },
    id(){
        this.edit = false;
        this.fetCause(this.id);
        this.fetCheckCause(this.id);
    },
  },
      props: {
            title:"form.create_cause",
            object_id:"",
            module:"",
            id:"",


    //Mỗi đối tượng muốn sử dụng cause thì phải cài đặt API để nó gọi vào
    //page_url_reminder:"api/reminders",
  },

  data () {
    return {
      index:'',
      isReadOnly:false,
      cause:{},
        fields: [
                {
                    key: "cause",
                    label: this.$t("form.causes")
                },
                {
                    key: "measure",
                    label: this.$t("form.corrective_measures")
                },
                {
                    key: "date",
                    label: this.$t("form.date")
                },
                 {
                    key: "person_in_charge",
                    label: this.$t("form.person_in_charge")
                },
                  {
                    key: "file",
                    label: this.$t("form.file")
                },
                {
                    key: "action",
                    lable: ""
                }
            ],
        infos: {
                cause:"",
                measure:"",
                date:"",
                person_in_charge:"",
                object_id: this.object_id[0],
                attachment_file:[],
                attachment_file_removed:[]
            },
      check_cause:"",
      errors:{},
      loading: false,
      edit: false,
      token:"",
      //page_url_reminder : "api/reminders",
      page_url_cause: 'api/car/cause_measures',
      page_url_check_cause: 'api/car/check_cause_measures',
      editorClass : "col-lg-12", 
               editorConfig: {
                    toolbarGroups : [
                        // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                        // { name: 'forms', groups: [ 'forms' ] },
                        // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                        // { name: 'links', groups: [ 'links' ] },
                        { name: 'insert', groups: [ 'insert' ] },
                        // { name: 'styles', groups: [ 'styles' ] },
                        { name: 'colors', groups: [ 'colors' ] },
                        // { name: 'tools', groups: [ 'tools' ] },
                        // { name: 'others', groups: [ 'others' ] },
                        // { name: 'about', groups: [ 'about' ] }              
                    ],
                    removeButtons: 'NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                },
                editorConfig1: {
                    toolbarGroups : [
                        // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                        // { name: 'forms', groups: [ 'forms' ] },
                        // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                        // { name: 'links', groups: [ 'links' ] },
                        { name: 'insert', groups: [ 'insert' ] },
                        // { name: 'styles', groups: [ 'styles' ] },
                        { name: 'colors', groups: [ 'colors' ] },
                        // { name: 'tools', groups: [ 'tools' ] },
                        // { name: 'others', groups: [ 'others' ] },
                        // { name: 'about', groups: [ 'about' ] }              
                    ],
                    removeButtons: 'NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                },
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.fetCause('');
      this.fetCheckCause('');
  },
  methods: {
    reset(){
        this.infos.cause             = "";
        this.infos.measure            = "";
        this.infos.date          ="";
        this.infos.person_in_charge            = "";
        this.infos.object_id = this.object_id[0];
        this.infos.attachment_file = [];
        this.infos.attachment_file_removed =[];
        this.infos.other_attacheds = [];
        this.infos.id             ="";
    },
     refresh() {
             location.reload();
     },
    fetCause(id){
       this.reset();
       //this.infos = {};
       //this.cause.type_id = 1;
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_cause+"/"+id;
        fetch(page_url,{
          method:"get",
          headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                    "Accept" :"application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
        }).then(res=>res.json())
        .then(res=>{
                    console.log(res);
                    if(res.statuscode =="403"){
                      window.location.href = "/errorpage?statuscode="+res.statuscode;
                    }
                    if (!res.data.errors) {
                          //toastr.success(this.$t('form.save_success'),"");
                          this.infos = res.data;
                        //  this.cause.date_due = this.cause.date_due.toLocalString();
                        //console.log(moment(this.infos.date).format('MM/DD/YYYY'));
                            this.edit = true;
                    }else{
                        console.log(this.errors);
                        this.errors = res.data.errors;
                    }

        }).catch(err=>{
          console.log(err);
        });
      }

    },
    fetCheckCause(id){
       this.reset();
       //this.infos = {};
       //this.cause.type_id = 1;
      // console.log(id);
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_check_cause+"/"+id;
        fetch(page_url,{
          method:"get",
          headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                    "Accept" :"application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
        }).then(res=>res.json())
        .then(res=>{
              this.check_cause = res.is_step;
              //console.log(this.check_cause);     

        }).catch(err=>{
          console.log(err);
        });
      }

    },
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
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                      base64: reader.result
                  };
                 //console.log(docs);
                 this.infos.attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';
           
        },
     handleClickInputFile(index) {
            
            this.$refs.fileVoucher.click();
            
     
        },  
        deleteFile(item,index){
           if(confirm(this.$t('form.confirm_delete') +'?')){
                
              //  console.log("index="+index);
               

                this.infos.attachment_file_removed.push({...item});
                this.infos.attachment_file.splice(index,1);
            }
        },
        downloadFile(idfile,filename){
          var page_url = 'api/car/downloadFile/'+idfile;
          fetch(page_url, {
             headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                responseType: 'arraybuffer',
                method:'post'  
           }) 
            .then( res => res.blob() )
           .then(res=>{
           
              var newBlob = new Blob([res], {type: "octet/stream"});
              if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
              }
              // For other browsers:
              // Create a link pointing to the ObjectURL containing the blob.
              const data = URL.createObjectURL(newBlob);
              var link = infos.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.click();
            
              setTimeout(function () {
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));
           
        },
    AddCauses() {
           this.loading= true;
            var page_url = this.page_url_cause;
            this.infos.car_id = this.object_id[0];
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.infos),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json",
                  "Accept" :"application/json",
                  "X-Requested-With": "XMLHttpRequest"
              }
          })
              .then(res => res.json())
              .then(data => {
                console.log(data);
                 this.loading= false;

                   if(data.statuscode == "403"){
                        toastr.warning(data.message,"");
                        return;
                    }


                  if (!data.data.errors  && data.data.success === 1) {

                       // console.log(data.data.cause);

                      toastr.success(this.$t('form.save_success'),"");
                      $("#createCauseModal").modal("hide");
                       //this.$emit('getcar');
                       //this.reset();
                      this.refresh();

                  }else{
                      console.log(this.errors);
                      this.errors = data.data.errors;
                       if(this.errors.person_in_charge)
                       toastr.error(this.errors.person_in_charge[0],"Thông báo");
                       if(this.errors.date)
                       toastr.error(this.errors.date[0],"Thông báo");
                       if(this.errors.measure)
                       toastr.error(this.errors.measure[0],"Thông báo");
                       if(this.errors.cause)
                       toastr.error(this.errors.cause[0],"Thông báo");
                       
                       
                      

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.cause.object_id = this.object_id[0];
              this.cause.module =this.module;
             // console.log(this.id);
                fetch(page_url+"/"+this.id, {
              method: "PUT",
              body: JSON.stringify(this.infos),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json",
                  "Accept" :"application/json",
                  "X-Requested-With": "XMLHttpRequest"
              }
          })
              .then(res => res.json())
              .then(data => {
                //  console.log(data);
                   if(data.statuscode == "403"){
                        toastr.warning(data.message,"");
                        return;
                    }

                  if (!data.data.errors && data.data.success === 1) {
                         toastr.success(this.$t('form.update_success'),"");
                         $("#createCauseModal").modal("hide");
                         //this.$emit('getcar');
                         this.refresh();
                  }else{
                      this.errors = data.data.errors;
                       this.errors = data.data.errors;
                       if(this.errors.person_in_charge)
                       toastr.error(this.errors.person_in_charge[0],"Thông báo");
                       if(this.errors.date)
                       toastr.error(this.errors.date[0],"Thông báo");
                       if(this.errors.measure)
                       toastr.error(this.errors.measure[0],"Thông báo");
                       if(this.errors.cause)
                       toastr.error(this.errors.cause[0],"Thông báo");
                  }
                    this.loading= false;
              })
              .catch(err => {
                this.loading= false;

                });
          }

      },
       add_new_row() {
            var info = {};
            //info.step = "";
            this.infos.steps.push({ ...info });
        },
        delete_row(item, index) {
            if (confirm("Xác nhận xoá?")) {
                this.infos.steps_del.push({ ...item });
                this.infos.steps.splice(index, 1);
            }
        },
       hasError(fieldName){
            return (fieldName in this.errors);
        },
        getError(fieldName){
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        },
      hasAnyError(){
          return Object.keys(this.errors).length > 0;
      },
          clearAllError(){
          this.errors = {};
      },
      clearError(event){
        Vue.delete( this.errors,event.target.name);
          //console.log(event.target.name);
      },
  },

}
</script>

<style>

</style>
