<template>
    <nav>
        <ul class="pagination">
             <li class="paginate_button page-item previous" v-if="pagination.current_page > 1" >
                 <a class="page-link " v-on:click.prevent="changePage(pagination.current_page -1 )" href="#"><i class="ace-icon fa fa-angle-double-left"></i></a>
            </li>
            <li class="paginate_button page-item" v-for="page in pageNumber" :key="page" :class="{'active': page == pagination.current_page}">
               <a href="#" class="page-link" v-on:click.prevent="changePage(page)">{{ page }}</a>
                </li>
            
            <li class="paginate_button page-item next" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)" href="#"><i class="ace-icon fa fa-angle-double-right"></i></a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
  methods: {
      changePage:function(page){
          this.pagination.current_page = page;
      }
  },
  props: {
      pagination:{
          type:Object,
          required:true
      },
      offset:{
          type:Number,
          default:2
      }
  },
  computed:{
      pageNumber:function(){

          if(!this.pagination.to){
              return []
            };
          var from = this.pagination.current_page - this.offset;
          
          if(from < 1){
            from = 1;
          }
         var to = from + (this.offset * 2);
         if(to > this.pagination.last_page){
             to = this.pagination.last_page;
         }
         var pagesArray = [];
        //  for(from = 1;from <= to; from++){
        //      pagesArray.push(from);
        //  }
          while (from <= to) {
                pagesArray.push(from);
                from++;
            }
         return pagesArray;

      }
      
  }

}
</script>

<style>

</style>