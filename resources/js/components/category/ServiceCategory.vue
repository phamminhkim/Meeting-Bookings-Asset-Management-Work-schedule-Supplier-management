<template>
<div>
       <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark">  {{$t(title)}}</h5>
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
                                 
                                <button  class="btn btn-info btn-sm" @click="showServiceCategory()"> <i class="fa fa-plus"></i>
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
                                        <th style="width: 50%;">{{$t('form.name')}}</th> 
                                        <th style="width: 30%;">{{$t('form.team')}}</th>                                      
                                        <th>action</th>
                                    </tr>
                                </thead>

                                <tbody id="tbbody_id">
                                    <tr
                                        v-for="(serviceCategory,index) in serviceCategorys"
                                        v-bind:key="serviceCategory.id"
                                    >
                                        <td>
                                            {{ serviceCategory.name }}
                                        </td>
                                      <td>
                                          <span v-for="(te,idx) in serviceCategory.teams" v-bind:key="idx"> {{ te.name }}</span>
                                           
                                        </td>
                                        <td>
                                            <div
                                                class="hidden-sm hidden-xs btn-group"
                                            >
                                                <button @click="editServiceCategory(serviceCategory,index)"
                                                        class="btn btn-xs  mr-3"  :title ="$t('form.edit')"> 
                                                    <i
                                                        class="fa fa-edit text-green bigger-120"
                                                    ></i>
                                                </button>

                                                <button  @click="assignTeamShow(serviceCategory,index)"
                                                    class="btn btn-xs   mr-3"  :title ="$t('form.assign_team')"
                                                >
                                                    <i
                                                        class="ace-icon text-blue fa fa-users bigger-120"
                                                    ></i>
                                                </button>
                                                <button @click="deleteServiceCategory(serviceCategory.id)"
                                                    class="btn btn-xs  "  :title ="$t('form.delete')"
                                                >
                                                    <i 
                                                        class="ace-icon text-red fa fa-trash bigger-120"
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
                                        @pagination-change-page="fetchServiceCategory" 
                                    
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
         <!-- ======= END HTML============ -->
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
                                <label> {{$t('form.service')}} </label>
                                :{{serviceCategory.name}}
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
        <!-- Modal add/edit -->
        <div class="modal fade" id="AddServiceCategory" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddServiceCategory" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} {{$t('form.service')}}</h4>
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
                                <label for="name"
                                    >{{$t('form.name')}}</label
                                >
                                <input
                                    v-model="serviceCategory.name"
                                    type="text"
                                    class="form-control"  v-bind:class="hasError('name')?'is-invalid':''"
                                    id="name"
                                    name="name"
                                    placeholder=""
                                    required
                                />
                                
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
    </div>  
</div>

</template>

<script>
 
 
// import Pagination from "../shared/Pagination.vue";

