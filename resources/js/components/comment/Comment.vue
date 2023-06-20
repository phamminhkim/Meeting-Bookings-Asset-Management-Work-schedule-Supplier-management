<template>
<div>
<div class="card">
  <div class="card-body" >
	<ul class="comment-section">
      <div v-for="(comment, index) in this.datacomments"
                                                    v-bind:key="index">
			<li :class="comment.user.id != user_id?' comment user-comment':'comment author-comment'" >
          <div class="info">
               <a >{{comment.user.name}}</a>
                    <span>{{comment.created_at|ago}}</span>
                </div>
                <a class="avatar" >
                    <img v-bind:src="comment.user.avatar" width="35" v-bind:alt="comment.user.name" v-bind:title="comment.user.name" />
                </a>
               <p>
                    {{comment.content}}  
                    <span v-for="(file, index) in comment.other_attacheds"
                                                    v-bind:key="index"> 
                        <br/>
                      <a v-if="comment.other_attacheds.length!=0" href="#"  @click.prevent="downloadFile(file.attachment_file[index].id,file.attachment_file[index].name)">{{file.attachment_file[index].name}}</a>                          
                     </span>
                      <br/>
                      <br/>
                      <span style="padding-top:10px;">
                        <a v-if="comment.user.id == user_id" href="#"  @click.prevent="fetComment(comment.id)" class="link-black text-sm"><i class="fas fa-pencil-alt mr-1"></i> Sửa</a>
                        <a v-if="comment.user.id == user_id" href="#"  @click.prevent="Delcomment(comment.id)" class="link-black text-sm"><i class="fas fa-trash mr-1"></i> Xóa</a>
                        <a v-if="comment.user.id != user_id" href="#"  @click.prevent="ReplyComment(comment.id)" class="link-black text-sm"><i class="fas fa-reply mr-1"></i> Trả lời</a>
                        <span class="float-right">
                            <a href="#" class="link-black text-sm" @click.prevent="Addcommentvote(comment.id)">
                            <i class="far fa-thumbs-up mr-1"></i> 
                             </a>
                            <span class="dropup">
                               {{comment.comment_votes.length}}
                              <div class="dropup-content">
                                <span v-for="(vote, findex) in comment.comment_votes"
                                                    v-bind:key="findex">
                               <a href="#"><img v-bind:src="vote.user.avatar" width="20" v-bind:alt="vote.user.name" v-bind:title="vote.user.name" /> {{vote.user.name}}</a>
                               </span>
                            </div>
			  				
		                      	</span>
                        </span>
                        </span>
                  </p>
			</li>
      </div>
  </ul>
  <ul class="comment-section" style="padding: 0px;">
    <li class="write-new">
        <form action=""  @submit.prevent="Addcomment()" >
                        
          <div class="btn-group" style="margin-bottom: 10px;">
            <ul class="fc-color-picker" id="color-chooser">
            <li>
            <a class="text-primary" title="chọn file"   v-on:click="handleClickInputFile(index)"><i class="fas fa-paperclip fa-1x	"></i></a>
             <div class="d-flex justify-content-between">
              <ul class="list-unstyled">
              <li v-for="(file,index) in comment.attachment_file" v-bind:key="index" class="itemfile">
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
                   <div class="d-flex justify-content-between" v-for="(other_attached,index) in comment.other_attacheds" v-bind:key="index">
                     <ul class="list-unstyled">
                    <li v-for="(file,findex) in other_attached.attachment_file" v-bind:key="findex" class="itemfile">
                     <div class="btn-group">
                      <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                      <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFileedit(file,index,findex)" ><i class="far fa-times-circle "></i></button>                          
                
                       </div>
                      </li>
                       </ul>
                   </div> </li>
                     
                    <!--   <li><a class="text-warning" href="#"><i class="fas fa-laugh-beam"></i></a></li> -->
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <div class="input-group">
                    <textarea placeholder="Viết bình luận tại đây..." v-model="comment.content" ></textarea>
                    <!-- /btn-group -->
                  </div>
                    <div>
                      <button type="submit" class="button-send" style="background-color:  #1B6AAA; color: #ffffff;">{{$t('form.send')}}</button>
                    </div>
                  <!-- /input-group -->
                </form>
            </li>
		        </ul>
        </div>

