<template>
  <div>
    
    <div class="content-header" >
      <div class="container-fluid"  >
        <div class="row" >
          <div class="col-sm-6">
          
           <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> Hợp đồng</h5>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <a href="payment/contracts?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo hợp đồng</a>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   
    <div class="row">
            
          <div class="col-md-12">
           
            <div class="card">
               <!-- /.card-header -->
               
              <div class="card-body">
                  <div class="row mt-0">
                   
                  <div class="col-md-9">
                    <div class="form-group row">
                      <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                      <div class="btn-group ">
                        <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                          <span v-if="!show_search">Hiện tìm kiếm</span> 
                          <span v-if="show_search">Ẩn tìm kiếm</span> 
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
                            <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Từ ngày</label>
                          <div class="col-md-3">
                            <input type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1">
                          </div>
                            <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Đến ngày</label>
                          <div class="col-md-3">
                            <input type="date"  v-model="form_filter.end_date" class="form-control form-control-sm mt-1">
                          </div>
                            <!-- <label  class="col-form-label-sm " style="text-align:left" for="">Thanh  toán</label>
                          <div class="col-md-3">
                            <select name="" v-model="form_filter.payment_status" id="" class="form-control form-control-sm mt-1">
                              <option value="1">Chưa thanh toán</option>
                              <option value="2">Đang thanh toán</option>
                              <option value="3">Đã thanh toán</option>
                              <option value="">All</option>
                            </select>
                          </div>
                           -->
                        </div>
                        <div class="form-group row">
                            
                            <label  class="col-form-label-sm col-md-1 " style="text-align:left" title="Số hợp đồng" for="">Số HĐ</label>
                          <div class="col-md-3">
                            <input type="text" v-model="form_filter.contract_num" class="form-control form-control-sm mt-1">
                          </div>
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
                            <label  class="col-form-label-sm   " style="text-align:left" for="id_bophan">Bộ phận &nbsp; &nbsp; &nbsp; </label>
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
                          
                        
                        </div>
                        <div class="form-group row">
                            <label  class="col-form-label-sm col-md-1" style="text-align:left" for="">Loại hợp đồng</label>
                          <div class="col-md-3">
                            <select name="" id="" v-model="form_filter.contract_type" class="form-control form-control-sm mt-1">
                              <option value="1">HĐ Số tiền cố định</option>
                              <option value="2">HĐ phát sinh theo chu kỳ</option>
                              <option value="3">HĐ nguyên tắc</option>
                              <option value="">All</option>
                            </select>
                          </div>
                            <label  class="col-form-label-sm  col-md-1" style="text-align:left" for="">Nhà cung cấp</label>
                          <div class="col-md-3">
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
                            <label  class="col-form-label-sm " style="text-align:left" for="">Tình trạng &nbsp;</label>
                          <div class="col-md-3">
                            <select name="" id="" v-model="form_filter.status" class="form-control form-control-sm mt-1">
                              <option value="1">Chưa thực hiện</option>
                              <option value="2">Đang thực hiện</option>
                              <option value="3">Đã thanh lý</option>
                              <option value="">All</option>
                            </select>
                          </div>

                        </div>

                      </div>
                      <div class="col-md-12 mb-2" style="width:100%;text-align: center;margin-top:-20px">
                          
                           <button type="submit" class="btn btn-warning btn-sm mt-1" @click="filter_data()"> <i class="fa fa-search"></i> Tìm </button>
                           <button type="reset" class="btn btn-secondary btn-sm mt-1" @click="clearFilter()"> <i class="fa fa-reset"></i> Clear</button>
                          
                        
                      </div>
                  </div>

                <div class="row mt-1 " style="background-color:#F4F6F9">
                   
                  <div class="col-md-9">
                    <div class="form-group row">
                      <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                      
                       <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa hợp đồng" @click="editContract()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá hợp đồng"  @click.prevent="deleteContract()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button>
                       <button type="button" class="btn  btn-info  btn-sm ml-1 mt-1" title="Tạo phụ lục"  @click="addSubContract()"><i class="fas fa-plus" title="Tạo phụ lục"></i>Tạo phụ lục</button>
                    </div>
                    
                  </div>
                  <div class="col-md-3">
                    <div class="row mt-1">
                    <div class="input-group input-group-sm  col-12">
                       
                                            <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                      <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter" placeholder="Nhập thông tin hợp đồng..." aria-label="Search">
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
                   
                 <div class="active tab-pane" id="hopdongmoi">
                      <div class="row" >
                           <b-table striped hover responsive :bordered="true" head-variant="light"  :sticky-header="false"   small
                           :items="contracts"
                           :current-page="current_page"
                           :per-page="per_page" 
                            :filter="filter"
                            selectable
                             
                             ref="selectableTable"
                           
                           :fields="fields">

                               <template #head(selected)>
                              
                              <!-- {{selected}} -->
                               <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"     ></b-form-checkbox>
                              </template>
                              <template  v-slot:cell(selected)="data"   >
                              
                               <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>  
                              </template>                              

                              <template #head(contract_num)>
                                <div class="text-nowrap">Số hợp đồng</div>
                              </template>
                             <template #head(status)>
                                <div class="text-nowrap">Tình trạng</div>
                              </template>

                              <template #cell(content)="data" >
                              <span  v-html="data.value"></span>
                            </template> 
                             <template  v-slot:cell(contract_num)="data" >
                              <span  ><a href="#" @click.prevent="viewContract(data.item.id)">{{data.item.contract_num}}</a></span>
                            </template>
                              <template #cell(created_at)="data">
                               <span>{{data.value | formatDate}}</span> 
                            </template>
                            <template #cell(sign_date)="data">
                               <span>{{data.value | formatDate}}</span> 
                            </template>

                             <template #cell(amount)="data">
                              <span   ><strong>{{data.value.toLocaleString(locale_format)}} </strong></span>
                            </template>
                            <template #cell(status)="data">
                               <span  v-if="data.value == 1">Chưa thực hiện </span>
                               <span  v-if="data.value == 2">Đang thực hiện</span>
                               <span  v-if="data.value == 3">Đã thanh lý</span>
                              
                            </template>

                             <!-- <template v-slot:cell(action)="data">
                              <span>
                                 <button class="btn btn-default btn-xs" title="Tạo phụ lục" @click="addSubContract(data.item.id)" v-if="!data.parent"><i class="fa fa-plus"  title="Tạo phụ lục"></i> </button>&nbsp;                                 
                                 <button class="btn btn-default btn-xs" title="Sửa" @click="editContract(data.item.id)"><i class="fa fa-edit" title="Sửa"></i></button>&nbsp;
                               <button  class="btn btn-default btn-xs text-red" title="Xoá" @click.prevent="deleteContract(data.item.id)"><i class="fa fa-trash" title="Xoá"></i></button>
                               </span>
                            </template> -->
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
                  </div>
         
          <!-- Loading (remove the following to stop the loading)-->

                <div class="center overlay" v-if="loading">
                    <i
                        class="fa fa-spinner fa-spin fa-4x fa-fw gray center"
                    ></i>
                    <span class="sr-only">Loading...</span>
                </div>
                        <!-- end loading -->
              </div>

              <!-- /.card-body -->
            </div>
   
            <!-- /.card -->
          </div>
        </div>
 <!-- /. </div>-->
          
