$(function () {
    //định dạng Ngày: dd/mm/yyyy
    //sử dụng :  <input type="date"  class="form-control" data-date="2020-08-29"    data-date-format="DD/MM/YYYY"  v-model="contract.sign_date">
    $("input[type='date']").on("change", function() {
        
        if(this.value){
          this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format( this.getAttribute("data-date-format") )
          );
        }else{
          $(this).attr('data-date', '');
        }
     
        
    }).trigger("change")
   
  })