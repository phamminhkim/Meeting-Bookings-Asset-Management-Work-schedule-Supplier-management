<template>
<div>
        <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> {{$t(title)}}</h5>
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
        <div class="col-md-12">

          <div class="card ">

            <!-- /.card-header -->
            <div class="card-body">


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
                  <div class="col-md-12 ">
                      <div class="form-group row">
                          <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="">{{$t('form.from_date')}}</label>
                        <div class="col-sm-2">
                          <input type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1">
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

                          <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="">{{$t('form.company')}}</label>
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
                        <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="id_bophan">{{$t('form.department')}} &nbsp; &nbsp; &nbsp; </label>
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
                          <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1"   for="">{{$t('form.status')}}</label>
                            <div class="col-sm-6 mt-1 mb-1">
                                <treeselect  placeholder="All" :multiple="multiple"   :disable-branch-nodes="false" v-model="form_filter.status"  :options="status_option" />
                            </div>
                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for="">{{$t('form.vendor')}}</label>
                            <div class="col-sm-2">
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

                     </div>
                       <div class="form-group row">
                        <label
                          class="col-form-label-sm col-sm-2 col-form-label text-left text-md-right"
                          for=""
                          >{{ $t("form.docbrowsertype") }}</label
                        >
                        <div class="col-sm-10">
                          <treeselect id="docbrowser_type_id" :disabled="edit"
                                                          :disable-branch-nodes="true"
                                                          v-model="form_filter.docbrowser_type_id"
                                                          :options="tree_docbrowserType" :multiple="true"  />
                        </div>


                      </div>
                        <div class="form-group row">

                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   for=""></label>
                            <div class="col-sm-4">
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="form_filter.hide_doc_cancel" >
                                    <label class="form-check-label" for="save_filter"> <i>{{$t('form.hide_doc_cancel')}}</i>  </label>
                                </div>
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
                <div class="row mt-1 " style="background-color:#F4F6F9">

                  <div class="col-md-4">
                    <div class="form-group row">
                       <button type="button" class="btn btn-success btn-sm" @click.prevent="multipleApprove()" ><i class="fas fa-check"></i> {{$t('form.multipleapprove')}}</button>

                      <!-- <button type="button" class="btn btn-success btn-sm" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.paid')}}</button> -->

                       <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editRequest()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button>
                       -->
                    </div>

                  </div>
                  <div class="col-md-5">
                    <div class="form-group row">
                     <select name="" id="" v-model="filter" class="form-control form-control-sm mt-1">

                            <option v-for="docbrowser_type in tree_docbrowserType" v-bind:key="docbrowser_type.id" v-bind:value="docbrowser_type.label">

                               {{$t(docbrowser_type.label) }}
                              </option>

                            <option value="">All - Loại trình ký</option>
                          </select>
                      <!-- <treeselect id="docbrowser_type_id" :disabled="edit"

                                                    :disable-branch-nodes="true"
                                                     v-model="form_filter.docbrowser_type_id"
                                                   
                                                    :options="tree_docbrowserType" :multiple="true"  /> -->
 
                    </div>

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
              <!-- <div class="row">
                <div class="col-md-6">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active"  v-on:click="changeStatus('')" href="/#chuaduyet" data-toggle="tab">Danh sách chờ</a></li>
                    <li class="nav-item"><a class="nav-link" v-on:click="changeStatus('2')" href="/#duocduyet" data-toggle="tab">Danh sách đã duyệt</a></li>
                    <li class="nav-item"><a class="nav-link" v-on:click="changeStatus('1')" href="/#tuchoi" data-toggle="tab">Danh sách phản hồi</a></li>
                  </ul>
                </div>
                <div class="col-md-6">

                  <div class="input-group input-group-sm mt-1">

                    <input class="form-control form-control-navbar" id="search" type="search" v-on:keyup.enter="searchDocument()" v-on:keyup.delete="clearSearch()" v-on:search="searchDocument()" placeholder="Search" aria-label="Search">
                    <span class="input-group-append">
                      <button type="button" @click="searchDocument()" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button>
                    </span>

                  </div>

                </div>
              </div> -->

              <!-- <hr> -->

                <div  class="active tab-pane" id="yeucaumoi">
                  <div class="row">
                        <b-table striped hover responsive :bordered="true" head-variant="light"  :sticky-header="false"   small
                           :items="requests"
                           :current-page="current_page"
                           :per-page="per_page"
                            :filter="filter"
                            :filter-included-fields="filterOn"
                            selectable
                             ref="selectableTable"
                          :sort-compare="myCompare"
                           :fields="fields">
                            <template #head(selected)>
                              <!-- {{selected}} -->
                               <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"     ></b-form-checkbox>
                              </template>
                           <template  v-slot:cell(selected)="data"   >
                               <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>
                           </template>
                             <template #cell(created_at)="data">
                                <span>{{data.value | formatDateDB}}</span>
                           </template>
                             <template v-slot:cell(serial_num)="data">
                                <span  > <a href="#" @click.prevent="viewDocument(data.item.id)">{{data.item.serial_num}}</a> </span>
                           </template>
                             <template v-slot:cell(title)="data">
                                <span  > <a href="#" @click.prevent="viewDocument(data.item.id)">{{data.item.title}}</a> </span>
                           </template>
                           <template #cell(date_approve)="data">
                               <span v-if="data.item.waitingApproval">
                                  {{data.item.waitingApproval.checkout | formatDateDB}}
                                  </span>
                           </template>
                           <template v-slot:cell(send_date_approve)="data">
                                <span v-if="data.item.lastApprove">{{data.item.lastApprove.created_at | formatDateDB}}</span>
                           </template>
                           <template #cell(amount)="data">
                                <span   ><strong>{{data.value.toLocaleString(locale_format)}} </strong></span>
                           </template>
                           <template v-slot:cell(status)="data">

                                <span  v-if="data.value == 3" class="badge bg-success"> {{$t('Đã thanh toán')}}</span>
                                <span  v-else-if="data.value == 2" class="badge bg-primary"> {{$t('Duyệt hoàn tất')}}</span>
                               <span v-else-if="data.value == 1" class="badge badge-secondary">{{$t('Đang xử lý')}} </span>
                               <span v-else-if="data.value == -1" class="badge bg-danger">{{$t('Đã huỷ')}} </span>
                               <span  v-else class="badge bg-warning" >{{$t('Yêu cầu mới')}}</span>

                            </template>
                           <template v-slot:cell(approve)="data">
                               <span v-if="data.item.waitingApproval" v-html="getStatus(data.item)"></span>
                            </template>

                      </b-table>
                      <div class="row"   >
                             <div class="col-md-12" >
                              <div class="form-group row">
                                <label  class="col-form-label-sm col-md-4" style="text-align:right" for="">Per page:</label>
                              <div class="col-md-3">
                                  <b-form-select
                                    size="sm"
                                    v-model="per_page"
                                    :options="pageOptions"

                                  ></b-form-select>
                              </div>
                                <label  class="col-form-label-sm col-md-1" style="text-align:right" for=""></label>
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

            <!-- /.card-body -->
             <loading :loading="loading"></loading>
          </div>

          <!-- /.card -->
        </div>
    </div>