</template>

<script>
export default {
  data () {
    return {
      contracts:[],
      total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter:"",
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
    
      errors:{},
      status:"",
      tab:"",
       loading: false,
      edit: false,
      token:"",
      page_url_contracts : "api/payment/contracts",
      page_url_vendors:"/api/category/vendors",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      show_search:false,
      form_filter:{
        contract_num:"",
        start_date:"",
        end_date:"",
        payment_status:"",
        contract_type:"",
        vendor_id:"",
        company_id:"",
        department_id:"",
        status:""

      },
      locale_format:'de-DE',
      companies:[],
      departments:[],
      vendors:[],
      selected:[],
      selectAll: false,
      fields: [

          {
            key: 'selected',
            label:'All',
            stickyColumn: true
          },
         {
            key: 'contract_num',
            label:'Số hợp đồng',
            sortable: true,
            stickyColumn: true
          },
         
          {
            key: 'amount',
             label:'Số tiền',
            sortable: true,
            stickyColumn: true
          },
           {
            key: 'created_at',
             label:'Ngày tạo',
            sortable: true,
            stickyColumn: true
          },
           {
            key: 'sign_date',
             label:'Ngày ký',
            sortable: true,
            stickyColumn: true
          },
                   {
            key: 'status',
            label:'Tình trạng',
            sortable: true
            ,stickyColumn: true

          },
           {
            key: 'vendor.comp_name',
             label:'Nhà cung cấp',
            
            sortable: true
             ,stickyColumn: true
            
          },
           {
            key: 'content',
             label:'Diễn giải',
            
            sortable: true
             ,stickyColumn: true
            
          },

          //   {
          //   key: 'action',
          //    label:'',
            
          //   sortable: false
          //    ,stickyColumn: true
            
          // },
            
          
        ],
       
    }
  },
  created() {

        this.token = "Bearer " + window.Laravel.access_token;
            
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
       
        //Thiết lập form tìm kiếm mặc định
        this.init();
        //Lấy danh sách dựa vào form tìm kiếm
        this.fetCompany();
        this.fetDepartment();
        this.fetVendor();
        this.fetchContract();
    },
    methods:{
      //Khởi tạo các giá trị trong form tìm kiếm
      init(){
            const  start_date = new Date();
            const  today = new Date();
            start_date.setMonth(start_date.getMonth() - 1);
            this.form_filter.start_date = start_date.getFullYear()+"-"+ fixDigit(start_date.getMonth()+1)+"-"+fixDigit(start_date.getDate());;
            this.form_filter.end_date = today.getFullYear()+"-"+ fixDigit(today.getMonth()+1)+"-"+fixDigit(today.getDate());
      },
      fetVendor() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_vendors;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.vendors = res.data.data;
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
                  this.departments = res.data.data;
              })
              .catch(err => console.log(err));
      },
      fetchContract(){
          
             this.loading = true;
            this.contracts=Array();
             
             const params = new URLSearchParams({
                  contract_num:   this.form_filter.contract_num,
                  start_date:     this.form_filter.start_date,
                  end_date:       this.form_filter.end_date,
                  payment_status: this.form_filter.payment_status,
                  status:         this.form_filter.status,
                  contract_type:  this.form_filter.contract_type,
                  vendor_id:      this.form_filter.vendor_id,
                  company_id:     this.form_filter.company_id,
                  department_id:  this.form_filter.department_id
              });
            console.log(params.toString());
            var page_url = this.page_url_contracts +'?'+ params.toString();
           
            console.log("abc"+page_url);
            fetch(page_url,{
              headers:{Authorization:this.token},
             
              })
            .then(res=>res.json())
            .then(res=>{
              console.log(res);
                this.contracts = res.data;

                // this.contract_filter = [...this.contracts];
                this.pagination = res.data;
                this.loading = false;
            }).catch(err=>{
              console.log(err);
                 this.loading = false;
            });
      },
      editContract(){

        console.log(this.selected.length);
        if(this.selected.length != 1){
          toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
          return;
        }
        var id = this.selected;
        window.location.href= "payment/contracts?type=edit&id="+id;
      },
      addSubContract(){
         if(this.selected.length != 1){
          toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
          return;
        }
        var id = this.selected;
        console.log("id="+id);
        let item = this.contracts.find(i => {
          
          return i.id == id;
        });
        if(!item.parent){
          window.location.href= "payment/contracts?type=add&parent="+id;
        }else{
          toastr.info('Tạo phụ lục cho phụ lục chưa được hỗ trợ.',"");
        }
        
      },

      viewContract(id){

        //  if(this.selected.length != 1){
        //   toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
        //   return;
        // }
        // var id = this.selected;
        window.location.href= "payment/contracts?type=view&id="+id;
      },

      deleteContract(){

         if(this.selected.length != 1){
          toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
          return;
        }
        var id = this.selected;
        let index = this.contracts.findIndex(i => {
          console.log("id"+i.id);
          return i.id == id;
        });
        console.log(this.selected);
        this.$bvModal.msgBoxConfirm('Xác nhận xoá ?', {
          title: '',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'danger',
          okTitle: 'OK',
          cancelTitle: 'Cancel',
          footerClass: 'p-2',
          hideHeaderClose: false,
          centered: true
        })
          .then(value => {
           if(value){
              var  page_url = "api/payment/contracts/"+id;
              fetch(page_url,{
                  method:"DELETE",
                  headers:{Authorization:this.token}
                  })
              .then(res=>res.json())
              .then(res=>{
                  if(res.data.success == '1'){
                    //this.contracts.splice(index,1);
                    this.contracts.splice(index,1);
                    //this.contracts = [...this.contracts];
                  }else{
                    toastr.warning(res.data.message,"Cảnh báo");
                  }

              }).catch(err=>console.log(err)); 
           }
          })
          .catch(err => {
            console.log(err);
          })
        
      },
    	select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.contracts) {
            this.selected.push(this.contracts[i].id);
          }
        }
      },
      showSearch(){
        this.show_search = ! this.show_search;
        //this.clearFilter();

      },
     
      clearFilter(){
           for(let field in this.form_filter){
                this.form_filter[field] = "";
            }
          this.init();
        // this.contract_filter =this.contracts;
      },
        selectAllRows(value) {
        
        if(value){
          this.$refs.selectableTable.selectAllRows();
        }else{
           this.$refs.selectableTable.clearSelected();
        }
     
      },
      onRowSelected(items) {
        this.selected = items;
      },
        
       
      filter_data(){
         this.fetchContract();
        // this.contract_filter = this.contracts;
        // //console.log(this.contract_filter.filter(data=>data.contract_num.indexOf(this.form_filter.contract_num)));
        
        // var startDate = new Date(this.form_filter.start_date);
        // var endDate = new Date(this.form_filter.end_date);
        // if(isNaN(endDate)){
        //    var endDate = new Date(this.form_filter.start_date);
        // }
        // if(!isNaN(endDate)){
        //   endDate.setHours(23);
        //   endDate.setMinutes(59);
        //   endDate.setSeconds(59);
        // }

        //   if(this.form_filter.contract_num.toLowerCase() != ""){
        //      this.contract_filter =  this.contract_filter.filter(data=>data.contract_num.toLowerCase().indexOf(this.form_filter.contract_num.toLowerCase())>-1);
        //   }
        //   if(!isNaN(endDate) && !isNaN(startDate) ){
        //        this.contract_filter =  this.contract_filter.filter(data=>
        //         {
        //           var date = new Date(data.created_at);
        //           return (date >= startDate && date <= endDate);
        //         }
                
        //         );
        //   }
       
        // if(this.form_filter.payment_status != ""){
        //   this.contract_filter =  this.contract_filter.filter(data=>{
        //       if(this.form_filter.payment_status == 0){
        //         return data.payment_status == null || data.payment_status == '0';
        //       }else 
        //       {
        //         return data.payment_status == this.form_filter.payment_status;
        //       }
        //   });
        // }
       
        // if(this.form_filter.vendor_id != ""){
        //   this.contract_filter =  this.contract_filter.filter(data=>data.vendor_id == this.form_filter.vendor_id);
        // }

        //  if(this.form_filter.contract_type != ""){
        //   this.contract_filter =  this.contract_filter.filter(data=>data.contract_type == this.form_filter.contract_type);
        // }
     
      },
      getStatus(status){
        if(status == 2){
          return 'Hoàn tất';
        }else if(status == 1){
          return 'Đang xử lý';
        }else{
            return 'Hợp đồng mới';
        }
      }
    },
    computed:{
      rows(){
        return this.contracts.length;
      },
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
          // let company_id = this.form_filter.company_id;
         
          return this.vendors.filter(function(item){
              // if(item.company_id == company_id){
                return true;
              // }
          });
        }
 
      
    }


}

</script>

<style scoped>
 
 .form-group {
    margin-bottom: 1px  !important;
}
</style>
 