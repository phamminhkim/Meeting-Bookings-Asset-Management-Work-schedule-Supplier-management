<template>
    <div>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i>   {{$t(title)}}</h5>
            </div>
            <div class="col-sm-6">

                <create-new-menu url='issues?type=add&doctype=' module='ISSUE'></create-new-menu>
                <!-- <div class="float-sm-right">
                  <div class="btn-group-vertical ">
                      <button class="btn btn-primary dropdown-toggle dropdown-icon"  data-toggle="dropdown"> {{ $t('form.create')}} </button>
                      <div class="dropdown-menu dropdown-menu-right" role="menu" >
                        <div v-for="(doc,index) in document_types_submenu" v-bind:key="index">
                            <a href="#" v-if="doc.id!=''"   @click.prevent="showAdd(doc.code)" class="dropdown-item">{{ $t(doc.name)}}</a>
                            <li v-if="doc.id ==''"  class="dropdown-submenu dropdown-hover ">
                                <a   href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle ">{{doc.name}}</a>
                                <ul   class="dropdown-menu dropdown-menu-md-right border-0 shadow  ">

                                <li v-for="(sub,index_sub) in doc.submenu" v-bind:key="index_sub">
                                    <a href="#"  @click.prevent="showAdd(sub.code)"  class="dropdown-item">{{sub.name}}</a>
                                </li>
                                </ul>
                            </li>
                        </div>

                      </div>
                  </div>

                </div> -->

            </div>
         </div>
        </div>
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
                            <div class="col-sm-6">
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

                              <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="miss_invoice">{{$t('form.document_by')}}</label>
                                <div class="col-md-2">
                                    <select name="" id="" v-model="form_filter.visibility" class="form-control form-control-sm mt-1">

                                        <option value="0">{{$t('form.owner')}}</option>
                                        <option value="">All</option>

                                    </select>

                                </div>

                        </div>
                        <div class="form-group row">
                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1"   for="">{{$t('form.status')}}</label>
                                <div class="col-sm-6 mt-1 mb-1">
                                    <treeselect  placeholder="All" :multiple="multiple"   :disable-branch-nodes="false" v-model="form_filter.status"  :options="status_option" />
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

                  <div class="col-md-9">
                    <div class="form-group row">
                      <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->

                       <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editDocument()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->
                       <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1"  title="In" @click="printRequest()"><i class="fas fa-print" title="In hợp đồng"></i></button> -->

                       <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  :title="$t('form.edit')" @click="editDocument()"><i class="fas fa-edit" :title="$t('form.edit')"></i>{{$t('form.edit')}}</button>
                       <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.delete')"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" :title="$t('form.delete')"></i> {{$t('form.delete')}} </button> -->
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.cancel')"  @click.prevent="cancelRequest()" > <i class="fas fa-window-close" :title="$t('form.cancel')"></i>  {{$t('form.cancel')}}</button>
                        <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-user-edit"></i> Phân công</button>
                        <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-tasks"></i> Xử lý</button>
                        <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.finished')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1" @click.prevent="notification_show()" ><i class="fas fa-bell"></i> {{$t('form.reminder')}}</button>

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
                <div  class="active tab-pane" id="yeucaumoi">
                  <div class="row">

                        <b-table striped hover responsive :bordered="true" head-variant="light"  :sticky-header="false"   small
                           :items="requests"

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
                             <template v-slot:cell(newtab)="data" >
                                <a target="_blank" :href="viewRequest(data.item.id)" ><i title="View in new tab" class="fas fa-external-link-alt"></i></a>
                           </template>
                           <template  v-slot:cell(selected)="data"   >
                               <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>
                           </template>
                          <!-- <template v-slot:cell(contract_num)="data">
                                <span v-if="data.item.contract"> {{data.item.contract.contract_num}}</span>
                           </template> -->
                            <template v-slot:cell(serial_num)="data">
                                 <div class="d-flex  justify-content-between">

                                  <a :href="viewRequest(data.item.id)"  >{{data.item.serial_num}}</a>


                                <b-button style="padding:0" class="ml-2"  :id="`popover-${data.item.id}`" v-if="data.item.reminders && data.item.reminders.length >0"   variant="link"  :title="$t('form.reminder')">
                                   <i  class="far fa-bell small"></i>
                                </b-button>
                                  <b-popover v-if="data.item.reminders && data.item.reminders.length >0"
                                  :target="`popover-${data.item.id}`"
                                  placement="auto"
                                  :title="$t('form.reminder')"
                                  triggers="hover focus"

                                >
                                 <template #title>{{$t('form.reminder')}}</template>
                                   <span v-for="reminder in data.item.reminders " v-bind:key="reminder.id">
                                     <li>{{reminder.content}}</li>

                                    </span>
                                </b-popover>


                                </div>

                           </template>
                             <template v-slot:cell(title)="data">
                                <span   > <a :href="viewRequest(data.item.id)">{{data.item.title}}</a> </span>
                           </template>
                            <!-- <template v-slot:cell(content)="data">
                                <span  > <a href="#" @click.prevent="printRequest(data.item.id)">{{data.item.content}}</a> </span>
                           </template> -->
                            <template v-slot:cell(feedback)="data"  >
                                <span   v-if="getApproveInfo(data.item.approveds) == 1" class="badge bg-warning" > X </span>
                           </template>
                            <template v-slot:cell(miss_invoice)="data"  >
                              <div style="text-align:center" >
                                <input type="checkbox" v-model="data.item.miss_invoice"   @change.prevent="setMissInvoice($event,data.item.id)" >
                              </div>

                           </template>
                           <template #cell(created_at)="data">
                                <span>{{data.value | formatDateDB}}</span>
                           </template>
                           <template #cell(send_date)="data">
                                <span>{{data.value | formatDateDB}}</span>
                           </template>
                           <template #cell(amount)="data">
                                <span   ><strong>{{data.value.toLocaleString(locale_format)}} </strong></span>
                           </template>
                           <template #cell(document_type_id)="data">
                                <span  v-if="data.item.document_type" >{{$t(data.item.document_type.name)}} </span>
                           </template>

                           <template #cell(status)="data">
                               <span v-if="data.value == 3" class="badge bg-success">{{$t('Đã thanh toán')}}</span>
                               <span v-else-if="data.value == 2" class="badge bg-primary">{{$t('Duyệt hoàn tất')}}</span>
                               <span v-else-if="data.value == 1" class="badge badge-secondary">{{$t('Đang xử lý')}} </span>
                               <span v-else-if="data.value == -1" class="badge bg-danger">{{$t('Đã huỷ')}} </span>
                               <span  v-else class="badge bg-warning" >{{$t('Yêu cầu mới')}} </span>
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


            </div>
               <!-- /.card-body -->
             <loading :loading="loading"></loading>

          </div>

          <!-- /.card -->
        </div>
      </div>

      <!-- dialog -->

       <create-event-dialog :object_id="selected" v-on:fromReminder="fromReminder" :id="reminder_id" module="ISSUE"></create-event-dialog>
       <!-- <dialog-update-hard-doc :object_id="selected" :obj="update_date" v-on:fromUpdateHardDoc="fromUpdateHardDoc"></dialog-update-hard-doc> -->
    </div>

