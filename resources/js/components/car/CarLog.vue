<template>
    <div>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h6 class="m-0 text-dark" style="padding-top: 10px;"><i class="fa fa-angle-double-right fa-xs" style="color: #96989a;"></i> {{$t('form.car_log')}}</h6>
            </div>
            <div class="col-sm-6">

                <div class="float-sm-right">

                  
                </div>

            </div>
         </div>
        </div>
      </div>
            <!-- <div class="row">
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="btn info-box-icon bg-info elevation-1"><i class="fa fa-paper-plane"  @click="fetchCarStatus(0)"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{$t('form.total')}}</span>
                <span class="info-box-number"> {{this.statistics.is_wfapprove}} 
                </span>
              </div>
            
            </div>
         
          </div>
         
            <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="btn info-box-icon bg-warning elevation-1" style="background-color: #ff9007!important;"><i class="fa fa-spinner" style="color: white;"  @click="fetchCarStatus(1)"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{$t('form.delay')}}</span>
                <span class="info-box-number"> {{this.statistics.is_wfapprove_delay}} </span>
              </div>
             
            </div>
           
          </div>
         
          <div class="clearfix hidden-md-up"></div>


         
        </div> -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <!-- /.card-header -->
            <div class="card-body">

                  <div class="row mt-0">

                  <div class="col-md-9">
                    <div class="form-group row">
                      <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->
                      <div class="btn-group ">
                        <button type="button" class="btn btn-info btn-xs" @click="showSearch()">
                          <span v-if="!show_search">{{$t('form.show_search')}}</span>
                          <span v-if="show_search">{{$t('form.hide_search')}}</span>
                        </button>
                        <button type="button" class="btn btn-info btn-xs " @click="showSearch()" >
                        <i v-if="show_search" class="fas fa-angle-up"></i>
                        <i v-else class="fas fa-angle-down"></i>
                        </button>

                        </div>
                        <!-- <button type="button" :title="$t('form.filter')" onclick="location.reload(true)" class="btn btn-secondary  btn-xs ml-1" ><i class="fas fa-redo-alt" title="Refresh"></i></button> -->
                        
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

                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"   :title="$t('form.car_num')" for="">{{$t('form.car_num')}}</label>
                                <div class="col-sm-2">
                                <input type="text" v-model="form_filter.serial_num" class="form-control form-control-sm mt-1">
                                </div>
                            </div>
                        <div class="form-group row">
                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"  for="">{{$t('form.type')}}</label>
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
                           <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"></label>
                                <div class="col-sm-2">
                                <select name="" id="" v-model="form_filter.standard_id" class="form-control form-control-sm mt-1">
                                    <option v-for="standard in standards" v-bind:key="standard.id" v-bind:value="standard.id">

                                    {{$t(standard.name) }}
                                    </option>

                                    <option value="">All</option>
                                </select>
                                </div>
                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right">Người tạo</label>
                            <div class="col-sm-2">
                            <select name="" id="" v-model="form_filter.user_id" class="form-control form-control-sm mt-1">
                                    <option v-for="user_create in user_creates" v-bind:key="user_create.id" v-bind:value="user_create.user_id">
                                    {{$t(user_create.user.name) }}
                                    </option>

                                    <option value="">All</option>
                                </select>
                            </div>
                            <!-- <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right">Người duyệt</label> -->
                            <div class="col-sm-2">
                            </div>
                        </div>
                           <div class="form-group row">
                           <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right"></label>
                                <div class="col-sm-2">
                                <select name="" id="" v-model="form_filter.type_car_id" class="form-control form-control-sm mt-1">
                                    <option v-for="type_car in typecars" v-bind:key="type_car.id" v-bind:value="type_car.id">

                                    {{$t(type_car.name) }}
                                    </option>

                                    <option value="">All</option>
                                </select>
                                </div>
                          
                            <div class="col-sm-2">
                            
                            </div>
                            <!-- <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right">Người duyệt</label> -->
                            <div class="col-sm-2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-form-label-sm col-sm-2 col-form-label text-left  text-md-right mt-1"   for="">{{$t('form.status')}}</label>
                                <div class="col-sm-6 mt-1 mb-1">
                                    <treeselect  placeholder="All" :multiple="multiple"   :disable-branch-nodes="false" v-model="form_filter.status"  :options="status_option" />
                                </div>

                        </div>
                        <div class="col-md-12" style="text-align:center" >
                            <button type="submit" class="btn btn-info btn-sm mt-1 mb-1" @click="filter_data()"> <i class="fa fa-search"></i> {{$t('form.find')}} </button>
                            <button type="reset" class="btn btn-secondary btn-sm mt-1 mb-1" @click="clearFilter()"> <i class="fa fa-reset"></i> {{$t('form.clear')}}</button>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" v-model="save_filter" >
                                <label class="form-check-label" for="save_filter"> <i>{{$t('form.save_filter')}}</i>  </label>
                            </div>
                            </div>
                    </div>


                    </div>
                <div class="row mt-1">

                  <div class="col-md-9">
                    <div class="form-group row">
                      <!-- <button type="button" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>Tạo hợp đồng</button> -->

                       <!-- <button type="button" class="btn btn-warning btn-sm ml-1 mt-1"  title="Sửa" @click="editCar()"><i class="fas fa-edit" title="Sửa hợp đồng"></i></button>
                       <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" title="Xoá"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" title="Xoá hợp đồng"></i></button> -->
                       <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1"  title="In" @click="printRequest()"><i class="fas fa-print" title="In hợp đồng"></i></button> -->

                       <!-- <button type="button" class="btn btn-default btn-sm ml-1 mt-1"  :title="$t('form.edit')" @click="editCar()"><i class="fas fa-edit" :title="$t('form.edit')"></i>{{$t('form.edit')}}</button> -->
                       <!-- <button type="button" class="btn btn-danger  btn-sm ml-1 mt-1" :title="$t('form.delete')"  @click.prevent="deleteRequest()" ><i class="fas fa-trash-alt" :title="$t('form.delete')"></i> {{$t('form.delete')}} </button> -->
                       <!-- <button type="button" class="btn btn-default  btn-sm ml-1 mt-1" :title="$t('form.cancel')"  @click.prevent="cancelCar()" > <i class="fa fa-download" :title="Excel"></i> Excel </button> -->
                       <!-- <download-excel
                        class="btn btn-default  btn-sm ml-1 mt-1"
                         :fields="fields1"
                          :data="tableData"
                          name="Phiếu car"
                          type="xls"
                      >
                      <i class="fa fa-download"></i>
                       Excel
                      </download-excel> -->
                       <button type="button" @click="filter_data()" class="btn btn-default  btn-sm ml-1 mt-1"   ><i class="fas fa-sync-alt" :title="$t('form.reload')"></i></button>
                        <!-- <button type="button" class="btn btn-success btn-sm ml-1 mt-1" @click.prevent="update_paid()" ><i class="fas fa-bookmark"></i> {{$t('form.paid')}}</button> 
                        <button type="button" class="btn btn-secondary btn-sm ml-1 mt-1" @click.prevent="notification_show()" ><i class="fas fa-bell"></i> {{$t('form.reminder')}}</button>-->

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

                        <b-table striped hover responsive  :sticky-header="false" 
                           :items="requests"
                            primary-key="id"
                           :current-page="current_page"
                           :per-page="per_page"
                            :filter="filter"
                            :filter-included-fields="['serial_num','created_at']"
                            selectable
                             ref="selectableTable"
                             filter-debounce="1000"
                           :fields="fields">
                            <template #head(selected)>
                              <!-- {{selected}} -->
                               <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"     ></b-form-checkbox>
                              </template>

                           <template  v-slot:cell(selected)="data"   >
                               <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>
                           </template>
                            <template #cell(user_id)="data">
                                <span  v-if="data.item.user" >{{$t(data.item.user.name)}} </span>
                           </template>
                            <template v-slot:cell(serial_num)="data">
                                <div class="d-flex  justify-content-between">
                                  <a :href="viewRequest(data.item.id)"  >{{data.item.serial_num}}</a>
                                </div>
                           </template>
                             <template v-slot:cell(approved)="data">
                                <span> <a :href="viewRequest(data.item.id)" style="color: #212529;">
                                <table>
                                <th>Người duyệt</th>
                                <th>Ngày duyệt</th>
                                <th>Ngày mong đợi</th>
                                <th>online</th>
                                <th>Trạng thái</th>
                                <th>Bước</th>
                                <tr v-for="approve in data.item.approveds" :key="approve.id">
                                <td>{{approve.user.name}}</td>
                                <td>{{approve.checkout}}</td>
                                <td>{{approve.expected_time}}</td>
                                <td v-if="approve.online=='X'"><i class="fa fa-check-circle fa-xs" style="color: rgb(7, 186, 7);"></i></td>
                                <td v-if="approve.online=='Y'"><i class="fa fa-check-circle fa-xs" style="color: red;"></i></td>
                                <td v-if="approve.release==null"></td>
                                <td v-if="approve.release==2"><i class="fa fa-check-circle fa-xs" style="color: rgb(7, 186, 7);"></i></td>
                                <td v-if="approve.release===-2"><i class="fa fa-check-circle fa-xs" style="color: red;"></i></td>
                                <td>{{approve.step}}</td>
                                </tr>
                                </table>
                                </a> 
                                </span>
                           </template>

                           <template #cell(created_at)="data">
                                <span>{{data.value | formatDateDB}}</span>
                           </template>

                           <template #cell(status)="data">
                               <span v-if="data.value == 2" class="badge bg-primary">{{$t('Duyệt hoàn tất')}}</span>
                               <span v-else-if="data.value == 1" class="badge badge-warning">{{$t('Đang xử lý')}} </span>
                               <span v-else-if="data.value == -1" class="badge bg-danger">{{$t('Đã huỷ')}} </span>
                               <span v-else-if="data.value == -2" class="badge bg-danger">{{$t('Từ chối')}} </span>
                               <span  v-else class="badge bg-info">{{$t('Yêu cầu mới')}} </span>
                            </template>
                           <template #cell(document_type_id)="data">
                                <span  v-if="data.item.document_type" >{{$t(data.item.document_type.code)}} </span>
                           </template> 
                      </b-table>
                     
                             <div class="col-md-11" >
                             <div class="float-sm-right">
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
      <create-event-dialog :object_id="selected" v-on:fromReminder="fromReminder" :id="reminder_id" module="PRICE"></create-event-dialog>

       <!-- <dialog-update-hard-doc :object_id="selected" :obj="update_date" v-on:fromUpdateHardDoc="fromUpdateHardDoc"></dialog-update-hard-doc> -->

    </div>

