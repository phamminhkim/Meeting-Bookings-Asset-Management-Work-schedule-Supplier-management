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
                        </div>
                         <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
                            <div class="col-md-12 ">
                                <div class="form-group row">
                                    <!-- <label  class="col-form-label-sm    col-md-1" style="text-align:left" for="department_id">Phòng ban </label>
                                    <div class="col-md-2">
                                     <select name="" id="department_id" v-model="form_filter.department_id" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="department in departments"
                                            :key="department.id"
                                            v-bind:value="department.id"
                                            >
                                            {{ department.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select> 
                                    </div> -->
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
                                :items="standards"
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
                                    <button @click="editWorkLow(data.item)"
                                            class="btn btn-xs  mr-3"  title ="Edit">
                                        <i
                                            class="fa fa-edit  text-green bigger-120"
                                        ></i>
                                    </button>
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
        <div class="modal fade" id="Addstandard" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="Addstandard" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$t('form.add')}} tiêu chuẩn phiếu car </h4>
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
                                <label for="name"  class="col-form-label-sm"
                                    >{{$t('form.name')}}</label
                                >
                                <input
                                    v-model="standard.name"
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
                                <input
                                    v-model="standard.description"
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-bind:class="hasError('description')?'is-invalid':''"
                                    id="description"
                                    name="description"
                                    required
                                    placeholder=""/>
                                  <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('description')}}</strong>
                                </span>
                            </div>


                            <div class="form-group">
                                <label for="active"   class="col-form-label-sm"
                                    >{{$t('form.active')}}</label
                                >

                                <select class="form-control form-control-sm" v-model="standard.active" name="active" id="active">
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
            standards: [],
            pagesNumber: [],
            departments:[],
            standard: {
                id: "",
                name: "",
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
            page_url_carerrors : "/api/category/standards",
            //page_url_department : "/api/category/departments",
            show_search:false,
            form_filter:{
                department_id:"",
                active:""
            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label:this.$t('form.standardname'),
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
        //this.fetDepartment();
        this.fetchCarerror();
    },
    mounted() {


        // this.fetchGroup(this.pagination.current_page);

    },

    methods: {
        //  fetDepartment() {
        //     // const this.token = "Bearer " + window.Laravel.access_this.token;
        //     var page_url = this.page_url_department;
        //     fetch(page_url, { headers: { Authorization: this.token } })
        //         .then(res => res.json())
        //         .then(res => {
        //             //console.log("Xin chao");
        //             this.departments = res.data;
        //         })
        //         .catch(err => console.log(err));
        // },

        // fetchGroup() {
        //     //$("#tbbody_id").html('');

        //     this.loading = true;

        //     this.workflows = Array();
        //       const params = new URLSearchParams({
        //         active:this.form_filter.active,
        //         company_id:this.form_filter.company_id,
        //         document_type_id:this.form_filter.document_type_id
        //       });
        //      var page_url = this.page_url_carerrors +'?'+ params.toString();

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
        fetchCarerror() {
            //$("#tbbody_id").html('');

            this.loading = true;

            this.standards = Array();
              const params = new URLSearchParams({
                active:this.form_filter.active,
                department_id:this.form_filter.department_id,
              });
             var page_url = this.page_url_carerrors +'?'+ params.toString();

            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.standards = res.data;

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


              $("#Addstandard").modal("show");
        },
        Addstandard() {
             var page_url = this.page_url_carerrors;// "/api/category/workflows";
            if(this.edit === false){


                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.standard),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                    if (!data.data.errors) {
                         this.reset();
                        this.standards.push(data.data);
                        this.showMessage('Thông báo','Lưu thành công');
                        $("#Addstandard").modal("hide");
                        this.fetchCarerror();

                    }else{
                        this.errors = data.data.errors;
                        this.showError('Thông báo','Tuyến duyệt đã tồn tại');

                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.standard.id, {
                method: "PUT",
                body: JSON.stringify(this.standard),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {

                       // this.standards[this.standard.index]=this.standard;
                        this.$set(this.standards, this.standard.index, {...this.standard});
                        this.showMessage('Thông báo','Cập nhật thành công');
                        $("#Addstandard").modal("hide");
                         this.fetchCarerror();
                         this.reset();
                      
                    }else{

                        this.errors = data.data.errors;

                    }
                })
                .catch(err => console.log(err));
            }


        },
        editWorkLow(standard){

         let index = this.standards.findIndex(i => {
            console.log("id"+i.id);
            return i.id == standard.id;
            });
           // var standard={..._obj};
            this.edit = true;
            this.standard.id            = standard.id;
            this.standard.name    = standard.name;
            this.standard.description   = standard.description;
            this.standard.active        = standard.active;
           
              $("#Addstandard").modal("show");
        },
        deleteWorkLow(id){

            if(confirm('Bạn muốn xoá?')){
                fetch(`${this.page_url_carerrors}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                   this.showMessage('Thông báo','Xoá thành công');
                    this.fetchCarerror();
                });
            }

        },
        assignUserShow(standard){
            return '/category/carerrorstep?type=assign&id='+standard.id;
        },

        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.standards) {
            this.selected.push(this.standards[i].id);
          }
        }
      },
      filter_data(){
         //this.fetchGroup();
         this.fetchCarerror();
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
        department_name(department_id){
            var obj = this.departments.find(x=>x.id == department_id);
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
            for(let field in this.standard){
                this.standard[field] = null;
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
        return this.standards.length;
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
          let company_id = this.standard.company_id;
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
