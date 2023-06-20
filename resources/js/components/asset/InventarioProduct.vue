<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="float-left mr-4"  @click="exit()" style="cursor:pointer">
                            <i class="fas fa-caret-left"></i>
                        </div>
                        <div class="float-left">
                            <h5 class="m-0 text-dark text-uppercase"><b>Kiểm kê
                                {{ $t(getWarehouse(notification_id)) }} </b></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <b-tabs content-class="mt-1" small>
                    <b-tab title="DỮ LIỆU" id="bangiao">
                        <div class="header">
                            <div class="bh">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card" style="border:4px solid lightgray;margin: 1rem auto;">
                                            <div class="card-body" id="user_confirm">
                                                <p class="text-white font-weight-bold">01. Người sử dụng xác nhận/ Giám
                                                    sát xác nhận <br>
                                                    <!-- <span id="update" class="text-xs text-white">ĐANG TRONG TRẠNG THÁI
                                                        SỬ DỤNG</span> -->
                                                    <!-- <span id="update" class="text-xs text-white"> {{Get_Status}}  </span> -->
                                                </p>
                                                <span class="text-secondary mb-1"><i class="fas fa-clock"></i>Hạn chót:
                                                    {{ time }}
                                                </span><br>

                                            </div>
                                        </div>

                                    </div>
                                    <!-- <div class="col-sm-3" style="border-right:4px solid white;background: whitesmoke;">
                                        <p class="text-success">02. Người giám sát xác nhận</p>
                                        <span class="text-secondary">Người giám sát xác nhận tình trạng tài sản của
                                            người dùng</span>
                                    </div> -->
                                    <div class="col-lg-4">
                                        <div class="card " style="border:4px solid lightgray;margin: 1rem auto;">
                                            <div class="card-body " id="step_2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="float-left">
                                                            <p v-if="this.getcomplete(this.id)===0" id="step" class="font-weight-bold">02. Đánh giá cuối cùng</p>
                                                            <p v-if="this.getcomplete(this.id)===1" id="step" class="font-weight-bold">02. Đánh giá cuối cùng</p>
                                                        </div>
                                                        <div class="float-right">
                                                            <button v-if="this.getcomplete(this.id)===0" @click.prevent=successinventario id="bt"
                                                            class="button text-xs btn-xs float-right font-weight-bold"
                                                            style="vertical-align:middle"><span
                                                                class="text-xs font-weight-bold">Đánh
                                                                giá cuối cùng </span></button>
                                                                <button v-if="this.getcomplete(this.id)===1" @click.prevent=successinventario id="bt"
                                                            class="button text-xs btn-xs float-right font-weight-bold"
                                                            style="vertical-align:middle"><span
                                                                class="text-xs font-weight-bold">Xem lại đánh giá </span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 ">
                                        <div class="card"
                                            style="border:4px solid lightgray;margin: 1rem auto;">
                                            <div class="card-body " id="sum_user">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="text-align-center"
                                                            style="text-align:center;border-left:2px solid white;border-right:2px solid white;">
                                                            <div v-for="list in getall">
                                                                <span class="badge bg-warning font-weight-bold text-sm">
                                                                    {{ list.countallUser }}
                                                                </span> <br>
                                                                <small class="badge bg-secondary ">NGƯỜI SỬ DỤNG</small>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="text-align-center"
                                                            style="text-align:center;border-left:2px solid white;border-right:2px solid white;">
                                                            <div v-for="list in getall">

                                                                <span class="badge bg-warning text-sm">
                                                                    {{ list.allconfirm }}/{{ list.all }}</span> <br>
                                                                <small class="badge bg-secondary">DANH SÁCH </small>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <b-table responsive small hover headVariant :items="list_user_warehouse"
                                                :fields="fields">

                                                <template #head(#)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <template #cell(#)=data>
                                                    <span >{{ data.index + 1 }}</span>
                                                </template>
                                                <template #head(user.name)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <!-- <template #cell(Người_sử_dụng)=data>
                                                    <span v-if="data.item.Người_sử_dụng == 'Phạm Minh Kim'">
                                                        <b-avatar variant="info"
                                                            src="/goods/266085228_1017420635471721_5554321211732546733_n - Copy.jpg"
                                                            class="mr-2" size="2rem"></b-avatar>
                                                    </span>
                                                    <span v-if="data.item.Người_sử_dụng == 'Nguyễn Ngọc Hùng'">
                                                        <b-avatar variant="info" src="/goods/hung.jpg" class="mr-2"
                                                            size="2rem">
                                                        </b-avatar>
                                                    </span>
                                                    <span v-if="data.item.Người_sử_dụng == 'Văn Trường'">
                                                        <b-avatar variant="info" src="/goods/hung.jpg" class="mr-2"
                                                            size="2rem">
                                                        </b-avatar>
                                                    </span>
                                                    <span>{{ data.item.Người_sử_dụng }}</span>
                                                </template> -->
                                                <template #cell(user.name)=data>
                                                    <b-avatar variant="info" :src="data.item.user.avatar" class="mr-2"
                                                        size="3rem">
                                                    </b-avatar> <span> {{ data.item.user.name }} </span>
                                                </template>
                                                <template #head(ts)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <template #head(dsccdc)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <template #cell(ts)=data>
                                                    <div class="text-center" v-if="data.item.ts !==0">
                                                        <span class="text-xs text-secondary font-weight-bold">Ngưởi dùng
                                                            đánh giá: <span class="badge bg-secondary text-sm">
                                                                {{ data.item.sumasset }} / {{ data.item.ts }}</span>
                                                            <br></span>
                                                        <span class="text-xs text-secondary font-weight-bold">Thủ kho
                                                            đánh giá: <span class="badge bg-secondary text-sm">
                                                                {{ data.item.sumstockerasset }} / {{ data.item.ts
                                                                }}</span> <br></span>
                                                        <span
                                                            v-if="data.item.sumstockerasset == 0 && data.item.sumasset == 0"
                                                            class="badge bg-danger">Chưa xác nhận</span>
                                                        <span
                                                            v-if="data.item.sumasset == data.item.sumstockerasset && data.item.sumasset !== 0 && data.item.sumstockerasset !== 0 && data.item.sumasset == data.item.ts && data.item.sumstockerasset == data.item.ts"
                                                            class="badge bg-success">Đã xác nhận đầy đủ</span>
                                                        <span
                                                            v-if="data.item.sumasset !== data.item.sumstockerasset || data.item.sumasset !== data.item.ts && data.item.sumstockerasset !== data.item.ts"
                                                            class="badge bg-warning">Chưa xác nhận đầy đủ</span>
                                                    </div>
                                                    <div class="text-center" v-if="data.item.ts ===0">
                                                        <span class="badge bg-danger" >Không có tài sản thuộc kho</span>
                                                    </div>
                                                    <!-- <div v-else class="text-center">
                                                        <span class="text-dark"> {{data.item.sumasset}} / {{ data.item.ts }}</span><br>
                                                        <span class="badge bg-waring">Đã xác nhận đầy đủ</span>
                                                    </div> -->
                                                </template>
                                                <template #cell(dsccdc)=data>
                                                    <div class="text-center" v-if="data.item.dsccdc !==0">
                                                        <span class="text-xs text-secondary font-weight-bold">Người dùng
                                                            đánh giá: <span class="badge bg-secondary text-sm">
                                                                {{ data.item.sumtool }} / {{ data.item.dsccdc }}</span>
                                                            <br></span>
                                                        <span class="text-xs text-secondary font-weight-bold">Thủ kho
                                                            đánh giá: <span class="badge bg-secondary text-sm">
                                                                {{ data.item.sumstockertool }} / {{ data.item.dsccdc
                                                                }}</span>
                                                            <br></span>
                                                        <span
                                                            v-if="data.item.sumtool == data.item.sumstockertool && data.item.sumtool !== 0 && data.item.sumstockertool !== 0 && data.item.sumtool == data.item.dsccdc && data.item.sumstockertool == data.item.dsccdc"
                                                            class="badge bg-success">Đã xác nhận đầy đủ</span>
                                                        <span
                                                            v-if="data.item.sumtool == 0 && data.item.sumstockertool == 0"
                                                            class="badge bg-danger">Chưa xác nhận</span>
                                                        <span
                                                            v-if="data.item.sumtool !== data.item.sumstockertool || data.item.sumtool !== data.item.dsccdc && data.item.sumstockertool !== data.item.dsccdc"
                                                            class="badge bg-warning">Chưa xác nhận đầy đủ</span>
                                                    </div>
                                                    <div class="text-center" v-if="data.item.dsccdc ===0">
                                                        <span class="badge bg-danger">Không có CCDC thuộc kho</span>
                                                    </div>
                                                </template>
                                                <template #head(action)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>

                                                <template #cell(action)=data>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <b-button v-if="getcomplete(id)===1"
                                                                @click="detailinventario(data.item.user_id, data.item.even, data.item.asset_warehouse_id, time)"
                                                                class="btn btn-xs btn-info" id="bt_kiemke"
                                                                style="vertical-align:middle"> <span id="kiem_ke" class="text-sm">Xem lại kiểm kê</span>
                                                            </b-button>
                                                            <b-button v-if="getcomplete(id)===0"
                                                                @click="detailinventario(data.item.user_id, data.item.even, data.item.asset_warehouse_id, time)"
                                                                class="btn btn-xs btn-info" id="bt_kiemke"
                                                                style="vertical-align:middle"> <span id="kiem_ke" class="text-sm">Tiến hành kiểm
                                                                    kê</span>
                                                            </b-button>
                                                            
                                                        </div>

                                                    </div>
                                                </template>
                                            </b-table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>


                    </b-tab>

                </b-tabs>
            </div>
        </div>
    </div>
