<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-file-contract"></i>Kiểm Kê</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                <!-- <div class="input-group mb-3" style="width:70%;float:right;">
                                    <input type="text" class="form-control" placeholder="Search model"
                                        aria-label="Search model" aria-describedby="basic-addon2"
                                        style="border-right:none;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="background:white;color:gainsboro"
                                            id="basic-addon2"><i class="fas fa-search"></i></span>
                                    </div>
                                </div> -->
                            </div>
                            <div class="col-sm-4">
                                <div class="float-right">
                                    <b-dropdown id="shadow_btn" right text="Tạo mới" variant="primary">
                                        <b-dropdown-item @click="show()"><i class="fas fa-plus"></i> Tạo mới kiểm kê</b-dropdown-item>

                                    </b-dropdown>
                                    <!-- <button  class="btn btn-info btn-sm" @click="showAssetStock()"> <i class="fa fa-plus"></i>
                                                {{ $t('form.create')}}</button> -->
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="TẤT CẢ" title-link-class="animation1" active id="tatca">
                        <template #title>
                            <div class="tess">
                            <strong>TẤT CẢ</strong>
                         </div>
                        </template>
                        <div class="header" >
                            <b-table :current-page="current_page" :per-page="per_page"  :bordered="true" small headVariant responsive :items="asset_inventarios" :fields="fields">
                                <template #cell(#)="data">
                                    <div>
                                        <span> {{ data.index + 1 }} </span>
                                    </div>
                                </template>
                                <template #head(#)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(name)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(responsible)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(deadline_confirm)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(user.name)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(description)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(complete)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(complete)="data">
                                    <span v-if="data.item.complete==1" class="badge bg-success"> Đã hoàn thành ({{data.item.time_confirm}})</span>
                                    <span v-if="data.item.complete==0" class="badge bg-danger"> Chưa hoàn thành</span>

                                </template>
                                <template #head(asset_warehouse.name)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(view)="data">
                                    <div class="text-success">
                                        <span> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(responsible)="data">
                                    <span> {{ $t(getuserName(data.item.responsible)) }} </span>
                                </template>

                                <template #cell(view)="data">
                                    <div class="text-success">
                                        <button @click="inventario_product(data.item.id, data.item.asset_warehouse_id,data.item.deadline_confirm)"
                                            class="btn btn-sm btn-secondary"> Chi tiết </button>
                                    </div>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-form-label-sm col-md-2"
                                            style="text-align: left" for="">Per
                                            page:</label>
                                        <div class="col-md-3">
                                            <b-form-select size="sm" v-model="per_page"
                                                :options="pageOptions">
                                            </b-form-select>
                                        </div>
                                        <label class="col-form-label-sm col-md-1"
                                            style="text-align: left" for=""></label>
                                        <div class="col-md-3">
                                            <b-pagination v-model="current_page" pills
                                                :total-rows="rows"
                                                :per-page="per_page" align="fill"
                                                class="my-0"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <!-- <b-tab title="ĐANG LÀM" id="bangiao">
                        <div class="header" style="background-color:rgb(244, 246, 249);">

                        </div>

                    </b-tab>
                    <b-tab title="ĐÃ HOÀN THÀNH" id="sudung">
                        <div class="header" style="background-color:rgb(244, 246, 249);">

                        </div>

                    </b-tab> -->
                </b-tabs>
            </div>
        </div>
        <div class="modal  " id="AddInventario" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddInventarios">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">TẠO MỚI KIỂM KÊ</h4>
                                <h4 v-if="edit"> CẬP NHẬT KIỂM KÊ</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" style="border:1px solid orange;background:floralwhite;">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <i class="fas fa-flag text-warning"></i>
                                    </div>
                                    <div class="col-sm-11">
                                        <p class="text-warning">Notice</p>
                                        <span>Kho hàng sẽ bị khóa giao dịch cho đến khi kiểm kê kết thúc hoặc qúa thời hạn hoàn thành</span>
                                    </div>
                                </div>


                            </div>
                            <div class="form-group">
                                <label>Tên kiểm kê</label> <small class="text-danger"> (*) </small>
                                <input v-model="inventario.name" id="name" name="name" class="form-control"
                                    placeholder="3-255 characters"
                                    v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('name') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Người chịu trách nhiệm</label> <small class="text-danger"> (*) </small>
                                <treeselect placeholder="Người chịu trách nhiệm" :disable-branch-nodes="true"
                                    id="responsible" :show-count="true" :multiple="false"
                                    v-model="inventario.responsible" name="responsible" :options="users"
                                    v-bind:class="hasError('responsible') ? 'is-invalid' : ''">
                                </treeselect>
                                <span v-if="hasError('responsible')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('responsible') }}</strong>
                                </span>
                            </div>
                            <!-- <div class="form-group">
                                    <label>Người theo dõi</label> 
                                   <input class="form-control" placeholder="Nhập @ để tag" />
                                </div> -->
                            <div class="form-group">
                                <label>Kho hàng</label> <small class="text-danger"> (*) </small>
                                <!-- <select v-model="inventario.asset_warehouse_id" class="form-control"
                                    id="asset_warehouse_id" name="asset_warehouse_id"
                                    v-bind:class="hasError('asset_warehouse_id') ? 'is-invalid' : ''">
                                    <option selected>Vui lòng chọn một</option>
                                    <option v-for="house in asset_warehouses" :key="house.id" :value="house.id">
                                        {{ house.name }} </option>
                                </select> -->

                                <treeselect placeholder="Kho hàng" :disable-branch-nodes="true"
                                            :show-count="true" :multiple="false" v-model="inventario.asset_warehouse_id"
                                            :options="tree_slocs"
                                            v-bind:class="hasError('asset_warehouse_id') ? 'is-invalid' : ''">
                                        </treeselect>
                                <span v-if="hasError('asset_warehouse_id')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('asset_warehouse_id') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Hạn chót hoàn thành kiểm kê</label> <small class="text-danger"> (*) </small>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input v-model="inventario.deadline_confirm" type="date" class="form-control"
                                            placeholder="Click to select a date" id="deadline_confirm"
                                            name="deadline_confirm"
                                            v-bind:class="hasError('deadline_confirm') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('deadline_confirm')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('deadline_confirm') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <input v-model="inventario.time" type="time" class="form-control" id="time"
                                            name="time" v-bind:class="hasError('time') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('time')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('time') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" v-model="inventario.description" value="Nội dung" v-bind:class="hasError('description') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('description') }}</strong>
                                        </span>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-success" style="width:47%;">
                                Lưu lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
    props: {

        title: ""
    },
    components: {
        Treeselect,
    },
    data() {
        return {
            edit: false,
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            inventario: {
                id: '',
                name: '',
                responsible: null,
                asset_warehouse_id: null,
                deadline_confirm: '',
                create_by: '',
                time: '',
                description: '',
                index: '',
                complete:'',
            },
            fields: [
                {
                    key: '#',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: 'text-nowrap'
                },
                {
                    key: 'responsible',
                    label: 'Ngươi chịu trách nhiệm',
                    class: 'text-nowrap'
                },
                {
                    key: 'asset_warehouse.name',
                    label: 'Kho hàng',
                    class: 'text-nowrap'
                },
                {
                    key: 'deadline_confirm',
                    label: 'Hạn chót xác nhận',
                    class: 'text-nowrap'
                },
                {
                    key: 'user.name',
                    label: 'Người tạo',
                    class: 'text-nowrap'
                },
                {
                    key: 'description',
                    label: 'Nội dung',
                    class: 'text-nowrap'
                },
                {
                    key: 'complete',
                    label: 'Complete',
                    class: 'text-nowrap'
                },
                {
                    key: 'view',
                    label: 'Chi tiết',
                    class: 'text-nowrap'
                }
            ],
            url_tree_slocs: "api/asset/wall",
            tree_slocs: [],
            asset_inventarios: [],
            asset_warehouses: [],
            users: [],
            userss: [],
            loading: false,
            token: '',
            errors: {},
            url_asset_inventario: "api/asset/inventario",
            url_asset_warehouses: "/api/asset/warehouse",
            page_url_userscombo: "api/user/allnew",
            page_url_users: "api/user/all",
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
        this.fetchUser();
        this.getUser();
        this.fetWarehouse_Tree();
    },
    methods: {
        getuserName(id) {
            var obj = this.userss.find(x => x.id == id);
            return obj ? obj.name : '';
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
        getUser() {

            var page_url = this.page_url_users
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.userss = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        fetchinventario() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;

            var page_url = this.url_asset_inventario;
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
        fetchWarehouse() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            var page_url = this.url_asset_warehouses;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_warehouses = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchUser() {

            var page_url = this.page_url_userscombo + "?type=tree_combobox";
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
        AddInventarios() {
            var page_url = this.url_asset_inventario;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.inventario),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.asset_inventarios.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#AddInventario").modal("hide");
                            this.fetchinventario();
                        } else {
                            this.errors = data.data.errors;
                         
                            this.showError('Thông báo', 'Lỗi');
                            
                        }
                        this.loading = false;
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.inventario.id, {
                    method: "PUT",
                    body: JSON.stringify(this.inventario),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.asset_inventarios, this.inventario.index, { ...this.inventario });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#AddInventario").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Lỗi');
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditInventarios(inventario) {
            let index = this.asset_inventarios.findIndex(i => {
                console.log("id" + i.id);
                return i.id == inventario.id;
            });
            this.clearAllError();
            this.edit = true;
            this.inventario.id = inventario.id;
            this.inventario.name = inventario.name;
            this.inventario.responsible = inventario.responsible;
            this.inventario.asset_warehouse_id = inventario.asset_warehouse_id;
            this.inventario.deadline_confirm = inventario.deadline_confirm;
            this.inventario.create_by = inventario.create_by;
            this.inventario.description = inventario.description;

            this.inventario.index = index;
            $("#AddInventario").modal("show");
        },
        DeleteInventarios(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_inventario}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchCats();
                    });
            }
        },
        clearAllError() {
            this.errors = {};
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            // for (let field in this.inventario) {
            //     this.inventario[field] = null;
            // }
            // this.edit = false;
            this.inventario.id = "";
            this.inventario.name = "";
            this.inventario.responsible = null;
            this.inventario.asset_warehouse_id = null;
            this.inventario.deadline_confirm = "";
            this.inventario.time = "";
            this.inventario.description = "";
            this.inventario.create_by = "";
            this.inventario.index = -1;
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.error(message, title);
        },
        show() {
            this.reset();
            
            $('#AddInventario').modal("show");
        },
        inventario_product(id, asset_warehouse_id,time) {
            window.location.href = '/asset/inventario?type=product&id=' + id + '&notification_id=' + asset_warehouse_id + '&time=' + time;
        },
        hasError(fieldName) {
            return fieldName in this.errors;
        },
        getError(fieldName) {
            // console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];
        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        },

    },
    computed: {
        rows() {
            return this.asset_inventarios.length;
        },
    },
}
</script>
<style lang="scss" scoped>
.animation div{
   
    animation: color 2s infinite;
}
.animation1 div::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background:#3c8dbc;
    transition: width .1s;
}
.animation1 div:hover::after {
    width: 100%;
    transition: width .1s;
}
#shadow_btn:hover{

    color:white;
    box-shadow: 1px 1px 10px #3c8dbc;
}
.tess{
    clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
    color: gray;
}
</style>