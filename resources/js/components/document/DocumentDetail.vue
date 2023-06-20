<template>
  <div>
      <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="#" @click.prevent="backToList()">{{ $t(pre_title)}}</a> </h5></li>

                <li class="breadcrumb-item active">

                  <span >{{$t('form.detail')}}</span>

                  </li>
             </ol>
            </div>
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">

                 <button v-if="user_id == document.user_id && document.locked != 1" class="btn  btn-default" @click.prevent="editDocument()"><i class="fas fa-edit"></i>{{$t('form.edit')}}</button>
                 <button class="btn btn-default" @click.prevent="print()"><i class="fas fa-print"></i></button>
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- ----------------- -->
    <div class="row">
        <div class="col-md-8">
           <div class="card">
               <div class="" >
                    <div class="p-2" style="text-align:center;background-color:#28659C;color:white" v-if="form_title">{{form_title}}</div>
                    <div class="p-2" style="text-align:center;background-color:#28659C;color:white" v-if="!form_title">{{ $t(pre_title)}}</div>
                   <div class="d-flex  justify-content-between m-2" style="border-bottom:1px solid #CEE2EE">
                    <div   >
                        <span class="mute small"><i>{{$t('form.create_by')}}: <strong v-if="document.user">{{document.user.name}} - {{document.user.username}}  </strong> </i></span><br>
                        <span class="mute small"><i>{{$t('form.company')}}: <strong v-if="document.user && document.company"> {{document.company.name}}</strong></i></span>
                    </div>
                    <div   >
                        <span class="mute small" > <i> {{$t('form.serial_num')}} :<strong> {{document.serial_num}} </strong></i></span><br>
                        <span class="mute small" ><i>{{$t('form.send_date')}}: <strong class="ml-4" >{{document.send_date| formatDate}}</strong></i></span>
                    </div>
                   </div>


               </div>

               <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                         <div class="form-group row"  v-if="showControl('docbrowser_type_id')">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.docbrowsertype')}}:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label-sm" v-if="document.docbrowser_type" >{{document.docbrowser_type.name}}</label>
                            </div>
                          </div>
                        <div class="form-group row">
                          <span class="col-form-label-sm col-sm-2 ">{{$t('form.about')}}:</span>
                          <div class="col-sm-10">
                              <label class="col-form-label-sm">{{document.title}}</label>
                          </div>
                        </div>
                        <div class="form-group row"  v-if="showControl('budget_num')">
                          <span class="col-form-label-sm col-sm-2 ">{{$t('form.budget_code')}}:</span>
                          <div class="col-sm-10">
                              <label class="col-form-label-sm">{{document.budget_num}}</label>
                          </div>
                        </div>
                        <div class="form-group row" v-if="showControl('amount')">
                            <span class="col-form-label-sm col-sm-2 " ><span v-html="showLabel('amount',$t('form.amount'))"></span>:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label-sm">{{document.amount.toLocaleString(locale_format)}} {{document.currency }}</label>
                            </div>
                        </div>
                        <div class="form-group row"  v-if="showControl('doc_type_id')">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.doc_type')}}:</span>
                            <div class="col-sm-10">
                                <label class="col-form-label-sm" v-if="document.doc_type" >{{document.doc_type.name}}</label>
                            </div>
                          </div>
                        <div class="form-group row">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.content')}}:</span>
                            <div class="col-sm-10"  style="overflow-x: auto;">
                                <p  class="col-form-label-sm" v-html="document.content"></p>
                          </div>
                        </div>
                         <div class="form-group row"  v-if="showControl('filesign')">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.sign_file')}}:</span>
                            <div class="col-sm-10">
                               <div class="d-flex justify-content-between">

                                        <ul class="list-unstyled">
                                          <li v-for="(filesign,index) in document.filesigns" v-bind:key="index" class="itemfile">
                                            <div class="btn-group">

                                            <button type="button"  v-if="filesign.attachment_file[0].id" class="btn btn-default btn-xs" @click.prevent="downloadFile(filesign.attachment_file[0].id,filesign.attachment_file[0].name)"> {{filesign.attachment_file[0].name}}</button>
                                            <button type="button"  v-if="filesign.signed" class="btn btn-default btn-xs" @click.prevent="downloadFile(filesign.attachment_file[0].id,filesign.attachment_file[0].name)" title="Đã ký"> <i class="fas fa-check text-green" title="Đã ký"></i></button>
                                            <button type="button" v-if="filesign.attachment_file[0].id" class="btn btn-default btn-xs" @click.prevent="downloadFile(filesign.attachment_file[0].id,filesign.attachment_file[0].name)"  ><i class="fas fa-download"></i></button>
                                          </div>
                                          </li>
                                        </ul>


                                </div>
                          </div>
                        </div>
                         <div class="form-group row">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.attached_file')}}:</span>
                            <div class="col-sm-10">
                               <div class="d-flex justify-content-between">

                                        <ul class="list-unstyled">
                                          <li v-for="(file,index) in document.attachment_file" v-bind:key="index" class="itemfile">
                                            <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"> {{file.name}}</button>

                                            <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>
                                          </div>
                                          </li>
                                        </ul>


                                </div>
                          </div>
                        </div>

                         <div class="form-group row">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.viewers')}}

                            </span>
                            <div class="col-sm-10">
                                <p  class="col-form-label-sm">
                                    <span v-for="(shared,index) in document.shareds" v-bind:key="index">


                                        <span  class="badge ml-1" v-if="shared.group">{{shared.group.company_id}}-{{shared.group.name}}</span>
                                    </span>

                                </p>

                          </div>
                        </div>
                          <div class="form-group row">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.shared')}}

                            </span>
                            <div class="col-sm-10">
                                    <shared-list v-bind:object="document" :items_props="document.shareds" v-on:sharedAction="sharedAction"   :type="'REPORT'"></shared-list>

                          </div>
                        </div>

                     <!-- <b-form-group   >
                        <span v-if="document.budget_type == 0" class="col-form-label-sm col-sm-2 "><i class="far fa-check-square"></i> {{ $t('Ngoài / Vượt ngân sách') }}</span>
                        <span v-if="document.budget_type != 0" class="col-form-label-sm col-sm-2 "><i class="far fa-square"></i> {{ $t('Ngoài / Vượt ngân sách') }}</span>
                        <span v-if="document.budget_type == 1" class="col-form-label-sm col-sm-2 "><i class="far fa-check-square"></i> {{ $t('Trong ngân sách') }}</span>
                        <span v-if="document.budget_type != 1" class="col-form-label-sm col-sm-2 "><i class="far fa-square"></i>  {{ $t('Trong ngân sách') }}</span>
                        </b-form-group> -->

                    </div>
                </div>


               </div>
               <loading :loading="loading"></loading>
           </div>
           <reminder-list v-bind:object="document" :items_props="document.reminders" v-on:reminderAction="reminderAction"   :type="'REPORT'"></reminder-list>
           <timeline-list :list="document.timelines" :showicon="true"></timeline-list>
        </div>
         <div class="col-md-4 ">
         <approve-form v-bind:object="document"  :showstep="showStep('showstep')"  :type="'REPORT'" :user_id="user_id"></approve-form>
         </div>
    </div>
    <create-event-dialog :object_id="object_id" v-on:fromReminder="fromReminder" :id="reminder_id"  module="REPORT"></create-event-dialog>
    <shared-dialog :doc_id="object_id" v-on:fromShared="fromShared"   module="REPORT"></shared-dialog>
  </div>
