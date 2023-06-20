<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-layer-group"></i>Trạng thái tài sản</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-10">
                                <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search model"
                                        aria-label="Search model" aria-describedby="basic-addon2" v-model="filter"
                                        style="border-right:none;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="background:white;color:gainsboro"
                                            id="basic-addon2"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="float-sm-left">
                                    <b-dropdown id="dropdown-1" right text="Tạo mới" variant="primary">
                                        <b-dropdown-item @click="show()">Tạo mới trạng thái</b-dropdown-item>

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
            <div class="card-body">
                <b-table hover responsive :bordered="true" small :items="asset_statuses" :fields="fields"
                    :current-page="current_page" :per-page="per_page" :filter="filter">
                    <template #head(id)=data>
                        <div style="text-align:center">
                            <span class="text-success">{{data.label}}</span>
                        </div>
                    </template>
                    <template #cell(id)=data>
                        <div style="text-align:center">
                            <span>{{data.item.id}}</span>
                        </div>
                    </template>
                    <template #head(name)=data>
                        <div style="text-align:center">
                            <span class="text-success">{{data.label}}</span>
                        </div>
                    </template>
                    <template #head(#)=data>
                        <div style="text-align:center">
                            <span class="text-success">{{data.label}}</span>
                        </div>
                    </template>
                    <template #cell(name)=data>
                        <div v-if="data.item.name == 1" class="bg-success"
                            style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Tốt</div>
                        <div v-if="data.item.name == 2" class="bg-warning"
                            style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Sửa chữa</div>
                        <div v-if="data.item.name == 3" class="bg-danger"
                            style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Mất</div>
                        <div v-if="data.item.name == 4" class="bg-danger"
                            style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Hỏng</div>
                        <div v-if="data.item.name == 5" class="bg-danger"
                            style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Hủy bỏ</div>
                    </template>
                    <template #cell(#)="data">
                        <div class="margin" style="text-align:center">
                            <button class="btn btn-xs" @click="editStatus(data.item)"><i class="fas fa-edit text-green "
                                    title="Edit"></i></button>

                            <button class="btn btn-xs" @click="deleteStatus(data.item.id)"><i
                                    class="fas fa-trash text-red " title="Delete"></i></button>
                        </div>
                    </template>
                </b-table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-4" style="text-align: left" for="">Per page:</label>
                            <div class="col-md-3">
                                <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                </b-form-select>
                            </div>
                            <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                            <div class="col-md-3">
                                <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page" pills
                                    class="ml-1"></b-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal  " id="addstatus" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="AddStatus">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">TẠO MỚI TRẠNG THÁI</h4>
                                <h4 v-if="edit"> CẬP NHẬT TRẠNG THÁI</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên</label> <small class="text-danger"> (*) </small>
                                <select v-model="status.name" class="form-control" id="name" name="name"
                                    v-bind:class="hasError('name') ? 'is-invalid' : ''">
                                    <option value="1">Tốt</option>
                                    <option value="2">Sửa chữa</option>
                                    <option value="3">Mất</option>
                                    <option value="4">Hỏng</option>
                                    <option value="5">Hủy bỏ</option>
                                </select>
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('name') }}</strong>
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
export default {
    data() {
        return {
            edit: false,
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            asset_statuses: [],
            status: {
                id: '',
                name: '',
                index: ''
            },
            fields: [
                {
                    key: 'id',
                    label: 'ID'
                },
                {
                    key: 'name',
                    label: 'Trạng thái'
                },
                {
                    key: '#',
                    label: 'action'
                }
            ],
            filter: "",
            url_asset_status: "/api/asset/status",
            token: '',
            errors: {},
            loading: false,
            edit: false,

        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchStatus();
    },
    methods: {
        fetchStatus() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            var page_url = this.url_asset_status;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_statuses = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        AddStatus() {
            var page_url = this.url_asset_status;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.status),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        
                        if (!data.data.errors) {
                            
                            this.reset();
                            this.asset_statuses.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addstatus").modal("hide");

                        } else {
                           
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.status.id, {
                    method: "PUT",
                    body: JSON.stringify(this.status),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            
                            this.$set(this.asset_statuses, this.status.index, { ...this.status });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addstatus").modal("hide");
                            this.reset();
                        } else {
                           
                            this.errors = data.data.errors;
                            console.log(this.errors);
                            this.showError('Thông báo', this.errors.id[0]);
                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        editStatus(status) {
            // var department={..._obj};
            let index = this.asset_statuses.findIndex(i => {

                return i.id == status.id;
            });
            this.edit = true;
            this.status.id = status.id;
            this.status.name = status.name;
            this.status.index = index;
            $("#addstatus").modal("show");
        },
        deleteStatus(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_status}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchStatus();
                    });
            }
        },
        clearAllError() {
            this.errors = {};
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.status) {
                this.status[field] = null;
            }
            
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
        show() {
            this.reset();
            $('#addstatus').modal("show");
        },

    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.asset_statuses.length;
        },
    },

}
</script>