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
                                 
                                <button  class="btn btn-info btn-sm" @click="showPositionApp()"> <i class="fa fa-plus"></i>
                                  <span > {{ $t('form.create')}}</span>  
                                     </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row" style="background-color:#F4F6F9">
                        <div class="col-md-9">

                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm mt-1 mb-1">
                                <input type="search" class="form-control form-control-navbar" 
                                    v-model="filter"
                                    :placeholder="$t('form.search')"
                                     aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <b-table striped hover bordered responsive head-variant="light" small
                            :fields="fields"
                            :items="positionapps"
                            :filter="filter"
                            :current-page="current_page"
                            :per-page="per_page" 
                        >
                            <template #cell(user_id)="data">
                               <span>{{user_name(data.value)}}</span>
                           </template>
                             <template #cell(position_id)="data">
                              <span>{{position_name(data.value)}}</span> 
                           </template>
                           <template #cell(mask)="data">
                                <span v-if="data.value == 1">Sếp</span>
                                <span v-if="data.value==2">NV QA</span>
                                <span v-if="data.value==3">NV QC</span>
                                <span v-if="data.value==2">Mặc định</span>
                            </template>
                            <template #cell(active)="data">
                                <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                <span class="badge bg-warning" v-if="data.value==0">Ngưng hoạt động</span>
                            </template>
                            <template #cell(action)="data">
                                <div class="margin">
                                    <button class="btn btn-xs"  @click="editPositionApp(data.item)"><i class="fas fa-edit text-green " title="Edit"></i></button>
                                    <button class="btn btn-xs" @click="deletePositionApp(data.item.id)" ><i class="fas fa-trash text-red " title="Delete"></i></button>
                                </div>
                            </template>
                        </b-table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="margin">
                                    <div class="btn-group">
                                        <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                        <b-form-select 
                                            size="sm"
                                            v-model="per_page"
                                            :options="pageOptions"
                                        
                                        ></b-form-select>
                                        <b-pagination
                                         v-model="current_page"
                                        :total-rows="rows"
                                        :per-page="per_page"

                                         size="sm"
                                         class="ml-1"
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
        <div class="modal fade" id="addPositionApp" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="addPositionApp" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">
                               <span v-if="!edit">{{$t('form.add')}}</span> 
                               <span v-if="edit">{{$t('form.update')}}</span> 
                                 {{$t('form.positionapp')}}</h4>
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
                                <label for="userId">{{$t('form.user_id')}}</label>
                                <select2 v-model="positionapp.user_id" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                 <span v-if="hasError('user_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('user_id')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="position_id"
                                    >{{$t('form.position')}}</label
                                >
                                <select2 v-model="positionapp.position_id" :options="selectOption"  placeholder="Nhập chức danh cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                  <span v-if="hasError('position_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('position_id')}}</strong>
                                 </span>
                            </div>
                             <div class="form-group">
                                <label for="mask"   class="col-form-label-sm"
                                    >{{$t('form.mask')}}</label
                                >
                                <select class="form-control" v-model="positionapp.mask" name="mask" id="mask">
                                    <option value="1"  >{{$t('form.superior')}}</option>
                                    <option value="2">{{$t('form.qa')}}</option>
                                    <option value="3">{{$t('form.qc')}}</option>
                                    <option value="4" selected>{{$t('form.default')}}</option>
                                </select>


                                  <span v-if="hasError('mask')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('mask')}}</strong>
                                </span>
                            </div> 
                            <div class="form-group">
                                <label for="description"  class="col-form-label-sm"
                                    >{{$t('form.description')}}</label
                                >
                                <textarea
                                    v-model="positionapp.description"
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-bind:class="hasError('description')?'is-invalid':''"
                                    id="description"
                                    name="description"
                                    placeholder=""/>
                                  <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('description')}}</strong>
                                </span>
                            </div>
                         
                            <div class="form-group">
                                <label for="active"   class="col-form-label-sm"
                                    >{{$t('form.active')}}</label
                                >

                                <select class="form-control" v-model="positionapp.active" name="active" id="active">
                                    <option value="1" selected >{{$t('form.active')}}</option>
                                    <option value="0">{{$t('form.inactive')}}</option>
                                </select>


                                  <span v-if="hasError('active')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('active')}}</strong>
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
import Select2 from "v-select2-component";
export default {
  props: {
      title:""
  },
    components: {
        // Pagination
    },
    data() {
        return {
            value: [],
            options: [],
            selectoptions: [],
            positionapps:[],
            pagesNumber: [],
            positionapp: {
                id: "",
                user_id: "",
                position_id: "",
                mask: "",
                description: "",
                active:""
            },
            positions: [],
            users: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
             filter:"",
            modules:[],
            per_page: 10,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors:{},
            
            paginationdata: {},
            loading: false,
            edit: false,
            token:"",
            page_url_user : "api/user/all",
            page_url_position : "/api/category/positions",
            page_url_positionapp:"/api/category/positionapps",
             fields: [
                {
                    key: 'user_id',
                    label:this.$t('form.user_id'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'position_id',
                    label:this.$t('form.positionname'),
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'mask',
                    label:this.$t('form.mask'),
                    sortable: true,
                    stickyColumn: true
                },
                  {
                    key: 'active',
                    label:this.$t('form.active'),
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
            
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetUser();
        this.fetchPosition();
        this.fetchPositionApp();
    },
    mounted() {
         
        // this.fetchDepartment(this.pagination.current_page);
      
    },
    methods: {
        fetUser() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_user;//"/api/category/users";
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.users = res.data;
                  //  console.length(res);
                })
                .catch(err => {
                    console.log(err);

                    });
        },
         fetchPosition() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_position;//"/api/category/users";
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.positions = res.data;
                  //  console.length(res);
                })
                .catch(err => {
                    console.log(err);

                    });
        },
        fetchPositionApp(page) {
            //$("#tbbody_id").html('');
            this.loading = true;
            var page_url = this.page_url_positionapp;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.positionapps = res.data;
                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {
                     this.loading = false;
                    console.log(err);
                });
            this.edit = false;
           
        },
        showPositionApp(){
             this.reset();
              $("#addPositionApp").modal("show");
        },
        addPositionApp() {
             var page_url = this.page_url_positionapp;// "/api/category/positions";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.positionapp),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                         this.reset();
                        this.positionapps.push(data.data);            
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#addPositionApp").modal("hide");
                        this.fetchPositionApp();
                       
                    }else{
                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.positionapp.id, {
                method: "PUT",
                body: JSON.stringify(this.positionapp),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {
                        
                       // this.positions[this.position.index]=this.position;     
                        this.$set(this.positionapps, this.positionapp.index, {...this.positionapp});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#addPositionApp").modal("hide");
                       this.reset();
                      this.fetchPositionApp();
                    }else{
                        this.errors = data.data.errors;
                         
                    }
                })
                .catch(err => console.log(err));
            }
           
            
        },
        editPositionApp(positionapp){
           // var position={..._obj};
            let index = this.positionapps.findIndex(i => {
           
            return i.id == positionapp.id;
            });
             this.edit = true;
            this.positionapp.id          = positionapp.id;
            this.positionapp.user_id  = positionapp.user_id;
            this.positionapp.position_id        = positionapp.position_id;
            this.positionapp.mask        = positionapp.mask;
            this.positionapp.description        = positionapp.description;
            this.positionapp.active        = positionapp.active;
              $("#addPositionApp").modal("show");
        },
        deletePositionApp(id){
            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_positionapp}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                  this.fetchPositionApp();
                });
            }
        },
        user_name(user_id){
            var obj = this.users.find(x=>x.id == user_id);

            if(obj)
             return obj.name;
            else
             return "";
        },
        position_name(position_id){
            var obj = this.positions.find(x=>x.id == position_id);
            if(obj)
             return obj.name;
            else
             return "";
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
            for(let field in this.positionapp){
                this.positionapp[field] = null;
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
        },
        rows(){
            return this.positionapps.length;
        },
        placeholder(){
          return this.$t('form.search');
        },
        multOption(){
          this.users?.map((e) => {
          let o={
            text: e.name+'('+e.username+')',
            id: e.id
          }
          this.options.push(o);

      });

      return this.options;

      },
     selectOption(){
        // console.log(this.positions);
          this.positions?.map((e) => {
          let o={
              /* text: e.name+'('+e.company_id+')', */
              text: e.name,
              id: e.id
          }
          this.selectoptions.push(o);

      });

      return this.selectoptions;

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