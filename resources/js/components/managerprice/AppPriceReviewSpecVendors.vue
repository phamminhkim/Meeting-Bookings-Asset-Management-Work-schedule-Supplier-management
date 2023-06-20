<template>
<div>

    <div v-html="dataDisplay()"></div>

</div>
</template>

<script>
export default {
  props: {
        data: Object,

    },
data() {
    return {
         praprequest: this.data,
         locale_format: "de-DE",
    };
    },
  methods:{
      dataDisplay(){
          var table = "";
           var flag = '';
          var index = 0;
          var style = 'style="background-color:#F4F4F4"';

              table = '<table class="table table-bordered table-sm table-responsive-sm ">'
              + '<thead>'
              +  '<tr '+style+'>'
              +       '<th  style="vertical-align:middle;text-align:center">STT</th>'
              +       '<th  style="vertical-align:middle;text-align:center">Nội dung/NCC</th>';
                        this.listVendor.forEach(vendor => {
                        table +='<th  style="vertical-align:middle;text-align:center" >'+vendor+'</th>'
                        });

               table += '</tr>'
                    '</thead>';
               table += '<tbody>';
               this.praprequest.products.forEach(product => {
                index ++;
                //Tên sản phẩm/ dịch vụ và thương hiệu
               table  +='<tr '+style+' >'
                        +'<td style="vertical-align:middle;text-align:center">'+index+'</td>'
                        +'<td >'+product.name+'/HIỆU</td>';
                        this.listVendor.forEach(vendor => {
                            flag = '';
                            product.details.forEach(detail => {

                                if (vendor == detail.vendor_display) {
                                    table +='<td style="vertical-align:middle;text-align:center">';
                                      if(detail.brand !=null){
                                           table += detail.brand;
                                      }

                                      table +='</td>';
                                    flag = 'X';
                                }
                            });
                            if (flag == '') {
                                 table +='<td ></td>';
                            }
                        });

                        +'</tr>';

                 //Giá sản phẩm - thành tiền
                          let  amount = 0;
                           let quantity = 0;
                           let count = 0;
                table   +='<tr >'
                        +'<td ></td>'
                        +'<td>'+this.vatText(product)+'</td>';
                        this.listVendor.forEach(vendor => {

                            flag = '';
                            amount = 0;
                            quantity = 0;
                            count = 0;
                            table +='<td style="vertical-align:middle;text-align:center">';
                            product.details.forEach(detail => {

                                if ( vendor == detail.vendor_display) {
                                    count ++;
                                    quantity = (detail.quantity == null || detail.quantity == 0)?1:detail.quantity;
                                    amount = detail.price * quantity;
                                    if (count > 1) {
                                        table +='<br>';
                                    }
                                    table +=''+ amount.toLocaleString(this.locale_format, { minimumFractionDigits: 4 });

                                    flag = 'X';
                                }else{

                                }
                            });
                            table +='</td>';
                            //   if (flag == '') {
                            //      table +='<td></td>';
                            // }
                        });

                        +'</tr>';
                //Thông số sản phẩm
                 product.specs.forEach(spec => {

                       table    +='<tr>'
                                +'<td></td>'
                                +'<td>'+spec.name+'</td>';
                                 this.listVendor.forEach(vendor => {
                                     flag = '';
                                    spec.others.forEach(other => {

                                        if (vendor == other.vendor_display && other.value) {
                                            table +='<td style="vertical-align:middle;text-align:center">'+ other.value+'</td>';
                                            flag = 'X';
                                        }
                                    });
                                    if (flag == '') {
                                         table +='<td></td>';
                                    }
                                });
                                +'</tr>';
                 });
          });
             //sumary
              table   +='<tr >'
               +       '<td  style="vertical-align:middle;text-align:center"></td>'
               +       '<td  style="vertical-align:middle;text-align:center"> <strong>TỔNG TIỀN</strong> </td>';
                        this.listVendor.forEach(vendor => {
                        table +='<td  style="vertical-align:middle;text-align:center" >'+this.sumAmount(vendor)+'</td>'
                        });

               table += '</tr>';
             table += '</tbody>';
          return table;
      },
      vatText(item){
        var is_vat = '';
                this.praprequest.products.forEach(product => {


                    if (item == product) {

                        product.details.forEach(detail => {
                        if (detail.vendor_id == this.praprequest.vendor_id ) {
                           is_vat = detail.is_vat;
                        }
                    });
                    }

           });
            var vat = ( is_vat != '-2' && is_vat != '-1' )?(is_vat +"%"):"";
          return  is_vat == '-2'?"Giá có VAT":("Giá chưa VAT " +vat);
      },
      sumAmount(vendor){
           var amount = 0;
          this.praprequest.products.forEach(product => {

                  product.details.forEach(detail => {
                  if (detail.price > 0 && detail.vendor_display == vendor && detail.quantity > 0 ) {
                      amount += detail.price * detail.quantity;
                  }
              });


          });
          return amount == 0 ?"":amount.toLocaleString(this.locale_format, { minimumFractionDigits: 4 });;
      },
       //Lấy giá hiện tại đầu tiên của sản phẩm > 0
      currentPrice(item){
          var price_old = 0;
          this.praprequest.products.forEach(product => {


              if (item == product) {

                  product.details.forEach(detail => {
                  if (detail.price_old > 0) {
                      price_old = detail.price_old;
                  }
              });
              }

          });
          return price_old;
      },
      //Tính toán chênh lệch của giá của nhà cung cấp được chọn so với giá cũ ( hiện tai)
      percentDiff(item){
          var diff = 0;
          var price_old = this.currentPrice(item);
          if (price_old > 0 ) {
               item.details.forEach(detail => {
                   if (detail.vendor_id ==  this.praprequest.vendor_id) {
                       diff =  ( detail.price - price_old ) * 100 / price_old;
                   }
               });
          }
          return diff > 0 ?(diff.toLocaleString(this.locale_format) + "%"):"";
      },
  },
  computed:{

      listVendor(){
          var vendors = new Array;

          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {

                   if (!vendors.includes(detail.vendor_display)) {
                    vendors.push(detail.vendor_display);
              }
              });

          });

          return vendors;
        },
      listVendorCurrPrice(){
          var vendors = new Array;

          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {
                   if (!vendors.includes(detail.vendor_display) && detail.price_old > 0) {
                    vendors.push(detail.vendor_display);
              }
              });

          });
           // console.log(vendors);
          return vendors;
        },
      vendor_curr_price_count(){
          var list = this.listVendorCurrPrice;
          return list.length;
      },
       vendor_count(){
          var list = this.listVendor;
          return list.length;
      }
  },
  watch: {
      data(){
         this.praprequest = this.data;
      },

    },
}
</script>

<style>

</style>
