<template>
<div class="modal fade" id="createResultModal" tabindex="-1" role="dialog" aria-labelledby="createResultModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
      <form action=""  @submit.prevent="AddResult()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createResultModal"><i class="fa fa-info-circle" style="color: #17a2b8;"></i> {{$t('form.result_evaluation')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
           <div class="col-md-12" id="mota">
						<label class="label">Nội dung mô tả</label>
						  <ckeditor
                              v-model="evaluations.content" 
                              v-bind:config="editorConfig"
                              v-bind:class="editorClass"
                              name="content"
                              id="content"
                              
                            ></ckeditor> 
						</div>
						<div class="form-group" style="padding-left:10px;">
						<label class="label">Kết quả</label>
						<table>
						<tr>
						<td>
              <div class="custom-control custom-radio" style="margin-left:20px;">
              <input class="custom-control-input" type="radio" value="0" id="customRadio1" v-model="evaluations.result">
              <label for="customRadio1" class="custom-control-label" style="font-weight: 500;">Đạt</label>
              </div>
						</td>
						<td>
                <div class="custom-control custom-radio" style="margin-left:10px;">
                <input class="custom-control-input" type="radio" value="1" id="customRadio2" v-model="evaluations.result">
                <label for="customRadio2" class="custom-control-label" style="font-weight: 500;">Không đạt</label>
                </div>
						</td>
						<td>
                <div class="custom-control custom-radio" style="margin-left:10px;">
                <input class="custom-control-input" type="radio" value="2" id="customRadio3" v-model="evaluations.result">
                <label for="customRadio3" class="custom-control-label" style="font-weight: 500;">Không có dữ liệu xác nhận</label>
                </div>
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
              <li v-for="(file,index) in evaluations.attachment_file" v-bind:key="index" class="itemfile">
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
                   <div class="d-flex justify-content-between" v-for="(other_attached,index) in evaluations.other_attacheds" v-bind:key="index">
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
						<label class="label">Ngày đánh giá</label>
						</td>
						<td style="padding-top:15px;">
						<input type="date" v-model="evaluations.date" class="form-control"  />
						</td>
						</tr>
						<!-- <tr>
						<td style="padding-top:15px;">
						<label class="label">Người ký duyệt </label>
						</td>
						<td style="padding-top:15px;">
						<select
                                        name=""
                                        id=""
                                        v-model="result.user_id"
                                        class="form-control"
                                    >
                                        <option
                                            v-for="nameapprove in nameapproves"
                                            :key="nameapprove.user_id"
                                            v-bind:value="nameapprove.user_id"
                                        >
                                            {{ nameapprove.user.name }}
                                        </option>
                                    </select>
						</td>
						</tr> -->
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
        this.fetResultEvaluation(this.id);
       // console.log(this.id);
    },
  },
      props: {
            title:"form.create_result",
            object_id:"",
            module:"",
            id:"",


    //Mỗi đối tượng muốn sử dụng result thì phải cài đặt API để nó gọi vào
    //page_url_reminder:"api/reminders",
  },

  data () {
    return {
      // evaluation:[],
       index:'',
       wfapproved: {
                user_id: "",
                wfstep_id: ""
            },
      nameapproves: [],     
      files: [],
      result:{},
      evaluations:{
        content:"",
        result:0,
        date:"",
        car_id: this.object_id[0],
        attachment_file:[],
        attachment_file_removed:[],
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
     // page_url_reminder : "api/reminders",
      page_url_name_approve: "api/category/positionapps",
      page_url_result_evaluation : "api/car/result_evaluations",
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
      this.fetResultEvaluation('');
      this.fetNameApprove();
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
        this.evaluations.content             = "";
        this.evaluations.result            = 0;
        this.evaluations.date            = "";
        this.evaluations.attachment_file = [];
        this.evaluations.attachment_file_removed =[];
        this.evaluations.other_attacheds = [];
        this.evaluations.id             ="";
        this.evaluations.car_id = this.object_id;
        
    },
    fetResultEvaluation(id){
       this.reset();
       //this.result = {};
       //this.result.type_id = 1;
      // console.log(this.id);
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_result_evaluation+"/"+id;
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
                          this.evaluations = res.data;
                        //  this.result.date_due = this.result.date_due.toLocalString();
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
                 this.evaluations.attachment_file.push({...docs});

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
               

                this.evaluations.attachment_file_removed.push({...item});
                this.evaluations.attachment_file.splice(index,1);
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
              var link = result.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.click();
            
              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));
           
        },
    AddResult() {
           this.loading= true;
            var page_url = this.page_url_result_evaluation;
           // this.result.module = this.module;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.evaluations),
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
                      $("#createResultModal").modal("hide");
                      //this.$emit('getcar');
                     // this.reset();
                      this.refresh();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;
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
              this.result.object_id = this.object_id[0];
              //this.result.module =this.module;
             // console.log(this.result);
                fetch(page_url+"/"+this.id, {
              method: "PUT",
              body: JSON.stringify(this.evaluations),
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
                         $("#createResultModal").modal("hide");
                          //this.$emit('getcar');
                         this.refresh();
                  }else{
                      this.errors = data.data.errors;
                      if(this.errors.date)
                       toastr.error(this.errors.date[0],"Thông báo");
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");
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
