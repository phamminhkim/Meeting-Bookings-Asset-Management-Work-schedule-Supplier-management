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
                  <div v-if="hasAnyError > 0">
                      <div class="alert alert-warning">
                        <button type="button" class="close" @click.prevent="clearAllError()">
                          <i class="ace-icon fa fa-times"></i>
                        </button>
                        <ul>
                          <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
                        </ul>
                      </div>
                    </div>
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
                    </div>
                  </div>
                  <div class="form-group row">
                    <b-table hover responsive :bordered="true"   head-variant="light" sticky-header="true" small
                            :items="infos.limits"
                            :fields="fields"
                        >
                        
                          <template #head(from)>
                            <span>HM Từ<small class="text-red">(*)</small></span>
                         </template>
                          <template #head(to)>
                            <span>HM Đến<small class="text-red">(*)</small></span>
                         </template>
                         <template #head(value)>
                            <span>Xếp loại<small class="text-red">(*)</small></span>
                         </template>
                         
                        <template #cell(name)="data">
                             <span class="text-gray">{{data.value}}</span>
                            <!-- <input v-model="data.item.name" type="text"  maxlength="50" class="form-control form-control-sm"> -->
                        </template>
                        <template #cell(from)="data">
                             <vue-numeric v-bind:precision="2" :separator="separator" de  v-model="data.item.from" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input v-model="data.item.from" type="number" style="text-align:right"  class="form-control form-control-sm"> -->
                        </template>
                          <template #cell(to)="data">
                               <vue-numeric v-bind:precision="2"  :separator="separator" v-model="data.item.to" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                            <!-- <input  v-model="data.item.to" type="number" style="text-align:right" class="form-control form-control-sm"> -->
                        </template>
                        <template #cell(value)="data">
                          <input  class="form-control form-control-sm"  v-model="data.item.value">                            
                        </template>
                         
                        <template #cell(action)="data">
                            <button @click.prevent="delete_row(data.item,data.index)" class="btn btn-xs "  title ="Delete" >
                                        <i  class="text-red fa fa-trash bigger-120" ></i>
                            </button>
                        </template>
                        </b-table>
                        <a href="#" @click.prevent.stop="add_new_row()"> <i class="fa fa-plus-circle"></i> <i> dòng mới </i> </a>
                  </div>
                </div>
              </div>
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
  data(){
    return{
      infos:{
            
            company_id:"",          
            limits:[],
            limits_del:[]
        },
      separator:'.',
      form_filter:{
        company_id:"",           
      },
      selected: {},      
      fields:[        
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
            key:"value",
            lable:"Xếp loại",
            class:"text-right"
        },       
        {
            key:"action",
            lable:"action"
        }
        ],
      companies:[],
      loading: false,
      edit: false,
      token:"",
      errors:[],        
      page_url_hrconfigtype:"api/category/hrconfigtype",
      page_url_company:"/api/category/companies",
      hrconfigtype_index_form:"api/category/hrconfigtype_index_form",
     
 
    }
  },
  created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.fetCompany();    
  },
  methods: {

    save(){
          this.loading = true;
          var page_url = this.page_url_hrconfigtype;         
          this.infos.company_id = this.form_filter.company_id;         
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
                  this.fetchTypeLimit();
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
    fetCompany() {
         var page_url = this.page_url_company;
         fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {
                this.companies = res.data;
            })
            .catch(err => console.log(err));
        },
    filter_data(){
        
        this.fetchTypeLimit();
    },
    add_new_row(){
            var limit = {};
            limit.from = "";
            limit.to = "";
            limit.value = "";
            this.infos.limits.push({... limit});

      },

      delete_row(item,index){
        // alert('test');
        if(confirm('Xác nhận xoá?')){
          this.infos.limits_del.push({...item});
          this.infos.limits.splice(index,1);
        }
      },
    fetchTypeLimit(){

      if(this.form_filter.company_id === ''  )
        {
            return ;
        }

        this.loading= true;
      const params = new URLSearchParams(
          {
              company_id:this.form_filter.company_id,             
          }
        );
        
        var page_url = this.hrconfigtype_index_form +"?"+params.toString();
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
      
    },
    clearAllError(){
        this.errors = {};
    },
  },
  computed: {
    
    hasAnyError() {
      return Object.keys(this.errors).length > 0;
    },
  },
}
</script>

<style>

</style>