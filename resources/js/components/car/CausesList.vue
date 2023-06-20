<template>
    <div class="card" >
            <div class="card-footer" v-if="(this.step_user===1 && this.step_approve!=null && this.is_status!=2) || (this.step_user===1 && this.is_evaluation===1) || (this.step_user===1 && this.step_approve===null && this.is_document==='SCAR' && this.is_cause===1) || (this.step_user===1 && this.step_approve===null && this.is_document==='PCAR' && this.is_process===1 && this.is_status!=2 && this.is_cause===0) || (this.step_user===1 && this.step_approve===3 && this.is_document==='PCAR' && this.is_evaluation ===0 && this.is_status!=2)|| (this.step_user===1 && this.step_approve===null && this.is_document==='PCAR' && this.is_exist_monitor ===1 && this.is_status!=2) || (this.step_user===1 && this.step_approve===null && this.is_document==='PCAR' && this.is_process ===1 && this.is_exist_cause===1) || (this.step_user===1 && this.is_status===-2 && this.is_cause_measure==0 && this.is_step1_approve===1) || (this.step_user===1 && this.step_approve===null  &&  this.is_document==='PCAR' && this.is_process===0 && this.is_status===-2 && this.is_step1_approve===1)">
                <button  v-if="(this.step_approve===1 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step1_approve===1 ) || (this.step_approve===2 && this.is_document==='PCAR' && this.is_step1_approve===1) || (this.step_approve===2 && this.is_process===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===-2 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step===2 && this.is_step1_approve===1) || ( this.is_document==='PCAR' && this.step_approve===null && this.is_process===0 && this.is_status===-2 && this.is_cause===0 && this.is_step1_approve===1) ||
                (this.step_approve===1 && this.is_document==='SCAR' && this.is_step1_approve===1) || (this.step_approve===null && this.is_document==='SCAR' && this.is_exist_monitor===0 && this.is_step1_approve===1) || (this.step_approve===2 && this.is_document==='SCAR' && this.is_step1_approve===1)||
                (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===0) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===1) || (this.step_approve===4 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1 && this.is_exist_cause===1 && this.is_exist_monitor===0 && this.is_step3_approve===0) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_status!=-2) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_exist_cause===1 && this.is_cause_measure===1 && this.is_monitor===0 && this.is_status ===-2) || (this.is_status===-2 && this.is_cause_measure===0 && this.is_document==='PCAR' && this.is_step2_approve===1) || (this.is_document==='PCAR' && this.is_step===3 && this.is_cause===0 && this.is_status ===-2)" class="btn btn-info btn-sm" :title="$t('form.assign_user')" @click="showAssign()"><i class="fa fa-tasks" style="color:#ffffff;"></i> {{$t('form.assign_user')}} </button>
                <button v-if="(this.step_approve===1 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step1_approve===1 ) || (this.step_approve===2 && this.is_document==='PCAR' && this.is_step1_approve===1) || (this.step_approve===2 && this.is_process===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===-2 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step===2 && this.is_step1_approve===1) || ( this.is_document==='PCAR' && this.step_approve===null && this.is_process===0 && this.is_status===-2 && this.is_cause===0 && this.is_step1_approve===1)" class="btn btn-info btn-sm" :title="$t('form.fast_process')" @click="showFastProcess()"><i class="fa fa-spinner" style="color:#ffffff;"></i> {{$t('form.fast_process')}} </button>
                <button v-if="(this.step_approve===1 && this.is_document==='SCAR' && this.is_step1_approve===1) || (this.step_approve===null && this.is_document==='SCAR' && this.is_exist_monitor===0 && this.is_step1_approve===1) || (this.step_approve===2 && this.is_document==='SCAR' && this.is_step1_approve===1)" class="btn btn-warning btn-sm" :title="$t('form.causes')" @click="showCause()" style="color:#ffffff; background-color: #fd7e14;border-color: #fd7e14;"><i class="fa fa-comments" style="color:#ffffff;"></i> {{$t('form.causes_and_measures')}} </button>
                <button v-if="(this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===0) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===1) || (this.step_approve===4 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1 && this.is_exist_cause===1 && this.is_exist_monitor===0 && this.is_step3_approve===0) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_status!=-2) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_exist_cause===1 && this.is_cause_measure===1 && this.is_monitor===0 && this.is_status ===-2) || (this.is_status===-2 && this.is_cause_measure===0 && this.is_document==='PCAR' && this.is_step2_approve===1) || (this.is_document==='PCAR' && this.is_step===3 && this.is_cause===0 && this.is_status ===-2)" class="btn btn-warning btn-sm" :title="$t('form.causes')" @click="showCause()" style="color:#ffffff; background-color: #fd7e14;border-color: #fd7e14;"><i class="fa fa-comments" style="color:#ffffff;"></i> {{$t('form.causes_and_measures')}} </button>
                <button v-if="(this.step_approve===3 && this.is_document==='SCAR') || (this.step_approve===4 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===1 && this.is_exist_monitor===0 && this.is_step3_approve===1)" class="btn-info btn btn-sm" :title="$t('form.monitor_implementation')" @click="showMonitor()"><i class="fas fa-check-circle" style="color:#ffffff;"></i> {{$t('form.monitor_implementation')}} </button>
                <button v-if="(this.step_approve===4 && this.is_document==='SCAR') || (this.step_approve===null && this.is_evaluation===1 && this.is_document==='SCAR') || (this.step_approve===null && this.is_evaluation===0 && this.is_document==='SCAR' && this.is_monitor===1)" class="btn-info btn btn-sm" :title="$t('form.result_evaluation')" @click="showResult()"><i class="fa fa-star" style="color:#ffffff;"></i> {{$t('form.result_evaluation')}} </button>
                 <button v-if="(this.step_approve ===null && this.is_document==='PCAR' && this.is_exist_monitor===1 && this.is_monitor===0 )|| (this.step_approve ===4 && this.is_document==='PCAR' && this.is_exist_monitor===1 && this.is_monitor===0 && this.is_status!=2) || (this.step_approve ===4 && this.is_document==='PCAR' && this.is_exist_monitor===1 && this.is_monitor===1 && this.is_status===-2)" class="btn-info btn btn-sm" :title="$t('form.result_evaluation')" @click="showResult()"><i class="fa fa-star" style="color:#ffffff;"></i> {{$t('form.result_evaluation')}} </button>
            </div>
            <div  class="card-footer" v-if="this.assign &&
             (this.object.proposer.id != this.user_id) &&
              ((this.step_approve===1 && 
              this.is_document==='SCAR' &&
               this.is_step1_approve===1) ||
                ((this.step_approve===null &&
                 this.is_document==='SCAR' &&
                  this.is_exist_monitor===0 &&
                   this.is_step1_approve===1) ||
                    (this.step_approve===2 &&
                     this.is_document==='SCAR' &&
                      this.is_step1_approve===1)))">
                 <button class="btn btn-warning btn-sm" :title="$t('form.causes')" @click="showCause()" style="color:#ffffff; background-color: #fd7e14;border-color: #fd7e14;"><i class="fa fa-comments" style="color:#ffffff;"></i> {{$t('form.causes_and_measures')}} </button>
            </div>
            <div  class="card-footer" v-if="this.assign && (this.object.proposer.id != this.user_id) && ((this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===0) || (this.step_approve===3 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===0 && this.is_exist_cause===1) || (this.step_approve===4 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_cause===1 && this.is_exist_cause===1 && this.is_exist_monitor===0 && this.is_step3_approve===0) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_cause_measure===1 && this.is_status!=-2) || (this.step_approve===null && this.is_process===1 && this.is_document==='PCAR' && this.is_exist_cause===1 && this.is_cause_measure===1 && this.is_monitor===0 && this.is_status ===-2) || (this.is_status===-2 && this.is_cause_measure===0 && this.is_document==='PCAR' && this.is_step2_approve===1) || (this.is_document==='PCAR' && this.is_step===3 && this.is_cause===0 && this.is_status ===-2))">
                 <button class="btn btn-warning btn-sm" :title="$t('form.causes')" @click="showCause()" style="color:#ffffff; background-color: #fd7e14;border-color: #fd7e14;"><i class="fa fa-comments" style="color:#ffffff;"></i> {{$t('form.causes_and_measures')}} </button>
            </div>
             <div class="card-footer" v-if="this.assign && (this.object.proposer.id != this.user_id) && ((this.step_approve===1 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step1_approve===1 ) || (this.step_approve===2 && this.is_document==='PCAR' && this.is_step1_approve===1) || (this.step_approve===2 && this.is_process===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===1 && this.is_document==='PCAR' && this.is_step1_approve===1)|| (this.step_approve===null && this.is_process===1 && this.is_status===-2 && this.is_document==='PCAR' && this.is_cause===0 && this.is_step===2 && this.is_step1_approve===1) || ( this.is_document==='PCAR' && this.step_approve===null && this.is_process===0 && this.is_status===-2 && this.is_cause===0 && this.is_step1_approve===1))">
               <button  class="btn btn-info btn-sm" :title="$t('form.fast_process')" @click="showFastProcess()"><i class="fa fa-spinner" style="color:#ffffff;"></i> {{$t('form.fast_process')}} </button> 
             </div>
    </div>
