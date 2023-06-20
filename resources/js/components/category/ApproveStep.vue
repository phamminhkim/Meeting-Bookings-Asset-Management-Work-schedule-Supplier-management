<template>
   <div>
   <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark">{{$t(title)}}</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <!-- <a class="btn  btn-default" href="/category/team" type="button">
                        <i class="ace-icon fa fa-close bigger-110"></i>
                        Đóng
                 </a> -->
                  <a href="#" class="btn btn-primary" @click.prevent="save()"><i class="fa fa-save"></i> {{$t('form.save')}}</a>
                </div>
               
            </div>   
         </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-form-label" for="">{{$t('form.document_type')}}</label>
                                <div class="col-md-4 mb-2">
                                     <b-form-select v-model="selected" @change="change_type()" :options="documentTypes"  ></b-form-select>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <b-table hover responsive :bordered="true" sticky-header="false" small
                            :items="infos.steps"
                            :fields="fields"
                        >
                        <template #cell(step)="data">
                                <b-select :options="steps" v-model="data.item.step" size="sm"></b-select>
                                <!-- <select name="" id="" v-model="data.item.step" class="form-control form-control-sm">
                                    <option value="1">Step 1</option>
                                    <option value="2">Step 2</option>
                                    <option value="3">Step 3</option>
                                    <option value="4">Step 4</option>
                                </select> -->
                        </template>     
                        <template #cell(name)="data">
                            <input v-model="data.item.name" type="text" class="form-control form-control-sm">
                        </template>
                        <template #cell(action)="data">
                            <button @click="delete_row(data.item,data.index)" class="btn btn-xs "  title ="Delete" >
                                        <i  class="text-red fa fa-trash bigger-120" ></i>
                            </button>
                        </template>
                        </b-table>
                         <a href="#" @click.prevent.stop="add_new_row()"> <i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i> </a>
                    </div>
                      <loading :loading="loading"></loading>
                </div>
            </div>
        </div>

    </div>

   </div>
</template>

<script>
export default {
  data () {
    return {

        infos:{
            document_type_id:"",
            steps:[],
            steps_del:[]
        },
        steps:[],
        documentTypes:[],
        selected: {},
        fields:[

            {
                key:"step",
                 label:this.$t('form.step'),
            },
             {
                key:"name",
                label:this.$t('form.name'),
            },
            {
                key:"action",
                lable:"action"
            }
        ],
        loading: false,
        edit: false,
        token:"",
        page_url_document_type:"api/category/documenttypes",
        page_url_approvestep:"api/category/approvestep",
        page_url_step:"api/category/steps",
    }
  },
  created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.get_step();
       this.get_document_type();

  },
  methods: {
    get_step(){
        this.loading =true;
        var page_url = this.page_url_step;
        fetch(page_url,{
            method:'get',
            headers:{
                Authorization:this.token,
                "content-type": "application/json"
            }
        }).then(res=>res.json())
        .then(data=>{
              this.loading =false;
              console.log(data);
              if(data.success== 1){
                 var  obj={}
                 data.data.forEach(element => {
                        obj ={};
                        obj.value=element.id;
                        obj.text=element.name;
                        this.steps.push({...obj});
                 });
                         
              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });
       
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
              console.log(data);
              if(data.success== 1){
                 var  documentType={}
                 data.data.forEach(element => {
                        documentType ={};
                        documentType.value=element.id;
                        documentType.text=element.name;
                        this.documentTypes.push({...documentType});
                 });
                        documentType ={};
                        documentType.value="";
                        documentType.text="----------";
                        this.documentTypes.push({...documentType});
              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });
       
      },
      change_type(){
          this.loading= true;
          var page_url = this.page_url_approvestep +"?document_type_id="+this.selected;
          fetch(page_url,{
              method:'get',
              headers:{
                  Authorization:this.token,
                  'content-type':'application/json'
              }
          }).then(res=>res.json())
          .then(data=>{
              this.loading= false;
              this.infos.steps = data.data;
          }).catch(err=>{
              this.loading= false;
          })

      },
      add_new_row(){
            var info = {};
            info.step = "";
            info.name ="";
            this.infos.steps.push({... info});
       
      },
      save(){
          this.loading = true;
          var page_url = this.page_url_approvestep ;
          this.infos.document_type_id = this.selected;
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
             console.log(data);
             if(data.data.success == 1){
                toastr.success("Lưu thành công", "Thông báo");
             }else{
                toastr.warning("Lỗi", "Thông báo");
                    
             }
         }).catch(err=>{
             console.log(err);
             this.loading = false;

         });

      },
      delete_row(item,index){
            
            
           if(confirm('Xác nhận xoá?')){
            this.infos.steps_del.push({...item});
            this.infos.steps.splice(index,1);
          }
      }

  },
  props: {
      title:String
  },

}
</script>

<style>

</style>