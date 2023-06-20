<template>
    <div>
        <div class="content-header " >
            <div class="container-fluid ml-0">
            <div class="row">
                <div class="col-md-6">
                <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
                <ol class="breadcrumb ">
                <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="#" @click.prevent="backToList()">{{ $t(pre_title)}}</a> </h5></li>

                    <li class="breadcrumb-item active">

                    <span >{{$t('form.detail')}}</span>

                    </li>
                </ol>
                </div>
                <div class="col-md-6" >
                <div class="float-sm-right "  style="margin-top:-5px; ">
                     <button v-if="user_id == praprequest.user_id && praprequest.locked != 1" class="btn  btn-default" @click.prevent="editDocument()"><i class="fas fa-edit"></i>{{$t('form.edit')}}</button>
                    <button class="btn btn-default" @click.prevent="print()"><i class="fas fa-print"></i></button>
                </div>
                </div>
            </div>
            </div>
        </div>
         <!-- ----------------- -->
        <div class="row">
             <div class="col-md-8">
                  <div class="card">
                       <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5 ">{{$t('form.company')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" v-if="praprequest.company" >{{praprequest.company.name}}</label>
                                            <label class="col-form-label-sm" v-if="!praprequest.company" >&nbsp;</label>
                                        </div>
                                     </div>
                                      <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.department')}}:</span>
                                        <div class="col-sm-7">
                                            <label  class="col-form-label-sm" v-if="praprequest.department && praprequest.group">{{praprequest.department.code}}/{{praprequest.group.name}}</label>
                                            <label  class="col-form-label-sm" v-if="!praprequest.department">&nbsp;</label>
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.document_num')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm" > {{praprequest.serial_num}} </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.proposer_payment')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm"  v-if="praprequest.proposer">{{praprequest.proposer.name}} </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.vendor')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm"  v-if="praprequest.vendor">{{praprequest.vendor.comp_name}} </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.title')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm"  >{{praprequest.title}} </label>
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.effective_date')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm"  >{{praprequest.effective_date | formatDate}} </label>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.purchase_note')}}:</span>
                                            <div class="col-sm-7">
                                                <span   v-html="praprequest.purchase_note" >  </span>

                                            </div>
                                        </div> -->
                                         <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.currency')}}:</span>
                                            <div class="col-sm-7">
                                                <label class="col-form-label-sm"  >{{praprequest.currency }} </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-form-label-sm col-sm-5">{{$t('form.price_approval_file')}}:</span>
                                            <div class="col-sm-7">
                                                 <ul class="list-unstyled mt-0">
                                                    <li v-for="(file,findex) in praprequest.attachment_file" v-bind:key="findex" class="itemfile">
                                                    <a  href="#" @click.prevent="downloadFile(file.id,file.name)" class="btn-link text-secondary text-nowrap"><i  v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a>

                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                </div>
                                 <div class="col-md-6">
                                     <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.material_type_name')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" > {{praprequest.material_type_name}} </label>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.method_payment_name')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" > {{praprequest.method_payment_name}} </label>
                                        </div>
                                    </div>

                                      <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.principle_contract')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" > {{praprequest.contract_num}} </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.diff_info')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" > {{praprequest.diff_info}} </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.is_order')}}:</span>
                                        <div class="col-sm-7">
                                             <span v-if="praprequest.is_order"><i class="far fa-check-square"></i></span>
                                             <span v-if="!praprequest.is_order"><i class="far fa-square"></i></span>

                                            <!-- <label class="col-form-label-sm" > {{praprequest.is_order}} </label> -->
                                        </div>
                                    </div>
                                      <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-5">{{$t('form.another_idea')}}:</span>
                                        <div class="col-sm-7">
                                            <label class="col-form-label-sm" > {{praprequest.another_idea}} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-2">{{$t('form.purchase_note')}}:</span>
                                        <div class="col-sm-10">
                                            <span   v-html="praprequest.purchase_note" >  </span>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-2 ">{{$t('form.viewers')}}

                                        </span>
                                        <div class="col-sm-10">
                                            <p  class="col-form-label-sm">
                                                <span v-for="(shared,index) in praprequest.shareds" v-bind:key="index">


                                                    <span  class="badge ml-1" v-if="shared.group">{{shared.group.company_id}}-{{shared.group.name}}</span>
                                                </span>

                                            </p>

                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-form-label-sm col-sm-2 ">{{$t('form.shared')}}

                                        </span>
                                        <div class="col-sm-10">
                                                <shared-list v-bind:object="praprequest" :items_props="praprequest.shareds" v-on:sharedAction="sharedAction"   :type="'PRICE'"></shared-list>

                                    </div>
                                    </div>
                                </div>
                            </div>
                            <b-tabs content-class="mt-3" small>
                                  <b-tab :title="$t('form.price')" >
                                        <price-approve-request-review v-if="vendor_count == 1"  :data="praprequest"></price-approve-request-review>
                                        <price-approve-request-review-vendor v-if="vendor_count > 1 && !showSpec()"  :data="praprequest"></price-approve-request-review-vendor>
                                        <price-approve-request-review-spec-vendor v-if="vendor_count > 1 && showSpec()"  :data="praprequest"></price-approve-request-review-spec-vendor>

                                  </b-tab>
                                  <b-tab :title="$t('form.product_service')" >
                                        <b-table responsive striped hover bordered  :items="praprequest.products"
                                        :fields="product_fields" :sticky-header="true"   small >


                                        <template #cell(quantity)="data">
                                            <ul  class="list-unstyled">
                                                    <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                        <span v-if="detail.quantity && detail.quantity!='0' && detail.vendor_id == praprequest.vendor_id">{{detail.quantity.toLocaleString(locale_format)}}</span>
                                                    </li>

                                                </ul>
                                        </template>
                                        <template #cell(price)="data">
                                            <ul  class="list-unstyled">
                                                    <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                    <span v-if="detail.price && detail.price!='0' && detail.vendor_id == praprequest.vendor_id">{{detail.price.toLocaleString(locale_format)}}</span>
                                                    </li>

                                                </ul>
                                        </template>
                                        <template #cell(price_old)="data">
                                            <ul  class="list-unstyled">
                                                    <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                    <span v-if="detail.price_old != null && detail.price_old != '0' && detail.vendor_id == praprequest.vendor_id">{{detail.price_old.toLocaleString(locale_format)}}</span>
                                                    </li>

                                                </ul>
                                        </template>
                                        <template #cell(brand)="data">
                                            <ul  class="list-unstyled">
                                                    <li style="width:100%;" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                    <span v-if="detail.vendor_id == praprequest.vendor_id">{{detail.brand}}</span>
                                                    </li>

                                                </ul>
                                        </template>
                                        <template #cell(is_vat)="data">
                                            <ul  class="list-unstyled">
                                                    <li style="width:100%; text-align:center" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                    <span v-if="detail.is_vat != '-1' && detail.is_vat != '-2'">{{detail.is_vat}}</span>
                                                    <span v-if="detail.is_vat == '-2'">Đã có VAT</span>

                                                    </li>

                                                </ul>
                                        </template>
                                        <!-- <template #cell(action)="data">
                                            <a href="#" class="btn btn-sm text-info" @click.prevent="editProduct(data.item)">
                                            <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm text-red"  @click.prevent="deleteProduct(data.item)">
                                            <i class="fas fa-trash"></i>
                                            </a>
                                        </template> -->
                                        <template v-slot:cell(product_specs)="data">
                                            <div class="d-flex justify-content-between">
                                                <ul  class="list-unstyled">
                                                    <li v-for="(spec,index) in data.item.specs" v-bind:key="index">
                                                        {{spec.name}}
                                                    </li>

                                                </ul>
                                            </div>
                                        </template>


                                    <template #row-details="row">
                                        <b-card>
                                            <b-table striped hover :items="row.item.details"></b-table>

                                        <b-button size="sm" @click="row.toggleDetails">Hide Details</b-button>
                                        </b-card>
                                    </template>
                                        </b-table>
                                  </b-tab>

                                   <b-tab :title="$t('form.attached_document')"   >
                                    <table class="table table-bordered table-sm table-responsive">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th  class="col-form-label-sm text-nowrap">{{$t('form.type')}}</th>
                                    <th  class="col-form-label-sm text-nowrap">File</th>
                                    <th  class="col-form-label-sm text-nowrap">{{$t('form.note')}}</th>
                                    <!-- <th  class="col-form-label-sm text-nowrap">{{$t('form.checked')}}</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(payment_attached,index) in praprequest.other_attacheds" v-bind:key="index">
                                    <td scope="row">1</td>
                                    <td class="text-nowrap">{{payment_attached.name}}
                                        </td>
                                <td>
                                <ul class="list-unstyled mt-0">
                                    <li v-for="(file,findex) in payment_attached.attachment_file" v-bind:key="findex" class="itemfile">
                                    <a href="#" @click.prevent="downloadFile(file.id,file.name)" class="btn-link text-secondary"><i  v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a>
                                    <!-- download file -->
                                    <!-- <a href="downloadFile/12505" target="_blank"  class="btn-link text-secondary"><i  v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a> -->
                                    <!-- <a target="_blank" :href="get_url_download(file.id)">
                                    <i style="color:#17a2b8;" class="fas fa fa-eye"></i>
                                    </a>  -->
                                    </li>

                                </ul>
                                </td>
                                <td>
                                    <span>{{payment_attached.note}}</span>
                                    </td>
                                    <!-- <td style="text-align: center; vertical-align: middle;">
                                    <input type="checkbox" v-model="payment_attached.checked" @change="paymentAttachedCheck($event,payment_attached.id)" name="" id="">

                                    </td> -->
                                    </tr>
                                    </tbody>
                                </table>
                                </b-tab>
                            </b-tabs>

                            <!-- card-body -->
                       </div>
                       <loading :loading="loading"></loading>
                  </div>
                   <reminder-list v-bind:object="praprequest" :items_props="praprequest.reminders" v-on:reminderAction="reminderAction"   :type="'PRICE'"></reminder-list>
            <timeline-list :list="praprequest.timelines" :showicon="true"></timeline-list>
             </div>
              <div class="col-md-4 ">
              <approve-form v-bind:object="praprequest" showstep=""  :type="'PRICE'" :user_id="user_id"></approve-form>
              </div>
        </div>
        <create-event-dialog :object_id="object_id" v-on:fromReminder="fromReminder" :id="reminder_id"  module="PRICE"></create-event-dialog>
         <shared-dialog :doc_id="object_id" v-on:fromShared="fromShared"   module="PRICE"></shared-dialog>
    </div>