</template>

<script>
export default {
    data () {
    return {
        step_approve:[],
        step_user:[],
        items:this.items_props,
        selected:"",
        object_id:this.object.id,
        assign:false,
        is_status:"",
        is_document:'',
        fields:[
         {
            key: 'content',
            label:this.$t('form.content'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
          {
            key: 'date_due',
            label:this.$t('form.date'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
          {
            key: 'active',
            label:this.$t('form.status'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
           {
            key: 'user_create',
            label:this.$t('form.user'),
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
           {
            key: 'action',
            label:'',
            sortable: true,
            stickyColumn: true,
             class:"text-nowrap"
          },
        ],

        page_url_info_step_approve: "api/car/info_approve"


    }
  },
  watch: {
       object(){
           this.object_id = this.object.id;
           const array = [];
           for(let $i=0;$i<this.object.shareds.length;$i++){
            if(this.object.shareds[$i]['type']==4){
                array.push(this.object.shareds[$i]['assign']['id'])
            }
           }
           if(array.includes(Number(this.user_id))){
           this.assign = true;
           }
          // console.log(this.assign)
             this.get_info_approve();
       },
      items_props(){
          this.items =  this.items_props;
      }
  },
  props: {
      object: Object,
      items_props:Array,
      type:"",
      user_id: String,
  },
    created () {
        this.token = "Bearer " + window.Laravel.access_token;
       
  },
  methods: {
/*         notification_show(){
         this.object_id  = "";
         $('#createEventModal').modal('show');
      }, */
     showFastProcess(){

          this.$emit('fastprocessAction',null,'SHOW');
      },
 
      showCause(){

          this.$emit('causeAction',null,'SHOW');
      },
     showAssign(){
          this.$emit('assignAction',null,'SHOW');
      },
      showMonitor(){

          this.$emit('monitorAction',null,'SHOW');
      },
       showResult(){

          this.$emit('resultAction',null,'SHOW');
      },


    get_info_approve(){
         //console.log(this.object.id);
            var page_url = this.page_url_info_step_approve +"/" +this.object.id;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.step_approve = res.data;
                    this.step_user = res.user;
                    this.is_evaluation = res.is_evaluation;
                    this.is_document =  res.is_document;
                    this.is_cause = res.is_cause;
                    this.is_cause_measure = res.is_cause_measure;
                    this.is_process = res.is_process;
                    this.is_monitor = res.is_monitor;
                    this.is_exist_cause = res.is_exist_cause;
                    this.is_exist_monitor = res.is_exist_monitor;
                    this.is_status = res.is_status;
                    this.is_step1_approve =  res.is_step1_approve;
                    this.is_step2_approve = res.is_step2_approve;
                    this.is_step3_approve = res.is_step3_approve;
                    this.is_step = res.is_step;
                   
                    //console.log(this.is_exist_monitor);
                })
                .catch(err => console.log(err));
        },
  },

}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
