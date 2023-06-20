<template>
     <!-- modal dialog -->
      <div class="modal fade" id="modal-user">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{$t('form.list')}} {{name}}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body card">
              <loading :loading="loading"></loading>

                <div class="row mt-1">
                    <div class="col-4">
                        <a href="#" class="btn btn-sm btn-info" v-on:click.prevent="chooseItem()" id="btn_enter_user"><i class="fas fa-arrow-circle-down"></i> {{$t('form.select')}}</a>
                    </div>
                    <div class="input-group input-group-sm  col-8">
                      <input class="form-control form-control-navbar mb-1" id="search" type="search" v-model="filter" :placeholder="$t('form.search')" aria-label="Search">
                      <span class="input-group-append">
                          <button type="button"  class="btn btn-default btn-flat mb-1"><i class="fas fa-search"></i></button>
                      </span>
                    </div>
                  </div>
              <div>
                <b-table
                  striped hover responsive :bordered="true" head-variant="light" sticky-header="false" small
                  :items="items"
                  :fields="fields"
                  :current-page="current_page"
                  :per-page="per_page"
                  :filter="filter"
                >

               <template #head(selected)>
                      <!-- {{selected}} -->
                <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll" @change="select"     >All</b-form-checkbox>
                </template>
                <template  v-slot:cell(selected)="data"   >
                <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected" ></b-form-checkbox>
                </template>

                </b-table>
                <div class="row"   >
                  <div class="col-md-12" >
                  <div class="form-group row">
                    <label  class="col-form-label-sm col-md-2" style="text-align:left" for="">Per page:</label>
                        <div class="col-md-3">
                            <b-form-select
                                size="sm"
                                v-model="per_page"
                                :options="pageOptions"

                            ></b-form-select>
                        </div>
                        <label  class="col-form-label-sm col-md-1" style="text-align:left" for=""></label>
                        <div class="col-md-4">
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

          <!-- /.modal-content -->
        </div>

        <!-- /.modal-dialog -->
      </div>

</template>

<script>
import Loading from './Loading.vue';

export default {
components:{
        Loading
    },
  created () {


      this.token = "Bearer " + window.Laravel.access_token;

  },
  mounted(){
       this.getListUser();
     ///this.getListUser();
  },
  props: {

       name:String,
       fields:Array,
       eventname:String,
       url:String,

  },
  data () {
    return {

        items:[],
        total: 0,
        per_page: 10,
        from: 1,
        to: 0,
        current_page: 1,
        filter:"",
        pageOptions: [2,10, 50, 100, 500, { value: this.rows, text: "All" }],
        page_url_user : "",
        loading:false,
        selected:[],
        selectAll: false,

    }
  },
  methods: {
      getListUser(){

        this.loading = true;
        var page_url = this.url;
        fetch(page_url,{
          headers:{
             Authorization: this.token,
             "content-type": "application/json"
          }
        }).then(res=>res.json())
          .then(data=>{

             this.items = data.data;
             this.loading = false;
          }).catch(err => {

                console.log(err);
                this.loading = false;
                });
      },
      select() {
        this.selected = [];
        if (this.selectAll) {
          for (let i in this.items) {
            this.selected.push(this.items[i].id);
          }
        }
      },
      chooseItem(){
        var data = [];
          let items = this.items;
          let user = {}

          items.forEach(item=>{
            if(  this.selected.includes(item.id) ){

                data.push(item);
            }
          });

          this.$emit(this.eventname,data);
          this.selected = [];

       }

  },
  computed:{

    rows(){
        return this.items.length;
      },
  },
  watch:{
       url(){
         this.filter ="";
         this.getListUser();
       },
      cols(){
           this.fields  = this.cols;

      },


  },
   ready:function(){

        //    this.fields  = this.cols;
        //    this.page_url_user = this.url;
        //   this.getListUser();
        // use this in the child component
    },

}
</script>

<style>

</style>
