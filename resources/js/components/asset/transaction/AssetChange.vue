<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="m-0 text-dark"> <i class="fas fa-exchange-alt"></i> {{ $t(title) }} </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="float-md-right">
                                    <b-dropdown variant="primary" class="float-left mr-1" right text="Import Excel TS"
                                        style="display:inline-block;">
                                        <b-dropdown-item @click.prevent="showImportExcel()">Bàn giao đầu
                                            kỳ</b-dropdown-item>
                                    </b-dropdown>
                                    <button class="btn btn-primary  text-black" id="shadow_btn" @click="showAssetStock()"><i
                                            class="fas fa-plus"></i> Tạo giao dịch</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group float-left mb-1">
                                            <button type="button" class="btn btn-warning btn-xs" @click="showSearch()">
                                                <span v-if="!show_search">{{ $t('form.show_search') }}</span>
                                                <span v-if="show_search">{{ $t('form.hide_search') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-xs " @click="showSearch()">
                                                <i v-if="show_search" class="fas fa-angle-up"></i>
                                                <i v-else class="fas fa-angle-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input v-model="form_filter.search" @change="fetchTransaction" type="search"
                                            class="form-control rounded-pill"
                                            placeholder="Nhập và vui lòng nhấn enter để tìm kiếm...">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="row" v-if="show_search"
                    style="background-color:rgb(244, 246, 249);border-radius: 5px;box-shadow: 1px 1px 5px lightgrey;">
                    <div class="col-md-12">
                        <div class="form-group mt-2 row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-sticky-note"></i> <span
                                    class="text-secondary">Chính sách: </span></label>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm mt-1" v-model="trans_type">
                                    <!-- <option value="" selected>All</option> -->
                                    <option value="1"> Bàn giao </option>
                                    <option value="2"> Thu hồi </option>
                                    <option value="3"> Thanh lý </option>

                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-cube"></i> <span
                                    class="text-secondary">Kho hàng: </span></label>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm mt-1 " title="Tìm kiếm"
                                    v-model="form_filter.asset_warehouse_id">

                                    <option v-for="house in asset_warehouses" :value="house.id">
                                        {{
                                            house.name }}
                                    </option>
                                </select>
                            </div>
                            <!-- <label class="col-form-label-sm ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fas-xs fa-calendar-check"></i>
                                <span class="text-secondary">Ngày giao dịch: </span></label>
                            <div class="col-md-3">
                                <input class="form-control form-control-sm mt-1" type="date" v-model="created_at" />
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">

                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-user"></i> <span
                                    class="text-secondary">Người sử dụng: </span></label>

                            <div class="col-md-3">
                                <treeselect placeholder="Chọn người dùng" :disabled="edit" :disable-branch-nodes="true"
                                    :multiple="true" v-model="transaction_use" :options="list_user">
                                </treeselect>


                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label-sm ml-1 mt-1" style="text-align:left" for=""> <i
                                    class="fas fas-xs fa-calendar-check"></i>
                                <span class="text-secondary">Từ ngày: </span></label>
                            <div class="col-md-3">
                                <input class="form-control form-control-sm mt-1" type="date"
                                    v-model="form_filter.start_date" />
                            </div>
                            <label class="col-form-label-sm ml-1 mt-1" style="text-align:left" for=""> <i
                                    class="fas fas-xs fa-calendar-check"></i>
                                <span class="text-secondary">Đến ngày: </span></label>
                            <div class="col-md-3">
                                <input class="form-control form-control-sm mt-1" type="date"
                                    v-model="form_filter.end_date" />
                            </div>
                            <div class="col-md-1.5">
                                <button class="btn btn-warning btn-xs mt-2" @click="btn_filter()"> <i
                                        class="fas fa-search fas-sm"></i> Tìm kiếm </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class=col-md-3>
                                <button id="btn_refesh" type="reset" class="btn btn-outline-warning btn-xs mt-2  "
                                    @click="clearFilter()">
                                    Refresh
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="body">
                    <b-table  hover responsive :bordered="true"
                        :sticky-header="true" small :items="transaction.asset_transactions" :fields="fields">
                        <template #head(#)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>

                        <template #head(updated_at)=data>
                            <div class="text-center">
                                <span class="text-success">{{ data.label }}</span>
                            </div>
                        </template>

                        <template #cell(created_at)=data>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                style="margin-top:40px;" class="text-center hover-view">
                                <span>{{ data.item.created_at | formatDate }}</span>
                            </div>
                        </template>
                        <template #cell(date_transaction)=data>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                style="margin-top:40px;" class="text-center hover-view">
                                <span>{{ data.item.date_transaction | formatDate }}</span>
                            </div>
                        </template>
                        <template #head(department_id)=data>
                            <div class="text-center text-success">
                                <span>{{ data.label }}</span>
                            </div>
                        </template>
                        <template #cell(department_id)=data>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                class="text-center hover-view">
                                <span v-if="data.item.department_id">{{ data.item.department.name }}</span>
                            </div>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                v-if="data.item.department_id == null" class="text-center hover-view">
                                <i class="fas fa-landmark" style="font-size: 30px;opacity:0.5"></i><br>
                                <span class="badge bg-secondary font-weight-bold">Không có phòng ban</span>
                            </div>
                        </template>
                        <template #cell(updated_at)=data>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                style="margin-top:40px;" class="text-center hover-view" v-if="data.item.confirm == 1">
                                <span>{{ data.item.updated_at | formatDate }}</span>
                            </div>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                v-if="data.item.department_id !== null" class="text-center hover-view">
                                <i class="fas fa-calendar-times" style="font-size: 30px;margin-top:5px;opacity:0.5"></i><br>
                                <span class="badge bg-secondary font-weight-bold">Không có ngày xác nhận</span>
                            </div>
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                v-if="data.item.department_id == null && data.item.confirm == 3"
                                class="text-center hover-view">
                                <i class="fas fa-calendar-times" style="font-size: 30px;margin-top:5px;opacity:0.5"></i><br>
                                <span class="badge bg-secondary font-weight-bold">Không có ngày xác nhận</span>
                            </div>
                        </template>

                        <template v-slot:cell(#)=data>
                            {{ data.index + 1 }}
                        </template>
                        <template #head(trans_type)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #head(newtab)=data>
                            <div class="text-center">
                                <span class="text-success">{{ data.label }}</span>
                            </div>
                        </template>
                        <template #cell(trans_type)=data>
                            <span v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                class="hover-view">
                                <span v-if="data.item.trans_type == 1">
                                    <i class="fas fa-hands"
                                        style="font-size:20px;color:#1fc700;border-radius:30px;background:repeating-linear-gradient(110deg, gray, transparent 100px);"></i>
                                    <span class="text-success"><b>Bàn giao</b></span>
                                </span>
                                <span v-if="data.item.trans_type == 2">
                                    <i class="fas fa-hand-rock"
                                        style="font-size:20px;color:darkred;border-radius:30px;"></i>
                                    <span class="text-warning"><b>Thu hồi</b></span> </span>
                                <span v-if="data.item.trans_type == 3">
                                    <i class="fas fa-times-circle"
                                        style="font-size:20px;color:linen;background:darkred;border-radius:30px;"></i>
                                    <span class="text-danger"><b>Thanh lý</b></span>
                                </span>
                                <span v-if="data.item.trans_type == 4">
                                    <i class="fas fa-wrench icon-repair" style="font-size:20px;"></i>
                                    <span class="text-danger text-repair"><b>Sửa chữa</b></span>
                                    <p v-if="data.item.assetdable_id" class="font-weight-bold" style="margin-left:23px;"> {{
                                        data.item.assetdable.name }} </p>
                                </span>
                                <div v-if="data.item.trans_type !== 4" class="text-dark mt-2 " style="margin-left:23px;">
                                    <div v-for="(tes, index) in data.item.asset_transaction_items" :key="index">
                                        <span v-if="tes.objectable_id"><b> {{ tes.objectable.name }}:</b> </span>
                                        <span class="text-secondary"> {{ tes.quantity }} </span>
                                    </div>
                                    <!-- <div v-if="tes.objectable_type == 'App\\Models\\asset\\Asset'">
                                        <span><b>{{ $t(getAssets(tes.objectable_id)) }}:</b> </span>
                                        <span class="text-secondary"> {{ tes.quantity }} </span>
                                    </div> -->

                                </div>


                            </span>
                        </template>

                        <template #head(user_id)=data>
                            <div class="text-center">
                                <span class="text-success">{{ data.label }}</span>
                            </div>
                        </template>
                        <template #cell(user_id)="data">
                            <div @click="viewtype(data.item)" class="text-center hover-view" v-for="user in users"
                                :key="user.id">
                                <span v-if="user.id == data.item.user_id">
                                    <b-avatar :src="user.avatar"></b-avatar><br>
                                    <div style="display: inline-table;">
                                        <span> {{ user.name }} </span><br>
                                        <span v-if="data.item.confirm == 0" class="badge bg-danger font-weight-bold">
                                            Chưa xác nhận </span>
                                        <span v-if="data.item.confirm == 1" class="badge bg-success font-weight-bold">
                                            Đã xác nhận </span>
                                        <span v-if="data.item.confirm == 3" class="badge bg-warning font-weight-bold">
                                            Không cần xác nhận </span>
                                    </div>
                                </span>
                            </div>
                            <div @click="viewtype(data.item)" class="text-center hover-view"
                                v-if="data.item.user_id == null">
                                <i class="fas fa-user-alt-slash" style="font-size: 30px;margin-top: 5px;opacity:0.5"></i>
                                <br>
                                <span class="badge bg-secondary font-weight-bold">Không có người sử dụng</span>
                            </div>
                        </template>
                        <template #cell(create_by)="data">
                            <div v-b-tooltip.hover title="Nhấp vào xem chi tiết" @click="viewtype(data.item)"
                                class="text-center hover-view">
                                <span v-if="data.item.create_by">
                                    <b-avatar :src="data.item.create_by.avatar"></b-avatar><br>
                                    <span> {{ data.item.create_by.name }} </span>
                                </span>
                            </div>
                        </template>
                        <template #head(create_by)=data>
                            <div class="text-center">
                                <span class="text-success">{{ data.label }}</span>
                            </div>
                        </template>
                        <template #head(asset_warehouse_id)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #head(created_at)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template #head(date_transaction)=data>
                            <span class="text-success">{{ data.label }}</span>
                        </template>
                        <template v-slot:cell(newtab)=data>
                            <div class="my-4" style="text-align:center" v-if="data.item.trans_type == 1">
                                <button id="show_btn_cancel" class="btn btn-outline-danger"
                                    v-if="data.item.user_id !== null && data.item.confirm == 0"
                                    @click="DeleteChange(data.item.id)">Hủy giao
                                    dịch </button>
                                <button
                                    v-if="data.item.confirm == 1 || data.item.confirm == 0 && data.item.department_id !== null"
                                    @click="printRecord(data.item.id)" id="shadow_btn_my" class="btn btn-default"><i
                                        class="fas fa-print"></i> Print</button>
                                <!-- <button id="show_btn_cancel" class="btn btn-outline-danger"
                                                                v-if="data.item.department_id === null && data.item.user_id !== null && data.item.confirm == 0"
                                                                @click="DeleteChange(data.item.id)">Hủy giao dịch</button>  -->
                            </div>
                            <div class="text-center my-4 " v-if="data.item.trans_type == 2">
                                <button id="show_btn_cancel" class="btn btn-outline-danger"
                                    v-if="data.item.user_id !== null && data.item.confirm == 0"
                                    @click="DeleteRecovery(data.item.id)">Hủy giao
                                    dịch</button>
                                <!-- <button id="show_btn_cancel" class="btn btn-outline-danger"
                                                                v-if="data.item.department_id !== null && data.item.user_id === null"
                                                                @click="DeleteRecovery(data.item.id)">Thu hồi phòng ban</button> -->
                            </div>
                            <div class="text-center my-4 " v-if="data.item.trans_type == 3">
                                <button id="show_btn_cancel" class="btn btn-outline-danger"
                                    @click="DeleteCancels(data.item.id)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <div class="my-4" style="text-align:center" v-if="data.item.trans_type == 4">
                                <button id="show_btn_cancel" class="btn btn-outline-danger"
                                    v-if="data.item.user_id !== null && data.item.confirm == 0"
                                    @click="DeleteChange(data.item.id)">Hủy giao
                                    dịch </button>
                                <button
                                    v-if="data.item.confirm == 1 || data.item.confirm == 0 && data.item.department_id !== null"
                                    @click="printRecord(data.item.id)" id="shadow_btn_my" class="btn btn-default"><i
                                        class="fas fa-print"></i> Print</button>
                            </div>
                        </template>
                    </b-table>
                    <div class="text-center  " v-if="loading">
                        <i class="fad fa-spinner fa-pulse "
                            style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                        <br>
                        <span class="text-secondary font-weight-bold text-xs font-italic">Vui lòng chờ giây lát...</span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="margin">
                                <div class="btn-group">
                                <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                <b-form-select size="sm" v-model="perPage_transaction" :options="perPageOption_transaction"
                                    @change="fetchTransaction">

                                </b-form-select>
                                <!-- <span>{{currentPage}} of {{lastPage}}</span> -->
                                <b-pagination @input="fetchTransaction" v-model="paginate_transaction.current_page"
                                    :total-rows="paginate_transaction.total" :per-page="perPage_transaction"
                                    :last-page="paginate_transaction.last_page" pills class="ml-1"></b-pagination>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- popup -->
                    <div class="modal" id="addfields" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <form v-if="show_view == true">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label>XEM CHI TIẾT</label>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <p class="text-secondary font-weight-bold">Hình thức giao dịch:
                                            <span v-if="transaction.trans_type == 1"
                                                class="text-success font-weight-bold">Bàn giao
                                                <span v-if="transaction.user_id == null"> tài sản cho phòng ban
                                                </span>
                                                <span v-if="transaction.department_id == null"> tài sản cho người sử
                                                    dụng </span>
                                            </span>
                                            <span v-if="transaction.trans_type == 2"
                                                class="text-warning font-weight-bold">Thu hồi
                                                <span v-if="transaction.user_id == null"> tài sản phòng ban </span>
                                                <span v-if="transaction.department_id == null"> tài sản người sử
                                                    dụng </span>
                                            </span>
                                            <span v-if="transaction.trans_type == 3"
                                                class="text-danger font-weight-bold">Thanh lý
                                            </span>
                                            <span v-if="transaction.trans_type == 4"
                                                class="text-repair font-weight-bold text-uppercase">
                                                Sửa chữa tài sản
                                            </span>
                                        </p>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <p v-if="transaction.assetdable_id">
                                                    Tên tài sản:
                                                    <span class="font-weight-bold">{{ transaction.assetdable.name }}</span>
                                                </p>
                                                <p class="" v-if="transaction.trans_type == 1">
                                                    Người bàn giao:
                                                    <span class="font-weight-bold"> {{ transaction.create_by.name }}</span>
                                                </p>
                                                <p class="" v-if="transaction.trans_type == 2">Người thu hồi:
                                                    <span class="font-weight-bold">{{ transaction.create_by.name }}</span>
                                                </p>
                                                <p class="" v-if="transaction.trans_type == 3">Người thanh lý:
                                                    <span class="font-weight-bold">{{ transaction.create_by.name }}</span>
                                                </p>
                                                <p v-if="transaction.trans_type == 4">Người sửa chữa:
                                                    <span class="font-weight-bold">{{ transaction.create_by.name }}</span>
                                                </p>
                                                <p v-if="transaction.trans_type == 1">Ngày bàn giao:
                                                    <span class="font-weight-bold">{{ transaction.date_transaction |
                                                        formatDate }} </span>
                                                </p>
                                                <p v-if="transaction.trans_type == 2">Ngày thu hồi:
                                                    <span class="font-weight-bold">
                                                        {{ transaction.date_transaction | formatDate }}
                                                    </span>
                                                </p>
                                                <p v-if="transaction.trans_type == 3">Ngày thanh lý:
                                                    <span class="font-weight-bold">
                                                        {{ transaction.date_transaction | formatDate }}
                                                    </span>
                                                </p>
                                                <p v-if="transaction.trans_type == 4">Ngày sửa chữa:
                                                    <span class="font-weight-bold">
                                                        {{ transaction.date_transaction | formatDate }}
                                                    </span>
                                                </p>
                                                <p>Ngày tạo: <span class="font-weight-bold">{{ transaction.created_at |
                                                    formatDate }}</span> </p>
                                                <p>Ghi chú: <span class="font-weight-bold">{{ transaction.note }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-secondary font-weight-bold"
                                                    v-if="transaction.department_id == null">Người sử dụng: {{
                                                        $t(getuserName(transaction.user_id)) }} </p>
                                                <p v-if="transaction.department_id == null && transaction.user_id">Confirm:
                                                    <span v-if="transaction.confirm == 0" class="badge bg-danger">
                                                        Chưa xác nhận</span>
                                                    <span v-if="transaction.confirm == 1" class="badge bg-success">
                                                        Đã xác nhận</span>
                                                    <span v-if="transaction.confirm == 3" class="badge bg-warning">
                                                        Không cần xác nhận</span>
                                                </p>
                                                <p class="text-secondary font-weight-bold"
                                                    v-if="transaction.user_id == null">Phòng ban: {{
                                                        $t(GetDepartmentName(transaction.department_id)) }} </p>
                                                <p v-if="transaction.department_id == null && transaction.confirm == 1">
                                                    Ngày xác nhận: {{ transaction.updated_at | formatDate }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Danh sách
                                                <span v-if="transaction.trans_type == 1"
                                                    class="text-success font-weight-bold">Bàn giao</span>
                                                <span v-if="transaction.trans_type == 2"
                                                    class="text-warning font-weight-bold">Thu hồi</span>
                                                <span v-if="transaction.trans_type == 3"
                                                    class="text-danger font-weight-bold">Thanh lý</span>
                                                <span v-if="transaction.trans_type == 4"
                                                    class="text-repair font-weight-bold">Sửa chữa </span>
                                            </label>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>Tên tài sản</td>
                                                        <td>Loại</td>
                                                        <td
                                                            v-if="transaction.user_id == null && transaction.trans_type !== 3 && transaction.department_id !== null">
                                                            Phòng ban</td>
                                                        <td
                                                            v-if="transaction.department_id == null && transaction.trans_type !== 3 && transaction.user_id !== null">
                                                            Người sử dụng</td>
                                                        <td>Số lượng</td>
                                                        <td>Kho</td>
                                                        <td v-if="transaction.trans_type == 4">Nội dung mới</td>
                                                        <td v-if="transaction.trans_type == 4">Ghi chú</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in transaction.asset_transaction_items"
                                                        :key="index">
                                                        <td> {{ index + 1 }} </td>
                                                        <td>
                                                            <div v-if="item.objectable_id">
                                                                <span> {{ item.objectable.name }}
                                                                </span>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div
                                                                v-if="item.objectable_type == 'App\\Models\\asset\\AssetTool'">
                                                                <span>Công cụ dụng cụ</span>
                                                            </div>
                                                            <div v-if="item.objectable_type == 'App\\Models\\asset\\Asset'">
                                                                <span>Tài sản</span>
                                                            </div>
                                                        </td>
                                                        <td v-if="item.user_id == null && item.department_id"> {{
                                                            item.department.name }} </td>
                                                        <td v-if="item.department_id == null && item.user_id"> {{
                                                            item.user.name }} </td>
                                                        <td> {{ item.quantity }} </td>
                                                        <td v-if="item.asset_warehouse_id"> {{ item.asset_warehouse.name }}
                                                        </td>
                                                        <td v-if="item.history && transaction.trans_type == 4">{{
                                                            item.history.new_content }}</td>
                                                        <td v-if="item.history && transaction.trans_type == 4"> {{
                                                            item.history.description }} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- popup-->
        <div class="modal " id="addstockAsset" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">HÌNH THỨC GIAO DỊCH TÀI SẢN - CÔNG CỤ DỤNG CỤ</h4>

                                <!-- <h4 v-if="edit"> Cập Nhật công cụ dụng cụ</h4> -->
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="card" id="card_transaction" @click="view()" style="cursor:pointer">
                                            <div class="card-body" id="item_card_transaction">
                                                <div class="bod">
                                                    <div style="text-align:center;">
                                                        <span><a @click="view()">
                                                                <i class="fas fa-hands"
                                                                    style="font-size:55px;color:#1fc700;border-radius:30px;background:repeating-linear-gradient(110deg, gray, transparent 100px);"></i></a></span>

                                                    </div>
                                                    <div style="text-align:center;font-size:15px;margin-top:5px">
                                                        <span class="font-weight-bold text-success">Bàn
                                                            giao</span><br><span class="text-secondary">Tài sản - Công
                                                            cụ dụng cụ</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card" id="card_recovery" @click="showAssetStockthuhoi()"
                                            style="cursor:pointer">
                                            <div class="card-body" id="item_card_recovery">
                                                <div class="bod">
                                                    <div style="text-align:center;"> <span><a
                                                                @click="showAssetStockthuhoi()"><i class="fas fa-hand-rock"
                                                                    style="font-size:55px;color:darkred;border-radius:30px;"></i></a></span>

                                                    </div>
                                                    <div style="text-align:center;font-size:15px;margin-top:5px">
                                                        <span class="font-weight-bold text-warning">Thu
                                                            hồi</span><br><span class="text-secondary">Tài sản - Công cụ
                                                            dụng cụ</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card" id="card_cancel" @click="ShowAssetCancel()"
                                            style="cursor:pointer">
                                            <div class="card-body" id="item_card_cancel">
                                                <div class="bod">
                                                    <div style="text-align:center;"> <span><a @click="ShowAssetCancel()"><i
                                                                    class="fas fa-times-circle"
                                                                    style="font-size:55px;color:linen;background:darkred;border-radius:30px;"></i></a></span>

                                                    </div>
                                                    <div style="text-align:center;font-size:15px;margin-top:5px">
                                                        <span class="font-weight-bold text-danger">Thanh lý</span><br><span
                                                            class="text-secondary">Tài sản - Công cụ dụng cụ</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-3">
                                                               <div class="card">
                                                                   <div class="card-body">
                                                                       <div class="bod">
                                                                           <div style="text-align:center;"> <span><a
                                                                                       @click="showAssetStockrepair()"><i
                                                                                           class="fas fa-sync-alt"
                                                                                           style="font-size:55px;color:green;border-radius:30px;"></i></a></span>
                                                                           </div>
                                                                           <div style="text-align:center;font-size:15px;margin-top:5px">
                                                                               <span>Phục hồi (Chỉ tài sản)</span>
                                                                           </div>
                                                                       </div>
                                                                   </div>
                                                               </div>
                                                           </div> -->

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <!-- <div class="col-sm-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="bod">
                                                    <div style="text-align:center;"> <span><a
                                                                @click="showAssetStockrepair()"><i
                                                                    class="fas fa-question-circle"
                                                                    style="font-size:55px;color:lightgoldenrodyellow;background:orangered;border-radius:30px;"></i></a></span>
                                                    </div>
                                                    <div style="text-align:center;font-size:15px;margin-top:5px">
                                                        <span>Mất (Chỉ tài sản)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-sm-4">
                                        <div class="card" id="card_repair" style="cursor:pointer" @click="pageAddRepair()">
                                            <div class="card-body" id="item_card_transaction">
                                                <div class="bod">
                                                    <div style="text-align:center;"> <span><a><i class="fas fa-wrench"
                                                                    style="font-size:55px;background: linear-gradient(#fd7e14, rgb(205 6 0));-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i></a></span>
                                                    </div>
                                                    <div class="text-center" style="font-size:15px;margin-top:5px">
                                                        <span class="font-weight-bold"
                                                            style="background: linear-gradient(#fd7e14, rgb(205 6 0));-webkit-background-clip: text;-webkit-text-fill-color: transparent;">Sửa
                                                            chữa
                                                        </span><br><span class="text-secondary">Tài sản</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="bod">
                                                    <div style="text-align:center;"> <span><a
                                                                @click="showAssetStockrepair()"><i
                                                                    class="fas fa-heart-broken"
                                                                    style="font-size:55px;color:darkred;border-radius:30px;"></i></a></span>
                                                    </div>
                                                    <div style="text-align:center;font-size:15px;margin-top:5px">
                                                        <span>Hỏng (Chỉ tài sản)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- popup thu hồi-->

        <div class="modal fade " id="closestockAsset" tabindex="-1" role="dialog" style="overflow-y:scroll">
            <div class="modal-dialog modal-xl ">
                <div class="modal-content">
                    <form @submit.prevent="delay" @keydown="clearAllError">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">THU HỒI TÀI SẢN - CÔNG CỤ DỤNG CỤ</h4>
                                <h4 v-if="edit"> Thu hồi tài sản </h4>
                                <!-- <h4 v-if="edit"> Cập Nhật công cụ dụng cụ</h4> -->
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="demo" style="color:red;display:none"></div>
                            <div class="form-group">

                                <input type="checkbox" v-model="transaction.check" /> <span>Thu hồi phòng ban</span>
                            </div>
                            <div class="form-group" v-if="transaction.check == false">
                                <label>Người dùng</label> <small class="text-danger"> (*) </small>
                                <select class="form-control" v-model="transaction.user_id" v-if="edit" disabled>
                                    <option v-for="userr in evenNumbers" :key="userr" v-bind:value="userr">
                                        {{ $t(getuserName(userr)) }} </option>
                                </select>
                                <select class="form-control" v-model="transaction.user_id" v-if="!edit">
                                    <option v-for="userr in evenNumbers" :key="userr" v-bind:value="userr">
                                        {{ $t(getuserName(userr)) }} </option>
                                </select>
                            </div>
                            <div class="form-group" v-if="transaction.check == true">
                                <label>Phòng ban </label><small class="text-danger"> (*) </small>
                                <select class="form-control" v-model="transaction.department_id">
                                    <option v-for="(department, index) in transaction_department" :key="index"
                                        :value="department"> {{ $t(GetDepartmentName(department)) }} </option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea v-model="transaction.note" class="form-control" rows="3" v-if="edit" disabled
                                    placeholder="Lý do" v-bind:class="hasError('note') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('note') }}</strong>
                                </span>
                                <textarea v-model="transaction.note" class="form-control" rows="3" v-if="!edit"
                                    placeholder="Lý do" v-bind:class="hasError('note') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('note') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Ngày thu hồi</label> <small class="text-danger">(*)</small>
                                <input type="date" class="form-control" v-model="transaction.date_transaction" />
                            </div>
                            <div class="form-group">
                                <button v-if="transaction.check == false" class="btn btn-outline-primary  "
                                    style="margin-top:10px;" @click.prevent="ShowRecovery()"> Danh sách đã bàn
                                    giao của
                                    <span><b>{{ $t(getuserName(transaction.user_id)) }} </b></span>
                                </button>
                                <button v-if="transaction.check == true" class="btn btn-outline-primary  "
                                    style="margin-top:10px;" @click.prevent="ShowRecovery()"> Danh sách đã bàn
                                    giao của
                                    <span><b> phòng ban {{ $t(GetDepartmentName(transaction.department_id)) }}
                                        </b></span>
                                </button>
                                <div class="form-group" style="box-shadow: 1px 1px 5px slategrey;">
                                    <b-table small hover responsive headVariant :items="transaction.asset_transaction_items"
                                        :sticky-header="true" :fields="fields_recovery" id="table">

                                        <template #cell(#)="data">
                                            <span> {{ data.index + 1 }} </span>
                                        </template>
                                        <template #cell(objectable_id)="data">
                                            <div>
                                                <span><b> {{ data.item.name }}</b>
                                                </span>

                                            </div>
                                        </template>
                                        <template #cell(department_id)="data">
                                            <div>
                                                <span> {{ $t(GetDepartmentName(data.item.department_id)) }} </span>
                                            </div>
                                        </template>
                                        <template #head(asset_warehouse_id)="data">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </template>
                                        <template #head(user_id)="data">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </template>
                                        <template #cell(user_id)="data">
                                            <div>
                                                <span> {{ $t(getuserName(data.item.user_id)) }} </span>
                                            </div>
                                        </template>
                                        <template #head(department_id)="data">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </template>

                                        <template #cell(asset_warehouse_id)="data">
                                            <div class="float-left">
                                                <i class="fas fa-cube mr-2 "> </i>
                                            </div>
                                            <span>
                                                {{ data.item.asset_warehouse_name }} </span>
                                        </template>
                                        <template #cell(quantity)="data">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="bg bg-secondary text-center w-25 mx-auto "
                                                        v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'"
                                                        style="font-weight:700;margin-top:3px;"> {{
                                                            data.item.quantity
                                                        }}
                                                    </div>
                                                    <div class="bg text-center  "
                                                        v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'"
                                                        style="font-weight:700;margin-top:3px;">
                                                        <input class="w-auto rounded-pill text-center " id="quantity"
                                                            name="quantity" v-model="data.item.quantity">


                                                    </div>

                                                </div>
                                            </div>
                                        </template>
                                        <template #head(quantity)="data">
                                            <div class="text-center">
                                                <span class="text-success"> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <template #head(#)="data">
                                            <div>
                                                <span class="text-success"> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <template #head(objectable_id)="data">
                                            <div>
                                                <span class="text-success"> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <template #head(action)="data">
                                            <div class="text-center">
                                                <span class="text-success"> {{ data.label }} </span>
                                            </div>

                                        </template>

                                        <template #cell(action)="data">
                                            <div class="text-center">
                                                <button @click.prevent="DeleteItems(data.item, data.index)"
                                                    id="show_btn_cancel" class="btn btn-outline-danger btn-sm"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </template>
                                    </b-table>
                                </div>
                            </div>
                            <div class="form-group" v-if="transaction.check == false">
                                <input @click="show_user_confirm()" type="checkbox" id="check" class="checkbox"
                                    v-model="transaction.confirm" :value="3">
                                <span id="text"> Người dùng không cần xác nhận</span>
                            </div>
                            <div class="form-group">
                                <div class="modal fade " id="recovery" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <form @submit.prevent="Addetail">
                                                <div class="modal-header">
                                                    <div class="modal-title">
                                                        <h4>{{ $t(getuserName(transaction.user_id)) }} </h4>
                                                    </div>
                                                    <button type="button" class="close" @click.prevent="Close_Recovery()"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <b-table small hover responsive headVariant sticky-header="400px"
                                                            :items="list_transaction_asset_user_id" :fields="noidung"
                                                            id="table">
                                                            <template #head(date_transaction)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #cell(date_transaction)="data">
                                                                <div class="text-center">
                                                                    <span> {{ data.item.date_transaction | formatDate }}
                                                                    </span>
                                                                </div>

                                                            </template>
                                                            <template #head(#)=data>
                                                                <span class="text-success">{{ data.label }}</span>
                                                            </template>
                                                            <template #cell(#)=data>
                                                                <span>{{ data.item.id }}</span>
                                                            </template>
                                                            <template #cell(department_id)="data">
                                                                <div>
                                                                    <span v-if="data.item.department_id">
                                                                        {{ data.item.department.name }}
                                                                    </span>
                                                                </div>
                                                            </template>
                                                            <template #head(objectable_id)=data>
                                                                <div class="text">
                                                                    <span class="text-success">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #head(asset_warehouse_id)="data">
                                                                <span class="text-success"> {{ data.label }} </span>
                                                            </template>
                                                            <template #cell(asset_warehouse_id)="data">
                                                                <i class="fas fa-cube mr-2"></i> <span
                                                                    v-if="data.item.asset_warehouse_id"> {{
                                                                        data.item.asset_warehouse.name
                                                                    }}
                                                                </span>
                                                            </template>
                                                            <template #head(department_id)="data">
                                                                <span class="text-success"> {{ data.label }} </span>
                                                            </template>
                                                            <template #cell(objectable_id)="data">
                                                                <div class="text-dark" style="margin-top:3px;">
                                                                    <div
                                                                        v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">
                                                                        <span v-if="data.item.objectable_id"><b> {{
                                                                            data.item.objectable.name
                                                                        }}</b>
                                                                        </span>

                                                                    </div>
                                                                    <div
                                                                        v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">
                                                                        <span v-if="data.item.objectable_id">
                                                                            <b>{{ data.item.objectable.name }}</b>
                                                                        </span>

                                                                    </div>

                                                                </div>
                                                            </template>


                                                            <template #head(quantity)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #cell(quantity)=data>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="bg bg-secondary text-center w-25 mx-auto "
                                                                            style="font-weight:700;margin-top:3px;">
                                                                            <span> {{ data.item.quantity }} </span>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </template>
                                                            <template #head(action)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #cell(action)="data">
                                                                <div class="text-center" style="margin-top:1px">
                                                                    <input type="checkbox" id="cb" class="checkbox"
                                                                        v-model="item_transaction" :value="data.item" />
                                                                </div>
                                                            </template>
                                                        </b-table>
                                                    </div>

                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="submit" class="btn btn-primary" style="width:30%;">
                                                        Lưu
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="button" @click="clear()" class="btn btn-default" data-dismiss="modal"
                                style="width:47%;">
                                Hủy bỏ
                            </button>
                            <button id="submit_recovery" type="submit" class="btn btn-danger" style="width:47%;">
                                Thu hồi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- popup hủy bỏ-->
        <div class="modal fade" id="cancel" tabindex="-1" role="dialog" style="overflow-y:scroll">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form @submit.prevent="delayCancel" @keydown="clearAllError">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4>THANH LÝ TÀI SẢN/CÔNG CỤ DỤNG CỤ</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea v-model="transaction.note" class="form-control" rows="3" placeholder="Lý do"
                                    v-bind:class="hasError('note') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('note') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Ngày thanh lý</label> <small class="text-danger">(*)</small>
                                <input type="date" class="form-control" v-model="transaction.date_transaction" />
                            </div>
                            <div class="form-group">
                                <div class="text-right mt-2 mb-2 ">
                                    <b-dropdown v-if="!edit" id="dropdown-1" right text="Danh sách"
                                        variant="outline-primary">
                                        <b-dropdown-item @click.prevent="ShowCancel()"> Tài sản</b-dropdown-item>
                                        <b-dropdown-item @click.prevent="ShowCancel_tool()">Công cụ dụng cụ
                                        </b-dropdown-item>
                                    </b-dropdown>
                                </div>
                            </div>
                            <div class="form-group" style="box-shadow: 1px 1px 5px slategrey;">
                                <b-table small hover headVariant :items="transaction.asset_transaction_items"
                                    :sticky-header="true" :fields="fields_cancel" id="table">

                                    <template #cell(#)="data">
                                        <span> {{ data.index + 1 }} </span>
                                    </template>
                                    <template #cell(objectable_id)="data">
                                        <div v-if="data.item.type == 1">
                                            <span><b> {{ data.item.name }}</b>
                                            </span>
                                        </div>
                                        <!-- <div v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">
                                            <span><b> {{ $t(getAssetTool(data.item.objectable_id)) }}</b>
                                            </span>
                                        </div> -->

                                        <div v-if="data.item.type == 0">
                                            <span><b>{{ data.item.name }}</b>
                                            </span>

                                        </div>
                                        <!-- <div v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">
                                            <span><b>{{ $t(getAssets(data.item.objectable_id)) }}</b>
                                            </span>

                                        </div> -->
                                    </template>

                                    <template #cell(quantity)="data">
                                        <!-- <div class="row">
                                                                   <div class="col-sm-12">
                                                                       <div class="bg bg-secondary text-center"
                                                                           v-if="data.item.type == 0"
                                                                           style="font-weight:700;margin-top:3px;"> {{ data.item.quantity }}
                                                                       </div>
                                                                       <div class="bg bg-secondary text-center"
                                                                           v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'"
                                                                           style="font-weight:700;margin-top:3px;"> {{ data.item.quantity }}
                                                                       </div>
                                                                      

                                                                   </div>
                                                               </div> -->
                                        <div class="row">
                                            <div class="col-sm-12">

                                                <div class="text-center mt-1 " v-if="data.item.type == 1">
                                                    <input class="w-auto rounded-pill text-center  " id="quantity"
                                                        name="quantity" style="margin:0 auto;width:50%;"
                                                        v-model="data.item.quantity" />

                                                </div>
                                                <div class="mt-1" v-if="data.item.type == 0">
                                                    <div class="bg bg-secondary rounded-pill w-50"
                                                        style="text-align:center;margin:0 auto">
                                                        <span> {{ data.item.quantity }} </span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </template>
                                    <template #head(quantity)="data">
                                        <div class="text-center">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(#)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(asset_status_id)="data">
                                        <div class="text-center">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #cell(asset_status_id)="data">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="bg bg-success text-center"
                                                    style="font-weight:700;margin-top:3px"
                                                    v-if="data.item.asset_status_id == 1">Tốt</div>
                                                <div class="bg bg-danger text-center" style="font-weight:700;margin-top:3px"
                                                    v-if="data.item.asset_status_id == 3">Thanh lý</div>
                                                <div v-if="data.item.asset_status_id == 4"
                                                    class="bg text-repair-item font-weight-bold text-sm p2 text-center">
                                                    Đang sửa chữa </div>
                                                <div class="bg bg-danger text-center" style="font-weight:700;margin-top:3px"
                                                    v-if="data.item.asset_status_id == 9">Xấu</div>
                                            </div>
                                        </div>
                                    </template>
                                    <template #head(objectable_id)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                </b-table>
                            </div>
                            <div class="form-group">
                                <div class="modal fade" id="itemcancel" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <form @submit.prevent="AddCancel">
                                                <div class="modal-header">
                                                    <div class="modal-title">
                                                        <h4>Danh sách tài sản </h4>
                                                    </div>
                                                    <button type="button" class="close" @click.prevent="Close_Cancel()"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <b-table small responsive hover headVariant :items="thanhly"
                                                            :fields="cancel" id="table">

                                                            <template #head(#)=data>
                                                                <span class="text-success">{{ data.label }}</span>
                                                            </template>
                                                            <template #cell(#)=data>
                                                                <span>{{ data.index + 1 }}</span>
                                                            </template>
                                                            <template #head(objectable_id)=data>
                                                                <div class="text">
                                                                    <span class="text-success">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #cell(name)="data">
                                                                <div style="margin-top:3px;">
                                                                    <span> {{ data.item.name }} </span>
                                                                </div>
                                                            </template>
                                                            <template #head(quantity)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #head(name)="data">
                                                                <span class="text-success"> {{ data.label }} </span>
                                                            </template>
                                                            <template #head(asset_status_id)="data">
                                                                <div class="text-center">
                                                                    <span class="text-success"> {{ data.label }} </span>
                                                                </div>
                                                            </template>
                                                            <template #cell(asset_status_id)="data">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="bg bg-success text-center"
                                                                            style="font-weight:700;margin-top:3px;"
                                                                            v-if="data.item.asset_status_id == 1">Tốt
                                                                        </div>
                                                                        <div class="bg bg-danger text-center"
                                                                            style="font-weight:700;margin-top:3px;"
                                                                            v-if="data.item.asset_status_id == 3">Thanh lý
                                                                        </div>
                                                                        <p v-if="data.item.asset_status_id == 4"
                                                                            class="bg text-repair-item font-weight-bold text-sm p2 text-center">
                                                                            Đang sửa chữa </p>
                                                                        <div class="bg bg-danger text-center"
                                                                            style="font-weight:700;margin-top:3px;"
                                                                            v-if="data.item.asset_status_id == 9">Xấu
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template #cell(quantity)=data>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="bg bg-secondary text-center"
                                                                            style="font-weight:700;margin-top:3px;">
                                                                            {{
                                                                                data.item.quantity
                                                                            }} </div>
                                                                    </div>
                                                                </div>

                                                            </template>
                                                            <template #head(action)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #cell(action)="data">
                                                                <div class="text-center" style="margin-top:2px">
                                                                    <input type="checkbox" id="cb" class="checkbox"
                                                                        v-model="item_transaction" :value="data.item" />
                                                                </div>
                                                            </template>
                                                        </b-table>
                                                        <div class="text-center " v-if="loading">
                                                            <div class="position-loading ">
                                                                <i class="fad fa-spinner fa-pulse "
                                                                    style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                                                                <br>
                                                                <span
                                                                    class="text-secondary font-weight-bold text-xs font-italic">Vui
                                                                    lòng chờ giây lát...</span>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row">
                                                                    <label class="col-form-label-sm col-md-2"
                                                                        style="text-align: left" for="">Per
                                                                        page:</label>
                                                                    <div class="col-md-3">
                                                                        <b-form-select size="sm" v-model="perPage_thanhly"
                                                                            :options="perPageOptions_thanhly"
                                                                            @change="fetchThanhly">
                                                                        </b-form-select>
                                                                    </div>
                                                                    <label class="col-form-label-sm col-md-1"
                                                                        style="text-align: left" for=""></label>
                                                                    <div class="col-md-3">
                                                                        <b-pagination @input="fetchThanhly"
                                                                            v-model="paginate_assets_thanhly.current_page"
                                                                            :total-rows="paginate_assets_thanhly.total"
                                                                            :per-page="perPage_thanhly"
                                                                            :last-page="paginate_assets_thanhly.last_page"
                                                                            pills class="ml-1"></b-pagination>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="submit" class="btn btn-primary" style="width:30%;">
                                                        Lưu
                                                    </button>
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal"
                                                                               style="width:47%;">
                                                                               Đóng
                                                                           </button> -->

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade " id="itemcanceltool" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <form @submit.prevent="AddCancel">
                                                <div class="modal-header">
                                                    <div class="modal-title">
                                                        <div class="modal-title">
                                                            <h4>Danh sách CCDC </h4>
                                                        </div>
                                                        <!-- <h4>{{ $t(getuserName(transaction.user_id)) }} </h4> -->
                                                    </div>
                                                    <button type="button" class="close" @click.prevent="Close_Cancel()"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <b-table small hover responsive headVariant :items="asset_tools"
                                                            :fields="cancel" id="table">

                                                            <template #head(#)=data>
                                                                <span class="text-success">{{ data.label }}</span>
                                                            </template>
                                                            <template #cell(#)=data>
                                                                <span>{{ data.item.id }}</span>
                                                            </template>

                                                            <template #cell(name)="data">
                                                                <div style="margin-top:3px;">
                                                                    <span> {{ data.item.name }} </span>
                                                                </div>
                                                            </template>
                                                            <template #head(quantity)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <template #head(name)="data">
                                                                <span class="text-success"> {{ data.label }} </span>
                                                            </template>
                                                            <template #head(asset_status_id)="data">
                                                                <div class="text-center">
                                                                    <span class="text-success"> {{ data.label }} </span>
                                                                </div>
                                                            </template>
                                                            <template #cell(asset_status_id)="data">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="bg bg-success text-center"
                                                                            style="font-weight:700;margin-top:3px;"
                                                                            v-if="data.item.asset_status_id == 1">Tốt
                                                                        </div>
                                                                        <div class="bg bg-danger text-center"
                                                                            style="font-weight:700;margin-top:3px;"
                                                                            v-if="data.item.asset_status_id == 3">Thanh lý
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template #cell(quantity)=data>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="bg bg-secondary text-center"
                                                                            style="font-weight:700;margin-top:3px;">
                                                                            {{
                                                                                data.item.quantity
                                                                            }} </div>
                                                                    </div>
                                                                </div>

                                                            </template>
                                                            <template #head(action)=data>
                                                                <div class="text-center">
                                                                    <span class="text-success ">{{ data.label }}</span>
                                                                </div>
                                                            </template>
                                                            <!-- <template #cell(action)="data">
                                                                                       <div class="text-center" style="margin-top:2px">
                                                                                           <input type="checkbox" id="cb"
                                                                                               v-model="item_transaction" :value="data.item" />
                                                                                       </div>
                                                                                   </template> -->
                                                            <template #cell(action)="data">
                                                                <div class="text-center" style="margin-top:2px">
                                                                    <input type="checkbox" id="cb" class="checkbox"
                                                                        v-model="item_transaction" :value="data.item" />
                                                                </div>
                                                            </template>
                                                        </b-table>
                                                        <div class="text-center overlay " v-if="loading">
                                                            <i
                                                                class="fa fa-spinner fa-spin fa-4x fa-fw text-success center position-loading"></i>
                                                            <span class="sr-only text-white">Vui lòng chờ giây lát...</span>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="margin">
                                                                    <div class="btn-group">
                                                                        <label
                                                                            class="col-form-label-sm text-nowrap mr-1">Per
                                                                            page: </label>
                                                                        <b-form-select size="sm" v-model="perPage"
                                                                            :options="perPageOptions"
                                                                            @change="fetchAssetTools">
                                                                        </b-form-select>
                                                                        <b-pagination @input="fetchAssetTools"
                                                                            v-model="paginate_stocks.current_page"
                                                                            :total-rows="paginate_stocks.total"
                                                                            :per-page="perPage"
                                                                            :last-page="paginate_stocks.last_page" pills
                                                                            class="ml-1"></b-pagination>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="submit" class="btn btn-primary" style="width:30%;">
                                                        Lưu
                                                    </button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-success" style="width:47%;" :disabled="disabled">
                                Lưu lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Form import Excel-->
        <div class="modal" tabindex="-1" role="dialog" id="import_excel">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-import-excel">
                            <input type="file" ref="fileInput" @change="handleFileChange" />
                            <p class="mx-sm-auto" v-if="demo !== ''" style="position:relative"><i
                                    class="fas fa-file-contract mt-4" style="font-size:30px"></i><br>File:
                                {{ demo }}
                                <span class="text-danger"
                                    style="position:absolute;top:0;right:10px;font-size:25px;cursor:pointer;"
                                    @click="clearFileInput()"><i class="fas fa-times-circle"></i></span>
                            </p>
                            <p class="mx-sm-auto" v-else><i class="fas fa-file-contract mt-4"
                                    style="font-size:30px"></i><br> Thêm file
                                Excel <br><span class="text-secondary" style="font-size:10px">hoặc kéo và thả</span></p>
                            <div class="text-right update_data_excel" style="position:absolute;top:5px;right:5px;">
                                <button v-if="demo !== ''" class="btn btn-outline-info btn-xs" @click="showDataExcel()"><i
                                        class="fas fa-eye"></i> Xem dữ liệu Excel</button> <br>
                                <button v-if="demo !== ''" class="btn btn-outline-warning btn-xs"
                                    @click="chooseNewFile()"><i class="fas fa-edit"></i> Thay đổi file</button>
                            </div>
                        </div>
                        <div class="text-center mt-1">
                            <button v-if="demo !== ''" type="button" class="btn btn-success" @click="uploadFiles()"><i
                                    class="fas fa-upload"></i> Upload</button>
                        </div>
                        <div class="form-group table-responsive">
                            <label>Thuộc tính đã chọn: </label>
                            <a class="float-right" style="cursor:pointer" @click="exportExcel"> <i
                                    class="fas fa-download"></i> <u>Dowload templates Excel</u> </a>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr class="bg-dark font-weight-bold">
                                        <td class="px-md-1" v-for="field_excel in selected_fields">{{
                                            field_excel }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-secondary font-italic">
                                        <td v-for="data_excel in data"> {{ data_excel.value }} <span class="text-danger">
                                                ({{ data_excel.label }}) </span> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <label>Chọn cấu hình File <span class="text-success"> Excel</span></label>
                        <div class="text-secondary item-colum item mt-1" v-for="(prop, index) in properties" :key="index"
                            style="position:relative">
                            <div>
                                <span> <i class="fas fa-bars mr-2" style="opacity:0.5;color:forestgreen;"></i> <b>{{ index +
                                    1 }}</b> . {{ prop.name }}
                                    <span class="text-danger font-weight-bold" v-if="prop.disabled == true"
                                        style="font-size:10px"> (bắt buộc) </span>
                                    <span class="text-primary font-weight-bold" v-if="prop.fromAssetFields == true"
                                        style="font-size:10px"> (thuộc tính mở rộng) </span>
                                </span>
                            </div>
                            <div style="position:absolute;right:10px;top:15px">
                                <input type="checkbox" v-model="selected_fields" :value="prop.name"
                                    :disabled="prop.disabled" style="transform: scale(1.6);" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="resetDataExcel()" type="button" class="btn btn-outline-secondary"
                            data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Data Excel-->
        <div class="modal " tabindex="-1" role="dialog" id="data_excel">
            <div class="modal-dialog fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xem chi tiết</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Ghi chú: </label><br>
                            <label>1. Bàn giao cho người sử dụng (<small class="text-danger font-italic">Vui lòng để trống
                                    cột 'Phòng ban và Công ty'</small>).</label>
                            <br> <label>2. Bàn giao cho Phòng ban (<small class="text-danger font-italic">Vui lòng để trống
                                    cột 'Mã số nhân viên và Người sử dụng'</small>).</label>
                        </div>
                        <label>Danh sách Excel đã thêm vào: <i class="fas fa-sort-amount-down text-success"></i></label>
                        <div v-if="demo !== ''" type="button" class="btn bg-gradient-success float-right css-upload-excel"
                            style="position:sticky;top:10px;z-index:1;border-radius: 50%;" @click="uploadFiles()">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover ">
                                <thead>
                                    <tr class="bg-dark font-weight-bold">
                                        <th>#</th>
                                        <th v-for="column in columnss">{{ column }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, rowIndex) in rowss" :key="rowIndex">
                                        <td class="font-weight-bold text-secondary">{{ rowIndex + 1 }}</td>
                                        <td v-for="(cell, cellIndex) in row" :key="cellIndex">{{ cell }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import * as XLSX from 'xlsx';
import axios from 'axios';
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
            per_pages: 5,
            current_pages: 1,
            pageOptionss: [5, 10, 50, 100, 500, { value: this.row_asset, text: "All" }],
            list_user: [],
            sortBy: 'id',
            sortDesc: true,
            fields: [
                {
                    key: '#',
                    label: '#'
                },
                {
                    key: 'trans_type',
                    label: 'Hành động',
                    class: "text-nowrap",
                },
                {
                    key: 'date_transaction',
                    label: 'Ngày giao dịch',
                    class: "text-nowrap",
                },


                {
                    key: 'department_id',
                    label: 'Phòng ban',
                    class: "text-nowrap",
                },
                {
                    key: 'user_id',
                    label: 'Người sử dụng',
                    class: "text-nowrap",
                },
                {
                    key: 'create_by',
                    label: 'Người tạo',
                    class: "text-nowrap",
                },
                {
                    key: 'updated_at',
                    label: 'Ngày xác nhận',
                    class: "text-nowrap",
                },
                {
                    key: 'created_at',
                    label: 'Ngày tạo',
                    class: "text-nowrap",
                },
                {
                    key: 'newtab',
                    label: 'Nhấp chọn',
                    class: "text-nowrap",
                }
            ],
            noidung: [
                {
                    key: '#',
                    label: 'ID'
                },
                {
                    key: 'objectable_id',
                    label: 'Tên',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_warehouse_id',
                    label: 'Kho hàng',
                    class: "text-nowrap",
                },
                {
                    key: 'department_id',
                    label: 'Phòng ban',
                    class: "text-nowrap",
                },
                {
                    key: 'date_transaction',
                    label: 'Ngày bàn giao mới nhất',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng bàn giao',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                }
            ],
            cancel: [
                {
                    key: '#',
                    label: 'ID'
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                }
            ],
            fields_recovery: [
                {
                    key: '#',
                    label: '#',
                    class: "text-nowrap",
                },
                {
                    key: 'objectable_id',
                    label: 'Tên',
                    class: "text-nowrap",
                },
                {
                    key: 'user_id',
                    label: 'Người sử dụng',
                    class: "text-nowrap",
                },
                {
                    key: 'department_id',
                    label: 'Phòng ban',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_warehouse_id',
                    label: 'Kho hàng',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng thu hồi',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                }
            ],
            fields_cancel: [
                {
                    key: '#',
                    label: '#',

                },
                {
                    key: 'objectable_id',
                    label: 'Tên',
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng',
                    stickyColumn: true,
                    class: "text-nowrap",
                },
            ],
            transaction: {
                id: '',
                user_id: '',
                create_by: '',
                asset_policy_id: '',
                note: '',
                updated_at: '',
                created_at: '',
                date_transaction: '',
                check: false,
                department_id: "",
                assetdable: {},
                assetdable_id: '',
                index: '',
                confirm: '',
                asset_transactions: [],
                asset_transaction_items: [],
                asset_transaction_items_del: [],
            },
            asset_transaction_item: {
                id: '',
                asset_transaction_id: '',
                objectable_id: '',
                objectable_type: '',
                asset_warehouse_id: '',
                department_id: '',
                user_id: '',
                quantity: '',
                asset_status_id: '',
                name: '',
                asset_warehouse_name: '',
                index: ''
            },
            token: '',
            assets: [],
            asset_tools: [],
            asset_policies: [],
            errors: {},
            message: {},
            item_transaction: [],
            edit: false,
            show: false,
            show_search: false,
            dupacity: false,
            counter: 0,
            action: 0,
            trans_type: '',
            selected: [],
            selectAll: false,
            loading: false,
            huybo: false,
            users: [],
            asset_warehouses: [],
            asset_uses: [],
            form_filter: {
                asset_warehouse_id: '',
                start_date: '',
                end_date: '',
                search: '',
            },
            transaction_use: [],
            created_at: '',
            url_asset_transactions: "/api/asset/transaction",
            url_asset_get_transaction: "/api/asset/get-transaction",
            page_url_users: "api/user/allnew",
            page_url_usersnew: "api/user/allnew",
            page_url_department: "/api/category/departments",
            page_url_stockPage: "api/asset/stockPage",
            url_asset_assets: "api/asset/assetPage",
            url_asset_recovery: "api/asset/recovery",
            url_asset_policies: "/api/asset/policy",
            url_asset_use: "api/asset/waiting",
            url_asset_cancel: "api/asset/cancel",
            url_asset_thanhly: "api/asset/thanhly",
            thanhly: [],
            page_url_slocs: "api/asset/warehouse",
            page_url_asset_change_item: "api/asset/asset_change_item",
            disabled: false,
            timeout: null,
            tableDatas: null,
            tableHeader: null,
            error_import_excel: {},
            departments: [],
            demo: '',
            columnss: [],
            rowss: [],
            properties: [],
            data: [
                { label: "Vui lòng nhập chữ 'Bàn giao'", value: "Bàn giao" },
                { label: "Bắt buộc", value: "Nhập tên tài sản" },
                { label: "Bắt buộc", value: "Nhập mã số nhân viên của người sử dụng" },
                { label: "Bắt buộc", value: "Nhập người sử dụng" },
                { label: "Bắt buộc", value: "Nhập phòng ban muốn bàn giao" },
                { label: "Bắt buộc", value: "Nhập mã công ty thuộc phòng ban bàn giao" },
                { label: "Vui lòng nhập chữ 'Bàn giao đầu kỳ'", value: "Bàn giao đầu kỳ" }
            ],
            selected_fields: ["Loại giao dịch", "Tên tài sản", "Mã số nhân viên", "Người sử dụng", "Phòng ban", "Mã công ty", "Ghi chú"],
            failed: {},
            paginate_assets: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,
            show_view: false,

            paginate_assets_thanhly: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_thanhly: [10, 20, 50, 100, 500],
            perPage_thanhly: 10,

            paginate_stocks: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            paginate_transaction: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOption_transaction: [10, 20, 50, 100, 500],
            perPage_transaction: 10,



        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchTransaction();
        this.getUser();
        this.fetchAssetTools();
        this.fetchAssets();
        this.fetchPolicies();
        this.getAsset_use();
        this.fetchWarehouse();
        this.getUser_tree();
        this.fetDepartment();
        this.fetchThanhly();

    },
    watch: {

        'transaction.check': function () {
            if (this.transaction.check === false) {
                this.transaction.department_id = '';

            } else {
                this.transaction.user_id = '';
            }
        }
    },
    mounted() {
        this.transaction.date_transaction = this.defaultDate;
    },
    methods: {
        fetchThanhly() {
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.url_asset_thanhly + "/" + this.paginate_assets_thanhly.current_page + "?per_page=" + this.perPage_thanhly;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.thanhly = res.data;
                    this.paginate_assets_thanhly = res.paginate;
                    this.loading = false;
                })
                .catch(err => console.log(err));
        },
        uploadFiles() {
            const file = this.$refs.fileInput.files[0];
            var page_url = this.page_url_asset_change_item;
            const formData = new FormData();
            formData.append('file', file);
            axios.post(page_url, formData, {
                headers: {
                    'Authorization': this.token,
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    this.failed = JSON.stringify(response.data);
                    const failedData = JSON.parse(this.failed);
                    if (failedData.success == 0) {
                        toastr.warning(failedData.message);
                    } else {
                        toastr.success('Import File Excel thành công')
                        this.tableDatas = response.data.data;
                        $('#import_excel').modal('hide');
                        $("#data_excel").modal("hide");
                        this.fetchTransaction();
                    }

                })
                .catch(error => {
                    if (error.response) {
                        // Server trả về mã lỗi HTTP và thông báo lỗi
                        this.error_import_excel = error.response.data.message;
                        toastr.warning(this.error_import_excel);
                        console.log(error.response.data.message)
                    } else {
                        // Lỗi xảy ra khi gửi yêu cầu AJAX
                        console.log(error.message)
                    }
                });
        },
        exportExcel() {
            const selectedFieldsArray = Array.isArray(this.selected_fields) ? this.selected_fields : [this.selected_fields];
            const headers = [...selectedFieldsArray];
            const data = this.data.map(obj => headers.map(header => obj[header])) // lấy dữ liệu theo thuộc tính được chọn
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]) // tạo worksheet từ dữ liệu và header
            const workbook = XLSX.utils.book_new() // tạo workbook mới
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1') // thêm worksheet vào workbook
            XLSX.writeFile(workbook, 'data.xlsx') // tạo file excel và tải xuống
        },
        resetDataExcel() {
            this.clearFileInput();
            this.selected_fields = ["Loại giao dịch", "Tên tài sản", "Mã số nhân viên", "Người sử dụng", "Phòng ban", "Mã công ty", "Ghi chú"];
        },
        showDataExcel() {
            $("#data_excel").modal("show");
        },
        clearFileInput() {
            this.demo = '';
            const fileInput = this.$refs.fileInput;
            fileInput.type = 'text';
            fileInput.type = 'file';
        },
        chooseNewFile() {
            this.clearFileInput();
            this.$refs.fileInput.click();
        },
        handleFileChange(event) {
            this.demo = event.target.files[0].name;
            const file = this.$refs.fileInput.files[0];
            const reader = new FileReader();

            reader.onload = (e) => {
                const data = e.target.result;
                const workbook = XLSX.read(data, { type: 'binary' });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                if (rows.length > 0) {
                    this.columnss = rows[0];
                    this.rowss = rows.slice(1);
                }
            };

            reader.readAsBinaryString(file);
            $("#data_excel").modal("show");
        },
        showImportExcel() {
            $('#import_excel').modal('show');
        },
        GetDepartmentName(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            return obj != null ? obj.name : '';

        },
        viewtype(transactions) {
            this.show_view = true;
            let x = this.transaction.asset_transactions.find(x => { return x.id == transactions.id })
            this.transaction.trans_type = transactions.trans_type;
            this.transaction.confirm = transactions.confirm;
            this.transaction.created_at = transactions.created_at;
            this.transaction.department_id = transactions.department_id;
            this.transaction.user_id = transactions.user_id;
            this.transaction.updated_at = transactions.updated_at;
            this.transaction.create_by = transactions.create_by;
            this.transaction.date_transaction = transactions.date_transaction;
            this.transaction.note = transactions.note;
            this.transaction.id = transactions.id;
            this.transaction.assetdable_id = transactions.assetdable_id;
            this.transaction.assetdable = transactions.assetdable;
            this.transaction.asset_transaction_items = [...transactions.asset_transaction_items];
            this.transaction.index = x;
            console.log(transactions);
            $("#addfields").modal("show");
        },
        Close_Cancel() {
            $("#itemcanceltool").modal("hide");
            $("#itemcancel").modal("hide");
        },
        Close_Recovery() {
            $("#recovery").modal("hide");
        },
        delay() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.AddRecovery()
        },
        fetDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },
        delayCancel() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.AddCancels()
        },

        getuserName(id) {
            var obj = this.users.find(x => x.id == id);

            if (obj ? obj.active : '' == 0) {
                return obj ? obj.name : '';
            } else {
                return (obj ? obj.name : '') + ' (Không hoạt động)';

            }

        },
        getWarehouse(id) {
            var obj = this.asset_warehouses.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        getAssetTool(id) {
            var obj = this.asset_tools.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        getAssets(id) {
            var obj = this.assets.find(x => x.id == id);
            return obj ? obj.name : '';
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
                    this.users = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        getUser_tree() {
            var page_url = this.page_url_usersnew + "?type=tree_combobox";
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.list_user = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        getAsset_use() {
            var page_url = this.url_asset_use
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_uses = data.data;
                }).catch(err => {
                    console.log(err);
                });
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
        fetchRecovery() {
            this.loading = true;
            let vm = this;


            var page_url = this.url_asset_recovery;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.transaction.asset_transactions = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        fetchAssets() {
            var page_url = this.url_asset_assets + "/" + this.paginate_assets.current_page + "?per_page=" + this.perPage;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.assets = data.data;
                    this.paginate_assets = data.paginate;
                }).catch(err => {
                    console.log(err);
                });
        },
        fetchAssetTools() {
            this.loading = true;
            var page_url = this.page_url_stockPage + "/" + this.paginate_stocks.current_page + "?per_page=" + this.perPage;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_tools = data.data;
                    this.paginate_stocks = data.paginate;
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        fetchTransaction() {
            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;

            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                trans_type: this.trans_type,
                user_id: this.transaction_use,
                // created_at: this.created_at,
                start_date: this.form_filter.start_date,
                end_date: this.form_filter.end_date,
                search: this.form_filter.search,
            });
            var page_url = this.url_asset_get_transaction + "/" + this.paginate_transaction.current_page + "?per_page=" + this.perPage_transaction + '&' + params.toString();
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.transaction.asset_transactions = data.data;
                    this.paginate_transaction= data.paginate;
                    this.loading = false;
                    this.properties = [
                        {
                            name: "Loại giao dịch",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Tên tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Mã số nhân viên",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Người sử dụng",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Phòng ban",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Mã công ty",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Ghi chú",
                            disabled: true,
                            fromAssetFields: false,
                        },
                    ];
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchPolicies() {
            //$("#tbbody_id").html('');
            var page_url = this.url_asset_policies
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_policies = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        AddRecovery() {
            var page_url = this.url_asset_recovery;
            if (this.edit === false) {
                console.log(page_url);
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.transaction),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(123);
                        if (!data.data.errors) {
                            // this.transaction.asset_transactions.push({ ...data.data.data });
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#closestockAsset").modal("hide");
                            this.reset();
                            this.fetchTransaction();
                            // this.getAsset_use();
                            // this.fetchAssets();
                            // this.fetchAssetTools();
                        } else {
                            this.errors = data.data.errors;
                            this.message = data.data.message;
                            // this.showError('Thông báo', this.errors.asset_policy_id[0]);
                            toastr.warning('Lỗi', this.message);
                            if (this.errors.department_id[0] !== null) {
                                document.getElementById('demo').innerHTML = "Lưu ý: " + this.errors.department_id[0];
                                document.getElementById('demo').style.display = 'block';
                            }


                        };
                        // if (data.data.success == 0) {
                        //     this.message = data.data.message;

                        //     toastr.warning('Thông báo', this.message);
                        // }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.transaction.id, {
                    method: "PUT",
                    body: JSON.stringify(this.transaction),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.data.success == '1') {
                            let index = this.transaction.index;
                            this.transaction = { ...data.data.data };
                            this.transaction.index = index;
                            console.log("index=" + this.transaction.index);
                            //this.asset_policies[this.policies.index] = { ...this.policies };
                            this.$set(this.asset_transactions, this.transaction.index, { ...this.transaction });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#closestockAsset").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        AddCancels() {
            var page_url = this.url_asset_cancel;
            if (this.edit === false) {
                console.log(page_url);
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.transaction),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            // this.transaction.asset_transactions.push({ ...data.data.data });

                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#cancel").modal("hide");
                            this.fetchTransaction();
                            this.fetchAssets();
                        } else {
                            this.errors = data.data.errors;

                            this.showError('Thông báo', this.errors.input[0]);
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.transaction.id, {
                    method: "PUT",
                    body: JSON.stringify(this.transaction),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.data.success == '1') {
                            let index = this.transaction.index;
                            this.transaction = { ...data.data.data };
                            this.transaction.index = index;
                            console.log("index=" + this.transaction.index);
                            //this.asset_policies[this.policies.index] = { ...this.policies };
                            this.$set(this.asset_transactions, this.transaction.index, { ...this.transaction });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#cancel").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        DeleteChange(id) {
            let index = this.transaction.asset_transactions.findIndex((i) => {
                console.log("id" + i.id);
                return i.id == id;
            });
            console.log("id=" + id);
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
                title: "",
                size: "sm",
                buttonSize: "sm",
                okVariant: "danger",
                okTitle: "OK",
                cancelTitle: "Cancel",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            })
                .then((value) => {
                    if (value) {
                        var page_url = this.url_asset_transactions + "/" + id;
                        fetch(page_url, {
                            method: "DELETE",
                            headers: {
                                Authorization: this.token,
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                        })
                            .then((res) => res.json())
                            .then((res) => {
                                if (res.statuscode == "403") {
                                    toastr.warning(this.$t(res.message), this.$t("form.warning"));
                                    return;
                                }
                                if (res.data.success == "1") {
                                    // this.requests = [];
                                    this.transaction.asset_transactions.splice(index, 1);
                                    // this.$refs.selectableTable.refresh()
                                    toastr.success(this.$t("form.success"), "");
                                } else {
                                    toastr.warning(res.data.errors);
                                }
                            })
                            .catch((err) => console.log(err));
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        DeleteRecovery(id) {
            let index = this.transaction.asset_transactions.findIndex((i) => {
                console.log("id" + i.id);
                return i.id == id;
            });
            console.log("id=" + id);
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
                title: "",
                size: "sm",
                buttonSize: "sm",
                okVariant: "danger",
                okTitle: "OK",
                cancelTitle: "Cancel",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            })
                .then((value) => {
                    if (value) {
                        var page_url = this.url_asset_recovery + "/" + id;
                        fetch(page_url, {
                            method: "DELETE",
                            headers: {
                                Authorization: this.token,
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                        })
                            .then((res) => res.json())
                            .then((res) => {
                                if (res.statuscode == "403") {
                                    toastr.warning(this.$t(res.message), this.$t("form.warning"));
                                    return;
                                }
                                if (res.data.success == "1") {
                                    // this.requests = [];
                                    this.transaction.asset_transactions.splice(index, 1);
                                    // this.$refs.selectableTable.refresh()
                                    toastr.success(this.$t("form.success"), "");
                                } else {
                                    toastr.warning(res.data.errors);
                                }
                            })
                            .catch((err) => console.log(err));
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        DeleteCancels(id) {
            let index = this.transaction.asset_transactions.findIndex((i) => {
                console.log("id" + i.id);
                return i.id == id;
            });
            console.log("id=" + id);
            this.$bvModal.msgBoxConfirm(this.$t("Xác nhận xoá") + "?", {
                title: "",
                size: "sm",
                buttonSize: "sm",
                okVariant: "danger",
                okTitle: "OK",
                cancelTitle: "Cancel",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            })
                .then((value) => {
                    if (value) {
                        var page_url = this.url_asset_cancel + "/" + id;
                        fetch(page_url, {
                            method: "DELETE",
                            headers: {
                                Authorization: this.token,
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                        })
                            .then((res) => res.json())
                            .then((res) => {
                                if (res.statuscode == "403") {
                                    toastr.warning(this.$t(res.message), this.$t("form.warning"));
                                    return;
                                }
                                if (res.data.success == "1") {
                                    // this.requests = [];
                                    this.transaction.asset_transactions.splice(index, 1);
                                    // this.$refs.selectableTable.refresh()
                                    toastr.success(this.$t("form.success"), "");
                                } else {
                                    toastr.warning(this.$t(res.data.message), this.$t("form.warning"));
                                }
                            })
                            .catch((err) => console.log(err));
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        Addetail() {
            this.show = true;
            for (let index = 0; index < this.item_transaction.length; index++) {
                this.asset_transaction_item.objectable_id = this.item_transaction[index].objectable_id;
                this.asset_transaction_item.quantity = this.item_transaction[index].quantity;
                this.asset_transaction_item.asset_warehouse_id = this.item_transaction[index].asset_warehouse_id;
                this.asset_transaction_item.department_id = this.item_transaction[index].department_id;
                this.asset_transaction_item.user_id = this.item_transaction[index].user_id;
                this.asset_transaction_item.name = this.item_transaction[index].objectable.name;
                // this.asset_transaction_item.asset_status_id = 1
                this.asset_transaction_item.asset_warehouse_name = this.item_transaction[index].asset_warehouse.name;
                this.asset_transaction_item.objectable_type = this.item_transaction[index].objectable_type;
                let isexist = false;
                this.transaction.asset_transaction_items.forEach(element => {
                    if (element.objectable_id == this.item_transaction[index].objectable_id) {
                        isexist = true;
                    }

                });
                if (!isexist) {
                    this.transaction.asset_transaction_items.push({ ...this.asset_transaction_item })
                }

            }
            $("#recovery").modal("hide");
            // this.reset_item();
        },
        AddCancel() {
            for (let index = 0; index < this.item_transaction.length; index++) {
                this.asset_transaction_item.objectable_id = this.item_transaction[index].id;
                this.asset_transaction_item.quantity = this.item_transaction[index].quantity;
                this.asset_transaction_item.asset_status_id = this.item_transaction[index].asset_status_id;
                this.asset_transaction_item.asset_warehouse_id = this.item_transaction[index].asset_warehouse_id;
                this.asset_transaction_item.name = this.item_transaction[index].name;
                this.asset_transaction_item.type = this.item_transaction[index].asset_type.type;
                let isexist = false;
                this.transaction.asset_transaction_items.forEach(element => {
                    if (element.objectable_id == this.item_transaction[index].id) {
                        isexist = true;
                    }

                });
                if (!isexist) {
                    this.transaction.asset_transaction_items.push({ ...this.asset_transaction_item })
                }
            }
            $("#itemcancel").modal("hide");
            $("#itemcanceltool").modal("hide");
        },
        DeleteItems(item, index) {

            if (confirm(this.$t('form.confirm_delete') + '?')) {

                this.transaction.asset_transaction_items_del.push({ ...item });

                this.transaction.asset_transaction_items.splice(index, 1);
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
        showAssetStock() {
            $("#addstockAsset").modal("show");
        },
        showAssetStockExcel() {
            $("#addstockAssetexcel").modal("show");
        },
        showAssetStockuser() {
            $("#userstock").modal("show");
        },
        showAssetStockthuhoi() {
            this.reset();
            $("#closestockAsset").modal("show");
            $("#addstockAsset").modal("hide");
        },
        reset() {
            this.clearAllError();
            // for (let field in this.transaction) {
            //     this.transaction[field] = null;
            // }
            this.edit = false;
            this.transaction.id = "";
            this.transaction.trans_type = "";
            this.transaction.asset_policy_id = "";
            this.transaction.confirm = "",
                this.transaction.check = false;
            this.transaction.asset_transaction_items = [];
            this.transaction.note = "";
            this.transaction.department_id = "";
            this.transaction.created_at = "";
            this.transaction.updated_at = "";
            this.transaction.create_by = "";
            this.transaction.user_id = "";
            this.transaction.assetdable_id = "";
            this.transaction.assetdable = {};
            this.transaction.index = -1;
        },
        showAssetStockclose() {
            this.show = false;
            $("#closestockAsset").modal("show");
            // $("#addstockAsset").modal("hide");
        },
        ShowAssetCancel() {
            this.reset();
            // this.fetchThanhly();
            $("#cancel").modal("show");
            $("#addstockAsset").modal("hide");
        },
        view() {
            window.location.href = '/asset/change?type=addchange';
        },
        viewtrichxuat() {
            window.location.href = '/asset/change?type=trichxuat';
        },
        viewchangeedit(id) {
            window.location.href = '/asset/change?type=edit&id=' + id;
        },
        pageAddRepair() {
            window.location.href = '/asset/change?type=repair';
        },
        hide(item, user_id) {
            if (!item || user_id !== 'row') return
            if (this.transaction.user_id !== item.user_id) return 'd-none';
        },
        // cancel_hide(item, user_id) {
        //     if (!item || user_id !== 'row') return
        //     if (item.user_id == null  || item.asset_status_id !== 1) return 'd-none';
        //     // if (item.asset_status_id !== 3) 
        //     //     console.log(item.asset_status_id);
        //     // return 'd-none';
        // },
        onRowSelected(item) {
            this.item_transaction = [...item];
            alert(123);
            let kim = [];
            for (let index = 0; index < this.item_transaction.length; index++) {
                kim = this.item_transaction[index].asset_transaction_items;
                console.log(kim);
                for (let inc = 0; inc < kim.length; inc++) {
                    // this.asset_transaction_item.asset_transaction_id = kim[inc].asset_transaction_id;
                    this.asset_transaction_item.objectable_id = kim[inc].objectable_id;
                    this.asset_transaction_item.objectable_type = kim[inc].objectable_type;
                    this.asset_transaction_item.quantity = kim[inc].quantity;
                    this.asset_transaction_item.asset_status_id = kim[inc].asset_status_id;
                    // alert(this.asset_transaction_item.objectable_id );
                    this.transaction.asset_transaction_items.push({ ...this.asset_transaction_item })
                }
                // let isexist = false;
                // this.transaction.asset_transaction_items.forEach(element => {
                //     if (element.objectable_id == this.item_transaction[index].id) {
                //         isexist = true;
                //     }
                // });
                // if (!isexist) {
                // }
            }
        },
        selectAllRows() {
            this.$refs.selectableTable.selectAllRows();
        },
        clearSelected() {
            this.$refs.selectableTable.clearSelected();
            // this.total = this.counter - this.counter;
        },
        clearAllError(event) {
            this.errors = {};
        },
        filter_data() {
            this.counter++;
            if (this.form_filter.asset_warehouse_id !== "" && this.counter > 1) {

                this.fetchTransaction();
                this.fetchWarehouse();
                this.counter = 0;
            }
        },
        filter_trans_type() {
            if (this.trans_type !== "") {
                // this.action++;
                this.fetchTransaction();
                this.fetchWarehouse();
                // this.action = 0;

            }
        },
        filter_transaction_use() {



            // this.action++;
            this.fetchTransaction();
            this.fetchWarehouse();
            // this.action = 0;

        },
        btn_filter() {
            if (this.transaction_use == undefined) {
                this.transaction_use = "";
                this.fetchTransaction();
                this.fetchWarehouse();
            } else {
                this.fetchTransaction();
                this.fetchWarehouse();
            }


            // this.filter_asset_use();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            this.transaction_use = "",
                this.trans_type = "";
            this.fetchTransaction();
            this.fetchWarehouse();

        },
        ShowRecovery() {

            this.item_transaction = [];
            this.getAsset_use();
            $("#recovery").modal("show");
        },
        ShowCancel() {
            this.item_transaction = [];

            $("#itemcancel").modal("show");
        },
        ShowCancel_tool() {
            this.item_transaction = [];
            $("#itemcanceltool").modal("show");
        },
        clear() {
            this.transaction.note = '';
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();
        },
        Stoppush() {
            for (let index = 0; index < this.users.length; index++) {
                for (let us = 0; us < this.transaction.asset_transactions.length; us++) {
                    if (this.users[index].id == this.transaction.asset_transactions[us].user_id) {
                        console.log(this.users[index].id);
                        return this.users[index].name;
                    }

                }

            }

        },
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        getError(fieldName) {
            return this.errors[fieldName][0];
        },
        show_user_confirm() {
            var checkBox = document.getElementById("check");
            var text = document.getElementById("text");
            if (checkBox.checked == true) {
                text.style.color = 'green';
                text.style.fontStyle = 'italic';

            } else {

                text.style.color = 'black';


            }
        },
        printRecord(id) {
            window.location.href = '/asset/print?id=' + id;
        },

    },
    computed: {
       
        evenNumbers: function () {
            var new_numbers = [];
            for (var i = 0; i < this.asset_uses.length; i++) {
                for (var ki = 0; ki < this.transaction.asset_transactions.length; ki++) {
                    if (this.asset_uses[i].user_id == this.transaction.asset_transactions[ki].user_id && this.asset_uses[i].user_id !== null) {
                        new_numbers.push(this.asset_uses[i].user_id);
                    }
                }
            }
            let unique = [...new Set(new_numbers)];
            return unique;
        },
        // User_asset: function () {
        //     var users = [];
        //     let is = false;
        //     for (var i = 0; i < this.assets.length; i++) {
        //         if (this.assets[i].asset_status_id !== 3) {
        //             is = true
        //         }
        //         if (!is && this.assets[i].user_id !== null) {
        //             users.push(this.assets[i].user_id);
        //         }
        //     }
        //     let unique = [...new Set(users)];
        //     console.log(unique);
        //     return unique;
        // },

        asset_asset() {
            var asset = [];
            for (var index = 0; index < this.assets.length; index++) {
                if (this.assets[index].asset_status_id !== 3 && this.assets[index].user_id !== '' && this.assets[index].user_id == null) {
                    asset.push(this.assets[index]);
                }
            }
            return asset;
        },
        row_asset() {
            var asset = [];
            for (var index = 0; index < this.assets.length; index++) {
                if (this.assets[index].asset_status_id !== 3 && this.assets[index].user_id !== '' && this.assets[index].user_id == null) {
                    asset.push(this.assets[index]);

                }
            }
            return asset.length;
        },
        filter_asset_use() {
            var use = [];
            for (var kim = 0; kim < this.transaction.asset_transactions.length; kim++) {
                use.push(this.transaction.asset_transactions[kim].user_id)
            }
            let unique = [...new Set(use)];
            return unique
        },
        list_transaction_asset_user_id() {
            var use = [];
            for (let index = 0; index < this.asset_uses.length; index++) {
                if (this.asset_uses[index].user_id == this.transaction.user_id) {
                    use.push(this.asset_uses[index])
                }
                if (this.asset_uses[index].department_id == this.transaction.department_id) {
                    use.push(this.asset_uses[index])
                }
            }
            let unique = [...new Set(use)];
            return unique
        },
        transaction_department() {
            var new_department = [];
            for (var i = 0; i < this.asset_uses.length; i++) {
                for (var ki = 0; ki < this.transaction.asset_transactions.length; ki++) {
                    if (this.asset_uses[i].department_id == this.transaction.asset_transactions[ki].department_id && this.asset_uses[i].department_id !== null) {
                        new_department.push(this.asset_uses[i].department_id);
                    }
                }
            }

            let unique = [...new Set(new_department)];
            return unique;
        },
        defaultDate() {
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0');
            let yyyy = today.getFullYear();
            return yyyy + '-' + mm + '-' + dd;
        },
    }

}
</script>
<style lang="scss" scoped>
.form-group {
    margin-bottom: 5px !important;
}

/* ul{
   background:red;
} */
#btn_refesh:hover {
    box-shadow: 1px 1px 10px orange;
    color: white;
    width: 65px;
}


#type::placeholder {
    color: green;
}

#card_transaction:hover {
    color: white;
    background-color: #7FFF00;
    animation: card_transaction 1s infinite;
}

#card_repair:hover {
    color: white;
    background-color: #fd7e14;
    animation: card_transaction 1s infinite;
}

#item_card_transaction:hover {
    animation: item_card_transaction 1s infinite;
}

#card_recovery:hover {
    color: white;
    background-color: yellow;
    animation: card_recovery 1s infinite;
}

#item_card_recovery:hover {
    animation: item_card_recovery 1s infinite;
}

#card_cancel:hover {
    color: white;
    background-color: red;
    animation: card_cancel 2s infinite;
}

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;

}

#shadow_btn:hover {
    color: white;
    box-shadow: 1px 1px 10px #3c8dbc;
}

#item_card_cancel:hover {
    color: white;
    animation: item_card_cancel 2s infinite;
}

#submit_recovery:hover {
    width: 110px;
    box-shadow: 1px 1px 10px red;
}

@keyframes card_transaction {
    50% {
        box-shadow: 1px 1px 20px lightgreen;
    }
}

@keyframes item_card_transaction {
    50% {
        padding-left: 50px;
    }
}

@keyframes card_recovery {
    50% {
        box-shadow: 1px 1px 20px yellow;
    }
}

@keyframes item_card_recovery {
    50% {
        padding-right: 50px;
    }
}

@keyframes card_cancel {
    50% {
        box-shadow: 1px 1px 20px red;
    }
}

@keyframes item_card_cancel {

    50% {
        padding-left: 50px;
    }
}

.checkbox {
    transform: scale(1.5);
}

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}


::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}


::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 5px;
}


::-webkit-scrollbar-corner {
    background-color: #f1f1f1;
}

.hover-view:hover {
    color: #3A8CC3;
}

.form-import-excel {
    position: relative;
    width: 100%;
    height: 150px;
    background: rgb(247, 248, 250);

}

.form-import-excel p {
    width: 50%;
    height: 100%;
    text-align: center;
    color: black;

    border-radius: 10px;
    border: 3px dotted lightgray;

}

.form-import-excel:hover p {
    background: rgb(234, 235, 237);

}

.form-import-excel input {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
}

.item-colum {
    border-bottom: 1px solid rgb(234, 235, 237);
    padding: 10px;
}

.item {
    background: rgb(247, 248, 250);
    border-radius: 5px;
}

input::-webkit-file-upload-button {
    visibility: hidden;
}

.fullscreen {
    width: 100% !important;
    max-width: none !important;
    height: 100% !important;
    margin: 0 !important;
}

.position-loading {
    position: absolute;
    top: 40%;
    left: 50%;
    z-index: 1;
    transform: translate(-50%, -50%);
}

.text-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-repair-item {
    background: linear-gradient(#ff7400, rgb(255 9 9 / 90%));
    color: white;
    margin-top: 3px;
}

.icon-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.background-status-repair {
    background: linear-gradient(#fd7e14, rgb(205 6 0));
    color: white;
}
</style>
