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
                  <button  class="btn btn-info btn-sm" @click="showGroup()"> <i class="fa fa-plus"></i>
                                     {{ $t('form.create')}}</button>
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
                      <div class="card-body">
                        <div class="row mt-0">
                   
                            <div class="col-md-9">
                                <div class="form-group row">
                              
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                                    <span v-if="!show_search">{{$t('form.show_search')}}</span> 
                                    <span v-if="show_search">{{$t('form.hide_search')}}</span>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs " @click="showSearch()" >
                                    <i v-if="show_search" class="fas fa-angle-up"></i>
                                    <i v-else class="fas fa-angle-down"></i>
                                    </button>
                                </div>
                                
                                </div>
                                
                            </div>
                            <div class="col-md-3">
                                <div class="row">

                                </div>

                            
                            </div>
                        </div>   
                         <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                            <div class="col-md-12 ">
                                <div class="form-group row">
                                   <label  class="col-form-label-sm col-md-1 " style="text-align:left" title="Mã nhân viên" for="">Mã NV</label>
                                    <div class="col-md-3">
                                    <input type="text" v-model="form_filter.manv" class="form-control form-control-sm mt-1">
                                    </div>
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Tên NV</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="form_filter.tennv"   class="form-control form-control-sm mt-1">
                                    </div>
                                    <label  class="col-form-label-sm  col-md-1" style="text-align:left" for="">Trạng thái</label>
                                    <div class="col-md-3">
                                    <select name="" v-model="form_filter.active" id="" class="form-control form-control-sm mt-1">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    
                                    
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Công ty</label>
                                    <div class="col-md-3">
                                    <select name="" id="" v-model="form_filter.company_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="company in companies"
                                                :key="company.id"
                                                v-bind:value="company.id"
                                            >
                                                {{ company.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    <label  class="col-form-label-sm    col-md-1" style="text-align:left" for="id_bophan">Bộ phận &nbsp; &nbsp; &nbsp; </label>
                                    <div class="col-md-3">
                                    <select name="" id="id_bophan" v-model="form_filter.department_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="department in department_filter"
                                            :key="department.id"
                                            v-bind:value="department.id"
                                            >
                                            {{ department.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    
                                     <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning btn-sm mt-1" @click="filter_data()"> <i class="fa fa-search"></i> Tìm </button>
                                    <button type="reset" class="btn btn-secondary btn-sm mt-1" @click="clearFilter()"> <i class="fa fa-reset"></i> Clear</button>
                                    
                                    </div>
                                </div>
                                 
                            </div>
            
                            
                        </div>  
     
                        <div class="row mt-1 " style="background-color:#F4F6F9">
                            
                            <div class="col-md-9">
                                
                                
                                <button type="button" class="btn btn-success btn-sm"  @click="createTeamForGroup()">
                                    <i class="fas fa-plus"></i>Tạo team duyệt cho group</button>
                                <!-- <div class="form-group row">
                                <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editRequest()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                                <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button>
                                
                                </div> -->
                                
                            </div>
                            <div class="col-md-3">
                                <div class="row mt-1">
                                <div class="input-group input-group-sm  col-12">
                                
                                                        <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter" placeholder="Nhập thông tin tìm kiếm.." aria-label="Search">
                                <span class="input-group-append">
                                    <button type="button"  class="btn btn-default btn-flat mb-1"><i class="fas fa-search"></i></button>
                                    <!-- <button type="button" @click="searchContact()" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button> -->
                                </span>
                                    <!-- <download-excel
                                    :data   = "contracts" title="Eport Excel"  class="ml-2" >
                                    <span style="cursor: pointer;"><i class="fa fa-download"></i></span>
                                </download-excel> -->
                                </div>
                                </div>
                            
                                
                            
                            </div>
                        </div>
                         <div class="row">
                            <b-table
                                striped hover responsive :bordered="true" head-variant="light"  :sticky-header="false"   small  
                                :items="groups"
                                :current-page="current_page"
                                :per-page="per_page" 
                                :filter="filter"
                                :fields="fields"
                                 selectable
                                 ref="selectableTable"
                            >
                            <template #head(selected)>
                              <!-- {{selected}} -->
                            <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"     ></b-form-checkbox>
                            </template>
                            
                           <template  v-slot:cell(selected)="data"   >
                               <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>  
                           </template> 
                           <template #cell(company_id)="data">
                               <span>{{company_name(data.value)}}</span>
                           </template>
                             <template #cell(department_id)="data">
                               <span>{{department_name(data.value)}}</span>
                           </template>
                           <template #cell(workshop_id)="data">
                               <span>{{workshop_name(data.value)}}</span>
                           </template>
                           <template #cell(party_id)="data">
                               <span>{{party_name(data.value)}}</span>
                           </template>
                           <template #cell(active)="data">
                                  <span class="badge bg-success" v-if="data.item.active == 1">{{$t('form.active')}}</span>
                                  <span class="badge bg-warning" v-if="data.item.active == 0">{{$t('form.inactive')}}</span>
                           </template>

                           <template #cell(action)="data">
                               <div
                                    class="hidden-sm hidden-xs btn-group"
                                >
                                    <button @click="editGroup(data.item)"
                                            class="btn btn-xs  mr-3"  title ="Edit">
                                        <i
                                            class="fa fa-edit  text-green bigger-120"
                                        ></i>
                                    </button>
                                        <a v-bind:href="assignUserShow(data.item)"  
                                        class="btn btn-xs   mr-3"  title ="Assign User"
                                    >
                                        <i
                                            class="ace-icon text-blue fa fa-users bigger-120"
                                        ></i>
                                        <span v-if="data.item.users_count>0" class="badge badge-danger navbar-badge">{{data.item.users_count}}</span>
                                        <span v-else  class="badge badge-default navbar-badge">&nbsp;</span>
                                    </a>
                                    <a v-if="data.item.teamID > 0" v-bind:href="assignTeamShow(data.item)"  
                                        class="btn btn-xs   mr-3"  title ="Approve Team"
                                    >
                                        <i
                                            class="ace-icon text-blue fa fa-cog bigger-120"
                                        ></i>
                                        <span v-if="data.item.teamCount>0" class="badge badge-danger navbar-badge">{{data.item.teamCount}}</span>
                                        <span v-else  class="badge badge-default navbar-badge">&nbsp;</span>
                                    </a>
                                    <button @click="deleteGroup(data.item.id)"
                                        class="btn btn-xs "  title ="Delete"
                                    >
                                        <i
                                            class="ace-icon text-red fa fa-trash bigger-120"
                                        ></i>
                                    </button>
                                    
                                        
                                </div>
                           </template>
                            </b-table>
                           <div class="row"   >
                             <div class="col-md-12" >
                              <div class="form-group row">
                                <label  class="col-form-label-sm col-md-4" style="text-align:left" for="">Per page:</label>
                                    <div class="col-md-3">
                                        <b-form-select 
                                            size="sm"
                                            v-model="per_page"
                                            :options="pageOptions"
                                        
                                        ></b-form-select>
                                    </div>
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for=""></label>
                                    <div class="col-md-3">
                                        <b-pagination
                                        v-model="current_page"
                                        :total-rows="rows"
                                        :per-page="per_page"
                                        align="fill"
                                        size="sm"
                                        class="my-0"
                                      ></b-pagination>
                                    </div>
                                </div>
                                </div>
                            </div>  

                         </div>

                        </div>
                        <!-- Loading (remove the following to stop the loading)-->

                        <div class="center overlay" v-if="loading">
                            <i
                                class="fa fa-spinner fa-spin fa-4x fa-fw gray center"
                            ></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <!-- end loading -->
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
 
        </div>

        <!-- Modal -->
        <div class="modal fade" id="AddGroup" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddGroup" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} Group </h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                                   @click.prevent="clearAllError()"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                 
                             <div class="form-group">
                                <label for="company_id" class="col-form-label-sm"
                                    >{{$t('form.company')}}</label >
                                    <select id="company_id" class="form-control form-control-sm" v-model="group.company_id"  v-bind:class="hasError('company_id')?'is-invalid':''">

                                        <option v-for="company in companies" v-bind:key="company.id" v-bind:value="company.id"
                                        >
                                            {{ company.name }}
                                        </option>
                                        <option value=""></option>
                                    </select>
                                    <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('company_id')}}</strong>
                                    </span>
                            </div>                             
                             <div class="form-group">
                                <label for="department_id"  class="col-form-label-sm"
                                    >{{$t('form.department')}}</label>
                                    <select id="department_id" class="form-control form-control-sm" v-model="group.department_id"  v-bind:class="hasError('department_id')?'is-invalid':''">
                                        <option v-for="department in department_filter_form" v-bind:key="department.id" v-bind:value="department.id" >
                                            {{department.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('department_id')}}</strong>
                                    </span>
                             </div> 
                             <div class="form-group">
                                <label for="workshop_id"  class="col-form-label-sm"
                                    >{{$t('form.workshop')}}</label>
                                    <select id="workshop_id" class="form-control form-control-sm" v-model="group.workshop_id"  v-bind:class="hasError('workshop_id')?'is-invalid':''">
                                        <option v-for="workshop in workshop_filter_form" v-bind:key="workshop.id" v-bind:value="workshop.id" >
                                            {{workshop.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('workshop_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('workshop_id')}}</strong>
                                    </span>
                             </div> 
                             <div class="form-group">
                                <label for="party_id"  class="col-form-label-sm"
                                    >{{$t('form.party')}}</label>
                                    <select id="party_id" class="form-control form-control-sm" v-model="group.party_id"  v-bind:class="hasError('party_id')?'is-invalid':''">
                                        <option v-for="party in party_filter_form" v-bind:key="party.id" v-bind:value="party.id" >
                                            {{party.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('party_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('party_id')}}</strong>
                                    </span>
                             </div> 
                            <div class="form-group">
                                <label for="name"  class="col-form-label-sm"
                                    >{{$t('form.code')}}</label
                                >
                                <input
                                    v-model="group.name"
                                    type="text"
                                    class="form-control form-control-sm"  v-bind:class="hasError('name')?'is-invalid':''"
                                    id="name"
                                    name="name"
                                    placeholder=""
                                    
                                    required
                                />
                                
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('name')}}</strong>
                                    
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="description"  class="col-form-label-sm"
                                    >{{$t('form.description')}}</label
                                >
                                <textarea
                                    v-model="group.description"
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-bind:class="hasError('description')?'is-invalid':''"
                                    id="description"
                                    name="description"
                                    placeholder="" required/>
                                  <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('description')}}</strong>
                                </span>
                            </div>
                            
                                           
                            <div class="form-group">
                                <label for="active"   class="col-form-label-sm"
                                    >{{$t('form.active')}}</label
                                >
                              
                                <select class="form-control form-control-sm" v-model="group.active" name="active" id="active">
                                    <option value="1" selected >{{$t('form.active')}}</option>
                                    <option value="0">{{$t('form.inactive')}}</option>
                                </select>
									
								 
                                  <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('level')}}</strong>
                                </span>
                            </div>
                    
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button
                                type="button"
                                class="btn btn-default"
                                data-dismiss="modal"
                                 @click.prevent="clearAllError()"
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
            groups: [],
            pagesNumber: [],
            companies:[],
            departments:[],
            workshops: [],
            parties: [],
            group: {
                id: "",
                name: "",
                description: "",               
                active:"",
                company_id:"",
                department_id:"",
                workshop_id:"",
                party_id:"",
                index:""
            },
          
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            filter:"",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors:{},
           
            loading: false,
            edit: false,
            token:"",
            page_url_group : "/api/category/groups",
            page_url_department : "/api/category/departments",
            page_url_workshop : "/api/category/workshops",
            page_url_party : "/api/category/parties",
            page_url_company:"/api/category/companies",
            show_search:false,
            form_filter:{
                manv:"",
                tennv:"",
                active:"",
                company_id:"",
                department_id:""
            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label:'Mã',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'description',
                    label:'Mô tả',
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'company_id',
                    label:'Công ty',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'department_id',
                    label:'Phòng ban',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'workshop_id',
                    label:'Xưởng SX',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'party_id',
                    label:'Tổ SX',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'active',
                    label:'Trạng thái',
                    sortable: true,
                    stickyColumn: true
                },

                {
                    key: 'action',
                    label:'Hành động',
                    sortable: true,
                    stickyColumn: true
                },
                 
            ],
            selected:[],
            selectAll: false,
        };
    },
    created() {

        this.token = "Bearer " + window.Laravel.access_token;
            
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetCompany();
        this.fetDepartment();
        this.fetWorkshop();
        this.fetParty();
        this.fetchGroup();
       
    },
    mounted() {

         
        // this.fetchGroup(this.pagination.current_page);
      
    },

    methods: {
        fetCompany() {
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
         fetDepartment() {
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },
        fetWorkshop() {
            var page_url = this.page_url_workshop;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.workshops = res.data;
                })
                .catch(err => console.log(err));
        },
        fetParty() {
            var page_url = this.page_url_party;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.parties = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchGroup() {
            //$("#tbbody_id").html('');

            this.loading = true;

            this.groups = Array();
              const params = new URLSearchParams({
                manv:this.form_filter.manv,
                tennv:this.form_filter.tennv,
                active:this.form_filter.active,
                company_id:this.form_filter.company_id,
                department_id:this.form_filter.department_id
              });
             var page_url = this.page_url_group +'?'+ params.toString();

            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.groups = res.data;

                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;
           
        },
        showGroup(){
             this.reset();
             this.group.active = 1;
             
        //    if(this.company_id == '' || this.department_id == ''){
        //          toastr.info("Vui lòng chọn công ty và phòng ban");
        //          return;
        //    }
          
                
              $("#AddGroup").modal("show");
        },
        AddGroup() {
             var page_url = this.page_url_group;// "/api/category/groups";
           

            if(this.edit === false){

               
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.group),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                         this.reset();
                        this.groups.push(data.data);            
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#AddGroup").modal("hide");
                       
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
        editGroup(group){

         let index = this.groups.findIndex(i => {
            return i.id == group.id;
            });
           // var group={..._obj};
             this.edit = true;
            this.group.id            = group.id;
            this.group.active        = group.active;
            this.group.description   = group.description;
            this.group.name          = group.name;
            this.group.party_id      = group.party_id;
            this.group.workshop_id   = group.workshop_id;
            this.group.department_id = group.department_id;
            this.group.company_id    = group.company_id;
            this.group.index         = index;

              $("#AddGroup").modal("show");
        },
        deleteGroup(id){

            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_group}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                    this.fetchGroup();
                });
            }

        },
        assignUserShow(group){
            return '/category/group?type=assign&id='+group.id;
        },
        assignTeamShow(group){
            return '/category/team?type=assign&id='+group.teamID;
        },
        createTeamForGroup() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn nhóm muốn tạo"), "");
                return;
            }

            this.loading = true;
            var page_url =  "/api/category/create-team-for-group";
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        group_ids: this.selected
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            let totalCreateNew = data.data.totalCreateNew;
                            let totalExisted = data.data.totalExisted;
                        //this.approveRoutings.push(data.data);
                            this.showMessage("Thông báo", "Tạo mới thành công " + totalCreateNew + " cây duyệt, đã tồn tại " + totalExisted + " cây duyệt.");
                            this.selected = [];
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
        },
        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.groups) {
            this.selected.push(this.groups[i].id);
          }
        }
      },
      filter_data(){
         this.fetchGroup();
      },
        showSearch(){
        this.show_search = ! this.show_search;
        //this.clearFilter();

      },
      clearFilter(){
           for(let field in this.form_filter){
                this.form_filter[field] = "";
            }
         
        // this.contract_filter =this.contracts;
      },
        company_name(company_id){
            var obj = this.companies.find(x=>x.id == company_id);
            
            if(obj)
             return obj.name;
            else
             return "";
        },
        department_name(department_id){
            var obj = this.departments.find(x=>x.id == department_id);
            if(obj)
             return obj.name;
            else
             return "";
        },
        workshop_name(workshop_id){
            var obj = this.workshops.find(x=>x.id == workshop_id);
            if(obj)
             return obj.name;
            else
             return "";
        },
        party_name(party_id){
            var obj = this.parties.find(x=>x.id == party_id);
            if(obj)
             return obj.name;
            else
             return "";
        },
        hasError(fieldName){
            return (fieldName in this.errors);
        },
        getError(fieldName){
            return this.errors[fieldName][0];
            
        },
        clearError(event){
             Vue.delete( this.errors,event.target.name);
        },
        reset(){
            this.clearAllError();
            this.edit = false;
            for(let field in this.group){
                this.group[field] = null;
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
      rows(){
        return this.groups.length;
      },
      hasAnyError(){
            return Object.keys(this.errors).length > 0;
        },
     department_filter(){
          let company_id = this.form_filter.company_id;
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },

        department_filter_form(){
          let company_id = this.group.company_id;
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },

        workshop_filter_form(){
          let department_id = this.group.department_id;
          return this.workshops.filter(function(item){
              if(item.department_id == department_id){
                return true;
              }
          });
        },

        party_filter_form(){
          let workshop_id = this.group.workshop_id;
          return this.parties.filter(function(item){
              if(item.workshop_id == workshop_id){
                return true;
              }
          });
        },
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
