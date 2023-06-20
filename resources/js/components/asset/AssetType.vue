<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7">
                        <h5 class="m-0 text-dark"><i class="fas fa-dice-d6"></i> {{ $t(title) }}</h5>
                    </div>
                    <div class="col-md-5">

                        <div class="float-sm-right" style="margin-top:10px;float:right">
                            <b-dropdown right text="Import Excel TS" variant="primary" style="display:inline-block;">
                                <b-dropdown-item @click.prevent="showImportExcel()">Loại Tài sản</b-dropdown-item>
                            </b-dropdown>
                            <!-- <button class="btn btn-info " @click="showAssetType()" style="font-weight:700;float:right;">
                                                                                            <i class="fa fa-plus"></i>
                                                                                            {{ $t('form.create')}}</button> -->
                            <button class="btn btn-primary text-black" id="shadow_btn" @click="showAssetType()"><i
                                    class="fas fa-plus"></i> Tạo Model tài sản</button>


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
                                :title="$t('form.employee_name')" for=""> <i class="fas fa-folder text-secondary"></i>
                                <span class="text-secondary">Nhóm tài sản: </span></label>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm mt-1" v-model="form_filter.asset_group_id"
                                    @change="filter_data()">
                                    <option value="">All</option>
                                    <option v-for="group in asset_groups" :key="group.id" v-bind:value="group.id">
                                        {{ group.name }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button id="btn_refesh" type="reset" class="btn btn-outline-warning btn-xs mt-2   "
                                @click="clearFilter()">
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <b-tabs table-info active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="TẤT CẢ" title-link-class="animation1">
                        <template #title>
                            <div class="tess">
                                <strong>TẤT CẢ</strong>
                            </div>
                        </template>
                        <div class="body" style="margin-top:10px">
                            <b-table :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" hover responsive :bordered="true"
                                small :items="asset_types" :fields="fields" primary-key="id" :filter="filter"
                                :current-page="current_page" :per-page="per_page" @row-clicked="showA">
                                <template #head(#)='data'>
                                    <span class="text-success">STT</span>
                                </template>
                                <template v-slot:cell(#)='data'>
                                    <!-- {{data.item.asset_type_details}} -->
                                    {{ data.index + 1 }}
                                </template>
                                <template #head(name)='data'>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)='data'>
                                    <div v-for="(img, index) in data.item.attachment_image" v-bind:key="img.index"
                                        style="display:inline-block">
                                        <div v-if="img.url">
                                            <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                        </div>
                                        <div v-if="img.base64">
                                            <b-avatar :src="image" class="mr-2" size="2rem"></b-avatar>
                                        </div>
                                    </div>

                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                            <span class="text-secondary"> <span>{{ data.item.code }}</span></span>
                                        </div>
                                    </div>
                                </template>
                                <template #head(type)='data'>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(type)='data'>
                                    <span class="text-secondary" v-if="data.item.type == 0"><i class="fas fa-box-open"></i>
                                        Tài sản </span>
                                    <span class="text-secondary" v-if="data.item.type == 1"><i class="fas fa-layer-group">
                                        </i> Công cụ dụng cụ </span>
                                </template>
                                <template #head(asset_group_id)='data'>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_group_id)='data'>
                                    <i class="fas fa-folder text-secondary"></i> <span>
                                        {{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>
                                </template>
                                <!-- <template #head(amount)=data>
                                                                                                <span class="text-success">{{ data.label }}</span>
                                                                                            </template> -->
                                <!-- <template #cell(amount)="data">
                                                                                                <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                                                                            </template> -->
                                <template v-slot:cell(newtab)='data'>
                                    <div>
                                        <b-dropdown id="shadow_btn" text="Action" variant="outline-secondary" right
                                            style="display:flex;">
                                            <b-dropdown-item v-b-hover="handleHover"
                                                @click.prevent="editAssetType(data.item)">
                                                <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                            </b-dropdown-item>
                                            <b-dropdown-item v-b-hover="handleHovers"
                                                @click="DeleteAssetType(data.item.id)">
                                                <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                            </b-dropdown-item>
                                            <!-- <b-dropdown-item>Trường tùy chỉnh</b-dropdown-item> -->
                                        </b-dropdown>
                                    </div>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for="">Per
                                            page:</label>
                                        <div class="col-md-3">
                                            <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                            </b-form-select>
                                        </div>
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                                        <div class="col-md-3">
                                            <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page"
                                                align="fill" pills class="my-0"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="LOẠI TÀI SẢN" title-link-class="animation1">
                        <template #title>
                            <div class="tess">
                                <strong>LOẠI TÀI SẢN</strong>
                            </div>
                        </template>
                        <div class="header" style="background-color:rgb(244, 246, 249);heigh:20px">
                            <div class="row">
                                <div class="col-sm-3" style="margin-top:7px;color:darkgray;">
                                    <!-- <div style="display:inline-block;">
                                                                                                    <i class="fas fa-stop"></i> <span>Nhóm tài sản: </span>
                                                                                                </div>
                                                                                                <div class="dropdown" style="display:inline-block;">
                                                                                                    <select class="form-control" v-model="form_filter.asset" @click="filter_data()">
                                                                                                        <option @click="filter_data()" v-for="group in asset_groups" :key="group.id" v-bind:value="group.id">{{group.name}}</option>
                                                                                                    </select>
                                                                                                </div> -->
                                </div>
                                <div class="col-sm-7">

                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <b-table :sort-by.sync="sortBy" responsive :sort-desc.sync="sortDesc" hover :bordered="true"
                                small :items="taisan" :fields="fields" :filter="filter" :current-page="current_page2"
                                @row-clicked="showA" :per-page="per_page2" :tbody-tr-class="hidetool">
                                <template #head(#)=data>
                                    <span class="text-success">ID</span>
                                </template>
                                <template v-slot:cell(#)=data>
                                    <span>{{ data.index + 1 }}</span>
                                </template>
                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)=data>
                                    <div v-for="img in data.item.attachment_image" :key="img.id"
                                        style="display:inline-block">
                                        <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                    </div>
                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                            <span class="text-secondary"> <span>{{ data.item.code }}</span></span>
                                        </div>
                                    </div>
                                </template>
                                <template #head(type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(type)='data'>
                                    <span class="text-secondary" v-if="data.item.type == 0"><i class="fas fa-box-open"></i>
                                        Tài sản </span>
                                    <span class="text-secondary" v-if="data.item.type == 1"><i class="fas fa-layer-group">
                                        </i> Công cụ dụng cụ </span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_group_id)=data>

                                    <i class="fas fa-folder text-secondary"></i>
                                    <span>{{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>


                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(amount)=data>
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>
                                <template v-slot:cell(newtab)=data>
                                    <b-dropdown id="shadow_btn" text="Action" variant="outline-secondary" right
                                        style="display:flex;">
                                        <b-dropdown-item v-b-hover="handleHover" @click="editAssetType(data.item)">
                                            <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHovers" @click="DeleteAssetType(data.item.id)">
                                            <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                        </b-dropdown-item>
                                        <!-- <b-dropdown-item>Trường tùy chỉnh</b-dropdown-item> -->
                                    </b-dropdown>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for="">Per
                                            page:</label>
                                        <div class="col-md-3">
                                            <b-form-select size="sm" v-model="per_page2" :options="pageOptions">
                                            </b-form-select>
                                        </div>
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                                        <div class="col-md-3">
                                            <b-pagination v-model="current_page2" :total-rows="rows" :per-page="per_page2"
                                                align="fill" pills class="my-0"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="LOẠI CÔNG CỤ DỤNG CỤ" title-link-class="animation1">
                        <template #title>
                            <div class="tess">
                                <strong>LOẠI CÔNG CỤ DỤNG CỤ</strong>
                            </div>
                        </template>
                        <div class="header" style="background-color:rgb(244, 246, 249);height:20px;">
                            <div class="row">
                                <div class="col-sm-3" style="margin-top:7px;color:darkgray;">
                                    <!-- <div style="display:inline-block;">
                                                                                                    <i class="fas fa-stop"></i> <span>Nhóm tài sản: </span>
                                                                                                </div>
                                                                                                <div class="dropdown" style="display:inline-block;">
                                                                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2"
                                                                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                        Tất cả
                                                                                                    </button>
                                                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                                                                        <button v-for="group in asset_groups" :key="group.id" class="dropdown-item"
                                                                                                            type="button">{{group.name}}</button>

                                                                                                    </div>
                                                                                                </div> -->
                                </div>
                                <div class="col-sm-7">

                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <b-table :sort-by.sync="sortBy" responsive :sort-desc.sync="sortDesc" hover :bordered="true"
                                small :items="congcudungcu" :fields="fields" :filter="filter" @row-clicked="showA"
                                :current-page="current_page3" :per-page="per_page3" :tbody-tr-class="hideasset">
                                <template #head(#)=data>
                                    <span class="text-success">ID</span>
                                </template>
                                <template v-slot:cell(#)=data>
                                    <span>{{ data.index + 1 }}</span>
                                </template>
                                <template #head(name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(name)=data>
                                    <div v-for="img in data.item.attachment_image" :key="img.id"
                                        style="display:inline-block">
                                        <b-avatar :src="img.url" class="mr-2" size="2rem"></b-avatar>
                                    </div>
                                    <div class="row" style="display: inline-flex;">
                                        <div class="col-sm-12">
                                            <span>{{ data.item.name }}</span><br>
                                            <span class="text-secondary"> <span>{{ data.item.code }}</span></span>
                                        </div>
                                    </div>
                                </template>
                                <template #head(type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(type)='data'>
                                    <span class="text-secondary" v-if="data.item.type == 0"><i class="fas fa-box-open"></i>
                                        Tài sản </span>
                                    <span class="text-secondary" v-if="data.item.type == 1"><i class="fas fa-layer-group">
                                        </i> Công cụ dụng cụ </span>
                                </template>
                                <template #head(asset_group_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(asset_group_id)=data>
                                    <div>
                                        <i class="fas fa-folder text-secondary"></i>
                                        <span>{{ $t(getAssetGroupValueName(data.item.asset_group_id)) }} </span>
                                    </div>

                                </template>
                                <template #head(amount)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(amount)=data>
                                    <span>{{ data.item.amount | numFormat('0,0') }} VND</span>
                                </template>
                                <template v-slot:cell(newtab)=data>
                                    <b-dropdown id="shadow_btn" text="Action" variant="outline-secondary" right
                                        style="display:flex;">
                                        <b-dropdown-item v-b-hover="handleHover" @click="editAssetType(data.item)">
                                            <span :class="isHovered ? 'text-warning' : ''">Chỉnh sửa</span>
                                        </b-dropdown-item>
                                        <b-dropdown-item v-b-hover="handleHovers" @click="DeleteAssetType(data.item.id)">
                                            <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                        </b-dropdown-item>
                                        <!-- <b-dropdown-item>Trường tùy chỉnh</b-dropdown-item> -->
                                    </b-dropdown>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for="">Per
                                            page:</label>
                                        <div class="col-md-3">
                                            <b-form-select size="sm" v-model="per_page3" :options="pageOptions">
                                            </b-form-select>
                                        </div>
                                        <label class="col-form-label-sm col-md-1" style="text-align: left" for=""></label>
                                        <div class="col-md-3">
                                            <b-pagination v-model="current_page3" :total-rows="rows" :per-page="per_page3"
                                                align="fill" pills class="my-0"></b-pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <!-- popup-->
        <div class="modal fade" id="addtypeAsset" tabindex="-1" role="dialog" style="overflow-y:scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent.enter="delayType">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 v-if="!edit">Tạo Model tài sản</h4>
                                <h4 v-if="edit"> Cập Nhật Model tài sản</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên Model tài sản</label> <small class="text-red">( * )</small>
                                <input v-model="type.name" class="form-control" id="name" type="name"
                                    v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('name') }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Mã Model tài sản</label> <small class="text-red">( * )</small>
                                        <input v-model="type.code" class="form-control" id="code" name="code"
                                            v-bind:class="hasError('code') ? 'is-invalid' : ''" />
                                        <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('code') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Loại tài sản</label> <small class="text-red">( * )</small>
                                        <select v-if="getAssets(type.id) || getTools(type.id)" @change="refest()"
                                            v-model="type.type" class="form-control" id="type" name="type"
                                            v-bind:class="hasError('type') ? 'is-invalid' : ''" :disabled="enabled">
                                            <option value="0" selected>Tài sản</option>
                                            <option value="1">Công cụ dụng cụ</option>
                                        </select>
                                        <select v-else @change="refest()" v-model="type.type" class="form-control" id="type"
                                            name="type" v-bind:class="hasError('type') ? 'is-invalid' : ''">
                                            <option value="0" selected>Tài sản</option>
                                            <option value="1">Công cụ dụng cụ</option>
                                        </select>
                                        <span v-if="hasError('type')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('type') }}</strong>
                                        </span>
                                    </div>
                                    <!-- getAssets  :disabled="enabled"-->
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6" v-if="type.type == '1'">
                                        <label>Loại</label>
                                        <select class="form-control" v-model="type.asset_cat_id">
                                            <option v-for="cat in asset_cats" :key="cat.id" v-bind:value="cat.id">
                                                {{ cat.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label> <small class="text-danger"> (*)</small>
                                        <select v-model="type.asset_group_id" class="form-control"
                                            v-bind:class="hasError('asset_group_id') ? 'is-invalid' : ''">
                                            <option v-for="group in asset_groups" :key="group.id" v-bind:value="group.id">
                                                {{ group.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('asset_group_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('asset_group_id') }}</strong>
                                        </span>
                                    </div>
                                    <!-- <div class="col-sm-6">
                                                                                                    <label>Giá mua</label>
                                                                                                    <vue-numeric :separator="separator" v-model="type.amount" class="form-control"
                                                                                                        placehoder="Giá">
                                                                                                    </vue-numeric>
                                                                                                </div> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label>Hình ảnh</label> -->
                                <div class="col-form-label-sm ">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div>
                                                <input type="file" v-on:change="onImageChange" class="form-control"
                                                    accept=".xlsx,.xls,image/x-png,image/gif,image/jpeg,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                                    name="images[]" @change="emitEventImg($event)" id="Themimgg"
                                                    style="display:none" ref="imgg" multiple>
                                                <button type="button" class="btn btn-sm cssfile right"
                                                    style="border:1px solid lightgray;padding:6px;font-weight:700;border-radius:5px;"
                                                    v-on:click="handleClickInputImg()"> <i class="fas fa-image fa-lg"></i>
                                                    Thêm ảnh </button>
                                            </div>
                                            <div class="form-group mt-2 row">
                                                <div class="col-md-4 mt-2" v-for="(imgg, index) in type.attachment_image"
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
                                <textarea v-model="type.description" class="form-control"
                                    v-bind:class="hasError('description') ? 'is-invalid' : ''"></textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>{{ getError('description') }}</strong>
                                </span>
                            </div>
                            <div class="modal" id="Add_fields" tabindex="-2" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form @submit.prevent="Add_custom_fields">
                                            <div class="modal-header" style="background:whitesmoke;">
                                                <div class="modal-title">
                                                    <label>CUSTOMS FIELDS</label>

                                                </div>
                                                <button type="button" class="close" aria-label="Close"
                                                    @click.prevent="Close_fields()">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <b-table small fixed responsive hover :items="asset_fields"
                                                    :fields="col_field">
                                                    <template #head(#)="data">
                                                        <div>
                                                            <span class="text-success"> {{ data.label }} </span>
                                                        </div>
                                                    </template>
                                                    <template #head(name)="data">
                                                        <div>
                                                            <span class="text-success"> {{ data.label }} </span>
                                                        </div>
                                                    </template>
                                                    <template #head(check)="data">
                                                        <div class="text-center">
                                                            <span class="text-success"> {{ data.label }} </span>
                                                        </div>
                                                    </template>
                                                    <template #cell(#)="data">
                                                        <div>
                                                            <span> {{ data.index + 1 }} </span>
                                                        </div>
                                                    </template>
                                                    <template #cell(check)="data">
                                                        <div class="text-center">
                                                            <input type="checkbox" class="checkbox" v-model="check"
                                                                :value="data.item" />
                                                        </div>
                                                    </template>
                                                </b-table>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" style="width:47%;"
                                                    @click.prevent="Close_fields()">
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
                            <div class="form-group">
                                <b-table responsive hover :items="type.asset_type_details" :fields="field_asset_type">
                                    <template #head(#)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(value)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #cell(value)="data">
                                        <div>
                                            <input class="form-control form-control-sm" v-model="data.item.value" />
                                        </div>
                                    </template>
                                    <template #head(name)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(action)="data">
                                        <div class="text-center">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #cell(#)="data">
                                        <div>
                                            <span> {{ data.index + 1 }} </span>
                                        </div>
                                    </template>
                                    <template #cell(action)="data">
                                        <div class="text-center">
                                            <button @click.prevent="DeleteItems(data.item, data.index)" id="show_btn_cancel"
                                                class="btn btn-outline-danger btn-sm"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </template>
                                </b-table>
                            </div>
                        </div>
                        <div class="form-group">
                            <button @click.prevent="show_fields()" class="snip0047 btn btn-sm btn-default"> <span>Thêm
                                    thuộc tính mở rộng</span> <i class="fas fa-plus"></i></button>
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
        <div class="modal fade" id="Showw" tabindex="-1" role="dialog" style="overflow-y:scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent.enter="Showw">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4>Chi tiết Model tài sản</h4>

                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên Model tài sản</label>
                                <input v-model="type.name" class="form-control" id="name" type="name" readonly />

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Mã Model tài sản</label>
                                        <input v-model="type.code" class="form-control" id="code" name="code" readonly />

                                    </div>
                                    <div class="col-md-6">
                                        <label>Loại tài sản</label>

                                        <select v-model="type.type" class="form-control" id="type" name="type"
                                            :disabled="enabled">
                                            <option value="0" selected>Tài sản</option>
                                            <option value="1">Công cụ dụng cụ</option>
                                        </select>


                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6" v-if="type.type == '1'">
                                        <label>Loại</label>
                                        <select class="form-control" v-model="type.asset_cat_id" :disabled="enabled">
                                            <option v-for="cat in asset_cats" :key="cat.id" v-bind:value="cat.id">
                                                {{ cat.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Nhóm tài sản</label>
                                        <select :disabled="enabled" v-model="type.asset_group_id" class="form-control">
                                            <option v-for="group in asset_groups" :key="group.id" v-bind:value="group.id">
                                                {{ group.name }}
                                            </option>
                                        </select>

                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-form-label-sm ">
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="form-group mt-2 row">
                                                <div class="col-md-4 mt-2" v-for="(imgg, index) in type.attachment_image"
                                                    :key="index">
                                                    <div class="card m-auto mt-2" id="card_img"
                                                        style="max-width:200px;height:auto;width:100%">
                                                        <img style="max-width:250px;height:200px;width:100%"
                                                            v-if="imgg.base64" :src="imgg.base64"
                                                            class="img-responsive img-fluid" rounded="lg" />
                                                        <img style="max-width:250px;height:200px;width:100%" v-if="imgg.url"
                                                            :src="imgg.url" class="img-responsive img-fluid" rounded="lg" />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea v-model="type.description" class="form-control" readonly></textarea>

                            </div>

                            <div class="form-group">
                                <b-table responsive hover :items="type.asset_type_details" :fields="field_asset_chitiet">
                                    <template #head(#)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(name)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>

                                    <template #head(value)="data">
                                        <div>
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #head(action)="data">
                                        <div class="text-center">
                                            <span class="text-success"> {{ data.label }} </span>
                                        </div>
                                    </template>
                                    <template #cell(#)="data">
                                        <div>
                                            <span> {{ data.index + 1 }} </span>
                                        </div>
                                    </template>

                                </b-table>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
        <!-- Form import Excel-->
        <div class="modal" tabindex="-1" role="dialog" id="import_excel" data-backdrop="static" data-keyboard="false">
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
                        <div class="form-import-excel ">
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
                                        <td v-for="data_excel in selectedExcel"> {{ data_excel.value }} <span
                                                class="text-danger">
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
import XLSX from 'xlsx'

import axios from 'axios';
export default {
    props: {
        title: ""
    },
    data() {
        return {
            columns: [],
            rowss: [],
            properties: [], // danh sách các thuộc tính
            selected_fields: ["Mã Model Tài sản", "Tên Model Tài sản", "Loại", "Nhóm tài sản"], // thuộc tính được chọn
            data: [
                { label: "Mã Model Tài sản", value: "" },
                { label: "Tên Model Tài sản", value: "" },
                { label: "Loại", value: "" },
                { label: "Nhóm tài sản", value: "" }
            ],
            index: [],
            total: 0,
            per_page: 10,
            per_page2: 10,
            per_page3: 10,
            from: 1,
            to: 0,
            current_page: 1,
            current_page2: 1,
            current_page3: 1,
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            asset_types: [],
            asset_groups: [],
            asset_cats: [],
            field: {
                id: '',
                name: '',
                value: '',
                index: ''
            },
            type: {
                id: '',
                name: '',
                code: '',
                asset_group_id: '',
                amount: '',
                asset_cat_id: '',
                asset_type_details: [],
                asset_type_details_del: [],
                details: [],
                attachment_image: [],
                attachment_image_del: [],
                description: '',
                index: '',
                index1: "",
                index2: "",
                type: "",
            },
            fields: [
                {
                    key: '#',
                    label: 'ID',
                    class: "text-nowrap",
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap",
                },
                {
                    key: 'type',
                    label: 'Loại tài sản',
                    class: "text-nowrap",
                },
                {
                    key: 'asset_group_id',
                    label: 'Nhóm tài sản',
                    class: "text-nowrap",
                },
                // {
                //     key: 'amount',
                //     label: 'Giá mua',
                //     class: "text-nowrap",
                // },
                {
                    key: 'newtab',
                    label: '',
                }
            ],
            form_filter: {
                asset_group_id: '',
                asset: '',
                tool: '',
            },
            check: [],
            col_field: [
                {
                    key: '#',
                    label: '#',
                    class: "text-nowrap"
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap"
                },
                {
                    key: 'check',
                    label: 'Chọn',
                    class: "text-nowrap"
                },
            ],
            field_asset_type: [
                {
                    key: '#',
                    label: '#',
                    class: "text-nowrap"
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap"
                },
                {
                    key: 'value',
                    label: 'Value',
                    class: "text-nowrap"
                },
                {
                    key: 'action',
                    label: 'Action',
                    class: "text-nowrap"
                },
            ],
            field_asset_chitiet: [
                {
                    key: '#',
                    label: '#',
                    class: "text-nowrap"
                },
                {
                    key: 'name',
                    label: 'Tên',
                    class: "text-nowrap"
                }, {
                    key: 'value',
                    label: 'Nội dung',
                    class: "text-nowrap"
                },


            ],
            filter: '',
            counter: 0,
            image: '',
            sortBy: 'id',
            sortDesc: true,
            edit: false,
            loading: false,
            show_search: false,
            token: '',
            separator: '.',
            errors: {},
            url_asset_groups: "/api/asset/group",
            url_asset_types: "/api/asset/type",
            url_asset_field: "/api/asset/asset_field",
            page_url_taisan: "api/asset/taisan",
            page_url_congcudungcu: "api/asset/congcudungcu",
            url_asset_cat: "/api/asset/cat",
            asset_fields: [],
            isHovered: false,
            congcudungcu: [],
            taisan: [],
            isHovereds: false,
            mainProps: { blank: true, blankColor: '#777', width: 75, height: 75, class: 'm1' },
            disabled: false,
            timeout: null,
            url_asset_assets: "api/asset/assets",
            assets: [],
            asset_tools: [],
            url_asset_tool: "api/asset/stock",
            enabled: true,
            tableData: null,
            tableHeader: null,
            page_url_asset_import: "api/asset/asset_import",
            demo: '',
            error_import_excel: {},
            failed: {},
        }
    },
    created() {

        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        // this.fetDetails();
        this.fetchGroups();
        this.fetchTypes();
        this.fetchTaiSan();
        this.fetchCongCu();
        this.fetchCats();
        this.fetchField();
        this.fetchAssets();
        this.fetchTools();

    },

    methods: {
        exportExcel() {
            const selectedFieldsArray = Array.isArray(this.selected_fields) ? this.selected_fields : [this.selected_fields];
            const headers = [...selectedFieldsArray];
            const data = this.data.map(obj => headers.map(header => obj[header])) // lấy dữ liệu theo thuộc tính được chọn
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
            this.selected_fields = ["Mã Model Tài sản", "Tên Model Tài sản", "Loại", "Nhóm tài sản"];
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
            var page_url = this.page_url_asset_import;
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
                        this.tableData = response.data.data;
                        $('#import_excel').modal('hide');
                        $("#data_excel").modal("hide");
                        this.fetchTypes();
                    }
                })
                .catch(error => {
                    console.error(error);
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

        getAssets(id) {
            var obj = this.assets.find(x => x.asset_type_id == id);
            return obj ? obj.asset_type_id : '';
        },
        getTools(id) {

            var obj = this.asset_tools.find(x => x.asset_type_id == id);
            return obj ? obj.asset_type_id : '';
        },
        fetchTools() {
            const params = new URLSearchParams({
            });
            //$("#tbbody_id").html('');
            var page_url = this.url_asset_tool + '?' + params.toString();
            //  console.log(page_url);
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
        fetchAssets() {
            //$("#tbbody_id").html('');
            this.assets = Array();

            var page_url = this.url_asset_assets;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.assets = data.data;
                }).catch(err => {

                    console.log(err);
                });
        },

        delayType() {
            this.disabled = true

            // Re-enable after 5 seconds
            this.timeout = setTimeout(() => {
                this.disabled = false
            }, 3000)

            this.AddTypes()
        },
        fetchTaiSan() {

            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.taisan = Array();
            const params = new URLSearchParams({
                asset_group_id: this.form_filter.asset_group_id,

            });
            var page_url = this.page_url_taisan + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == 1) {
                        this.taisan = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchCongCu() {

            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.congcudungcu = Array();
            const params = new URLSearchParams({
                asset_group_id: this.form_filter.asset_group_id,

            });
            var page_url = this.page_url_congcudungcu + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == 1) {
                        this.congcudungcu = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

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
                            name: "Mã Model Tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Tên Model Tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Loại",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Nhóm tài sản",
                            disabled: true,
                            fromAssetFields: false,
                        },
                        {
                            name: "Ghi chú",
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
        fetchGroups() {
            var page_url = this.url_asset_groups;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_groups = res.data;
                    this.type.attachment_image_del = [];
                    this.type.asset_type_details_del = [];

                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetchCats() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;

            var page_url = this.url_asset_cat;//"/api/category/companies";
            //console.log('load');
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset_cats = res.data;

                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetchTypes() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_types = Array();
            const params = new URLSearchParams({
                asset_group_id: this.form_filter.asset_group_id,

            });
            var page_url = this.url_asset_types + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    if (res.success == 1) {
                        this.asset_types = res.data;
                        this.type.attachment_image_del = [];
                        this.type.asset_type_details_del = [];

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        AddTypes() {
            // this.type.details.push({...this.details});
            this.fetchField();

            var page_url = this.url_asset_types;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.type),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.data.success == 1) {
                            this.reset();
                            this.asset_types.push(data.data.data);
                            this.taisan.push(data.data.data);
                            this.congcudungcu.push(data.data.data);

                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addtypeAsset").modal("hide");

                        } else {

                            this.errors = data.data.errors;

                            this.showError('Thông báo', this.errors.field[0]);


                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.type.id, {
                    method: "PUT",
                    body: JSON.stringify(this.type),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data.success == 1) {
                            let index = this.type.index;
                            let index1 = this.type.index1;
                            let index2 = this.type.index2;
                            this.type = { ...data.data.data };
                            this.$set(this.asset_types, index, { ...this.type });
                            this.$set(this.taisan, index1, { ...this.type });
                            this.$set(this.congcudungcu, index2, { ...this.type });

                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addtypeAsset").modal("hide");
                            this.reset();
                        } else {

                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.field[0]);



                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        emitEventImg(event) {
            // this.type.attachment_image = [];

            for (let index = 0; index < event.target.files.length; index++) {
                const imgg = event.target.files[index];
                let reader = new FileReader();
                reader.readAsDataURL(imgg);

                reader.onload = () => {
                    const docs = {
                        name: imgg.name,
                        url: imgg.url,
                        size: imgg.size,
                        ext: imgg.type.split("/").pop(),
                        lastModifiedDate: imgg.lastModifiedDate,
                        base64: reader.result
                    };
                    this.type.attachment_image.push({ ...docs });

                };
            }
            event.target.value = "";
            $("#imggdinhkem").collapse("show");

        },
        handleClickInputImg() {

            this.$refs.imgg.click();

        },
        deleteImg(item, index) {
            // this.type.attachment_image = [];
            // console.log(this.type.attachment_image);

            if (confirm('Bạn muốn xoá ảnh?')) {

                this.type.attachment_image_del.push({ ...item });

                this.type.attachment_image.splice(index, 1);
            }
        },
        createImage(imgg) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(imgg);
        },
        onImageChange(b) {
            let files = b.target.files || b.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        getAssetGroupValueName(id) {
            var obj = this.asset_groups.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        showAssetType() {
            this.reset();
            $("#addtypeAsset").modal("show");
        },
        editAssetType(type) {
            this.edit = true;
            this.clearAllError();
            this.fetchAssets();
            this.fetchTools();
            this.fetchField();

            let index = this.asset_types.findIndex(i => {
                return i.id == type.id;
            });
            let index1 = this.taisan.findIndex(i => {
                return i.id == type.id;
            });
            let index2 = this.congcudungcu.findIndex(i => {
                return i.id == type.id;
            });

            // this.index=-1;

            this.type.id = type.id;
            this.type.name = type.name;
            this.type.code = type.code;
            this.type.type = type.type;
            this.type.asset_cat_id = type.asset_cat_id;
            this.type.asset_group_id = type.asset_group_id;
            this.type.amount = type.amount;
            this.type.description = type.description;
            this.type.asset_type_details = type.asset_type_details;


            this.type.attachment_image = [...type.attachment_image];
            if (this.type.attachment_image == null) {
                this.type.attachment_image = [];
            }
            this.type.asset_type_details_del = [];
            this.type.attachment_image_del = [];
            this.image = '';
            this.type.index = index;
            this.type.index1 = index1;
            this.type.index2 = index2;
            $("#addtypeAsset").modal("show");

        },
        showA(type) {
            this.edit = true;
            this.clearAllError();
            this.fetchAssets();
            this.fetchTools();
            this.fetchField();

            let index = this.asset_types.findIndex(i => {
                return i.id == type.id;
            });
            let index1 = this.taisan.findIndex(i => {
                return i.id == type.id;
            });
            let index2 = this.congcudungcu.findIndex(i => {
                return i.id == type.id;
            });

            // this.index=-1;

            this.type.id = type.id;
            this.type.name = type.name;
            this.type.code = type.code;
            this.type.type = type.type;
            this.type.asset_cat_id = type.asset_cat_id;
            this.type.asset_group_id = type.asset_group_id;
            this.type.amount = type.amount;
            this.type.description = type.description;
            this.type.asset_type_details = type.asset_type_details;


            this.type.attachment_image = [...type.attachment_image];
            if (this.type.attachment_image == null) {
                this.type.attachment_image = [];
            }
            this.type.asset_type_details_del = [];
            this.type.attachment_image_del = [];
            this.image = '';
            this.type.index = index;
            this.type.index1 = index1;
            this.type.index2 = index2;
            $("#Showw").modal("show");

        },
        DeleteAssetType(id) {
            this.fetchAssets();
            this.fetchTools();
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_types}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        // if(data.data.success ==0){


                        // }
                        if (data.data.success == 1) {

                            this.showMessage('Thông báo', 'Xoá thành công');
                            // this.loading=true;
                            this.fetchTypes();
                            this.fetchTaiSan();
                            this.fetchCongCu();
                            this.reset();


                        } else {
                            this.errors = data.data.errors;
                            toastr.warning(this.errors);
                        }


                    });
            }

        },
        clearAllError() {
            this.errors = {};
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.type) {
                this.type[field] = null;
            }
            this.type.asset_type_details = [];
            this.type.attachment_image = [];
            this.type.attachment_image_del = [];
            this.type.asset_type_details_del = [];
            this.type.index = -1;
            this.type.index1 = -1;
            this.type.index2 = -1;
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
        hidetool(item, type) {
            if (!item || type !== 'row') return
            if (item.type == 1) return 'd-none';
        },
        hideasset(item, type) {
            if (!item || type !== 'row') return
            if (item.type == 0) return 'd-none';
        },
        filter_data() {
            // this.counter++;
            // if (this.form_filter.asset_group_id !== "" && this.counter > 1 || this.form_filter.asset_group_id == "" && this.counter > 1 ) {
            this.fetchTypes();
            this.fetchTaiSan();

            this.fetchCongCu();

            //     this.counter = 0;
            // }


        },
        handleHover(hovered) {
            this.isHovered = hovered
        },
        handleHovers(hovereds) {
            this.isHovereds = hovereds
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }
            this.fetchTypes();
        },
        refest() {
            if (this.type.type == 0) {
                this.type.asset_cat_id = null;
                this.type.asset_group_id = '';
            }
            else {
                this.type.asset_group_id = '';
                this.type.asset_cat_id = '';

            }
        },
        show_fields() {
            this.check = [];
            this.fetchField();
            $("#Add_fields").modal("show");
        },
        Close_fields() {
            $("#Add_fields").modal("hide");

        },
        Add_custom_fields() {
            console.log(this.check);
            for (let index = 0; index < this.check.length; index++) {
                this.field.name = this.check[index].name;
                this.field.value = this.check[index].value;
                let isexist = false;
                this.type.asset_type_details.forEach(element => {
                    if (element.name == this.check[index].name) {
                        isexist = true;
                    }

                });
                if (!isexist) {
                    this.type.asset_type_details.push({ ...this.field });
                }

            }
            $("#Add_fields").modal("hide");

        },
        DeleteItems(item, index) {

            if (confirm(this.$t('form.confirm_delete') + '?')) {

                this.type.asset_type_details_del.push({ ...item });

                this.type.asset_type_details.splice(index, 1);
            }

        },


    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        rows() {
            return this.asset_types.length;
        },
        selectedExcel() {
            return this.selected_fields.map(function (field) {
                return { label: field, value: "Nhập" };
            });
        }
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

.form-group {
    margin-bottom: 5px !important;
}

#search:focus {
    border: 1px solid #ced4da;
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

#shadow_btn:hover {
    color: white;
    box-shadow: 1px 1px 10px #3c8dbc;
}

.cssfile:hover {
    background: lightseagreen;
    color: #f8f9fa;
}

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;
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

#show_btn_cancel:hover {
    color: white;
    box-shadow: 1px 1px 10px red;

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
</style>