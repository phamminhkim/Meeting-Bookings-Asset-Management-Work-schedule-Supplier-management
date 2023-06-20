<template>
<div>
     <table class="table table-bordered table-sm table-responsive-sm ">
        <thead>
            <tr>
                <th  scope="col" rowspan="2" style="vertical-align:middle;text-align:center;">STT</th>
                <th  scope="col" rowspan="2" style="vertical-align:middle">Sản phẩm</th>
                <th  scope="col" rowspan="2" style="vertical-align:middle">ĐVT</th>
                <th  scope="col" rowspan="2" style="vertical-align:middle">Số lượng</th>
                <th :colspan="vendor_curr_price_count" v-if="vendor_curr_price_count>0" style="vertical-align:middle;text-align:center;">Đơn giá hiện tại</th>
                <th :colspan="vendor_count" style="text-align:center;">Đơn giá mới</th>
                <th rowspan="2" style="vertical-align:middle;text-align:center;">Chênh lệch</th>

            </tr>
            <tr >

                <th  style="vertical-align:middle" v-for="(vendor) in listVendorCurrPrice" v-bind:key="vendor">{{vendor}}</th>
                <th style="vertical-align:middle" v-for="(vendor,index) in listVendor" v-bind:key="index">{{vendor}}</th>


            </tr>
        </thead>
        <tbody>
            <tr v-for="(product,index) in praprequest.products" v-bind:key="index">
                <td style="text-align:center;">{{index + 1}}</td>
                <td> <strong>{{product.name}}</strong>
                       <div class="d-flex justify-content-between">
                            <ul  class="list-unstyled">
                                <li v-for="(spec,index) in product.specs" v-bind:key="index">
                                    - {{spec.name}}
                                    <span v-for="(other,other_index) in spec.others" v-bind:key="other_index">
                                        <span v-if="other.vendor_id == praprequest.vendor_id">:{{other.value}}</span>
                                    </span>

                                </li>

                            </ul>
                        </div>
                    </td>
                <td>{{product.unit}}   </td>
                <td>
                    <span v-for="(detail,index_detail) in product.details" v-bind:key ="index_detail">
                         <br v-if="index_detail >= 1">
                        <span v-if="detail.quantity && detail.vendor_id == praprequest.vendor_id" style="float:right;vertical-align:middle">{{detail.quantity.toLocaleString(locale_format)}}</span>
                    </span>
                </td>
<!--
                  <td  >

                    <span v-for="(detail,index_detail) in product.details" v-bind:key ="index_detail">
                        <br v-if="index_detail >= 1">
                        <span v-if="detail.price_old !=0" style="float:right" class="text-md-right">{{detail.price_old.toLocaleString(locale_format)}}</span>
                    </span>
                </td> -->
                 <td v-for="(vendor,index) in listVendorCurrPrice" v-bind:key="index">

                     <!-- <span v-for="(detail,index_detail) in product.details" v-bind:key ="index_detail">
                          <br v-if="index_detail >= 1">
                        <span v-if="detail.price_old  && vendor == detail.vendor_display " style="float:right" class="text-md-right">{{detail.price_old.toLocaleString(locale_format)}}</span>
                    </span> -->
                     <span v-html="showPriceHtmlOld(vendor,product)"></span>
                </td>

                <td v-for="(vendor) in listVendor" v-bind:key="vendor">

                     <span v-html="showPriceHtml(vendor,product)"></span>
                </td>


                <td  style="vertical-align:middle;text-align:center;"> <span>{{percentDiff(product)}}</span></td>

            </tr>
             <tr v-html="showTong()">

             </tr>

        </tbody>
    </table>

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
         locale_format:"de-DE",
         newrow : '',

    };
    },
  methods:{
       showPriceHtmlOld(vendor,product){
            let html = "";
            let count = 0;
            product.details.forEach(detail => {

                if(detail.price_old  && vendor == detail.vendor_display){
                    if(count >= 1){
                        html += '<br>';
                    }
                    html += '<span style="float:right;" class="text-md-right">'+ detail.price_old.toLocaleString(this.locale_format, { minimumFractionDigits: 4 }) + '</span>';
                    count ++;
                }
            });
          return html;
      },
      showPriceHtml(vendor,product){
            let html = "";
            let count = 0;
            product.details.forEach(detail => {

                if(detail.price  && vendor == detail.vendor_display){
                    if(count >= 1){
                        html += '<br>';
                    }
                    html += '<span style="float:right;" class="text-md-right">'+ detail.price.toLocaleString(this.locale_format, { minimumFractionDigits: 4 }) + '</span>';
                    count ++;
                }
            });
          return html;
      },
      showTong(){
          let colspan =  this.vendor_curr_price_count + 4;
         let table   = '<tr >';
             table  +=     '<td  style="vertical-align:middle;text-align:center"  colspan='+colspan+'><strong>TỔNG TIỀN</strong></td>';
                        this.listVendor.forEach(vendor => {
                        table +='<td  style="vertical-align:middle;text-align:right" >'+this.sumAmount(vendor)+'</td>'
                        });

               table += '</tr>';
               return table;
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
                   if (!vendors.includes(detail.vendor_display) ) {
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
          //  console.log(vendors);
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
