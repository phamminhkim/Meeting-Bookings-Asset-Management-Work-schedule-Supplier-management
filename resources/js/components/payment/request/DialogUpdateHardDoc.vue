<template>
<div class="modal fade" id="UpdateDateModal" tabindex="-1" role="dialog" aria-labelledby="UpdateDateModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action=""  @submit.prevent="UpdateDate()" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="UpdateDateModal"> {{$t('form.date_receive_hard_doc')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
               <!-- <div class="form-group">
                 <label for=""> {{$t('form.content')}}</label> <small   class="text-red">( * )</small>
                 <input type="text" required v-model="reminder.content" class="form-control" maxlength="255">
              </div> -->
              <div class="form-group">
                 <label for="date_due">{{$t('form.date')}}</label>  <small   class="text-red">( * )</small>
                 <b-form-datepicker :required="true"  
                  v-bind:class="hasError('date_due')?'is-invalid':''"   
                   @click="clearError($event)"
                 aria-required="true" id="example-datepicker" 
                 v-model="obj.date"   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"   >

                 </b-form-datepicker>
                    <span v-if="hasError('date_due')" class="invalid-feedback" role="alert">
                      <strong>{{getError('date_due')}}</strong>
                                      
                     </span>
              </div>
  
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{$t('form.close')}}</button>
        <button type="submit" class="btn btn-primary">{{$t('form.save')}}</button>
      </div>
    </div>
      </form>
  </div>
</div>
    
</template>>

<script>
export default {

     props: {
        obj:Object,
        object_id:Array,

  },
  data () {
    return {
        data:{
            date:this.obj.date,
            id: "",
        },
        
        errors:{},
        loading: false,
        edit: false,
        token:"",
         
    }
  },
  methods: {
      UpdateDate(){
            
           this.data.date = this.obj.date;
           this.data.id = this.obj.id;
          this.$emit("fromUpdateHardDoc",this.data);
          $('#UpdateDateModal').modal('hide');
          // this.reset();
      },
      reset(){
        //    this.data.id = "";
        //    this.date.date="";
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