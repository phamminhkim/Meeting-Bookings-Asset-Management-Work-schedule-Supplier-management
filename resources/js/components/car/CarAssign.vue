<template>
<div class="modal fade" id="createAssignModal" tabindex="-1" role="dialog" aria-labelledby="createAssignModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
    <form action=""  @submit.prevent="Addassign()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createAssignModal"><i class="fa fa-info-circle" style="color: #17a2b8;"></i> {{$t('form.assign_user')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
       
				           <div class="input-group" style="padding-left: 10px; display:none;">
                    <ckeditor v-bind:config="editorConfig" 
                              v-bind:class="editorClass"
                              v-model="content"
                                ></ckeditor>
                    <!-- /btn-group -->
                  </div>
                <div class="form-group" style="padding: 10px;">
                 <treeselect  placeholder="Chọn người nhận" :disable-branch-nodes="true" :multiple="true" v-model="receiver_ids"    :options="tree_users" />
                <div class="form-group" v-show="false">
                  <label for=""> {{$t('form.type')}}</label>
                  <select name="" id="" v-model="type" class="form-control">
                      <option value="0">Group</option>
                      <option value="1" selected>User</option>
                  </select>
                </div>
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
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
components: {  Treeselect},
watch: {
      object_id(){
      this.object_id = this.object_id;
      this.edit = false;
    },
  },
props: {
          title:"form.create_assign",
          object_id:"",
           module:"",
            id:"",
  },
 data() {
   return {
          editorConfig: {
                height: '150px'
                },
      receiver_ids:[],
      type:4,
      content:"",
      tree_users:[],
      page_url_users:"api/user/all",
      page_url_shared  :"/api/shareds",
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
   };
 },
  created () {
        this.token = "Bearer " + window.Laravel.access_token;
         this.getUserTree();
  },
  methods: {
         reset(){
        this.content            ='';
        this.type             = '';
        this.receiver_ids =[];
    },
        getUserTree(){
         var  page_url = this.page_url_users+"?type=tree_combobox"
          //console.log(page_url);
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
      Addassign() {
           this.loading= true;
            var page_url = this.page_url_shared;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify({
              'doc_id':this.object_id[0],
              'module': this.module,
              'type':this.type,
              'object_ids':this.receiver_ids,
              'content':this.content}),
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

                       // console.log(data.data.comment);
                        toastr.success(this.$t('form.send_success'),"");
                        $("#createAssignModal").modal("hide");
                        this.$emit('getcar');
                        this.reset();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;
                       if(this.errors.receiver_ids)
                       toastr.error(this.errors.receiver_ids[0],"Thông báo");
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");
                      

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.object_id = this.object_id[0];
              this.module =this.module_comment;
              this.type = 4;
              if(this.receiver_ids){
                this.receiver_ids;
              }else{
                this.receiver_ids = null;
              }
             // console.log(this.comment);
                fetch(page_url+"/"+this.id, {
              method: "PUT",
              body: JSON.stringify({
              'doc_id':this.object_id[0],
              'module': this.module,
              'type':this.type,
              'object_ids':this.receiver_ids,
              'content':this.content}),
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
                         $("#createAssignModal").modal("hide");
                         this.$emit('getcar');
                         this.reset();
                  }else{
                      this.errors = data.data.errors;
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
    }
}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
