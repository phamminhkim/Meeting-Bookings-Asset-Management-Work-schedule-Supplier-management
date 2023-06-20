<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">
                            <i class="fas fa-box-open"></i>{{ $t(title) }}
                        </h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <b-dropdown right text="Import Excel TS" variant="primary" style="display:inline-block;">
                                <b-dropdown-item @click.prevent="showImportExcel()">Tài sản</b-dropdown-item>
                            </b-dropdown>
                            <b-dropdown id="shadow_btn" right text="Tạo mới" variant="primary"
                                style="display:inline-block;">
                                <b-dropdown-item @click="showAsset()">Tạo mới tài sản
                                </b-dropdown-item>
                                <!-- <div v-if="success && !loadingEx">
                                    <b-dropdown-item  >
                                    <download-excel  :name="fileName" :data="tableData" title="Danh sách tài sản" type="xls">
                                       <p >Trích xuất tài sản</p> 
                                    </download-excel>
                                    </b-dropdown-item>
                                </div>
                                <div v-if="loadingEx">
                                    <b-dropdown-item disabled>
                                    <i class="fas fa-spinner fa-spin"></i> Đang xử lý, vui lòng chờ...
                                    </b-dropdown-item>
                                </div> -->
                                <!-- <div v-if="!success && !loadingEx">
                                    <b-dropdown-item disabled>
                                        <span style="display: inline-block;">Trích xuất tài sản</span>
                                        <span style="margin-left: 2px;line-height:0px;display: block; font-size: 10px; color: red; font-style: italic;">Loading Tài sản để trích xuất</span>
                                    </b-dropdown-item>
                                </div> -->
                                <b-dropdown-item v-if="!loadingEx" @click="ExportData">
                                    <span style="display: inline-block;"> Trích xuất tài sản</span>
                                    <!-- <span  v-if="success" style="line-height:0px; font-size: 12px; color: darkgreen; font-style: italic;">(Hoàn thành)!</span>                             -->
                                </b-dropdown-item>
                                <div v-if="loadingEx">
                                    <b-dropdown-item disabled>
                                        <i class="fas fa-spinner fa-spin"></i> Đang xử lý, vui lòng chờ...
                                    </b-dropdown-item>
                                </div>
                                <b-progress style="border-radius: 5px; margin: 5px;" v-if="loadingEx" :value="progress"
                                    :max="maxProgress" :show-progress="false" :show-value="true" animated
                                    striped></b-progress>

                            </b-dropdown>


                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <input role="presentation" class="form-control rounded-pill mt-1 mb-1"
                            placeholder="Nhập tìm kiếm và nhấn enter....." v-model="filter" @change="filterAll" />
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">

                            <div class="btn-group ">
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

                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="row" v-if="show_search"
                    style="background-color:rgb(244, 246, 249);border-radius: 5px;box-shadow: 1px 1px 5px lightgrey;">
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fad fa-circle-notch"> </i> <span
                                    class="text-secondary">Trạng Thái: </span></label>
                            <div class="col-md-3">
                                <select name="" class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.asset_status_id" @change="filter_asset_status_id()">
                                    <option value="">All</option>
                                    <option value="1">Tốt</option>
                                    <option value="3">Thanh lý</option>
                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-folder text-secondary"></i>
                                <span class="text-secondary">Nhóm tài sản: </span></label>
                            <div class="col-md-3">
                                <select name="" class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.asset_group_id" @change="filter_asset_group_id()">
                                    <option value="">All</option>
                                    <option v-for="gr in asset_groups" v-bind:value="gr.id">
                                        {{ gr.name }}
                                    </option>

                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""><i class="fas fa-cube"></i> <span
                                    class="text-secondary">Kho hàng: </span></label>
                            <div class="col-md-2">
                                <select name="" class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.asset_warehouse_id" @change="filter_asset_warehouse_id()">
                                    <option value="">All</option>
                                    <option v-for="slocc in asset_warehouses" v-bind:value="slocc.id">
                                        {{ slocc.name }}
                                    </option>

                                </select>
                            </div>
                            <div class="col-md-2">
                                <button id="btn_refesh" type="reset" class="btn btn-outline-warning btn-xs mt-2  "
                                    @click="clearFilter()">
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="TẤT CẢ" title-link-class="animation1" id="tatca">
                        <template #title>
                            <div class="tess">
                                <strong>TẤT CẢ</strong>
                            </div>
                        </template>
                        <div class="body">
                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" hover responsive :bordered="true"
                                :fields="fields" :items="assets" :sticky-header="true" @row-clicked="showA">
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(end_date)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_status_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(department.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>

                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">
                                    <i class="fas fa-cube"></i> <span>{{ getwarehouseNameName(data.value) }}</span>
                                </template>
                                <template #cell(user_id)="data">
                                    <div v-if="data.item.user_id != null && data.item.waiting == 1">
                                        <span>{{ getUserName(data.value) }} (Chưa xác nhận)</span>
                                    </div>
                                    <div v-if="data.item.user_id != null && data.item.waiting == 0">
                                        <span>{{ getUserName(data.value) }}</span>
                                    </div>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i> <span>{{ getGroupName(data.value)
                                    }}</span>
                                </template>
                                <template v-slot:cell(#)=data>
                                    <span v-if="data.item.assett == 0">{{ data.index + 1 }}</span>
                                </template>
                                <template #cell(asset_status_id)=data>
                                    <div v-if="data.item.asset_status_id == 1" class="badge w-100 p-2 bg-success"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Tốt</div>
                                    <div v-if="data.item.asset_status_id == 3" class="badge w-100 p-2 bg-danger"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Thanh lý
                                    </div>
                                    <div v-if="data.item.asset_status_id == 4" class="badge w-100 p-2 text-repair"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Đang sửa chữa
                                    </div>
                                    <div v-if="data.item.asset_status_id == 5" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Mất</div>
                                    <div v-if="data.item.asset_status_id == 9" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Xấu</div>
                                </template>
                                <template v-slot:cell(action)=data>
                                    <b-dropdown size="sm"
                                        v-if="data.item.user_id == null && data.item.asset_status_id != 3 && data.item.department_id == null"
                                        id="shadow_btn" text="Action" variant="outline-secondary" right
                                        style="display:flex;">

                                        <b-dropdown-item v-b-hover="handleHover" @click="editAsset(data.item)">
                                            <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHovers" @click="deleteAsset(data.item.id)">
                                            <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHoverss" @click="info(data.item)">
                                            <span :class="isHoveredss ? 'text-primary' : ''">Lịch sử</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item  @click="showHistoryV2(data.item)">
                                            <span>Lịch sử v2</span>
                                        </b-dropdown-item>


                                    </b-dropdown>
                                    <!-- <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button> -->
                                    <button v-else @click="showHistoryV2(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>


                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>
                                <template #cell(name)=data>
                                    <div v-for="img in data.item.attachment_image" :key="img.id"
                                        style="display:inline-block">
                                        <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                    </div>
                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                        </div>
                                    </div>
                                </template>

                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="perPage" :options="perPageOptions"
                                                @change="fetchAsset">

                                            </b-form-select>
                                            <!-- <span>{{currentPage}} of {{lastPage}}</span> -->
                                            <b-pagination @input="fetchAsset" v-model="paginate_assets.current_page"
                                                :total-rows="paginate_assets.total" :per-page="perPage"
                                                :last-page="paginate_assets.last_page" pills class="ml-1"></b-pagination>

                                        </div>


                                    </div>
                                </div>
                            </div>




                        </div>
                    </b-tab>
                    <b-tab title="CHƯA BÀN GIAO" title-link-class="animation1" id="bangiao">
                        <template #title>
                            <div class="tess">
                                <strong>CHƯA BÀN GIAO</strong>
                            </div>
                        </template>
                        <div class="body">

                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" hover responsive :bordered="true"
                                :fields="fields" :items="chuabangiao" :sticky-header="true" :tbody-tr-class="chuabangiaoo">
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(end_date)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_status_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(department.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>

                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">
                                    <i class="fas fa-cube"></i><span>{{ getwarehouseNameName(data.value) }}</span>
                                </template>
                                <template #cell(user_id)="data">
                                    <div v-if="data.item.user_id != null && data.item.waiting == 1">
                                        <span>{{ getUserName(data.value) }} (Chưa xác nhận)</span>
                                    </div>
                                    <div v-if="data.item.user_id != null && data.item.waiting == 0">
                                        <span>{{ getUserName(data.value) }}</span>
                                    </div>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i> <span>{{ getGroupName(data.value)
                                    }}</span>
                                </template>
                                <template v-slot:cell(#)=data>
                                    <span v-if="data.item.assett == 0">{{ data.index + 1 }}</span>
                                </template>
                                <template #cell(asset_status_id)=data>
                                    <div v-if="data.item.asset_status_id == 1" class="badge w-100 p-2 bg-success"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Tốt</div>
                                    <!-- <div v-if="data.item.asset_status_id == 2" class="bg-warning"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Sửa chữa
                                    </div> -->
                                    <div v-if="data.item.asset_status_id == 3" class="badge w-100 p-2 bg-danger"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Thanh lý
                                    </div>
                                    <div v-if="data.item.asset_status_id == 4" class="badge w-100 p-2 text-repair"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Đang sửa chữa
                                    </div>
                                    <div v-if="data.item.asset_status_id == 5" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Mất</div>
                                    <div v-if="data.item.asset_status_id == 9" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Xấu</div>
                                </template>
                                <template v-slot:cell(action)=data>
                                    <b-dropdown size="sm"
                                        v-if="data.item.user_id == null && data.item.asset_status_id != 3 && data.item.department_id == null"
                                        id="shadow_btn" text="Action" variant="outline-secondary" right
                                        style="display:flex;">

                                        <b-dropdown-item v-b-hover="handleHover" @click="editAsset(data.item)">
                                            <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHovers" @click="deleteAsset(data.item.id)">
                                            <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHoverss" @click="info(data.item)">
                                            <span :class="isHoveredss ? 'text-primary' : ''">Lịch sử</span>
                                        </b-dropdown-item>



                                    </b-dropdown>
                                    <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>

                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>
                                <template #cell(name)=data>
                                    <div v-for="img in data.item.attachment_image" :key="img.id"
                                        style="display:inline-block">
                                        <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                    </div>
                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                        </div>
                                    </div>
                                </template>

                            </b-table>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <!-- <b-form-select size="sm" v-model="per_page2" :options="pageOptions">
                                            </b-form-select>
                                            <b-pagination v-model="current_page2" :total-rows="rows" :per-page="per_page2"
                                                pills class="ml-1"></b-pagination> -->
                                            <b-form-select size="sm" v-model="perPage_chuabangiao"
                                                :options="perPageOptions_chuabangiao" @change="fetchChuabangiao">

                                            </b-form-select>
                                            <b-pagination @input="fetchChuabangiao"
                                                v-model="paginate_assets_chuabangiao.current_page"
                                                :total-rows="paginate_assets_chuabangiao.total"
                                                :per-page="perPage_chuabangiao"
                                                :last-page="paginate_assets_chuabangiao.last_page" pills
                                                class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>




                        </div>
                    </b-tab>
                    <b-tab title="ĐÃ ĐƯỢC BÀN GIAO" title-link-class="animation1" id="sudung">
                        <template #title>
                            <div class="tess">
                                <strong>ĐÃ ĐƯỢC BÀN GIAO</strong>
                            </div>
                        </template>

                        <div class="body">
                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" hover responsive :bordered="true"
                                :fields="fields" :items="dabangiao" :sticky-header="true" :tbody-tr-class="dabangiaoo">
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(end_date)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_status_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(department.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>


                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">

                                    <i class="fas fa-cube"></i> <span>{{ getwarehouseNameName(data.value) }}</span>

                                </template>
                                <template #cell(user_id)="data">
                                    <div v-if="data.item.user_id != null && data.item.waiting == 1">
                                        <span>{{ getUserName(data.value) }} (Chưa xác nhận)</span>
                                    </div>
                                    <div v-if="data.item.user_id != null && data.item.waiting == 0">
                                        <span>{{ getUserName(data.value) }}</span>
                                    </div>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i> <span>{{ getGroupName(data.value)
                                    }}</span>
                                </template>
                                <template v-slot:cell(#)=data>
                                    <span v-if="data.item.assett == 0">{{ data.index + 1 }}</span>
                                </template>
                                <template #cell(asset_status_id)=data>
                                    <div v-if="data.item.asset_status_id == 1" class="badge w-100 p-2 bg-success"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Tốt</div>
                                    <!-- <div v-if="data.item.asset_status_id == 2" class="bg-warning"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Sửa chữa
                                    </div> -->
                                    <div v-if="data.item.asset_status_id == 3" class="badge w-100 p-2 bg-danger"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Thanh lý
                                    </div>
                                    <div v-if="data.item.asset_status_id == 4" class="badge w-100 p-2 text-repair"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Đang sửa chữa
                                    </div>
                                    <div v-if="data.item.asset_status_id == 5" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Mất</div>
                                    <div v-if="data.item.asset_status_id == 9" class="bg-danger w-100 p-2"
                                        style="text-align:center;padding:3px;margin:0 auto;font-weight: 700;">Xấu</div>
                                </template>
                                <template v-slot:cell(action)=data>
                                    <b-dropdown size="sm"
                                        v-if="data.item.user_id == null && data.item.asset_status_id != 3 && data.item.department_id == null"
                                        id="shadow_btn" text="Action" variant="outline-secondary" right
                                        style="display:flex;">

                                        <b-dropdown-item v-b-hover="handleHover" @click="editAsset(data.item)">
                                            <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHovers" @click="deleteAsset(data.item.id)">
                                            <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHoverss" @click="info(data.item)">
                                            <span :class="isHoveredss ? 'text-primary' : ''">Lịch sử</span>
                                        </b-dropdown-item>



                                    </b-dropdown>
                                    <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>
                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>
                                <template #cell(name)=data>
                                    <div v-for="img in data.item.attachment_image" :key="img.id"
                                        style="display:inline-block">
                                        <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                    </div>
                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                        </div>
                                    </div>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <!-- <b-form-select size="sm" v-model="per_page3" :options="pageOptions">
                                            </b-form-select>
                                            <b-pagination v-model="current_page3" :total-rows="rows" :per-page="per_page3"
                                                pills class="ml-1"></b-pagination> -->

                                            <b-form-select size="sm" v-model="perPage_dabangiao"
                                                :options="perPageOptions_dabangiao" @change="fetchDabangiao">

                                            </b-form-select>
                                            <b-pagination @input="fetchDabangiao"
                                                v-model="paginate_assets_dabangiao.current_page"
                                                :total-rows="paginate_assets_dabangiao.total" :per-page="perPage_dabangiao"
                                                :last-page="paginate_assets_dabangiao.last_page" pills
                                                class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>




                        </div>
                    </b-tab>
                </b-tabs>

            </div>
            <div class="center overlay" v-if="loading">
                <i class="fa fa-spinner fa-spin fa-4x fa-fw gray center"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- popup -->
        <div class="modal fade bd-example-modal-lg" id="addAsset" tabindex="-1" role="dialog" style="overflow-y:scroll">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="delay">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">Thêm tài sản</h4>
                                <h4 v-if="edit"> Cập Nhật tài sản</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Model tài sản</label> <small class="text-red">( * )</small>
                                        <treeselect :disable-branch-nodes="true" placeholder="Model tài sản"
                                            :show-count="true" :multiple="false" v-model="assett.asset_type_id"
                                            :options="tree_assettype" @change="changetype(assett.asset_type_id)"
                                            v-bind:class="hasError('asset_type_id') ? 'is-invalid' : ''">
                                        </treeselect>
                                        <span v-if="hasError('asset_type_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_type_id') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">

                                        <label>Số sê-ri</label> <small class="text-red">( * )</small>
                                        <input class="form-control" v-model="assett.seri" id="seri" name="seri"
                                            v-bind:class="hasError('seri') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('seri')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('seri') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Tên tài sản</label> <small class="text-red">( * )</small>
                                <input class="form-control" v-model="nameAsset" readonly
                                    v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('name') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label> <small class="text-red">( * )</small>
                                        <input class="form-control" v-model="filter_asset_group"
                                            v-bind:class="hasError('asset_group_id') ? 'is-invalid' : ''" readonly />
                                        <!-- <select class="form-control" id="" 
                                                                v-bind:class="hasError('asset_group_id') ? 'is-invalid' : ''">
                                                                <option value="" disabled selected>Vui lòng chọn một</option>
                                                                <option v-for="group in asset_types" :key="group.asset_group.id"
                                                                    v-bind:value="group.asset_group.id"
                                                                    v-if="assett.asset_type_id == group.id"> {{ group.asset_group.name }}
                                                                </option>
                                                            </select> -->
                                        <span v-if="hasError('asset_group_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_group_id') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà cung cấp</label>

                                        <treeselect :disable-branch-nodes="true" placeholder="Nhà cung cấp"
                                            :show-count="true" :multiple="false" v-model="assett.vendor_id"
                                            :options="tree_vendos">
                                        </treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Kho hàng</label> <small class="text-red">( * )</small>
                                        <treeselect placeholder="Kho hàng" :disable-branch-nodes="true" :show-count="true"
                                            :multiple="false" v-model="assett.asset_warehouse_id" :options="tree_slocs"
                                            v-bind:class="hasError('asset_warehouse_id') ? 'is-invalid' : ''">
                                        </treeselect>
                                        <span v-if="hasError('asset_warehouse_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_warehouse_id') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Hashtag</label>
                                        <input class="form-control" v-model="assett.hashtag"
                                            v-bind:class="hasError('hashtag') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('hashtag')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('hashtag') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Giá mua</label>
                                        <vue-numeric v-bind:minus="false" :separator="separator" v-model="assett.amount"
                                            class="form-control" name="amount" placeholder=""
                                            v-bind:class="hasError('amount') ? 'is-invalid' : ''">
                                        </vue-numeric>
                                        <span v-if="hasError('amount')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('amount') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà sản xuất</label>
                                        <input class="form-control" v-model="assett.producer"
                                            v-bind:class="hasError('producer') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('producer')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('producer') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>SAP Code</label>

                                        <input class="form-control" v-model="assett.sap_code"
                                            v-bind:class="hasError('sap_code') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('sap_code')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('sap_code') }}</strong>
                                        </span>
                                    </div>
                                    <!-- <div class="col-sm-6">
                                                            <label>Phòng ban</label>

                                                            <treeselect placeholder="Phòng ban" :disable-branch-nodes="true"
                                                                :show-count="true" :multiple="false" v-model="assett.department_id"
                                                                :options="tree_department">
                                                            </treeselect>

                                                        </div> -->
                                    <div class="col-sm-6">
                                        <label>Ngày nhập</label>
                                        <input type="date" class="form-control" v-model="assett.add_date" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-6">
                                        <label>Ngày hết hạn sử dụng</label>
                                        <input type="date" class="form-control" v-model="assett.end_date" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="col-form-label-sm col-12">
                                            <input type="file" v-on:change="onImageChange" class="form-control"
                                                accept=".xlsx,.xls,image/x-png,image/gif,image/jpeg,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                                name="images[]" @change="emitEventImg($event)" id="Themimgg"
                                                style="display:none" ref="imgg" multiple>
                                            <button type="button" class="btn btn-sm cssfile right"
                                                style="border:1px solid lightgray;padding:6px;font-weight:700;border-radius:5px;"
                                                v-on:click="handleClickInputImg()"> <i class="fas fa-image fa-lg"></i>
                                                Thêm ảnh </button>

                                            <div class="form-group mt-2 row">
                                                <div class="col-md-4 mt-2" v-for="(imgg, index) in assett.attachment_image"
                                                    :key="index">
                                                    <div class="card m-auto mt-2" id="card_img"
                                                        style="max-width:200px;height:auto;width:100%">
                                                        <img style="max-width:250px;height:200px;width:100%"
                                                            v-if="imgg.base64" :src="imgg.base64"
                                                            class="img-responsive img-fluid" rounded="lg" />
                                                        <img style="max-width:250px;height:200px;width:100%" v-if="imgg.url"
                                                            :src="imgg.url" class="img-responsive img-fluid" rounded="lg" />
                                                        <div class="card-footer">
                                                            <button id="show_btn_cancel"
                                                                class="btn btn-outline-danger w-100"
                                                                @click.prevent="deleteImg(imgg, index)"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>


                                    </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    v-model="assett.description"
                                    v-bind:class="hasError('description') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('description') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>File đính kèm</label>
                                        <br>
                                        <div class="col-form-label-sm col-12">
                                            <input type="file" v-on:change="onFileChange" class="form-control"
                                                accept=".docx" name="images[]" @change="emitEvent($event)" id="ThemFile"
                                                style="display:none" ref="file" multiple>
                                            <button class="btn btn-sm cssfile right"
                                                style="border:1px solid lightgray;padding:6px;font-weight:700;border-radius:5px;"
                                                type="button" v-on:click="handleClickInputFile()">
                                                <i class="fas fa-file"></i> Thêm File
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" v-for="(file, index) in assett.attachment_file" v-bind:key="index">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div style="font-size:12px;display:inline-block;width:78%">
                                                <span><i> {{ index + 1 + '.' + file.name }}</i></span>
                                            </div>
                                            <div class="float-right">
                                                <button style="border-radius:50px;font-size:12px" class="btn text-red"
                                                    @click.prevent="deleteFile(file, index)"><i
                                                        class="fas fa-trash fa-lg"></i></button>
                                                <button type="button" class="btn btn-default btn-xs "
                                                    style="background:darkcyan;color:white;border-radius:10px;"
                                                    @click.prevent="downloadFile(file.id, file.name)"><i
                                                        class="fas fa-download"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group table_custom">
                                    <label>Cấu hình Model</label>
                                    <b-table responsive hover :items="asset_custom_field" :fields="custom_model_asset"
                                        head-variant="light">
                                        <template #head(#)='data'>
                                            <div class="text-success">
                                                <span> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <template #cell(#)='data'>
                                            <div>
                                                <span> {{ data.index + 1 }} </span>
                                            </div>
                                        </template>
                                        <template #head(value)='data'>
                                            <div class="text-success">
                                                <span> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <template #cell(value)='data'>
                                            <!-- <div class="" style="border-bottom:2px solid blue;display:inline-block">
                                                                    <span class="text-secondary"> {{data.item.value}}  </span>
                                                                </div> -->
                                            <input class='form-control form-control-sm' v-model='data.item.value' />

                                        </template>
                                        <template #head(name)='data'>
                                            <div class="text-success">
                                                <span> {{ data.label }} </span>
                                            </div>
                                        </template>
                                        <!-- <template #cell(action)="data">
                                                                <div class="text-center">
                                                                    <button @click.prevent="DeleteItems(data.item, data.index)"
                                                                        id="show_btn_cancel" class="btn btn-outline-danger btn-sm"><i
                                                                            class="fas fa-trash-alt"></i></button>
                                                                </div>
                                                            </template> -->
                                    </b-table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Đóng
                            </button>
                            <button type="submit" class="btn btn-success" style="width:47%;" :disabled="disabled">
                                Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="ShowHistory" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form @submit.prevent="ShowHistory">
                        <div class="modal-header">
                            <div class="modal-title">

                                <h4>Lịch sử Tài sản</h4>
                            </div>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>


                        <div class="modal-body">
                            <div class="mb-1">
                                <span class="text-secondary ">Tên tài sản: <strong class="text-uppercase">{{ assett.name }}
                                    </strong></span>
                            </div>
                            <!-- <b-table :items="history" responsive :fields="lichsubangiao" stacked="md"
                                :current-page="current_page4" :per-page="per_page4" small>
                                <template #head(#)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(#)=data>
                                    <div>
                                        <span class="badge"> {{ data.index + 1 }} </span>
                                    </div>
                                </template>
                                <template #head(giaodich)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(department_id)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(created_by)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(created_att)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(trangthai)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(department_id)=data>
                                    <span>{{ getdepartmentsName(data.item.department_id) }}</span>

                                </template>
                                <template #head(user_id)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(confirm)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(note)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #cell(giaodich)=data>
                                    <div class="badge" v-if="data.item.giaodich == 'Tạo tài sản'">
                                        <span class="text-primary">Tạo tài sản</span>
                                    </div>
                                    <div class="badge" v-if="data.item.giaodich == 1">
                                        <span class="text-success">Bàn giao</span>
                                    </div>
                                    <div class="badge" v-if="data.item.giaodich == 2">
                                        <span class="text-danger">Thu hồi</span>

                                    </div>
                                    <div class="badge" v-if="(data.item.giaodich == 3)">
                                        <span class="text-danger">Thanh lý tài sản</span>
                                    </div>
                                    <div class="badge" v-if="(data.item.giaodich == 4)">
                                        <span class="text-warning">Sửa chữa</span>
                                    </div>
                                </template>

                                <template #cell(created_by)=data>
                                    <span>{{ getUserName(data.item.created_by) }}</span>

                                </template>
                                <template #cell(trangthai)=data>
                                    <div class="badge bg-success " v-if="assett.asset_status_id == 1" style="float:center;">
                                        <span class="text-white ">Tốt</span>
                                    </div>
                                    <div v-if="assett.asset_status_id == 9" style="float:center;">
                                        <span class="text-danger">Xấu (Thủ kho đánh giá)</span>
                                    </div>
                                </template>
                                <template #cell(user_id)=data>
                                    <span>{{ getUserName(data.item.user_id) }}</span>

                                </template>
                                <template #cell(created_att)=data>

                                    <span>{{ fomatTime(data.item.created_at) }}</span>

                                </template>


                                <template #cell(confirm)=data>

                                    <div style="border: 1px solid ghostwhite"
                                        v-if="data.item.confirm == 0 && data.item.department_id == null && data.item.user_id != null">
                                        <span class="badge bg-danger">Chưa xác nhận</span>
                                    </div>
                                    <div style="border: 1px solid ghostwhite"
                                        v-if="data.item.confirm == 0 && data.item.department_id != null && data.item.user_id == null">
                                        <span class="badge bg-danger"></span>
                                    </div>

                                    <div style="border: 1px solid ghostwhite"
                                        v-if="data.item.confirm == 1 && data.item.department_id == null && data.item.user_id != null">
                                        <span class="badge bg-success ">Đã xác nhận</span>
                                    </div>
                                    <div style="border: 1px solid ghostwhite" v-if="data.item.confirm == 3"><span
                                            class="badge bg-warning ">Không cần xác nhận</span></div>
                                </template>


                                <template #cell(note)=row>

                                    <button id="shadow_btn_my" @click="row.toggleDetails" class="btn btn-sm btn-success"> {{
                                        row.detailsShowing ? 'Ẩn' : 'Hiện' }} lý
                                        do</button>



                                </template>
                                <template #row-details=row>

                                    <ul style="background:ghostwhite">
                                        <span> <strong>Lý do:</strong>{{ row.item.note }}</span>
                                    </ul>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="per_page4" :options="pageOptionss">
                                            </b-form-select>
                                            <b-pagination v-model="current_page4" :total-rows="rows_history"
                                                :per-page="per_page4" pills class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div> -->

                        </div>
                        <div class="modal-footer justify-content-between" style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="Showw" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form v-if="selectedAsset" :asset="selectedAsset" @submit.prevent="Showw">
                        <div class="modal-header">
                            <div class="modal-title">

                                <h4>Chi tiết Tài sản</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Model tài sản</label>
                                        <div v-for="types in asset_types" :key="types.id" v-bind:value="types.id"
                                            v-if="assett.asset_type_id == types.id">
                                            <input class="form-control" v-model="types.name" readonly />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Kho hàng</label>

                                        <div v-for="wareh in asset_warehouses" :key="wareh.id" v-bind:value="wareh.id"
                                            v-if="assett.asset_warehouse_id == wareh.id">
                                            <input class="form-control" v-model="wareh.name" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Tên tài sản</label>
                                <input class="form-control" v-model="nameAsset" readonly />

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label>
                                        <div v-for="group in asset_types" :key="group.asset_group.id"
                                            v-bind:value="group.asset_group.id" v-if="assett.asset_type_id == group.id">
                                            <input class="form-control" v-model="group.asset_group.name" readonly />

                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà cung cấp</label>
                                        <div v-if="assett.vendor_id == null">
                                            <input class="form-control" readonly />
                                        </div>
                                        <div v-else>
                                            <div v-for="vend in vendors" :key="vend.id" v-bind:value="vend.id">
                                                <div v-if="assett.vendor_id == vend.id">
                                                    <input class="form-control" v-model="vend.comp_name" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Số sê-ri</label>
                                        <input class="form-control" v-model="assett.seri" id="seri" name="seri" readonly />

                                    </div>
                                    <div class="col-sm-6">
                                        <label>Hashtag</label>
                                        <input class="form-control" v-model="assett.hashtag" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Giá mua</label>
                                        <vue-numeric :separator="separator" v-model="assett.amount" class="form-control"
                                            name="amount" placeholder="" readonly>
                                        </vue-numeric>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà sản xuất</label>
                                        <input class="form-control" v-model="assett.producer" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>SAP Code</label>
                                        <input class="form-control" v-model="assett.sap_code" id="seri" name="seri"
                                            readonly />

                                    </div>

                                    <div class="col-sm-6">
                                        <label>Ngày nhập</label>
                                        <input type="date" class="form-control" v-model="assett.add_date" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-6">
                                        <label>Ngày hết hạn sử dụng</label>
                                        <input type="date" class="form-control" v-model="assett.end_date" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <label>Hình ảnh</label>
                                        <div class="form-group mt-2 row">
                                            <div class="col-md-4 mt-2" v-for="(imgg, index) in assett.attachment_image"
                                                :key="index">
                                                <div class="card m-auto mt-2" id="card_img"
                                                    style="max-width:200px;height:auto;width:100%">
                                                    <img style="max-width:250px;height:200px;width:100%" v-if="imgg.base64"
                                                        :src="imgg.base64" class="img-responsive img-fluid" rounded="lg" />
                                                    <img style="max-width:250px;height:200px;width:100%" v-if="imgg.url"
                                                        :src="imgg.url" class="img-responsive img-fluid" rounded="lg" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    v-model="assett.description" readonly></textarea>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>File đính kèm</label>
                                        <br>

                                    </div>
                                </div>
                                <div class="form-group" v-for="(file, index) in assett.attachment_file" v-bind:key="index">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div style="font-size:12px;display:inline-block;width:78%">
                                                <span><i> {{ index + 1 + '.' + file.name }}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group table_custom">
                                    <label>Cấu hình Model</label>
                                    <b-table :items="asset_custom_field" :fields="custom_model_asset">
                                        <template #cell(#)='data'>
                                            <div>
                                                <span> {{ data.index + 1 }} </span>
                                            </div>
                                        </template>
                                        <template #cell(value)='data'>
                                            <input class='form-control form-control-sm' v-model='data.item.value'
                                                readonly />
                                        </template>

                                    </b-table>
                                </div>
                            </div>

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
                        <button @click="resetDataExcel()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
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
                        <div class="form-group">
                            <label>Thuộc tính đã chọn: </label>
                            <a class="float-right" style="cursor:pointer" @click="exportExcel()"> <i
                                    class="fas fa-download"></i> <u>Dowload templates Excel</u> </a>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr class="bg-dark font-weight-bold">
                                            <td class="px-md-1" v-for="field_excel in selected_fields">{{
                                                field_excel }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-secondary font-italic">
                                            <td v-for="data_excel in selectedExcel"> {{ data_excel.value }} <span
                                                    class="text-danger">
                                                    ({{ data_excel.label }}) </span> </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
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

                        <label>Danh sách Excel đã thêm vào: <i class="fas fa-sort-amount-down text-success"></i></label>
                        <div v-if="demo !== ''" type="button" class="btn bg-gradient-success float-right css-upload-excel"
                            style="position:sticky;top:10px;z-index:1;border-radius: 50%;" @click="uploadFiles()">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-dark font-weight-bold">
                                        <th>#</th>
                                        <th v-for="column in columns">{{ column }}</th>
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
        <b-modal ref="confirmationModal" hide-footer centered no-close-on-backdrop no-close-on-esc>
            <template #modal-title>
                Xác nhận
            </template>
            <div class="modal-body-excel">
                Bạn có muốn tiến hành trích xuất Tài sản ?
            </div>
            <div class="modal-actions">
                <b-button variant="success" @click="onCancelExport">
                    <download-excel :name="fileName" :data="tableData" title="Danh sách tài sản" type="xls">
                        Đồng ý
                    </download-excel>
                </b-button>
                <b-button variant="primary" @click="ExportDataReLoad">
                    Load lại tài sản
                </b-button>
                <b-button variant="danger" @click="onCancelExport">
                    Hủy bỏ
                </b-button>
            </div>
        </b-modal>

        <!-- update history v-2 -->
        <AssetHistoryV2 ref="history_v2"></AssetHistoryV2>

    </div>
</template>
 
<script>

    import AssetHistoryV2 from './AssetHistoryV2.vue';
import XLSX from 'xlsx';
import axios from 'axios';
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import moment from 'moment';
Vue.use(moment);
export default {
    props: {
        title: ""
    },
    components: {
        Treeselect,
        AssetHistoryV2
    },
    data() {
        return {
            searchAsset: "",
            columns: [],
            rowss: [],
            asset_fields: [],
            url_asset_field: "/api/asset/asset_field",
            properties: [],
            data: [
                { name: 'John', age: 30, address: 'New York' },
                { name: 'Jane', age: 25, address: 'London' },
                { name: 'Bob', age: 40, address: 'Paris' }
            ],
            image: '',
            page_url_asset: "api/asset/assets",
            page_url_assetPage: "api/asset/assetPage",
            page_url_chuabangiao: "api/asset/chuabangiao",
            page_url_dabangiao: "api/asset/dabangiao",
            chuabangiao: [],
            dabangiao: [],
            enabled: true,
            page_url_slocs: "api/asset/warehouse",
            page_url_vendors: "/api/category/vendors",
            page_url_status: "/api/asset/status",
            page_url_group: "api/asset/group",
            url_asset_transactions: "/api/asset/transaction",
            asset_warehouses: [],
            vendors: [],
            asset_statuses: [],
            asset_groups: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            per_page: 10,
            per_page2: 10,
            per_page3: 10,
            per_page4: 10,
            current_page: 1,
            current_page2: 1,
            current_page3: 1,
            current_page4: 1,

            filter: "",
            filter_chuabangiao: "",
            filter_dabangiao: "",


            pagesNumber: [],
            selected: [],
            selectAll: false,
            errors: {},
            data_tss: [],
            assett: {
                id: "",
                asset_type_id: null,
                asset_warehouse_id: null,
                name: "",
                asset_group_id: "",
                vendor_id: null,
                asset_status_id: "",
                seri: "",
                hashtag: "",
                sap_code: "",
                // department_id: null,
                amount: "",
                producer: "",
                add_date: "",
                end_date: "",
                description: "",
                index: "",
                index1: "",
                index2: "",
                asset_details: [],
                asset_details_del: [],
                assets: [],
                chuabangiao: [],
                dabangiao: [],
                attachment_file: [],
                attachment_file_del: [],
                attachment_image: [],
                attachment_image_del: [],
            },
            separator: '.',
            assets: [],
            loading: false,
            paginationdata: {},
            form_filter: {
                asset_warehouse_id: "",
                asset_group_id: "",
                asset_status_id: "",

            },
            token: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            pageOptionss: [10, 50, 100, 500, { value: this.rows_history, text: "All" }],
            sortBy: 'id',
            sortDesc: true,
            index: [],
            data_excels_assets: [],
            fields: [
                {
                    key: 'id',
                    label: '#',
                }, {
                    key: 'name',
                    label: 'Tài Sản',
                    class: "text-nowrap",
                }, {
                    key: 'asset_code',
                    label: 'Mã Tài Sản',
                    class: "text-nowrap",
                },
                {
                    key: 'department.name',
                    label: 'Phòng Ban',
                    class: "text-nowrap",
                }, {
                    key: 'user_id',
                    label: 'Người Sử Dụng',
                    class: "text-nowrap",
                }, {
                    key: 'asset_group_id',
                    label: 'Nhóm Tài Sản',
                    class: "text-nowrap",
                }, {
                    key: 'asset_warehouse_id',
                    label: 'Kho Hàng',
                    class: "text-nowrap",

                }, {
                    key: 'end_date',
                    label: 'Ngày Hết Hạn',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng Thái',
                    class: "text-nowrap",
                }, {
                    key: 'amount',
                    label: 'Giá Mua',
                    class: "text-nowrap",

                }, {
                    key: 'action',
                    label: '',
                    class: "text-nowrap",

                },
            ],

            custom_model_asset: [
                {
                    key: '#',
                    label: '#'
                },
                {
                    key: 'name',
                    label: 'Tên'
                },
                {
                    key: 'value',
                    label: 'Chi tiết'
                },
                {
                    key: 'action',
                    label: ''
                },
            ],
            lichsubangiao: [
                {
                    key: '#',
                    label: '#',


                },
                {
                    key: 'giaodich',
                    label: 'Giao dịch',

                }, {
                    key: 'created_by',
                    label: 'Người tạo',


                }, {
                    key: 'user_id',
                    label: 'Người sử dụng',


                }, {
                    key: 'department_id',
                    label: 'Phòng ban',


                }, {
                    key: 'created_att',
                    label: 'Ngày tạo/ Giao dịch',


                }, {
                    key: 'trangthai',
                    label: 'Trạng thái',

                }, {
                    key: 'confirm',
                    label: 'Confirm',


                }, {
                    key: 'note',
                    label: 'Lý do',

                },

            ],
            tableData: [],

            fileName: "Danh_Sach_Tai_San_" + moment(Date()).format("MM/DD/YYYY"),

            field: {
                id: '',
                name: '',
                index: ''
            },
            check: [],
            dt: {},
            url_tree_slocs: "api/asset/wall",
            tree_slocs: [],
            show_search: false,
            isHovered: false,
            isHovereds: false,
            isHoveredss: false,
            selectAll: false,
            edit: false,
            page_url_gettreevendos: "api/asset/gettreevendos",
            url_asset_type: "/api/asset/type",
            tree_assettype: [],
            type_details: [],
            type_details_url: "/api/asset/type",
            asset_types_update_2: [],
            url_asset_group: "/api/asset/group",
            asset_types: [],
            asset_groups: [],
            errors_amount: {},
            counter: 0,
            counter1: 0,
            counter2: 0,
            asset_uses: [],
            page_url_users: "api/user/allnew",
            disabled: false,
            timeout: null,
            users: [],
            url_asset_use: "api/asset/assetuse",
            page_url_treeDepartment: "/api/asset/gettreeDepartment",
            tree_department: [],
            page_url_department: "/api/category/departments",

            page_url_gettreeassettype: "api/asset/gettreeassettypets",

            page_url_lichsubangiao: "api/asset/lichsubangiaotaisan",
            lichsubangiaos: [],
            asset_transactions: [],
            asset_types_update: [],
            data_department: [],
            changetypes: '',
            errors_custom: {
                code: '',
                custom_field: [],
            },
            tree_vendos: [],
            departments: [],
            data_users: [],
            data_excels: [],
            demo: '',
            page_url_asset_import_item: "api/asset/asset_import_item",
            page_url_getDataExcel: "api/asset/getDataExcel",
            tableDatas: null,
            tableHeader: null,
            error_import_excel: {},
            data_department: [],
            attributes: [],
            page_url_export_excel: "api/asset/export_excel",
            selected_fields: ["Model Tài sản", "Số seri", "Tên tài sản", "Kho"],
            failed: {},
            paginate_assets: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            paginate_assets_chuabangiao: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_chuabangiao: [10, 20, 50, 100, 500],
            perPage_chuabangiao: 10,

            paginate_assets_dabangiao: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_dabangiao: [10, 20, 50, 100, 500],
            perPage_dabangiao: 10,
            selectedAsset: {},
            loadingEx: false,
            success: false,
            maxProgress: 100,
            progress: 0,
            startTime: 0,
            endTime: 0,
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.getSlocs();
        this.fetVendor();
        this.getStatus();
        this.fetchAsset();
        this.fetchChuabangiao();
        this.fetchDabangiao();
        this.getGroup();
        this.fetAssetType();
        this.fetAssetGroup();
        this.fetWarehouse_Tree();
        this.getUser();
        // this.fetchTransaction();
        this.getAsset_use();
        this.fetchLichsu();
        this.fetDepartment();
        this.fetVendor_Tree();
        this.fetchTypes();
        this.fetDepartment_Tree();
        this.fetAssettype_Tree();
        this.fetchField();
    },
    methods: {
        showHistoryV2(item){
            this.$refs.history_v2.showModal(item);
        },
        exportExcel() {
            const selectedFieldsArray = Array.isArray(this.selected_fields) ? this.selected_fields : [this.selected_fields];
            const headers = [...selectedFieldsArray];
            const data = this.selectedExcel.map(obj => headers.map(header => obj[header])) // lấy dữ liệu theo thuộc tính được chọn
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]) // tạo worksheet từ dữ liệu và header
            const workbook = XLSX.utils.book_new() // tạo workbook mới
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1') // thêm worksheet vào workbook
            XLSX.writeFile(workbook, 'data.xlsx') // tạo file excel và tải xuống
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
                    this.columns = rows[0];
                    this.rowss = rows.slice(1);
                }
            };

            reader.readAsBinaryString(file);
            $("#data_excel").modal("show");
        },
        showImportExcel() {
            $('#import_excel').modal('show');
        },
        showDataExcel() {
            $("#data_excel").modal("show");
        },
        resetDataExcel() {
            this.clearFileInput();
            this.selected_fields = ["Model Tài sản", "Số seri", "Tên tài sản", "Kho"];
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
        uploadFiles() {
            const file = this.$refs.fileInput.files[0];
            var page_url = this.page_url_asset_import_item;
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
                        this.fetchAsset();
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
        fetchField() {
            var page_url = this.url_asset_field;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_fields = res.data;
                    this.properties = [
                        {
                            name: "SAP code",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Model Tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Số seri",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Tên tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Kho",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Nhà cung cấp",
                            disabled: false,
                            fromAssetFields: false,
                        },

                        {
                            name: "Hashtag",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Giá mua",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Nhà sản xuất",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Ngày nhập",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Ngày hết hạn sử dụng",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Nội dung",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        ...this.asset_fields.map(field => {
                            return {
                                name: field.name,
                                disabled: false,
                                fromAssetFields: true,
                            }
                        }),

                    ];
                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetVendor_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_gettreevendos + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_vendos = data.data;
                }).catch(err => {

                    console.log(err);
                });
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
        fetDepartment_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_treeDepartment + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_department = data.data;
                }).catch(err => {

                    console.log(err);
                });
        },
        fetAssettype_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_gettreeassettype + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_assettype = data.data;
                }).catch(err => {

                    console.log(err);
                });
        },
        fetchTypes() {

            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.type_details = Array();
            const params = new URLSearchParams({

            });
            var page_url = this.type_details_url + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == 1) {
                        this.type_details = res.data;
                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchLichsu() {
            this.loading = true;
            this.lichsubangiaos = Array();
            let vm = this;

            var page_url = this.page_url_lichsubangiao;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.lichsubangiaos = res.data;
                    this.data_tss = res.data_ts;

                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        fetchTransaction() {
            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;

            var page_url = this.url_asset_transactions;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_transactions = data.data;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        getUser() {

            var page_url = this.page_url_users
            //console.log(page_url);
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
        handleClickInputFile() {
            this.$refs.file.click();
        },
        emitEvent(event) {
            // this.assett.attachment_file = [];
            for (let index = 0; index < event.target.files.length; index++) {
                const file = event.target.files[index];
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = () => {
                    const docs = {
                        name: file.name,
                        size: file.size,
                        ext: file.type.split("/").pop(),
                        lastModifiedDate: file.lastModifiedDate,
                        base64: reader.result
                    };

                    this.assett.attachment_file.push({ ...docs });

                };
            }

            event.target.value = "";
        },
        deleteFile(item, index) {
            // this.assett.attachment_file = [];

            if (confirm('Bạn muốn xoá file?')) {

                this.assett.attachment_file_del.push({ ...item });
                this.assett.attachment_file.splice(index, 1);
            }
        },
        downloadFile(idfile, filename) {
            var page_url = 'api/payment/downloadFile/' + idfile;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                responseType: 'arraybuffer',
                method: 'post'
            })
                .then(res => res.blob())
                .then(res => {

                    var newBlob = new Blob([res], { type: "octet/stream" });
                    if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                        window.navigator.msSaveOrOpenBlob(newBlob);
                        return;
                    }
                    const data = URL.createObjectURL(newBlob);
                    var link = document.createElement('a');
                    link.href = data;
                    link.download = filename;
                    link.click();

                    setTimeout(function () {
                        URL.revokeObjectURL(data)
                    }, 100);
                }).catch(err => console.log(err));

        },

        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createFile(files[0]);
        },
        createFile(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
            //file.target.value = "";
        },
        handleClickInputImg() {

            this.$refs.imgg.click();

        },
        deleteImg(item, index) {
            // this.assett.attachment_image = [];

            this.assett.attachment_image_del.push({ ...item });
            this.assett.attachment_image.splice(index, 1);

        },
        emitEventImg(event) {
            // this.assett.attachment_image = [];
            for (let index = 0; index < event.target.files.length; index++) {
                const imgg = event.target.files[index];
                let reader = new FileReader();
                reader.readAsDataURL(imgg);

                reader.onload = () => {
                    const docs = {
                        name: imgg.name,
                        size: imgg.size,
                        ext: imgg.type.split("/").pop(),
                        lastModifiedDate: imgg.lastModifiedDate,
                        base64: reader.result
                    };
                    this.assett.attachment_image.push({ ...docs });

                };
            }
            event.target.value = "";
            $("#imggdinhkem").collapse("show");

        },
        onImageChange(b) {
            let files = b.target.files || b.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(imgg) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(imgg);
        },
        getwarehouseNameName(asset_warehouse_id) {
            var obj = this.asset_warehouses.find(x => x.id == asset_warehouse_id);
            return obj != null ? obj.name : '';

        },
        getUserName(user_id) {
            var obj = this.users.find(x => x.id == user_id);

            if (obj ? obj.active : '' == 0) {
                return obj ? obj.name : '';
            } else {
                return (obj ? obj.name : '') + ' (Không hoạt động)';

            }
        },
        getGroupName(asset_group_id) {
            var obj = this.asset_groups.find(x => x.id == asset_group_id);
            return obj != null ? obj.name : '';

        },
        getdepartmentsName(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            return obj != null ? obj.name : '';

        },
        fetVendor() {
            var page_url = this.page_url_vendors;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.vendors = res.data;
                })
                .catch(err => console.log(err));
        },
        getSlocs() {
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
        getGroup() {
            var page_url = this.page_url_group
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                }
            }).then(res => res.json())
                .then(res => {
                    this.asset_groups = res.data;

                }).catch(err => {

                    console.log(err);
                });

        },
        fomatTime(created_at) {
            return moment(created_at).format('DD/MM/YYYY');
        },
        getStatus() {
            var page_url = this.page_url_status
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                }
            }).then(res => res.json())
                .then(res => {
                    this.asset_statuses = res.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        fetchAsset() {
            this.loading = true;
            this.assets = Array();
            let vm = this;
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
                asset_status_id: this.form_filter.asset_status_id,
                search: this.filter,

            });
            var page_url = this.page_url_assetPage + "/" + this.paginate_assets.current_page + "?per_page=" + this.perPage + '&' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.assets = res.data;
                    this.paginate_assets = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        onCancelExport() {
            this.$refs.confirmationModal.hide();

        },
        ExportDataReLoad() {
            this.ExportData();
            this.$refs.confirmationModal.hide();

        },
        ExportData() {
            this.loadingEx = true;
            this.progress = 0;
            this.startTime = performance.now();
            // Sử dụng Promise.all() để thực thi fetchAssetExportExcel() và cập nhật giá trị progress song song
            Promise.all([this.fetchAssetExportExcel(), this.updateLoading()])
                .then(() => {
                    this.endTime = performance.now();
                    const elapsedTime = this.endTime - this.startTime;
                    const timePerPercent = elapsedTime / this.maxProgress;

                    // Cập nhật giá trị progress
                    const timer = setInterval(() => {
                        if (this.progress < this.maxProgress) {
                            this.progress += 1;
                        } else {
                            clearInterval(timer);
                            this.loadingEx = false;
                            this.success = true;
                            this.$bvToast.toast('Loading Tài sản thành công, Bạn có thể tiến hành Trích xuất Tài sản', {
                                title: 'Thành công',
                                variant: 'success',
                                solid: true,
                            });
                            this.$refs.confirmationModal.show();
                        }
                    }, timePerPercent);
                })
                .catch((error) => {
                    console.error(error);
                    this.loadingEx = false;
                });
        },

        updateLoading() {
            return new Promise((resolve, reject) => {
                const intervalId = setInterval(() => {
                    if (this.progress < this.maxProgress) {
                        this.progress += 1;
                    } else {
                        clearInterval(intervalId);
                        resolve();
                    }
                }, 10);
            });
        },


        fetchAssetExportExcel() {
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
                asset_status_id: this.form_filter.asset_status_id,

            });
            const page_url = this.page_url_getDataExcel + '?' + params.toString();
            return fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.data_excels_assets = res.data;
                    this.data_excels = res.data_excel;
                    this.data_users = res.data_user;
                    this.data_department = res.data_department;
                    this.tableData = this.data_excels_assets.map((asset, index) => {
                        let department = this.data_department.find(d => d.id == asset.user?.department_id) || {};
                        let user = this.data_users.find(u => u.id == asset.user_id) || {};
                        let vendor = this.vendors.find(v => v.id == asset.vendor_id) || {};
                        let createBy = this.data_users.find(u => u.id == asset.create_by) || {};
                        let xacnhan = asset.user_id != null && asset.waiting == 1 ? 'Chưa xác nhận' : asset.user_id != null && asset.waiting == 0 ? 'Đã xác nhận' : '';
                        let tttaisan = asset.user_id == null && asset.asset_status_id == 1 && asset.department_id == null ? 'Chưa bàn giao' : asset.user_id == null
                            && asset.asset_status_id == 3 && asset.department_id == null ? 'Đã thanh lý' : asset.user_id != null || asset.department_id != null ? 'Đã bàn giao' : asset.user_id != null
                                && asset.asset_status_id == 9 && asset.department_id == null ? 'Xấu(Thủ kho đánh giá)' : '';
                        let group_name = asset.asset_group == null ? '' : asset.asset_group != null ? asset.asset_group.name : '';
                        let warehouse_name = asset.asset_warehouse == null ? '' : asset.asset_warehouse != null ? asset.asset_warehouse.name : '';
                        delete this.data_excels[index].id;
                        return {
                            "Số thứ tự": index,

                            "Mã tài sản": asset.asset_code,
                            "Model tài sản": asset.asset_type.name,
                            "Số seri": asset.seri,
                            "Tên tài sản": asset.name,
                            "Sap_CODE": asset.sap_code,
                            "Người sử dụng": user.name || '',
                            "Mã nhân viên": user.username || '',
                            "Bộ phận": department.name || '',
                            "Phòng ban sử dụng": asset.department_id != null ? asset.department.name : '',
                            "Trạng thái xác nhận": xacnhan,
                            "Trạng thái tài sản": tttaisan,


                            "Kho hàng": warehouse_name,

                            "Nhóm tài sản": group_name,

                            "Nhà cung cấp": vendor.comp_name || '',
                            "Nhà sản xuất": asset.producer,

                            "Hastag": asset.hashtag,

                            "Giá": asset.amount,

                            "Số lượng": asset.quantity,

                            "Ngày nhập": asset.add_date,
                            "Ngày hết hạn sử dụng": asset.end_date,
                            "Người tạo": createBy.name || '',

                            "Nội dung": asset.description,
                            ...this.data_excels[index],
                        };
                    });



                });
        },
        fetchChuabangiao() {
            this.loading = true;
            this.chuabangiao = Array();
            let vm = this;

            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
                asset_status_id: this.form_filter.asset_status_id,
                search: this.filter,
            });
            // var page_url = this.page_url_chuabangiao + '?' + params.toString();
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_chuabangiao + "/" + this.paginate_assets_chuabangiao.current_page + "?per_page=" + this.perPage_chuabangiao + '&' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.chuabangiao = res.data;
                    this.paginate_assets_chuabangiao = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        fetchDabangiao() {
            this.loading = true;
            this.dabangiao = Array();
            let vm = this;

            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
                asset_status_id: this.form_filter.asset_status_id,
                search: this.filter,
            });
            // var page_url = this.page_url_dabangiao + '?' + params.toString();
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_dabangiao + "/" + this.paginate_assets_dabangiao.current_page + "?per_page=" + this.perPage_dabangiao + '&' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.dabangiao = res.data;
                    this.paginate_assets_dabangiao = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        fetAssetType() {
            var page_url = this.url_asset_type;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_types = res.data;
                })
                .catch(err => console.log(err));
        },
        fetAssetGroup() {
            var page_url = this.url_asset_group;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_groups = res.data;
                    // console.log(res.data);
                })
                .catch(err => console.log(err));
        },
        delay() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.addAsset()
        },
        addAsset() {

            var page_url = this.page_url_asset;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.assett),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.assets.push(data.data);
                            this.chuabangiao.push(data.data);
                            // this.Add_custom_fields();
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addAsset").modal("hide");

                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            this.errors_custom.custom_field = data.data.errors.custom_field;
                            this.showError('Thông báo', 'Lỗi');
                            // this.showError('Thông báo', this.errors.seri[0]);

                            toastr.warning(this.errors_custom.custom_field, 'Thông báo');
                            this.hasErrors_custom;
                            // this.errors_amount = data.data.errors;
                            // toastr.warning(this.errors_amount.amount[0]);
                            toastr.warning(this.errors.check_user_department[0]);
                        }

                    })
                    .catch(err => console.log(err));
            } else {
                //update

                fetch(page_url + "/" + this.assett.id, {
                    method: "PUT",
                    body: JSON.stringify(this.assett),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        // console.log(this.files.attachment_image);
                        // console.log(data);
                        if (data.data.success == 1) {
                            let index = this.assett.index;
                            let index1 = this.assett.index1;
                            let index2 = this.assett.index2;
                            this.assett = { ...data.data.data };
                            this.$set(this.assets, index, { ... this.assett });
                            this.$set(this.chuabangiao, index1, { ... this.assett });
                            this.$set(this.dabangiao, index2, { ... this.assett });


                            this.loading = true;

                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addAsset").modal("hide");
                            this.reset();
                            // window.location.href = "/asset/assetss";
                        } else {
                            this.errors = data.data.errors;

                            this.showError('Thông báo', 'Lỗi');
                            // this.showError('Thông báo', this.errors.seri[0]);

                            // this.errors_amount = data.data.errors;
                            // toastr.warning(this.errors_amount.amount[0]);
                        }
                        this.loading = false;

                    })
                    .catch(err => console.log(err));
            }
        },
        showAsset() {
            this.reset();
            $("#addAsset").modal("show");
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        reset() {

            this.clearAllError();
            this.edit = false;
            this.assett.id = "";
            this.assett.asset_type_id = null;
            this.assett.asset_warehouse_id = null;
            this.assett.name = "";
            this.assett.asset_group_id = "";
            this.assett.vendor_id = null;
            this.assett.asset_status_id = "";
            this.assett.seri = "";
            this.assett.hashtag = "";
            this.assett.sap_code = "";
            // this.assett.department_id = null;
            this.assett.amount = "";
            this.assett.producer = "";

            this.assett.asset_details = [];
            this.assett.asset_details_del = [];
            this.assett.attachment_image = [];
            this.assett.attachment_image_del = [];
            this.assett.attachment_file = [];
            this.assett.attachment_file_del = [];
            this.assett.add_date = "";
            this.assett.end_date = "";
            this.assett.description = "";
            this.assett.index = -1;
            this.assett.index1 = -1;
            this.assett.index2 = -1;


        },
        clearAllError() {
            this.errors = {};
            this.errors_custom.custom_field = [];
        },
        filter_asset_warehouse_id() {

            this.fetchAsset();
            this.fetchChuabangiao();
            this.fetchDabangiao();
            this.fetAssetGroup();
            this.getSlocs();

        },
        filter_asset_group_id() {

            this.fetchAsset();
            this.fetchChuabangiao();
            this.fetchDabangiao();
            this.fetAssetGroup();
            this.getSlocs();

        },
        filter_asset_status_id() {

            this.fetchAsset();
            this.fetchChuabangiao();
            this.fetchDabangiao();
            this.fetAssetGroup();
            this.getSlocs();

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
        deleteAsset(id) {
            var obj = this.asset_uses.find(x => x.objectable_id == id);
            if (!obj) {

                this.$bvModal.msgBoxConfirm(this.$t("Xác nhận xóa") + "?", {
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
                            fetch(`${this.page_url_asset}/${id}`, {
                                method: 'delete',
                                headers: {
                                    Authorization: this.token,
                                    "content-type": "application/json",
                                    Accept: "application/json",
                                    "X-Requested-With": "XMLHttpRequest",
                                },
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.data.success == 1) {
                                        this.showMessage('Thông báo', 'Xoá thành công');
                                        this.fetchAsset();
                                        this.fetchChuabangiao();
                                        this.fetchDabangiao();
                                        this.reset();

                                    } else {
                                        this.errors = data.data.errors;
                                        toastr.warning(this.errors);
                                    }
                                });

                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            } else {
                toastr.warning('Tài sản đang được sử dụng  , vui lòng không xóa');
            }
        },
        info(assett) {
            this.clearAllError();
            let index = this.assets.findIndex(i => {
                return i.id == assett.id;
            });
            let index1 = this.chuabangiao.findIndex(i => {
                return i.id == assett.id;
            });
            let index2 = this.dabangiao.findIndex(i => {
                return i.id == assett.id;
            });
            this.edit = true;
            this.assett.id = assett.id;
            this.assett.asset_type_id = assett.asset_type_id;
            this.assett.asset_warehouse_id = assett.asset_warehouse_id;
            this.assett.name = assett.name;
            this.assett.asset_group_id = assett.asset_group_id;
            this.assett.vendor_id = assett.vendor_id;
            this.assett.asset_status_id = assett.asset_status_id;
            this.assett.seri = assett.seri;
            this.assett.hashtag = assett.hashtag;
            this.assett.sap_code = assett.sap_code;
            // this.assett.department_id = assett.department_id;
            this.assett.amount = assett.amount;
            this.assett.producer = assett.producer;
            this.assett.attachment_image = [...assett.attachment_image];
            if (this.assett.attachment_image == null) {
                this.assett.attachment_image = [];
            }
            this.assett.attachment_image_del = [];
            this.image = '';
            this.assett.attachment_file = [...assett.attachment_file];
            if (this.assett.attachment_file == null) {
                this.assett.attachment_file = [];
            }
            this.assett.attachment_file_del = [];
            this.assett.add_date = assett.add_date;
            this.assett.end_date = assett.end_date;
            this.assett.description = assett.description;
            this.assett.index = index;
            this.assett.index1 = index1;
            this.assett.index2 = index2;
            $("#ShowHistory").modal("show");
        },
        ShowHistory() {

        },
        showA(assett) {
            console.time('ShowA total time'); // Bắt đầu đếm
            this.clearAllError();
            console.time('findIndex time'); // Đo thời gian cho việc tìm vị trí
            let index = this.assets.findIndex(i => {
                return i.id == assett.id;
            });
            // let index1 = this.chuabangiao.findIndex(i => {
            //     return i.id == assett.id;
            // });
            // let index2 = this.dabangiao.findIndex(i => {
            //     return i.id == assett.id;
            // });
            console.timeEnd('findIndex time');

            console.time('edit mode setting time'); // Đo thời gian cho việc thiết lập chế độ chỉnh sửa
            this.edit = true;
            this.assett.id = assett.id;
            this.assett.asset_type_id = assett.asset_type_id;
            this.assett.asset_warehouse_id = assett.asset_warehouse_id;
            this.assett.name = assett.name;
            this.assett.asset_group_id = assett.asset_group_id;
            this.assett.vendor_id = assett.vendor_id;
            this.assett.asset_status_id = assett.asset_status_id;
            this.assett.seri = assett.seri;
            this.assett.hashtag = assett.hashtag;
            this.assett.amount = assett.amount;
            this.assett.sap_code = assett.sap_code;
            // this.assett.department_id = assett.department_id;
            this.assett.producer = assett.producer;
            this.assett.attachment_image = [...assett.attachment_image];
            if (this.assett.attachment_image == null) {
                this.assett.attachment_image = [];
            }
            this.assett.attachment_image_del = [];
            this.image = '';
            this.assett.attachment_file = [...assett.attachment_file];
            if (this.assett.attachment_file == null) {
                this.assett.attachment_file = [];
            }
            this.assett.attachment_file_del = [];
            this.assett.add_date = assett.add_date;
            this.assett.end_date = assett.end_date;
            this.assett.description = assett.description;
            // this.assett.index = index;
            // this.assett.index1 = index1;
            // this.assett.index2 = index2;
            console.timeEnd('edit mode setting time');

            // Và cứ tiếp tục cho các phần còn lại

            console.timeEnd('ShowA total time'); // Kết thúc đếm
            $("#Showw").modal("show");
            // const modal = document.getElementById('Showw');
            // modal.classList.add('show');
        },
        changetype(asset_type_id) {
            return this.changetypes = asset_type_id;
        },
        editAsset(assett) {
            this.clearAllError();
            this.fetAssetType();
            let index = this.assets.findIndex(i => {
                return i.id == assett.id;
            });
            let index1 = this.chuabangiao.findIndex(i => {
                return i.id == assett.id;
            });
            let index2 = this.dabangiao.findIndex(i => {
                return i.id == assett.id;
            });
            this.edit = true;
            this.assett.id = assett.id;
            this.assett.asset_type_id = assett.asset_type_id;
            this.assett.asset_warehouse_id = assett.asset_warehouse_id;
            this.assett.name = assett.name;
            this.assett.asset_group_id = assett.asset_group_id;
            this.assett.vendor_id = assett.vendor_id;
            this.assett.asset_status_id = assett.asset_status_id;
            this.assett.seri = assett.seri;
            this.assett.hashtag = assett.hashtag;
            this.assett.amount = assett.amount;
            this.assett.sap_code = assett.sap_code;
            // this.assett.department_id = assett.department_id;
            this.assett.producer = assett.producer;
            this.assett.attachment_image = [...assett.attachment_image];
            this.assett.asset_details = [...assett.asset_details];
            if (this.assett.attachment_image == null) {
                this.assett.attachment_image = [];
            }
            if (this.assett.attachment_image == null) {
                this.assett.attachment_image = [];
            }
            this.assett.attachment_image_del = [];
            this.assett.asset_details_del = [];
            this.image = '';
            this.assett.attachment_file = [...assett.attachment_file];
            if (this.assett.attachment_file == null) {
                this.assett.attachment_file = [];
            }
            this.assett.attachment_file_del = [];
            this.assett.add_date = assett.add_date;
            this.assett.end_date = assett.end_date;
            this.assett.description = assett.description;
            this.assett.index = index;
            this.assett.index1 = index1;
            this.assett.index2 = index2;
            $("#addAsset").modal("show");
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
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }
            this.fetchAsset();
            this.fetchChuabangiao();
            this.fetchDabangiao();

        },
        rowClass(item, type) {
            // this.per_page=10;
            if (!item || type !== 'row') return
            if (item.status === 'awesome') return 'table-success'
        },
        handleHover(hovered) {
            this.isHovered = hovered
        },
        handleHovers(hovereds) {
            this.isHovereds = hovereds
        },
        handleHoverss(hoveredss) {
            this.isHoveredss = hoveredss
        },
        chuabangiaoo(item, user_id) {
            if (!item || user_id !== 'row') return
            if (item.user_id != null || item.department_id != null) return 'd-none';
        },
        dabangiaoo(item, user_id) {
            if (!item || user_id !== 'row') return
            if (item.user_id === null && item.department_id === null) return 'd-none';
        },
        show_fields() {
            this.check = [];
            $("#Add_fields").modal("show");
        },
        // Add_custom_fields() {
        //     for (let index = 0; index < this.asset_custom_field.length; index++) {
        //         this.field.id = this.check[index].asset_type_id;
        //         this.field.name = this.check[index].name;
        //         let isexist = false;
        //         this.assett.asset_details.forEach(element => {
        //             if (element.name == this.check[index].name && element.id == this.assett.asset_type_id) {
        //                 isexist = true;
        //             }
        //             if (element.id !== this.assett.asset_type_id) {
        //                 this.assett.asset_details_del.push(...this.assett.asset_details);
        //                 console.log( this.assett.asset_details_del);
        //                 this.assett.asset_details = [];

        //             }

        //         });
        //         if (!isexist) {
        //             this.assett.asset_details.push({ ...this.field });
        //         }
        //     }

        //     $("#Add_fields").modal("hide");

        // },
        DeleteItems(item, index) {
            if (confirm(this.$t('form.confirm_delete') + '?')) {
                this.assett.asset_details_del.push({ ...item });
                this.assett.asset_details.splice(index, 1);
            }
        },
        resetFilter() {
            this.filter = "";
            this.fetchAsset();
        },
        resetFilterChuabangiao() {
            this.filter_chuabangiao = "";
            this.fetchChuabangiao();
        },
        resetFilterDabangiao() {
            this.filter_dabangiao = "";
            this.fetchDabangiao();
        },
        filterAll() {
            this.fetchAsset();
            this.fetchChuabangiao();
            this.fetchDabangiao();
        },
    },

    computed: {

        rows() {
            return this.assets.length;
        },
        rows_history() {
            return this.history.length;
        },
        placeholder() {
            return this.$t('form.search');
        },
        nameAsset() {
            let asset_name = '';
            for (let index = 0; index < this.asset_types.length; index++) {
                if (this.assett.asset_type_id == this.asset_types[index].id) {
                    this.assett.name = this.asset_types[index].name + ' - ' + this.assett.seri;
                }
                asset_name = this.assett.name;
            }
            return asset_name;
        },
        filter_asset_group() {
            let group = '';
            for (let index = 0; index < this.asset_types.length; index++) {
                if (this.assett.asset_type_id == this.asset_types[index].id) {
                    this.assett.asset_group_id = this.asset_types[index].asset_group_id;
                    group = this.asset_types[index].asset_group.name;
                }
            }
            return group;
        },
        asset_custom_field() {
            var news = [];
            for (var index = 0; index < this.asset_types.length; index++) {
                if (this.assett.asset_type_id == this.asset_types[index].id) {
                    var element = this.asset_types[index].asset_type_details;
                    for (var custom = 0; custom < element.length; custom++) {
                        news.push(element[custom]);

                    }
                    this.assett.asset_details = news;
                }
                if (this.edit == true) {
                    for (let index = 0; index < this.assets.length; index++) {
                        if (this.assett.id == this.assets[index].id) {
                            this.assett.asset_details = this.assets[index].asset_details;
                            this.assett.asset_details_del = [];
                        }

                        if (this.assett.id == this.assets[index].id && this.assett.asset_type_id !== this.assets[index].asset_type_id) {
                            this.assett.asset_details_del = this.assett.asset_details;
                            this.assett.asset_details = news;
                        }

                    }
                }
            }
            return this.assett.asset_details;
        },
        name_button_custom() {
            let name = '';
            for (var index = 0; index < this.asset_types.length; index++) {
                if (this.assett.asset_type_id == this.asset_types[index].id) {
                    name = this.asset_types[index].name;
                }
            }
            return name;
        },
        // history() {
        //     let his = [];
        //     let his_assets = [];
        //     for (let index = 0; index < this.data_tss.length; index++) {
        //         // const element = this.assets[index];
        //         if (this.assett.id == this.data_tss[index].id) {
        //             his.push(this.data_tss[index]);
        //         }
        //         // console.log(this.stockk.id == this.assets[index].id);

        //     }
        //     for (let index = 0; index < this.lichsubangiaos.length; index++) {
        //         // const element = this.assets[index];
        //         if (this.assett.id == this.lichsubangiaos[index].objectable_id) {
        //             his.push(this.lichsubangiaos[index]);
        //         }
        //         // console.log(this.stockk.id == this.assets[index].id);

        //     }
        //     // his_assets.concat([...his);
        //     return his;
        // },
        hasErrors_custom() {
            const x = document.querySelectorAll("div.table_custom");
            for (let index = 0; index < x.length; index++) {
                if (this.errors_custom.custom_field.length == 0) {
                    x[index].style.border = "1px solid white";
                }

                if (this.errors_custom.custom_field.length !== 0) {
                    console.log(this.errors_custom.custom_field.length);
                    x[index].style.border = "1px solid red";
                }
            }
        },
        selectedExcel() {
            return this.selected_fields.map(function (field) {
                return { label: field, value: "Nhập" };
            });
        },


    },
}

