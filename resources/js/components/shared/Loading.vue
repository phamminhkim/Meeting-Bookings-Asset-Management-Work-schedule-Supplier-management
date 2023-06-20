<template>
     <!-- Loading (remove the following to stop the loading)-->
       <div class="center overlay" style="" v-if="loading">
        <i
            class="fa fa-spinner fa-spin fa-4x fa-fw gray center"
        ></i>
        <span class="sr-only">Loading...</span>
         <!-- <b-spinner variant="primary" label="Spinning"></b-spinner> -->
    </div>
                <!-- end loading -->
</template>

<script>
export default {
  props: {
      loading:false
  },

}
</script>

<style>

</style>