</template>
<script>
// import { exit } from 'process';
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import _toConsumableArray from '@babel/runtime/helpers/toConsumableArray';

export default {
    components: {
        Treeselect,
    },
    props: {
        id: String,
        notification_id: String,
        time: "",
        title: ""
    },
    data() {
        return {
            fields: [
                {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'user.name',
                    label: 'Người sử dụng',
                    class: 'text-nowrap'
                },
                {
                    key: 'ts',
                    label: 'Tài sản',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'dsccdc',
                    label: 'Công cụ dụng cụ',
                    class: 'text-nowrap text-center'
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: 'text-nowrap'
                },
            ],
            url_asset_inventario: "api/asset/inventario",
            page_url_slocs: "api/asset/warehouse",
            asset_inventarios: [],
            list_user_warehouse: [],
            asset_warehouses: [],
            token: '',
            users: [],
            page_url_users: "api/user/allnew",
            filter: {
                user_id: '',
            },
            loading: false,
            his:[],
            url_asset_history: "api/asset/inventario_history",
            asset_inventarios:[],
            url_inventario: "api/asset/inventario",

        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchinventario();
        this.fetchWarehouse();
        this.getUser();
        this.fetchAssetHis();
        this.fetchinven();
    },
    watch:{
        // status_use(){
        //     this.gettess();
        // }
    },
    methods: {
        getcomplete(id) {
           
           var obj = this.asset_inventarios.find(x => x.id == id);
           return obj ? obj.complete : '';
       },
        fetchinven() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;

            var page_url = this.url_inventario;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_inventarios = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchAssetHis(page) {
            //$("#tbbody_id").html('');
            if (this.id != null) {
                this.loading = true;

                var page_url = this.url_asset_history;
                //  console.log(page_url);
                fetch(page_url, {
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                }).then(res => res.json())
                    .then(data => {
                        
                        this.his = data.data;
                      
                        this.loading = false;
                    }).catch(err => {
                        console.log(err);
                        this.loading = false;
                    });
            }

        },
        getUser() {

            var page_url = this.page_url_users + "?type=tree_combobox";
            //  console.log(page_url);
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
        getWarehouse(id) {
            var obj = this.asset_warehouses.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        fetchWarehouse() {

            var page_url = this.page_url_slocs;
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
        fetchinventario(page) {
            if (this.id !== null) {
                this.loading = true;
                var page_url = this.url_asset_inventario + "/" + this.id + "/edit";
                fetch(page_url, {
                    headers: {
                        Authorization: this.token,

                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        // console.log(res.data);
                        this.list_user_warehouse = res.data;
                        // if (res.success ==  1) {

                        // }
                        this.loading = false;
                    }).catch(err => {
                        console.log(err);
                        this.loading = false;

                    });
            }


        },
        detailinventario(user_id, inven, ware_house, time) {
            window.location.href = '/asset/inventario?type=detail&user_id=' + user_id + '&inven=' + inven + '&ware_house=' + ware_house + '&time=' + time;
        },
        exit() {
            window.location.href = '/asset/inventario';
        },
        successinventario() {
            for (let index = 0; index < this.list_user_warehouse.length; index++) {
                if ( this.list_user_warehouse[index].sumasset != 0 && this.list_user_warehouse[index].sumstockerasset !=0 &&
                this.list_user_warehouse[index].sumstockertool != 0 && this.list_user_warehouse[index].sumtool !=0
                ) {
                    window.location.href = '/asset/inventario?type=success&id=' + this.id + '&notification_id=' + this.notification_id + '&time=' + this.time;
                    // toastr.warning('aaa');
                }  else {
                    toastr.warning('Dòng: ' + index + 1 + ' - ' + 'Tên: ' + this.list_user_warehouse[index].user.name + '<br>' + ' Chưa xác nhận đầy đủ');
               
                }
                break;
            }
            //     } else {
            //         toastr.warning('Dòng: ' + index + 1 + ' - ' + 'Tên: ' + this.list_user_warehouse[index].user.name + '<br>' + ' Chưa xác nhận đầy đủ');
               
            //     }
            //     break

            // }
            // window.location.href = '/asset/inventario?type=success&id=' + id + '&notification_id=' + asset_warehouse_id + '&time=' + time;
        },
    },
    computed: {
        getall() {
            let getall = [];
            for (let index = 0; index < this.list_user_warehouse.length; index++) {
                // const element = this.asset_tools[index];

                getall.push(this.list_user_warehouse[index]);

                // console.log(this.stockk.id == this.asset_tools[index].id);
                break
            }
            return getall;
        },
        // Get_Status(){
        //     this.status_use = 'ĐANG TRONG TRẠNG THÁI SỬ DỤNG';
        //     return this.status_use;
        // },


    }
}
</script>
<style lang="scss" scoped>
#step_2 {

    background: whitesmoke;
    color: #28a745;
  
}
#sum_user{
    background: whitesmoke;
   
}
#tess {

    background: white;
    clip-path: polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%);
}



#sum_user::after {
    content: '';
    display: block;
    width: 0;
    height: 3px;
    margin-top: 5px;
    background: lightgray;
    transition: width .5s;
}

