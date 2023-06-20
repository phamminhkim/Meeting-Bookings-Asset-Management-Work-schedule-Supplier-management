<template>
<div>
       <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark">  {{title}}</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                  <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                </div>
               
            </div>   
         </div>
        </div>
      </div>
          <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('form.list')}}</h3>
                            <div class="card-tools">
                                 
                                <button  class="btn btn-info btn-sm" @click="showPayrequestType()"> <i class="fa fa-plus"></i>
                                     {{ $t('form.create')}}</button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                <table
                                id="simple-table"
                                class="table table-bordered table-striped"
                            >
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="min-width: 30px">{{$t('form.name')}}</th>
                                        <th>{{ $t('form.company') }}</th>
                                         <th style="width: 30%;">{{$t('form.team')}}</th>       
                                        <th>action</th>
                                    </tr>
                                </thead>

                                <tbody id="tbbody_id">
                                    <tr
                                        v-for="(payrequestType,index) in payrequestTypes"
                                        v-bind:key="payrequestType.id"
                                    >
                                        <td>
                                            {{ payrequestType.id }}
                                        </td>
                                        <td>{{ payrequestType.name }}</td>

                                        <td>{{ payrequestType.company_id }}</td>
                                        <td>
                                          <span v-for="(te,idx) in payrequestType.teams" v-bind:key="idx"> {{ te.name }}</span>
                                           
                                        </td>
                                        <td>
                                            <div
                                                class="hidden-sm hidden-xs btn-group"
                                            >
                                                <button   @click="editPayrequestType(payrequestType,index)"
                                                        class="btn btn-xs  mr-3">
                                                    <i
                                                        class="fa fa-edit text-green bigger-120"
                                                    ></i>
                                                </button>
                                                <button  @click="assignTeamShow(payrequestType,index)"
                                                    class="btn btn-xs   mr-3"  title ="Assign Team"
                                                >
                                                    <i
                                                        class="ace-icon text-blue fa fa-users bigger-120"
                                                    ></i>
                                                </button>
                                                <button  @click="deletePayrequestType(payrequestType.id)"
                                                    class="btn btn-xs  "
                                                >
                                                    <i
                                                        class="ace-icon fa text-red fa-trash bigger-120"
                                                    ></i>
                                                </button>
                                                
                                                 
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-5"></div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="float-right mt-3">
                                        <pagination 
                                        :data="pagination" 
                                        :show-disabled="true" 
                                        @pagination-change-page="fetchPayrequestType" 
                                    
                                        :limit="5"> </pagination>
                                    </div>
                                    
                                </div>
                               
                            </div>
                            
                        </div>
                        <!-- Loading (remove the following to stop the loading)-->

                        <loading :loading="loading"></loading>
                        <!-- end loading -->
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
 
        </div>

        <!-- Modal -->
        <div class="modal fade" id="AddPayrequestType" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="addPayrequestType" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} </h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="CompanyId">{{$t('form.company')}}</label>
                                <select
                                    name="company_id"
                                    class="form-control"
                                    v-bind:class="hasError('company_id')?'is-invalid':''"
                                    v-model="payrequestType.company_id"
                                    @change="clearError($event)"
                                >   
                                    <option
                                        v-for="company in companies"
                                        :key="company.id"
                                        v-bind:value="company.id"
                                    >
                                        {{ company.name }}
                                    </option>
                                </select>
                                 <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('company_id')}}</strong>
                                    
                                </span>
                            </div>
                           
                            <div class="form-group">
                                <label  
                                    >{{$t('form.name')}}</label
                                >
                                <input
                                    v-model="payrequestType.name"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('name')?'is-invalid':''"
                                    id="name"
                                    name="name"
                                    placeholder="" required/>
                                  <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('name')}}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button
                                type="button"
                                class="btn btn-default"
                                data-dismiss="modal"
                            >
                                {{ $t('form.back')}}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $t('form.save')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Modal assign team -->
        <div class="modal fade" id="AssignTeam" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="assignTeam" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.assign')}} {{$t('form.team')}}</h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                             
                            <div class="form-group">
                                <label> {{$t('form.name')}} </label>
                                :{{payrequestType.name}}
                            </div>
                            <div class="form-group">
                                <label for="team">{{$t('form.team')}}</label>
                                <select class="form-control" name="team_id" id="team_id" v-model="team.id">
                                    <option value="-1" selected>...</option>
                                    <option v-for="te in teams" v-bind:key="te.id" v-bind:value="te.id">{{te.name}}</option>
                                </select>
                               
                            </div>

                        
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button
                                type="button"
                                class="btn btn-default"
                                data-dismiss="modal"
                            >
                                {{ $t('form.back')}}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $t('form.save')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
 
