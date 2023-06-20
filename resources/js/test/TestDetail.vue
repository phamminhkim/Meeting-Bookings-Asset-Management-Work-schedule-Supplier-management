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
                 <button class="btn btn-default" @click.prevent="print()"><i class="fas fa-print"></i></button>
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- ----------------- -->
    <div class="row">
        <div class="col-md-8">

           <timeline-list :list="document.timelines" :showicon="true"></timeline-list>
        </div>
         <approvewf-form v-bind:object="document"  :type="'CARS'" :user_id="user_id"></approvewf-form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
         document:{
        id:"1",
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
        users:[],
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
  methods: {

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