</template>

<script>

import Loading from '../shared/Loading.vue';
 import Treeselect from '@riophae/vue-treeselect'
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
  props: {
     title:"",
  },
  components: { Loading,Treeselect},
  data () {
    return {
      document_code:"",
      requests:[],
      total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter:"",
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
      document_types:[],
      document_types_submenu:[],
      errors:{},
      status:"",
      locale_format:'de-DE',


      fields: [
          {
            key: 'selected',
            label:'All',
            stickyColumn: true
          },
            {
            key: 'newtab',
            label:"",

            stickyColumn: true,
             class:"text-nowrap"
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
              class:"text-nowrap",


          },

          {
            key: 'waiting_for_approval',
            label:this.$t('form.waiting_for_approval'),
            sortable: true,
            stickyColumn: true,
             sortByFormatted: true,
            class:"text-nowrap",
            formatter: (value, key, item) => {
               let nameUser ="";
               if(item.approveds.length > 0){
                 let approveItem = item.approveds[item.approveds_count-1];
                 if(approveItem.finished == 0 && approveItem.release == 0 && approveItem.online == 'X' )
                 {
                  nameUser =   approveItem.user?approveItem.user.name:"";
                 }
                return nameUser;
               }else{
                 return "";
               }
            },
          },
          {
          key: 'feedback',
          label:this.$t('form.feedback'),
          sortable: true,
          stickyColumn: true,
          tdClass:"text-center",
           class:"text-nowrap"
        },
         {
            key: 'title',
            label:this.$t('form.about'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
        //    {
        //     key: 'amount',
        //     label:this.$t('form.amount'),
        //     sortable: true,
        //     stickyColumn: true,
        //     class:"text-right"
        //   },
        //    {
        //     key: 'currency',
        //     label:this.$t('form.currency'),
        //     sortable: true,
        //     stickyColumn: true,
        //     class:"text-center text-nowrap"

        //   },
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
            key: 'send_date',
            label:this.$t('form.send_date'),
            sortable: true,
            stickyColumn: true,
             class:"text-center text-nowrap"
          },
                  {
          key: 'document_type_id',
            label:this.$t('form.document_type'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },

      ],
      tab:"",
       loading: false,
      edit: false,
      token:"",
      page_url_request : "/api/issues",

      page_url_vendors:"/api/category/vendors",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      page_url_document_type : "/api/category/documenttypes",
      page_url_document_type_sub_menu : "/api/category/documenttype/submenu",
    //   page_url_payment_update_paid : "/api/payment/update_paid",
    //   page_url_payment_miss_invoice_check : "/api/payment/miss_invoice_check",
      show_search:false,
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
        visibility:"",
      },
      status_option:[

        {id: '0',label: this.$t('Yêu cầu mới')},
        {id: '1',label: this.$t('Đang xử lý')},
        {id: '2',label: this.$t('Duyệt hoàn tất')},
        {id: '3',label: this.$t('Đã hoàn thành')},
        {id: '-1',label: this.$t('Đã huỷ')},
        // {id: '',label: 'All'},
      ],
      companies:[],
      departments:[],
      variant_data:[],
      vendors:[],
      selected:[],
      selectAll: false,
       multiple: true,
       save_filter:false,
       variant_name:'document_DocumentList',
    }
  },
  created() {

        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        //Thiết lập form tìm kiếm mặc định
        this.getVariant();
        this.init();
        //Lấy danh sách dựa vào form tìm kiếm
        this.fetCompany();
        this.fetDocumentType();
        this.fetDocumentTypeSubmenu();
        this.fetDepartment();
        this.fetVendor();
        // this.fetchRequest();

    },
    methods:{

     fromReminder(obj){


        if(this.selected.length != 1){

          return "";
         }

         var request_id  = this.selected;
         //alert(request_id);
         let index = this.requests.findIndex(item=>{
           return item.id == request_id;
         });

         this.requests[index].reminders.push(obj) ;

      },
      get_doc_name(){
        return "abc";
      },
      //Khởi tạo các giá trị trong form tìm kiếm
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
       fetDocumentTypeSubmenu() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_document_type_sub_menu+"?module=REPORT";
        fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {

                this.document_types_submenu = res.data;
                 // console.log(this.document_types_submenu);
            })
            .catch(err => console.log(err));
        },
       fetDocumentType() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_document_type+"?module=REPORT";
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
      showAdd(code){
        window.location.href="issues?type=add&doctype="+code;
      },
      fetchRequest(page,search){

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
                document_type_id: this.form_filter.document_type_id,
                department_id:      this.form_filter.department_id,
                 visibility:      this.form_filter.visibility,

              });
             var page_url = this.page_url_request +'?'+ params.toString();

            fetch(page_url,{

              headers:{
                Authorization:this.token,
                  "Content-Type": "application/json",
                  "Accept": "application/json",
                  "X-Requested-With": "XMLHttpRequest",
                }
              })

            .then(res=>res.json())
            .then(res=>{

                if(res.statuscode == "403" ){
                  window.location.href = "/errorpage?statuscode="+res.statuscode;
                }

                if(res.success == '1'){
                  this.requests = res.data;

                }
                this.loading = false;
            }).catch(err=>{
                console.log(err);
                 this.loading = false;

            });
      },
    editDocument(){

     if(this.selected.length != 1){
          toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
          return;
        }
    var id = this.selected;
    window.location.href= "issues?type=edit&id="+id;
    },
    viewRequest(id){
    return "issues?type=view&id="+id;
    // window.location.href= "issues?type=view&id="+id;
    },
    printRequest(){
        if(this.selected.length != 1){
          toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
          return;
        }
    var id = this.selected;
    window.location.href= "issues?type=print&id="+id;
    },

    cancelRequest(){
           if(this.selected.length != 1){
          toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
          return;
        }
        var id = this.selected;
        let index = this.requests.findIndex(i => {
          console.log("id"+i.id);
          return i.id == id;
        });
        console.log("id="+id);
        this.$bvModal.msgBoxConfirm(this.$t('Xác nhận huỷ')+"?",{
           title: '',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'danger',
          okTitle: 'OK',
          cancelTitle: 'Cancel',
          footerClass: 'p-2',
          hideHeaderClose: false,
          centered: true
        }).then(value=>{

          if(value){
            var  page_url = "/api/issues/update_cancel";
            fetch(page_url,{
                method:"POST",
                body:JSON.stringify({"id": ""+id}),
                headers:{
                    Authorization:this.token,
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                  }
                })
            .then(res=>res.json())
            .then(res=>{
                  if(res.statuscode == "403"){
                     toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
                    return;
                  }
                if(res.data.success == '1'){
                  //  toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
                   toastr.success(this.$t('form.cancel_success'),"");
                   this.requests[index].status = -1;
                   this.selected =[];

                }else{

                   toastr.warning(this.$t(res.data.message) ,this.$t('form.warning'));
                }

              }).catch(err=>console.log(err));
              }

        })
        .catch(err => {
            console.log(err);
         })
    },
    deleteRequest( ){

        if(this.selected.length != 1){
          toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
          return;
        }
        var id = this.selected;
        let index = this.requests.findIndex(i => {
          console.log("id"+i.id);
          return i.id == id;
        });
        console.log("id="+id);
        this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+"?",{
           title: '',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'danger',
          okTitle: 'OK',
          cancelTitle: 'Cancel',
          footerClass: 'p-2',
          hideHeaderClose: false,
          centered: true
        }).then(value=>{

          if(value){
            var  page_url = this.page_url_request+ "/"+id;
            fetch(page_url,{
                method:"DELETE",
                headers:{
                    Authorization:this.token,
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                  }
                })
            .then(res=>res.json())
            .then(res=>{
                  if(res.statuscode == "403"){

                    toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
                    return;
                  }
                if(res.data.success == '1'){
                  // this.requests = [];
                  this.requests.splice(index,1);
                  // this.$refs.selectableTable.refresh()
                   toastr.success(this.$t('form.success'),"");
                   this.selected =[];
                }else{
                  toastr.warning(this.$t(res.data.message),this.$t('form.warning'));
                }

              }).catch(err=>console.log(err));
              }

        })
        .catch(err => {
            console.log(err);
         })


        // if(confirm("Xác nhận xoá ?")){

        // }
      },
    searchRequest(){
        //alert($('#search').val());
        this.fetchRequest(null,$('#search').val());
      },
      setMissInvoice(event,payrequest_id){

        var miss_invoice = event.target.checked?1:0;
        var page_url = this.page_url_payment_miss_invoice_check;
        fetch(page_url,{
          method:"post",
          body:JSON.stringify({'id':payrequest_id,'miss_invoice':miss_invoice}),
          headers:{
            Authorization:this.token,
            'Content-Type':'applocation/json',
            'Accept':'applocation/json',
            'X-Requested-With':'XMLHttpRequest',
          }
        }).then(res=>res.json())
        .then(res=>{
                if(res.statuscode == "403"){
                  toastr.warning(res.message,"");
                  return;
                }
              if(res.data.success == '1'){

               if(res.data.miss_invoice == '1'){
                   toastr.success(this.$t('form.check_success'),"");
               }else{
                   toastr.success(this.$t('form.uncheck_success'),"");
               }
            }else{
              toastr.warning(this.$t('form.check_error'), "");
            }

        }).catch(err=>{

          console.log(err);
        })
      },
      getApproveInfo(listApprove){

        if(listApprove &&  listApprove.length > 0){

         var lastApprove = listApprove[listApprove.length -1];
           if(lastApprove && lastApprove.release == 1){
             // console.log(lastApprove.release);
              return lastApprove.release;
           }
        }
      },
      filter_data(){
          this.saveVariant([this.form_filter]);
         this.fetchRequest();
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

             this.fetchRequest();

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
       changeStatus(status){
        this.status = status;
        this.fetchRequest();

      },
      notification_show(){
         if(this.selected.length != 1){
             toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu') );
             return;

         }
         var id  = this.selected;
         let index = this.requests.findIndex(item=>{
           return item.id == id;
         });
          //this.$bvModal.show("create_event_dialog")
         //$("#create_event_dialog").dialog('show');
        //  $('#create_event_dialog').modal('show');
         $('#createEventModal').modal('show');


      },
      notification_show(){
         if(this.selected.length != 1){
             toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu') );
             return;

         }
         var id  = this.selected;
         let index = this.requests.findIndex(item=>{
           return item.id == id;
         });
          //this.$bvModal.show("create_event_dialog")
         //$("#create_event_dialog").dialog('show');
        //  $('#create_event_dialog').modal('show');
         $('#createEventModal').modal('show');


      },
      //cập nhật trạng thái đã thanh toán
       update_paid(){
         if(this.selected.length != 1){
             toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu') );
             return;

         }
         var id  = this.selected;
         let index = this.requests.findIndex(item=>{
           return item.id == id;
         });

        this.$bvModal.msgBoxConfirm(this.$t('Đã thanh toán')+ '?',{
          okVariant:'danger',
          okTitle:"OK",
          cancelTitle:"Cancel",
          centered:true
        }).then(value=>{
            if(value){
              var page_url = this.page_url_payment_update_paid;
              fetch(page_url,{
                method:"POST",
                body:JSON.stringify({'id':""+id}),
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
                    toastr.warning(res.message,"");
                    return;
                  }
                  if(res.data.success == '1'){
                    toastr.success(this.$t('form.update_success'),"");
                     this.requests[index].status = 3;
                    this.selected =[];
                  }else{
                    toastr.warning(this.$t('form.update_error'),"");
                  }
              }).catch(err=>{
                  console.log(err);
              })
            }
        }).catch(err=>{
            console.log(err);
        })
       },
    },
    computed:{
      document_type_no_groups(){
          let list = [];
          this.document_types.forEach(document_type => {
              //console.log(document_type.document_group);
              if (!document_type.document_group) {
                  list.push(document_type);
              }

          });
          return list;
      },
       document_type_groups(){
          let list = [];
          this.document_types.forEach(document_type => {

              if (document_type.document_group) {
                  if(!list.includes(document_type.document_group))
                  list.push(document_type.document_group);
              }

          });
          return list;
      },

      reminder_id(){
        var id ="";
        if(this.selected.length != 1){

          return "";
         }
         var request_id  = this.selected;
         let index = this.requests.findIndex(item=>{
           return item.id == request_id;
         });
         if(this.requests[index].reminder_one){
           id =  this.requests[index].reminder_one.id;
         }
         return id;
      },
      rows(){
        this.pageOptions= [10, 50, 100, 500, { value: this.requests.length, text: "All" }];
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
.dropdown-submenu > .dropdown-menu  {
    left:auto !important;
    margin-left: 10px;
    margin-top: 30px;
    top: 0;
    // right:auto !important;
    //   right: 100%;
}

</style>
