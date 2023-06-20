<template>
    <div class="card">
        <div  class="card-header" style="background-color: #E5E5E5;">
            <h6 class="card-title"><i class="fas fa-bell"></i> {{$t('form.reminder')}}</h6>
            <div class="card-tools">
                <button class="btn btn-secondary btn-sm" :title="$t('form.add')" @click="show()"> <i class="fas fa-plus"></i>  </button>
            </div>
        </div>
        <div class="card-body"  >
            <b-table    small="small" :items="items" id="reminderRef" ref="reminderRef" thead-class="hidden_header" :responsive="true" :fields="fields">
                <template #cell(date_due)="data">
                    <span>{{data.value|formatDate}}</span>
                </template>
                <template #cell(content)="data">
                     <!-- <textarea name="" id=""  readonly v-model="data.value" style="border:0px" maxlength="255" cols="30" rows="10"></textarea> -->
                     <pre style="font-family:none;font-size: inherit;white-space: pre-wrap;">{{data.value}}</pre>
                     <!-- <span  >{{data.value}}</span> -->
                      <div class="d-flex justify-content-between">

                                        <ul class="list-unstyled">
                                          <li v-for="(file,index_file) in data.item.attachment_file" v-bind:key="index_file" class="itemfile">
                                            <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs"  @click.prevent="downloadFile(file.id,file.name)"> {{file.name}}</button>
                                            <!-- <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile(file,index_file)" ><i class="far fa-times-circle "></i></button> -->
                                            <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>
                                          </div>
                                          </li>
                                        </ul>


                                </div>
                </template>
                 <!-- <template #row-details="row">
                     <pre style="font-family:none;font-size: inherit;white-space: pre-wrap;">{{row.item.content}}</pre>
                 </template> -->
                 <template #cell(active)="data">
                     <div v-if="data.item.type_id == 1">
                        <span v-if="data.value == 0" class="badge bg-success">{{$t('form.completed')}}</span>
                        <span v-if="data.value == 1" class="badge bg-warning">{{$t('form.pending')}}</span>
                     </div>
                     <div v-if="data.item.type_id == 0">
                       <i class="far fa-sticky-note " :title="$t('form.note')"></i>
                     </div>

                </template>
                <template #cell(action)="data">
                    <a href="#" :title="$t('form.edit')" class="btn btn-sm"   @click.prevent="edit(data.item)"> <i class="fas fa-edit"></i> </a>
                    <a href="#" :title="$t('form.delete')" class="text-red btn btn-sm"  @click.prevent="del(data.item)"><i class="fas fa-trash"></i></a>
                </template>
                <template #cell(user_create)="data">

                   <b-avatar v-if="data.item.user" :src="data.item.user.avatar" :title="data.item.user.name" size="sm"></b-avatar>
                </template>
           </b-table>
        </div>

         <!-- <create-event-dialog :object_id="object_id"  :id="selected" :module="type"></create-event-dialog> -->
    </div>

</template>

<script>
export default {
    data () {
    return {
        items:this.items_props,
        selected:"",
        object_id:this.object.id,
        fields:[
         {
            key: 'content',
            label:this.$t('form.content'),
            sortable: true,
            stickyColumn: true,
            //  class:"text-nowrap"
          },
          {
            key: 'date_due',
            label:this.$t('form.date'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
          {
            key: 'active',
            label:this.$t('form.status'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
           {
            key: 'user_create',
            label:this.$t('form.user'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
           {
            key: 'action',
            label:'',
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
        ],
        page_url_reminder  :"/api/reminders",


    }
  },
  watch: {
      items_props(){
          this.items =  this.items_props;

      }
  },
  props: {
        object: Object,
      items_props:Array,
      type:""
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
        notification_show(){

         this.object_id  = "";
         $('#createEventModal').modal('show');


      },
      show(){

          this.$emit('reminderAction',null,'SHOW');
      },
      edit(obj){
          console.log(obj);
          this.$emit('reminderAction',obj,'EDIT');
      },
      del(obj){
          let reminder = obj;
          var page_url = this.page_url_reminder+"/"+reminder.id;
          this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+ '?',
           {
               okVariant:"danger",
               okTitle:"Ok",
               cancelTitle:"Cancel",
               centered:true
           }).then(value=>{
               if(value){

                     fetch(page_url,{
                        method:'DELETE',
                        body:JSON.stringify({'module':this.type,'object_id':this.object.id}),
                        headers:{
                            Authorization:this.token,
                            'Content-Type':'application/json',
                            "Accept": "application/json",
                            'X-Requested-With':'XMLHttpRequest'
                        }
                        }).then(res=>res.json())
                        .then(res=>{
                                if(res.statuscode == "403"){
                                    toastr.warning(res.message,"");
                                    return;
                                }
                                if(res.data.success == '1'){
                                    toastr.success(this.$t('form.delete_success'),"");
                                    this.$emit('reminderAction',reminder,'DELETE');
                                }else{
                                    toastr.warning(this.$t('form.delete_error'),"");
                                }
                        }).catch(err=>{
                            console.log(err);
                            toastr.warning(this.$t('form.delete_error'),"");
                        });
               }
           });


      },

  },
  created () {
        this.token = "Bearer " + window.Laravel.access_token;
  },


}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
