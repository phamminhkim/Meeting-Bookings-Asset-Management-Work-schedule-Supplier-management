<template>
<div>   
    <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/payment/contracts">Hợp đồng</a> </h5></li>
              
                <li class="breadcrumb-item active">
                  <!-- <span v-if="edit_contract">Chỉnh sửa</span>  -->
                  <span >Chi tiết</span> 
                  
                  </li>
             </ol>
            </div> 
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                      <a href="/payment/contracts" class="btn btn-default ">Quay lại</a>   
                        <button v-if="!contract.contract_liquidation" class="btn btn-danger" data-toggle="modal" data-target="#thanhlyModal">Thanh lý</button>
                        <button v-if="contract.contract_liquidation" class="btn btn-danger" data-toggle="modal" >Đã thanh lý</button>
              </div>
            </div> 
          </div>
        </div> 
      </div>
    <div class="card ">
        <div class="card-header">
          <h3 class="card-title">Hợp đồng số : <strong>{{contract.contract_num}}</strong></h3>
          <div class="card-tools">
           
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
              <div class="row"  v-if="contract.contract_type != 1">
                <b-table striped hover  responsive  :bordered="true" head-variant="light" sticky-header="200px"  small
                  :items="contract.contract_term_plans"
                   :fields="fields"
                >
                  <template #cell(date_paid)="data">
                               <span>{{data.value | formatDate}}</span> 
                   </template>
                    <template #cell(amount_paid)="data">
                       <span><strong>{{data.value.toLocaleString(locale_format)}} </strong></span>
                    </template>
                </b-table>

              </div>
              <div class="row"  v-if="contract.contract_type == 1">
                <div class="col-12 col-sm-4">
                  
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Tổng số tiền</span>
                      <span class="info-box-number text-center text-muted mb-0">
                        {{contract.amount.toLocaleString(locale_format)}}</span>
                     
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Số tiền đã thanh toán</span>
                      <span class="info-box-number text-center text-muted mb-0" v-if="contract.contract_type == 1">
                        {{paidAmount.toLocaleString(locale_format)}}</span>
                      
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Số tiền còn lại</span>
                      <span class="info-box-number text-center text-muted mb-0" v-if="contract.contract_type == 1">
                        {{remainAmount.toLocaleString(locale_format)}} </span>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <h5><i class="fas fa-caret-right"></i> Điều khoản hợp đồng</h5>
                  <div class="post" v-for="term in contract.contract_terms" v-bind:key="term.id">
                      <h6>   
                        <i v-if="contract.contract_type ==1" :class="isPaid(term.amount,term.amount_paid)?'fa fa-check-circle text-green':'fa fa-check-circle text-gray'" ></i>
                       
                        <i> <u> Điều khoản {{term.terms_num}}</u> :  <span v-if="isPaid(term.amount,term.amount_paid) && contract.contract_type==1">Đã thanh toán ( {{term.date_paid | formatDate}} )</span> </i> </h6>                      
                        <div class="d-flex w-100 justify-content-between" >
                        <span>
                            Ngày đến hạn: <strong> {{term.date_due | formatDate}}  </strong> 
                          </span>
                          <span>
                            Tổng tiền: <strong> {{term.amount.toLocaleString(locale_format)}}  </strong> (VND)
                          </span>
                        </div>
                 
                      <p class="mt-1 mb-0">
                       {{term.content}} 
                      </p>
                      
                      <small>{{term.note}}  </small> 
                     
                  </div>
                   <hr>
                     <h5><i class="fas fa-caret-right"></i> Phụ lục</h5>
                                     <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th >Số HĐ</th>
                    <th>#</th>
                    <th>Nội dung</th>
                    <th  >Ngày dự kiến</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                   
                  
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(term,index) in contract_childs" v-bind:key="index">
                     <td>{{term.contract_num_child}}</td>
                    <td>{{term.terms_num}}</td>
                    <td> {{term.content}}</td>
                    <td> {{term.date_due | formatDate}}</td>
                    
                    <td> {{term.amount | numFormat('0,0')  }}</td>
                    <td style="text-align:center">
                      <i class="fa fa-check-circle text-green" v-if="term.status==2"  title="Đã thanh toán"></i>
                      </td>
                  
                   
                  </tr>

                  </tbody>
                
                </table>
                </div>
              </div>
                <hr>
              <div class="row" v-if="contract.contract_liquidation">
                <div class="col-12">
                  <h5> <i><small class="text-red" >(*)</small> Hợp đồng đã được thanh lý ngày  
                  <strong>  {{contract.contract_liquidation.date_liquid | formatDate}}  </strong> 
                  như sau:  </i>   </h5>
                   <div class="form-group row ml-2">
                       <span v-html="contract.contract_liquidation.content"></span> 
                  </div> 
                   <h5 class="text-muted">Biên bản thanh lý:</h5>
                    <ul class="list-unstyled">
                      <li v-for="file in contract.contract_liquidation.attachment_file" v-bind:key="file.id">
                        <a href="" class="btn-link text-secondary"  @click.prevent="downloadFile(file.id,file.name)" ><i v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a>
                      </li>
                      
                    </ul>                 
                </div>
              </div>
                <!-- lịch sử thanh toán -->
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <!-- <h6 class=""><i class="fa fa-info-circle text-primary "></i> {{contract.title}}</h6> -->
               <!-- <p class="text-sm">Diễn giải:
                  <b class="d-block" v-html="contract.content" ></b>
                </p> -->
              <!-- <span class="text-muted" > <i class="fa fa-info-circle text-primary "></i>
                <span  v-html="contract.content"></span>
              </span>  -->
              <!-- <p class="text-muted" v-html="contract.content">

              </p> -->
               <div class="text-muted">
                <p class="text-sm">Công ty: 
                  <b class="d-block" v-if="contract.company">{{contract.company.name}}</b>
                </p>
                <p class="text-sm">Đại diện nội bộ
                  <b class="d-block"  >{{contract.a_signer}}</b>
                </p>
                  <p class="text-sm">Chức vụ
                  <b class="d-block">{{contract.a_position}}</b>
                </p>
              </div>
              <div class="text-muted">
                <p class="text-sm">Nhà cung cấp: 
                  <b class="d-block" v-if="contract.vendor">{{contract.vendor.comp_name}}</b>
                </p>
                <p class="text-sm">Đại diện nhà cung cấp
                  <b class="d-block"  > {{contract.b_signer}}</b>
                </p>
                  <p class="text-sm">Chức vụ
                  <b class="d-block">{{contract.b_position}}</b>
                </p>
              </div>

              <h5 class="mt-3 text-muted">File đính kèm</h5>
              <ul class="list-unstyled">
                <li v-for="file in contract.attachment_file" v-bind:key="file.id">
                  <a href="" class="btn-link text-secondary"  @click.prevent="downloadFile(file.id,file.name)" ><i v-bind:class="getIcon(file.ext)"></i> {{file.name}}</a>
                </li>
                 
              </ul>
              <!-- <div class="text-center mt-5 mb-3">
                <a href="#" class="btn btn-sm btn-primary">Add files</a>
                <a href="#" class="btn btn-sm btn-warning">Report contact</a>
              </div> -->
            </div>
          </div>
        </div>
        <loading :loading="loading"></loading>
        <!-- /.card-body -->
      </div>
       <contract-liquidation :contract="contract" ></contract-liquidation>
    </div>
