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
                  <button  class="btn btn-info btn-sm" @click="showWFStepDetail()"> <i class="fa fa-plus"></i>
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
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for="wfstep">Workflow step</label>
                                    <div class="col-md-2">
                                    <select name="" id="wfstep" v-model="form_filter.wfstep_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="wfstep in wfsteps"
                                                :key="wfstep.id"
                                                v-bind:value="wfstep.id"
                                            >
                                                {{ wfstep.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for="wfusertype">Workflow user type</label>
                                    <div class="col-md-2">
                                    <select name="" id="wfusertype" v-model="form_filter.wfusertype_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="wfusertype in wfusertypes"
                                            :key="wfusertype.id"
                                            v-bind:value="wfusertype.id"
                                            >
                                            {{ wfusertype.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select>
                                    </div>
                                    <label  class="col-form-label-sm  col-md-1" style="text-align:left" for="">Trạng thái</label>
                                    <div class="col-md-2">
                                    <select name="" v-model="form_filter.active" id="" class="form-control form-control-sm mt-1">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
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
                                :items="wfstepdetails"
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
                           <template #cell(wfstep_id)="data">
                               <span>{{wfstep_name(data.value)}}</span>
                           </template>
                             <template #cell(wfusertype_id)="data">
                              <span>{{wfusertype_name(data.value)}}</span> 
                           </template>
                             <template #cell(wfapptype_id)="data">
                              <span>{{wfapptype_name(data.value)}}</span> 
                           </template>
                           <template #cell(active)="data">
                                  <span class="badge bg-success" v-if="data.item.active == 1">{{$t('form.active')}}</span>
                                  <span class="badge bg-warning" v-if="data.item.active == 0">{{$t('form.inactive')}}</span>
                           </template>

                           <template #cell(action)="data">
                               <div
                                    class="hidden-sm hidden-xs btn-group"
                                >
                                    <button @click="editWFStepDetail(data.item)"
                                            class="btn btn-xs  mr-3"  title ="Edit">
                                        <i
                                            class="fa fa-edit  text-green bigger-120"
                                        ></i>
                                    </button>
                             
                                    <button @click="deleteWFStepDetail(data.item.id)"
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
        <div class="modal fade" id="AddWFStepDetail" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddWFStepDetail" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} WorkFlow Step Detail </h4>
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
                                <label for="wfstep_id" class="col-form-label-sm"
                                    >{{$t('form.wfstep')}}</label >
                                    <select id="wfstep_id" required class="form-control" v-model="wfstepdetail.wfstep_id"  v-bind:class="hasError('wfstep_id')?'is-invalid':''">
                                        <option
                                            v-for="wfstep in wfsteps"
                                            :key="wfstep.id"
                                            v-bind:value="wfstep.id"
                                            >
                                            {{ wfstep.name }}
                                            </option>
                                        <option value=""></option>
                                    </select>
                                    <span v-if="hasError('wfstep_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('wfstep_id')}}</strong>
                                    </span>
                            </div>

                            <div class="form-group">
                                <label for="wfusertype_id"  class="col-form-label-sm"
                                    >{{$t('form.wfusertype')}}</label>
                                        <select  id="wfusertype_id" class="form-control" v-model="wfstepdetail.wfusertype_id"  v-bind:class="hasError('wfusertype_id')?'is-invalid':''">
                                        <option v-for="wfusertype in wfusertypes" v-bind:key="wfusertype.id" v-bind:value="wfusertype.id" >
                                            {{wfusertype.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('wfusertype_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('wfusertype_id')}}</strong>
                                    </span>
                             </div>   
                             <div class="form-group">
                                <label for="wfapptype_id"  class="col-form-label-sm"
                                    >{{$t('form.wfapptype')}}</label>
                                        <select  id="wfapptype_id" class="form-control" v-model="wfstepdetail.wfapptype_id"  v-bind:class="hasError('wfapptype_id')?'is-invalid':''">
                                        <option v-for="wfapptype in wfapptypes" v-bind:key="wfapptype.id" v-bind:value="wfapptype.id" >
                                            {{wfapptype.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('wfapptype_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('wfapptype_id')}}</strong>
                                    </span>
                             </div>                         
                            <div class="form-group">
                                <label for="level"  class="col-form-label-sm"
                                    >{{$t('form.level')}}</label
                                >
                                <input
                                    v-model="wfstepdetail.level"
                                    type="number"
                                    class="form-control"  v-bind:class="hasError('level')?'is-invalid':''"
                                    id="level"
                                    name="level"
                                    placeholder=""
                                    required
                                />
                                <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('level')}}</strong>

                                </span>
                            </div>
                            <div class="form-group">
                                <label for="required"  class="col-form-label-sm"
                                    >{{$t('form.required')}}</label
                                >
                                <input
                                    v-model="wfstepdetail.required"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('required')?'is-invalid':''"
                                    id="required"
                                    name="required"
                                    placeholder="" required/>
                                  <span v-if="hasError('required')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('required')}}</strong>
                                </span>
                            </div>


                            <div class="form-group">
                                <label for="active"   class="col-form-label-sm"
                                    >{{$t('form.active')}}</label
                                >

                                <select class="form-control" v-model="wfstepdetail.active" name="active" id="active">
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
            wfstepdetails: [],
            pagesNumber: [],
            wfsteps:[],
            wfapptypes:[],
            wfusertypes:[],
            wfstepdetail: {
                id: "",
                wfstep_id:"",
                wfusertype_id:"",
                wfapptype_id: "",
                level: "",
                required: "",
                active:"",
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
            page_url_wfsteps : "/api/category/lists",
            page_url_wfstepdetails : "/api/category/wfstepdetails",
            page_url_wfapptypes : "/api/category/wfapptypes",
            page_url_wfusertypes:"api/category/wfusertypes",
            show_search:false,
            form_filter:{
                wfstep_id:"",
                wfusertype_id:"",
                wfapptype_id:"",
                level:"",
                required:"",
                active:""
            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
                {
                    key: 'wfstep_id',
                    label:'Workflow step',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'wfusertype_id',
                    label:'Workflow user type',
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'wfapptype_id',
                    label:'Workflow approve type',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'level',
                    label:'Level',
                    sortable: true,
                    stickyColumn: true
                },
               {
                    key: 'required',
                    label:'required',
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
        this.fetWfstep();
        this.fetWfusertypes();
        this.fetWfapptypes();
        this.fetchWfstepdetails();

    },
    mounted() {


        // this.fetchGroup(this.pagination.current_page);

    },

    methods: {
    fetWfstep() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_wfsteps;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.wfsteps = res.data;
                })
                .catch(err => console.log(err));
        },
    fetWfusertypes() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_wfusertypes;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.wfusertypes = res.data;
                })
                .catch(err => console.log(err));
        },
    fetWfapptypes() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_wfapptypes;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.wfapptypes = res.data;
                })
                .catch(err => console.log(err));
        },
    fetchWfstepdetails() {
            //$("#tbbody_id").html('');

            this.loading = true;
            this.wfstepdetails = Array();
              const params = new URLSearchParams({
                wfstep_id:this.form_filter.wfstep_id,
                wfusertype_id:this.form_filter.wfusertype_id,
                wfapptype_id:this.form_filter.wfapptype_id,
                level:this.form_filter.level,
                required:this.form_filter.required,
                active:this.form_filter.active
              });
             var page_url = this.page_url_wfstepdetails +'?'+ params.toString();

            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.wfstepdetails = res.data;

                   // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        showWFStepDetail(){
             this.reset();
             //this.group.active = 1;

        //    if(this.company_id == '' || this.document_type_id == ''){
        //          toastr.info("Vui lòng chọn công ty và phòng ban");
        //          return;
        //    }


              $("#AddWFStepDetail").modal("show");
        },
        AddWFStepDetail() {
             var page_url = this.page_url_wfstepdetails;// "/api/category/wfstepdetails";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.wfstepdetail),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.data.errors) {
                         this.reset();
                        this.wfstepdetails.push(data.data);
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#AddWFStepDetail").modal("hide");
                        this.fetchWfstepdetails();

                    }else{
                        this.errors = data.data.errors;
                        this.showError('Thông báo','Lưu không thành công');

                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.wfstepdetail.id, {
                method: "PUT",
                body: JSON.stringify(this.wfstepdetail),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {

                       // this.wfstepdetails[this.wfstepdetail.index]=this.wfstepdetail;
                        this.$set(this.wfstepdetails, this.wfstepdetail.index, {...this.wfstepdetail});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#AddWFStepDetail").modal("hide");
                         this.fetchWfstepdetails();
                         this.reset();
                      
                    }else{

                        this.errors = data.data.errors;

                    }
                })
                .catch(err => console.log(err));
            }


        },
        editWFStepDetail(wfstepdetail){

         let index = this.wfstepdetails.findIndex(i => {
            console.log("id"+i.id);
            return i.id == wfstepdetail.id;
            });
           // var wfstepdetail={..._obj};
            this.edit = true;
            this.wfstepdetail.id            = wfstepdetail.id;
            this.wfstepdetail.wfstep_id    = wfstepdetail.wfstep_id;
            this.wfstepdetail.wfusertype_id = wfstepdetail.wfusertype_id;
            this.wfstepdetail.wfapptype_id          = wfstepdetail.wfapptype_id;
            this.wfstepdetail.level   = wfstepdetail.level;
            this.wfstepdetail.required   = wfstepdetail.required;
            this.wfstepdetail.active        = wfstepdetail.active;
           
              $("#AddWFStepDetail").modal("show");
        },
        deleteWFStepDetail(id){

            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_wfstepdetails}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                    this.fetchWfstepdetails();
                });
            }

        },

        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.wfstepdetails) {
            this.selected.push(this.wfstepdetails[i].id);
          }
        }
      },
      filter_data(){
         //this.fetchGroup();
         this.fetchWfstepdetails();
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
        wfstep_name(wfstep_id){
            var obj = this.wfsteps.find(x=>x.id == wfstep_id);

            if(obj)
             return obj.name;
            else
             return "";
        },
        wfusertype_name(wfusertype_id){
            var obj = this.wfusertypes.find(x=>x.id == wfusertype_id);
            if(obj)
             return obj.name;
            else
             return "";
        },
         wfapptype_name(wfapptype_id){
            var obj = this.wfapptypes.find(x=>x.id == wfapptype_id);
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
            for(let field in this.wfstepdetail){
                this.wfstepdetail[field] = null;
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
        },
        showError(title,message){
             if(!title)
                title = "Information";
             toastr.options = {
                positionClass: "toast-bottom-right"
                };

            toastr.error(message, title);
        }
    },
    computed: {
      rows(){
        return this.wfstepdetails.length;
      },
      hasAnyError(){
            return Object.keys(this.errors).length > 0;
        },
     department_filter(){
          let company_id = this.form_filter.company_id;
         // this.contract.wfusertype_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },

        department_filter_form(){
          let company_id = this.wfstepdetail.company_id;
         // this.contract.wfusertype_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
    //  company_name(){

    //       var obj =  this.companies.find(x=>x.id == this.company_id);
    //       if(obj)
    //         return obj.name;

    //  },
    //   department_name(){

    //       var obj =  this.departments.find(x=>x.id == this.wfusertype_id);
    //       if(obj)
    //         return obj.name;

    //  },
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