</div>
 <div class="modal fade" id="createReplyCommentModal" tabindex="-1" role="dialog" aria-labelledby="createReplyCommentModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
    <div class="modal-content">
    <ul class="comment-section" style="padding: 0px;">
    <li class="write-new">
          <form action=""  @submit.prevent="AddReplyComment()">
          <div class="btn-group" style="margin-bottom: 10px;">
          <ul class="fc-color-picker" id="color-chooser">
            <li>
            <a class="text-primary" title="chọn file"   v-on:click="handleClickInputFilereply(index)"><i class="fas fa-paperclip fa-1x	"></i></a>
             <div class="d-flex justify-content-between">
              <ul class="list-unstyled">
              <li v-for="(file,index) in reply.attachment_file" v-bind:key="index" class="itemfile">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                  <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFilereply(file,index)" ><i class="far fa-times-circle "></i></button>                          
                  <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>                          
                </div>
                 </li>      
                 </ul>
                  <input type="file"
                                        accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" 
                                          @input="emitEvent_voucher_reply($event)"
                                          @change="emitEvent_voucher_reply($event)"
                                          id="ThemFileVoucherreply"
                                          style="display:none"
                                          ref="fileVoucherreply"
                                          multiple
                                  
                                  >        
                  </div>
                   <div class="d-flex justify-content-between" v-for="(other_attached,index) in reply.other_attacheds" v-bind:key="index">
                     <ul class="list-unstyled">
                    <li v-for="(file,findex) in other_attached.attachment_file" v-bind:key="findex" class="itemfile">
                     <div class="btn-group">
                      <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                       </div>
                      </li>
                       </ul>
                   </div> </li>
                     
                    <!--   <li><a class="text-warning" href="#"><i class="fas fa-laugh-beam"></i></a></li> -->
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <div class="input-group">
                    <textarea placeholder="Viết phản hồi tại đây..." v-model="reply.content" ></textarea>
                    <!-- /btn-group -->
                  </div>
                    <div>
                      <button type="submit" class="button-send" style="background-color:  #1B6AAA; color: #ffffff;">{{$t('form.send')}}</button>
                    </div>
                  <!-- /input-group -->
                </form>
            </li>
		        </ul>
    </div>
  
  </div>
