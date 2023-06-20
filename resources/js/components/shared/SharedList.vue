<template>
     <div>
        <a href="#" @click.prevent="display_shared()"><i class="fas fa-share-square"></i></a>
        <span v-for="item in items" v-bind:key="item.id">
            <span v-if="item.type == 1">
                 <div class="btn-group ml-1 mt-1 "  >
                    <button type="button" class="btn btn-default btn-xs" :title="item.viewer.username"> {{item.viewer.name}}</button>
                    <button type="button" class="btn btn-default btn-xs text-red " @click.prevent="del(item)" ><i class="far fa-times-circle "></i></button>
                    <!-- <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button> -->
                </div>


            </span>

            </span>
     </div>

</template>

<script>
export default {
    data () {
    return {
        items:this.items_props,
        selected:"",
        object_id:this.object.id,
        page_url_shared  :"/api/shareds",
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

        display_shared(){

         this.object_id  = "";
         $('#createSharedModal').modal('show');
      },
      show(){

          this.$emit('sharedAction',null,'SHOW');
      },
      edit(obj){
          console.log(obj);
          this.$emit('sharedAction',obj,'EDIT');
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
                          body:JSON.stringify({'module':this.type,'doc_id':this.object.id}),
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
                                    this.$emit('sharedAction',shared,'DELETE');
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
  .shared_item{
       display: inline;
  }

</style>
