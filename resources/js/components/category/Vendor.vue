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
                  <button  class="btn btn-info btn-sm" @click="showVendor()"> <i class="fa fa-plus"></i>
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
                                      <label  class="col-form-label-sm  col-md-1" style="text-align:left" for="">{{$t('form.company')}}</label>
                                    <div class="col-md-3">
                                  <select
                                    name="company_id"
                                    class="form-control"

                                    v-model="form_filter.company_id"

                                    required
                                >
                                    <option
                                        v-for="company in companies"
                                        :key="company.id"
                                        v-bind:value="company.id"
                                    >
                                        {{ company.name }}
                                    </option>
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
                                :items="vendors"
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
                                    <button @click="editVendor(data.item)"
                                            class="btn btn-xs  mr-3"  title ="Edit">
                                        <i
                                            class="fa fa-edit  text-green bigger-120"
                                        ></i>
                                    </button>

                                    <button @click="deleteVendor(data.item.id)"
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
        <div class="modal fade" id="AddVendor" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddVendor" @keydown="clearError">
                        <div class="modal-header">
                            <h4 class="modal-title">
                               <span v-if="!edit">{{$t('form.add')}} </span>
                               <span v-if="edit">{{$t('form.update')}} </span>
                                {{$t('form.vendor')}}</h4>
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
                                <label for="company_id"
                                    >{{$t('form.company')}}</label
                                >
                                <small style="color:red">(*)</small>
                               <select
                                    name="company_id"
                                    class="form-control"
                                    v-bind:class="hasError('company_id')?'is-invalid':''"
                                    v-model="vendor.company_id"
                                    @change="clearError($event)"
                                    required
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
                                <label for="comp_name"
                                    >{{$t('form.name')}}</label
                                >
                                 <small style="color:red">(*)</small>
                                <input
                                    v-model="vendor.comp_name"
                                    type="text"
                                    class="form-control"  v-bind:class="hasError('comp_name')?'is-invalid':''"
                                    id="comp_name"
                                    name="comp_name"
                                    placeholder=""

                                    required
                                />

                                <span v-if="hasError('comp_name')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('comp_name')}}</strong>

                                </span>
                            </div>
                            <div class="form-group">
                                <label for="tax_code"
                                    >{{$t('form.tax_code')}}</label
                                >

                                <input
                                    v-model="vendor.tax_code"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('tax_code')?'is-invalid':''"
                                    id="tax_code"
                                    name="tax_code"
                                    placeholder="" />
                                  <span v-if="hasError('tax_code')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('tax_code')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="address"
                                    >{{$t('form.address')}}</label
                                >
                                <input
                                    v-model="vendor.address"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('address')?'is-invalid':''"
                                    id="address"
                                    name="address"
                                    placeholder="" />
                                  <span v-if="hasError('address')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('address')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="phone"
                                    >{{$t('form.phone')}}</label
                                >
                                <input
                                    v-model="vendor.phone"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('phone')?'is-invalid':''"
                                    id="phone"
                                    name="phone"
                                    placeholder="" />
                                  <span v-if="hasError('phone')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('phone')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="fax"
                                    >{{$t('form.fax')}}</label
                                >
                                <input
                                    v-model="vendor.fax"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('fax')?'is-invalid':''"
                                    id="fax"
                                    name="fax"
                                    placeholder="" />
                                  <span v-if="hasError('fax')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('fax')}}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="email"
                                    >{{$t('form.email')}}</label
                                >
                                <input
                                    v-model="vendor.email"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('email')?'is-invalid':''"
                                    id="email"
                                    name="email"
                                    placeholder="" />
                                  <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('email')}}</strong>
                                </span>
                            </div>
                             <div class="form-group">
                                <label for="signer"
                                    >{{$t('form.signer')}}</label
                                >
                                <input
                                    v-model="vendor.signer"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('signer')?'is-invalid':''"
                                    id="signer"
                                    name="signer"
                                    placeholder="" />
                                  <span v-if="hasError('signer')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('signer')}}</strong>
                                </span>
                            </div>
                             <div class="form-group">
                                <label for="position"
                                    >{{$t('form.position')}}</label
                                >
                                <input
                                    v-model="vendor.position"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('position')?'is-invalid':''"
                                    id="position"
                                    name="position"
                                    placeholder="" />
                                  <span v-if="hasError('position')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('position')}}</strong>
                                </span>
                            </div>
                             <div class="form-group">
                                <label for="contact"
                                    >{{$t('form.contact')}}</label
                                >
                                <input
                                    v-model="vendor.contact"
                                    type="text"
                                    class="form-control"
                                    v-bind:class="hasError('contact')?'is-invalid':''"
                                    id="contact"
                                    name="contact"
                                    placeholder="" />
                                  <span v-if="hasError('contact')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('contact')}}</strong>
                                </span>
                            </div>