</script>
<style lang="scss" scoped>
#btn_refesh:hover {
    box-shadow: 1px 1px 10px orange;
    color: white;
    width: 65px;
}

.modal-body-excel {
    margin-bottom: 1rem;
    text-align: center;
    font-size: 20px;
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.modal-actions>b-button {
    margin-right: 1rem;
}

#card_img {
    box-shadow: 1px 1px 10px lightgray;
}

.animation div {

    animation: color 2s infinite;
}

.animation1 div::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: #3c8dbc;
    transition: width .1s;
}

.animation1 div:hover::after {
    width: 100%;
    transition: width .1s;
}

.tess {
    color: gray;
}

.form-group {
    margin-bottom: 5px !important;
}

@keyframes padding {
    50% {
        box-shadow: 1px 1px 10px cyan;
    }
}

#smoke {

    background: whitesmoke;
}

#smoke___BV_tab_button__ {

    color: gray;
}

#smoke___BV_tab_button__.active {
    box-shadow: 1px 1px 4px;
    color: #3c8dbc;
    animation: border_bottom 2s infinite;

}

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;
}

#shadow_btn:hover {

    color: white;
    box-shadow: 1px 1px 10px #3c8dbc;
}

.cssfile:hover {

    background: lightseagreen;
    color: #f8f9fa;
}

