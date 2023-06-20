<template>
<div class="modal fade" id="createFastProcessModal" tabindex="-1" role="dialog" aria-labelledby="createFastProcessModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
      <form action=""  @submit.prevent="AddFastProcess()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createFastProcessModal"><i class="fa fa-info-circle" style="color: #17a2b8;"></i> {{$t('form.fast_process')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
           <div class="col-md-12" id="mota">
						  <ckeditor      
                              :read-only="this.check_fast_process"
                              v-model="result.content" 
                              v-bind:config="editorConfig"
                              v-bind:class="editorClass"
                              name="content"
                              id="content"
                              
                            ></ckeditor> 
						</div>
						<div class="form-group" style="padding-left:10px;">
						<table>
						<tr>
						<td style="padding-top:15px;">
						<label class="label">Tệp đính kèm</label>
						</td>
						<td colspan="2" style="padding-top:15px;">

            <button type="button"  title="chọn file" class="btn btn-xs btn-outline " v-on:click="handleClickInputFile(index)">
            <i  title="chọn file" class="fa fa-upload"></i></button>
            <div class="d-flex justify-content-between">
              <ul class="list-unstyled">
              <li v-for="(file,index) in result.attachment_file" v-bind:key="index" class="itemfile">
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
                    <div class="d-flex justify-content-between" v-for="(other_attached,index) in result.other_attacheds" v-bind:key="index">
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
						<label class="label">Thời hạn</label>
						</td>
						<td style="padding-top:15px;">
						<input type="date" style="max-width: 170px;" v-model="result.date" class="form-control" />
						</td>
						</tr>
            <tr>
						<td style="padding-top:15px;">
						<label class="label">Người phụ trách</label>
						</td>
						<td style="padding-top:15px;">
						<input type="text" style="min-width: 400px;" v-model="result.person_in_charge" class="form-control" />
            </td>
						</tr>
						</table>
            </div>
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
export default {
  watch: {
    object_id(){
      this.result.object_id = this.object_id[0];
    },
    id(){
        this.edit = false;
        this.fetFastProcess(this.id);
        this.fetCheckFastProcess(this.id);
    },
  },
      props: {
            title:"form.create_fast_process",
            object_id:"",
            module:"",
            id:"",


    //Mỗi đối tượng muốn sử dụng result thì phải cài đặt API để nó gọi vào
    //page_url_reminder:"api/reminders",
  },

  data () {
    return {
       processe:[],
       index:'',
       wfapproved: {
                user_id: "",
                wfstep_id: ""
            },
      nameapproves: [],     
      files: [],
      result:{
        content:"",
        date:"",
        person_in_charge:"",
        object_id: this.object_id[0],
        module :this.module,
        type_id:1,
        attachment_file:[],
        attachment_file_removed:[]
      },
        infos: {
                wfmain_id: "",
                steps: [],
                steps_del: []
            },
      errors:{},
      loading: false,
      edit: false,
      token:"",
      check_fast_process:false,
      page_url_reminder : "api/reminders",
      page_url_name_approve: "api/category/positionapps",
      page_url_fast_processe : "api/car/fast_processes",
      page_url_check_fast_processe: 'api/car/check_fast_processes',
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
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.fetFastProcess('');
      this.fetCheckFastProcess('');
  },
  methods: {
          refresh() {
             location.reload();
     },
        fetNameApprove() {
            var page_url = this.page_url_name_approve;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.nameapproves = res.data;
                })
                .catch(err => console.log(err));
        },
    reset(){
        this.result.content         = "";
        this.result.date            = "";
        this.result.person_in_charge            = "";
        this.result.object_id           = this.object_id[0];
        this.result.attachment_file = [];
        this.result.attachment_file_removed =[];
        this.result.other_attacheds = [];
        this.result.id             ="";
    },
    fetFastProcess(id){

       this.reset();
       //console.log(id);
       //this.result = {};
       //this.result.type_id = 1;
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_fast_processe+"/"+id;
       // console.log(page_url);
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
                   // console.log(res);
                    if(res.statuscode =="403"){
                      window.location.href = "/errorpage?statuscode="+res.statuscode;
                    }
                    if (!res.data.errors) {
                          //toastr.success(this.$t('form.save_success'),"");
                          this.result = res.data;
                          //console.log( this.result.date);
                          //this.result.date = this.result.date;
                            this.edit = true;
                    }else{
                        //console.log(this.errors);
                        this.errors = res.data.errors;
                       
                    }

        }).catch(err=>{
          console.log(err);
        });
      }

    },
    fetCheckFastProcess(id){
       this.reset();
       //this.infos = {};
       //this.cause.type_id = 1;
       //console.log(id);
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_check_fast_processe+"/"+id;
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
              this.check_fast_process = res.is_step;
              //console.log(this.check_fast_process);     

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
                 this.result.attachment_file.push({...docs});
                // console.log(this.result.attachment_file);

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
               

                this.result.attachment_file_removed.push({...item});
                this.result.attachment_file.splice(index,1);
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
              var link = document.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.click();
            
              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));
           
        },
    AddFastProcess() {
           this.loading= true;
            var page_url = this.page_url_fast_processe;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify( this.result),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json",
                  "Accept" :"application/json",
                  "X-Requested-With": "XMLHttpRequest"
              }
          })
              .then(res => res.json())
              .then(data => {
                //alert(data.statuscode);'
                 this.loading= false;

                   if(data.statuscode == "403"){
                        toastr.warning(data.message,"");
                        return;
                    }


                  if (!data.data.errors  && data.data.success === 1) {

                       // console.log(data.data.result);

                        toastr.success(this.$t('form.save_success'),"");
                      $("#createFastProcessModal").modal("hide");
                        //this.$emit('getcar');
                         //this.reset();
                       this.refresh();

                  }else{
                     // console.log(this.errors);
                       this.errors = data.data.errors;
                       if(this.errors.person_in_charge)
                       toastr.error(this.errors.person_in_charge[0],"Thông báo");
                       if(this.errors.date)
                       toastr.error(this.errors.date[0],"Thông báo");
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");
                       
                       
                      

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              //this.result.object_id = this.object_id[0];
              //this.result.module =this.module;
              //console.log(this.fast_process);
                fetch(page_url+"/"+this.result.id, {
              method: "PUT",
              body: JSON.stringify(this.result),
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
 

                  if (!data.data.errors && data.data.success === 1) {
                         toastr.success(this.$t('form.update_success'),"");
                         $("#createFastProcessModal").modal("hide");
                         //this.$emit('getcar');
                          this.refresh();
                  }else{
                      this.errors = data.data.errors;
                      $("#createFastProcessModal").modal("hide");
                      if(this.errors.person_in_charge)
                       toastr.error(this.errors.person_in_charge[0],"Thông báo");
                       if(this.errors.date)
                       toastr.error(this.errors.date[0],"Thông báo");
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");
                      // console.log(data.data.errors);
                        //toastr.error("Phiếu đã hoàn tất. Vui lòng không cập nhật", "Thông Báo");
                  }
                    this.loading= false;
              })
              .catch(err => {
                this.loading= false;

                });
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

<style >
</style>
