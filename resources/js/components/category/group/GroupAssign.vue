<template>
  <div>
       <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"> Cấu hình Group</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                   <a class="btn  btn-default" href="/category/group" type="button">
                            <i class="ace-icon fa fa-close bigger-110"></i>
                            Đóng
                    </a>
                  <a href="#" @click.prevent="saveConfigGroup()" class="btn btn-primary" ><i class="fa fa-save"></i> Lưu</a>
                </div>
               
            </div>   
         </div>
        </div>
      </div>      
      <div class="card card-default">
            <!-- <div class="card-header">
              <h3 class="card-title">Cấu hình Group</h3>
             </div> -->
          
           <div class="form-horizontal">
               <div class="card-body">
                   <div class="form-group row">
                     
                      <label for="name" class="col-sm-3 col-form-label text-md-right ">Tên</label>
 
                        <div class="col-sm-9">
                        <input type="text" readonly  class="form-control col-xs-10 col-sm-6" id="name" v-model="group.name">
                        </div>
                             
                   </div>
                     <div class="space-4"></div>
                     <div class="clearfix">
                          <div class="col-md-9 offset-md-3 ">
                            
                            
                        </div>
                        
                    </div>
                       <hr>
                     <div class="form-group row">
          
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-5 col-sm-2">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">
                                           <i class="blue ace-icon fa fa-user bigger-110"></i>
                                        User
                                    </a>
                                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">
                                        
                                        Info
                                        
                                        </a>
                                   
                                    </div>
                                </div>
                                
                                <div class="col-7 col-sm-10">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                        <button type="button" v-on:click="popupUser()" class="btn btn-block btn-outline-secondary btn-sm col-2"   id="btnChonUser" >Chọn User</button>
                                         
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                            <th>Mã NV</th>
                                            <th>Tên</th>
                                           
                                              <!-- <th>Review</th> -->
                                            <th>Xóa</th>
                                            </thead>
                                            <tbody>
                                                 <tr v-for="(user,index) in group.users" v-bind:key="index">
                                                <td>{{user.username}}</td>
                                                <td>{{user.name}}</td>
                                              
                                                <!-- <td style="text-align:center">
                                                    <b-form-checkbox v-model="user.pivot.review" value="1" unchecked-value="0"></b-form-checkbox>
                                                </td> -->
                                                <td>
                                                    <button class="btn btn-xs" v-on:click.prevent="detachUser(user,index)"><i class="fa fa-trash text-red bigger-120 "></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                            
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                            Info
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                   </div>
               </div>
               
           </div>
      </div>
        <dialog-search v-on:selectedItem="selectedItem"></dialog-search>
        
  </div>
</template>

<script>
import DialogSearch from '../team/DialogSearch.vue';
 
export default {
   
   components:{
       DialogSearch,
   },
  props: {
     id:"",
      title:""
      
               
  },
    data(){
        return{
           
             group: {
                id: "",
                name: "",
                description: "",               
                active:"",
                index:"",
                users:[],
                users_del:[],
            },
            users:[],
            userSelected:[],
             errors:{},
            paginationdata: {},
            loading: false,
            edit: false,
            token:"",
            page_url_group : "/api/category/groups",
            page_url_assign_user :"api/category/assign_user_group",
            page_url_detach_user :"api/category/detach_user_group",
            

        }
    },
   created () {
       this.token = "Bearer " + window.Laravel.access_token;

      this.getGroup();
  },
  methods: {

    
      getGroup() {
             var page_url = this.page_url_group+"/"+this.id;// "/api/category/groups";
            if(this.edit === false){
                fetch(page_url, {
                method: "GET",               
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                     console.log(data.data);
                    if (!data.data.errors) {
                        this.group = data.data;
                        this.group.users_del=[];
                        //this.users = this.group.users;
                        //  this.reset();
                        // this.groups.push(data.data);            
                        // this.showMessage('Thông báo','Lưu thành công');
                        // $("#AddGroup").modal("hide");
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.group.id, {
                method: "PUT",
                body: JSON.stringify(this.group),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.groups[this.group.index]=this.group;     
                        this.$set(this.groups, this.group.index, {...this.group});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#AddGroup").modal("hide");
                       this.reset();
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }
           
            
        },
         popupUser(){
           
                $("#modal-user").modal("show");
         },
         selectedItem(data){
           
             this.userSelected = data;

             this.userSelected.forEach(u => {
                //  var isnew = this.users.filter(item=>{
                //      return (item.id == u.id)
                //  });
                //  if(isnew == ''){
                     
                      let user = {...u};
                      user.pivot = {
                          level:1,
                          group_id: this.group.id,
                          user_id:user.id
                      }
                     // this.saveConfigGroup(this.group.id,user.id,1);
                      this.group.users.push(user);
                //  }

             });
         },
        //  updateLevel(event,userId,groupId){
        //      this.saveConfigGroup(groupId,userId,event.target.value);
        //      console.log(event.target.value);
        //  },
         detachUser(item,index){
            this.$bvModal.msgBoxConfirm('Xác nhận xoá?',{
                title:"",
                size:'sm',
                buttonSize:"sm",
                okVariant:"danger",
                okTitle:"Ok",
                cancelTitle:"Cancel",
                footerClass:"p-2",
                centered:true
            }).then(value=>{
                if(value){

                     this.group.users_del.push({...item});
                     this.group.users.splice(index,1);
                }
            })

     
         },
         saveConfigGroup(){
             
             fetch(this.page_url_assign_user,{
                 method:'post',
                 body: JSON.stringify({'group': this.group}),
                 headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                     },
                
                }).then(res=>res.json())
                .then(data=>{
                //   toastr.options = {
                //        positionClass: "toast-bottom-right"
                //   }
                  toastr.success("Cập nhật thành công", 'Thông báo');
                }) .catch(err => console.log(err));
             
         },
       
  },
 
}
 
</script>

<style>

</style>