</div> 
</div>
</template>
<script>
export default {
  watch: {
    object_id(){
      this.comment.object_id = this.object_id[0];
    },
   datacomments(){
          this.datacomments =  this.datacomments;
    },
    id(){
        this.edit = false;
        this.fetComment(this.id);
    },
  },
props: {
            object_id:"",
            module:"",
            id:"",
            datacomments:Array,
            user_id: String,
            get_info_approved: Array
  },
  data () {
    return {
      index:'',
      comment:{
        content:"",
        object_id: this.object_id[0],
        module :this.module,
        type_id:1,
        attachment_file:[],
        attachment_file_removed:[]
      },
       reply:{
        parent_id:"",
        content:"",
        object_id: this.object_id[0],
        module :this.module,
        type_id:1,
        attachment_file:[],
        attachment_file_removed:[]
      },
      commentvote:{
        vote:1,
      },
      errors:{},
      loading: false,
      edit: false,
      token:"",
      page_url_comment : "api/comments",
      page_url_commentvote : "api/commentvotes",
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.fetComment('');

  },
  methods: {
       reset(){
        this.comment.content             = "";
        this.comment.object_id           = this.object_id[0];
        this.comment.module              =this.module;
        this.comment.type_id             =1;
        this.comment.attachment_file = [];
        this.comment.attachment_file_removed =[];
        this.comment.other_attacheds = [];
        this.comment.id             ="";
    },
     resetreply(){
        this.reply.content             = "";
        this.reply.object_id           = this.object_id[0];
        this.reply.module              =this.module;
        this.reply.type_id             =1;
        this.reply.attachment_file = [];
        this.reply.attachment_file_removed =[];
        this.reply.other_attacheds = [];
        this.reply.id             ="";
    },
    fetComment(id){
       this.reset();
       //this.reminder = {};
       //this.reminder.type_id = 1;
     // console.log('11111');
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_comment+"/"+id;
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
                          this.comment = res.data;
                          //console.log(this.comment);
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
    Addcomment() {
           this.loading= true;
            var page_url = this.page_url_comment;
             this.comment.object_id = this.object_id[0];
             this.comment.module = this.module;
            
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.comment),
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
                        toastr.success(this.$t('form.save_success'),"");
                        this.$emit('getcar');
                        this.reset();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.comment.object_id = this.object_id[0];
              this.comment.module =this.module;
             // console.log(this.comment);
                fetch(page_url+"/"+this.comment.id, {
              method: "PUT",
              body: JSON.stringify(this.comment),
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
                         $("#createEventModal").modal("hide");
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
      Delcomment(obj){
          var page_url = this.page_url_comment+"/"+obj;
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
                        body:JSON.stringify({'module':this.module,'object_id':this.object_id[0],'id':obj}),
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
                                    this.$emit('getcar');
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
     Addcommentvote(obj) {
           this.loading= true;
            var page_url = this.page_url_commentvote;
             this.commentvote.object_id = this.object_id[0];
             this.commentvote.module = this.module;
             this.commentvote.comment_id = obj;
             //console.log(obj);
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.commentvote),
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
                        toastr.success(this.$t('form.save_success'),"");
                        this.$emit('getcar');
                        this.reset();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.comment.object_id = this.object_id[0];
              this.comment.module =this.module;
              this.commentvote.id = obj;
             // console.log(this.comment);
                fetch(page_url+"/"+this.commentvote.id, {
              method: "PUT",
              body: JSON.stringify(this.commentvote),
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
                         $("#createEventModal").modal("hide");
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
      ReplyComment(parent_id){
          $("#createReplyCommentModal").modal("show");
          this.reply.parent_id = parent_id;
      },
      AddReplyComment() {
           this.loading= true;
            var page_url = this.page_url_comment;
             this.reply.object_id = this.object_id[0];
             this.reply.module = this.module;
            
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.reply),
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
                        toastr.success(this.$t('form.save_success'),"");
                        $("#createReplyCommentModal").modal("hide");
                        this.$emit('getcar');
                        this.resetreply();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;
                       if(this.errors.content)
                       toastr.error(this.errors.content[0],"Thông báo");

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.reply.object_id = this.object_id[0];
              this.reply.module =this.module;
             // console.log(this.comment);
                fetch(page_url+"/"+this.reply.id, {
              method: "PUT",
              body: JSON.stringify(this.reply),
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
                         $("#createReplyCommentModal").modal("hide");
                         this.$emit('getcar');
                         this.resetreply();
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
                 this.comment.attachment_file.push({...docs});

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
                this.comment.attachment_file_removed.push({...item});
                this.comment.attachment_file.splice(index,1);
            }
        },
       deleteFileedit(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){
              //  console.log("index="+index);
                this.comment.other_attacheds[index].attachment_file_removed.push({...item});
                this.comment.other_attacheds[index].attachment_file.splice(findex,1);
            }
        },
        emitEvent_voucher_reply(event) {
            
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
                 this.reply.attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';
           
        },
       handleClickInputFilereply(index) {
            this.$refs.fileVoucherreply.click();
        }, 
        deleteFilereply(item,index){
           if(confirm(this.$t('form.confirm_delete') +'?')){
                
              //  console.log("index="+index);
               

                this.reply.attachment_file_removed.push({...item});
                this.reply.attachment_file.splice(index,1);
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
<style  lang="scss" scoped>


.comment-section{
    list-style: none;
    width: 100%;
    padding: 10px;
}

.comment{
    display: flex;
    border-radius: 3px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.comment.user-comment{
    color:  #808080;
}

.comment.author-comment{
    color:  #60686d;
    justify-content: flex-end;
}

/* User and time info */

.comment .info{
    width: 17%;
}

.comment.user-comment .info{
    text-align: right;
}

.comment.author-comment .info{
    order: 3;
}


.comment .info a{	/* User name */
    display: block;
    text-decoration: none;
    color: #656c71;
    font-weight: bold;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    padding: 10px 0 3px 0;
}

.comment .info span{	/* Time */
    font-size: 11px;
    color:  #9ca7af;
}


/* The user avatar */

.comment.user-comment .avatar{
    padding: 10px 18px 0 3px;
}

.comment.author-comment .avatar{
    order: 2;
    padding: 10px 3px 0 18px;
}

.comment .avatar img{
    display: block;
    border-radius: 50%;
}

.comment.user-comment .avatar img{
    float: right;
}





/* The comment text */

.comment p{
    line-height: 1.5;
    padding: 18px 22px;
    width: 70%;
    position: relative;
    word-wrap: break-word;
    text-align: justify;
}

.comment.user-comment p{
    background-color:  #f3f3f3;
    border-radius: 10px;
}

.comment.author-comment p{
    background-color:  #e2f8ff;
    order: 1;
    border-radius: 10px;
    
}

.user-comment p:after{
    content: '';
    position: absolute;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 2px solid #f3f3f3;
    left: -8px;
    top: 18px;
}

.author-comment p:after{
    content: '';
    position: absolute;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 2px solid #e2f8ff;
    right: -8px;
    top: 18px;
}




/* Comment form */

.write-new{
    margin: 10px auto 0;
    width: 100%;
}

.write-new textarea{
    color:  #444;
    font: inherit;

    outline: 0;
    border-radius: 3px;
    border: 1px solid #cecece;
    background-color:  #fefefe;
    box-shadow: 1px 2px 1px 0 rgba(0, 0, 0, 0.06);
    overflow: auto;

    width:100%;
    min-height: 80px;
    padding: 15px 20px;
}

.write-new img{
    border-radius: 50%;
    margin-top: 15px;
}

.write-new {
    float:right;
   
    border-radius: 2px;
    border: 0;
  
    cursor: pointer;

    padding: 10px 25px;
    margin-top: 18px;
}
.button-send{
    float:right;
    box-shadow: 1px 2px 1px 0 rgba(0, 0, 0, 0.12);
    border-radius: 2px;
    border: 0;
    font-weight: bold;
    cursor: pointer;

    padding: 10px 25px;
    margin-top: 18px;
}


/* Responsive styles */

@media (max-width: 800px){
    /* Make the paragraph in the comments take up the whole width,
    forcing the avatar and user info to wrap to the next line*/
    .comment p{
        width: 100%;
    }

    /* Reverse the order of elements in the user comments,
    so that the avatar and info appear after the text. */
    .comment.user-comment .info{
        order: 3;
        text-align: left;
    }

    .comment.user-comment .avatar{
        order: 2;
    }

    .comment.user-comment p{
        order: 1;
    }


    /* Align toward the beginning of the container (to the left)
    all the elements inside the author comments. */
    .comment.author-comment{
        justify-content: flex-start;
    }


    .comment-section{
        margin-top: 10px;
    }

    .comment .info{
        width: auto;
    }

    .comment .info a{
        padding-top: 15px;
    }

    .comment.user-comment .avatar,
    .comment.author-comment .avatar{
        padding: 15px 10px 0 18px;
        width: auto;
    }

    .comment.user-comment p:after,
    .comment.author-comment p:after{
        width: 12px;
        height: 12px;
        top: initial;
        left: 28px;
        bottom: -6px;
    }

    .write-new{
        width: 100%;
    }
}



/* ------- Demo footer. Please ignore and remove ------- */

footer {
    font: normal 16px Arial, Helvetica, sans-serif;
    padding: 15px 35px;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #1B1F22;
    box-shadow: 0 -1px 1px rgba(0, 0, 0, 0.2);
    z-index: 1;
    text-align: left;
}

footer a.tz{
    font-weight: normal;
    font-size: 16px !important;
    text-decoration: none !important;
    display: block;
    margin-right: 300px;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #CCC !important;
    position: relative;
    padding-left: 140px;
}

footer a.tz:before{
    font-weight: bold;
    color: #FFF;
    content: 'tutorial';
    position: absolute;
    text-align: right;
    width: 100px;
    left: -20px;
}

footer a.tz:after{
    content: 'zine';
    position: absolute;
    font-weight: bold;
    color: #DA431C;
    left: 80px;
}


@media (max-width: 1024px) {
    footer{ display:none;}
}
/* Dropup content (Hidden by Default) */
.dropup-content {
  display: none;
  position: absolute;
  bottom: 30px;
  left:5px;
  background-color: #ffffff;
  min-width: 220px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1111;
  border-radius:7px;
}

/* Links inside the dropup */
.dropup-content a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
  display: block;
  font-size: 13px;

}


/* Change color of dropup links on hover */
.dropup-content a:hover {background-color: #ddd}

/* Show the dropup menu on hover */
.dropup:hover .dropup-content {
  display: block;
}
</style>