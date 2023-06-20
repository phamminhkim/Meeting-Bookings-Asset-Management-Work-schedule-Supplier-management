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
                  <button  class="btn btn-info btn-sm" @click="showWorkLow()"> <i class="fa fa-plus"></i>
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
                                    <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Công ty</label>
                                    <div class="col-md-2">
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
                                    <label  class="col-form-label-sm    col-md-1" style="text-align:left" for="document_type">Loại chứng từ &nbsp; &nbsp; &nbsp; </label>
                                    <div class="col-md-2">
                                    <select name="" id="document_type" v-model="form_filter.document_type_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="documenttype in documentTypes"
                                            :key="documenttype.id"
                                            v-bind:value="documenttype.id"
                                            >
                                            {{ documenttype.name }}
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
                                :items="wfmains"
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
                             <template #cell(document_type_id)="data">
                              <span>{{document_name(data.value)}}</span> 
                           </template>
                           <template #cell(active)="data">
                                  <span class="badge bg-success" v-if="data.item.active == 1">{{$t('form.active')}}</span>
                                  <span class="badge bg-warning" v-if="data.item.active == 0">{{$t('form.inactive')}}</span>
                           </template>

                           <template #cell(action)="data">
                               <div
                                    class="hidden-sm hidden-xs btn-group"
                                >
                                    <button @click="editWorkLow(data.item)"
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
                                    <button @click="deleteWorkLow(data.item.id)"
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
        <div class="modal fade" id="AddWorkLow" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddWorkLow" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} Work Flow </h4>
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
                                    <select id="company_id" required class="form-control form-control-sm" v-model="workflow.company_id"  v-bind:class="hasError('company_id')?'is-invalid':''">

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
                                <label for="document_type_id"  class="col-form-label-sm"
                                    >{{$t('form.document_type')}}</label>
                                        <select  id="document_type_id" class="form-control form-control-sm" v-model="workflow.document_type_id"  v-bind:class="hasError('document_type_id')?'is-invalid':''">
                                        <option v-for="documentType in documentTypes" v-bind:key="documentType.id" v-bind:value="documentType.id" >
                                            {{documentType.name}}
                                        </option>
                                        <option value=""></option>

                                    </select>
                                    <span v-if="hasError('document_type_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('document_type_id')}}</strong>
                                    </span>
                             </div>                            
                            <div class="form-group">
                                <label for="name"  class="col-form-label-sm"
                                    >{{$t('form.name')}}</label
                                >
                                <input
                                    v-model="workflow.name"
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
                                <label for="code"  class="col-form-label-sm"
                                    >{{$t('form.code')}}</label
                                >
                                <input
                                    v-model="workflow.code"
                                    type="text"
                                    class="form-control form-control-sm"  v-bind:class="hasError('code')?'is-invalid':''"
                                    id="code"
                                    name="code"
                                    placeholder=""

                                    required
                                />

                                <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('code')}}</strong>

                                </span>
                            </div>
                            <div class="form-group">
                                <label for="description"  class="col-form-label-sm"
                                    >{{$t('form.description')}}</label
                                >
                                <textarea
                                    v-model="workflow.description"
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

                                <select class="form-control form-control-sm" v-model="workflow.active" name="active" id="active">
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
            workflows: [],
            wfmains: [],
            pagesNumber: [],
            companies:[],
            departments:[],
            documentTypes:[],
            workflow: {
                id: "",
                company_id:"",
                document_type_id:"",
                name: "",
                code:"",
                description: "",
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
            page_url_group : "/api/category/groups",
            page_url_wfmains : "/api/category/wfmains",
            page_url_department : "/api/category/departments",
            page_url_document_type:"api/category/documenttypes",
            page_url_company:"/api/category/companies",
            show_search:false,
            form_filter:{
                active:"",
                company_id:"",
                document_type_id:""
            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
                {
                    key: 'company_id',
                    label:'Công ty',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'document_type_id',
                    label:'Loại',
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'name',
                    label:'Tên',
                    sortable: true,
                    stickyColumn: true
                },
                                 {
                    key: 'code',
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
        this.fetCompany();
        this.fetDocumentType();
        this.fetDepartment();
       // this.fetchGroup();
        this.fetchWfmain();
        this.get_document_type();

    },
    mounted() {


        // this.fetchGroup(this.pagination.current_page);

    },

    methods: {
         get_document_type(){
        this.loading =true;
        var page_url = this.page_url_document_type;
        fetch(page_url,{
            method:'get',
            headers:{
                Authorization:this.token,
                "content-type": "application/json"
            }
        }).then(res=>res.json())
        .then(data=>{
              this.loading =false;
             // console.log(data);
              if(data && data.success== 1){

                  this.documentTypes = data.data;

              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });

      },

        fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
         fetDocumentType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_document_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.documentTypes = res.data;
                })
                .catch(err => console.log(err));
        },
         fetDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },

        // fetchGroup() {
        //     //$("#tbbody_id").html('');

        //     this.loading = true;

        //     this.workflows = Array();
        //       const params = new URLSearchParams({
        //         active:this.form_filter.active,
        //         company_id:this.form_filter.company_id,
        //         document_type_id:this.form_filter.document_type_id
        //       });
        //      var page_url = this.page_url_wfmains +'?'+ params.toString();

        //     let vm = this;
        //     fetch(page_url, { headers: { Authorization: this.token } })
        //         .then(res => res.json())
        //         .then(res => {
        //             this.workflows = res.data;

        //            // this.pagination = res.data;
        //             this.loading = false;
        //         })
        //         .catch(err => {

        //             console.log(err);
        //             this.loading = false;
        //         });
        //     this.edit = false;

        // },
        fetchWfmain() {
            //$("#tbbody_id").html('');

            this.loading = true;

            this.wfmains = Array();
              const params = new URLSearchParams({
                active:this.form_filter.active,
                company_id:this.form_filter.company_id,
                document_type_id:this.form_filter.document_type_id
              });
             var page_url = this.page_url_wfmains +'?'+ params.toString();

            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.wfmains = res.data;

                   // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        showWorkLow(){
             this.reset();
             //this.group.active = 1;

        //    if(this.company_id == '' || this.document_type_id == ''){
        //          toastr.info("Vui lòng chọn công ty và phòng ban");
        //          return;
        //    }


              $("#AddWorkLow").modal("show");
        },
        AddWorkLow() {
             var page_url = this.page_url_wfmains;// "/api/category/workflows";


            if(this.edit === false){


                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.workflow),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                    if (!data.data.errors) {
                         this.reset();
                        this.workflows.push(data.data);
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#AddWorkLow").modal("hide");
                        this.fetchWfmain();

                    }else{
                        this.errors = data.data.errors;
                        this.showError('Thông báo','Tuyến duyệt đã tồn tại');

                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.workflow.id, {
                method: "PUT",
                body: JSON.stringify(this.workflow),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {

                       // this.workflows[this.workflow.index]=this.workflow;
                        this.$set(this.workflows, this.workflow.index, {...this.workflow});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#AddWorkLow").modal("hide");
                         this.fetchWfmain();
                         this.reset();
                      
                    }else{

                        this.errors = data.data.errors;

                    }
                })
                .catch(err => console.log(err));
            }


        },
        editWorkLow(workflow){

         let index = this.workflows.findIndex(i => {
            console.log("id"+i.id);
            return i.id == workflow.id;
            });
           // var workflow={..._obj};
            this.edit = true;
            this.workflow.id            = workflow.id;
            this.workflow.company_id    = workflow.company_id;
            this.workflow.document_type_id = workflow.document_type_id;
            this.workflow.name          = workflow.name;
            this.workflow.code          = workflow.code;
            this.workflow.description   = workflow.description;
            this.workflow.active        = workflow.active;
           
              $("#AddWorkLow").modal("show");
        },
        deleteWorkLow(id){

            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_wfmains}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                    this.fetchWfmain();
                });
            }

        },
        assignUserShow(workflow){
            return '/category/workflowstep?type=assign&id='+workflow.id;
        },

        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.workflows) {
            this.selected.push(this.workflows[i].id);
          }
        }
      },
      filter_data(){
         //this.fetchGroup();
         this.fetchWfmain();
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
        document_name(document_type_id){
            var obj = this.documentTypes.find(x=>x.id == document_type_id);
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
            for(let field in this.workflow){
                this.workflow[field] = null;
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
        return this.workflows.length;
      },
      hasAnyError(){
            return Object.keys(this.errors).length > 0;
        },
     department_filter(){
          let company_id = this.form_filter.company_id;
         // this.contract.document_type_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },

        department_filter_form(){
          let company_id = this.workflow.company_id;
         // this.contract.document_type_id = "";
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

    //       var obj =  this.departments.find(x=>x.id == this.document_type_id);
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
