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
                  <button v-if="user_id == payrequest.user_id && payrequest.locked != 1" class="btn  btn-default" @click.prevent="editRequest()"><i class="fas fa-edit"></i>{{$t('form.edit')}}</button>
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
              <!-- <div class="card-header">
                <h3 class="card-title">Yêu cầu thanh toán</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.company')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" v-if="payrequest.company" >{{payrequest.company.name}}</label>
                         <label class="col-form-label-sm" v-if="!payrequest.company" >&nbsp;</label>
                      </div>
                    </div>
                     <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.department')}}:</span>
                      <div class="col-sm-7">
                         <label  class="col-form-label-sm" v-if="payrequest.department && payrequest.group">{{payrequest.department.code}}/{{payrequest.group.name}}</label>
                         <label  class="col-form-label-sm" v-if="!payrequest.department">&nbsp;</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.document_num')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" > {{payrequest.serial_num}} </label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.proposer_payment')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm"  v-if="payrequest.proposer_payment">{{payrequest.proposer_payment.name}} </label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.currency != 'VND'">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.currency')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.currency}}</label>
                      </div>
                    </div>
                     <div class="form-group row"  v-if="payrequest.currency != 'VND'">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.exchanged_rate')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.exchange_rate.toLocaleString(locale_format)}}</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.suggested_amount')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.amount.toLocaleString(locale_format)}} ({{payrequest.currency}})</label>
                      </div>
                    </div>

                    <div class="form-group row"   v-if="payrequest.currency != 'VND'">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.amount_exchanged')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.amount_exchanged.toLocaleString(locale_format)}} (VND)</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.purpose')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm">{{payrequest.content}}</label>
                      </div>
                    </div>
                      <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.unit_individual_receiving_money')}} :</span>
                      <div class="col-sm-7">
                         <label v-if="!payrequest.vendor"  class="col-form-label-sm">{{payrequest.money_receiver}}
                        </label>
                        <label v-if="payrequest.vendor"  class="col-form-label-sm">{{payrequest.vendor.comp_name}}
                        </label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">
                        <!-- {{$t('form.advance_payment_period')}}  -->
                         <span v-html="showLabel('finish_date',$t('form.advance_payment_period'))"></span>
                        :</span>
                      <div class="col-sm-7">
                         <label  class="col-form-label-sm">{{payrequest.finish_date | formatDate}}
                        </label>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5  ">{{$t('form.payment_method')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm">
                           <span v-if="payrequest.method_payment == 1">{{$t('form.transfer')}}</span>
                           <span v-if="payrequest.method_payment == 2">{{$t('form.cash')}}</span>
                         </label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.method_payment == 1">
                      <span class="col-form-label-sm col-sm-5  ">{{$t('form.bank')}}:</span>
                      <div class="col-sm-7">

                         <label class="col-form-label-sm" v-if="payrequest.bank">{{payrequest.bank.name}}</label>
                         <label class="col-form-label-sm" v-if="payrequest.bank_name">{{payrequest.bank_name}}</label>
                         <label class="col-form-label-sm" v-if="!payrequest.bank && payrequest.bank_name">&nbsp;</label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.method_payment == 1">
                      <span class="col-form-label-sm col-sm-5  ">{{$t('form.bank_branch')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.bank_branch}}&nbsp;</label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.method_payment == 1">
                      <span class="col-form-label-sm col-sm-5  ">{{$t('form.bank_account_number')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.bank_account}}&nbsp;</label>
                      </div>
                    </div>
                     <div class="form-group row" v-if="showControl('budget_type')">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.budget')}}:</span>
                      <div class="col-sm-7">

                         <label v-if="payrequest.budget_type_obj"  class="col-form-label-sm">{{$t(payrequest.budget_type_obj.name)}}</label>

                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.out_budget==1">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.amount_out_budget')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.amount_out_budget.toLocaleString(locale_format)}} ({{payrequest.currency}})</label>
                      </div>
                    </div>

                    <div class="form-group row"   v-if="payrequest.currency != 'VND' && payrequest.out_budget==1">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.amount_out_exchanged')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.amount_out_exchanged.toLocaleString(locale_format)}} (VND)</label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.budget_num ">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.budget_code')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.budget_num}}&nbsp;</label>
                      </div>
                    </div>
                    <div class="form-group row" v-if="payrequest.doc_reference">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.document_reference')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.doc_reference}}&nbsp;</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.type')}}:</span>
                      <div class="col-sm-7">

                         <label v-if="payrequest.payment_type"  class="col-form-label-sm">{{$t(payrequest.payment_type.name)}}</label>

                      </div>
                    </div>
                     <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5">{{$t('form.note')}}:</span>
                      <div class="col-sm-7">

                         <label v-if="payrequest.note"  class="col-form-label-sm">{{payrequest.note}}</label>

                      </div>
                    </div>
                     <div class="form-group row" v-if="settlement_doc && settlement_doc.serial_num && settlement_doc.serial_num!=''" >
                      <span class="col-form-label-sm col-sm-5">{{$t('form.settlement_doc')}}:</span>
                      <div class="col-sm-7">

                         <label  class="col-form-label-sm">
                           <a v-bind:href="'/payment/requests?type=view&id='+settlement_doc.id" target="_blank">{{$t('form.detail')}}</a>
                         </label>

                      </div>

                  </div>


                    </div>
                </div>

                 <b-tabs content-class="mt-3" small>
                    <b-tab :title="$t('form.advance_payment_vourcher')"  v-if="showControl('payment_advance_moneys')" active>

                        <b-table striped hover responsive :bordered="true"    :sticky-header="false"   small
                           :items="payrequest.payment_advance_moneys"
                             selectable

                           :fields="fields">

                            <template #cell(serial_num)="data">
                             <a v-bind:href="'/payment/requests?type=view&id='+data.item.advance_money_id"> {{data.value}}</a>
                            </template>

                             <template  #cell(company_id)="data">

                                <span v-if="data.item.refer" >{{data.item.refer.company_id}}</span>
                                <!-- <span v-else >{{data.value}}</span> -->
                            </template>

                      </b-table>

                      </b-tab>
                  <b-tab :title="$t('form.contract')" v-if="payrequest.contract">
                   <div class="row border border-info rounded   " v-if="payrequest.contract">
                  <div class="col-md-6 mt-2">
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.contract_num')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" >{{payrequest.contract.contract_num}}</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">"{{$t('form.contract_term')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" v-if="payrequest.contract_term">{{payrequest.contract_term.terms_num}}/<span >{{term_count}}</span> </label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5 ">{{$t('form.sign_date')}}:</span>
                      <div class="col-sm-7">
                         <label class="col-form-label-sm" v-if="payrequest.contract">{{payrequest.contract.sign_date | formatDate}}</label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <span class="col-form-label-sm col-sm-5  ">{{$t('form.date_due')}}:</span>
                      <div class="col-sm-7">
                        <label class="col-form-label-sm" v-if="payrequest.contract_term">{{payrequest.contract_term.date_due | formatDate}}</label>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6 mt-2">
                     <span class="col-form-label-sm">{{$t('form.term_of_payment')}}:  <span v-if="payrequest.contract_term"  v-html="payrequest.contract_term.term_content"></span></span>

                  </div>

                 </div>
                  </b-tab>
                  <b-tab :title="$t('form.payment_voucher')"   v-if="showControl('payment_vouchers')">
                   <span> <i> {{$t('form.sum')}} : {{payment_voucher_total_amount.toLocaleString(locale_format) }} {{payrequest.currency}}</i></span>
                   <table class="table table-bordered table-sm table-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th class="col-form-label-sm text-nowrap">{{$t('form.content')}}</th>
                        <th  class="col-form-label-sm text-nowrap">{{$t('form.attached_doc')}}</th>
                        <th  class="col-form-label-sm  text-nowrap">{{$t('form.amount')}}</th>
                        <th  class="col-form-label-sm  text-nowrap">{{$t('form.cost_center')}}</th>
                        <th  class="col-form-label-sm  text-nowrap">{{$t('form.gl_account')}}</th>
                        <th  class="col-form-label-sm  text-nowrap">PR/PO</th>
                        <th  class="col-form-label-sm  text-nowrap">File</th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(payment_voucher,index) in payrequest.payment_vouchers" v-bind:key="index">
                        <td scope="row">{{index+1}}</td>
                        <td> <span class="text-nowrap">{{payment_voucher.content}}</span></td>
                        <td class="text-nowrap">
                            <span v-if="payment_voucher.type_obj">{{$t(payment_voucher.type_obj.name)}}:</span>
                            <!-- <span v-if="payment_voucher.type==2">{{$t('form.voucher')}}:</span>  -->
                            {{payment_voucher.doc_num}} - {{$t('form.date')}}: {{payment_voucher.doc_date | formatDate}}
                        </td>
                        <td style="text-align:right">{{payment_voucher.amount.toLocaleString(locale_format)}}
                        </td>
                        <td>
                            {{payment_voucher.costcenter}}
                        </td>
                       <td>
                            {{payment_voucher.gl_account}}
                        </td>
                       <td>
                            {{payment_voucher.prpo_type}}:{{payment_voucher.prpo_num}}
                        </td>
                         <td>
                           <ul class="list-unstyled mt-0">
                            <li v-for="(file,findex) in payment_voucher.attachment_file" v-bind:key="findex" class="itemfile">
                              <a  href="#" @click.prevent="downloadFile(file.id,file.name)" class="btn-link text-secondary text-nowrap"><i  v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a>

                            </li>
                           </ul>
                       </td>
                      </tr>

                    </tbody>
                  </table>
                  </b-tab>
                  <b-tab :title="$t('form.attached_document')"  v-if="showControl('payment_attacheds')"  >
                      <table class="table table-bordered table-sm table-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th  class="col-form-label-sm text-nowrap">{{$t('form.type')}}</th>
                      <th  class="col-form-label-sm text-nowrap">File</th>
                      <th  class="col-form-label-sm text-nowrap">{{$t('form.note')}}</th>
                      <th  class="col-form-label-sm text-nowrap">{{$t('form.checked')}}</th>
                    </tr>
                  </thead>
                   <tbody>
                       <tr v-for="(payment_attached,index) in payrequest.payment_attacheds" v-bind:key="index">
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
                    <td style="text-align: center; vertical-align: middle;">
                      <input type="checkbox" v-model="payment_attached.checked" @change="paymentAttachedCheck($event,payment_attached.id)" name="" id="">

                       </td>
                    </tr>
                     </tbody>
                </table>
                  </b-tab>
                </b-tabs>


                  <div class="row">
                    <div class="col-md">
                     <div class="form-group row">
                <!-- <h6 class="mt-3 text-gray"   ><i class="fas fa-caret-right  "></i> Chứng từ thanh toán</h6> -->

                <!-- <h6 class="mt-3 text-gray"><i class="fas fa-caret-right  "></i> Chứng tù file đính kèm</h6> -->


              </div>
