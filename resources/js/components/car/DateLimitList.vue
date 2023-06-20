<template>
    <div class="card">
        <div  class="card-header" style="background-color: #E5E5E5;">
            <table id="datelimitRef" ref="datelimitRef">
            <tr>
            <td><h6 class="card-title"><i class="fa fa-calendar"></i> {{$t('form.date_to_limit')}}: </h6></td>
            <td>
             {{this.object.date_to_limit | formatDB}}
            </td>
            <td v-if="this.object.status!=2">
             <a href="#" :title="$t('form.edit')" class="btn btn-sm"   @click.prevent="edit(object_id)"> <i class="fas fa-edit"></i> </a>           
            </td>
            </tr>
            </table>
             <div>
        </div>
          
        </div>
       
         <!-- <create-event-dialog :object_id="object_id"  :id="selected" :module="type"></create-event-dialog> -->
    </div>

</template>

<script>
export default {
    data () {
    return {
        items:this.items_props,
        selected:"",
        object_id:this.object.id,
        fields:[
          {
            key: 'date_to_limit',
            label:this.$t('form.date_to_limit'),
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
      


    }
  },
  watch: {
      items_props(){
          this.items =  this.items_props;

      }
  },
  props: {
        object: Object,
      items_props:Array,
      type:""
  },
  methods: {
      edit(obj){
          this.$emit('datelimitAction',obj,'EDIT');
      },
  },
  created () {
        this.token = "Bearer " + window.Laravel.access_token;
       // console.log(this.object.date_to_limit);
  },


}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
