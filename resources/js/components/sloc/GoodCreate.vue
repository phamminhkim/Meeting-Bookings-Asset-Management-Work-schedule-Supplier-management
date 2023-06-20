<template>
    <div>
        <form id="AddDetails" @submit.prevent="addGood" @keydown="clearAllError">
            <div class="content-header ">
                <div class="container-fluid ml-0">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item">
                                    <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a
                                            href="/sloc/gooddocus">Nhập xuất kho</a> </h5>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span v-if="id != null">{{ $t('form.edit') }}</span>
                                    <span v-else>{{ $t('form.create') }}</span>
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <div class="float-sm-right " style="margin-top:-5px; ">
                                <a href="/sloc/gooddocus" class="btn btn-default ">Huỷ</a>
                                <button type="submit" :disabled="loading" value="Lưu" class="btn btn-primary"> Lưu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-if="hasAnyError > 0">
                        <div class="alert alert-warning">
                            <button type="button" class="close" @click.prevent="clearAllError()">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <ul>
                                <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card  card-default">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="thongtinchung" role="tabpanel"
                                    aria-labelledby="thongtinchung-tab">
                                    <div class="row  ">
                                        <div class="col-md-6">
                                              <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Số chứng Từ</span>
                                                    <small class="text-red"></small>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="serial_num"
                                                        v-model="details.serial_num"
                                                        readonly
                                                       
                                                        name="serial_num" placeholder="" />
                                                  
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Loại phiếu</span> 
                                                    <small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8">
                                                    <div v-if="id != null">
                                                        <div v-if="details.type==0">
                                                            <input class="form-control form-control-sm" value="Nhập" readonly />

                                                        </div>
                                                        <div v-if="details.type==1">
                                                            <input class="form-control form-control-sm" value="Xuất" readonly />

                                                        </div>
                                                   
                                                       
                                                </div>
                                                <div v-else> 
                                                    <select id="type" class="form-control form-control-sm"
                                                        v-model="details.type"
                                                        >
                                                        <option selected value="0" >Nhập </option>
                                                        <option value="1"> Xuất </option>
                                                    </select>
                                                </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Kho</span>
                                                    <small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8"> 
                                                    <treeselect placeholder="Kho hàng"
                                                     :disable-branch-nodes="true"
                                                     :show-count="true"
                                                    :multiple="false" v-model="details.sloc_id"
                                                    :options="tree_slocs" required></treeselect>                                               
                                                   
                                                </div>
                                            </div>
                                          
                                           

                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span v-if="details.type == 0">Người giao hàng</span>
                                                    <span v-if="details.type == 1">Người nhận hàng</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="shipper_name"
                                                        v-model="details.shipper_name"
                                                        v-bind:class="hasError('shipper_name') ? 'is-invalid' : ''"
                                                        name="shipper_name" placeholder=""/>
                                                    <span v-if="hasError('shipper_name')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('shipper_name') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                         

                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Lý do nhập</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input v-model="details.reason" type="text" class="form-control form-control-sm"
                                                        id="reason" name="reason" placeholder=""  />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    <div class="row mt-1">
                            <div class="col-md-12">
                                <b-card no-body  >
                                    <b-tabs card small active-nav-item-class="font-weight-bold text-uppercase text-black"  >
                                    <b-tab title="Công cụ dụng cụ" >
                                        <div class="form-group row">
                             
                                            <button type="button" v-on:click="showGood()" class="btn  btn-outline-info btn-sm ml-1"   ><strong></strong>{{$t('Nhập công cụ dụng cụ')}}  <i class="fas fa-plus"></i>  </button>
                                        </div>
                                        <b-table small hover headVariant :items="details.gooddocus_details"
                                        :fields="fields_detail" id="table">
                                        <template v-slot:cell(#)=data>
                                            {{ data.index + 1 }}
                                        </template>
                                        <template #head(#)=data>
                                            <span class="text-success">{{ data.label }}</span>
                                        </template>
                                        <template #head(objectable_id)=data>
                                            <span class="text-success">{{ data.label }}</span>
                                        </template>
                                
                                        <template #head(unit_price)=data>
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #head(quantity)=data>
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #head(money)=data>
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                            
                                        </template>
                                        <template #head(action)="data" >
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                        </template>
                                      
                                        <template #head(end_date)=data>
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #cell(money)=data>
                                            <div class="text-center">
                                                <span><strong> {{data.item.quantity * data.item.unit_price | numFormat('0,0') }}</strong></span>
                                            </div>
                                        </template>
                                        <template #head(amount)=data>
                                            <div class="text-center">
                                                <span class="text-success" >{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template  #cell(amount)="data">

                                            <div class="text-center">
                                                <div v-for="qty in asset_tools" :key="qty.id">
                                                    <div v-if="qty.id==data.item.objectable_id">  
                                                <span v-if="qty.quantity==null">0</span>
                                                <span v-else>{{qty.quantity }}</span>
                                            </div>
                                            </div>
                                                <!-- <div v-for="qty in asset_tools" :key="qty.id">
                                                <div v-if="qty.quantity !=null">
                                                <div v-if="details.type == 0">      
                                                <span v-if="qty.id==data.item.objectable_id"><strong> {{qty.quantity + data.item.quantity | numFormat('0,0')}}</strong></span>
                                                </div>
                                                <div v-if="details.type == 1">      
                                                <span v-if="qty.id==data.item.objectable_id"><strong> {{qty.quantity - data.item.quantity | numFormat('0,0')}}</strong></span>
                                                </div>
                                            </div>
                                            </div> -->
                                            </div>
                                        </template>
                                        <template #cell(objectable_id)=data>
                                            <i class="fas fa-layer-group"></i>  <span>{{ gettoolsName(data.value) }}</span>

                                           </template>


                                        <template #cell(quantity)="data">
                                            <div class="text-center">
                                            <span>{{ data.item.quantity}} </span>
                                        </div>
                                                    </template>
                                        <template #cell(unit_price)="data">
                                            <div class="text-center">
                                            <span>{{ data.item.unit_price | numFormat('0,0')}} </span> </div>
                                                    </template>
                                               
                                             <template #cell(end_date)="data">
                                            <div class="text-center">
                                                <div class="text-center">
                                            <span>{{ data.item.end_date }} </span> </div>
                                            </div>
                                        </template>
                                        <template #cell(action)=data>
                                            <div class="row">
                                                <!-- <div class="col-sm-6">
                                                    <div class="text-right">                                             
                                               <button @click.prevent="editGooddetails(data.item,data.index)" class="btn btn-primary btn-sm">Sửa</button>
                                           </div>
                                                </div> -->
                                                <!-- <div class="col-sm-12"> -->
                                                    <div class="text-center">                                             
                                               <button @click.prevent="DeleteItems(data.item,data.index)" class="btn btn-danger btn-sm">Xóa</button>
                                           </div>
                                            </div>
                                            <!-- </div>                                                                                  -->
                                        </template>
                                    </b-table>
                                      
                                    </b-tab>
                                    </b-tabs>
                                </b-card>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="AddGood" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" @submit.prevent.enter="addGoodDetails()" @keydown="clearAllError">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span v-if="!edit_voucher" > Nhập công cụ dụng cụ</span>
                                <span v-if="edit_voucher" > Cập nhật công cụ dụng cụ</span>                            
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">           
                            </div>
                            <div class="form-group">
                                <label for="GoodSerial_Num">Công cụ dụng cụ</label>                            
                                <treeselect placeholder="Tất cả"
                                                     :disable-branch-nodes="true"
                                                     :show-count="true"
                                                    :multiple="false" v-model="gooddetails.objectable_id"
                                                    :options="tree_tools" required></treeselect>
                            </div>
                            <div class="form-group">
                                <label for="GoodShipperName">Số lượng</label>                        
                                <input  v-model="gooddetails.quantity" type="text" class="form-control" id="quantity"
                                    name="quantity" placeholder="quantity" required />
                            </div>
                            <div class="form-group">
                                <label for="GoodUserID">Đơn giá</label>            
                                <vue-numeric :separator="separator"  v-model="gooddetails.unit_price"   class="form-control" 
                                    name="unit_price" placeholder="unit_price" required >
                                </vue-numeric>                              
                            </div>                          
                            <div class="form-group">
                                <label for="GoodReason">Ngày đến hạn</label>
                                <input type="datetime-local" class="form-control form-control-sm" id="reason"
                                    v-model="gooddetails.end_date" data-date="" data-date-format="DD/MM/YYYY"
                                    v-bind:class="hasError('reason') ? 'is-invalid' : ''" name="reason" placeholder=""
                                     />
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                {{ $t('form.back') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $t('form.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal dialog -->
    </div>
</template>
<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
    props: {
        id: String,
        parent: String,
    },
    components: {
        Treeselect,
    },
    data() {
        return {
            enabled_1: false,
            selectMode: 'single',
            selected: [],
            selectAll: false,
            huybo: false,
            counter: 0,
            countertool: 0,
            noidung: [{
                    key: "selected",
                    label: "SELECT",
                    
                },{
                    key: 'id',
                    label: 'ID',
                }, {
                    key: 'name',
                    label: 'Công cụ dụng cụ',
                }
                ],
           
            url_goodunits: "/api/sloc/goodunits",
            goodunits: [],
            filter: '',
            page_url_details: "/api/sloc/gooddocuss",
            fields_detail: [
                {
                    key: '#',
                    label: 'ID'
                },
                {
                    key: 'objectable_id',
                    label: 'Công cụ dụng cụ'
                }, {
                    key: 'quantity',
                    label: 'Số lượng'
                },
                {
                    key: 'unit_price',
                    label: 'Đơn giá'
                },
                {
                    key: 'money',
                    label: 'Thành tiền'
                }, {
                    key: 'amount',
                    label: 'Tồn kho'
                },
                {
                    key: 'end_date',
                    label: 'Ngày đến hạn'
                },
                {
                    key: 'action',
                    label: ''
                },
            ], 
            details: {
                id: "",
                sloc_id: null,
                serial_num: "",
                shipper_name: "",
                user_id: "",
                type: "0",
                reason: "",
                index: "",
                gooddocus: [],
                gooddocus_details: [],
                gooddocus_details_del: [],
                gooddocus_edit:[],
                goods_del: [],
            },        
            gooddetails: {
                id: "",
                good_id: "",
                name :'',
                type:'',
                goodunit_id: "",
                quantity: "",
                objectable_id: null,
                objectable_type: '',
                unit_price: "",
                amount: '' ,
                gooddocu_id: "",
                end_date: "",
                index: "",
            },
            asset_warehouses: [],

            page_url_slocs: "api/asset/warehouse",
            locale_format: 'de-DE',
            edit_payrequest: false,
            errors: {},
            loading: false,
            edit: false,
            token: "",
            url_asset_tools:  "api/asset/stock",
            url_asset_toolss:  "api/asset/all",
            url_tree_slocs:  "api/asset/wall",

            asset_tools: [],
            page_url_users: "api/user/all",
            page_url_detail: "/api/sloc/goods",
            edit_voucher: false,
            users: [],
            goods:[],
            separator:'.',
            users: [],
            edit: false,
            tree_tools: [],
            tree_slocs:[],
           
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.fetTools_Tree();
        this.getDetails();
        this.getUser();
        this.fetchGoodunits();
        this.fetGoods();
        this.getSlocs();
        this.fetchAssetsTool();
        this.fetWarehouse_Tree();

        
    },
    methods: {
        gettoolsName(objectable_id) {
            var obj = this.asset_tools.find(x => x.id == objectable_id);
            return obj != null ? obj.name : '';
        },
        DeleteItems(item, index) {       
           if (confirm(this.$t('Xác nhận xóa') + '?')) {
               this.details.gooddocus_details_del.push({ ...item });
               this.details.gooddocus_details.splice(index, 1);
           }

       },
        fetchAssetsTool() {
            var page_url = this.url_asset_tools
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_tools = data.data;

                }).catch(err => {

                    console.log(err);
                });
        },
        fetTools_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.url_asset_toolss + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_tools = data.data;
                }).catch(err => {

                console.log(err);
            });
        },
   
        fetWarehouse_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.url_tree_slocs + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_slocs = data.data;
                }).catch(err => {

                console.log(err);
            });
        },
       
       
     
        getSlocs(){
             var page_url = this.page_url_slocs
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                  
                }
            }).then(res => res.json())
                .then(res => {
                    this.asset_warehouses = res.data;
                }).catch(err => {

                    console.log(err);
                });

        },
       getGoodDetailsValueName(id){
          var obj = this.goodunits.find(x=>x.id == id );
          return obj?obj.name:'';
        },
           
        fetGoods() {
            this.loading = true;
            let vm = this;
            
            var page_url = this.page_url_detail;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.goods = res.data;
                        
                    }
                    this.loading = false;

                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchGoodunits() {
         
            let vm = this;

            var page_url = this.url_goodunits;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.goodunits = res.data;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
     
        getDetails(page) {
            if (this.id != null) {
                this.loading = true;
                var page_url = this.page_url_details + "/" + this.id + "/edit";
                fetch(page_url, { headers: { Authorization: this.token } })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        if (res.data.success == '1') {
                            this.details = res.data;
                             this.details.gooddocus_details_del = [];
                        }
                        this.edit_payrequest = true;
                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        addGood() {
            var page_url = this.page_url_details;
            if (this.edit_payrequest === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.details),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + data.statuscode;
                        }
                        this.loading = false;                     
                        if (!data.data.errors) {
                            this.details.gooddocus.push(data.data);
                            toastr.success(this.$t('form.save_success'), "");
                            window.location.href = "/sloc/gooddocus";
                        } else {
                            this.errors = data.data.errors;
                            // this.showError('Thông báo', this.errors.quantity[0]);

                            this.showError('Lỗi',this.errors);
                        }
                    })
                    .catch(err => {
                        this.loading = false;

                    });
            } else {
                //update
                fetch(page_url + "/" + this.details.id, {
                    method: "PUT",
                    body: JSON.stringify(this.details),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.data.errors) {
                            toastr.success(this.$t('form.update_success'), "");
                            window.location.href = "/sloc/gooddocus";                        
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Lỗi',this.errors);
                            this.showError('Thông báo', this.errors.quantity[0]);

                        }
                        this.loading = false;

                    })
                    .catch(err => {
                        this.loading = false;

                    });
            }
        },
        showError(title,message){
             if(!title)
                title = "Information";
             toastr.options = {
                positionClass: "toast-bottom-right"
                };

            toastr.error(message, title);
        },
        editGooddetails(item, index) {
            this.edit_voucher = true;                 
            this.gooddetails.id = item.id;
            this.gooddetails.good_id = item.good_id;  
            this.gooddetails.goodunit_id = item.goodunit_id;
            this.gooddetails.objectable_id = item.objectable_id;
            this.gooddetails.objectable_type = item.objectable_type;
            this.gooddetails.name = item.name;
            this.gooddetails.type = item.type;
            this.gooddetails.amount = item.amount;
            this.gooddetails.quantity = item.quantity;
            this.gooddetails.unit_price = item.unit_price;
            this.gooddetails.gooddocu_id = item.gooddocu_id;
            this.gooddetails.end_date = item.end_date;
            this.gooddetails.index = index;
            

            

            $("#AddGood").modal("show");
        },
     
        addGoodDetails(){
            let b='';
            let a='';
            let c='';
            let d='';
            if ( this.edit_voucher === false){  
                if( this.details.gooddocus_details == null)  this.details.gooddocus_details = [];
                    let detail = this.details.gooddocus_details;
                    let detail1 = [];
                    for (let i = 0; i <  detail.length ; i++) {
                        detail1 = detail[i].objectable_id;
                        if(this.gooddetails.objectable_id != detail1 ){    
                            b=0;
                        }
                    else{
                            a=1;
                            this.showError('Thông báo','Công cụ dụng cụ bị trùng');                  
                    }
                } 
                if(b=0 || a!=1){
                     this.details.gooddocus_details.push({...this.gooddetails})
                    this.resetPayementVoucher();

                }    
                
                }else{               
                    let detail2 = this.details.gooddocus_details;
                var detail3 = [];
                var idinput =this.gooddetails.objectable_id;
                let n=this.gooddetails.index;
                for (let i = 0; i <  detail2.length ; i++) {
                    detail3= detail2[i].objectable_id;  
                   switch (i) {
                      case n:
                    break;                      
                    default:
                        if(idinput != detail3 ){    
                                c=0;
                                }
                        else{
                                n=i;
                                d=1;
                                this.showError('Thông báo','Công cụ dụng cụ bị trùng');                  
                            }
                        break
                        }      
                }             
                if(c=0 ||d!=1){
                    this.$set(this.details.gooddocus_details, this.gooddetails.index, {...this.gooddetails});
                    this.resetPayementVoucher();
                    this.edit_voucher= false; 
                }                  
          }

            $("#AddGood").modal("hide");
        },
        resetPayementVoucher() {
            $('#serial_num').val('');
            this.clearAllError();
            this.edit = false;
            this.gooddetails.good_id='';
            this.gooddetails.goodunit_id='';
            this.gooddetails.objectable_id = null;
            this.gooddetails.name = '';
            this.gooddetails.quantity='';
            this.gooddetails.unit_price='';
            this.gooddetails.gooddocu_id='';
            this.gooddetails.end_date='';      
        },
        getUser() {

            var page_url = this.page_url_users
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.users = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        showGood() {
            this.edit_voucher = false;
            this.resetPayementVoucher();
            $("#AddGood").modal("show");
        },

        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.good) {
                this.good[field] = null;
            }
        },
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        getError(fieldName) {
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
        },
        
        clearAllError() {
            this.errors = {};
        },


    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },     
    },

};


</script>
<style scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
 