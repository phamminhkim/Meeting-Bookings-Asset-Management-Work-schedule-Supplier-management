<template>
  <div>
        <date-range-picker  minDate="01/01/2022" maxDate="01/12/2022" 
        showWeekNumbers="true" 
        timePicker="true" 
        autoApply="true" 
        timePicker24Hour="true"
        showDropdowns="true"
        v-model="dateRange">
         <template v-slot:input="picker" style="min-width: 350px;">
            {{ picker.startDate | formatDate }} - {{ picker.endDate | formatDate }}
        </template></date-range-picker>
  </div>
</template>

<script>
 import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
// Vue.component('DateRangePicker', DateRangePicker);
export default {
  components:{
    DateRangePicker
  },
   data() {

    return {
       
       dateRange:{ startDate:null, endDate:null },
    }
    },
 
  
}
</script>

<style>

</style>