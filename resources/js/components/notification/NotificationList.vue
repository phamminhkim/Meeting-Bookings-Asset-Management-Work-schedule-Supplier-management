<template>
  <div>
     <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"><i class="nav-icon fas fa-bell"></i>   {{$t("form.notification")}}</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                  <div class="btn-group-vertical">
                    <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                     
                      <!-- <button class="btn btn-primary dropdown-toggle dropdown-icon"  data-toggle="dropdown"> {{ $t('form.create')}} </button>

                      <div class="dropdown-menu mr-5" role="menu" >
                        
                        <a href="#" v-for="doc in document_types" v-bind:key="doc.id" @click.prevent="showAdd(doc.code)" class="dropdown-item">{{ $t(doc.name)}}</a>
                      
                      </div> -->
                  </div>
                  <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                </div>
               
            </div>   
         </div>
        </div>
      </div>
      <div class="card">
         <div class="card-body">
               
              <div class="row mt-1 " style="background-color:#F4F6F9">
                   
                <div class="col-md-9">
                     
                    <div class="form-group row">
                        <button type="button" class="btn btn-default btn-sm ml-2"  :title="$t('form.mark_read')" @click.prevent="mark_read()"><i class="fas fa-book-reader"></i> {{$t('form.mark_read')}}</button>
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
              <b-table hover responsive :bordered="false" outlined head-variant="light" thead-class=""  small
                :items="items"
                :fields="fields"
                :current-page="current_page"
                :per-page="per_page" 
                :filter="filter"
                    selectable
                  >

                    <template #cell(title)="data">
                      <div v-if="!data.item.read_at">
                        <i :class="data.item.data.data.icon" ></i>
                        <a :href="get_url(data.item.data.data.url,data.item.id)" class="ml-2"> 
                          <span style="color:#343a40 "> <strong> {{data.item.data.data.title}} </strong> </span><br>
                          <span class="text-mute text-sm ml-4 text-gray"   v-if="data.item.data.data.content"><strong>{{data.item.data.data.content}}</strong> </span>
                        </a> 
                      </div>
                      <div v-else >
                        <i :class="data.item.data.data.icon" style="color:gray" ></i>
                        <a :href="get_url(data.item.data.data.url,data.item.id)" class="ml-2"> 
                          <span  style="color:gray ">  {{data.item.data.data.title}}  </span><br>
                          <span class="text-mute text-sm ml-4 text-gray" v-if="data.item.data.data.content"> {{data.item.data.data.content}}  </span>
                        </a> 
                      </div>
                    </template>
                    <template #head(selected)  >
                      <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"></b-form-checkbox>
                    </template>
                     <template #cell(created_at)="data">
                          <span>{{data.value | formatDateTimeDB}}</span> 
                      </template> 
                    <template  v-slot:cell(selected)="data"   >
                      <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>  
                  </template> 
              </b-table>
              <div class="row"   >
                        <div class="col-md-12" >
                        <div class="form-group row">
                          <label  class="col-form-label-sm " style="text-align:left" for="">Per page:</label>
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
</template>

<script>
export default {
   props: {
       
  },
  data () {
    return {
        items:[],
      
        fields:[
            {
                key:"selected",
                lable:"",
                 stickyColumn: true,
                 thStyle:"width:10px"
            },
          
             {
                key:"title",
                label:this.$t('form.title'),
                 sortable: true,
                 stickyColumn: true,
                   class:"text-nowrap"
            },
             {
              key:"created_at",
                sortable: true,
              label:this.$t('form.date'),
                stickyColumn: true,
                class:"text-right text-nowrap col-sm-1"
            },
   
          
        ],
        selected:[],
        selectAll: false,
        loading: false,
        edit: false,
        token:"",
        errors:[],
        total: 0,
        per_page: 10,
        from: 1,
        to: 0,
        current_page: 1,
        filter:"",
        pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
        errors:{},
        status:"",
        page_url_notification:"/api/notifications",
    }
  },

  created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.fetNotification();

  },
  methods: {
    mark_read(){

      if(this.selected == ""){
        toastr.info(this.$t('Vui lòng chọn dữ liệu'));
        return;
      }
      var page_url = this.page_url_notification;
      fetch(page_url,{
        method:"post",
        body:JSON.stringify({'ids':this.selected}),
        headers:{
          Authorization:this.token,
          'Content-Type':"application/json",
          'Accept': "application/json",
          'X-Requested-Width':'XMLHttpRequest'
        }
      }).then(res=>res.json())
      .then(res=>{

        if(res.success == "1"){
          toastr.success(this.$t('form.update_success'),"");
          window.location.href="/notifications";
        }else{
            toastr.warning(this.$t('form.update_error'),"");
        }

      }).catch(err=>{
        console.log(err);
      })

    },
    get_url(url,noti_id){
      return url+"&notification_id="+noti_id;
    },
      fetNotification(){
          var page_url = this.page_url_notification;
          fetch(page_url,{
              method:"get",
             
              headers:{
                  Authorization:this.token,
                  'Content-Type':"application/json",
                  
              }
          }).then(res=>res.json())
          .then(res=>{
              this.items = res.data;

          }).catch(err=>{
              console.log(err);
          })
      },
       select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.items) {
            this.selected.push(this.items[i].id);
          }
        }
      },
  },
  computed:{
       rows(){
       
        return this.items.length;
      },
  },

}
</script>

<style>
.hidden_header {
  display: none;
}
</style>