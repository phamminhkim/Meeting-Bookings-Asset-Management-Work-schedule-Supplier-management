<template>
    <div>
        <div class="content-header">
            <div class="container-fluid m1-0">
                <div class="row">
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <h5><i class="nav-icon fas fa-file-contract"></i><a href="/sloc/gooddocus">
                                        Nhập xuất kho
                                </a></h5>
                            </li>
                            <li class="breadcrumb-item active">
                                <span> {{ $t('form.detail') }}</span>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <div class="float-sm-right" style="margin-top:-5px;">
                            <button class="btn btn-default" @click="editSloc()"><i class="fas fa-edit"></i>{{
                                    $t('form.edit')
                            }}</button>
                            <button class="btn btn-default"><i class="fas fa-print"></i></button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="">

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Công Ty:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm"><strong> {{ companies.name }} </strong></label>

                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Người Tạo:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm" v-if="details.user_id">{{ details.user.name
                                        }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Số Chứng Từ:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm">{{ details.serial_num }}</label>
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Kho:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm" >{{ getslocName(details.sloc_id) }}</label>
                                    </div>
                                </div>

                                <div class="form-group row" >
                                    <span class="col-form-label-sm col-sm-4 ">Loại Phiếu:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm" v-if="details.type==0">Phiếu nhập</label>
                                        <label class="col-form-label-sm"  v-if="details.type==1">Phiếu xuất</label>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Họ Tên Người Giao ( Nhận ) Hàng:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm">{{ details.shipper_name }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Lý Do Nhập:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm">{{ details.reason }}</label>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <span class="col-form-label-sm col-sm-4 ">Nhập Tại Kho:</span>
                                    <div class="col-sm-8">
                                        <label class="col-form-label-sm"></label>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <div class="col-sm-12">
                                       <b-card no-body  >
                                    <b-tabs card small active-nav-item-class="font-weight-bold text-uppercase text-black"  >
                                    <b-tab title="Chi tiết công cụ dụng cụ" >
                                    
                                        <table class="table table-bordered table-sm" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Công cụ dụng cụ</th>
                                   
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành Tiền</th>
                                        <th>Tồn kho</th>
                                        <th>Ngày Đến Hạn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(goodl, index) in details.gooddocus_details" v-bind:key="index">
                                        <td scope="row">{{ index + 1 }}</td>
                                        <td><i class="fas fa-layer-group"></i>{{$t(getGoodDetailsValueName(goodl.objectable_id ))}}</td>

                                        
                                        <td>
                                            {{ goodl.quantity | numFormat('0,0') }}
                                        </td>
                                        <td>
                                            {{ goodl.unit_price | numFormat('0,0') }}
                                        </td>
                                        <td>
                                           <span><strong> {{goodl.quantity * goodl.unit_price | numFormat('0,0')  }}</strong></span>
                                        </td>
                                        <td>
                                            <div v-for="qty in asset_tools" :key="qty.id">
                                                <div v-if="details.type == 0">
                                            <span  v-if="qty.id==goodl.objectable_id"><strong> {{qty.quantity }} </strong></span>
                                        </div>
                                                <div v-if="details.type == 1">   
                                            <span v-if="qty.id==goodl.objectable_id"><strong> {{qty.quantity}}</strong></span>                                                   
                                        </div>
                                         </div>
                                        </td>
                                        <td>
                                            {{ goodl.end_date }}
                                        </td>               
                                    </tr>
                                </tbody>
                            </table>
                                    </b-tab>
                                    </b-tabs>
                                </b-card>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between ">
                            <h3 class="card-title">{{ $t('form.approval_information') }} </h3>
                            <span v-if="info.finished" class="badge badge-primary badge-success mr-1 "
                                :title="$t('form.complete_approval')">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>

                    </div>

                    <div class="card-body">
                        <div v-for="(step, index) in info.steps" v-bind:key="index" id="thongtinduyet">
                            <span v-if="showstep == 'X'" class="text-success">{{ $t(step.name) }}</span>

                            <ul class="list-group">
                                <li class="list-group-item align-items-center border-0 "
                                    v-for="(user, idx) in step.users" v-bind:key="idx">
                                    <div class="d-flex justify-content-between ">
                                        <span :title="user.username">{{ user.name }} <br />

                                            <small v-if="user.release == 2">
                                                <span class="time"><i class="fas fa-clock  text-gray"></i>{{
                                                        user.checkout | formatDateTime
                                                }}</span>
                                            </small>
                                        </span>
                                        <div v-if="user.release == 2">
                                            <!-- Chỉ vị trí đầu tiên  -->
                                            <!-- <span v-if="user.review == 1 && idx == 0"
                                        class="badge badge-primary badge-success mt-2 "
                                        style="float:right;"
                                        :title="$t('form.review')"
                                    >
                                       <i style="width: 12px;" class="fas fa-user-check"></i>
                                    </span>
                                    <span  v-else
                                        class="badge badge-primary badge-success mt-2 "
                                        style="float:right;"
                                        :title="$t('form.approved')"
                                    >
                                        <i class="fas fa-check"></i>
                                    </span> -->

                                            <span class="badge badge-primary badge-success mt-2 " style="float:right;"
                                                :title="$t('form.approved')">
                                                <i class="fas fa-check"></i>
                                            </span>

                                        </div>
                                        <span v-if="user.waiting && user.release == 0"
                                            class="badge badge-primary badge-light">{{ $t('form.waiting_for_approval')
                                            }}</span>
                                        <!-- <span  v-if="user.user_current_approve &&  !user.status && !user.refuse && user.review" class="badge badge-primary badge-light">Chờ review</span> -->
                                        <span v-if="user.release == 1" class="badge badge-primary badge-warning">{{
                                                $t('form.feedback')
                                        }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <loading :loading="loading"></loading>
                    </div>
                    <div class="card-footer" v-if="info.is_document_cancel">
                        <span class="btn btn-block text-red">{{ $t('form.document_cancel') }}</span>
                    </div>
                    <div class="card-footer" v-if="!info.is_document_cancel">
                        <button type="button" class="btn btn-info  btn-block" :disabled="loading"
                            @click="approvePaymentSend()" v-if="info.is_send">
                            <i class="fas fa-paper-plane"></i> {{ $t('form.send_approve') }}
                        </button>
                        <button type="button" class="btn btn-success  btn-block" :disabled="loading"
                            v-if="info.is_approve" @click="approvePaymentAgree()">
                            <i class="fas fa-check"> </i> {{ $t('form.approve') }}
                        </button>
                        <button type="button" class="btn btn-danger  btn-block" @click="showDialogAddFeedback()"
                            v-if="info.is_approve">
                            {{ $t('form.reject_feedback') }}
                        </button>

                        <button type="button" onclick="goBack()" class="btn btn-default btn-block"
                            :title="$t('form.back')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                        </button>
                    </div>
                </div>
                <h6 class="text-gray"> <i class="far fa-comments mr-1"></i> <strong> <i>
                            <u>{{ $t('form.info_feedback') }}:</u> </i> </strong> </h6>
                <ul class="list-group">
                    <li class="list-group-item disabled " style="background-color:rgb(235, 232, 232);"
                        v-for="(feedback, index) in info.feedbacks" v-bind:key="index">
                        <strong>{{ feedback.name }}:</strong>
                        <small><span class="time"><i class="fas fa-clock  text-gray"> </i> {{ feedback.date |
                                formatDateTime
                        }}</span></small><br>
                        {{ feedback.content }}
                    </li>


                </ul>
                <div class="modal fade" id="ykienphanhoi" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">{{ $t('form.opinion_feedback') }}</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" name="" id="feedback" required maxlength="254"
                                    rows="3"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">
                                    {{ $t('form.close') }}
                                </button>
                                <button type="button" @click="approvePaymentRefuse()" data-dismiss="modal"
                                    class="btn btn-primary">
                                    {{ $t('form.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="width:66.5%;">
            <div class="card-header" style="background-color: #E5E5E5;">
                <h6 class="card-title"><i class="fas fa-bell"></i> {{ $t('form.reminder') }}</h6>
                <div class="card-tools">
                    <button class="btn btn-secondary btn-sm" :title="$t('form.add')"> <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- <b-table    small="small" :items="items" id="reminderRef" ref="reminderRef" thead-class="hidden_header" :responsive="true" :fields="fields">
                <template #cell(date_due)="data">
                    <span>{{data.value|formatDate}}</span>
                </template>
                <template #cell(content)="data"> -->
                <!-- <textarea name="" id=""  readonly v-model="data.value" style="border:0px" maxlength="255" cols="30" rows="10"></textarea> -->
                <!-- <pre style="font-family:none;font-size: inherit;white-space: pre-wrap;">{{data.value}}</pre> -->
                <!-- <span  >{{data.value}}</span> -->
                <!-- <div class="d-flex justify-content-between">

                                        <ul class="list-unstyled">
                                          <li v-for="(file,index_file) in data.item.attachment_file" v-bind:key="index_file" class="itemfile">
                                            <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs"  @click.prevent="downloadFile(file.id,file.name)"> {{file.name}}</button> -->
                <!-- <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile(file,index_file)" ><i class="far fa-times-circle "></i></button> -->
                <!-- <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button> -->
                <!-- </div>
                                          </li>
                                        </ul> -->

                <!-- 
                                </div>
                </template> -->
                <!-- <template #row-details="row">
                     <pre style="font-family:none;font-size: inherit;white-space: pre-wrap;">{{row.item.content}}</pre>
                 </template> -->

                <!-- </b-table> -->
            </div>

            <!-- <create-event-dialog :object_id="object_id"  :id="selected" :module="type"></create-event-dialog> -->
        </div>
        <div class="card" style="width:66.5%;">
            <div class="card-body">
                <div class="row">
                    <button class="btn btn-secondary btn-sm mb-3" @click="display()">{{ $t('form.history') }}</button>
                    <div class="timeline timeline-inverse" v-show="show">
                        <!-- <div class="time-label">
                <span class="bg-gray">
                  Lịch sử
                </span>
              </div> -->

                        <div v-for="item in list" v-bind:key="item.id">


                            <i v-if="showicon && item.icon" :class="item.icon"> </i>
                            <i v-else class="fas fa-clock text-gray "> </i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i>
                                    {{ item.created_at | formatDateTime }}</span>
                                <h3 class="timeline-header mute"><i><a style="color:gray">{{ item.user.name }}:</a></i>
                                    {{ $t(item.title) }}</h3>
                                <div class="timeline-body" v-if="item.content">
                                    {{ item.content }}
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>
<script>


export default {
    props: {
        id: String,
        notification_id: String,
        list: Array,
        showicon: Boolean,
        object: Object,
        type: String,
        user_id: String,
        showstep: String,
        goodunit_id: String,
    },
    data() {
        return {
            page_url_good: "/api/sloc/gooddocuss",
            // good_details: {
            //     id:"",
            //     good_id:"",
            //     quantity:"",
            //     unit_price:"",
            //     amount:"",
            //     end_date:"",
            //     index:"",
            //     goods:[]
            // },
            show: true,
            info: {
                is_send: false,
                finished: false,
                user_wait: {},
                next_user: {},
                list_user: [],
                steps: []
            },
            after_send: false,
            page_url_approve_payment: "/api/approve",
            // list: [],
            details: {
                id: "",
                reason: "",
                serial_num: "",
                sloc_id: "",
                type: "",
                shipper_name: '',
                reason: "",

                index: "",
                gooddocus: [],
                gooddocus_details: [],

            },
            filter: '',
            token: '',
            loading: false,
            companies: [],
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            url_goodunits: "/api/sloc/goodunits",
            departments: [],
            url_slocs: "api/asset/warehouse",
            goodunits:[],
            asset_warehouses:[],
            url_asset_tools:  "api/asset/stock",

            asset_tools:[],

        }
    },
    
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.getDetails();
        this.fetchGoodunits();
        this.fetchSlocs();
        this.fetchAssetsTool();

    },

    watch: {
        object: function (value) {
            //   console.log('Prop changed: ', newVal, ' | was: ', oldVal)
            this.get_info_approved();
        }
    },
    methods: {
        getslocName(id) {
            var obj = this.asset_warehouses.find(x => x.id == id);
            return obj?obj.name:'';

        },
        
        getGoodDetailsValueName(id){
          var obj = this.asset_tools.find(x=>x.id == id );
          return obj?obj.name:'';
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
        fetchSlocs(){
             let vm = this;

            var page_url = this.url_slocs;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_warehouses = res.data;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchGoodunits() {
            //$("#tbbody_id").html('');
            // const this.token = "Bearer " + window.Laravel.access_this.token;
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
        getDetails() {
            if (this.id != null) {
                this.loading = true;
                var page_url = this.page_url_good + "/" + this.id + "?notification_id=" + this.notification_id;
                fetch(page_url, { headers: { Authorization: this.token } })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        if (res.data.success == '1') {
                            this.details = res.data;
                            this.details.goods_del = [];
                        }

                        this.loading = false;

                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;//"/api/category/companies";
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data.data;
                    //  console.length(res);
                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetchDepartment(page) {
            //$("#tbbody_id").html('');
            this.loading = true;
            var page_url = this.page_url_department;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
            this.edit = false;

        },

        showControl(fieldName) {
            if (fieldName in this.goods) {

                return this.goods[fieldName]['show'];
            }
            return false;
        },
        editSloc() {
            window.location.href = "sloc/docuscreate";
        },
        display() {
            this.show = !this.show;
        },
        get_info_approved() {
            this.loading = true;
            var page_url =
                this.page_url_approve_payment +
                "/info?type=" +
                this.type +
                "&id=" +
                this.object.id;
            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    //   console.log(data);
                    this.info = data.data.info;
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        //Gửi yêu cầu duyệt
        approvePaymentSend() {
            this.loading = true;
            fetch(this.page_url_approve_payment + "/send", {
                method: "post",
                body: JSON.stringify({ type: this.type, id: this.object.id }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    // console.log(data);
                    this.loading = false;
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t('form.send_approved_success'), "");
                    } else {

                        if (data.data.errors != null && !Array.isArray(data.data.errors)) {
                            toastr.success(data.data.errors, "");
                        }

                        toastr.success(this.$t('form.send_approved_error'), "");

                    }
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        editSloc(){
         window.location.href= "sloc/gooddocus?type=edit&id="+this.id;
  
        },
        approvePaymentAgree() {
            this.loading = true;
            fetch(this.page_url_approve_payment + "/agree", {
                method: "post",
                body: JSON.stringify({ type: this.type, id: this.object.id }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    // console.log(data);
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t('form.approved_success'), "");
                    } else {
                        toastr.success(this.$t('form.approved_error'), "");
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        //Ghi ý kiến phản hồi
        showDialogAddFeedback() {
            $("#ykienphanhoi").modal("show");
        },

        approvePaymentRefuse() {
            fetch(this.page_url_approve_payment + "/refuse", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    feedback: $("#feedback").val()
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    //  console.log(data);
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t('form.feedback_success'), "");
                    } else {
                        toastr.success(this.$t('form.feedback_error'), "");
                    }
                })
                .catch(err => console.log(err));
        },
    },
}
</script>
<style lang="scss" scoped>
 table {
    font-size: 15px;
}

.list-group-item {
    position: relative;
    display: block;
    padding: 0.35rem 0.15rem;
    background-color: #fff;
    /* border: 1px solid rgba(184, 26, 26, 0.125); */
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-group {
    margin-bottom: 0.5rem;
}
</style>