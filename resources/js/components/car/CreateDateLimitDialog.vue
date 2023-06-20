<template>
<div class="modal fade" id="createDateLimitModal" tabindex="-1" role="dialog" aria-labelledby="createDateLimitModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action=""  @submit.prevent="AddDatelimit()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createDateLimitModal"><i class="fa fa-calendar"></i> {{$t('form.date_to_limit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
     
              <div class="form-group">
              <table>
              <tr>
              <td>
                 <label for="date_to_limit">{{$t('form.date_to_limit')}}</label>
              </td>
              <td>
                 <input type="date" class="form-control"
                  v-bind:class="hasError('date_to_limit')?'is-invalid':''"
                   @click="clearError($event)"
                 aria-required="true"
                  v-model="datelimit.date_to_limit"    >
                    <span v-if="hasError('date_to_limit')" class="invalid-feedback" role="alert">
                      <strong>{{getError('date_to_limit')}}</strong>

                     </span>
              </td></tr>
              </table>
              </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> {{$t('form.close')}}</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{$t('form.save')}}</button>
      </div>
    </div>
      </form>
  </div>
</div>

</template>

<script>
export default {
  watch: {
    object_id(){
      this.datelimit.object_id = this.object_id[0];
    },
    id(){
        this.edit = false;
        this.fetDatelimit(this.id);
    },
  },
      props: {
            title:"form.create_datelimit",
            object_id:"",
            module:"",
            id:"",


    //Mỗi đối tượng muốn sử dụng datelimit thì phải cài đặt API để nó gọi vào
    //page_url_reminder:"api/reminders",
  },

  data () {
    return {
      datelimit:{
        object_id: this.object_id[0],
        module :this.module,
        type_id:1,
        date_to_limit:""
      },

      errors:{},
      loading: false,
      edit: false,
      token:"",
      page_url_update_date_limit : "api/car/update_date_limit",
      page_url_car: "/api/car/systems",
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.fetDatelimit('');
  },
  methods: {
       refresh() {
             location.reload();
     },
    reset(){
        this.datelimit.object_id           = this.object_id[0];
        this.datelimit.module              =this.module;
        this.datelimit.type_id             =1;
        this.datelimit.date_to_limit       = "";
        this.datelimit.id                  ="";
    },
    fetDatelimit(id){

       this.reset();
       //this.datelimit = {};
       //this.datelimit.type_id = 1;
      if(id != "" && id != "undefined"){
        var page_url = this.page_url_car+"/"+id;
        fetch(page_url,{
          method:"get",
          headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                    "Accept" :"application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
        }).then(res=>res.json())
        .then(res=>{
                   // console.log(res);
                    if(res.statuscode =="403"){
                      window.location.href = "/errorpage?statuscode="+res.statuscode;
                    }
                    if (!res.data.errors) {
                          //toastr.success(this.$t('form.save_success'),"");
                          this.datelimit = res.data;
                        //  this.datelimit.date_due = this.datelimit.date_due.toLocalString();
                            this.edit = true;
                    }else{
                        console.log(this.errors);
                        this.errors = res.data.errors;
                    }

        }).catch(err=>{
          console.log(err);
        });
      }

    },
    AddDatelimit() {
     // console.log(this.datelimit);
           this.loading= true;
            var page_url = this.page_url_update_date_limit;
            this.datelimit.module = this.module;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.datelimit),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json",
                  "Accept" :"application/json",
                  "X-Requested-With": "XMLHttpRequest"
              }
          })
              .then(res => res.json())
              .then(data => {
                //alert(data.statuscode);'
                 this.loading= false;

              //  console.log(data);
                  //  if(data.statuscode == "403"){
                  //       toastr.warning(data.message,"");
                  //       return;
                  //   }


                  if (!data.data.errors  && data.data.success === 1) {

                       // console.log(data.data.datelimit);

                        toastr.success(this.$t('form.save_success'),"");
                      $("#createDateLimitModal").modal("hide");
                        //this.fetDatelimit(id);
                        this.refresh();
                        this.reset();

                  }else{
                      console.log(this.errors);

                      this.errors = data.data.errors;

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.datelimit.object_id = this.object_id[0];
              this.datelimit.module =this.module;
              console.log(this.datelimit.id);
                fetch(page_url+"/"+this.datelimit.id, {
              method: "PUT",
              body: JSON.stringify(this.datelimit),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json",
                  "Accept" :"application/json",
                  "X-Requested-With": "XMLHttpRequest"
              }
          })
              .then(res => res.json())
              .then(data => {
                //  console.log(data);
                   if(data.statuscode == "403"){
                        toastr.warning(data.message,"");
                        return;
                    }

                  if (!data.data.errors && data.data.success === 1) {
                         toastr.success(this.$t('form.update_success'),"");
                         $("#createDateLimitModal").modal("hide");
                        // this.fetDatelimit(id);
                         this.refresh();
                  }else{
                      this.errors = data.data.errors;
                  }
                    this.loading= false;
              })
              .catch(err => {
                this.loading= false;

                });
          }

      },
       hasError(fieldName){
            return (fieldName in this.errors);
        },
        getError(fieldName){
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        },
      hasAnyError(){
          return Object.keys(this.errors).length > 0;
      },
          clearAllError(){
          this.errors = {};
      },
      clearError(event){
        Vue.delete( this.errors,event.target.name);
          //console.log(event.target.name);
      },
  },

}
</script>

<style>

</style>
