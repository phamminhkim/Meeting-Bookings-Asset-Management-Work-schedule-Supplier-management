<template>
<div class="card">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  {{$t('form.share_list')}}
                </h3>
              </div>
              <div class="card-body">

                 <shared-list v-bind:object="this.object" v-on:sharedAction="sharedAction" :items_props="this.object.shareds"  :type="'CARS'"></shared-list>
              </div>
            <shared-dialog :doc_id="object_id" v-on:fromShared="fromShared"  module="CARS"></shared-dialog>
            </div>
</template>
<script>
export default {
watch: {
      object_id(){
      this.object_id = this.object_id;
    },
  },
computed:{

    },
props: {
      object_id:"",
      module:"",
      object:Object
  },
components: {

    },
 data() {
   return {
   };
 },
  methods: {
     fromShared(obj){
         this.object.shareds = obj;
     },
    sharedAction(obj,type){
            var index ="";
        switch (type) {


           case 'DELETE':
                 //gọi ham delete
                 index = this.object.shareds.findIndex(data=>data.id == obj.id);
               this.object.shareds.splice(index,1);

            break;

          default:
            break;
        }
      },
  },
  created () {
        this.token = "Bearer " + window.Laravel.access_token;

  },
}
</script>

<style scoped>
  .hidden_header{
      display: none;
  }
</style>
