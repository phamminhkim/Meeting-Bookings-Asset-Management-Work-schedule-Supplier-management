<template>
<div class="modal fade" id="createSharedModal" tabindex="-1" role="dialog" aria-labelledby="createSharedModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action=""  @submit.prevent="AddShared()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" ><i class="fas fa-share-square"></i> {{$t('form.shared')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
               <div class="form-group">
                 <label for=""> {{$t('form.user')}}</label> <small   class="text-red">( * )</small>
                 <!-- <input type="text" required v-model="shared.doc_ids" class="form-control" maxlength="255"> -->
                 <treeselect  placeholder="" :disable-branch-nodes="true" :multiple="true" v-model="shared.object_ids"    :options="tree_users" />
              </div>

              <div class="form-group" v-show="false">
                 <label for=""> {{$t('form.type')}}</label>
                 <select name="" id="" v-model="shared.type" class="form-control">
                     <option value="0">Group</option>
                     <option value="1" selected>User</option>
                 </select>
              </div>
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
 import Treeselect from '@riophae/vue-treeselect'
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
 components: {  Treeselect},
  created(){
       this.token = "Bearer " + window.Laravel.access_token;
       this.getUserTree();
    },
  watch: {
    doc_id(){
      this.shared.doc_id = this.doc_id[0];
    },
    id(){
        this.edit = false;
       // this.fetReminder(this.id);
    },
  },
      props: {
            title:"form.create_event",
            doc_id:"",
            module:"",
            id:"",


    //Mỗi đối tượng muốn sử dụng shared thì phải cài đặt API để nó gọi vào
    //page_url_shared:"api/shareds",
  },

  data () {
    return {
      shared:{
        object_ids:[],
        type:1
      },
      tree_users:[],
      errors:{},
      loading: false,
      edit: false,
      token:"",
      page_url_shared : "api/shareds",
      page_url_users:"api/user/all",
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.getUserTree();
  },
  methods: {
    reset(){
        this.shared.object_ids       = [];
        this.shared.type             = 1;

    },
    getUserTree(){

         var  page_url = this.page_url_users+"?type=tree_combobox"
          fetch(page_url,{
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
          }).then(res=>res.json())
          .then(data=>{
              this.tree_users = data.data;
          }) .catch(err => {
                    console.log(err);
                });

      },
    AddShared() {
           this.loading= true;
            var page_url = this.page_url_shared;
            this.shared.module = this.module;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.shared),
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

                       // console.log(data.data.shared);

                        toastr.success(this.$t('form.save_success'),"");
                      $("#createSharedModal").modal("hide");
                       this.$emit('fromShared',data.data.shared);

                        this.reset();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.shared.doc_id = this.doc_id[0];
              this.shared.module =this.module;
              //console.log(this.shared);
                fetch(page_url+"/"+this.shared.id, {
              method: "PUT",
              body: JSON.stringify(this.shared),
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
                         $("#createSharedModal").modal("hide");
                         this.$emit('fromShared',data.data.shared);
                  }else{
                      this.errors = data.data.errors;
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

<style>

</style>
