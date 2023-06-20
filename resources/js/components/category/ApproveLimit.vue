<template>
   <div>
     <form @submit.prevent="save"  @keydown.enter.prevent="clearError">
   <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"> {{ $t(title)}}</h5>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a class="btn btn-default" href="#" @click.prevent="reset()" type="button">

                        Làm mới
                 </a>
                  <!-- <a href="#" class="btn btn-primary" @click.prevent="save()"><i class="fa fa-save"></i> Lưu</a> -->
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu </button>
                </div>

            </div>
         </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="border: 1px solid #E5E5E5;border-radius:5px;">
                        <div class="col-md-12 ">
                            <div class="form-group row">
                            <label  class="col-form-label-sm col-md-1" style="text-align:left" for="company_id">Công ty <small class="text-red">(*)</small></label>
                                <div class="col-md-3">
                                <select name="company_id" id="company_id" v-model="form_filter.company_id"
                                 v-bind:class="hasError('company_id')?'is-invalid':''"
                                @change="filter_data()"
                                 @click="clearError($event)"
                                class="form-control form-control-sm mt-1">
                                    <option
                                        v-for="company in companies"
                                            :key="company.id"
                                            v-bind:value="company.id"
                                        >
                                            {{ company.name }}
                                        </option>
                                    <option value=""></option>
                                </select>
                                 <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('company_id')}}</strong>
                                </span>
                                </div>
                                <label class="col-form-label-sm" for="document_type_id">Loại chứng từ<small class="text-red">(*)</small></label>
                                <div class="col-md-3">
                                    <select name="document_type_id" id="document_type_id" v-model="form_filter.document_type_id"
                                    @change="filter_data()"
                                     @click="clearError($event)"
                                      v-bind:class="hasError('document_type_id')?'is-invalid':''"
                                    class="form-control form-control-sm mt-1">
                                    <option
                                        v-for="documentType in documentTypes"
                                            :key="documentType.id"
                                            v-bind:value="documentType.id"
                                        >
                                            {{ documentType.name }}
                                        </option>
                                    <option value=""></option>
                                    </select>
                                    <span v-if="hasError('document_type_id')" class="invalid-feedback" role="alert">
                                        <strong>{{getError('document_type_id')}}</strong>
                                    </span>
                                </div>
                                 <label class="col-form-label-sm" for="payment_type_id">Loại thanh toán<small class="text-red">(*)</small></label>
                                    <div class="col-md-2">
                                    <select name="payment_type_id" id="payment_type_id" v-model="form_filter.payment_type_id"
                                    @change="filter_data()"
                                     @click="clearError($event)"
                                      v-bind:class="hasError('payment_type_id')?'is-invalid':''"
                                    class="form-control form-control-sm mt-1">
                                    <option
                                        v-for="paymentType in paymentTypes"
                                            :key="paymentType.id"
                                            v-bind:value="paymentType.id"
                                        >
                                            {{ paymentType.name }}
                                        </option>
                                    <option value=""></option>
                                    </select>
                                    <span v-if="hasError('payment_type_id')" class="invalid-feedback" role="alert">
                                        <strong>{{getError('payment_type_id')}}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label  class="col-form-label-sm    col-md-1" style="text-align:left" for="budget_type">Loại ngân sách<small class="text-red">(*)</small></label>
                                <div class="col-md-3">
                                <select name="budget_type" id="budget_type" v-model="form_filter.budget_type"
                                 @change="filter_data()"
                                   @click="clearError($event)"
                                  v-bind:class="hasError('budget_type')?'is-invalid':''"
                                class="form-control form-control-sm mt-1">
                                     <option v-for="bud in budget_types" :key="bud.id" :value="bud.id">{{bud.name}}</option>

                                </select>
                                <span v-if="hasError('budget_type')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('budget_type')}}</strong>
                                </span>
                                </div>
                                <label  class="col-form-label-sm  " style="text-align:left" for="currency">Loại tiền<small class="text-red">(*)</small> &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;  </label>
                                <div class="col-md-3">
                                <select name="currency" id="currency"  v-model="form_filter.currency"
                                @change="filter_data()"
                                  @click="clearError($event)"
                                   v-bind:class="hasError('currency')?'is-invalid':''"
                                    class="form-control form-control-sm mt-1">

                                     <option v-for="cur in currencies" :key="cur.id" :value="cur.id">{{cur.name}}</option>

                                </select>
                                <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('currency')}}</strong>
                                </span>
                                </div>
                                    <div class="col-md-3">
                                <!-- <button type="submit" class="btn btn-warning btn-sm mt-1" @click="filter_data()"> <i class="fa fa-search"></i> Tìm </button>
                                <button type="reset" class="btn btn-secondary btn-sm mt-1" @click="clearFilter()"> <i class="fa fa-reset"></i> Clear</button> -->

                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">


                            </div>
                        </div>

                    </div>
                    <div class="row justify-content-end" style="background-color:#F4F6F9">
                        <!-- <small class="ml-2 text-red"><strong> <i>ĐVT : Triệu đồng</i> </strong></small> -->
                    </div>
                    <div class="row">
                        <b-table hover responsive :bordered="true"   head-variant="light" sticky-header="true" small
                            :items="infos.limits"
                            :fields="fields"
                        >
                        <template #head(name)>
                            <span > Mã </span>
                         </template>
                          <template #head(from)>
                            <span>HM Từ<small class="text-red">(*)</small></span>
                         </template>
                          <template #head(to)>
                            <span>HM Đến<small class="text-red">(*)</small></span>
                         </template>
                          <template #head(from_sub)>
                            <span>Sub HM Từ<small class="text-red"></small></span>
                         </template>
                          <template #head(to_sub)>
                            <span>Sub HM Đến<small class="text-red"></small></span>
                         </template>
                          <template #head(description)>
                            <span>Ghi chú</span>
                         </template>
                          <template #head(avtive)>
                            <span>Trạng thái<small class="text-red">(*)</small></span>
                         </template>
                        <template #cell(name)="data">
                             <span class="text-gray">{{data.value}}</span>
                            <!-- <input v-model="data.item.name" type="text"  maxlength="50" class="form-control form-control-sm"> -->
                        </template>
                        <template #cell(from)="data">
                             <vue-numeric  :separator="separator"  v-model="data.item.from" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input v-model="data.item.from" type="number" style="text-align:right"  class="form-control form-control-sm"> -->
                        </template>
                          <template #cell(to)="data">
                               <vue-numeric  :separator="separator" v-model="data.item.to" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input  v-model="data.item.to" type="number" style="text-align:right" class="form-control form-control-sm"> -->
                        </template>
                        <template #cell(from_sub)="data">
                             <vue-numeric  :separator="separator"  v-model="data.item.from_sub" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input v-model="data.item.from" type="number" style="text-align:right"  class="form-control form-control-sm"> -->
                        </template>
                          <template #cell(to_sub)="data">
                               <vue-numeric  :separator="separator" v-model="data.item.to_sub" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input  v-model="data.item.to" type="number" style="text-align:right" class="form-control form-control-sm"> -->
                        </template>
                          <template #cell(description)="data">
                            <input v-model="data.item.description" type="text" maxlength="50" class="form-control form-control-sm">
                        </template>
                        <!-- <template #cell(active)="data">
                            <select v-model="data.item.active"  class="form-control form-control-sm">
                                <option value="1">Hoạt động</option>
                                <option value="0">Ngưng hoạt động</option>
                            </select>
                        </template> -->
                        <template #cell(action)="data">
                            <button @click.prevent="delete_row(data.item,data.index)" class="btn btn-xs "  title ="Delete" >
                                        <i  class="text-red fa fa-trash bigger-120" ></i>
                            </button>
                        </template>
                        </b-table>
                         <a href="#" @click.prevent.stop="add_new_row()"> <i class="fa fa-plus-circle"></i> <i> dòng mới </i> </a>
                    </div>
                      <loading :loading="loading"></loading>
                </div>
            </div>
        </div>

    </div>
     </form>
   </div>
