<template>
    <div>
        <form action="">
            <div class="card">
                <div class="card-header">
                    Kế hoạch thanh toán hợp đồng
                </div>
                <div class="card-body">
                    <div class="form-group row ">
                        <label for="" class="col-sm-1">Công ty</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="" id="">
                                <option value="1">TLMN</option>
                                <option value="2">TLMB</option>
                                <option value="3">TLMT</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-1">Từ ngày</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" />
                        </div>
                        <label for="" class="col-sm-1">Đến ngày</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" />
                        </div>
                         <div class="col-sm-2">
                          <button
                                type="button"
                                name=""
                                id=""
                                class="btn btn-primary "
                            ><i class="fas fa-money-check"></i>
                                   Tìm
                            </button>
                        </div>
                    </div>
 
                    <div class="form-row">
                        <div class="form-group col-md-1">
                           
                        </div>
                        <div class="form-group col-md-5">
                            <button
                                type="button"
                                name=""
                                id=""
                                class="btn btn-primary "
                            ><i class="fas fa-money-check"></i>
                                   Tìm
                            </button>
                        </div>
                        <div class="form-group col-md-2">
                            
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted"></div>
            </div>
        </form>
    </div>
</template>

<script>
export default {};
</script>

<style></style>
