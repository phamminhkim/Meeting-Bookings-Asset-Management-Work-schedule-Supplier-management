<template>
  <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">                 
                <div>  <h3 class="card-title"> <i class="fas fa-anchor"></i> Thông tin qui trình</h3></div>
                  <span  v-if="document.status == 3"
                        class="badge badge-primary badge-success mr-1 "
                        :title="$t('form.complete')" >
                        <i class="fas fa-check-circle"></i>
                    </span>
            </div>
         
         
        </div>
        <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                         
                       <div class="form-group row">
                            <span class="col-form-label-sm col-sm-5">Tên qui trình:</span>
                            <div class="col-sm-7">
                                <label v-if="document.wlworkflow" class="col-form-label-sm">{{document.wlworkflow.name}}</label>
                            </div>
                        </div>  
                         <div class="form-group row">
                            <span class="col-form-label-sm col-sm-5">Giai đoạn hiện tại:</span>
                            <div class="col-sm-7">
                                <label class="col-form-label-sm" 
                                v-if="document.wlworkflow && document.wlworkflow.current_phase"
                                >{{document.wlworkflow.current_phase.name}}</label>
                            </div>
                        </div>  
               
                
                    </div>
                </div>
        </div>
  </div>
</template>

<script>
export default {
    props:{
        document:Object, 
    },
}
</script>

<style lang="scss" scoped>
    .form-group {
        margin-bottom: 0;
    }
.col-form-label-sm {
    padding-top: 0;// calc(0.25rem + 1px);  
      padding-bottom:0;// calc(0.25rem + 1px); 
    font-size: 0.875rem;
    line-height: 1.5;
}
</style>