</template>

<script>

export default {

  data () {
    return {
         document:{
        title:"",
        content:"",
        amount:"",
        budget_type:"0",
        currency:"VND",
        serial_num:"",
        budget_num:"",
        group_id:"",
        payment_type_id:"0",
        document_type_id:this.doctype_id,

      },
       locale_format:'de-DE',
      // options: [
      //     { text: 'Vượt ngân sách', value: '1',notEnabled: true },
      //     { text: 'Ngoài ngân sách', value: '0',notEnabled: true },

      //   ],
    reminder_id:"",
        users:[],
      object_id:[],
      edit:false,
        errors:{},
      loading: false,
        token:"",
      tabIndex: 0,
      page_url_documents : "api/documents",
       page_url_users:"api/user/all",
       page_url_document_print :"/document/print",
    }
  },
    watch: {
    document(){
      this.object_id.push(this.document.id);
    }
  },
  methods: {
     fromShared(obj){
         this.document.shareds = obj;
     },
     fromReminder(obj){


       let index = this.document.reminders.findIndex(item=>{
           return item.id == obj.id;
         });

         if(index != -1){
             this.document.reminders[index] = obj;

             this.$root.$emit('bv::refresh::table', 'reminderRef');//refresh data trong danh sách reminder -> ở form khác

         }else{
            this.document.reminders.push(obj);
         }


      },
      sharedAction(obj,type){
            var index ="";
        switch (type) {


           case 'DELETE':
                 //gọi ham delete
                 index = this.document.shareds.findIndex(data=>data.id == obj.id);
               this.document.shareds.splice(index,1);

            break;

          default:
            break;
        }
      },
      reminderAction(obj,type){


          var index ="";
        switch (type) {
          case 'EDIT':
                index = this.document.reminders.findIndex(data=>data.id == obj.id);
              this.reminder_id = obj.id;
              $('#createEventModal').modal('show');
            break;
          case 'DELETE':
                index = this.document.reminders.findIndex(data=>data.id == obj.id);
               this.document.reminders.splice(index,1);
                this.reminder_id = "";
               //gọi ham delete
            break;
           case 'SHOW':
               this.reminder_id = "";
                $('#createEventModal').modal('show');
               //gọi ham delete
            break;
          default:
            break;
        }

      },
    editDocument(){
    window.location.href= "documents?type=edit&id="+this.id;
    },
      showStep(){

          return this.showControl('showstep')?"X":"";

      },
      downloadFile(idfile,filename){
          var page_url = 'api/payment/downloadFile/'+idfile;
          toastr.info("Đang download....","");
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
                 toastr.info("Download thành công","");
                return;
              }
              // For other browsers:
              // Create a link pointing to the ObjectURL containing the blob.
              const data = URL.createObjectURL(newBlob);
              var link = document.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.click();
               toastr.info("Download thành công","");
              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));

        },
      backToList(){

          //console.log("this.pre_url="+this.pre_url);
          window.location.href =  this.pre_url;
      },
      print(){
        window.location.href =  this.page_url_document_print+"/"+this.document.id;
      },
      getDocument(){
          if( this.id != ""){
            this.loading = true;

            var page_url = this.page_url_documents+"/"+this.id+"?notification_id="+this.notification_id;;
            fetch(page_url,{ headers: { Authorization: this.token } })
            .then(res=>res.json())
            .then(res=>{

            if(res.statuscode =="403"){
                  window.location.href = "/errorpage?statuscode="+res.statuscode;
              }
            if(res.data.success == '1'){
                this.document = res.data;

                this.edit = true;
                this.loading = false;


            }

          }).catch(err=>{
              this.loading = false;
              console.log(err);
          });
        }
      },
      showLabel(fieldName,value){

          if(fieldName in this.layout){
             if(this.layout[fieldName]['has_custom_label']){
               return this.layout[fieldName]['custom_label_text'];
             }
          }
          return value;
       },
      showControl(fieldName){
          if(fieldName in this.layout){

             return this.layout[fieldName]['isVisible'];
          }
          return false;
       },


  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
    //   this.getUser();
      this.getDocument();
  },
  props: {
     id:String,
     user_id:String,
     pre_url:"",
     pre_title:"",
     form_title:"",
     module_id:String,
     notification_id:String,
     layout:Object,
  },

}
</script>

<style  scoped>
   .form-group {
      margin-bottom: 1px  !important;
    }
    h1, h2, h3, h4, h5, h6 {
    font-weight: normal !important;;
    line-height: 1.2 !important;;
}
    h3, .h3 {
        font-size: 1.15rem !important;
    }
  h3 {
      display: block !important;;
      font-size: 1.17em !important;
      margin-block-start: 1em !important;;
      margin-block-end: 1em;
      margin-inline-start: 0px !important;;
      margin-inline-end: 0px !important;;
      font-weight: bold !important;;
  }
</style>