</div>

</template>

<script>
import Loading from '../shared/Loading.vue'
 import Treeselect from '@riophae/vue-treeselect'
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
  props: {
    user_id:"",
    title:"",
  },
     components: { Loading,Treeselect },
  data () {
    return {
      variant_name:'approve_ApproveDocument',
      type:"REPORT",
      requests:[],
      document_types:[],
      tree_docbrowserType: [],
       total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter:"",
      filterOn: [],
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
        locale_format:'de-DE',
      fields: [
          {
            key: 'selected',
            label:'All',
            stickyColumn: true
          },
          {
            key: 'serial_num',
            label:this.$t('form.document_num'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
           {
            key: 'status',
            label:this.$t('form.status'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
        {
            key: 'approve',
            label:this.$t('form.approve'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },

         {
            key: 'title',
            label:this.$t('form.title'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
           {
            key: "docbrowser_type.name",
            label: this.$t("form.docbrowsertype"),
            sortable: true,
            stickyColumn: true,
            class: "text-nowrap",
          },
           {
            key: 'amount',
            label:this.$t('form.amount'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap text-right"
          },
          {
            key: 'currency',
            label:this.$t('form.currency'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
           {
            key: 'user.name',
            label:this.$t('form.create_by'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
          {
            key: 'created_at',
            label:this.$t('form.create_date'),
            sortable: true,
            stickyColumn: true,
             class:"text-center text-nowrap"
          },
          {
            key: 'send_date_approve',
            label:this.$t('form.send_date'),
            sortable: true,
            sortByFormatted: true,
            stickyColumn: true,
            class:"text-nowrap",
            formatter: (value, key, item) => {
               if(item.lastApprove){
                 let a = item.lastApprove.created_at;
                 // Split them into an array of parts (yyyy-mm-dd)
                 a = a.split('-');
                // convert string parts to numbers
                 a = (parseInt(a[0], 10) * 10000) + (parseInt(a[1], 10) * 100) + parseInt(a[2])
                return a;
               }else{
                 return false;
               }
            },
          },
           {
            key: 'date_approve',
            class:"text-nowrap",
            label:this.$t('form.approve_date'),
             filterByFormatted: true,
             formatter: (value, key, item) => {

               if(item && item.lastApprove && item.lastApprove.checkout){

                  let a = item.lastApprove.checkout;
                  // Split them into an array of parts (yyyy-mm-dd)
                  a = a.substring(0, 10);;
                  a = a.split('-');
                  // convert string parts to numbers
                  // a = (parseInt(a[0], 10) * 10000) + (parseInt(a[1], 10) * 100) + parseInt(a[2])
                  a = (a[2]+'/'+a[1]+'/'+a[0])
                  return a;
               }
               return false;

            },
            sortable: true,
            stickyColumn: true

          },


      ],
      show_search:false,
      form_filter:{
        contract_num:"",
        serial_num:"",
        start_date:"",
        end_date:"",
         status:[],
        contract_type:"",
        vendor_id:"",
        company_id:"",
        department_id:"",
        document_type_id:"",
         hide_doc_cancel:false,
         docbrowser_type_id: [],
      },
        status_option:[

        {id: '0',label: this.$t('form.not_approved_yet')},
        {id: '1',label: this.$t('form.feedback')},
        {id: '2',label: this.$t('form.approved')},
      ],
       multiple: true,
      variant_data:[],
      save_filter:false,
      companies:[],
      departments:[],
      vendors:[],
       selected:[],
      selectAll: false,
      pagination: {},
       errors:{},
      status:"",
      tab:"",
       loading: false,
      edit: false,
      token:"",
      page_url_approve : "/api/approve",
        page_url_vendors:"/api/category/vendors",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      page_url_document_type : "/api/category/documenttypes",
      page_url_docbrowser_type: "/api/category/docbrowsertypes",
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
        //Thiết lập form tìm kiếm mặc định
        this.getVariant();
        this.init();
        //Lấy danh sách dựa vào form tìm kiếm
        this.fetCompany();
        this.fetDepartment();
        this.fetDocumentType();
        this.fetVendor();
        this.fetDocbrowserTypes_Tree();
        // this.fetchDocument();
  },

  methods: {

    fetDocbrowserTypes_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_docbrowser_type + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.tree_docbrowserType = res.data;
                })
                .catch((err) => console.log(err));
        },
    //Khởi tạo các giá trị trong form tìm kiếm
      init(){
             const  start_date = new Date();
            const  today = new Date();
            start_date.setMonth(start_date.getMonth() - 3);
            this.form_filter.start_date =
            start_date.getFullYear() +
            "-" +
            fixDigit(start_date.getMonth() + 1) +
            "-" +
            fixDigit(start_date.getDate());
          this.form_filter.end_date =
            today.getFullYear() +
            "-" +
            fixDigit(today.getMonth() + 1) +
            "-" +
            fixDigit(today.getDate());

             this.form_filter.status = [];
             this.form_filter.docbrowser_type_id = [];

             //variant:
            if( this.variant_data != 'undefined' &&  this.variant_data.length > 0){
               this.variant_data.forEach(variant => {

              if(variant.name == 'form_filter'){
               //console.log(variant.properties.serial_num);
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
                   if( this.form_filter.hide_doc_cancel == null ){
                        this.hide_doc_cancel = false;
                   }
                  }


                }


            });
            }
            //   this.form_filter.start_date = start_date.getFullYear()+"-"+ fixDigit(start_date.getMonth()+1)+"-"+fixDigit(start_date.getDate());;
            // this.form_filter.end_date = today.getFullYear()+"-"+ fixDigit(today.getMonth()+1)+"-"+fixDigit(today.getDate());
      },
       fetDocumentType() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_document_type+"?module="+this.type;
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

    fetchDocument(page,search){

              this.loading = true;
            this.requests=Array();
              const params = new URLSearchParams({
                contract_num:   this.form_filter.contract_num,
                serial_num:   this.form_filter.serial_num,
                start_date:     this.form_filter.start_date,
                end_date:       this.form_filter.end_date,
                status: this.form_filter.status,
                contract_type:  this.form_filter.contract_type,
                vendor_id:      this.form_filter.vendor_id,
                company_id:      this.form_filter.company_id,
                department_id:      this.form_filter.department_id,
                document_type_id:      this.form_filter.document_type_id,
                docbrowser_type_id:      this.form_filter.docbrowser_type_id,
                type:this.type,
                 hide_doc_cancel:this.form_filter.hide_doc_cancel,
              });

             var page_url = this.page_url_approve +'?'+ params.toString();

            // this.requests=Array();

            // var page_url = this.page_url_approve
            //               + "?page=" + page
            //               +"&status="+this.status
            //               +"&search="+search;

            fetch(page_url,{headers:{Authorization:this.token}})
            .then(res=>res.json())
            .then(res=>{
              //console.log(res);
                this.requests = res.data;
                // this.pagination = res.data;
                this.loading = false;
            }).catch(err=>{
              console.log(err);
                 this.loading = false;
            });
      },
        getVariant(name){
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

             this.fetchDocument();

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
     viewDocument(id){
        window.location.href= "/approve/document?type=view&id="+id;
      },
       searchDocument(){
        //alert($('#search').val());
        this.fetchDocument(null,$('#search').val());
      },
      filter_data(){
         this.saveVariant([this.form_filter]);
         this.fetchDocument();
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
      select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.requests) {
            this.selected.push(this.requests[i].id);
          }
        }
      },
     getStatus(document){
        var waiting_for_approval = this.$t('form.waiting_for_approval');
       var feedback = this.$t('form.feedback');
       let status = '<span class="badge bg-warning">'+waiting_for_approval+'</span>' ;
       if(document.waitingApproval.release == '2'){
           status =  '<span class="text-success center" ><i class="fas fa-check"></i></span>' ;
       }else if(document.waitingApproval.release == '1'){
           status = '<span class="badge bg-danger">'+feedback+'</span>' ;
       }

       return status;
      },
       changeStatus(status){
        this.status = status;
        this.fetchDocument();

      }, multipleApprove(){
         if(this.selected.length == 0){
             toastr.info(this.$t('Vui lòng chọn dòng dữ liệu') );
             return;

         }

        this.$bvModal.msgBoxConfirm(this.$t('Duyệt hàng loạt')+ '?',{
          okVariant:'danger',
          okTitle: this.$t('form.approve'),
          cancelTitle: this.$t('form.cancel'),
          centered:true
        }).then(value=>{

            if(value){
              fetch(this.page_url_approve + "/multipleagree",{
                method:"POST",
                body: JSON.stringify({
                    payments: this.selected.toString(),
                    type: this.type.toString()
                 }),
                headers:{
                  Authorization:this.token,
                  'Content-Type':'application/json',
                  "Accept": "application/json",
                  "X-Requested-With":"XMLHttpRequest"
                }
              }).then(res=>res.json())
              .then(res=>{

                  if(res.statuscode == "403"){
                     toastr.warning(res.message,"");
                     return;
                   }
                    this.requests = this.requests;
                // this.pagination = res.data;

                let TotalFail = res.data.failApproves.length;
                let TotalSuccess = res.data.successApproves.length;

                if (TotalFail == 0)
                  toastr.success(this.$t('form.approved_success') + ' ' + TotalSuccess + ' trên '  + (TotalFail + TotalSuccess) + ' DNTT',"");
                else if (TotalSuccess == 0)
                  toastr.error(this.$t('form.approved_success') + ' ' + TotalSuccess + ' trên '  + (TotalFail + TotalSuccess) + ' DNTT',"");
                else
                  toastr.warning(this.$t('form.approved_success') + ' ' + TotalSuccess + ' trên '  + (TotalFail + TotalSuccess) + ' DNTT',"");

                    this.fetchRequest();
                    this.selected =[];
               }).catch(err=>{
                   console.log(err);
               })
             }
         }).catch(err=>{
             console.log(err);
         })

        },
      //cập nhật trạng thái đã thanh toán

       myCompare(itemA, itemB, key) {
      if (key == 'date_approve' || key == 'send_date_approve') {

        // Convert the string formatted date to a number that can be compared
        // Get the values being compared from the items
        // console.log("checkoutA == "+ itemA.approveds[itemA.approveds_count-1]);
        // console.log("checkoutB == "+ itemB.approveds[itemB.approveds_count-1]);

        let a = itemA.approveds[itemA.approveds_count-1] == undefined ? "0000-00-00": itemA.approveds[itemA.approveds_count-1].checkout;
        let b = itemB.approveds[itemB.approveds_count-1] == undefined ? "0000-00-00": itemB.approveds[itemB.approveds_count-1].checkout;
        // Split them into an array of parts (yyyy-mm-dd)
        a = a.split('-')
        b = b.split('-')
        // convert string parts to numbers
        a = (parseInt(a[0], 10) * 10000) + (parseInt(a[1], 10) * 100) + parseInt(a[2])
        b = (parseInt(b[0], 10) * 10000) + (parseInt(b[1], 10) * 100) + parseInt(b[2])
        // Return the comparison result
        return a - b

      } else {
          // If field is not `date` we let b-table handle the sorting
        return false;

      }
    },
  },computed:{
     rows(){
        return this.requests.length;
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
          let company_id = this.form_filter.company_id;
         // this.contract.vendor_id = "";
          return this.vendors.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        }
  }


}
</script>

<style lang="scss" scoped>

  .form-group {
  margin-bottom: 1px  !important;
}

</style>
