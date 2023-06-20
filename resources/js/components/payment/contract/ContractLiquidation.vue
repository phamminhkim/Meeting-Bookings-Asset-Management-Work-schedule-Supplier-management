<template>
    <div>
        <div class="modal" id="thanhlyModal" tabindex="-1" role="dialog">
          <form action=""  @submit.prevent="add_contract_liquidations()"  >
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Thanh lý hợp đồng</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-level="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group row">
                      <label for="" class="col-form-label-sm col-sm-3">Ngày thanh lý <small class="text-red">( * )</small></label>
                      <div class="col-md-8">
                        <input type="date" v-model="contract_liquidation.date_liquid" class="form-control" required name="" id="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-form-label-sm col-sm-3">Nội dung  <small class="text-red">( * )</small></label>
                      <div class="col-md-8">
                        <textarea name="" id="content"  class="form-control textarea" required  cols="30" rows="20"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-form-label-sm col-sm-3">File(s) </label>
                      <div class="col-md-8">
                       <input type="file"
                        accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" 
                          @input="emitEvent($event)"
                          id="ThemFile"
                          style="display:none"
                          ref="file"
                          multiple
                        >
                        <a  href="#"
                      v-on:click.prevent="handleClickInputFile()"
                      >  <i class="fas fa-paperclip"></i> </a>
                       <span > {{contract_liquidation.attachment_file.length}} Files </span>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    
                    <button class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Thanh lý</button>
                  </div>
              </div>
            </div>
        </form>
      </div>
    </div>
</template>

<script>
export default {
  props: {
      contract:{},
      id:"",
  },
  data () {
    return {
        contract_liquidation:{
        contract_id:this.contract.id,
        date_liquid:"",
        content:"",
        attachment_file:[],
      },
      token:"",
      loading:false,
      page_url_contract_liquidations : "api/payment/contract_liquidations",
    }
  },
   created () {
     this.token = "Bearer " + window.Laravel.access_token;
     
      //this.getContract();
  },
  methods: {
      add_contract_liquidations() {
            this.loading= true;
             var page_url = this.page_url_contract_liquidations; 
             this.contract_liquidation.contract_id = this.contract.id; 
             this.contract_liquidation.content =  $('#content').summernote('code');
        
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.contract_liquidation),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
                 })
                .then(res => res.json())
                .then(data => {
                    console.log('tesst');
                    if (!data.data.errors) {
                         toastr.success('Thanh lý thành công',"Thông báo");
                        $("#thanhlyModal").modal("hide");
                    }else{
                        this.errors = data.data.errors;
                    }
                    this.loading= false;
                })
                .catch(err => {
                  this.loading= false;
                  console.log(err);
                  });
            
        },
         handleClickInputFile() {
            
            this.$refs.file.click();
     
        },
         emitEvent(event) {
             
              this.contract_liquidation.attachment_file = [];
            for (let index = 0; index < event.target.files.length; index++) {
              const file = event.target.files[index];
              //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
              let reader = new FileReader();
              reader.readAsDataURL(file);

              reader.onload = () => {
                  // console.log(event.target.files[0]);
                  const docs = {
                      name: file.name,
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                      base64: reader.result
                  };
                 // console.log(docs);
                  this.contract_liquidation.attachment_file.push({...docs});

              };
            }

        },
    //    getContractLiquidation(){
    //    // console.log(this.id);
    //     if( this.id != ''){
    //     this.loading = true;
    //     var page_url = this.page_url_contract_liquidations+"/"+this.id;
    //     fetch(page_url,{ headers: { Authorization: this.token } })
    //     .then(res=>res.json())
    //     .then(res=>{
    //     console.log(res);
    //     if(res.data.success == '1'){
    //         this.contract = res.data;
    //         //this.contract.contract_terms_del = [];
    //         //this.contract.attachment_file_removed = [];
    //         // this.edit_contract = true;
    //         this.loading = false;
    //         //console.log(getIconFile('docx'));
    //     }else{
    //         this.loading = false;
    //     }
    
    //     }).catch(err=>{
    //         this.loading = false;
    //         console.log(err);
    //     });
    //     }
        
        
    //   },
  },
    
}
</script>

<style>

</style>