</template>

<script>
export default {
  props: {
      title:String
  },
  data () {
    return {

        infos:{
            document_type_id:"",
            company_id:"",
            budget_type:"",
            currency:"",
            payment_type_id:"",
            limits:[],
            limits_del:[]
        },
         separator:'.',
        documentTypes:[],
        companies:[],
        paymentTypes:[],
        currencies:[],
        budget_types:[],
        selected: {},
        form_filter:{
            company_id:"",
            department_id:"",
            document_type_id:"",
            payment_type_id:"",
            budget_type:"",
            currency:"",
            },
        fields:[

            {
                key:"name",
                lable:"Mã",

                 variant: 'secondary'
            },
             {
                key:"from",
                lable:"HM Từ",
                class:"text-right"
            },
            {
                key:"to",
                lable:"HM Đến",
                class:"text-right"
            },
             {
                key:"from_sub",
                lable:"HM Từ sub",
                class:"text-right"
            },
            {
                key:"to_sub",
                lable:"HM Đến sub",
                class:"text-right"
            },
             {
                key:"description",
                lable:"HM Đến"
            },
            //  {
            //     key:"active",
            //     lable:"Trạng thái"
            // },
            {
                key:"action",
                lable:"action"
            }
        ],
        loading: false,
        edit: false,
        token:"",
        errors:[],
        page_url_document_type:"api/category/documenttypes",
        page_url_approvelimit:"api/category/approvelimit",
        approvelimit_index_form:"api/category/approvelimit_index_form",
        page_url_company:"/api/category/companies",
        page_url_currency:"/api/category/currencies",
        page_url_budget_type:"/api/category/budgettypes",
        page_url_paymentType:"/api/category/paymenttypes",

    }
  },
  created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.fetCompany();
       this.fetCurrency();
       this.fetBudgetType();
       this.get_document_type();
       this.getPaymentType();

  },
  methods: {
        fetCurrency() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_currency;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.currencies = res.data;
                })
                .catch(err => console.log(err));
        },
        getPaymentType(){

            var page_url = this.page_url_paymentType;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("paymentTypesXin chao");
                    this.paymentTypes = res.data;
                })
                .catch(err => console.log(err));
        },
        fetBudgetType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_budget_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.budget_types = res.data;
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

     filter_data(){

         this.fetchApprovLimit();
      },
      fetchApprovLimit(){

          if(this.form_filter.company_id === '' || this.form_filter.budget_type === ''
            || this.form_filter.document_type_id === '' || this.form_filter.currency === ''
            || this.form_filter.payment_type_id === ''
            )
            {
                return ;
            }

            this.loading= true;
          const params = new URLSearchParams(
              {
                  company_id:this.form_filter.company_id,
                  document_type_id:this.form_filter.document_type_id,
                  payment_type_id:this.form_filter.payment_type_id,
                  budget_type:this.form_filter.budget_type,
                  currency:this.form_filter.currency,
              }
          );
          var page_url = this.approvelimit_index_form +"?"+params.toString();
          fetch(page_url,{
              method:'get',
              headers:{
                  Authorization:this.token,
                  'content-type':'application/json'
              }
          }).then(res=>res.json())
          .then(data=>{
              this.loading= false;
              this.infos.limits = data.data;
          }).catch(err=>{
              this.loading= false;
          })

      },
      add_new_row(){
            var limit = {};
            limit.from = "";
            limit.to = "";
            limit.from_sub = "";
            limit.to_sub = "";
            limit.name ="";
            limit.description ="";
            limit.active ="1";
            this.infos.limits.push({... limit});

      },
      save(){
          this.loading = true;
          var page_url = this.page_url_approvelimit ;
          this.infos.document_type_id = this.form_filter.document_type_id;
          this.infos.payment_type_id = this.form_filter.payment_type_id;
          this.infos.company_id = this.form_filter.company_id;
          this.infos.budget_type = this.form_filter.budget_type;
          this.infos.currency = this.form_filter.currency;
         this.clearAllError();
         fetch(page_url,{
             method:'post',
             body: JSON.stringify(this.infos),
             headers:{
                 Authorization:this.token,
                 'content-type':'application/json'
             }
         }).then(res=>res.json())
         .then(data=>{

             this.loading = false;
             //console.log(data);
             if(data&& data.data.success == 1){
                toastr.success("Lưu thành công", "Thông báo");
                  this.fetchApprovLimit();
             }else{
                 this.loading = false;
                if(data && data.data.message!=""){
                    toastr.error(data.data.message, "Lỗi");
                }

                this.errors = data.data.errors;

             }

         }).catch(err=>{
             console.log(err);
             this.loading = false;

         });

      },
      delete_row(item,index){

           // alert('test');
           if(confirm('Xác nhận xoá?')){
            this.infos.limits_del.push({...item});
            this.infos.limits.splice(index,1);
          }
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

        },
        reset(){
            this.clearAllError();
            this.edit = false;
            for(let field in this.form_filter){
                this.form_filter[field] = "";
            }
            this.infos.limits=[];
            this.infos.limits_del=[];
            this.infos.document_type_id ="";
            this.infos.payment_type_id ="";
            this.infos.company_id ="";
            this.infos.budget_type ="";
            this.infos.currency ="";
        },
        clearAllError(){
            this.errors = {};
        },


  },
  computed:{
        hasAnyError(){
            return Object.keys(this.errors).length > 0;
        }
  },




}
</script>

<style lang="scss" scoped>


</style>>
