<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark mb-2"> <i class="fas fa-layer-group"></i>{{ $t(title) }}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-9">
                                <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                <div class="input-group mb-3" style="width:100%;">
                                    <input class="form-control form-control-navbar" id="search" type="search"
                                        style="width:50%" :placeholder="$t('form.search')" aria-label="Search"
                                        v-model="filter" />
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-default btn-flat mb-1">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="float-right">
                                    <!-- <b-dropdown right text="Import Excel TS" variant="primary"
                                        style="display:inline-block;">
                                        <b-dropdown-item @click.prevent="showImportExcel()">Công cụ dụng
                                            cụ</b-dropdown-item>
                                    </b-dropdown> -->
                                    <b-dropdown id="shadow_btn" right text="Tạo mới" variant="primary">
                                        <b-dropdown-item @click="showAssetStock()">Tạo mới công cụ dụng cụ
                                        </b-dropdown-item>
                                        <!-- <b-dropdown-item @click="showAssetStockExcel()">Nhập dữ liệu công cụ dụng cụ
                                        </b-dropdown-item> :fields="fieldexel"-->
                                        <b-dropdown-item>
                                            <download-excel :name="fileName" :data="tableData"
                                                title="Danh sách công cụ dụng cụ" type="xls">
                                                <i></i>
                                                <i></i> Trích xuất công cụ dụng cụ
                                            </download-excel>

                                        </b-dropdown-item>


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
                <div class="row">

                    <div class="col-md-9">
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
                    <div class="col-md-3">
                        <div class="row">
                        </div>
                    </div>
                </div>
                <div class="row" v-if="show_search"
                    style="background-color:rgb(244, 246, 249);border-radius: 5px;box-shadow: 1px 1px 5px lightgrey;">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""><i class="fas fa-folder text-secondary"></i>
                                <span class="text-secondary">Nhóm tài sản: </span></label>
                            <div class="col-md-3">
                                <select name="" @change="filter_asset_group_id()" class="form-control form-control-sm mt-1"
                                    id="asset_warehouse_id" v-model="form_filter.asset_group_id">
                                    <option value="">All</option>
                                    <option v-for="gr in asset_groups" v-bind:value="gr.id">
                                        {{ gr.name }}
                                    </option>
                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:left"
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-cube"></i> <span
                                    class="text-secondary">Kho hàng: </span></label>
                            <div class="col-md-3">
                                <select name="" @change="filter_asset_warehouse_id()"
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.asset_warehouse_id">
                                    <option value="">All</option>
                                    <option v-for="slocc in asset_warehouses" v-bind:value="slocc.id">
                                        {{ slocc.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">


                                <button id="btn_refesh" type="reset" class="btn btn-outline-warning btn-xs mt-2 "
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
                    <b-tab title="TẤT CẢ" title-link-class="animation1" active id="tatca">
                        <template #title>
                            <div class="tess">
                                <strong>TẤT CẢ</strong>
                            </div>
                        </template>
                        <div class="body">
                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" hover responsive :bordered="true"
                                :fields="fields" :items="asset_tools" :filter="filter" :sticky-header="true"
                                @row-clicked="showA" :current-page="current_page" :per-page="per_page"
                                :tbody-tr-class="rowClass">
                                <template #head(#)=data>
                                    <span class="text-success"> {{ data.label }} </span>
                                </template>
                                <template #head(sloc_tool)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(#)=data>
                                    <span> {{ data.index + 1 }} </span>
                                </template>
                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_type_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(quantity)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)="data">

                                    <span v-for="image in data.item.attachment_image">
                                        <b-avatar :src="image.url"> </b-avatar>
                                    </span>
                                    <span> {{ data.item.name }} </span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">
                                    <i class="fas fa-cube"></i>
                                    <span>{{ getwarehouseNameName(data.value) }}</span>
                                </template>
                                <template #cell(asset_type_id)="data">
                                    <i class="fas fa-dice-d6"></i>
                                    <span> {{ $t(getAssetTypeValueName(data.item.asset_type_id)) }} </span>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i>
                                    <span> {{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>
                                </template>
                                <template v-slot:cell(action)="data">
                                    <b-dropdown size="sm" v-if="data.item.quantity == data.item.sloc_tool" id="shadow_btn"
                                        text="Action" variant="outline-secondary" right style="display:flex;">


                                        <b-dropdown-item @click="editStock(data.item)">Chỉnh sửa</b-dropdown-item>

                                        <b-dropdown-item @click="deleteStock(data.item.id)">Xóa</b-dropdown-item>
                                        <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>



                                    </b-dropdown>
                                    <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>
                                    <!-- <div v-else> 
                                            <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>
                                        
                                         </div> -->
                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>

                            </b-table>

                            <!-- </div> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="perPage" :options="perPageOptions"
                                                @change="fetchStock">
                                            </b-form-select>
                                            <b-pagination @input="fetchStock" v-model="paginate_stocks.current_page"
                                                :total-rows="paginate_stocks.total" :per-page="perPage"
                                                :last-page="paginate_stocks.last_page" pills class="ml-1"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="CÓ THỂ BÀN GIAO" title-link-class="animation1" id="bangiao">
                        <template #title>
                            <div class="tess">
                                <strong>CÓ THỂ BÀN GIAO</strong>
                            </div>
                        </template>
                        <div class="body">
                            <b-table hover responsive :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :bordered="true"
                                :fields="fields" :items="cothebangiao" :filter="filter" @row-clicked="showA"
                                :sticky-header="true" :current-page="current_page2" :per-page="per_page2"
                                :tbody-tr-class="cothebangiaoo">
                                <template #head(#)=data>
                                    <span class="text-success"> {{ data.label }} </span>
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sloc_tool)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(#)=data>
                                    <span> {{ data.index + 1 }} </span>
                                </template>
                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_type_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(quantity)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)="data">

                                    <span v-for="image in data.item.attachment_image">
                                        <b-avatar :src="image.url"> </b-avatar>
                                    </span>
                                    <span> {{ data.item.name }} </span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">
                                    <i class="fas fa-cube"></i>
                                    <span>{{ getwarehouseNameName(data.value) }}</span>
                                </template>
                                <template #cell(asset_type_id)="data">
                                    <i class="fas fa-dice-d6"></i>
                                    <span> {{ $t(getAssetTypeValueName(data.item.asset_type_id)) }} </span>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i>
                                    <span> {{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>
                                </template>
                                <template v-slot:cell(action)="data">
                                    <b-dropdown size="sm" v-if="data.item.quantity == data.item.sloc_tool" id="shadow_btn"
                                        text="Action" variant="outline-secondary" right style="display:flex;">


                                        <b-dropdown-item @click="editStock(data.item)">Chỉnh sửa</b-dropdown-item>

                                        <b-dropdown-item @click="deleteStock(data.item.id)">Xóa</b-dropdown-item>
                                        <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>



                                    </b-dropdown>
                                    <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>
                                    <!-- <div v-else> 
                                            <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>
                                        
                                         </div> -->
                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>

                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="perPage_cothebangiao"
                                                :options="perPageOptions_cothebangiao" @change="fetchBangiao">
                                            </b-form-select>
                                            <b-pagination @input="fetchBangiao"
                                                v-model="paginate_stocks_cothebangiao.current_page"
                                                :total-rows="paginate_stocks_cothebangiao.total"
                                                :per-page="perPage_cothebangiao"
                                                :last-page="paginate_stocks_cothebangiao.last_page" pills
                                                class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="ĐÃ SỬ DỤNG HẾT" title-link-class="animation1" id="sudung">
                        <template #title>
                            <div class="tess">
                                <strong>ĐÃ SỬ DỤNG HẾT</strong>
                            </div>
                        </template>
                        <div class="body">
                            <b-table hover responsive :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :bordered="true"
                                :fields="fields" :items="dahet" :filter="filter" :current-page="current_page3"
                                :sticky-header="true" :per-page="per_page3" :tbody-tr-class="dahett" @row-clicked="showA">
                                <template #head(#)=data>
                                    <span class="text-success"> {{ data.label }} </span>
                                </template>
                                <template #cell(#)=data>
                                    <span> {{ data.index + 1 }} </span>
                                </template>
                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_type_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(quantity)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sloc_tool)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)="data">

                                    <span v-for="image in data.item.attachment_image">
                                        <b-avatar :src="image.url"> </b-avatar>
                                    </span>
                                    <span> {{ data.item.name }} </span>
                                </template>
                                <template #cell(asset_warehouse_id)="data">
                                    <i class="fas fa-cube"></i>
                                    <span>{{ getwarehouseNameName(data.value) }}</span>
                                </template>
                                <template #cell(asset_type_id)="data">
                                    <i class="fas fa-dice-d6"></i>
                                    <span> {{ $t(getAssetTypeValueName(data.item.asset_type_id)) }} </span>
                                </template>
                                <template #cell(asset_group_id)="data">
                                    <i class="fas fa-folder text-secondary"></i>
                                    <span> {{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>
                                </template>
                                <template v-slot:cell(action)="data">
                                    <b-dropdown size="sm" v-if="data.item.quantity == data.item.sloc_tool" id="shadow_btn"
                                        text="Action" variant="outline-secondary" right style="display:flex;">


                                        <b-dropdown-item @click="editStock(data.item)">Chỉnh sửa</b-dropdown-item>

                                        <b-dropdown-item @click="deleteStock(data.item.id)">Xóa</b-dropdown-item>
                                        <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>



                                    </b-dropdown>
                                    <button v-else @click="info(data.item)" id="shadow_btn"
                                        class="btn btn-sm btn-outline-secondary w-100">
                                        Xem lịch sử
                                    </button>
                                    <!-- <div v-else> 
                                            <b-dropdown-item @click="info(data.item)">Lịch sử</b-dropdown-item>
                                        
                                         </div> -->
                                </template>
                                <template #cell(amount)="data">
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>

                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="perPage_dahet" :options="perPageOptions_dahet"
                                                @change="fetchDahet">
                                            </b-form-select>
                                            <b-pagination @input="fetchDahet" v-model="paginate_stocks_dahet.current_page"
                                                :total-rows="paginate_stocks_dahet.total" :per-page="perPage_dahet"
                                                :last-page="paginate_stocks_dahet.last_page" pills
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
        <!-- popup-->
        <div class="modal fade bd-example-modal-lg" id="addStock" tabindex="-1" role="dialog" style="overflow-y:scroll">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="delay">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">TẠO MỚI CÔNG CỤ DỤNG CỤ</h4>
                                <h4 v-if="edit"> Cập Nhật công cụ dụng cụ</h4>
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
                                            :show-count="true" :multiple="false" v-model="stockk.asset_type_id"
                                            :options="tree_assettype"
                                            v-bind:class="hasError('asset_type_id') ? 'is-invalid' : ''">
                                        </treeselect>
                                        <span v-if="hasError('asset_type_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_type_id') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Kho hàng</label> <small class="text-red">( * )</small>
                                        <treeselect placeholder="Kho hàng" :disable-branch-nodes="true" :show-count="true"
                                            :multiple="false" v-model="stockk.asset_warehouse_id" :options="tree_slocs"
                                            v-bind:class="hasError('asset_warehouse_id') ? 'is-invalid' : ''">
                                        </treeselect>

                                        <span v-if="hasError('asset_warehouse_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_warehouse_id') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tên</label> <small class="text-red">( * )</small>
                                <input class="form-control" v-model="stockk.name" placeholder="Tên" id="name" name="name"
                                    v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('name') }}</strong>
                                </span>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label><small class="text-red">( * )</small>
                                        <input class="form-control" v-model="filter_asset_group"
                                            v-bind:class="hasError('asset_group_id') ? 'is-invalid' : ''" readonly />
                                        <!-- <select class="form-control" v-model="stockk.asset_group_id"
                                           >
                                            <option value="" disabled selected>Vui lòng chọn một</option>
                                            <option v-for="group in asset_types" :key="group.asset_group.id"
                                                v-bind:value="group.asset_group.id"
                                                v-if="stockk.asset_type_id == group.id"> {{ group.asset_group.name }}
                                            </option>
                                        </select> -->
                                        <span v-if="hasError('asset_group_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_group_id') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà cung cấp</label>

                                        <treeselect :disable-branch-nodes="true" placeholder="Nhà cung cấp"
                                            :show-count="true" :multiple="false" v-model="stockk.vendor_id"
                                            :options="tree_vendos">
                                        </treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Hashtag</label>
                                        <input class="form-control" v-model="stockk.hashtag"
                                            v-bind:class="hasError('hashtag') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('hashtag')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('hashtag') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà sản xuất</label>
                                        <input class="form-control" v-model="stockk.producer"
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
                                        <label>Giá mua trên một đơn vị</label>
                                        <vue-numeric :separator="separator" v-model="stockk.amount" class="form-control"
                                            name="amount" placeholder=""
                                            v-bind:class="hasError('amount') ? 'is-invalid' : ''">
                                        </vue-numeric>
                                        <span v-if="hasError('amount')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('amount') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Số lượng</label><small class="text-red">( * )</small>
                                        <!-- <input class="form-control" v-model="stockk.quantity" /> -->
                                        <input class="form-control" id="quantity" v-model="stockk.quantity" name="quantity"
                                            placeholder="" v-bind:class="hasError('quantity') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('quantity')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('quantity') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>SAP Code</label>

                                        <input class="form-control" v-model="stockk.sap_code"
                                            v-bind:class="hasError('sap_code') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('sap_code')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('sap_code') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <!--                                      
                                            <label>Phòng ban</label>
                                            <treeselect placeholder="Phòng ban" :disable-branch-nodes="true"
                                            :show-count="true" :multiple="false" v-model="stockk.department_id"
                                            :options="tree_department">
                                        </treeselect> -->


                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Ngày nhập</label>
                                <input type="date" class="form-control" v-model="stockk.add_date" />
                            </div>
                            <div class="form-group">
                                <div class="col-form-label-sm col-12">
                                    <input type="file" v-on:change="onImageChange" class="form-control"
                                        accept=".xlsx,.xls,image/x-png,image/gif,image/jpeg,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                        name="images[]" @change="emitEventImg($event)" id="Themimgg" style="display:none"
                                        ref="imgg" multiple><button type="button" class="btn btn-sm cssfile right"
                                        style="border:1px solid lightgray;padding:6px;font-weight:700;border-radius:5px;"
                                        v-on:click="handleClickInputImg()"> <i class="fas fa-image fa-lg"></i> Thêm ảnh
                                    </button>

                                    <div class="form-group mt-2 row">
                                        <div class="col-md-4 mt-2" v-for="(imgg, index) in stockk.attachment_image"
                                            :key="index">
                                            <div class="card m-auto mt-2" id="card_img"
                                                style="max-width:200px;height:auto;width:100%">
                                                <img style="max-width:250px;height:200px;width:100%" v-if="imgg.base64"
                                                    :src="imgg.base64" class="img-responsive img-fluid" rounded="lg" />
                                                <img style="max-width:250px;height:200px;width:100%" v-if="imgg.url"
                                                    :src="imgg.url" class="img-responsive img-fluid" rounded="lg" />
                                                <div class="card-footer">
                                                    <button id="show_btn_cancel" class="btn btn-outline-danger w-100"
                                                        @click.prevent="deleteImg(imgg, index)"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    v-model="stockk.description"
                                    v-bind:class="hasError('description') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('description') }}</strong>
                                </span>
                            </div>

                            <div class="form-group table_custom">
                                <label>Cấu hình Model</label>
                                <b-table responsive hover fixed :items="asset_custom_field" :fields="custom_model_asset">
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
                                </b-table>
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
        <div class="modal fade bd-example-modal-lg" id="Showw" tabindex="-1" role="dialog" style="overflow-y:scroll">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="Showw">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4>CHI TIẾT CÔNG CỤ DỤNG CỤ</h4>
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

                                        <div v-for="typee in asset_types" :key="typee.id" v-bind:value="typee.id"
                                            v-if="stockk.asset_type_id == typee.id">
                                            <input class="form-control" v-model="typee.name" readonly />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Kho hàng</label>
                                        <div v-for="wareh in asset_warehouses" :key="wareh.id" v-bind:value="wareh.id"
                                            v-if="stockk.asset_warehouse_id == wareh.id">
                                            <input class="form-control" v-model="wareh.name" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tên</label>

                                <input class="form-control" v-model="stockk.name" readonly />

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label>
                                        <input class="form-control" v-model="filter_asset_group" readonly />


                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà cung cấp</label>
                                        <div v-if="stockk.vendor_id == null">
                                            <input class="form-control" readonly />
                                        </div>
                                        <div v-else>
                                            <div v-for="vend in vendors" :key="vend.id" v-bind:value="vend.id">
                                                <div v-if="stockk.vendor_id == vend.id">
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
                                        <label>Hashtag</label>
                                        <input class="form-control" v-model="stockk.hashtag" readonly />

                                    </div>
                                    <div class="col-sm-6">
                                        <label>Nhà sản xuất</label>
                                        <input class="form-control" v-model="stockk.producer" readonly />

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Giá mua trên một đơn vị</label>
                                        <vue-numeric :separator="separator" v-model="stockk.amount" class="form-control"
                                            name="amount" placeholder="" readonly>
                                        </vue-numeric>

                                    </div>
                                    <div class="col-sm-6">
                                        <label>Số lượng</label>
                                        <input class="form-control" id="quantity" v-model="stockk.quantity" name="quantity"
                                            placeholder="" readonly />

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>SAP Code</label>

                                        <input class="form-control" v-model="stockk.sap_code" readonly />

                                    </div>
                                    <div class="col-sm-6">
                                        <!--                                      
                                            <label>Phòng ban</label>
                                            <div v-if="stockk.department_id !=null">
                                           <div v-for="dep in departments" :key="dep.id" v-bind:value="dep.id"
                                            v-if="stockk.department_id == dep.id">
                                            <input class="form-control" v-model="dep.name" readonly />
                                        </div>
                                    </div>
                                    <div v-else>  
                                              
                                              <input class="form-control" readonly />
                                              
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Ngày nhập</label>
                                <input class="form-control" v-model="stockk.add_date" readonly />
                            </div>
                            <div class="form-group">

                                <div class="col-sm-12">
                                    <label>Hình ảnh</label>
                                    <div class="form-group mt-2 row">
                                        <div class="col-md-4 mt-2" v-for="(imgg, index) in stockk.attachment_image"
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
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    v-model="stockk.description" readonly></textarea>

                            </div>

                            <div class="form-group table_custom">
                                <label>Cấu hình Model</label>
                                <b-table responsive hover fixed :items="asset_custom_field" :fields="custom_model_asset">
                                    <template #cell(#)='data'>
                                        <div>
                                            <span> {{ data.index + 1 }} </span>
                                        </div>
                                    </template>
                                    <template #cell(value)='data'>
                                        <input class='form-control form-control-sm' v-model='data.item.value' readonly />
                                    </template>
                                </b-table>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">

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

                                <h4>Lịch sử Công cụ dụng cụ</h4>
                            </div>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>


                        <div class="modal-body">

                            <div class="mb-1">
                                <span class="text-secondary ">Tên công cụ dụng cụ: <strong class="text-uppercase">{{
                                    stockk.name }} </strong></span>
                            </div>
                            <b-table :items="history" :fields="lichsubangiao" :filter="filter" stacked="md"
                                :current-page="current_page4" :per-page="per_page4" small>
                                <template #head(quantityy)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(user_id)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>

                                <template #head(department.name)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(note)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
                                <template #head(confirm)=data>
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
                                <template #head(giaodich)=data>
                                    <div>
                                        <span class="text-success"> {{ data.label }} </span>
                                    </div>
                                </template>
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
                                <template #cell(giaodich)=data>
                                    <div class="badge" v-if="data.item.giaodich == 'Tạo CCDC'">
                                        <span class="text-primary">Tạo CCDC</span>
                                    </div>
                                    <div class="badge" v-if="data.item.giaodich == 1">
                                        <span class="text-success">Bàn giao</span>
                                    </div>
                                    <div class="badge" v-if="data.item.giaodich == 2">
                                        <span class="text-danger">Thu hồi</span>

                                    </div>
                                    <div class="badge" v-if="data.item.giaodich == 3">
                                        <span class="text-danger">Thanh lý</span>

                                    </div>
                                </template>

                                <template #cell(created_by)=data>
                                    <span>{{ getUserName(data.item.created_by) }}</span>

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
                                        row.detailsShowing ? 'Ẩn' : 'Hiện' }} lý do</button>



                                </template>
                                <template #row-details=row>
                                    <div class="row-details">
                                        <ul>
                                            <li><strong>Lý do:</strong> {{ row.item.note }}</li>
                                        </ul>
                                    </div>
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
                            </div>





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

        <!-- Nhạp dữ liệu exel-->
        <div class="modal fade bd-example-modal-lg" id="addstockAssetexcel" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">NHẬP LIỆU CÔNG CỤ DỤNG CỤ</h4>
                                <!-- <h4 v-if="edit"> Cập Nhật công cụ dụng cụ</h4> -->
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Chọn file Excel</label>
                                <input type="file" v-on:change="addfile($event)" placeholder="Upload file"
                                    accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            </div>
                            <div class="form-group">
                                <label></label>
                                <input id="type" class="form-control"
                                    placeholder="Vui lòng chọn một file Excel với thứ tự các cột theo đúng với danh sách dưới đây. Download?"
                                    style="color:green;border:none;" disabled />
                            </div>
                            <div class="form-group" data-spy="scroll" data-offset="0" style="border:1px solid darkgrey">
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>01.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Mã hoặc
                                                    id Model tài sản (require)</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">model</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>02.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">ID hoặc
                                                    tên nhóm tài sản</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">category</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>03.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Số
                                                    sê-ri</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">serial_number</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>04.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span
                                                    style="margin-left:5px;">Hashtag</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">hashtag</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>05.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Tên tài
                                                    sản (require)</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">name</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>06.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Mã nhà
                                                    cung cấp</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">Vendor</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>07.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Nhà sản
                                                    xuất</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">manuafacture</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>08.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Giá
                                                    mua</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">price</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>09.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Ngày bắt
                                                    đầu</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">start_date</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>Kho hàng</label>
                                <select disabled class="form-control">
                                    <option selected>Vui lòng chọn một</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label></label>
                                <label>File mẫu <span><a href="#">tải xuống tại đây!</a></span></label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-success" style="width:47%;">
                                Nhập dữ liệu từ excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Nhập người sử dụng stock-->
        <div class="modal fade bd-example-modal-lg" id="userstock" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">NHẬP LIỆU NGƯỜI SỬ DỤNG CÔNG CỤ DỤNG CỤ</h4>
                                <!-- <h4 v-if="edit"> Cập Nhật công cụ dụng cụ</h4> -->
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Chọn file Excel</label>
                                <input type="file" class="form-control" />
                            </div>
                            <div class="form-group">
                                <p></p>
                                <input id="type" class="form-control"
                                    placeholder="Vui lòng chọn một file Excel với thứ tự các cột theo đúng với danh sách dưới đây. Download?"
                                    style="color:green;border:none;" disabled />
                            </div>
                            <p></p>
                            <div class="form-group" style="border:1px solid darkgrey">

                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>01.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Stock
                                                    ID/Code (require)</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">stock</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>02.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Handover
                                                    to (require)</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">handover</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bot" style="border-bottom:1px solid LightGray">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div style="text-align:center;padding:10px;"> <i class="fal fa-bars"
                                                    style="color:LightGray"></i></div>

                                        </div>
                                        <div class="col-sm-1" style="color:green">
                                            <div style="float: left; margin-top: 10px; width: 50%;height: 30px">
                                                <p>03.</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-8">
                                            <div style="margin-top:10px;"><i class="fas fa-grip-lines-vertical"
                                                    style="color:LightGray"></i><span style="margin-left:5px;">Số lượng
                                                    (require)</span></div>

                                        </div>
                                        <div class="col-sm-2">
                                            <div style="margin-top: 10px;float: right;">
                                                <span style="color:LightGray">quantity</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="form-group">
                                <label>Kho hàng</label>
                                <select disabled class="form-control">
                                    <option selected>Vui lòng chọn một</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label></label>
                                <label>File mẫu <span><a href="#">tải xuống tại đây!</a></span></label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:47%;">
                                Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-success" style="width:47%;">
                                Nhập dữ liệu từ excel
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
    </div>
