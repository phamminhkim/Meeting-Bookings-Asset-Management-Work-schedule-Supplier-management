<template>
    <div>
         <div class="row mt-0">

            <div class="col-md-9">
            <div class="form-group row">
                <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
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
                <!-- <button type="button" :title="$t('form.filter')" onclick="location.reload(true)" class="btn btn-secondary  btn-xs ml-1" ><i class="fas fa-redo-alt" title="Refresh"></i></button> -->
                <button @click="filter_data()" :title="$t('form.filter')" class="btn btn-secondary  btn-xs ml-1"   ><i class="fas fa-sync-alt" :title="$t('form.reload')"></i></button>
                <span v-if="save_filter" :title="$t('form.filter')" class="ml-1"><i class="fas fa-filter" :title="$t('form.filter')"></i></span>
            </div>

            </div>
            <div class="col-md-3">
            <div class="row">

            </div>


            </div>
        </div>
        <div class="row" v-if="show_search" style="border: 1px solid #E5E5E5;border-radius:5px;">
            <div class="col-sm-12">
                <div class="form-group row">
                    <label for="start_date"  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   >{{$t('form.from_date')}}</label>
                    <div class="col-sm-2">
                    <input   type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1">
                    </div>
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="">{{$t('form.to_date')}}</label>
                    <div class="col-sm-2">
                    <input type="date"  v-model="form_filter.end_date" class="form-control form-control-sm mt-1">
                    </div>

                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   :title="$t('form.serial_num')" for="">{{$t('form.serial_num')}}</label>
                        <div class="col-sm-2">
                        <input type="text" v-model="form_filter.serial_num" class="form-control form-control-sm mt-1">
                        </div>
                    </div>
                <div class="form-group row">
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="">{{$t('form.document_type')}}</label>
                        <div class="col-sm-2">
                        <select name="" id="" v-model="form_filter.document_type_id" class="form-control form-control-sm mt-1">
                            <option v-for="document_type in document_types" v-bind:key="document_type.id" v-bind:value="document_type.id">

                            {{$t(document_type.name) }}
                            </option>

                            <option value="">All</option>
                        </select>
                        </div>

                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right" for="">{{$t('form.company')}}</label>
                    <div class="col-sm-2">
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
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="id_bophan">{{$t('form.department')}}</label>
                    <div class="col-sm-2">
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


                </div>
                <div class="form-group row">

                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="">{{$t('form.vendor')}}</label>
                    <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.vendor_id" class="form-control form-control-sm mt-1">
                        <option
                        v-for="vendor in vendor_filter"
                        :key="vendor.id"
                        v-bind:value="vendor.id"
                        >
                        {{ vendor.comp_name  }}
                            </option>
                        <option value="">All</option>
                    </select>
                    </div>

                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="">{{$t('form.payment_method')}}</label>
                    <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.method_payment" class="form-control form-control-sm mt-1">
                        <option selected value="1">{{$t('form.transfer')}}</option>
                        <option value="2">{{$t('form.cash')}}</option>

                        <option value="">All</option>
                    </select>
                    </div>
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="miss_invoice">{{$t('form.miss_invoice')}}</label>
                    <div class="col-md-2">
                    <select name="" id="" v-model="form_filter.miss_invoice" class="form-control form-control-sm mt-1">
                        <option selected value="1">{{$t('form.miss_invoice')}}</option>
                        <option value="0">{{$t('form.full_invoice')}}</option>
                        <option value="">All</option>
                    </select>

                    </div>

                </div>
                <div class="form-group row">
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1"   for="">{{$t('form.status')}}</label>
                        <div class="col-sm-6 mt-1 mb-1">
                            <treeselect  placeholder="All" :multiple="multiple"   :disable-branch-nodes="false" v-model="form_filter.status"  :options="status_option" />
                        </div>
                    <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="miss_invoice">{{$t('form.document_by')}}</label>
                        <div class="col-md-2">
                            <select name="" id="" v-model="form_filter.visibility" class="form-control form-control-sm mt-1">

                                <option value="0">{{$t('form.owner')}}</option>
                                <option value="">All</option>

                            </select>

                        </div>
                </div>
                <div class="col-md-12" style="text-align:center" >
                    <button type="submit" class="btn btn-warning btn-sm mt-1 mb-1" @click="filter_data()"> <i class="fa fa-search"></i> {{$t('form.find')}} </button>
                    <button type="reset" class="btn btn-secondary btn-sm mt-1 mb-1" @click="clearFilter()"> <i class="fa fa-reset"></i> {{$t('form.clear')}}</button>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" v-model="save_filter" >
                        <label class="form-check-label" for="save_filter"> <i>{{$t('form.save_filter')}}</i>  </label>
                    </div>
                    </div>
                </div>


         </div>

    </div>
</template>