</template>

<script>


import FormFilter from "./FormFilter.vue";
import Loading from '../shared/Loading.vue';
import Treeselect from '@riophae/vue-treeselect';
import moment from 'moment';
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
// import  DialogUpdateHardDoc  from "./DialogUpdateHardDoc.vue";
export default {
  props: {
     title:"",
  },
  components: { Loading,Treeselect ,FormFilter},
  data () {
    return {
     tableData: [],
     car:0,
      fields1: {
                 STT: "STT",
                 Người_tạo: "Người_tạo",
                 Nhân_viên_QA: "Nhân_viên_QA",
                 Ngày_phát_hành_phiếu: "Ngày_phát_hành_phiếu",
                 Ngày_tới_hạn: "Ngày_tới_hạn",
                 Ngày_nhập_nguyên_nhân: "Ngày_nhập_nguyên_nhân",
                 Số_lần_tái_diễn: "Số_lần_tái_diễn",
                 Số_phiếu: "Số_phiếu",
                 Công_ty: "Công_ty",
                 Phòng_ban: "Phòng_ban",
                 Sự_không_phù_hợp: "Sự_không_phù_hợp",
                 Mức_độ_lỗi: "Mức_độ_lỗi",
                 Trạng_thái: "Trạng_thái",
                 Loại_phiếu:'Loại_phiếu',
                 Loại_phiếu_car_hệ_thống:'Loại_phiếu_car_hệ_thống',
                 Xử_lý_tức_thời: 'Xử_lý_tức_thời',
                 Nguyên_nhân: 'Nguyên_nhân',
                 Biện_pháp_khắc_phuc: 'Biện_pháp_khắc_phuc',
                 Thời_gian_thực_hiện: 'Thời_gian_thực_hiện',
                 Kết_quả_giám_sát_thực_hiện: 'Kết_quả_giám_sát_thực_hiện',
                 Ngày_giám_sát_thực_hiện: 'Ngày_giám_sát_thực_hiện',
                 Nội_dung_đánh_giá: 'Nội_dung_đánh_giá',
                 Kết_quả_đánh_giá: 'Kết_quả_đánh_giá',
                 Ngày_đánh_giá: 'Ngày_đánh_giá',
      },
      variant_name:'car_CarStatistical',
      requests:[],
      total: 0,
      per_page: 10,
      from: 1,
      to: 0,
      current_page: 1,
      filter:"",
      pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
      document_types:[],
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
            key: 'serial_num',
            label:this.$t('form.car_num'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
          {
            key: 'user_id',
            label:this.$t('form.create_by'),
            sortable: true,
            stickyColumn: true,
            class:"text-nowrap"
          },
           {
            key: 'approved',
            label:this.$t('form.approved'),
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
            key: 'created_at',
            label:this.$t('form.create_date'),
            sortable: true,
            stickyColumn: true,
             class:"text-center text-nowrap"
          },
           {
          key: 'document_type_id',
            label:this.$t('form.car_type'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },


      ],
      tab:"",
       loading: false,
      edit: false,
      token:"",
      page_url_request : "/api/car/index_statistics",
      page_url_department : "/api/category/groups",
      page_url_company:"/api/category/companies",
      page_url_document_type : "/api/category/documenttypes",
      page_url_statistic : "/api/car/statistics",
      page_url_statistic_status : "/api/car/statistic_status",
      page_url_typecars:"api/category/typecars",
      page_url_standards:"api/category/standards",
      page_url_carlogs:"api/car/log_approves",
      page_url_user_creates:"api/car/get_user_create",
      show_search:false,
     form_filter:{
        issue_date:"",
        user_id:"",
        company_id:"",
        status:[],
        department_id:"",
        document_type_id:"",
        type_car_id:"",
        standard_id	:"",
        serial_num:"",
        content:"",
        issue_count:"",
        created_at:"",
      },
      variant_data:[],
      update_date:{
        id:"",
        date:"",
      },
        status_option:[

        {id: '0',label: this.$t('Yêu cầu mới')},
        {id: '1',label: this.$t('Đang xử lý')},
        {id: '2',label: this.$t('Duyệt hoàn tất')},
        {id: '-1',label: this.$t('Đã huỷ')},
        {id: '-2',label: this.$t('Từ chối')},
        // {id: '',label: 'All'},
      ],
      companies:[],
      typecars:[],
      standards:[],
      user_creates:[],
      departments:[],
      statistics:[],
      selected:[],
      selectAll: false,
      save_filter:false,
      multiple: true
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
        this.fetDepartment();
        this.fetStatistics();
        this.fetTypeCar();
        this.fetStandard();
        this.fetCreateCar();
        //this.moreData();


    },
    methods:{
    
    // moreData() {
 
    // },
    eventSearchData(data){
        this.form_filter = data;
         this.fetchRequest();

    },
    filter_data(){
        this.saveVariant([this.form_filter]);
        //console.log(this.form_filter);
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
      getReminderContent(reminders){
        var str ="<ul>";
        reminders.forEach(element => {
          str +="<li>"+element.content+"</li>";
        });
         str +="</ul>";
        return  str;

      },
      fromReminder(obj){


        if(this.selected.length != 1){

          return "";
         }

         var request_id  = this.selected;
         //alert(request_id);
         let index = this.requests.findIndex(item=>{
           return item.id == request_id;
         });
         console.log(this.requests[index]);
         this.requests[index].reminders.push(obj) ;

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
       fetDocumentType() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_document_type+"?module=CARS";
        fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {
                //console.log("Xin chao");
                this.document_types = res.data;
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
       fetTypeCar() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_typecars;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.typecars = res.data;
                })
                .catch(err => console.log(err));
        },
        fetStandard() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_standards;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.standards = res.data;
                })
                .catch(err => console.log(err));
        },
        fetCreateCar() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_user_creates;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.user_creates = res.data;
                    //console.log(this.user_creates);
                })
                .catch(err => console.log(err));
        },
       fetStatistics() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
        var page_url = this.page_url_carlogs;
        fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {
                // console.log(res.is_new);
                this.statistics = res;
               
            })
            .catch(err => console.log(err));
        },
      showAdd(code){
        window.location.href="car/systems?type=add&doctype="+code;
      },
      fetchRequest(page,search){

             this.loading = true;
             this.requests=Array();
              const params = new URLSearchParams({
                status: this.form_filter.status,
                issue_date:   this.form_filter.issue_date,
                user_id:   this.form_filter.user_id,
                company_id:     this.form_filter.company_id,
                department_id:       this.form_filter.department_id,
                document_type_id:  this.form_filter.document_type_id,
                type_car_id:       this.form_filter.type_car_id,
                standard_id:       this.form_filter.standard_id,
                serial_num:      this.form_filter.serial_num,
                content:      this.form_filter.content,
                issue_count: this.form_filter.issue_count,
                created_at:      this.form_filter.created_at,
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
                  var list = [];
                 
                
                 
                  
               for (let i = 0; i <this.requests.length ; i++) {
                  var fast_process="";
                 if(this.requests[i].fast_process.length>0 && this.requests[i].document_type.code==='PCAR'){
                   for(let $j=0;$j<this.requests[i].fast_process.length;$j++){
                       //console.log(this.requests[i].document_type.code);
                      fast_process += this.requests[i].fast_process[$j].content.replace(/<\/?[^>]+(>|$)/g, "")+"<br/>";
                    
                   }
                 }
                  if(this.requests[i].cause_measure.length>0){
                      var cause="";
                      var is_measure="";
                      var is_date_cause ="";
                      var is_create_cause = "";
                   for(let $j=0;$j<this.requests[i].cause_measure.length;$j++){
                     cause += this.requests[i].cause_measure[$j].cause+"<br/>";
                     is_measure += this.requests[i].cause_measure[$j].measure+"<br/>";
                     is_date_cause += moment(this.requests[i].cause_measure[$j].date).format('MM/DD/YYYY')+"<br/>";
                     is_create_cause += moment(this.requests[i].created_at[$j].date).format('MM/DD/YYYY')+"<br/>";
                   }
                 }
                 //console.log(this.measure);
                   if(this.requests[i].monitor_implementation.length>0){
                      var monitor_implementation="";
                      var is_date_monitor="";
                   for(let $j=0;$j<this.requests[i].monitor_implementation.length;$j++){
                      is_date_monitor += moment(this.requests[i].monitor_implementation[$j].date).format('MM/DD/YYYY')+"<br/>";
                     if(this.requests[i].monitor_implementation[$j].result=='0')
                     monitor_implementation += 'Hoàn thành'+"<br/>";
                     else
                      monitor_implementation += 'Chưa hoàn thành'+"<br/>";
                   }
                 }
                  if(this.requests[i].result_evaluation.length>0){
                    var result_evaluation ="";
                    var content_evaluation ="";
                    var is_date_result ="";
                   for(let $j=0;$j<this.requests[i].result_evaluation.length;$j++){
                       content_evaluation += this.requests[i].result_evaluation[$j].content.replace(/<\/?[^>]+(>|$)/g, "")+"<br/>";
                       is_date_result += moment(this.requests[i].result_evaluation[$j].date).format('MM/DD/YYYY')+"<br/>";
                     if(this.requests[i].result_evaluation[$j].result=='0')
                     result_evaluation += 'Đạt'+"<br/>";
                     else if(this.requests[i].result_evaluation[$j].result=='1')
                     result_evaluation += 'Không đạt'+"<br/>";
                     else
                     result_evaluation += 'Không có dữ liệu'+"<br/>";
                   }
                 }
                  var is_status ='';
                 if(this.requests[i].status=='0'){
                   is_status = 'Yêu cầu mới';
                 }else if(this.requests[i].status=='1'){
                    is_status = 'Đang xử lý';
                 }else if(this.requests[i].status=='2'){
                    is_status = 'Duyệt hoàn tất';
                 }
                 else if(this.requests[i].status=='-1'){
                   is_status = 'Đã huỷ';
                 }
                  else if(this.requests[i].status=='-2'){
                   is_status = 'Từ chối';
                 }
                 var is_date= moment(this.requests[i].issue_date).format('MM/DD/YYYY');
                 var is_date_to_limit = "";
                 is_date_to_limit = moment(this.requests[i].date_to_limit).format('MM/DD/YYYY');
                 
                 var type_car= "";
                 if(this.requests[i].type_car_id !=null)
                 type_car = this.requests[i].type_car.name;

                 var name_error = "";
                 if(this.requests[i].car_error_id !=null){
                   name_error = this.requests[i].car_error.name;
                 }else{
                   name_error ="";
                 }
               // console.log(this.requests[i].car_error.name);
                list.push({
                  STT: i,
                  Người_tạo: this.requests[i].user.name,
                  Nhân_viên_QA: this.requests[i].proposer.name,
                  Ngày_phát_hành_phiếu: is_date,
                  Ngày_tới_hạn: is_date_to_limit,
                  Ngày_nhập_nguyên_nhân: is_create_cause,
                  Số_lần_tái_diễn: this.requests[i].issue_count,
                  Số_phiếu: this.requests[i].serial_num,
                  Công_ty: this.requests[i].company.name,
                  Phòng_ban: this.requests[i].department.code,
                  Sự_không_phù_hợp: this.requests[i].content.replace(/<\/?[^>]+(>|$)/g, ""),
                  Mức_độ_lỗi: name_error,
                  Trạng_thái: is_status,
                  Loại_phiếu: this.requests[i].document_type.code,
                  Loại_phiếu_car_hệ_thống: type_car,
                  Xử_lý_tức_thời:  fast_process,
                  Nguyên_nhân: cause,
                  Biện_pháp_khắc_phuc: is_measure,
                  Thời_gian_thực_hiện: is_date_cause,
                  Kết_quả_giám_sát_thực_hiện: monitor_implementation,
                  Ngày_giám_sát_thực_hiện: is_date_monitor,
                  Nội_dung_đánh_giá: content_evaluation,
                  Kết_quả_đánh_giá: result_evaluation,
                  Ngày_đánh_giá: is_date_result,
                });
              }
              this.tableData = list;
               }
                this.loading = false;
            }).catch(err=>{
                console.log(err);
                 this.loading = false;

            });
      },
      fetchCarStatus(status){
             this.loading = true;
             this.requests=Array();
             var page_url = this.page_url_statistic_status +'?status='+status;

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
                  var list = [];
                 
                
                 
                  
               for (let i = 0; i <this.requests.length ; i++) {
                  var fast_process="";
                 if(this.requests[i].fast_process.length>0 && this.requests[i].document_type.code==='PCAR'){
                   for(let $j=0;$j<this.requests[i].fast_process.length;$j++){
                       //console.log(this.requests[i].document_type.code);
                      fast_process += this.requests[i].fast_process[$j].content.replace(/<\/?[^>]+(>|$)/g, "")+"<br/>";
                    
                   }
                 }
                  if(this.requests[i].cause_measure.length>0){
                      var cause="";
                      var is_measure="";
                      var is_date_cause ="";
                      var is_create_cause ="";
                   for(let $j=0;$j<this.requests[i].cause_measure.length;$j++){
                     cause += this.requests[i].cause_measure[$j].cause+"<br/>";
                     is_measure += this.requests[i].cause_measure[$j].measure+"<br/>";
                     is_date_cause += moment(this.requests[i].cause_measure[$j].date).format('MM/DD/YYYY')+"<br/>";
                     is_create_cause += moment(this.requests[i].created_at[$j].date).format('MM/DD/YYYY')+"<br/>";
                   }
                 }
                 //console.log(this.measure);
                   if(this.requests[i].monitor_implementation.length>0){
                      var monitor_implementation="";
                      var is_date_monitor="";
                   for(let $j=0;$j<this.requests[i].monitor_implementation.length;$j++){
                      is_date_monitor += moment(this.requests[i].monitor_implementation[$j].date).format('MM/DD/YYYY')+"<br/>";
                     if(this.requests[i].monitor_implementation[$j].result=='0')
                     monitor_implementation += 'Hoàn thành'+"<br/>";
                     else
                      monitor_implementation += 'Chưa hoàn thành'+"<br/>";
                   }
                 }
                  if(this.requests[i].result_evaluation.length>0){
                    var result_evaluation ="";
                    var content_evaluation ="";
                    var is_date_result ="";
                   for(let $j=0;$j<this.requests[i].result_evaluation.length;$j++){
                       content_evaluation += this.requests[i].result_evaluation[$j].content.replace(/<\/?[^>]+(>|$)/g, "")+"<br/>";
                       is_date_result += moment(this.requests[i].result_evaluation[$j].date).format('MM/DD/YYYY')+"<br/>";
                     if(this.requests[i].result_evaluation[$j].result=='0')
                     result_evaluation += 'Đạt'+"<br/>";
                     else if(this.requests[i].result_evaluation[$j].result=='1')
                     result_evaluation += 'Không đạt'+"<br/>";
                     else
                     result_evaluation += 'Không có dữ liệu'+"<br/>";
                   }
                 }
                  var is_status ='';
                 if(this.requests[i].status=='0'){
                   is_status = 'Yêu cầu mới';
                 }else if(this.requests[i].status=='1'){
                    is_status = 'Đang xử lý';
                 }else if(this.requests[i].status=='2'){
                    is_status = 'Duyệt hoàn tất';
                 }
                 else if(this.requests[i].status=='-1'){
                   is_status = 'Đã huỷ';
                 }
                  else if(this.requests[i].status=='-2'){
                   is_status = 'Từ chối';
                 }
                 var is_date= moment(this.requests[i].issue_date).format('MM/DD/YYYY');
                 var is_date_to_limit = "";
                 is_date_to_limit = moment(this.requests[i].date_to_limit).format('MM/DD/YYYY');
               
                var type_car= "";
                 if(this.requests[i].type_car_id !=null)
                 type_car = this.requests[i].type_car.name;

                 var name_error = "";
                 if(this.requests[i].car_error_id !=null){
                   name_error = this.requests[i].car_error.name;
                 }else{
                   name_error ="";
                 }
               // console.log(this.requests[i].car_error.name);
                list.push({
                  STT: i,
                  Người_tạo: this.requests[i].user.name,
                  Nhân_viên_QA: this.requests[i].proposer.name,
                  Ngày_phát_hành_phiếu: is_date,
                  Ngày_tới_hạn: is_date_to_limit,
                  Ngày_nhập_nguyên_nhân: is_create_cause,
                  Số_lần_tái_diễn: this.requests[i].issue_count,
                  Số_phiếu: this.requests[i].serial_num,
                  Công_ty: this.requests[i].company.name,
                  Phòng_ban: this.requests[i].department.code,
                  Sự_không_phù_hợp: this.requests[i].content.replace(/<\/?[^>]+(>|$)/g, ""),
                  Mức_độ_lỗi: name_error,
                  Trạng_thái: is_status,
                  Loại_phiếu: this.requests[i].document_type.code,
                  Loại_phiếu_car_hệ_thống:  type_car,
                  Xử_lý_tức_thời:  fast_process,
                  Nguyên_nhân: cause,
                  Biện_pháp_khắc_phuc: is_measure,
                  Thời_gian_thực_hiện: is_date_cause,
                  Kết_quả_giám_sát_thực_hiện: monitor_implementation,
                  Ngày_giám_sát_thực_hiện: is_date_monitor,
                  Nội_dung_đánh_giá: content_evaluation,
                  Kết_quả_đánh_giá: result_evaluation,
                  Ngày_đánh_giá: is_date_result,
                });
              }
              this.tableData = list;
               }
                this.loading = false;
            }).catch(err=>{
                console.log(err);
                 this.loading = false;

            });
      },
    // editCar(){

    //  if(this.selected.length != 1){
    //       toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
    //       return;
    //     }
    //     var id = this.selected; 
    //     window.location.href= "car/systems?type=edit&id="+id;
    //      //console.log(window.location.href);
    // },
    viewRequest(id){
    //window.location.href= "payment/requests?type=view&id="+id;
      return "car/carlog?type=view&id="+id;
    },
    // printRequest(){
    //     if(this.selected.length != 1){
    //       toastr.info('Vui lòng chọn 1 dòng dữ liệu',"");
    //       return;
    //     }
    // var id = this.selected;
    // window.location.href= "payment/requests?type=print&id="+id;
    // },

    // cancelCar(){
    //        if(this.selected.length != 1){
    //       toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
    //       return;
    //     }
    //     var id = this.selected;
    //     let index = this.requests.findIndex(i => {
    //       //console.log("id"+i.id);
    //       return i.id == id;
    //     });
    //    // console.log("id="+id);
    //     this.$bvModal.msgBoxConfirm(this.$t('Xác nhận huỷ')+"?",{
    //        title: '',
    //       size: 'sm',
    //       buttonSize: 'sm',
    //       okVariant: 'danger',
    //       okTitle: 'OK',
    //       cancelTitle: 'Cancel',
    //       footerClass: 'p-2',
    //       hideHeaderClose: false,
    //       centered: true
    //     }).then(value=>{

    //       if(value){
    //         var  page_url = "/api/car/systems/update_cancel";
    //         fetch(page_url,{
    //             method:"POST",
    //             body:JSON.stringify({"id": ""+id}),
    //             headers:{
    //                 Authorization:this.token,
    //                 "Content-Type": "application/json",
    //                 "Accept": "application/json",
    //                 "X-Requested-With": "XMLHttpRequest",
    //               }
    //             })
    //         .then(res=>res.json())
    //         .then(res=>{
    //               if(res.statuscode == "403"){
    //                  toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
    //                 return;
    //               }
    //             if(res.data.success == '1'){
    //               //  toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
    //                toastr.success(this.$t('form.cancel_success'),"");
    //                this.requests[index].status = -1;
    //                this.selected =[];

    //             }else{

    //                toastr.warning(this.$t(res.data.message) ,this.$t('form.warning'));
    //             }

    //           }).catch(err=>console.log(err));
    //           }

    //     })
    //     .catch(err => {
    //         console.log(err);
    //      })
    // },
    // deleteRequest( ){

    //     if(this.selected.length != 1){
    //       toastr.info(this.$t('Vui lòng chọn 1 dòng dữ liệu'),"");
    //       return;
    //     }
    //     var id = this.selected;
    //     let index = this.requests.findIndex(i => {
    //       //console.log("id"+i.id);
    //       return i.id == id;
    //     });
    //     //console.log("id="+id);
    //     this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+"?",{
    //        title: '',
    //       size: 'sm',
    //       buttonSize: 'sm',
    //       okVariant: 'danger',
    //       okTitle: 'OK',
    //       cancelTitle: 'Cancel',
    //       footerClass: 'p-2',
    //       hideHeaderClose: false,
    //       centered: true
    //     }).then(value=>{

    //       if(value){
    //         var  page_url = this.page_url_request+ "/"+id;
    //         fetch(page_url,{
    //             method:"DELETE",
    //             headers:{
    //                 Authorization:this.token,
    //                 "Content-Type": "application/json",
    //                 "Accept": "application/json",
    //                 "X-Requested-With": "XMLHttpRequest",
    //               }
    //             })
    //         .then(res=>res.json())
    //         .then(res=>{
    //               if(res.statuscode == "403"){

    //                 toastr.warning(this.$t(res.message) ,this.$t('form.warning'));
    //                 return;
    //               }
    //             if(res.data.success == '1'){
    //               // this.requests = [];
    //               this.requests.splice(index,1);
    //               // this.$refs.selectableTable.refresh()
    //                toastr.success(this.$t('form.success'),"");
    //                this.selected =[];
    //             }else{
    //               toastr.warning(this.$t(res.data.message),this.$t('form.warning'));
    //             }

    //           }).catch(err=>console.log(err));
    //           }

    //     })
    //     .catch(err => {
    //         console.log(err);
    //      })


    //     // if(confirm("Xác nhận xoá ?")){

    //     // }
    //   },
    searchRequest(){
        //alert($('#search').val());
        this.fetchRequest(null,$('#search').val());
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

    },
    computed:{
      reminder_id(){
        var id ="";

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
.badge-warning {
    color: #ffffff;
    background-color: #ff9007!important;
}
.bg-primary {
    background-color: #28a745!important;
}
 .form-group {
    margin-bottom: 1px  !important;
}
.card-primary.card-outline {
    border-top: 3px solid #17a2b8;
}
.dropdown-item:hover,
.dropdown-item:focus {
  color: #ffffff;
  text-decoration: none;
  background-color: #17a2b8;
}

</style>