</template>

<script>
// import ContractLiquidation from './ContractLiquidation.vue';
 
export default {
  // components: { ContractLiquidation },

  props:{
    id:String
  },
  data () {
    return {
      contract:{
        contract_num:"",
        sign_date:"",
        a_signer:"",
        a_position:"",
        b_signer:"",
        b_position:"",
        vendor_id:"",
        date_begin:"",
        date_end:"",
        content:" ",
        amount:"",
        budget_id: "",
        title:"",
        company_id:"",
        department_id:"",
        contract_terms:[],
        //contract_terms_del:[],
        attachment_file:[],
        //attachment_file_removed:[],
        
      },
        locale_format:'de-DE',
      fields: [

         {
            key: 'content',
             label:'Nội dung',
            sortable: true,
            stickyColumn: true
          },
          {
            key: 'amount_paid',
             label:'Số tiền',
            sortable: true,
            stickyColumn: true
          },
           {
            key: 'date_paid',
             label:'Ngày TT',
            sortable: true,
            stickyColumn: true
          },
         
        ],
      token:"",
      loading:false,
      page_url_contracts : "api/payment/contracts",
      page_url_department : "/api/category/departments",
    }
  },  
  created () {
     this.token = "Bearer " + window.Laravel.access_token;
     
      this.getContract();
  },
  methods:{
    getContract(){
       // console.log(this.id);
        if( this.id != ''){
          this.loading = true;
        var page_url = this.page_url_contracts+"/"+this.id;
        fetch(page_url,{ headers: { Authorization: this.token } })
        .then(res=>res.json())
        .then(res=>{
          //console.log(res);
          if(res.data.success == '1'){
              this.contract = res.data;
              //this.contract.contract_terms_del = [];
              //this.contract.attachment_file_removed = [];
             // this.edit_contract = true;
              this.loading = false;
              console.log(getIconFile('docx'));
          }else{
              this.loading = false;
          }
       
        }).catch(err=>{
            this.loading = false;
            console.log(err);
        });
        }
        
        
      },
      getIcon(ext){
        return getIconFile(ext);
      },
      isPaid(amount, amount_paid){
       
        return amount == amount_paid;
       
      },
      //Thanh lý hợp đồng
      contract_liquidation(){

        alert("Thanh lý hoàn tất");

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
              link.click();
            
              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));
           
        },
  },
  computed:{
    paidAmount(){
      var amount = 0;
      this.contract.contract_terms.forEach(element => {
        amount = amount + element.amount_paid;
      });
      return amount;
    },
     contract_childs(){
          let child_terms  = [];
          if(this.contract && this.contract.childs){
          this.contract.childs.forEach(child => {
             child.contract_terms.forEach(term => {
               term.contract_num_child = child.contract_num;
               child_terms.push(term);
             });
          });
          }

          return child_terms;
        },
   
    remainAmount(){
      return this.contract.amount - this.paidAmount;
    }
  }

}
</script>

<style>

</style>