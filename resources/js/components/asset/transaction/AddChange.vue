<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> {{ $t(title) }} </h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div v-if="processing" class="position-submit">
                    <b-progress :value="progressValue" max=100 class="w-75 mb-2 progress-xs progress-submit"
                        variant="success" animated></b-progress>
                </div>
                <!-- <div v-if="processing" class="text-center p-4 text-light rounded position-submit">
                    <i class="fa fa-spinner fa-spin fa-4x fa-fw text-success center "></i>
                    <span class="sr-only text-white">Vui lòng chờ giây lát...</span>
                    <b-progress :value="progress" :max="maxProgress" :animated="true" class="my-2"></b-progress>
                </div>
                <div v-else-if="success" class="alert alert-success mt-3">
                    Thành công!
                </div> -->
                <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="GIAO DỊCH" title-link-class="animation1" id="smoke">
                        <template #title>
                            <div class="tess">
                                <strong>GIAO DỊCH</strong>
                            </div>
                        </template>
                        <form @submit.prevent="delayAddChange" class="mt-2"
                            style="background:white;border-radius:10px;border: 2px solid whitesmoke;box-shadow: 1px 1px 5px slategrey;">
                            <div class="modal-header">
                                <div class="modal-title">
                                    <h4 v-if="!edit">Bàn giao tài sản - công cụ dụng cụ</h4>
                                    <span style="color:darkgray;">Bạn có thể chọn chính sách để chọn nhanh tài sản hoặc
                                        chọn tài sản theo cách thủ công</span>
                                </div>

                            </div>
                            <div class="modal-body">
                                <div class="form-group">

                                    <input type="checkbox" v-model="transaction.check" /> <span>Bàn giao theo phòng
                                        ban</span>
                                </div>
                                <div id="demo" style="color:red;font-style:italic"></div>
                                <div class="form-group" v-if="transaction.check == false">
                                    <label>Chọn người dùng</label> <small class="text-danger"> (*) </small>
                                    <treeselect placeholder="Chọn người dùng" :disable-branch-nodes="true" id="user_id"
                                        :show-count="true" :multiple="false" v-model="transaction.user_id" name="user_id"
                                        :options="users" v-bind:class="hasError('user_id') ? 'is-invalid' : ''">
                                    </treeselect>
                                    <span v-if="hasError('user_id')" class="invalid-feedback" role="alert">
                                        <strong>Lưu ý {{ getError('user_id') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group" v-if="transaction.check == true">
                                    <label>Chọn phòng ban</label> <small class="text-danger"> (*)</small>
                                    <treeselect placeholder="Phòng ban" :disable-branch-nodes="true" :show-count="true"
                                        :multiple="false" v-model="transaction.department_id" :options="tree_department">
                                    </treeselect>
                                </div>
                                <div class="form-group">
                                    <label>Ghi chú</label>
                                    <textarea v-model="transaction.note" class="form-control"
                                        placeholder="Your note for this transaction" rows="3"
                                        v-bind:class="hasError('note') ? 'is-invalid' : ''"></textarea>
                                    <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError('note') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label>Ngày bàn giao</label> <small class="text-danger">(*)</small>
                                    <input type="date" class="form-control" v-model="transaction.date_transaction" />
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Tài sản được chọn</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div style="float:right">
                                                <b-dropdown id="shadow_btn" right text="Chọn tài sản"
                                                    variant="outline-primary">
                                                    <b-dropdown-item @click="showAssetStock()">Chọn tài sản
                                                    </b-dropdown-item>
                                                    <b-dropdown-item @click="showStock()">Chọn công cụ dụng cụ
                                                    </b-dropdown-item>
                                                </b-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <b-table small hover responsive headVariant :items="transaction.asset_transaction_items"
                                        :fields="fields_detail" id="table">
                                        <template v-slot:cell(#)=data>
                                            {{ data.index + 1 }}
                                        </template>
                                        <template #head(#)=data>
                                            <span class="text-success">{{ data.label }}</span>
                                        </template>
                                        <template #head(name)=data>
                                            <span class="text-success">{{ data.label }}</span>
                                        </template>
                                        <template #head(quantity)=data>
                                            <div class="text-center">
                                                <span class="text-success">{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #head(asset_warehouse_id)=data>

                                            <span class="text-success">{{ data.label }}</span>

                                        </template>

                                        <template #head(sloc)=data>
                                            <div class="text-center">
                                                <span class="text-success">{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #cell(sloc)="data">
                                            <div class="bg bg-secondary text-center w-100 rounded-pill mx-auto mt-1 ">
                                                <span> {{ data.item.sloc }} </span>
                                            </div>
                                        </template>
                                        <template #cell(asset_warehouse_id)="data">

                                            <span> {{ getWarehouse(data.item.asset_warehouse_id) }} </span>

                                        </template>


                                        <!-- <template #cell(soluong)=data>
                                            <div style="width:50%;background:darkgray;color:white" class="btn btn-sm">
                                                {{ data.item.soluon }}</div>
                                        </template> -->
                                        <template #head(asset_status_id)=data>
                                            <div class="text-center">
                                                <span class="text-success">{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #cell(asset_status_id)=data>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="bg bg-success text-center mt-1 rounded-pill "
                                                        style="font-weight:700;" v-if="data.item.asset_status_id == 1">
                                                        Tốt</div>
                                                </div>

                                            </div>
                                        </template>
                                        <template #cell(quantity)="data">
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



                                            <!-- <input class="form-control" readonly /> -->

                                        </template>
                                        <template #head(action)="data">
                                            <div class="text-center">
                                                <span class="text-success">{{ data.label }}</span>
                                            </div>
                                        </template>
                                        <template #cell(action)=data>
                                            <div class="text-center">

                                                <button @click.prevent="DeleteItems(data.item, data.index)"
                                                    id="show_btn_cancel" class="btn btn-outline-danger btn-sm"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </template>
                                    </b-table>
                                </div>
                                <div class="form-group"
                                    v-if="transaction.user_id !== null && transaction.department_id !== null || transaction.user_id === undefined && transaction.department_id === undefined">
                                    <div class="form-group"
                                        v-if="transaction.user_id !== undefined && transaction.department_id !== undefined">
                                        <input type="checkbox" v-model="transaction.check_confirm" /> <span>
                                            {{ $t(getuser_name(transaction.user_id)) }} không cần xác nhận </span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-success" style="width:47%;" :disabled="disabled">
                                    Bàn giao
                                </button>
                                <button type="button" @click="viewchange()" class="btn btn-default" data-dismiss="modal"
                                    style="width:47%;">
                                    Hủy bỏ
                                </button>
                            </div>
                        </form>

                    </b-tab>
                </b-tabs>

            </div>
        </div>
        <!-- popup Tài sản-->
        <div class="modal fade" id="addstockAsset" tabindex="-1" role="dialog">
            <div class="modal-dialog full_modal-dialog ">
                <div class="modal-content">
                    <form @submit.prevent="Addetail">
                        <div class="modal-header" id="title_header">
                            <div class="modal-title">
                                <h4 v-if="!edit">Chọn tài sản</h4>

                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" style="border-bottom:2px solid lightgrey">
                                <div class="row">
                                    <div class="col-md-12" style="color:green">
                                        <div class="float-left">
                                            <i class="fas fa-angle-right"></i>
                                            <span> {{ counter }} Tài sản đã được chọn</span>
                                        </div>
                                        <div class="float-right mb-2">
                                            <button id="btn_next" type="submit" class="btn btn-success btn-sm">Tiếp tục
                                                <i class="fas fa-caret-right ml-2 mt-1 text-xs"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2" style="position:relative">
                                    <div class="p-2" style="background: rgb(240, 241, 243);">
                                        <!-- <button class="navbar-toggler btn-xs btn-filter-group" type="button" data-toggle="collapse"
                                            data-target="#navbarToggleExternalContent"
                                            aria-controls="navbarToggleExternalContent" aria-expanded="false"
                                            aria-label="Toggle navigation"
                                            >
                                            <i class="fas fa-filter mr-2"></i>Bộ lọc
                                        </button> -->
                                        <!-- <div class="collapse" id="navbarToggleExternalContent"> -->
                                        <p class="font-weight-bold"><i class="fas fa-filter mr-2 text-secondary"></i>Bộ lọc
                                            tìm kiếm</p>
                                        <div>
                                            <label>Nhóm tài sản</label>
                                            <div class="item-group "
                                                v-for="(asset_group, index) in group.slice(0, showAll ? group.length : 5)"
                                                :key="index" :class="{ active: selectedGroupIndex === index }"
                                                :style="{ backgroundColor: selectedGroupIndex === index ? 'rgb(36, 143, 231)' : 'transparent' }"
                                                @click.stop="filterAssetGroup(asset_group.id, asset_group.name, index)">
                                                <div class="btn btn-xs " style="font-size:10px;padding:5px">
                                                    <span
                                                        :style="{ color: selectedGroupIndex === index ? 'white' : 'black' }">
                                                        <i class="fas fa-list font-weight-bold text-secondary mr-2"
                                                            style="opacity:0.5"></i>{{ asset_group.name }}</span>
                                                </div>
                                            </div>
                                            <div class="text-left font-weight-bold border-bottom"
                                                v-if="!showAll && limit < group.length" @click.prevent="showAll = true"
                                                style="cursor:pointer">
                                                <button class="btn btn-xs font-weight-bold"
                                                    style="background:transparent">Xem thêm...</button>
                                            </div>

                                            <button class="btn w-100 mt-2 btn-filter"
                                                @click.prevent="resetFilterAssetGroup()"><i
                                                    class="fas fa-not-equal mr-2"></i>Bỏ chọn Filter</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10" style="background: rgb(240, 241, 243);padding:20px;">
                                    <div id="section1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="float-left">
                                                    <h4>Tài sản</h4>
                                                    <span style="color:darkgray">Thiên long > Tất cả tài sản</span>

                                                    <div class="item-filter">
                                                        <span class="mr-2 text-secondary"> {{ paginate_assets.total }} items
                                                        </span>
                                                        <button v-if="name_asset_groups !== ''"
                                                            @click.prevent="resetFilterAssetGroup()"
                                                            class="btn btn-sm text-secondary font-weight-bold search-item"
                                                            style="border-radius:30px;background-color:white">
                                                            {{ name_asset_groups }}
                                                            <i class="fas fa-times ml-2"></i>
                                                        </button>
                                                        <button v-if="name_search_asset !== ''"
                                                            @click.prevent="resetNameSearchAsset()"
                                                            class="btn btn-sm text-secondary font-weight-bold search-item"
                                                            style="border-radius:30px;background-color:white">
                                                            {{ name_search_asset }}
                                                            <i class="fas fa-times ml-2"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <SearchAsset :fetchAssetTools="fetchTools" :fetchAssets="fetchAssets"
                                                    :reset="resetFilterAssetGroup" :fetchWarehouse="fetchWarehouse"
                                                    ref="search">
                                                </SearchAsset>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="position:relative;">
                                            <div class="col-md-12">
                                                <div class="float-left mt-2 mb-2">
                                                    <b-button id="selectall" size="xs" variant="outline-success"
                                                        style="font-weight:700" @click="selectAllRows">Chọn
                                                        tất cả</b-button>
                                                </div>
                                                <div class="float-right mt-2 mb-2">

                                                    <b-button id="clearselectall" size="xs" variant="outline-info"
                                                        style="font-weight:700" @click="clearSelected">Clear tất
                                                        cả</b-button>
                                                </div>
                                            </div>
                                            <b-table table-variant="light" hover responsive :bordered="true" small 
                                                :items="assets" :filter="filter" :fields="fields" selectable
                                                ref="selectableTable" @row-selected="onRowSelected" :tbody-tr-class="hide">
                                                <template #cell(#)="data">
                                                    <span> {{ data.index + 1 }} </span>
                                                </template>
                                                <template #head(#)=data>
                                                    <span class="text-success"> {{ data.label }} </span>
                                                </template>
                                                <template #head(asset_warehouse_id)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <template #cell(asset_warehouse_id)="data">

                                                    <span><i class="fas fa-cube mr-2"></i> {{ getWarehouse(data.item.asset_warehouse_id) }} </span>

                                                </template>
                                                <template #head(name)="data">
                                                    <span class="text-success"> {{ data.label }} </span>
                                                </template>
                                                <template #head(check)="data">
                                                    <div class="text-center">
                                                        <span class="text-success"> {{ data.label }} </span>
                                                    </div>
                                                </template>
                                                <template #head(quantity)=data>
                                                    <div class="text-center">
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </div>

                                                </template>
                                                <template #cell(quantity)="data">
                                                    <div
                                                        class="text-center mt-4 ">
                                                        <span class="badge badge-secondary p-1 px-5"> {{ data.item.quantity }} </span>
                                                    </div>
                                                </template>

                                                <template #cell(name)="data">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <i class="fas fa-box"></i>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <p style="color:green">{{ data.item.name }} <br>

                                                                    <span style="color:darkgray">
                                                                        {{ data.item.asset_code }} </span><br>

                                                                    <span style="color:darkgray"
                                                                        v-if="data.item.deviceName !== null">Device
                                                                        Name: {{ data.item.deviceName }} </span>
                                                                </p>
                                                                <label v-if="data.item.asset_status_id == 1"
                                                                    style="color:green">
                                                                    Tốt </label>
                                                                <!-- <p> {{ data.item.asset_details.find(detail => detail.name === 'device name').value }} </p> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template #cell(check)="{ rowSelected }">
                                                    <template v-if="rowSelected">
                                                        <div class="text-center">
                                                            <div
                                                                style="margin-top:20px;text-align:center;display:inline-block;font-size:25px;">
                                                                <div class="text-success" aria-hidden="true"
                                                                    style="font-weight:700;"><i
                                                                        class="fas fa-check-circle text-pimary rounded-lg font-italic"></i>
                                                                </div>
                                                                <span class="sr-only">Selected</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template v-else>
                                                        <div class="text-center">
                                                            <div class="bg text-center"
                                                                style="font-weight:700;margin-top:20px;display:inline-block;font-size:25px;">
                                                                <div id="hover_select" v-on:click="counter++"
                                                                    aria-hidden="true"><i
                                                                        class="fas fa-check-circle text-gray rounded-lg"></i>
                                                                </div>
                                                                <span v-on:click="counter++" class="sr-only">Not
                                                                    selected</span>
                                                            </div>
                                                        </div>

                                                    </template>
                                                    <!-- <input type="checkbox" id="cb" :value="data.item.id" v-model="item_transaction.objectable_id"
                                                                        /> -->
                                                </template>
                                            </b-table>

                                            <div class="text-center position-loading " v-if="loading">
                                                <i class="fad fa-spinner fa-pulse " style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                                                <br>
                                                <span class="text-secondary font-weight-bold text-xs font-italic">Vui lòng chờ giây lát...</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label-sm" style="text-align: left" for="">Per
                                                            page:</label>
                                                        <div class="col-md-3">
                                                            <b-form-select size="sm" v-model="perPage"
                                                                :options="perPageOptions" @change="fetchAssets">
                                                            </b-form-select>
                                                        </div>
                                                        <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                            for=""></label>
                                                        <div class="col-md-3">
                                                            <b-pagination @input="fetchAssets"
                                                                v-model="paginate_assets.current_page"
                                                                :total-rows="paginate_assets.total" :per-page="perPage"
                                                                :last-page="paginate_assets.last_page" pills
                                                                class="ml-1"></b-pagination>
                                                        </div>


                                                        <!-- <span>{{currentPage}} of {{lastPage}}</span> -->

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Popup Công cụ dụng cụ-->
        <div class="modal fade bd-example-modal-xl" id="addstock" tabindex="-1" role="dialog">
            <div class="modal-dialog  full_modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="Addetail">
                        <div class="modal-header" id="title_header">
                            <div class="modal-title">
                                <h4 v-if="!edit">Chọn công cụ dụng cụ</h4>

                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group p-2" style="border-bottom:2px solid lightgrey;">
                                <div class="row">
                                    <div class="col-md-12" style="color:green">
                                        <div class="float-left">
                                            <i class="fas fa-angle-right"></i>
                                            <span> {{ countertool }} Công cụ dụng cụ đã được chọn</span>
                                        </div>
                                        <div class="float-right">
                                            <button id="btn_next" type="submit" class="btn btn-success btn-sm">Tiếp tục
                                                <i class="fas fa-caret-right ml-2 mt-1 text-xs"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <div class="p-2" style="background: rgb(240, 241, 243);">
                                        <!-- <button class="navbar-toggler btn-xs btn-filter-group" type="button" data-toggle="collapse"
                                            data-target="#navbarToggleExternalContent"
                                            aria-controls="navbarToggleExternalContent" aria-expanded="false"
                                            aria-label="Toggle navigation"
                                            >
                                            <i class="fas fa-filter mr-2"></i>Bộ lọc
                                        </button> -->
                                        <!-- <div class="collapse" id="navbarToggleExternalContent"> -->
                                        <p class="font-weight-bold"><i class="fas fa-filter mr-2 text-secondary"></i>Bộ lọc
                                            tìm kiếm</p>
                                        <div>
                                            <label>Nhóm tài sản</label>
                                            <div class="item-group "
                                                v-for="(asset_group, index) in group.slice(0, showAll ? group.length : 5)"
                                                :key="index" :class="{ active: selectedGroupToolIndex === index }"
                                                :style="{ backgroundColor: selectedGroupToolIndex === index ? 'rgb(36, 143, 231)' : 'transparent' }"
                                                @click.stop="filterAssetToolGroup(asset_group.id, asset_group.name, index)">
                                                <div class="btn btn-xs " style="font-size:10px;padding:5px">
                                                    <span
                                                        :style="{ color: selectedGroupToolIndex === index ? 'white' : 'black' }">
                                                        <i class="fas fa-list font-weight-bold text-secondary mr-2"
                                                            style="opacity:0.5"></i>{{ asset_group.name }}</span>
                                                </div>
                                            </div>
                                            <div class="text-left font-weight-bold border-bottom"
                                                v-if="!showAll && limit < group.length" @click.prevent="showAll = true"
                                                style="cursor:pointer">
                                                <button class="btn btn-xs font-weight-bold"
                                                    style="background:transparent">Xem thêm...</button>
                                            </div>

                                            <button class="btn w-100 mt-2 btn-filter"
                                                @click.prevent="resetFilterAssetToolGroup()"><i
                                                    class="fas fa-not-equal mr-2"></i>Bỏ chọn Filter</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10" style="background: rgb(240, 241, 243);padding:20px;">
                                    <div id="section1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Công cụ dụng cụ</h4>
                                                <span style="color:darkgray">Thiên long > Tất cả công cụ dụng cụ</span>
                                                <div class="item-filter">
                                                    <span class="mr-2 text-secondary"> {{ paginate_changeTool.total }} items
                                                    </span>
                                                    <button v-if="name_asset_tool_groups !== ''"
                                                        @click.prevent="resetFilterAssetToolGroup()"
                                                        class="btn btn-sm text-secondary font-weight-bold search-item"
                                                        style="border-radius:30px;background-color:white">
                                                        {{ name_asset_tool_groups }}
                                                        <i class="fas fa-times ml-2"></i>
                                                    </button>
                                                    <button v-if="name_search_asset_tool !== ''"
                                                        @click.prevent="resetNameSearchTool()"
                                                        class="btn btn-sm text-secondary font-weight-bold search-item"
                                                        style="border-radius:30px;background-color:white">
                                                        {{ name_search_asset_tool }}
                                                        <i class="fas fa-times ml-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <SearchTool :fetchAssetTools="fetchTools" :fetchAssets="fetchAssets"
                                                    :reset="resetFilterAssetGroup" :fetchWarehouse="fetchWarehouse"
                                                    ref="search_tool"></SearchTool>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="float-left mt-2 mb-2">
                                                    <b-button id="selectall" size="xs" variant="outline-success"
                                                        style="font-weight:700" @click="selectedAllRows">Chọn
                                                        tất cả</b-button>
                                                </div>
                                                <div class="float-right mt-2 mb-2">

                                                    <b-button id="clearselectall" size="xs" variant="outline-info"
                                                        style="font-weight:700" @click="cleartool">Clear tất
                                                        cả</b-button>
                                                </div>
                                            </div>
                                            <b-table hover responsive :bordered="true" small :items="asset_tools"
                                                :filter="filter" :fields="fields" selectable ref="selectableTool"
                                                @row-selected="onRowSelectedd">
                                                <template #cell(#)="data">
                                                    <span> {{ data.index + 1 }} </span>
                                                </template>
                                                <template #head(#)=data>
                                                    <span class="text-success"> {{ data.label }} </span>
                                                </template>
                                                <template #head(asset_warehouse_id)=data>
                                                    <span class="text-success">{{ data.label }}</span>
                                                </template>
                                                <template #cell(asset_warehouse_id)="data">

                                                    <span><i class="fas fa-cube mr-2"></i> {{ getWarehouse(data.item.asset_warehouse_id) }} </span>

                                                </template>
                                                <template #head(name)="data">
                                                    <span class="text-success"> {{ data.label }} </span>
                                                </template>
                                                <template #head(check)="data">
                                                    <div class="text-center">
                                                        <span class="text-success"> {{ data.label }} </span>
                                                    </div>
                                                </template>
                                                <template #cell(name)="data">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <i class="fas fa-box"></i>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <p style="color:green">{{ data.item.name }} <br>
                                                                    <span style="color:darkgray">
                                                                        {{ data.item.asset_code }} </span><br>
                                                                    <span v-if="data.item.asset_status_id == 1"
                                                                        style="color:green"> Tốt </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template #head(quantity)=data>
                                                    <div class="text-center">
                                                        <span class="text-success">{{ data.label }}</span>
                                                    </div>

                                                </template>
                                                <template #cell(quantity)="data">
                                                    <div
                                                        class="text-center mt-4">
                                                        <span class="badge badge-secondary px-5 p-1"> {{ data.item.quantity }} </span>
                                                    </div>
                                                </template>
                                                <template #cell(check)="{ rowSelected }">
                                                    <template v-if="rowSelected">
                                                        <div class="text-center">
                                                            <div
                                                                style="margin-top:20px;text-align:center;display:inline-block;font-size:25px;">
                                                                <div class="text-success" aria-hidden="true"
                                                                    style="font-weight:700"><i
                                                                        class="fas fa-check-circle text-green  rounded-lg font-italic"></i>
                                                                </div>
                                                                <span class="sr-only">Selected</span>
                                                            </div>
                                                        </div>

                                                    </template>
                                                    <template v-else>
                                                        <div class="text-center">
                                                            <div class="bg text-center"
                                                                style="font-weight:700;margin-top:20px;display:inline-block;font-size:25px;">
                                                                <div id="hover_select" v-on:click="counter++"
                                                                    aria-hidden="true"><i
                                                                        class="fas fa-check-circle text-gray rounded-lg"></i>
                                                                </div>
                                                                <span v-on:click="counter++" class="sr-only">Not
                                                                    selected</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <!-- <input type="checkbox" id="cb" :value="data.item.id" v-model="item_transaction.objectable_id"
                                                            /> -->
                                                </template>
                                            </b-table>
                                            <div class="text-center position-loading " v-if="loading">
                                                <i class="fad fa-spinner fa-pulse " style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                                                <br>
                                                <span class="text-secondary font-weight-bold text-xs font-italic">Vui lòng chờ giây lát...</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label-sm" style="text-align: left" for="">Per
                                                            page:</label>
                                                        <div class="col-md-3">
                                                            <b-form-select size="sm" v-model="perPage_changeTool"
                                                                :options="perPageOptions_changeTool" @change="fetchTools">
                                                            </b-form-select>

                                                        </div>
                                                        <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                            for=""></label>
                                                        <div class="col-md-3">
                                                            <b-pagination @input="fetchTools"
                                                                v-model="paginate_changeTool.current_page"
                                                                :total-rows="paginate_changeTool.total"
                                                                :per-page="perPage_changeTool"
                                                                :last-page="paginate_changeTool.last_page" align="fill"
                                                                pills class="my-0">
                                                            </b-pagination>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
import SearchAsset from '../SearchAsset.vue';
import SearchTool from '../search/SearchTransactionTool.vue';

export default {

    props: {
        id: String,
        title: ""
    },
    components: {
        Treeselect,
        SearchAsset,
        SearchTool
    },
    data() {
        return {
            searchText: '',
            searchTextTool: '',
            filter: '',
            total: 0,
            from: 1,
            to: 0,
            // current_page: 1,
            perpage: 5,
            perpage1: 5,

            currentPage: 1,
            currentPage1: 1,

            pageOptions: [5, 20, 50, 100, 500, { value: this.rows, text: "All" }],
            pageOptionss: [5, 10, 20, 50, 100, { value: this.rowss, text: "All" }],
            fields: [
                {
                    key: '#',
                    label: '#',
                    variant: 'gostwhite',
                    background: 'red',
                    class: "text-nowrap",
                },
                {
                    key: 'name',
                    label: 'Tên tài sản',
                    class: "text-nowrap",
                }, {
                    key: 'asset_warehouse_id',
                    label: 'Kho hàng',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng còn lại',
                    class: "text-nowrap",
                },
                {
                    key: 'check',
                    label: 'Chọn',
                    class: "text-nowrap",
                }
            ],
            fields_detail: [
                {
                    key: '#',
                    label: '#'
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap",
                }, {
                    key: 'asset_warehouse_id',
                    label: 'Kho hàng',
                    class: "text-nowrap",
                },
                {
                    key: 'sloc',
                    label: 'Số lượng còn lại',
                    class: "text-nowrap",
                },
                {
                    key: 'quantity',
                    label: 'Số lượng bàn giao',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                    class: "text-nowrap",
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap",
                },
            ],
            selected: [],
            edit: false,
            checkd: false,
            checkd_1: false,
            transaction: {
                id: '',
                department_id: null,
                user_id: null,
                check: false,
                create_by: '',
                asset_policy_id: '',
                asset_warehouse_id: '',
                check_confirm: false,
                date_transaction: '',
                note: '',
                index: '',
                asset_transactions: [],
                asset_transaction_items: [],
                asset_transaction_items_del: [],
            },

            asset_transaction_item: {
                id: '',
                asset_transaction_id: '',
                objectable_id: '',
                objectable_type: '',
                quantity: '',
                asset_status_id: '',
                sloc: '',
                name: '',
                index: ''
            },
            item_transaction: [],
            users: [],
            asset_policies: [],
            assets: [],
            asset_tools: [],
            errors: {},
            edit: false,
            loading: false,
            token: '',
            url_asset_policies: "/api/asset/policy",
            url_asset_transactions: "/api/asset/transaction",
            url_asset_assets: "api/asset/thanhly",
            url_asset_tool: "api/asset/changeTool",
            page_url_users: "api/user/allnew",
            url_asset_group: "api/asset/group",
            url_asset_types: "/api/asset/type",
            page_url_warehouse: "api/asset/warehouse",
            page_url_treeDepartment: "/api/asset/gettreeDepartment",
            asset_types: [],
            group: [],
            counter: 0,
            countertool: 0,
            index: [],
            ser: 0,
            message: {},
            disabled: false,
            timeout: null,
            asset_warehouses: [],
            tree_department: [],
            userss: [],
            paginate_assets: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions: [10, 20, 50, 100, 500],
            perPage: 10,

            paginate_changeTool: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPageOptions_changeTool: [10, 20, 50, 100, 500],
            perPage_changeTool: 10,
            processing: false,
            success: false,
            progress: 0,
            maxProgress: 100,
            progressValue: 0,
            demo: '',

            asset_groups: '',
            name_asset_groups: '',
            selectedGroupIndex: null,

            asset_tool_groups: '',
            selectedGroupToolIndex: null,
            name_asset_tool_groups: '',

            shownItems: 10,
            moreVisible: true,
            limit: 5,
            showAll: false,

            name_search_asset_tool: '',
            name_search_asset: '',
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.getUser();
        // this.fetchTypes();
        // this.fetchAssets();
        // this.fetchTools();

        this.getUserName();
        this.fetDepartment_Tree();
        this.fetchAssetGroup();
    },
    methods: {
        viewMore() {
            this.shownItems += 10;
            if (this.shownItems >= this.group.length) {
                this.moreVisible = false;
            }
        },
        filterAssetGroup(id, name, index) {
            this.asset_groups = id;
            this.selectedGroupIndex = index;
            this.name_asset_groups = name;
            this.$refs.search.resetSearch();
            this.fetchAssets();
        },
        resetFilterAssetGroup() {
            this.asset_groups = "";
            this.selectedGroupIndex = null;
            this.name_asset_groups = "";
            this.fetchAssets();
        },
        filterAssetToolGroup(id, name, index) {
            this.asset_tool_groups = id;
            this.selectedGroupToolIndex = index;
            this.name_asset_tool_groups = name;
            this.$refs.search_tool.resetSearch();
            this.fetchTools();
        },
        resetFilterAssetToolGroup() {
            this.asset_tool_groups = "";
            this.selectedGroupToolIndex = null;
            this.name_asset_tool_groups = "";
            this.fetchTools();
        },
        resetNameSearchTool() {
            this.$refs.search_tool.deleteItemSearch();
        },
        resetNameSearchAsset() {
            this.$refs.search.deleteItemSearch();
        },
        getWarehouse(asset_warehouse_id) {
            var obj = this.asset_warehouses.find(x => x.id == asset_warehouse_id);
            return obj ? obj.name : '';
        },
        getuser_name(user_id) {
            var obj = this.userss.find(x => x.id == user_id);
            if (obj ? obj.active : '' == 0) {
                return obj ? obj.name : '';
            } else {
                return (obj ? obj.name : '') + ' (Không hoạt động)';

            }
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
        fetchWarehouse() {

            var page_url = this.page_url_warehouse;
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
        fetchAssetGroup() {

            var page_url = this.url_asset_group;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            }).then(res => res.json())
                .then(res => {
                    this.group = res.data;

                }).catch(err => {

                    console.log(err);
                });

        },
        delayAddChange() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 4000)

            this.AddTransaction()
        },
        onRowSelected(assets) {

            this.item_transaction = [...assets];

            this.counter = this.item_transaction.length;
        },
        onRowSelectedd(asset_tools) {
            this.item_transaction = [...asset_tools];

            this.countertool = this.item_transaction.length;
        },
        selectAllRows() {
            this.$refs.selectableTable.selectAllRows();
        },
        selectedAllRows() {
            this.$refs.selectableTool.selectAllRows();
        },
        clearSelected() {
            this.$refs.selectableTable.clearSelected();
            // this.total = this.counter - this.counter;
        },
        cleartool() {
            this.$refs.selectableTool.clearSelected();
        },
        getUserName() {

            var page_url = this.page_url_users;
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
        fetchTools() {
            this.loading = true;
            this.name_search_asset_tool = this.$refs.search_tool.query_tool;
            //$("#tbbody_id").html('');
            const params = new URLSearchParams({
                asset_type_id: this.searchText,
                name: this.$refs.search_tool.query_tool,
                groups: this.asset_tool_groups
            });
            var page_url = this.url_asset_tool + "/" + this.paginate_changeTool.current_page + "?per_page=" + this.perPage_changeTool + '&' + params.toString();
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.asset_tools = data.data;
                    this.paginate_changeTool = data.paginate;
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },

        fetchAssets() {
            //$("#tbbody_id").html('');

            this.loading = true;
            this.name_search_asset = this.$refs.search.searchKeyword;
            this.asset_types = Array();

            const params = new URLSearchParams({
                asset_type_id: this.searchText,
                name: this.$refs.search.searchKeyword,
                groups: this.asset_groups
            });
            var page_url = this.url_asset_assets + "/" + this.paginate_assets.current_page + "?per_page=" + this.perPage + '&' + params.toString();
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
                    this.assets.forEach(asset => {
                        const deviceDetail = asset.asset_details.find(detail => detail.name.toLowerCase() === 'device name');
                        if (deviceDetail) {
                            asset.deviceName = deviceDetail.value;
                        } else {
                            asset.deviceName = null;
                        }
                    });
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },

        AddTransaction() {
            this.processing = true;
            var page_url = this.url_asset_transactions;
            let val = 0;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.transaction),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => {
                        val += 33; // Hoàn thành bước 1/3, tăng giá trị biến lên 33%
                        this.progressValue = val;
                        return res.json();
                    })
                    .then(data => {
                        if (this.transaction.department_id === undefined) {

                        }

                        if (!data.data.errors) {
                            // this.reset();
                            this.transaction.asset_transactions.push(data.data);
                            val += 33; // Hoàn thành bước 2/3, tăng giá trị biến lên 66%
                            this.progressValue = val;
                            toastr.success(this.$t('form.save_success'), "");
                            window.location.href = "/asset/change";


                        } else {
                            this.errors = data.data.errors;
                            this.processing = false;
                            this.progressValue = 0;
                            this.showError('Thông báo', 'Lỗi');
                            document.getElementById('demo').innerHTML = "Lưu ý: " + data.data.message;
                        };
                        if (data.data.success == 0) {
                            this.message = data.data.message;
                            this.processing = false;
                            this.progressValue = 0;
                            toastr.warning(this.message);
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditTransaction(transaction, index) {
            // var department={..._obj};
            this.edit = true;
            this.transaction.id = transaction.id;
            this.transaction.user_id = transaction.user_id;
            this.transaction.trans_type = transaction.create_by;
            this.transaction.asset_policy_id = transaction.note;
            this.transaction.index = index;
            $("#addAsset").modal("show");
        },
        Addetail() {
            console.log(this.item_transaction.length);
            console.log('kim');
            for (let index = 0; index < this.item_transaction.length; index++) {
                this.asset_transaction_item.objectable_id = this.item_transaction[index].id;
                this.asset_transaction_item.name = this.item_transaction[index].name;
                this.asset_transaction_item.quantity = 1;
                this.asset_transaction_item.asset_warehouse_id = this.item_transaction[index].asset_warehouse_id;
                this.asset_transaction_item.asset_status_id = 1;
                this.asset_transaction_item.sloc = this.item_transaction[index].quantity;
                this.asset_transaction_item.type = this.item_transaction[index].asset_type.type;
                let isexist = false;
                this.transaction.asset_transaction_items.forEach(element => {
                    if (element.objectable_id == this.item_transaction[index].id) {

                        isexist = true;
                    }
                    // if(this.asset_transaction_item.type != 0) {
                    //     element.quantity++;
                    // }

                });
                if (!isexist) {

                    this.transaction.asset_transaction_items.push({ ...this.asset_transaction_item })
                }


            }

            this.clearSelected();
            // this.clearTool();
            $("#addstockAsset").modal("hide");
            $("#addstock").modal("hide");

            // this.reset_item();
        },
        DeleteItems(item, index) {

            // if (confirm(this.$t('form.confirm_delete') + '?')) {

            this.transaction.asset_transaction_items_del.push({ ...item });

            this.transaction.asset_transaction_items.splice(index, 1);
            // }

        },
        reset_item() {
            for (let field in this.item_transaction) {

                this.item_transaction[field] = null;

            }
        },
        clearAllError() {
            this.errors = {};
        },
        reset() {
            this.clearAllError();

            this.edit = false;
            for (let field in this.asset_transaction_item) {

                this.asset_transaction_item[field] = null;
            }

            this.type.index = -1;
        },
        showAssetStock() {
            this.clearSelected();
            this.clearAllError();
            this.filter = '';
            this.searchText = '';
            this.searchTextTool = '';
            $("#addstockAsset").modal("show");
            if (this.searchText == '') {
                this.fetchAssets();
                this.fetchWarehouse();

            }
        },
        showStock() {
            this.cleartool();
            this.clearAllError();
            this.filter = '';
            this.searchText = '';
            this.searchTextTool = '';
            $("#addstock").modal("show");
            this.fetchTools();
            this.fetchWarehouse();
            // if (this.searchTextTool == '') {
            //     this.fetchAssets();

            // }
        },
        showAssetStockExcel() {
            $("#addstockAssetexcel").modal("show");
        },
        showAssetStockuser() {
            $("#userstock").modal("show");
        },
        view() {
            window.location.href = '/asset/change';
        },
        viewchange() {
            window.location.href = '/asset/change';
        },
        hide(item, type) {
            if (!item || type !== 'row') return
            if (item.user_id || item.asset_status_id !== 1) return 'd-none';

        },
        search() {
            this.ser++;
            if (this.searchText !== "" && this.ser > 1 || this.searchText == '' && this.ser > 1) {
                this.fetchAssets();

                this.ser = 0;
            }

        },
        search_category_tool() {
            this.ser++;
            if (this.searchTextTool !== "" && this.ser > 1 || this.searchTextTool == '' && this.ser > 1) {
                this.fetchTools();
                this.ser = 0;
            }

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
    },
    watch: {
        'transaction.user_id': function () {
            if (this.transaction.user_id === undefined) {
                this.transaction.check_confirm = false;
            }
        },
        'transaction.check': function () {
            if (this.transaction.check === false) {
                this.transaction.department_id = null;
            } else {
                this.transaction.user_id = null
            }
        }
    },
    mounted() {
        this.transaction.date_transaction = this.defaultDate;
    },
    computed: {

        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            var news = [];
            for (var index = 0; index < this.asset_tools.length; index++) {
                if (this.asset_tools[index].quantity !== 0) {
                    news.push(this.asset_tools[index]);
                }
            }
            console.log(news.length)
            return news.length;

        },
        rowss() {
            var news = [];
            for (var index = 0; index < this.assets.length; index++) {
                if (this.assets[index].asset_status_id !== 3 && this.assets[index].user_id == null
                    && this.assets[index].department_id == null) {
                    news.push(this.assets[index]);
                }
            }
            // console.log(news.length)
            return news.length;
        },
        tool() {
            var news = [];
            for (var index = 0; index < this.asset_tools.length; index++) {
                if (this.asset_tools[index].quantity !== 0) {
                    news.push(this.asset_tools[index]);
                }
            }
            return news;
        },
        defaultDate() {
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0');
            let yyyy = today.getFullYear();
            return yyyy + '-' + mm + '-' + dd;
        },
        tess() {
            return this.$refs.search.searchKeyword
        }
    },
}

</script>
<style scoped lang="scss">
.animation div {
    border-bottom: 1px solid #3c8dbc;
    animation: color 2s infinite;
}

.tess {
    color: gray;
}

@keyframes color {
    50% {
        color: #3c8dbc;
    }
}

.form-group {
    margin-bottom: 5px !important;
}

#table td {
    border-left: none;
    border-right: none;
    border-top: none;
    border-bottom: 2px solid lavender;
}

/* ul{
    background:red;
} */
#tatca___BV_tab_button__ {
    border: none;
}

#tatca___BV_tab_button__:hover {
    border-bottom: 2px solid blue;
}

#tatca___BV_tab_button__.active {
    border-bottom: 2px solid blue;
    background: rgb(244, 246, 249);
}

#type::placeholder {
    color: green;
}

#cb {
    width: 20px;
    height: 20px;
    accent-color: green;
}

#selectall:hover {
    background: radial-gradient(lightgreen, green 250%);
    box-shadow: 1px 1px 10px black;
    width: 90px;
}

#clearselectall:hover {
    background: radial-gradient(rgb(20, 149, 255), steelblue 100%, steelblue);
    box-shadow: 1px 1px 10px black;
    width: 90px;
}