#sum_user::after {
    width: 100%;
    transition: width .5s;
}

#step_2::after {
    content: '';
    display: block;
    width: 10px;
    clip-path: polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%);
    height: 20px;
    
    background: linear-gradient(-45deg, #23a6d5, #23d5ab);
    transition: width .5s;
}

#step_2:hover::after {
    width: 20%;
    transition: width .5s;
}


#update {
    animation: user_confirms 2s infinite;
}

#user_confirm::after {
    width: 100%;
    transition: width .5s;
    
}

#user_confirm {
    background: linear-gradient(-45deg, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
    transition: width .5s;
    
}

#bt {
    display: inline-block;
    border-radius: 4px;
    background-color: #f4511e;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 28px;
    padding: 10px;
    transition: all 0.5s;
    cursor: pointer;
}

#bt span {
    cursor: pointer;
    display: inline-block;
    position: relative;
    transition: 0.5s;
}

#bt span:after {
    content: '\00bb';
    position: absolute;
    opacity: 0;
    top: 0;
    right: -20px;
    transition: 0.5s;
}

#bt:hover span {
    padding-right: 25px;
}

#bt:hover span:after {
    opacity: 1;
    right: 0;
}

#bt_kiemke {
    display: inline-block;
    border-radius: 4px;
    border: none;
    background: linear-gradient(-45deg, #23d5ab);
    clip-path: polygon(0% 0%, 75% 0%, 100% 50%, 75% 100%, 0% 100%);
    color: #FFFFFF;
    text-align: center;
    font-size: 19px;
    padding: 15px;
    transition: all 0.5s;
    cursor: pointer;
}
#bt_kiemke:hover {
    background-color: #f4511e;
}
@keyframes user_confirms {
    50% {
        background: gold;
    }
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}
</style>