<script>
 import Treeselect from '@riophae/vue-treeselect'
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
    components:{
        Treeselect
    },
    props:{
        module:String,
        eventname:String,
        variant_name:String,//tên variant dùng để phân biệt các form dùng làm key không nên trùng
    },
    data() {
        return {
        //  variant_name:'managerprice_AppPriceList',
        show_search:false,
        status_option:[
        {id: '0',label: this.$t('Yêu cầu mới')},
        {id: '1',label: this.$t('Đang xử lý')},
        {id: '2',label: this.$t('Duyệt hoàn tất')},
        {id: '3',label: this.$t('Đã thanh toán')},
        {id: '-1',label: this.$t('Đã huỷ')},
        // {id: '',label: 'All'},
       ],
      document_types:[],
      variant_data:[],
      companies:[],
      departments:[],
      vendors:[],
      form_filter:{
        contract_num:"",
        serial_num:"",
        start_date:"",
        end_date:"",
        status:[],
        contract_type:"",
        document_type_id:"",
        vendor_id:"",
        company_id:"",
        department_id:"",
        method_payment:"",
        miss_invoice:"",
        visibility:"",
      },
      variant_data:[],
      save_filter:false,
      multiple: true,
       page_url_vendors:"/api/category/vendors",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      page_url_document_type : "/api/category/documenttypes",

     };
   },
   
  created(){
        this.token = "Bearer " + window.Laravel.access_token;

        this.getVariant();
       this.init();
        //Lấy danh sách dựa vào form tìm kiếm
        this.fetCompany();
        this.fetDocumentType();
        this.fetDepartment();
        this.fetVendor();
  },
  methods:{
            //Khởi tạo các giá trị trong form tìm kiếm

        showSearch(){
        this.show_search = ! this.show_search;
        //this.clearFilter();

      },
       filter_data(){
        this.saveVariant([this.form_filter]);
        //console.log(this.form_filter);
        this.$emit(this.eventname,this.form_filter);
      },
       clearFilter(){
           for(let field in this.form_filter){
                this.form_filter[field] = "";
            }
          this.init();
        // this.contract_filter =this.contracts;
      },
      getVariant(name){

        //   this.clearFilter();
         var page_url = 'api/user/variant?url='+this.variant_name + '&no-cache='+new Date().getTime();
        var data = {
            url:this.variant_name,

        }
        //console.log(JSON.stringify(data));
        fetch(page_url,{
            method:"GET",

            headers:{
                Authorization:this.token,
                'Content-Type':'application/json',
                "Accept": "application/json",
                "X-Requested-With":"XMLHttpRequest"
              }
        }).then(res=>res.json())
        .then(res=>{
          console.log(res);
          if(res.statuscode == "403"){
              return;
            }
            if(res.success == '1'){
              this.variant_data = res.data;
              this.init();

            }
             this.$emit(this.eventname,this.form_filter);

        }).catch(err=>{
          console.log(err);
        })
      },
      saveVariant(obj){
        var page_url = 'api/user/variant';
        // var form_json = {...this.form_filter};

        var data = {
            url:this.variant_name,
            name:'form_filter',
            save_filter:this.save_filter?1:0 ,
            properties:this.form_filter
        }
        //console.log(JSON.stringify(data));
        fetch(page_url,{
            method:"POST",
            body:JSON.stringify(data),
            headers:{
                Authorization:this.token,
                'Content-Type':'application/json',
                "Accept": "application/json",
                "X-Requested-With":"XMLHttpRequest"
              }
        }).then(res=>res.json())
        .then(res=>{
           console.log(res);
        }).catch(err=>{
          console.log(err);
        })

      },
       init(){
            const  start_date = new Date();
            const  today = new Date();
            start_date.setMonth(start_date.getMonth() - 1);
            this.form_filter.status = [];
            //variant:
            if( this.variant_data != 'undefined' &&  this.variant_data.length > 0){
               this.variant_data.forEach(variant => {
              if(variant.name == 'form_filter'){
               // console.log(variant.properties.serial_num);
                  this.save_filter = variant.save_filter;
                  //nếu có nhấn lưu điều kiện lọc thì mới load vào form
                  if(this.save_filter){
                    this.form_filter = variant.properties;
                    for(let field in this.form_filter){
                      if( this.form_filter[field] == null){
                        this.form_filter[field] = "";
                      }
                    }
                  if( this.form_filter.status == null){
                     this.form_filter.status = [];
                  }
                  }
                }
            });
            }
            this.form_filter.start_date = start_date.getFullYear()+"-"+ fixDigit(start_date.getMonth()+1)+"-"+fixDigit(start_date.getDate());;
            this.form_filter.end_date = today.getFullYear()+"-"+ fixDigit(today.getMonth()+1)+"-"+fixDigit(today.getDate());
      },
       fetDocumentType() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_document_type+"?module="+this.module;
        fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {
                //console.log("Xin chao");
                this.document_types = res.data;
            })
            .catch(err => console.log(err));
        },
      fetVendor() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_vendors;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.vendors = res.data;
                })
                .catch(err => console.log(err));
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
  },//end method
  computed:{
         department_filter(){
          let company_id = this.form_filter.company_id;
         // this.contract.department_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
        vendor_filter(){
          let company_id = this.form_filter.company_id;
         // this.contract.vendor_id = "";
          return this.vendors.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        }
  },//end computed
}
</script>

<style lang="scss" scoped>

 .form-group {
    margin-bottom: 1px  !important;
}
</style>
