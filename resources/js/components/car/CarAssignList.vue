<template>
<div class="card">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  {{$t('form.assign_list')}}
                </h3>
              </div>
              <div class="card-body">
            <span v-for="item in this.object.shareds" v-bind:key="item.id">
            <span v-if="item.type == 4">
                 <div class="btn-group ml-1 mt-1 "  >
                    <button type="button" class="btn btn-default btn-xs" :title="item.assign.name"> {{item.assign.name}}</button>
                    <button type="button" class="btn btn-default btn-xs text-red " @click.prevent="del(item)" ><i class="far fa-times-circle "></i></button>
                </div>


            </span>

            </span>
                
              </div>
          
            </div>
</template>
<script>
export default {
watch: {
      object_id(){
      this.object_id = this.object_id;
    },
  },
computed:{

    },
props: {
      object_id:"",
      module:"",
      object:Object
  },
components: {

    },
 data() {
   return {
        page_url_shared  :"/api/shareds",
   };
 },
  methods: {
         fromShared(obj){
         this.object.shareds = obj;
     },
    sharedAction(obj,type){
            var index ="";
        switch (type) {


           case 'DELETE':
                 //gọi ham delete
                 index = this.object.shareds.findIndex(data=>data.id == obj.id);
               this.object.shareds.splice(index,1);

            break;

          default:
            break;
        }
      },
      del(obj){
          let shared = obj;
          var page_url = this.page_url_shared+"/"+shared.id;
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
                          body:JSON.stringify({'module':this.module,'doc_id':this.object.id}),
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
                                    this.sharedAction(shared,'DELETE');
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
      console.log(this.type);
  },
}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
