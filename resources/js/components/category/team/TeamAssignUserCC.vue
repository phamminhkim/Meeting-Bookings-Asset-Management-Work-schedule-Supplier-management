<template>
  <div>
       <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark">{{$t('form.config_team')}} - User CC</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                   <a class="btn  btn-default" href="javascript:history.back()"   type="button">
                            <i class="ace-icon fa fa-close bigger-110"></i>
                            {{$t('form.close')}}
                    </a>
                  <a href="#" @click.prevent="saveConfigTeamUserCC()" class="btn btn-primary" ><i class="fa fa-save"></i>  {{$t('form.save')}}</a>
                </div>
               
            </div>   
         </div>
        </div>
      </div>      
      <div class="card card-default">
            <!-- <div class="card-header">
              <h3 class="card-title">Cấu hình Team</h3>
             </div> -->
          
           <div class="form-horizontal">
               <div class="card-body">
                   <div class="form-group row">
                     
                      <label for="name" class="col-sm-3 col-form-label text-md-right "> {{$t('form.name')}}</label>
 
                        <div class="col-sm-9">
                        <input type="text" readonly  class="form-control col-xs-10 col-sm-6" id="name" v-model="team.name">
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
                                        <button type="button" v-on:click="popupUser()" class="btn btn-block btn-outline-secondary btn-sm col-2"   id="btnChonUser" >{{$t('form.add')}} User</button>
                                         
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                            <th>{{$t('form.employee_code')}}</th>
                                            <th>{{$t('form.employee_name')}}</th>
                                           
                                            <th>{{$t('form.delete')}}</th>
                                            </thead>
                                            <tbody>
                                                 <tr v-for="(user,index) in team.userccs" v-bind:key="index">
                                                <td>{{user.username}}</td>
                                                <td>{{user.name}}</td>
                                               
                                                <td>
                                                    <button class="btn btn-xs" v-on:click.prevent="detachUserCC(user,index)"><i class="fa fa-trash text-red bigger-120 "></i></button></td>
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
        <dialogSearch v-on:selectedItem="selectedItem"></dialogSearch>
  </div>
</template>

<script>
import dialogSearch from "./DialogSearch.vue";
export default {
   
   components:{
       dialogSearch,
   },
  props: {
      id:"",
      title:""
      
               
  },
    data(){
        return{
            steps:[],
             team: {
                id: "",
                name: "",
                description: "",               
                active:"",
                index:"",
                users:[],
                users_del:[],
                userccs:[],
                userccs_del:[],
            },
            users:[],
            userSelected:[],
             errors:{},
            paginationdata: {},
            loading: false,
            edit: false,
            token:"",
            page_url_team : "/api/category/teams",
            page_url_assign_user_cc :"api/category/assign_usercc_team",
            page_url_detach_user_cc :"api/category/detach_usercc_team",
             page_url_step:"api/category/steps",

        }
    },
   created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.get_step();
      this.getTeam();
  },
  methods: {

      get_step(){
        this.loading =true;
        var page_url = this.page_url_step;
        fetch(page_url,{
            method:'get',
            headers:{
                Authorization:this.token,
                "content-type": "application/json"
            }
        }).then(res=>res.json())
        .then(data=>{
              this.loading =false;
              console.log(data);
              if(data.success== 1){
                 var  obj={}
                 data.data.forEach(element => {
                        obj ={};
                        obj.value=element.id;
                        obj.text=element.name;
                        this.steps.push({...obj});
                 });
                         
              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });
       
      },
      getTeam() {
             var page_url = this.page_url_team+"/"+this.id;// "/api/category/teams";
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
                        this.team = data.data;
                        this.team.userccs_del=[];
                        //this.users = this.team.users;
                        //  this.reset();
                        // this.teams.push(data.data);            
                        // this.showMessage('Thông báo','Lưu thành công');
                        // $("#AddTeam").modal("hide");
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.team.id, {
                method: "PUT",
                body: JSON.stringify(this.team),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.teams[this.team.index]=this.team;     
                        this.$set(this.teams, this.team.index, {...this.team});
                        this.showMessage(this.$t('form.message'),this.$t('form.update_sucess'));
                        $("#AddTeam").modal("hide");
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
                         // level:1,
                          team_id: this.team.id,
                          user_id:user.id
                      }
                     // this.saveConfigTeamUserCC(this.team.id,user.id,1);
                      this.team.userccs.push(user);
                //  }

             });
         },
        //  updateLevel(event,userId,teamId){
        //      this.saveConfigTeamUserCC(teamId,userId,event.target.value);
        //      console.log(event.target.value);
        //  },
         detachUserCC(item,index){
            this.$bvModal.msgBoxConfirm( this.$t('form.confirm_delete') +'?',{
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

                     this.team.userccs_del.push({...item});
                     this.team.userccs.splice(index,1);
                }
            })

     
         },
         saveConfigTeamUserCC(){
             
             fetch(this.page_url_assign_user_cc,{
                 method:'post',
                 body: JSON.stringify({'team': this.team}),
                 headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                     },
                
                }).then(res=>res.json())
                .then(data=>{
                  toastr.options = {
                       positionClass: "toast-bottom-right"
                  }
                  toastr.success(this.$t('form.update_sucess'), this.$t('form.message'));
                }) .catch(err => console.log(err));
             
         },
       
  },
 
}
 
</script>

<style>

</style>