</template>
<script>
import XLSX from 'xlsx';
import axios from 'axios';
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import moment from "moment";
Vue.use(moment);
export default {
    props: {
        title: ""
    },
    components: {
        Treeselect,
    },
    data() {
        return {
            image: '',
            file: File,
            tree_slocs: [],
            page_url_cothebangiao: "api/asset/cothebangiao",
            page_url_lichsubangiao: "api/asset/lichsubangiao",
            lichsubangiaos: [],
            data_tss: [],
            page_url_dahet: "api/asset/dahet",
            page_url_stock: "api/asset/stock",
            page_url_stockPage: "api/asset/stockPage",
            page_url_slocs: "api/asset/warehouse",
            page_url_vendors: "/api/category/vendors",
            url_asset_type: "/api/asset/type",
            url_asset_group: "/api/asset/group",
            asset_warehouses: [],
            vendors: [],
            cothebangiao: [],
            dahet: [],
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
            pagesNumber: [],
            selected: [],
            selectAll: false,
            errors: {},
            err: {},
            note: '',
            asset_types: [],
            asset_groups: [],
            stockk: {
                id: "",
                // department_id:null,
                sap_code: "",
                asset_type_id: null,
                asset_warehouse_id: null,
                name: "",
                asset_group_id: "",
                vendor_id: null,
                quantity: "",
                hashtag: "",
                sloc_tool: "",
                amount: "",
                producer: "",
                add_date: "",
                cothebangiao: [],
                dahet: [],
                description: "",
                index1: "",
                index2: "",
                asset_tools: [],
                index1: "",
                asset_details: [],
                asset_details_del: [],
                attachment_image: [],
                attachment_image_del: [],
            },

            field: {
                id: '',
                name: '',
                index: ''
            },
            page_url_department: "/api/category/departments",
            fileName: "Danh_Sach_Cong_Cu_Dung_Cu_" + moment(Date()).format("MM/DD/YYYY"),
            url_asset_transactions: "/api/asset/transaction",
            asset_transactions: [],

            asset_tools: [],
            loading: false,
            paginationdata: {},
            erros_asset_code: {},
            form_filter: {
                asset_warehouse_id: "",
                asset_group_id: "",
            },
            isShow: true,
            token: "",
            locale_format: 'de-DE',
            tableData: [],
            tree_assettype: [],
            page_url_gettreeassettype: "api/asset/gettreeassettype",
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
                    key: 'department.name',
                    label: 'Phòng ban',

                }, {
                    key: 'created_att',
                    label: 'Ngày tạo',

                }, {
                    key: 'quantityy',
                    label: 'Số lượng',

                }, {
                    key: 'confirm',
                    label: 'Confirm',

                }, {
                    key: 'note',
                    label: 'Lý do',

                },

            ],
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            pageOptionss: [10, 50, 100, 500, { value: this.rows_history, text: "All" }],

            fields: [
                {
                    key: '#',
                    label: '#',
                }, {
                    key: 'name',
                    label: 'Công Cụ Dụng Cụ',
                    class: "text-nowrap",
                }, {
                    key: 'asset_code',
                    label: 'Mã CCDC',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số Lượng Còn Lại',
                    class: "text-nowrap",
                }, {
                    key: 'sloc_tool',
                    label: 'Nhập Kho',
                    class: "text-nowrap",
                }, {
                    key: 'asset_type_id',
                    label: 'Model Tài Sản',
                    class: "text-nowrap",
                }, {
                    key: 'asset_group_id',
                    label: 'Nhóm Tài Sản',
                    class: "text-nowrap",
                }, {
                    key: 'asset_warehouse_id',
                    label: 'Kho Hàng',
                    class: "text-nowrap",
                },
                {
                    key: 'amount',
                    label: 'Giá Mua',
                    class: "text-nowrap",
                }, {
                    key: 'action',
                    label: '',
                    class: "text-nowrap",
                },
            ],
            a: '',
            separator: '.',
            counter: 0,
            action: 0,
            sortBy: 'id',
            sortDesc: true,
            selectAll: false,
            edit: false,
            show_search: false,
            url_tree_slocs: "api/asset/wall",
            url_asset_use: "api/asset/assetuse",
            asset_uses: [],
            disabled: false,
            users: [],
            page_url_gettreevendos: "api/asset/gettreevendos",

            page_url_gettreeassettype: "api/asset/gettreeassettype",
            page_url_users: "api/user/all",
            page_url_treeDepartment: "/api/asset/gettreeDepartment",
            tree_department: [],
            check: [],
            departments: [],
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
                }, {
                    key: 'action',
                    label: ''
                },
            ],
            errors_custom: {
                code: '',
                custom_field: [],
            },
            tree_vendos: [],
            data_users: [],
            data_excels: [],

            paginate_stocks: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            paginate_stocks_cothebangiao: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_cothebangiao: [10, 20, 50, 100, 500],
            perPage_cothebangiao: 10,

            paginate_stocks_dahet: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_dahet: [10, 20, 50, 100, 500],
            perPage_dahet: 10,

            demo: '',
            columns: [],
            rowss: [],
            selected_fields: ["Model Tài sản", "Tên công cụ dụng cụ", "Nhóm", "Kho", "Số lượng"],
            properties: [],
            failed: {},
            tableDatas: null,
            tableHeader: null,
            error_import_excel: {},
            page_url_asset_tool_import_item: "api/asset/asset_tool_import",

            asset_fields: [],
            url_asset_field: "/api/asset/asset_field",
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
        this.fetchStock();
        this.fetchBangiao();
        this.fetchDahet();
        this.fetAssetType();
        this.fetAssetGroup();
        this.fetWarehouse_Tree();
        this.getAsset_use();
        this.fetchTransaction();
        this.getUser();
        this.fetchLichsu();
        this.fetDepartment_Tree();
        this.fetDepartment();
        this.fetVendor_Tree();
        this.fetAssettype_Tree();
        this.fetchField();
    },
    methods: {
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
                            name: "Tên công cụ dụng cụ",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Nhóm",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Kho",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Số lượng",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        // {
                        //     name: "Nhà cung cấp",
                        //     disabled: false,
                        //     fromAssetFields: false,
                        // },

                        {
                            name: "Hashtag",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        {
                            name: "Giá mua trên một đơn vị",
                            disabled: false,
                            fromAssetFields: false,
                        },
                        // {
                        //     name: "Nhà sản xuất",
                        //     disabled: false,
                        //     fromAssetFields: false,
                        // },
                        {
                            name: "Ngày nhập",
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
        uploadFiles() {
            const file = this.$refs.fileInput.files[0];
            var page_url = this.page_url_asset_tool_import_item;
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
                        this.fetchBangiao();
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
            const data = this.selectedExcel.map(obj => headers.map(header => obj[header])) // lấy dữ liệu theo thuộc tính được chọn
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]) // tạo worksheet từ dữ liệu và header
            const workbook = XLSX.utils.book_new() // tạo workbook mới
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1') // thêm worksheet vào workbook
            XLSX.writeFile(workbook, 'data.xlsx') // tạo file excel và tải xuống
        },
        chooseNewFile() {
            this.clearFileInput();
            this.$refs.fileInput.click();
        },
        showDataExcel() {
            $("#data_excel").modal("show");
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
                const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1, dateNF: 'yyyy-mm-dd' });
               console.log(XLSX.utils)
               console.log(worksheet)
               console.log(XLSX.utils)

                if (rows.length > 0) {
                    this.columns = rows[0];
                    this.rowss = rows.slice(1);
                }
            };

            reader.readAsBinaryString(file);
            $("#data_excel").modal("show");
        },
        resetDataExcel() {
            this.clearFileInput();
            // this.selected_fields = ["Model Tài sản", "Số seri", "Tên tài sản", "Kho"];
        },
        clearFileInput() {
            this.demo = '';
            const fileInput = this.$refs.fileInput;
            fileInput.type = 'text';
            fileInput.type = 'file';
        },
        fetchStock(page) {
            this.loading = true;
            this.asset_tools = Array();
            let vm = this;

            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
            });
            var page_url = this.page_url_stockPage + "/" + this.paginate_stocks.current_page + "?per_page=" + this.perPage + '&' + params.toString();

            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    this.asset_tools = res.data;
                    this.paginate_stocks = res.paginate;
                    this.data_excels = res.data_excel;
                    this.data_users = res.data_user;


                    this.tableData = this.asset_tools.map((asset, index) => {
                        const obj_create_by = this.data_users.find(x => x.id == asset.create_by);
                        const obj_vendor = this.vendors.find(x => x.id == asset.vendor_id);
                        let group_name = asset.asset_group == null ? '' : asset.asset_group != null ? asset.asset_group.name : '';
                        let warehouse_name = asset.asset_warehouse == null ? '' : asset.asset_warehouse != null ? asset.asset_warehouse.name : '';
                        return {
                            "Số thứ tự": index,
                            "Mã tài sản": asset.asset_code,
                            "Model CCDC": asset.asset_type.name,
                            "Tên CCDC": asset.name,
                            "SAP Code": asset.sap_code,
                            "Kho hàng": warehouse_name,
                            "Nhóm CCDC": group_name,
                            "Nhà cung cấp": obj_vendor?.comp_name || '',
                            "Nhà sản xuất": asset.producer,
                            "Hastag": asset.hashtag,
                            "Giá": asset.amount,
                            "Số lượng": asset.quantity,
                            "Số lượng tồn": asset.sloc_tool,
                            "Ngày nhập": asset.add_date,
                            "Người tạo": obj_create_by?.name || '',
                            "Nội dung": asset.description,
                            ...this.data_excels[index],
                        };
                    });


                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
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
        ShowHistory() {

        },
        fomatTime(created_at) {
            return moment(created_at).format('DD/MM/YYYY');
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
        getUserName(user_id) {
            var obj = this.users.find(x => x.id == user_id);

            if (obj ? obj.active : '' == 0) {
                return obj ? obj.name : '';
            } else {
                return (obj ? obj.name : '') + ' (Không hoạt động)';

            }
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
        delay() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.addStock();
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
        fetchBangiao(page) {
            this.loading = true;
            this.cothebangiao = Array();
            let vm = this;
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
            });
            var page_url = this.page_url_cothebangiao + "/" + this.paginate_stocks_cothebangiao.current_page + "?per_page=" + this.perPage_cothebangiao + '&' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.cothebangiao = res.data;
                    this.paginate_stocks_cothebangiao = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        fetchDahet(page) {
            this.loading = true;
            this.dahet = Array();
            let vm = this;
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                asset_group_id: this.form_filter.asset_group_id,
            });
            var page_url = this.page_url_dahet + "/" + this.paginate_stocks_dahet.current_page + "?per_page=" + this.perPage_dahet + '&' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.dahet = res.data;
                    this.paginate_stocks_dahet = res.paginate;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
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
        cothebangiaoo(item, quantity) {
            if (!item || quantity !== 'row') return
            if (item.quantity == 0) return 'd-none';
        },
        dahett(item, quantity) {
            if (!item || quantity !== 'row') return
            if (item.quantity > 0) return 'd-none';
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
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }
            this.fetchStock();
        },
        showAssetStockExcel() {
            $("#addstockAssetexcel").modal("show");
        },
        showAssetStockuser() {
            $("#userstock").modal("show");
        },
        getwarehouseNameName(asset_warehouse_id) {
            var obj = this.asset_warehouses.find(x => x.id == asset_warehouse_id);
            return obj != null ? obj.name : '';
        },
        getAssetTypeValueName(id) {
            var obj = this.asset_types.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        getAssetGroupValueName(id) {
            var obj = this.asset_groups.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        deleteImg(item, index) {
            // this.stockk.attachment_image = [];

            if (confirm('Bạn muốn xoá ảnh?')) {

                this.stockk.attachment_image_del.push({ ...item });
                this.stockk.attachment_image.splice(index, 1);
            }
        },
        emitEventImg(event) {
            // this.stockk.attachment_image = [];

            for (let index = 0; index < event.target.files.length; index++) {
                const imgg = event.target.files[index];
                //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
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
                    this.stockk.attachment_image.push({ ...docs });

                };
            }
            event.target.value = "";
            $("#imggdinhkem").collapse("show");

        },
        handleClickInputImg() {

            this.$refs.imgg.click();

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
            // file.target.value='';
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
        fetVendor() {
            var page_url = this.page_url_vendors;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.vendors = res.data;
                })
                .catch(err => console.log(err));
        },

        addStock() {

            var page_url = this.page_url_stock;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.stockk),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.asset_tools.push(data.data);
                            this.cothebangiao.push(data.data);

                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addStock").modal("hide");

                            this.reset();


                        } else {
                            this.errors = data.data.errors;
                            this.errors_custom.custom_field = data.data.errors.custom_field;

                            toastr.warning(this.errors_custom.custom_field, 'Thông báo');
                            this.hasErrors_custom;






                        }

                    })
                    .catch(err => console.log(err));
            } else {
                //update

                fetch(page_url + "/" + this.stockk.id, {
                    method: "PUT",
                    body: JSON.stringify(this.stockk),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {


                        if (data.data.success == 1) {


                            this.$set(this.asset_tools, this.stockk.index, { ...data.data.data });
                            this.$set(this.cothebangiao, this.stockk.index1, { ...data.data.data });
                            this.$set(this.dahet, this.stockk.index2, { ...data.data.data });

                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addStock").modal("hide");
                            this.reset();


                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Lỗi');
                            toastr.warning(this.errors.checkquantity[0]);

                        }
                        this.loading = false;

                    })
                    .catch(err => console.log(err));
            }


        },
        showAssetStock() {
            this.reset();
            $("#addStock").modal("show");
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            this.stockk.id = "";
            this.stockk.asset_type_id = null;
            this.stockk.asset_warehouse_id = null;
            this.stockk.name = "";
            this.stockk.asset_group_id = "";
            this.stockk.vendor_id = null;
            this.stockk.quantity = "";
            this.stockk.hashtag = "";
            this.stockk.amount = "";
            // this.stockk.department_id = null;
            this.stockk.sap_code = null;
            this.stockk.producer = "";
            this.stockk.attachment_image = [];
            this.stockk.asset_details = [];
            this.stockk.asset_details_del = [];
            this.stockk.attachment_image_del = [];
            this.stockk.add_date = "";
            this.stockk.description = "";
            this.stockk.index = -1;
            this.stockk.index1 = -1;
            this.stockk.index2 = -1;
        },
        clearAllError() {
            this.errors = {};
            this.errors_custom.custom_field = [];


        },
        filter_asset_group_id() {
            // this.counter++;
            // if (this.form_filter.asset_group_id !== "" && this.counter > 1 || this.form_filter.asset_group_id == "" && this.counter > 1) {
            this.fetchStock();
            this.fetchBangiao();
            this.fetchDahet();
            this.fetAssetGroup();
            this.getSlocs();
            //     this.counter = 0;
            // }

        },
        filter_asset_warehouse_id() {
            // this.action++;
            // if (this.form_filter.asset_warehouse_id !== "" && this.action > 1 || this.form_filter.asset_warehouse_id == "" && this.action > 1) {
            this.fetchStock();
            this.fetchBangiao();
            this.fetchDahet();
            this.fetAssetGroup();
            this.getSlocs();
            //     this.action = 0;
            // }

        },
        deleteStock(id) {

            var obj = this.asset_uses.find(x => x.objectable_id == id);
            // return ;
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
                            fetch(`${this.page_url_stock}/${id}`, {
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
                                        this.fetchStock();
                                        this.fetchBangiao();
                                        this.fetchDahet();
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

                toastr.warning('Một lượng CCDC đã được sử dụng  , vui lòng không xóa');
            }


            // if(confirm("Xác nhận xoá ?")){

            // }
        },


        info(stockk) {
            this.clearAllError();

            let index = this.asset_tools.findIndex(i => {
                return i.id == stockk.id;
            });
            let index1 = this.cothebangiao.findIndex(i => {
                return i.id == stockk.id;
            });
            let index2 = this.dahet.findIndex(i => {
                return i.id == stockk.id;
            });
            this.stockk.id = stockk.id;
            this.stockk.name = stockk.name;






            this.stockk.index = index;
            this.stockk.index1 = index1;

            this.stockk.index2 = index2;
            $("#ShowHistory").modal("show");
        },
        editStock(stockk) {
            this.clearAllError();

            let index = this.asset_tools.findIndex(i => {
                return i.id == stockk.id;
            });
            let index1 = this.cothebangiao.findIndex(i => {
                return i.id == stockk.id;
            });
            let index2 = this.dahet.findIndex(i => {
                return i.id == stockk.id;
            });
            this.edit = true;
            this.stockk.id = stockk.id;


            this.stockk.asset_type_id = stockk.asset_type_id;
            this.stockk.asset_warehouse_id = stockk.asset_warehouse_id;

            this.stockk.name = stockk.name;
            this.stockk.asset_group_id = stockk.asset_group_id;

            this.stockk.vendor_id = stockk.vendor_id;

            this.stockk.quantity = stockk.quantity;
            this.stockk.hashtag = stockk.hashtag;
            this.stockk.amount = stockk.amount;
            // this.stockk.department_id = stockk.department_id;
            this.stockk.sap_code = stockk.sap_code;

            this.stockk.producer = stockk.producer;
            this.stockk.attachment_image = [...stockk.attachment_image];
            this.stockk.asset_details = [...stockk.asset_details];
            if (this.stockk.attachment_image == null) {
                this.stockk.attachment_image = [];
            }
            this.stockk.asset_details_del = [];
            this.stockk.attachment_image_del = [];
            this.image = '';


            this.stockk.add_date = stockk.add_date;
            this.stockk.description = stockk.description;



            this.stockk.index = index;
            this.stockk.index1 = index1;

            this.stockk.index2 = index2;

            $("#addStock").modal("show");
        },
        showA(stockk) {
            this.clearAllError();

            let index = this.asset_tools.findIndex(i => {
                return i.id == stockk.id;
            });
            let index1 = this.cothebangiao.findIndex(i => {
                return i.id == stockk.id;
            });
            let index2 = this.dahet.findIndex(i => {
                return i.id == stockk.id;
            });
            this.edit = true;
            this.stockk.id = stockk.id;


            this.stockk.asset_type_id = stockk.asset_type_id;
            this.stockk.asset_warehouse_id = stockk.asset_warehouse_id;

            this.stockk.name = stockk.name;
            this.stockk.asset_group_id = stockk.asset_group_id;

            this.stockk.vendor_id = stockk.vendor_id;

            this.stockk.quantity = stockk.quantity;
            this.stockk.hashtag = stockk.hashtag;
            this.stockk.amount = stockk.amount;
            this.stockk.department_id = stockk.department_id;
            this.stockk.sap_code = stockk.sap_code;

            this.stockk.producer = stockk.producer;
            this.stockk.attachment_image = [...stockk.attachment_image];
            // this.stockk.asset_details = [...stockk.asset_details];
            if (this.stockk.attachment_image == null) {
                this.stockk.attachment_image = [];
            }
            // this.stockk.asset_details_del = [];
            this.stockk.attachment_image_del = [];
            this.image = '';


            this.stockk.add_date = stockk.add_date;
            this.stockk.description = stockk.description;



            this.stockk.index = index;
            this.stockk.index1 = index1;

            this.stockk.index2 = index2;

            $("#Showw").modal("show");
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
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.status === 'awesome') return 'table-success'
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        show_fields() {
            this.check = [];
            $("#Add_fields").modal("show");
        },
        Add_custom_fields() {
            for (let index = 0; index < this.check.length; index++) {
                this.field.id = this.check[index].asset_type_id;
                this.field.name = this.check[index].name;
                let isexist = false;
                this.stockk.asset_details.forEach(element => {
                    if (element.name == this.check[index].name) {
                        isexist = true;
                    }
                    if (element.id !== this.stockk.asset_type_id) {
                        this.stockk.asset_details_del.push(...this.stockk.asset_details);
                        console.log(this.stockk.asset_details_del);
                        this.stockk.asset_details = [];
                    }
                });
                if (!isexist) {
                    this.stockk.asset_details.push({ ...this.field });
                }
            }

            $("#Add_fields").modal("hide");

        },
        DeleteItems(item, index) {
            if (confirm(this.$t('form.confirm_delete') + '?')) {
                this.stockk.asset_details_del.push({ ...item });
                this.stockk.asset_details.splice(index, 1);
            }
        },
        showImportExcel() {
            $('#import_excel').modal('show');
        },


    },
    computed: {
        selectedExcel() {
            return this.selected_fields.map(function (field) {
                return { label: field, value: "Nhập" };
            });
        },
        rows() {
            return this.asset_tools.length;
        },
        placeholder() {
            return this.$t('form.search');
        },
        rows_history() {
            return this.history.length;
        },
        filter_asset_group() {
            let group = '';
            for (let index = 0; index < this.asset_types.length; index++) {
                if (this.stockk.asset_type_id == this.asset_types[index].id) {
                    this.stockk.asset_group_id = this.asset_types[index].asset_group_id;
                    group = this.asset_types[index].asset_group.name;
                }
            }
            return group;
        },
        history() {
            let his = [];
            for (let index = 0; index < this.data_tss.length; index++) {
                if (this.stockk.id == this.data_tss[index].id) {
                    his.push(this.data_tss[index]);
                }

            }
            for (let index = 0; index < this.lichsubangiaos.length; index++) {
                if (this.stockk.id == this.lichsubangiaos[index].objectable_id) {
                    his.push(this.lichsubangiaos[index]);
                    console.log(this.lichsubangiaos[index]);
                }

            }
            return his;
        },
        asset_custom_field() {
            var news = [];
            for (var index = 0; index < this.asset_types.length; index++) {
                if (this.stockk.asset_type_id == this.asset_types[index].id) {
                    var element = this.asset_types[index].asset_type_details;
                    for (var custom = 0; custom < element.length; custom++) {
                        news.push(element[custom]);
                    }
                    this.stockk.asset_details = news;
                }
                if (this.edit == true) {
                    for (let index = 0; index < this.asset_tools.length; index++) {
                        if (this.stockk.id == this.asset_tools[index].id) {
                            this.stockk.asset_details = this.asset_tools[index].asset_details;
                            this.stockk.asset_details_del = [];
                        }
                        if (this.stockk.id == this.asset_tools[index].id && this.stockk.asset_type_id !== this.asset_tools[index].asset_type_id) {
                            this.stockk.asset_details_del = this.stockk.asset_details;
                            this.stockk.asset_details = news;
                        }
                    }
                }
            }
            return this.stockk.asset_details;
        },
        name_button_custom() {
            let name = '';
            for (var index = 0; index < this.asset_types.length; index++) {
                if (this.stockk.asset_type_id == this.asset_types[index].id) {
                    name = this.asset_types[index].name;
                }
            }
            return name;
        },
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
    },
}

</script>
<style  lang="scss" scoped>
#btn_refesh:hover {
    box-shadow: 1px 1px 10px orange;
    color: white;
    width: 65px;
}

#shadow_btn:hover {

    color: white;
    box-shadow: 1px 1px 10px #3c8dbc;

}

#card_img {
    box-shadow: 1px 1px 10px lightgray;
}

.animation div {

    animation: color 2s infinite;
}

.row-details {
    border: 2px solid lightgrey;
    background: ghostwhite;
    padding: 10px;
    margin-bottom: 10px;
}

.row-details ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.row-details li {
    margin-bottom: 5px;
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

@keyframes color {
    50% {
        color: #3c8dbc;
    }
}

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;
}

.form-group {
    margin-bottom: 5px !important;
}

#type::placeholder {
    color: green;
}

.cssfile:hover {

    background: lightseagreen;
    color: #f8f9fa;
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

.fullscreen {
    width: 100% !important;
    max-width: none !important;
    height: 100% !important;
    margin: 0 !important;
}

.item-colum {
    border-bottom: 1px solid rgb(234, 235, 237);
    padding: 10px;
}

.item {
    background: rgb(247, 248, 250);
    border-radius: 5px;
}
</style>