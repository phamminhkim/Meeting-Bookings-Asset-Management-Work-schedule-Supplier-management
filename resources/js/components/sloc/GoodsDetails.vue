<template>
<div>
  <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/sloc/goods">Danh sách hàng hóa</a> </h5></li>
              
                <li class="breadcrumb-item active">
                  <span >Chi tiết</span> 
                  
                  </li>
             </ol>
            </div> 
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                      <a href="/sloc/goods" class="btn btn-default ">Quay lại</a>   
                       
              </div>
            </div> 
          </div>
        </div> 
      </div>
  <div class="card ">
          <div class="row">
            <div class="col-md-1"></div>
             <div class="col-md-5">

    <br>
      <div class="row">
        <div class="col-sm-3">
             <p class="tl">Mã mặt hàng: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.code }}</strong> </span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
             <p class="tl">Tên mặt hàng: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.name }}</strong> </span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
             <p class="tl">Đơn vị hàng: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.goodunit_id }}</strong> </span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
             <p class="tl">Kích thước: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.size }}</strong> </span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
             <p class="tl">Màu sắc: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.color }}</strong> </span>
        </div>
      </div>
       <div class="row">
        <div class="col-sm-3">
             <p class="tl">Trọng lượng: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.weight }}</strong> </span>
        </div>
      </div>
       <div class="row">
        <div class="col-sm-3">
             <p class="tl">Tồn kho: </p>
        </div>
        <div class="col-sm-9">
          <span style="text-align:center;"> <strong  >{{ goodss.open_stock }}</strong> </span>
        </div>
      </div>
      
      
             
            
           
      

 
 </div>
   <div class="col-md-6">
    <br>

              <ul class="list-unstyled">
                         

                <li v-for="file in goodss.attachment_file" v-bind:key="file.id"
               type="file" 
                                              accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" 

               @change="emitEvent($event)">
                  
                  <div  v-if="image" >
                              <img :src="image" class="img-responsive"  style="border-radius:35px;width: 90%;height:auto;">
                        </div>                           
                  <div  v-if="file.url" >
                   <img :src="file.url"  class="img-responsive"  style="border-radius:35px;width: 90%;height:auto;">
                    </div>
                                               
                </li>
                 
              </ul>
              </div>
              </div>     
                    <loading :loading="loading"></loading>
 
              </div>
              

</div>
 
</template>
<script>
export default {
  props: {
    id: String,
    goodunit_id: String,
 user_id: String,
  },
  data() {
    return {
      
      goodss:{
        goodunit_id:"",
                attachment_file:[],
                name:"",

      },
      image:'',
      token: "",
      loading: false,
      url_goods: "/api/sloc/goods",
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;

    this.getGoods();
  },
  methods: {
    
    getGoods() {
      if (this.id != '') {
        this.loading = true;

        var page_url = this.url_goods + "/" + this.id + "?notification_id=" + this.notification_id;
        fetch(page_url, { headers: { Authorization: this.token } })
          .then(res => res.json())
          .then(res => {

            if (res.data.success == '1') {
              this.goodss = res.data;

              this.loading = false;
                            console.log(getIconFile('docx'));

            } else {
              this.loading = false;
            }

          }).catch(err => {
            this.loading = false;
            console.log(err);
          });
      }


    },
     getIcon(ext){
        return getIconFile(ext);
      },
  },
  computed: {
   


  }

}
</script>

<style>
</style>