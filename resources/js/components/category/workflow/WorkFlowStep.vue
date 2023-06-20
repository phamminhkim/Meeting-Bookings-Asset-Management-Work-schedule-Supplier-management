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
                                <label class="col-form-label" for="">{{$t('form.wfmains_type')}}</label>
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
                                <b-select :options="steps" v-model="data.item.step" ></b-select>
                        </template>     
                         <template #cell(code)="data">
                              <input v-model="data.item.code" type="text" class="form-control form-control-sm custom-select">
                        </template>
                         <template #cell(name)="data">
                              <input v-model="data.item.name" type="text" class="form-control form-control-sm custom-select">
                        </template>
                         <template #cell(description)="data">
                            <input v-model="data.item.description" type="textarea" class="form-control form-control-sm custom-select">
                        </template>
                         <template #cell(wfusertype_id)="data">
                            <b-select :options="wfapptypes" v-model="data.item.wfusertype_id"  class="form-control form-control-sm "></b-select>
                        </template>
                         <template #cell(form_approval)="data">
                            <input v-model="data.item.form_approval" type="textarea" class="form-control form-control-sm custom-select">
                        </template>
                         <template #cell(next_user)="data">
                            <b-select :options="names" v-model="data.item.next_user"   class="form-control form-control-sm"></b-select>
                        </template>
                          <template #cell(is_end)="data">
                            <input v-model="data.item.is_end" type="number" class="form-control form-control-sm custom-select">
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
            wfmain_id:"",
            steps:[],
            steps_del:[],
            wfapptype_id:''
        },
        steps:[],
        names:[],
        wfapptypes:[],
        documentTypes:[],
        selected: {},
        fields:[

            {
                key:"step",
                 label:this.$t('form.step'),
            },
            {
                key:"code",
                 label:this.$t('form.code'),
            },
             {
                key:"name",
                label:this.$t('form.name'),
            },
             {
                key:"description",
                 label:this.$t('form.description'),
            },
            ,
              {
                key:"wfusertype_id",
                 label:this.$t('form.type'),
            },
             {
                key:"form_approval",
                 label:this.$t('form.form_approval'),
            },
              {
                key:"next_user",
                 label:this.$t('form.next_user'),
            },
             {
                key:"is_end",
                 label:this.$t('form.is_end'),
            },
            {
                key:"action",
                lable:"action"
            }
        ],
        loading: false,
        edit: false,
        token:"",
        page_url_wfmains_type:"api/category/wfmains",
        page_url_wfstep:"api/category/wfsteps",
        //page_url_wfsteps:"api/category/wfsteps",
        page_url_step:"api/category/steps",
        page_url_name:"api/category/wfapptypes",
        page_url_wfapptype:"api/category/wfusertypes",
    }
  },
  created () {
       this.token = "Bearer " + window.Laravel.access_token;
       this.get_step();
       this.get_document_type();
       this.get_name_app_type();
       this.get_wfapptype();

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
    get_name_app_type(){
        this.loading =true;
        var page_url = this.page_url_name;
        fetch(page_url,{
            method:'get',
            headers:{
                Authorization:this.token,
                "content-type": "application/json"
            }
        }).then(res=>res.json())
        .then(data=>{
              this.loading =false;
              //console.log(data);
              if(data.success== 1){
                 var  obj={}
                 data.data.forEach(element => {
                        obj ={};
                        obj.value=element.id;
                        obj.text=element.name;
                        this.names.push({...obj});
                 });
                         
              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });
       
      },
     get_wfapptype(){
        this.loading =true;
        var page_url = this.page_url_wfapptype;
        fetch(page_url,{
            method:'get',
            headers:{
                Authorization:this.token,
                "content-type": "application/json"
            }
        }).then(res=>res.json())
        .then(data=>{
              this.loading =false;
              //console.log(data);
              if(data.success== 1){
                 var  obj={}
                 data.data.forEach(element => {
                        obj ={};
                        obj.value=element.id;
                        obj.text=element.name;
                        this.wfapptypes.push({...obj});
                 });
                         
              }
        }).catch(err => {
            this.loading = false;
            console.log(err);
            });
       
      },
      get_document_type(){
        this.loading =true;
        var page_url = this.page_url_wfmains_type;
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
          var page_url = this.page_url_wfstep +"?wfmain_id="+this.selected;
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
          var page_url = this.page_url_wfstep ;
          this.infos.wfmain_id = this.selected;
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