export default {
  props: {
      title:""
  },
  
  data () {
    return {
           payrequestTypes: [],
            payrequestType: {
                id: "",
                name: "",
                company_id: "",
                index:"",
                teams:[]
            },
            companies: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            teams: {},
             team: {
                 id:"-1"
             },
            errors:{},
            loading: false,
            edit: false,
            token:"",
            page_url_payrequest_type : "/api/category/payrequest_types",
            page_url_company:"/api/category/companies",
            page_url_team : "/api/category/teams",
            page_url_assign_payrequest_type_to_team : "/api/category/assignPayrequestTypeToTeam",
    }
  },
  
  created () {
        this.token = "Bearer " + window.Laravel.access_token;
         this.fetchTeam();
        this.fetCompany();
        this.fetchPayrequestType();
  },
    methods: {
         fetchTeam(){
            var page_url = this.page_url_team;
            fetch(page_url,{headers:{Authorization:this.token}})
            .then(res=>res.json())
            .then(res=>{
                this.teams = res.data.data;
            }).catch(err=>console.log(err));
             
        },
       fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                  //  console.length(res);
                })
                .catch(err => {
                    console.log(err);

                    });
        },
        fetchPayrequestType(page) {
            //$("#tbbody_id").html('');
            this.loading = true;
            var page_url = this.page_url_payrequest_type+ "?page=" + page;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.payrequestTypes = res.data.data;
                    this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {
                     this.loading = false;
                    console.log(err);
                });
            this.edit = false;
           
        },
         showPayrequestType(){
             this.reset();
              $("#AddPayrequestType").modal("show");
        },
        addPayrequestType() {
             var page_url = this.page_url_payrequest_type;// "/api/category/payrequestTypes";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.payrequestType),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                         this.reset();
                        this.payrequestTypes.push(data.data);            
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#AddPayrequestType").modal("hide");
                       
                    }else{
                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.payrequestType.id, {
                method: "PUT",
                body: JSON.stringify(this.payrequestType),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.payrequestTypes[this.payrequestType.index]=this.payrequestType;     
                        this.$set(this.payrequestTypes, this.payrequestType.index, {...this.payrequestType});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#AddPayrequestType").modal("hide");
                       this.reset();
                    }else{
                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }
           
            
        },
        editPayrequestType(payrequestType,index){
           // var payrequestType={..._obj};
             this.edit = true;
            this.payrequestType.id          = payrequestType.id;
            this.payrequestType.company_id  = payrequestType.company_id;
          
            this.payrequestType.name        = payrequestType.name;
            this.payrequestType.teams        = payrequestType.teams;
            this.payrequestType.index        = index;
              $("#AddPayrequestType").modal("show");
        },
        deletePayrequestType(id){
            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_payrequest_type}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                    this.fetchPayrequestType();
                });
            }
        },
        assignTeamShow(payrequestType,index){
            
            this.payrequestType.id          = payrequestType.id;
            this.payrequestType.name        = payrequestType.name;
            this.payrequestType.company_id        = payrequestType.company_id;
            this.payrequestType.index       = index;
            this.team.id = "-1";
           
            if(payrequestType.teams != null && payrequestType.teams.length > 0){
                this.team = {...payrequestType.teams[0]} ;
                // console.log(payrequestType.teams[0]);
            }
            
           
            $("#AssignTeam").modal("show");
        },
         assignTeam(){
            var page_url = this.page_url_assign_payrequest_type_to_team;
            fetch(page_url,{
                method:'post',
                headers:{
                    Authorization:this.token,
                    "content-type":"application/json"
                },
                body:JSON.stringify({'id':this.payrequestType.id,'team_id':this.team.id}),
            }).then(res=>res.json())
            .then(data=>{
                
                if (!data.data.errors) {
                     
                     this.payrequestType.teams=[];
                        for (var te of this.teams) {
                            if(te.id == this.team.id){
                                 
                                 this.payrequestType.teams.push(te);
                            }
                        }
                        
                        this.$set(this.payrequestTypes, this.payrequestType.index, {...this.payrequestType});
                        if(this.team.id != "-1"){
                            this.showMessage('Thông báo','Gán thành công');
                        }else{
                            this.showMessage('Thông báo','Huỷ gán thành công');
                        }
                        
                        $("#AddTeam").modal("hide");
                        //this.reset();
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
            }).catch($err=>console.log($err));
        },
        hasError(fieldName){
            return (fieldName in this.errors);
        },
        getError(fieldName){
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];
            
        },
        clearError(event){
             Vue.delete( this.errors,event.target.name);
         //  console.log(event.target.name);
        },
        reset(){
            this.clearAllError();
            this.edit = false;
            for(let field in this.payrequestType){
                this.payrequestType[field] = null;
            }
        },
        clearAllError(){
            this.errors = {};
        },
        showMessage(title,message){
             if(!title)
                title = "Information";
             toastr.options = {
                positionClass: "toast-bottom-right"
                };
            
            toastr.success(message, title);
        }
  },
   computed: {
        hasAnyError(){
            return Object.keys(this.errors).length > 0;
        }
    },

}
</script>

<style>

</style>