@keyframes border_bottom {

    50% {
        border-color: lightcyan;
    }


    50% {
        border-top-left-radius: 15px;
    }

    50% {
        box-shadow: 1px 1px 5px #3c8dbc;
    }
}

@keyframes color {
    50% {
        color: #3c8dbc;
    }
}

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;

}

button.snip0047 {
    border: 2px solid lightgray;
    background-color: white;
    border-radius: 5px;
    font-weight: 700;
    color: black;
    cursor: pointer;
    padding: 5px 30px;
    margin: 3px;
    display: inline-block;

    position: relative;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
}

button.snip0047 span {
    display: inline-block;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;

}

button.snip0047 i {
    font-size: 20px;
    right: 15px;
    color: black;
    position: absolute;
    opacity: 0;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
    -webkit-transition-delay: 0s;
    transition-delay: 0s;
}

button.snip0047:before {
    content: '';
    top: 0;
    right: 0;
    width: 0;
    position: absolute;
    height: 100%;
    background: rgb(32, 178, 170);
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
    -webkit-transition-delay: 0.15s;
    transition-delay: 0.15s;
}

button.snip0047:hover span,
button.snip0047.hover span {
    -webkit-transform: translate3d(-20px, 0px, 0px);
    transform: translate3d(-20px, 0px, 0px);
    opacity: 1;
}

button.snip0047:hover i,
button.snip0047.hover i {
    opacity: 1;
    color: white;
    -webkit-transition-delay: 0.15s;
    transition-delay: 0.15s;
}

button.snip0047:hover:before,
button.snip0047.hover:before {
    width: 44px;
    -webkit-transition-delay: 0s;
    transition-delay: 0s;
}

button.snip0047:active:before {
    background: rgba(255, 255, 255, 0.3);
}

.checkbox {
    transform: scale(1.5);
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

.label-search {
    flex: 0 0 100px;
}

.text-repair {
    background: linear-gradient(#ff7400, rgb(255 9 9 / 90%));
    color: white;
}
</style>