<div class="form-group row">
                            <span class="col-form-label-sm col-sm-2 ">{{$t('form.shared')}}

                            </span>
                            <div class="col-sm-10">
                                    <shared-list v-bind:object="payrequest" :items_props="payrequest.shareds" v-on:sharedAction="sharedAction"   :type="'PAYREQUEST'"></shared-list>

                          </div>

                   </div>
                    </div>

                </div>

              </div>

                <loading :loading="loading"></loading>
              <!-- /.card-body -->
            </div>
            <reminder-list v-bind:object="payrequest" :items_props="payrequest.reminders" v-on:reminderAction="reminderAction"   :type="'PAYREQUEST'"></reminder-list>
            <timeline-list :list="payrequest.timelines" :showicon="true"></timeline-list>
          </div>
          <div class="col-md-4 ">
           <approve-form v-bind:object="payrequest" showstep=""  :type="'PAYMENT'" :user_id="user_id"></approve-form>
</div>

        </div>

          <create-event-dialog :object_id="object_id" v-on:fromReminder="fromReminder" :id="reminder_id"  module="PAYREQUEST"></create-event-dialog>
          <shared-dialog :doc_id="object_id" v-on:fromShared="fromShared"   module="PAYREQUEST"></shared-dialog>
  </div>
</template>

