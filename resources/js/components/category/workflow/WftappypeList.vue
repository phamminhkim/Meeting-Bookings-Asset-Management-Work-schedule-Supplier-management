<template>
<div>
       <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark">  {{ $t(title)}}</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                  <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                  <button  class="btn btn-info btn-sm" @click="showWFAppType()"> <i class="fa fa-plus"></i>
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
                                   <label  class="col-form-label-sm col-md-1 " style="text-align:left"     :title="$t('form.employee_code')" for="">{{$t('form.employee_code')}}</label>
                                    <div class="col-md-3">
                                    <input type="text" v-model="form_filter.manv" class="form-control form-control-sm mt-1">
                                    </div>
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" :title="$t('form.employee_name')" for="">{{$t('form.employee_name')}}</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="form_filter.tennv"   class="form-control form-control-sm mt-1">
                                    </div>

                                    
                                </div>
                                <div class="form-group row">
                                      <label  class="col-form-label-sm  col-md-1" style="text-align:left" for="">{{$t('form.status')}}</label>
                                    <div class="col-md-3">
                                    <select name="" v-model="form_filter.active" id="" class="form-control form-control-sm mt-1">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    
                                     <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning btn-sm mt-1" @click="filter_data()"> <i class="fa fa-search"></i> {{$t('form.find')}} </button>
                                    <button type="reset" class="btn btn-secondary btn-sm mt-1" @click="clearFilter()"> <i class="fa fa-reset"></i> {{$t('form.clear')}}</button>
                                    
                                    </div>
                                </div>
                                 
                            </div>
            
                            
                        </div>  
                        <div class="row mt-1 " style="background-color:#F4F6F9">
                            
                            <div class="col-md-9">
                                <!-- <div class="form-group row">
                                <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button>
                                
                                <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editRequest()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                                <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button>
                                
                                </div> -->
                                
                            </div>
                            <div class="col-md-3">
                                <div class="row mt-1">
                                <div class="input-group input-group-sm  col-12">
                                
                                                        <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter" :placeholder="$t('form.search')" aria-label="Search">
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
                                :items="wfapptypes"
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
                           <template #cell(active)="data">
                                  <span class="badge bg-success" v-if="data.item.active == 1">{{$t('form.active')}}</span>
                                  <span class="badge bg-warning" v-if="data.item.active == 0">{{$t('form.inactive')}}</span>
                           </template>
                           <template #cell(action)="data">
                               <div
                                    class="hidden-sm hidden-xs btn-group"
                                >
                                    <button @click="editWFAppType(data.item)"
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
                                    </a>
                                    <button @click="deleteWFAppType(data.item.id)"
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
        <div class="modal fade" id="AddWFAppType" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddWFAppType" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">
                               <span v-if="!edit">{{$t('form.add')}} </span> 
                               <span v-if="edit">{{$t('form.update')}} </span> 
                                {{$t('form.wfapptype')}}</h4>
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
                                <label for="id"
                                    >{{$t('form.id')}}</label
                                >
                                <input
                                    v-model="wfapptype.id"
                                    type="text"
                                    class="form-control"  v-bind:class="hasError('id')?'is-invalid':''"
                                    id="id"
                                    name="id"
                                    placeholder=""
                                    
                                    required
                                />
                                
                                <span v-if="hasError('id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('id')}}</strong>
                                    
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name"
                                    >{{$t('form.name')}}</label
                                >
                                <input
                                    v-model="wfapptype.name"
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
                            <div class="form-group">
                                <label for="description"
                                    >{{$t('form.description')}}</label
                                >
                                <textarea
                                    v-model="wfapptype.description"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('description')?'is-invalid':''"
                                    id="description"
                                    name="description"
                                    placeholder="" required/>
                                  <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('description')}}</strong>
                                </span>
                            </div>
                            
                                           
                            <div class="form-group">
                                <label for="status"
                                    >{{$t('form.status')}}</label
                                >
                              
                                <select class="form-control" v-model="wfapptype.active" name="status" id="status">
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
            wfapptypes: [],
            pagesNumber: [],
            wfapptype: {
                id: "",
                name: "",
                description: "",               
                active:"",
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
            page_url_wfapptypes : "/api/category/wfapptypes",
            page_url_department : "/api/category/departments",
            page_url_company:"/api/category/companies",
            show_search:false,
            form_filter:{
                manv:"",
                tennv:"",
                active:"",
            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
               {
                    key: 'id',
                    label:this.$t('form.id'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label:this.$t('form.name'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'description',
                    label:this.$t('form.description'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'active',
                   label:this.$t('form.status'),
                    sortable: true,
                    stickyColumn: true
                },

                {
                    key: 'action',
                    label:'action',
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
         this.fetchWFAppType();
       
    },
    mounted() {

         
        // this.fetchWFAppType(this.pagination.current_page);
      
    },

    methods: {
      
        fetchWFAppType() {
            //$("#tbbody_id").html('');

            this.loading = true;
           this.wfapptypes = Array();
              const params = new URLSearchParams({
                manv:this.form_filter.manv,
                tennv:this.form_filter.tennv,
                active:this.form_filter.active,
             
              });
             var page_url = this.page_url_wfapptypes +'?'+ params.toString();

            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.wfapptypes = res.data;

                   // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    
                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;
           
        },
        showWFAppType(){
             this.reset();
             this.wfapptype.active = 1;
              $("#AddWFAppType").modal("show");
        },
        AddWFAppType() {
             var page_url = this.page_url_wfapptypes;// "/api/category/wfapptypes";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.wfapptype),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                         this.reset();
                        this.wfapptypes.push(data.data);            
                        this.showMessage(this.$t('form.message'),this.$t('form.save_success'));
                        $("#AddWFAppType").modal("hide");
                        this.fetchWFAppType();
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.wfapptype.id, {
                method: "PUT",
                body: JSON.stringify(this.wfapptype),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.wfapptypes[this.wfapptype.index]=this.wfapptype;     
                        this.$set(this.wfapptypes, this.wfapptype.index, {...this.wfapptype});
                         this.showMessage(this.$t('form.message'),this.$t('form.update_success'));
                        $("#AddWFAppType").modal("hide");
                        this.reset();
                        this.fetchWFAppType();
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }
           
            
        },
        editWFAppType(wfapptype){
         let index = this.wfapptypes.findIndex(i => {
            console.log("id"+i.id);
            return i.id == wfapptype.id;
            });
           // var wfapptype={..._obj};
             this.edit = true;
            this.wfapptype.id            = wfapptype.id;
            this.wfapptype.active        = wfapptype.active;
            this.wfapptype.description   = wfapptype.description;
            this.wfapptype.name          = wfapptype.name;

              $("#AddWFAppType").modal("show");
              
        },
        deleteWFAppType(id){

            if(confirm(this.$t('form.confirm_delete')+'?')){
                fetch(`${this.page_url_wfapptypes}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                      this.showMessage(this.$t('form.message'),this.$t('form.delete_success'));
                      this.fetchWFAppType();
                });
            }

        },
        assignUserShow(wfapptype){
            return '/category/wfapptype?type=assign&id='+wfapptype.id;
        },
        
        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.wfapptypes) {
            this.selected.push(this.wfapptypes[i].id);
          }
        }
      },
      filter_data(){
         this.fetchWFAppType();
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
            for(let field in this.wfapptype){
                this.wfapptype[field] = null;
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
        return this.wfapptypes.length;
      },
        hasAnyError(){
            return Object.keys(this.errors).length > 0;
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
