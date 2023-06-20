<template>
<div>
     <table class="table table-bordered table-sm table-responsive-sm">
        <thead>
            <tr>
                <th  scope="col"  style="vertical-align:middle;text-align:center;">STT</th>
                <th  scope="col"   style="vertical-align:middle">Sản phẩm</th>
                <th  scope="col"  style="vertical-align:middle">ĐVT</th>
                <th  scope="col"   style="vertical-align:middle">Số lượng</th>
                <th  style="text-align:center;">Đơn giá mới</th>
                <th  scope="col"   style="vertical-align:middle">Đơn giá hiện tại</th>

            </tr>

        </thead>
        <tbody>
            <tr v-for="(product,index) in praprequest.products" v-bind:key="index">
                <td  style="text-align:center;">{{index + 1}} </td>
                <td> <strong>{{product.name}}</strong>  <span v-if="product.code_sap">({{product.code_sap}})</span>
                   <span v-html="getOtherInfo(product)"></span>
                 <!-- <span v-for="(spec,spec_index) in product.specs" v-bind:key="spec_index">
                     <span v-for="(vendor,vendor_index) in listVendor" v-bind:key="vendor_index">
                         <span v-for="(other,other_index) in spec.others" v-bind:key="other_index">
                             <span v></span>
                         </span>
                     </span>

                 </span> -->
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
                        <span v-if="detail.quantity != null" style="float:right" >{{detail.quantity.toLocaleString(locale_format)}}</span>
                    </span>
                </td>
                <td>
                    <span v-for="(detail,index_detail) in product.details" v-bind:key ="index_detail">
                        <br v-if="index_detail >= 1">
                        <span v-if="detail.price_old !='0' && detail.price > detail.price_old"  style="float:left" >
                            <i title="Tăng" class="fas fa-arrow-up text-gray"></i>
                    </span>

                    <span v-if="detail.price_old !='0' && detail.price < detail.price_old"> <i class="fas fa-arrow-down text-gray"></i></span>
                        <span v-if="detail.price != null" style="float:right" class="text-md-right " >
                        {{detail.price.toLocaleString(locale_format, { minimumFractionDigits: 4 })}}
                        </span>
                    </span>
                </td>
                <td>

                    <span v-for="(detail,index_detail) in product.details" v-bind:key ="index_detail">
                        <br v-if="index_detail >= 1">
                        <span v-if="detail.price_old != null && detail.price_old != '0'"  style="float:right" class="text-md-right">{{detail.price_old.toLocaleString(locale_format, { minimumFractionDigits: 4 })}}</span>
                    </span>
                </td>
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
         locale_format: "de-DE",
    };
    },
  methods:{
       showTong(){
          let colspan =    4;
         let table   = '<tr >';
             table  +=     '<td  style="vertical-align:middle;text-align:center"  colspan='+colspan+'><strong>TỔNG TIỀN</strong></td>';

             table +='<td  style="vertical-align:middle;text-align:right" >'+this.sumAmount()+'</td>'

               table += '</tr>';
               return table;
      },
      sumAmount(){
           var amount = 0;
          this.praprequest.products.forEach(product => {

                  product.details.forEach(detail => {
                  if (detail.price > 0 && detail.quantity > 0 ) {
                      amount += detail.price * detail.quantity;
                  }
              });


          });
          return amount == 0 ?"":amount.toLocaleString(this.locale_format, { minimumFractionDigits: 4 });
      },
       getOtherInfo(product){
             let info = "";
             console.log(product);
             if (this.listVendor && this.listVendor.length > 0) {
                    product.specs.forEach(spec => {

                       info     +=

                                +'<p>'+spec.name+'';
                                 this.listVendor.forEach(vendor => {
                                     flag = '';
                                    spec.others.forEach(other => {

                                        if (vendor == other.vendor_display && other.value) {
                                            info +='<span>'+ other.value+'</span>';
                                            flag = 'X';
                                        }
                                    });
                                    // if (flag == '') {
                                    //      table +='<td></td>';
                                    // }
                                });
                                +'</p>';
                 });
             }

                return info;
       },
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

  },
  computed:{
      vendor_count(){
         var vendors = new Array;
          var vendor_key = "";
          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {
                  vendor_key = detail.vendor_id  +"@"+ detail.vendor_display;
                   if (!vendors.includes(vendor_key)) {
                    vendors.push(vendor_key);
              }
              });

          });

          return vendors.length;

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