</template>

<script>
import Loading from '../shared/Loading.vue';
import SharedDialog from '../shared/SharedDialog.vue';
import TimelineList from '../timeline/TimelineList.vue';
export default {
  components: { SharedDialog },
     watch: {
        praprequest(){
        this.object_id.push(this.praprequest.id);
        }
    },
    props: {
     id:String,
     user_id:String,
     pre_url:"",
     pre_title:"",
     module_id:String,
     notification_id:String,
     layout:Object,
  },
  created () {
    this.token = "Bearer " + window.Laravel.access_token;
   // this.getUserInfo();
    this.getpraprequest();

  },
   data() {
        return {
            praprequest: {

                 proposer: this.user_id,
                vendor_id: null,
                currency: "VND",
                document_type_id: this.doctype_id,
                attachment_file: [],
                attachment_file_removed: [],
                other_attacheds:[],
                other_attacheds_del:[],
                products:[],
                products_del:[],

            },
            other_attached_curr_index:"",
            other_attached:{
                paycattype_id:"",
                name:"",
                note:"",
                attachment_file:[],
                attachment_file_removed:[],

            },
            product_fields: [

                {
                    key: 'code_sap',
                    label:this.$t('form.code_sap'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                },
                 {
                    key: 'name',
                    label:this.$t('form.product_name_service'),
                    sortable: true,
                    stickyColumn: true,
                     class:"text-nowrap"
                },
                {
                    key: 'unit',
                    label:this.$t('form.unit'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"


                },
                {
                    key: 'quantity',
                    label:this.$t('form.quantity'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                 },
                  {
                    key: 'price',
                    label:this.$t('form.new_price'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                 },

                  {
                    key: 'price_old',
                    label:this.$t('form.current_price'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                 },
                  {
                    key: 'is_vat',
                    label:'VAT',
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                 },
                   {
                    key: 'brand',
                    label:this.$t('form.brand'),
                    sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap"
                 },
                {
                    key: 'product_specs',
                    label:this.$t('form.info_product'),
                    sortable: true,
                    stickyColumn: true,

                    class:"text-nowrap"
                 },



            ],
            object_id:[],
            reminder_id:"",
            locale_format: "de-DE",
            companies: [],
            vendors: [],
            //tree_vendors:[],
            currencies: [],
            departments: [],
            requests_serial_num: "",
            users: [],
            // vendors_combobox:[],
            errors: {},
            loading: false,
            edit_praprequest: false,
            token: "",
            tabIndex: 0,
            page_url_department: "/api/category/departments",
            page_url_currency: "/api/category/currencies",
            page_url_users: "api/user/all",
            page_url_vendors: "/api/category/vendors",
            page_url_praprequest: "/api/price/requests",
            page_url_price_print :"/price/print",
        };
    },
    computed:{
        vendor_count(){
            var list = this.listVendor();
            return list.length;
        },


    },
    methods: {
        fromShared(obj){
         this.praprequest.shareds = obj;
        },
        sharedAction(obj,type){
            var index ="";
        switch (type) {


           case 'DELETE':
                 //gọi ham delete
                 index = this.praprequest.shareds.findIndex(data=>data.id == obj.id);
               this.praprequest.shareds.splice(index,1);

            break;

          default:
            break;
        }
      },
         editDocument(){
        window.location.href= "price/requests?type=edit&id="+this.id;
        },


        fromReminder(obj){

        let index = this.praprequest.reminders.findIndex(item=>{
            return item.id == obj.id;
            });

            if(index != -1){
                this.praprequest.reminders[index] = obj;

                this.$root.$emit('bv::refresh::table', 'reminderRef');//refresh data trong danh sách reminder -> ở form khác

            }else{
                this.praprequest.reminders.push(obj);
            }


      },
       reminderAction(obj,type){


          var index ="";
        switch (type) {
          case 'EDIT':
                index = this.praprequest.reminders.findIndex(data=>data.id == obj.id);
              this.reminder_id = obj.id;
              $('#createEventModal').modal('show');
            break;
          case 'DELETE':
                index = this.praprequest.reminders.findIndex(data=>data.id == obj.id);
               this.praprequest.reminders.splice(index,1);
                this.reminder_id = "";
               //gọi ham delete
            break;
           case 'SHOW':
               this.reminder_id = "";
                $('#createEventModal').modal('show');
               //gọi ham delete
            break;
          default:
            break;
        }

      },
        listVendor(){
          var vendors = new Array;
          var vendor_key = "";
         // console.log( this.praprequest.products);
          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {
                  vendor_key = detail.vendor_id  +"@"+ detail.vendor_display;
                   if (!vendors.includes(vendor_key)) {
                    vendors.push(vendor_key);
              }
              });

          });

          return vendors;
        },
       showSpec(){
          var flag = false;
          var check_quan = true;
          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {

                  //Do đặc thù spec nên số lượng nhập phải là 1 hoặc không nhập
                  if(detail.quantity > 1 )
                  {
                      check_quan = false;
                  }
              });
              product.specs.forEach(spec => {
                  spec.others.forEach(other => {
                   //Chỉ cần có nhập spec cho nhà cung cấp chính
                   if (other.vendor_id === this.praprequest.vendor_id) {

                       flag =  true;
                    }
                  });

              });

          });

          return flag && check_quan;
        },
      getpraprequest(){
       // console.log(this.id);
        if( this.id != ''){
          this.loading = true;
        }

        var page_url = this.page_url_praprequest+"/"+this.id+"?notification_id="+this.notification_id;
        fetch(page_url,{ headers: { Authorization: this.token } })
        .then(res=>res.json())
        .then(res=>{
          //console.log(res);
          if(res.data.success == '1'){
              this.praprequest = res.data;
               this.praprequest.attachment_file_removed = [];
               this.praprequest.other_attacheds_del = [];
                this.praprequest.products_del = [];

               //khởi tạo biến mảng attachment_file_removed

               this.praprequest.other_attacheds.forEach(element => {
                 element.attachment_file_removed = [];
               });

                this.praprequest.products.forEach(product => {
                    product.details_del = [];
                    product.specs_del = [];
                    product.specs.forEach(spec => {
                        spec.others_del = [];
                    });
               });
              this.loading = false;
          }

        }).catch(err=>{
            this.loading = false;
            console.log(err);
        });
      },
        print(){
         window.location.href =  this.page_url_price_print+"/"+this.praprequest.id;
      },
      downloadFile(idfile,filename){
          var page_url = 'api/payment/downloadFile/'+idfile;
          fetch(page_url, {
             headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                responseType: 'arraybuffer',
                method:'post'
           })
            .then( res => res.blob() )
           .then(res=>{

              var newBlob = new Blob([res], {type: "octet/stream"});
              if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
              }
              // For other browsers:
              // Create a link pointing to the ObjectURL containing the blob.
              const data = URL.createObjectURL(newBlob);
              var link = document.createElement('a');
              link.href = data;
              link.download = filename  ;
              // link.target = '_blank';
              link.click();

              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));

        },
      getIcon(ext){
        return getIconFile(ext);
      },
      showLabel(fieldName,value){
          if(fieldName in this.layout){
             if(this.layout[fieldName]['has_custom_label']){
               return this.layout[fieldName]['custom_label_text'];
             }
          }
          return value;
       },
      showControl(fieldName){
          if(fieldName in this.layout){

             return this.layout[fieldName]['isVisible'];
          }
          return false;
       },
      backToList(){

          //console.log("this.pre_url="+this.pre_url);
          window.location.href =  this.pre_url;
      },
  },
};
</script>
<style lang="scss" scoped>
 .form-group {
  margin-bottom: 1px  !important;
  margin-top: 1px  !important;
 }

</style>