<script>

import Loading from '../../shared/Loading.vue';
import SharedDialog from '../../shared/SharedDialog.vue';
import SharedList from '../../shared/SharedList.vue';
import TimelineList from '../../timeline/TimelineList.vue';

export default {
  watch: {
    payrequest(){
      this.object_id.push(this.payrequest.id);
    }
  },
  components: { Loading, TimelineList, SharedList, SharedDialog },
    data () {
     return {

       payrequest:{
        proposer_payment:"",
        method_payment:"1",
        amount:"",
        amount_exchanged:"",
        exchange_rate:"",
        bank_id:"",
        bank_account:"",
        content:"",
        finish_date:"",
        payrequest_type_id:"",

        date_end:"",
        company_id:" ",
        contract_id:"",
        contract_term_id:"",
        approveds:[],
        payment_vouchers:[],
        payment_vouchers_del:[],
        payment_attacheds:[],
        paycatset_id:"",
        team:{
          user:[]
        }
      },
      settlement_doc:{},
      object_id:[],
      approved_info:{
        show_approve:false,
        show_approvePaymentSend:false,
        show_class_disable:false,
        list_user:[]
      },
      user_approve:{
          id:"",
          name:"",
          status:false,
          refuse:false,//từ chối
          date_approve:"",
          user_current_approve:false,
      },
      reminder_id:"",
      fields: [
          {
              key: 'selected',
              label:'',
              stickyColumn: true
            },
           {
            key: 'serial_num',
            label:this.$t('form.document_num'),
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
            key: 'amount',
            label:this.$t('form.amount'),
            sortable: true,
            stickyColumn: true,
            formatter(value){
               // console.log(value);
               var locale_format = 'de-DE';
                if(value != null ){
                   return value.toLocaleString(locale_format);
                }
              return 0;
            }
          },

           {
            key: 'serial_date',
            label:this.$t('form.date'),
            sortable: true,
            stickyColumn: true,
            formatter(value) {

              return moment(String(value)).format('DD/MM/YYYY')   ;
              }
          },

      ],
       locale_format:'de-DE',
      //my_user:{},
      feedbacks:[],
      feedback:{
        name:"",
        date:"",
        content:""
      },
      is_owner:false,
      is_approve:false,
      is_reject:false,
      loading: false,
      token:"",
       page_url_payrequest:"/api/payment/requests",
       page_url_payrequest_settlement_doc:"/api/payment/settlement_doc",
       page_url_payment_attached_check:"/api/payment/payment_attached_check",
       page_url_user: "/api/user/myinfo",
       //page_url_approvePaymentSend :"/api/payment/approvePaymentSend",
       page_url_approve_payment : "/api/approve/payment",
       page_url_payment_print :"/payment/print",

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
    this.getPayrequest();
    this.get_settlement_doc();
  },
  methods: {
  fromShared(obj){
         this.payrequest.shareds = obj;
     },
    get_settlement_doc(){


        var page_url = this.page_url_payrequest_settlement_doc+"/"+this.id;
        fetch(page_url,{ headers: { Authorization: this.token } })
        .then(res=>res.json())
        .then(res=>{
          //console.log(res.data);
          if(res.success == '1'){
              this.settlement_doc = res.data;

              //this.check_owner(this.payrequest.user_id);
               //this.process_approved_info();
                //this.loading = false;
          }

        }).catch(err=>{
           // this.loading = false;
            console.log(err);
        });
    },
     fromReminder(obj){


       let index = this.payrequest.reminders.findIndex(item=>{
           return item.id == obj.id;
         });

         if(index != -1){
             this.payrequest.reminders[index] = obj;

             this.$root.$emit('bv::refresh::table', 'reminderRef');//refresh data trong danh sách reminder -> ở form khác

         }else{
            this.payrequest.reminders.push(obj);
         }


      },
      sharedAction(obj,type){
            var index ="";
        switch (type) {


           case 'DELETE':
                 //gọi ham delete
                 index = this.payrequest.shareds.findIndex(data=>data.id == obj.id);
               this.payrequest.shareds.splice(index,1);

            break;

          default:
            break;
        }
      },
     reminderAction(obj,type){


          var index ="";
        switch (type) {
          case 'EDIT':
                index = this.payrequest.reminders.findIndex(data=>data.id == obj.id);
              this.reminder_id = obj.id;
              $('#createEventModal').modal('show');
            break;
          case 'DELETE':
                index = this.payrequest.reminders.findIndex(data=>data.id == obj.id);
               this.payrequest.reminders.splice(index,1);
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
     getPayrequest(){
       // console.log(this.id);
        if( this.id != ''){
          this.loading = true;
        }

        var page_url = this.page_url_payrequest+"/"+this.id+"?notification_id="+this.notification_id;
        fetch(page_url,{ headers: { Authorization: this.token } })
        .then(res=>res.json())
        .then(res=>{
          //console.log(res);
          if(res.data.success == '1'){
              this.payrequest = res.data;

              //this.check_owner(this.payrequest.user_id);
               this.process_approved_info();
                this.loading = false;
          }

        }).catch(err=>{
            this.loading = false;
            console.log(err);
        });
      },
      paymentAttachedCheck(event,payment_attached_id){
       // console.log(event.target.checked);
        let status = event.target.checked? 1:0;
        fetch(this.page_url_payment_attached_check,{
          method:'post',
          body:JSON.stringify({'payment_attached_id':payment_attached_id,'status':status}),
          headers:{
             Authorization: this.token,
             "content-type": "application/json",
              "Accept": "application/json",
              "X-Requested-With": "XMLHttpRequest",
          }
        })
          .then(res=>res.json())
          .then(data =>{
          // console.log(data);
            if(data.statuscode == "403"){
                    toastr.warning(data.message,"");
                    return;
             }
            if(data.data.success == '1'){
               if(data.data.status == '1'){
                   toastr.success(this.$t('form.check_success'),"");
               }else{
                   toastr.success(this.$t('form.uncheck_success'),"");
               }


            }else{
              toastr.warning(this.$t('form.check_error'), "");
            }

          })
          .catch(err => console.log(err));
      },
      //Gửi yêu cầu duyệt
      approvePaymentSend() {
            fetch(this.page_url_approve_payment+"/send" , {
                method: "post",
                body:JSON.stringify({'payrequest_id':this.payrequest.id}),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                  // console.log(data);
                   if(data.data.success == '1'){

                      this.approved_info.show_class_disable = true;

                      this.approved_info.list_user.forEach(user => {

                        if(user.id == data.data.user.id){
                           user.user_current_approve = true;
                        }
                      });
                      toastr.success(this.$t('form.send_approved_success'),"");
                   }else{
                      toastr.success(this.$t('form.send_approved_error'),"");
                   }

                })
                .catch(err => console.log(err));
        },

        //duyệt thanh toán
        approvePaymentAgree() {
            fetch(this.page_url_approve_payment+"/agree" , {
                method: "post",
                body:JSON.stringify({'payrequest_id':this.payrequest.id}),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                  // console.log(data);
                   if(data.data.success == '1'){

                      this.approved_info.show_class_disable = true;
                      this.approved_info.show_approve = false;
                      this.approved_info.list_user.forEach(user => {

                        //reset trạng thái cho user duyệt hiện tại và kiểm tra cho user duyệt tiếp theo nếu có
                        if(data.data.user && user.id == data.data.user.id){
                           user.user_current_approve = true;

                        }else{
                          user.user_current_approve = false;
                        }
                        if(user.id == this.user_id){
                          user.status = true;
                          user.date_approve = data.data.approve.checkout;
                        }
                      });
                     // console.log(data.data);
                        toastr.success(this.$t('form.approved_success'),"");

                   }else{
                    toastr.success(this.$t('form.approved_error'),"");
                   }

                })
                .catch(err => console.log(err));
        },

       //Ghi ý kiến phản hồi
        showDialogAddFeedback(){

          $("#ykienphanhoi").modal('show');
        },

        approvePaymentRefuse(){

         fetch(this.page_url_approve_payment+"/refuse" , {
                method: "post",
                body:JSON.stringify({'payrequest_id':this.payrequest.id,'feedback':$('#feedback').val()}),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {

                 //  console.log(data);
                   if(data.data.success == '1'){

                      this.approved_info.show_class_disable = true;
                      this.approved_info.show_approve = false;
                      this.approved_info.list_user.forEach(user => {

                        if(user.id == this.user_id){
                          user.refuse = true;

                        }

                      });

                        //add phản hồi
                     //   console.log()
                        this.feedback = {};
                        this.feedback.name = data.data.feedback.user.name;
                        this.feedback.date = data.data.feedback.checkout;
                        this.feedback.content = data.data.feedback.note;
                        this.feedbacks.push({...this.feedback});

                        toastr.success(this.$t('form.feedback_success'),"");
                   }else{
                     toastr.success(this.$t('form.feedback_error'),"");
                   }

                })
                .catch(err => console.log(err));

        },
       get_url_download(idfile){
            var page_url = 'downloadFile/'+idfile;
            return page_url;
       },
       downloadFileView(idfile,filename){
          var page_url = 'api/payment/downloadFileView/'+idfile;
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
                  //  xhttp.responseType = "arraybuffer";
                return;
              }else{
                 xhttpGetPack.responseType = "application/pdf";
              }
              // For other browsers:
              // Create a link pointing to the ObjectURL containing the blob.
              const data = URL.createObjectURL(newBlob);
              var link = document.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.open = filename  ;
             // window.open(data)
              link.click();
              // let newWindow = window.open(data);

              //  let newWindow = window.open('/');
              //  newWindow.onload = () => { newWindow.location.assign(newBlob); };

              // newWindow.location = data;

              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));

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
        process_approved_info(){

        if(this.payrequest.team && this.payrequest.team.users){
          let is_finished = true;
           let last_info = null;
          if(this.payrequest.approveds && this.payrequest.approveds.length > 0){
            last_info =  this.payrequest.approveds[this.payrequest.approveds.length-1];
            //lấy thông tin feedback nếu có
             this.payrequest.approveds.forEach(refuse =>{
                if(refuse.release == '1'){
                    this.feedback = {};
                    this.feedback.name = refuse.user.name;
                    this.feedback.date = refuse.checkout;
                    this.feedback.content = refuse.note;
                    this.feedbacks.push({...this.feedback});
                }
             });
          }

          this.payrequest.team.users.forEach(user => {
            let info = this.get_approved(user.id);
            this.user_approve = {};
             this.user_approve.name = user.name;
             this.user_approve.id = user.id;
             //kiểm tra phiên bản duyệt khớp với thông tin duyệt cuối cùng
            if(info && info.online == 'X'){

               this.user_approve.status = info.release == 2 ? true:false;
               this.user_approve.refuse = info.release == 1 ? true:false;
               this.user_approve.date_approve = info.checkout;
            }

            this.user_approve.user_current_approve =  this.user_current_approve(user.id);

            //kiểm tra user hiện tại có phải là user login không?

            if(this.user_approve.user_current_approve && user.id == this.user_id
                && (info.release == null || info.release =='')){
                this.approved_info.show_approve = true;

            }
            //kiểm tra user hiện tại có phải là user Tạo phiếu không?
           // console.log('this.payrequest.user_id ==  this.user_id :'+ this.payrequest.user_id +"="+this.user_id);
            if(this.payrequest.user_id ==  this.user_id){
                this.approved_info.show_approvePaymentSend = true;
            }
            //

            //kiểm tra phiếu đã gửi và đang  chờ duyệt -> do đó , làm mờ nút Gửi
            if(info && ( info.release == null || info.release == 0 )){
              this.approved_info.show_class_disable = true;
            }
            //kiểm tra bản gửi duyệt cuối cùng mang trạng thái release = 1 (từ  chối thì hiển thị nút gửi lại)
            // if(info)
            // console.log('info &&  info.release == 2:'+ info +"="+info.release);
             if(info &&  info.release == 2 ){
               this.approved_info.show_approvePaymentSend = false;
            }
            //bật cờ gửi nếu
            this.approved_info.list_user.push({...this.user_approve});


          });

        }

        //nếu

      },
    editRequest(){
    window.location.href= "payment/requests?type=edit&id="+this.id;
    },
      print(){
         window.location.href =  this.page_url_payment_print+"/"+this.payrequest.id;
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
      get_approved(user_id){
          let last_approve_info = null;
          if(this.payrequest.approveds){

            this.payrequest.approveds.forEach(element => {
              if(element.user_id == user_id){
                last_approve_info = {...element};
                }

              });

          }

          return last_approve_info;
        },

      user_current_approve(user_id){
        let flag = false;
        let curr_user = '';
        if(this.payrequest.approveds){
            this.payrequest.approveds.forEach(element => {
              curr_user = element.user_id;
            });
            if(curr_user == user_id){
              return true;
            }
        }
        return false;

      },
      getIcon(ext){
        return getIconFile(ext);
      },
  },

computed:{
 payment_voucher_total_amount(){

         var total  = 0.0;
         this.payrequest.payment_vouchers.forEach(element => {
           total = total +  element.amount;
         });
         return total;
       },
  sortFeedback(){
      this.feedbacks.sort(( a, b) => {
            return new Date(a.date) - new Date(b.date);
        });
        return this.feedbacks;
  },
  term_count(){
    if(this.payrequest.contract && this.payrequest.contract.contract_terms){
       return  Object.keys(this.payrequest.contract.contract_terms).length;
    }else{
      return 0;
    }

  }
}
}
</script>

<style lang="scss" scoped>
 .form-group {
  margin-bottom: 1px  !important;
  margin-top: 1px  !important;
}
</style>