export default {
  props: {
      title:""
  },
    components: {
        // Pagination
    },
    data() {
        return {
            serviceCategorys: [],
            pagesNumber: [],
            serviceCategory: {
                id: "",
                name: "",
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
            errors:{},
            teams: {},
            team: {
                 id:"-1"
             },
            loading: false,
            edit: false,
            token:"",
            page_url_service_category : "/api/category/serviceCategories",
            page_url_team : "/api/category/teams",
            page_url_assign_service_to_team : "/api/category/assignServiceToTeam",
        };
    },
    created() {

        this.token = "Bearer " + window.Laravel.access_token;
            
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchTeam();
        this.fetchServiceCategory();
       
    },
    mounted() {

         
        // this.fetchServiceCategory(this.pagination.current_page);
      
    },

    methods: {
        fetchTeam(){
            var page_url = this.page_url_team;
            fetch(page_url,{headers:{Authorization:this.token}})
            .then(res=>res.json())
            .then(res=>{
                this.teams = res.data;
            }).catch(err=>console.log(err));
             
        },
        fetchServiceCategory(page) {
            //$("#tbbody_id").html('');

            this.loading = true;
            var page_url = this.page_url_service_category+ "?page=" + page;

            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.serviceCategorys = res.data.data;

                    this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err =>{
                    console.log(err);
                    this.loading = false;
                } );
            this.edit = false;
           
        },
        showServiceCategory(){
             this.reset();
             this.serviceCategory.active = 1;
              $("#AddServiceCategory").modal("show");
        },
        AddServiceCategory() {
             var page_url = this.page_url_service_category;// "/api/category/serviceCategorys";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.serviceCategory),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                         this.reset();
                        this.serviceCategorys.push(data.data);            
                       
                         this.showMessage(this.$t('form.message'),this.$t('form.save_success'));
                        $("#AddServiceCategory").modal("hide");
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.serviceCategory.id, {
                method: "PUT",
                body: JSON.stringify(this.serviceCategory),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.serviceCategorys[this.serviceCategory.index]=this.serviceCategory;     
                        this.$set(this.serviceCategorys, this.serviceCategory.index, {...this.serviceCategory});
           
                        this.showMessage(this.$t('form.message'),this.$t('form.update_success'));
                        $("#AddServiceCategory").modal("hide");
                       this.reset();
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }
           
            
        },
        editServiceCategory(serviceCategory,index){
           // var serviceCategory={..._obj};
             this.edit = true;
            this.serviceCategory.id          = serviceCategory.id;
            this.serviceCategory.name        = serviceCategory.name;
            this.serviceCategory.index        = index;

              $("#AddServiceCategory").modal("show");
        },
        assignTeamShow(serviceCategory,index){
            
            this.serviceCategory.id          = serviceCategory.id;
            this.serviceCategory.name        = serviceCategory.name;
            this.serviceCategory.teams        = serviceCategory.teams;
            this.serviceCategory.index       = index;
            this.team.id = "-1";
           
            if(serviceCategory.teams != null && serviceCategory.teams.length > 0){
                this.team = {...serviceCategory.teams[0]} ;
                // console.log(serviceCategory.teams[0]);
            }
            
           
            $("#AssignTeam").modal("show");
        },
        assignTeam(){
            var page_url = this.page_url_assign_service_to_team;
            fetch(page_url,{
                method:'post',
                headers:{
                    Authorization:this.token,
                    "content-type":"application/json"
                },
                body:JSON.stringify({'id':this.serviceCategory.id,'team_id':this.team.id}),
            }).then(res=>res.json())
            .then(data=>{
                
                if (!data.data.errors) {
                     
                     this.serviceCategory.teams=[];
                        for (var te of this.teams) {
                            if(te.id == this.team.id){
                                 
                                 this.serviceCategory.teams.push(te);
                            }
                        }
                        
                        this.$set(this.serviceCategorys, this.serviceCategory.index, {...this.serviceCategory});
                        if(this.team.id != "-1"){
                           
                            this.showMessage(this.$t('form.message'),this.$t('form.assign_success'));
                        }else{
                            this.showMessage(this.$t('form.message'),this.$t('form.cancel_assign_success'));
                        }
                        
                        $("#AddTeam").modal("hide");
                        //this.reset();
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
            }).catch($err=>console.log($err));
        },
        deleteServiceCategory(id){

            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_service_category}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                     this.showMessage(this.$t('form.message'),this.$t('form.delete_success'));
                    this.fetchServiceCategory();
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
        clearError(event){
             Vue.delete( this.errors,event.target.name);
         //  console.log(event.target.name);
        },
        reset(){
            this.clearAllError();
            this.edit = false;
          
            for(let field in this.serviceCategory){
                this.serviceCategory[field] = null;
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
    mounted() {
        console.log("Component mounted.");
    }
};

 
</script>
<style scoped>
/* fix khoảng cách bên dưới table so với phân trang */

.table {
    margin-bottom: 0px;
}
</style>
