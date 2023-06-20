<template>
<div class="modal fade" id="createMonitorModal" tabindex="-1" role="dialog" aria-labelledby="createMonitorModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 1366px;">
      <form action=""  @submit.prevent="AddMonitor()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createMonitorModal"><i class="fa fa-info-circle" style="color: #17a2b8;"></i> {{$t('form.monitor_implementation')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
          <table class="table table-bordered">  
					<tr>
						<th>STT</th>
						<th>Biện pháp khắc phục</th>
						<th>Đánh giá</th>
						<th>Ngày đánh giá</th>
            <th>Ngày hoàn thành</th>
					</tr>
					<tr  v-for="(monitor, index) in  this.causes" :key="monitor.id" >
					<td>{{index+1}}</td>
					<td v-html="monitor.measure"></td>
					<td>
				  <select v-model="monitor.result" class="form-control select2" required>
					<option value="0">Hoàn thành</option>
					<option value="1">Chưa hoàn thành</option>	
					</select>
					</td>
					<td><input  v-model="monitor.date"  type="date" class="form-control" required/></td>
          <td><input  v-model="monitor.finished_date"  type="date" class="form-control" required/></td>
				  </tr>
        	</table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> {{$t('form.close')}}</button>
        <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> {{$t('form.save')}}</button>
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
      this.monitor.id = this.object_id[0];
      this.get_Measure();
    },
    id(){
        this.edit = false;
        this.fetMonitor(this.id);
    },
  },
      props: {
            title:"form.create_monitor",
            object_id:"",
            module:"",
            id:"",
  },

  data () {
    return {
      causes: [],
      monitor:{
        cause_measures_id:"",
        causes:"",
        object_id: this.object_id[0],
        module :this.module,
        type_id:1,
        result:'',
        date:'',
        finished_date:''
      },
        infos: {
                steps: [],
                steps_del: []
            },
      errors:{},
      loading: false,
      edit: false,
      token:"",
      page_url_reminder : "api/reminders",
      page_url_measure : "api/car/cause_measures",
      page_url_monitor_implementation : "api/car/monitor_implementations"
    }
  },
  created () {
      this.token = "Bearer " + window.Laravel.access_token;
      this.fetMonitor('');
      //this.get_Measure();
  },
  methods: {
      refresh() {
             location.reload();
     },
    reset(){
        this.monitor.measure             = "";
        this.monitor.date            = "";
        this.monitor.finished_date   = "";
        this.monitor.id             ="";
    },
    fetMonitor(id){
       this.reset();
       //console.log(new Date().toLocaleDateString());
       //console.log(id);
       //this.monitor = {};
       //this.monitor.type_id = 1;
      if(id != "" && id != "undefined"){
      //  console.log(id);
        var page_url = this.page_url_monitor_implementation+"/"+id;
        //console.log(page_url);
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
                    //console.log(res);
                    if(res.statuscode =="403"){
                      window.location.href = "/errorpage?statuscode="+res.statuscode;
                    }
                    if (!res.data.errors) {
                          //toastr.success(this.$t('form.save_success'),"");
                          this.monitor = res.data;
                        //  this.monitor.date_due = this.monitor.date_due.toLocalString();
                       // console.log(this.monitor);
                            this.edit = true;
                    }else{
                       // console.log(this.errors);
                        this.errors = res.data.errors;
                    }

        }).catch(err=>{
          console.log(err);
        });
      }

    },
    get_Measure() {
            this.loading = true;
            var page_url =
                this.page_url_measure +
                "?car_id="+
                this.object_id[0];
              fetch(page_url,{ headers: { Authorization: this.token }})
                .then(res => res.json())
                 .then(res => {
                   this.causes = res.data;
                   // console.log(res.data);
                })
                .catch(err => console.log(err));
        },
    AddMonitor() {
           this.loading= true;
            var page_url = this.page_url_monitor_implementation;
            this.monitor.module = this.module;
          if(this.edit === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify({
                    id: this.object_id[0],
                    module:this.module,
                    monitor:this.causes
                }),
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

                   if(data.statuscode == "403"){
                        toastr.warning(data.message,"");
                        return;
                    }


                  if (!data.data.errors  && data.data.success === 1) {

                       // console.log(data.data.monitor);

                        toastr.success(this.$t('form.save_success'),"");
                      $("#createMonitorModal").modal("hide");
                      //this.$emit('getcar');
                       //this.$emit('fromMonitor',data.data.monitor);
                       this.refresh();

                  }else{
                     /// console.log(this.errors);

                      this.errors = data.data.errors;

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
              this.monitor.object_id = this.object_id[0];
              this.monitor.module =this.module;
             // console.log(this.id);
                fetch(page_url+"/"+this.id, {
              method: "PUT",
              body: JSON.stringify({ 
                    id: this.object_id[0],
                    monitor:this.causes}),
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
                         $("#createMonitorModal").modal("hide");
                         this.$emit('getcar');
                         //this.$emit('fromMonitor',data.data.monitor);
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
       add_new_row() {
            var info = {};
            info.step = "";
            this.infos.steps.push({ ...info });
        },
        delete_row(item, index) {
            if (confirm("Xác nhận xoá?")) {
                this.infos.steps_del.push({ ...item });
                this.infos.steps.splice(index, 1);
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