<!--
                            <div class="form-group">
                                <label for="status"
                                    >{{$t('form.status')}}</label
                                >

                                <select class="form-control" v-model="vendor.active" name="status" id="status">
                                    <option value="1" selected >{{$t('form.active')}}</option>
                                    <option value="0">{{$t('form.inactive')}}</option>
                                </select>


                                  <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                   <strong>{{getError('level')}}</strong>
                                </span>
                            </div> -->

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
            vendors: [],
            companies: [],
            pagesNumber: [],
            vendor: {
                id: "",
                comp_name: "",
                tax_code: "",
                phone:"",
                email:"",
                signer:"",
                position:"",
                address:"",
                contact:"",
                company_id:"",
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
            page_url_vendor : "/api/category/vendors",
            page_url_department : "/api/category/departments",
            page_url_company:"/api/category/companies",
            show_search:false,
            form_filter:{

                company_id:"",

            },
             fields: [
                {
                    key: 'selected',
                    label:'All',
                    stickyColumn: true
                },
                {
                    key: 'comp_name',
                    label:this.$t('form.vendor'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'tax_code',
                    label:this.$t('form.tax_code'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'phone',
                   label:this.$t('form.phone'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'email',
                   label:this.$t('form.email'),
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'company_id',
                   label:this.$t('form.company'),
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
        this.fetCompany();
         this.fetchVendor();

    },
    mounted() {


        // this.fetchVendor(this.pagination.current_page);

    },

    methods: {
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
        fetchVendor() {
            //$("#tbbody_id").html('');

            this.loading = true;
           this.vendors = Array();
              const params = new URLSearchParams({
                company_id:this.form_filter.company_id,


              });
             var page_url = this.page_url_vendor +'?'+ params.toString();

            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.vendors = res.data;

                   // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;

        },
        showVendor(){
             this.reset();
             this.vendor.active = 1;
              $("#AddVendor").modal("show");
        },
        AddVendor() {
             var page_url = this.page_url_vendor;// "/api/category/vendors";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.vendor),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                    if (!data.data.errors) {
                         this.reset();
                        this.vendors.splice(0,0,data.data);
                        this.showMessage(this.$t('form.message'),this.$t('form.save_success'));
                        $("#AddVendor").modal("hide");

                    }else{

                        this.errors = data.data.errors;

                    }
                })
                .catch(err => console.log(err));
            }else{
                //update
                 fetch(page_url+"/"+this.vendor.id, {
                method: "PUT",
                body: JSON.stringify(this.vendor),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (!data.data.errors) {

                       // this.vendors[this.vendor.index]=this.vendor;
                        this.$set(this.vendors, this.vendor.index, {...this.vendor});
                         this.showMessage(this.$t('form.message'),this.$t('form.update_success'));
                        $("#AddVendor").modal("hide");
                       this.reset();
                    }else{

                        this.errors = data.data.errors;

                    }
                })
                .catch(err => console.log(err));
            }


        },
        editVendor(vendor){

         let index = this.vendors.findIndex(i => {
            console.log("id"+i.id);
            return i.id == vendor.id;
            });
           // var vendor={..._obj};
             this.edit = true;
            this.vendor.id                = vendor.id;
            this.vendor.comp_name         = vendor.comp_name;
            this.vendor.signer            = vendor.signer;
            this.vendor.position          = vendor.position;
            this.vendor.tax_code          = vendor.tax_code;
            this.vendor.phone             = vendor.phone;
            this.vendor.email             = vendor.email;
            this.vendor.fax             = vendor.fax;
            this.vendor.company_id        = vendor.company_id;
            this.vendor.address           = vendor.address;
            this.vendor.contact           = vendor.contact;
            this.vendor.index             = index;

              $("#AddVendor").modal("show");
        },
        deleteVendor(id){

            if(confirm(this.$t('form.confirm_delete')+'?')){
                fetch(`${this.page_url_vendor}/${id}`,{
                    method:'delete',
                    headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                })
                .then(res=>res.json())
                .then(data=>{
                      this.showMessage(this.$t('form.message'),this.$t('form.delete_success'));
                    this.fetchVendor();
                });
            }

        },
        assignUserShow(vendor){
            return '/category/vendor?type=assign&id='+vendor.id;
        },

        select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.vendors) {
            this.selected.push(this.vendors[i].id);
          }
        }
      },
      filter_data(){
         this.fetchVendor();
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
            for(let field in this.vendor){
                this.vendor[field] = null;
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
        return this.vendors.length;
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

<style lang="scss" scoped>

 .form-group {
    margin-bottom: 1px  !important;
}
</style>