#hover_select:hover {
    font-size: 30px;
    color: green;
}

#title_header {
    background: radial-gradient(rgb(20, 149, 255), steelblue 100%, steelblue);
    box-shadow: 1px 1px 10px steelblue;
    color: white;
}

#btn_next:hover {
    background: radial-gradient(lightgreen, green 250%);
    color: white;
    box-shadow: 1px 1px 10px darkgreen;
    width: 90px;
}

@keyframes selectall {
    50% {
        box-shadow: 1px 1px 10px black;
    }
}

.position-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.position-submit {
    width: 100%;
    position: absolute;
    background: rgb(0 0 0 / 50%);
    height: 100%;
    z-index: 1;
}

.progress-submit {
    position: absolute;
    width: 100%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

}

.item-group {
    &:hover {
        background: radial-gradient(#92cbfa, rgb(231, 244, 255) 100%, rgb(149, 207, 255));
        cursor: pointer;
        color: white;
    }
}

.filter {
    background: radial-gradient(rgb(20, 149, 255), steelblue 100%, steelblue);
}

.full_modal-dialog {
    margin: 0 !important;
    width: 100% !important;
    height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    padding: 0 !important;
}

.show {
    padding-right: 0 !important;
}

.btn-filter {
    background: radial-gradient(rgb(20, 149, 255), rgb(0, 140, 255) 100%, rgb(0, 140, 255));
    color: white;
}

.search-item:hover {
    border: 1px solid gray;
    color: rgb(0, 140, 255);
}

.search-item:hover>i {
    color: red;
}

.btn-filter-group {
    background: transparent;
    border: 1px solid lightgray;
    padding: 5px;

    &:hover {
        background: rgb(219, 231, 242);
    }
}
</style>