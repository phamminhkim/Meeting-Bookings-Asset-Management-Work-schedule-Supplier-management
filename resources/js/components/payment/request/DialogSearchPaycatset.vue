<template>
     <!-- modal dialog -->
      <div class="modal fade" id="modal-paycatset">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Danh sách bộ chứng từ thanh toán</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                        <div class="col-md-10">
                            <input type="text" name="dialog_paycatset_search" @keyup.enter="getPaycatset()"   id="dialog_paycatset_search" class="form-control" placeholder="Tìm">
                        </div>
                        <button id="btn_dialog_find" type="button" v-on:click="getPaycatset()" class="btn btn-white btn-default btn-round">Tìm</button>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                          <table class="table table-bordered" id="dialog_tablemodel">
                              <thead>
                                  <tr>
                                      <th style="text-align:center;">
                                        <input type="checkbox" @change="checkAll()" ref="chk_all"  id="chk_all">
                                        </th>
                                      <th>id </th>
                                      <th>Tên</th>
                                      <th>Mô tả</th>
                                      
                                  </tr>
                              </thead>
                              <tbody id="dialog_user_body">
                                  <tr v-for="(paycatset,index) in paycatsets" v-bind:key="index">
                                        <td style="text-align:center;"> <input type="checkbox" ref="chk_item" class="checkitem"  name="ids[]" v-bind:value="paycatset.id"></td>
                                        <td>{{paycatset.id}}</td>
                                      <td>{{paycatset.name}}</td>
                                       <td>{{paycatset.description}}</td>
                                       
                                  </tr>
                              </tbody>
                          </table>
                          <div class="row">
                                <div class="col-sm-12 col-md-5"></div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="float-right mt-3">
                                        <pagination 
                                        :data="pagination" 
                                        :show-disabled="true" 
                                        @pagination-change-page="getPaycatset" 
                                    
                                        :limit="2"> </pagination>
                                    </div>
                                    
                                </div>
                               
                            </div>
                           <!-- Loading (remove the following to stop the loading)-->

                        <div class="overlay d-flex justify-content-center align-items-center" v-if="loading">
                            <i
                                class="fas fa-2x fa-sync fa-spin"
                            ></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <!-- end loading -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
              <a href="#" id="btn_close" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Đóng</a>

                <a href="#" class="btn btn-sm btn-primary" v-on:click.prevent="chooseItem()" id="btn_enter_user"> <i class="fa fa-check"></i> Chọn</a>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  
</template>

<script>
export default {
  created () {

        this.token = "Bearer " + window.Laravel.access_token;
     
  },
  mounted(){
     this.reset();
     this.getPaycatset();
  },
  props: {
  },
  data () {
    return {
        paycatsets:[],
        pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
        loading:false,
        page_url_paycatset :'api/category/search_paycatset',
    }
  },  
  methods: {
      reset(){
        $('#dialog_paycatset_search').val('');
      }
      ,
      getPaycatset(page,isSearch){
         this.loading = true;
         var valsearch = $('#dialog_paycatset_search').val();
         
         var search = ( valsearch == 'undefined'? '': ("&search="+valsearch)) ;   
         
         var  page_url = this.page_url_paycatset+ "?page=" + page + search;
          console.log(page_url);
          fetch(page_url,{
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
          }).then(res=>res.json())
          .then(data=>{
               
              this.paycatsets = data.data.data;
              this.pagination = data.data;
               this.loading = false;
              this.resetCheck();
              //console.log(data.data.data);
          }) .catch(err => {
                    
                    console.log(err);
                    this.loading = false;
                });

      },
      resetCheck(){
         $('#chk_all').prop('checked',false);
         $('.checkitem').prop('checked',false);
      }
      ,
      checkAll(){
       
         var checked = $('#chk_all').prop('checked');
        $('.checkitem').prop('checked',checked);
        
      },
      chooseItem(){
           var data = [];
          let paycatsets = this.paycatsets;
         // let user = {}

          $('input:checked.checkitem').each(function(){
            
              paycatsets.forEach(item=>{
                if(item.id == $(this).val()){
                   data.push(item);
                }
              });
            
          });
          if(data.length > 1){
              alert('Vui lòng chọn 1 bộ chứng từ');
          }else if(data.length < 1)
          {

          }else{
             this.$emit('selectedPaycatset',data);
             $('#modal-paycatset').modal('hide');
          }
                
       }
     
  },

}
</script>
    
<style>

</style>