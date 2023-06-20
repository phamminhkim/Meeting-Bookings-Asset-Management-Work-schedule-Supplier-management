<template>
    <div>
        <div class="content-header">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="m-0 text-dark" style="font-weight:700"> <i class="fas fa-cog"></i> {{ $t(title) }}
                        </h4>

                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
        </div>

        <div class="sha">
            <b-tabs card small table-info class="color" active-nav-item-class="animation text-uppercase"
                content-class="mt-3">
                <b-tab title="Kho hàng" id="khohang" title-link-class="animation1" class="mytabs">
                    <template #title>
                        <div class="tess">

                            <i class="fas fa-cube"></i> <strong>Kho hàng</strong>

                        </div>
                    </template>
                    <div class="content-header" style="border-bottom:1px solid gray">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="m-0 text-dark"><b>Kho hàng</b></h5>
                                    <span class="text-secondary">Quản lý kho hàng</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                            <div class="input-group mb-3" style="width:100%;float:right;">
                                                <input type="text" class="form-control" placeholder="Nhập tên kho"
                                                    v-model="form_filter.name" aria-describedby="basic-addon2"
                                                    @keyup.space="filter_datas" @keyup.delete="backsspace"
                                                    @keyup.enter="filter_datas" style="border-right:none;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-sm" @click="filter_datas()"
                                                        style="border-bottom-right-radius: 5px;border-top-right-radius: 5px; border-top-left-radius: 3px;border-bottom-left-radius: 3px;=">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn  ml-1" style="border-radius: 30px;"
                                                        @click.prevent="reloadPage()">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="float-sm-right">
                                                <button class="btn btn-primary text-black" id="shadow_btn"
                                                    @click="view()"><i class="fas fa-plus"></i> Tạo kho</button>
         
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="" style="margin-top:20px;">
                        <div class="">

                            <div class="card" style="border-bottom:1px solid whitesmoke"
                                v-for="ware in asset_warehouses" :key="ware.id" :filter="filter">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-2"><i class="fas fa-cube mr-1"></i><b>{{
                                                ware.name
                                            }}</b>
                                            </div>
                                        </div>
                                        <div class="col-md-5">

                                            <div class="text-secondary ml-4 mt-2">
                                                <span><b>Mã công ty:</b> {{ ware.company_id }}</span><br>
                                                <span><b>Mã kho:</b> {{ ware.code }}</span><br>

                                                <span><b>Địa chỉ:</b> {{ ware.address }}</span><br>
                                                <span><b>Điện thoại:</b> {{ ware.phone }}</span><br>
                                                <span><b>Tạo bởi:</b> {{ $t(getuserName(ware.create_by)) }}
                                                    <br> Ngày {{ ware.created_at | formatDB }} </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12 mt-2 ">
                                                    <div style="display:inline-flex"><b>Người quản lý</b></div>
                                                    <div class="float-left mr-2" v-for="user in users" :key="user.id">
                                                        <b-avatar v-b-tooltip.hover variant="info"
                                                            v-if="ware.user_id == user.id" :src="user.avatar"
                                                            :title="user.username + ' - ' + user.name" size="2rem">
                                                        </b-avatar>
                                                    </div>
                                                    <div>
                                                        <span v-if="ware.group_id"><b>Group:</b> {{ ware.group.name }}</span><br>
                                                    </div>
                                                </div>

                                                <!-- <span>
                                                            <div v-for="user in users" :key="user.id">
                                                                <b-avatar v-b-tooltip.hover variant="info"
                                                                    v-if="ware.user_id == user.id" :src="user.avatar"
                                                                    :title="user.username + ' - ' + user.name" size="2rem"></b-avatar>
                                                            </div>
                                                        </span> -->

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="float-right">
                                                <b-dropdown text="Sửa" right variant="white">
                                                    <b-dropdown-item v-b-hover="handleHover"
                                                        @click="EditWarehouse(ware)">
                                                        <span :class="isHovered ? 'text-primary' : ''">Chỉnh
                                                            sửa</span>
                                                    </b-dropdown-item>
                                                    <b-dropdown-item v-b-hover="handleHovers"
                                                        @click="DeleteWarehouse(ware.id)">
                                                        <span :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                                    </b-dropdown-item>
                                                </b-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- popup -->
                    <div class="modal" id="addAsset" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form @submit.prevent="AddWarehouse">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label v-if="!edit">TẠO KHO HÀNG MỚI</label>
                                            <label v-if="edit">CHỈNH SỬA KHO HÀNG</label>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Công ty</label> <small class="text-danger"> (*) </small>
                                            <select v-model="warehouses.company_id" class="form-control" id="company_id"
                                                name="company_id"
                                                v-bind:class="hasError('company_id') ? 'is-invalid' : ''">
                                                <option v-for="company in companies" :key="company.id"
                                                    :value="company.id">
                                                    {{ company.name }}</option>
                                            </select>
                                            <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('company_id') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Mã kho hàng</label><small class="text-danger"> (*) </small>
                                            <input class="form-control" placeholder="Mã kho hàng"
                                                v-model="warehouses.code"
                                                v-bind:class="hasError('code') ? 'is-invalid' : ''">
                                            <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('code') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Tên kho hàng</label> <small class="text-danger"> (*) </small>
                                            <input class="form-control" placeholder="Tên Kho hàng" id="name" name="name"
                                                v-model="warehouses.name"
                                                v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('name') }}</strong>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label>Người quản lý</label> <small class="text-danger"> (*)
                                            </small>
                                            <treeselect placeholder="Chọn người quản lý" :disable-branch-nodes="true"
                                                id="user_id" :show-count="true" :multiple="false"
                                                v-model="warehouses.user_id" name="user_id" :options="users_select"
                                                v-bind:class="hasError('user_id') ? 'is-invalid' : ''">
                                            </treeselect>

                                            <span v-if="hasError('user_id')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('user_id') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Group</label> <small class="text-danger"> (*)</small>
                                            <select class="form-control" v-model="warehouses.group_id" id="group_id" name="group_id" v-bind:class="hasError('group_id') ? 'is-invalid' : ''">
                                                <option  v-for="(group_cat,index) in group_categories" :key="index" :value="group_cat.id">  {{ group_cat.name }} </option>
                                            </select>
                                            <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('group_id') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input class="form-control" placeholder="Số điện thoại"
                                                v-model="warehouses.phone"
                                                v-bind:class="hasError('phone') ? 'is-invalid' : ''">
                                            <span v-if="hasError('phone')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('phone') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input class="form-control" placeholder="Địa chỉ kho hàng"
                                                v-model="warehouses.address"
                                                v-bind:class="hasError('address') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('address')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('address') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"
                                            style="width:47%;">
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
                </b-tab>
                <b-tab title="Nhóm tài sản" title-link-class="animation1" class="mytabs">
                    <template #title>
                        <div class="tess">
                            <i class="fas fa-folder"></i> <strong>Nhóm tài sản</strong>
                        </div>

                    </template>
                    <div class="content-header" style="border-bottom:1px solid gray">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="m-0 text-dark"><b>Nhóm tài sản</b></h5>
                                    <span class="text-secondary">Quản lý nhóm tài sản</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                            <div class="input-group mb-3" style="width:100%;float:right;">
                                                <input type="text" class="form-control"
                                                    placeholder="Nhập tên nhóm tài sản" v-model="filter_group.name"
                                                    @keyup.enter="filter_groups" aria-describedby="basic-addon2"
                                                    style="border-right:none;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-sm" @click="filter_groups()"
                                                        style="border-bottom-right-radius:5px;border-top-right-radius:5px;border-top-left-radius:3px;border-bottom-left-radius:3px;">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn  ml-1" @click.prevent="reloadPage()">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="float-sm-right">
                                                <button class="btn btn-primary text-black" id="shadow_btn"
                                                    @click="viewgroup()"><i class="fas fa-plus"></i> Tạo nhóm
                                                    tài sản</button>
                                                <!-- <b-dropdown id="dropdown-1" right text="Tạo mới" variant="primary">
                                                        <b-dropdown-item v-b-hover="handleHover" @click="viewgroup()">
                                                            <span :class="isHovered ? 'text-primary' : ''">Tạo nhóm tài sản
                                                                mới</span>
                                                        </b-dropdown-item>
                                                    </b-dropdown> -->
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top:20px;">
                        <div class="card-body">
                            <div class="form-group" style="border-bottom:1px solid whitesmoke"
                                v-for="group in asset_groups" :key="group.id">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div class="col-sm-5">
                                        <p><b>{{ group.name }}</b></p>
                                        <div class="text-secondary">
                                            <span>{{ group.name }}</span><br>
                                            <span><b>Tạo bởi:</b> {{ $t(getuserName(group.create_by)) }}

                                                <br><b>Ngày: </b> {{ group.created_at | formatDB }}
                                                <br><b>Mô tả: </b>{{ group.description }}
                                            </span>

                                        </div>
                                    </div>
                                    <div class="col-sm-3">

                                    </div>
                                    <div class="col-sm-3">
                                        <div class="float-right">
                                            <b-dropdown text="Sửa" right variant="white">
                                                <b-dropdown-item v-b-hover="handleHover" @click="EditGroups(group)">
                                                    <span :class="isHovered ? 'text-primary' : ''">Chỉnh
                                                        sửa</span>
                                                </b-dropdown-item>
                                                <b-dropdown-item v-b-hover="handleHovers"
                                                    @click="DeleteGroups(group.id)"><span
                                                        :class="isHovereds ? 'text-danger' : ''">Xóa</span>
                                                </b-dropdown-item>
                                            </b-dropdown>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- popup -->
                    <div class="modal" id="addgroupAsset" tabindex="-1" role="dialog">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form @submit.prevent="AddGroups">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label v-if="!edit">TẠO NHÓM TÀI SẢN MỚI</label>
                                            <label v-if="edit">CHỈNH SỬA NHÓM TÀI SẢN MỚI</label>

                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tên nhóm tài sản</label> <small class="text-danger"> (*)
                                            </small>
                                            <input v-model="groups.name" class="form-control" rows="3" id="name"
                                                name="name" placeholder="Tối đa 150 kí tự"
                                                v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('name') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Mô tả nhóm tài sản</label>
                                            <textarea v-model="groups.description" class="form-control" rows="5"
                                                placeholder="Mô tả nhóm tài sản"
                                                v-bind:class="hasError('description') ? 'is-invalid' : ''"> </textarea>
                                            <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('description') }}</strong>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"
                                            style="width:47%;">
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
                </b-tab>
                <b-tab title="Chính sách" class="mytabs" title-link-class="animation1">
                    <template #title>
                        <div class="tess">
                            <i class="fas fa-sticky-note"></i> <strong>Chính sách</strong>
                        </div>

                    </template>
                    <div class="content-header" style="border-bottom:1px solid gray">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="m-0 text-dark"><b>Chính sách</b></h5>
                                    <span class="text-secondary">Quản lý chính sách tài sản</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                            <div class="input-group mb-3" style="width:100%;float:right;">
                                                <input type="text" class="form-control"
                                                    placeholder="Nhập tên chính sách" v-model="filter_policy.name"
                                                    aria-describedby="basic-addon2" style="border-right:none;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-sm" @click="filter_policies()"
                                                        style="border-bottom-right-radius:5px;border-top-right-radius:5px;border-top-left-radius:3px;border-bottom-left-radius:3px;">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn  ml-1" style="border-radius: 30px;"
                                                        @click.prevent="reloadPage()">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="float-sm-right">
                                                <button class="btn btn-primary text-black" id="shadow_btn"
                                                    @click="viewpolicy()"><i class="fas fa-plus"></i> Tạo chính
                                                    sách</button>

                                                <!-- <b-dropdown v-b-hover="handleHover" id="dropdown-1" right text="Tạo mới"
                                                        variant="primary">
                                                        <b-dropdown-item @click="viewpolicy()">
                                                            <span :class="isHovered ? 'text-primary' : ''"> Tạo chính sách
                                                                mới</span>
                                                        </b-dropdown-item>
                                                    </b-dropdown> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert">


                        <b-alert variant="success" show><span><i class="fas fa-lightbulb"
                                    style="margin-right:5px"></i><a class="text-white">Nhấp chuôt vào đây để xem
                                    cách
                                    bạn có thể thiết lập mẫu in</a></span></b-alert>
                    </div>
                    <div class="card" style="margin-top:20px;">
                        <div class="card-body">
                            <div class="form-group" style="border-bottom:1px solid whitesmoke"
                                v-for="policy in asset_policies" :key="policy.id">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <div class="col-sm-8">
                                        <p><b>{{ policy.name }}</b><br>
                                            <span v-if="policy.type == 0" class="badge bg-success">Bàn
                                                giao</span>
                                            <span v-if="policy.type == 1" class="badge bg-warning">Thu
                                                hồi</span>
                                            <span v-if="policy.type == 2" class="badge bg-danger">Hủy bỏ</span>
                                            <span v-if="policy.type == 3" class="badge bg-success">Phục
                                                hồi</span>
                                            <span v-if="policy.type == 4" class="badge bg-danger">Mất</span>
                                            <span v-if="policy.type == 5" class="badge bg-warning">Sửa
                                                chữa</span>
                                            <span v-if="policy.type == 6" class="badge bg-danger">Hỏng</span>
                                        </p>

                                        <div class="text-secondary">
                                            <span><b>Tạo bởi:</b> {{ $t(getuserName(policy.create_by)) }}
                                                <br> Ngày {{ policy.created_at | formatDB }} </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="float-right">
                                            <b-dropdown text="Sửa" right variant="white">
                                                <b-dropdown-item v-b-hover="handleHover" @click="EditPolicies(policy)">
                                                    <span :class="isHovered ? 'text-primary' : ''">Chỉnh sửa
                                                    </span>
                                                </b-dropdown-item>
                                                <b-dropdown-item v-b-hover="handleHovers"
                                                    @click="DeletePolicies(policy.id)">
                                                    <span :class="isHovereds ? 'text-danger' : ''">Xóa
                                                    </span>
                                                </b-dropdown-item>

                                            </b-dropdown>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- popup -->
                    <div class="modal" id="addpolicyAsset" tabindex="-1" role="dialog">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form @submit.prevent="AddPolicies()" @keydown.enter.prevent="clearError($event)">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label v-if="!edit">TẠO MỚI CHÍNH SÁCH</label>
                                            <label v-if="edit">CHỈNH SỬA CHÍNH SÁCH</label>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tên</label> <small class="text-danger"> (*) </small>
                                            <input v-model="policies.name" class="form-control" id="name" name="name"
                                                placeholder="Tối đa 255 kí tự"
                                                v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('name') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Loại chính sách</label><small class="text-danger"> (*)
                                            </small>
                                            <select v-model="policies.type" class="form-control" id="type" name="type"
                                                v-bind:class="hasError('name') ? 'is-invalid' : ''">
                                                <option value="0" selected>Bàn giao</option>
                                                <option value="1">Thu hồi</option>
                                                <option value="2">Hủy bỏ</option>
                                                <option value="3">Phục hồi</option>
                                                <option value="4">Mất</option>
                                                <option value="5">Sửa chữa</option>
                                                <option value="6">Hỏng</option>
                                            </select>
                                            <span v-if="hasError('type')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('type') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <input type="file" v-on:change="onImageChange" class="form-control"
                                                accept=".xlsx,.xls,image/x-png,image/gif,image/jpeg,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                                name="images[]" @change="emitEvent($event)" id="ThemFile"
                                                style="display:none" ref="file" multiple>
                                            <button type="button" class="btn right btn-sm cssfile"
                                                style="font-weight: 600;height:30px;border:1px solid lightgray"
                                                v-on:click="handleClickInputFile()">
                                                Mẫu in
                                            </button>

                                        </div>
                                        <div class="form-group" v-for="(file, index) in policies.attachment_file"
                                            v-bind:key="index">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div style="font-size:12px;display:inline-block;width:78%">
                                                        <span><i> {{ index + 1 + '.' + file.name }}</i></span>
                                                    </div>
                                                    <div class="float-right">
                                                        <button style="border-radius:50px;font-size:12px"
                                                            class="btn text-red"
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
                                        <div class="form-group">
                                            <label>Điều kiện chính sách & điều khoản</label>
                                            <ckeditor v-model="policies.policy_conditions" value="Mô tả chi tiết"
                                                v-bind:class="hasError('policy_conditions') ? 'is-invalid' : ''">
                                            </ckeditor>
                                            <span v-if="hasError('policy_conditions')" class="invalid-feedback"
                                                role="alert">
                                                <strong>{{ getError('policy_conditions') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"
                                            style="width:47%;">
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
                </b-tab>
                <b-tab title="Loại" class="mytabs" title-link-class="animation1">
                    <template #title>
                        <div class="tess">
                            <i class="fas fa-cubes"></i> <strong>Loại CCDC</strong>
                        </div>

                    </template>
                    <div class="content-header" style="border-bottom:1px solid gray">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="m-0 text-dark"><b>Loại công cụ dụng cụ</b></h5>
                                    <span class="text-secondary">Quản lý loại công cụ dụng cụ</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                            <div class="input-group mb-3" style="width:100%;float:right;">
                                                <input type="text" class="form-control" v-model="filter_cat.name"
                                                    @keyup.enter="filter_cats"
                                                    placeholder="Nhập tên loại công cụ dụng cụ"
                                                    aria-describedby="basic-addon2" style="border-right:none;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-sm" @click.enter="filter_cats()"
                                                        style="border-bottom-right-radius:5px;border-top-right-radius:5px;border-top-left-radius:3px;border-bottom-left-radius:3px; ">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn  ml-1" style="border-radius: 30px;">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="float-sm-right">
                                                <button class="btn btn-primary text-black" id="shadow_btn"
                                                    @click="viewcat()"><i class="fas fa-plus"></i> Tạo
                                                    loại</button>

                                                <!-- <b-dropdown v-b-hover="handleHover" id="dropdown-1" right text="Tạo mới"
                                                        variant="primary">
                                                        <b-dropdown-item @click="viewcat()">
                                                            <span :class="isHovered ? 'text-primary' : ''"> Tạo loại</span>
                                                        </b-dropdown-item>
                                                    </b-dropdown> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top:20px;">
                        <div class="card-body">
                            <div class="form-group" style="border-bottom:1px solid whitesmoke" v-for="cat in asset_cats"
                                :key="cat.id">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <i class="fas fa-cubes"></i>
                                    </div>
                                    <div class="col-sm-8">
                                        <p><b>{{ cat.name }}</b><br>
                                            <span class="text-secondary"> {{ cat.code }} </span>

                                        </p>


                                    </div>
                                    <div class="col-sm-1">

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="float-right">
                                            <b-dropdown text="Sửa" right variant="white">
                                                <b-dropdown-item v-b-hover="handleHover" @click="EditCats(cat)">
                                                    <span :class="isHovered ? 'text-primary' : ''">Chỉnh sửa
                                                    </span>
                                                </b-dropdown-item>
                                                <b-dropdown-item v-b-hover="handleHovers" @click="DeleteCats(cat.id)">
                                                    <span :class="isHovereds ? 'text-danger' : ''">Xóa
                                                    </span>
                                                </b-dropdown-item>

                                            </b-dropdown>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal" id="addcatasset" tabindex="-1" role="dialog">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form @submit.prevent="AddCats()" @keydown.enter.prevent="clearError($event)">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label v-if="!edit">TẠO MỚI LOẠI</label>
                                            <label v-if="edit">CHỈNH SỬA LOẠI</label>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tên</label> <small class="text-danger"> (*) </small>
                                            <input v-model="cats.name" class="form-control" id="name" name="name"
                                                placeholder="Tối đa 255 kí tự"
                                                v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('name') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Mã</label><small class="text-danger"> (*) </small>
                                            <input v-model="cats.code" class="form-control" id="code" name="code"
                                                v-bind:class="hasError('code') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('code') }}</strong>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"
                                            style="width:47%;">
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
                </b-tab>
                <b-tab title="Cấu hình Model tài sản" class="mytabs" title-link-class="animation1">
                    <template #title>
                        <div class="tess">
                            <i class="fad fa-layer-plus"></i> <strong>Cấu hình Model tài sản</strong>
                        </div>

                    </template>
                    <div class="content-header" style="border-bottom:1px solid gray">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5 class="m-0 text-dark"><b>Cấu hình Model tài sản</b></h5>
                                    <span class="text-secondary">Quản lí cấu hình của Model tài sản</span>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <!-- <input class="form-control" style="width:50%;float:right;height:92%" placeholder="search model " /> -->
                                            <div class="input-group mb-3" style="width:100%;float:right;">
                                                <input type="text" class="form-control" v-model="filter_type.name"
                                                    @keyup.enter="filter_field" placeholder="Nhập tên cấu hình"
                                                    aria-describedby="basic-addon2" style="border-right:none;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-sm" @click.enter="filter_field()"
                                                        style="border-bottom-right-radius:5px;border-top-right-radius:5px;border-top-left-radius:3px;border-bottom-left-radius:3px; ">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button class="btn  ml-1" style="border-radius: 30px;">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="float-sm-right">
                                                <button class="btn btn-primary text-black" id="shadow_btn"
                                                    @click="viewtype()"><i class="fas fa-plus"></i> Thêm cấu
                                                    hình</button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top:20px;">
                        <div class="card-body">
                            <div class="form-group" style="border-bottom:1px solid whitesmoke"
                                v-for="field in asset_field" :key="field.id">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <i class="fad fa-layer-plus"></i>
                                    </div>
                                    <div class="col-sm-8">
                                        <p><b>Tên: {{ field.name }}</b><br>
                                            <span class="text-secondary">Mô tả: {{ field.description }} </span>

                                        </p>


                                    </div>
                                    <div class="col-sm-1">

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="float-right">
                                            <b-dropdown text="Sửa" right variant="white">
                                                <b-dropdown-item v-b-hover="handleHover" @click="EditField(field)">
                                                    <span :class="isHovered ? 'text-primary' : ''">Chỉnh sửa
                                                    </span>
                                                </b-dropdown-item>
                                                <b-dropdown-item v-b-hover="handleHovers"
                                                    @click="DeleteField(field.id)">
                                                    <span :class="isHovereds ? 'text-danger' : ''">Xóa
                                                    </span>
                                                </b-dropdown-item>

                                            </b-dropdown>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal" id="addfields" tabindex="-1" role="dialog">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form @submit.prevent="addfields()" @keydown.enter.prevent="clearError($event)">
                                    <div class="modal-header" style="background:whitesmoke;">
                                        <div class="modal-title">
                                            <label v-if="!edit">TẠO MỚI CẤU HÌNH</label>
                                            <label v-if="edit">CHỈNH SỬA CẤU HÌNH</label>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tên</label> <small class="text-danger"> (*) </small>
                                            <input v-model="fields.name" class="form-control" id="name" name="name"
                                                placeholder="" v-bind:class="hasError('name') ? 'is-invalid' : ''" />
                                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('name') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>Mô tả</label><small class="text-danger"></small>
                                            <textarea v-model="fields.description" class="form-control" id="description"
                                                name="description" rows="5"
                                                v-bind:class="hasError('description') ? 'is-invalid' : ''"></textarea>
                                            <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                                <strong>{{ getError('description') }}</strong>
                                            </span>
                                        </div>

                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                                    style="width:47%;">
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
                        </b-tab>
                    
                    </b-tabs>
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
            asset_warehouses: [],
            warehouses: {
                id: '',
                name: '',
                company_id: '',
                code: '',
                create_by: '',
                user_id: null,
                group_id:'',
                phone: '',
                address: '',
                index: ''
            },
            filter: '',
            form_filter: {
                name: '',
            },
            filter_group: {
                name: '',
            },
            filter_policy: {
                name: '',
            },
            filter_cat: {
                name: ''
            },
            filter_type: {
                name: ''
            },
            asset_groups: [],
            groups: {
                id: '',
                name: '',
                description: '',
                index: '',
            },
            cats: {
                id: '',
                name: '',
                code: '',
                index: ''
            },
            fields: {
                id: '',
                name: '',
                description: '',
                index: ''
            },
            unique_id: {
                id:'',
                module: '',
                serial: '',
                auto: 1,
                company_id: '',
                letter:'',
                index:'',
            },
            asset_policies: [],
            policies: {
                attachment_file: [],
                attachment_file_del: [],
                id: '',
                name: '',
                create_by: '',
                type: '',
                policy_conditions: '',
                index: ''
            },
            edit: false,
            loading: false,
            show_search: false,
            token: '',
            url_asset_warehouses: "/api/asset/warehouse",
            url_asset_groups: "/api/asset/group",
            asset_field:[],
            url_asset_cat: "/api/asset/cat",
            url_asset_policies: "/api/asset/policy",
            page_url_users: "api/user/all",
            page_url_company: "/api/category/companies",

            companies: [],
            asset_cats: [],
            users: [],
            users_select: [],
            page_url_users_select: "api/user/allnew",
            url_asset_fields: "/api/asset/asset_field",
            page_url_group_category: "api/category/groups",
            group_categories:[],
            errors: {},
            isHovered: false,
            isHovereds: false,
            image: '',
            enabled: true,
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetCompany();
        this.fetchCats();
        this.fetchWarehouse();
        this.fetchGroups();
        this.fetchPolicies();
        this.getUser();
        this.getUserSelect();
        this.fetchField();
        this.fetchGroupCategory();
    },
    methods: {
        fetchGroupCategory() {
            var page_url = this.page_url_group_category;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.group_categories = res.data;
                })
                .catch(err => {
                    console.log(err);

                });
        },
        fetchField() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_field = Array();
            const params = new URLSearchParams({
                name: this.filter_type.name,
            });
            var page_url = this.url_asset_fields + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_field = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        addfields() {
            var page_url = this.url_asset_fields;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.fields),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.asset_field.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addfields").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Lỗi');
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.fields.id, {
                    method: "PUT",
                    body: JSON.stringify(this.fields),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.asset_field, this.fields.index, { ...this.fields });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addfields").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', 'Lỗi');
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditField(fields) {
            let index = this.asset_field.findIndex(i => {
                console.log("id" + i.id);
                return i.id == fields.id;
            });
            this.clearAllError();
            this.edit = true;
            this.fields.id = fields.id;
            this.fields.name = fields.name;
            this.fields.create_by = fields.create_by;
            this.fields.created_at = fields.created_at;
            this.fields.description = fields.description;
            this.fields.index = index;
            $("#addfields").modal("show");
        },
        DeleteField(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_fields}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data.success == 1) {
                            this.showMessage('Thông báo', 'Xoá thành công');
                            this.fetchField();
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            toastr.warning(this.errors);
                        }

                    });
            }
        },
        getUserSelect() {

            var page_url = this.page_url_users_select + "?type=tree_combobox";
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.users_select = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        fetCompany() {
            var page_url = this.page_url_company;//"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                })
                .catch(err => {
                    console.log(err);

                });
        },
        getuserName(id) {
            var obj = this.users.find(x => x.id == id);
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
        fetchWarehouse() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_warehouses = Array();
            const params = new URLSearchParams({
                name: this.form_filter.name,
            });
            var page_url = this.url_asset_warehouses + '?' + params.toString();
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
        fetchGroups() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_groups = Array();
            const params = new URLSearchParams({
                name: this.filter_group.name,
            });
            var page_url = this.url_asset_groups + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_groups = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchCats() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_cats = Array();
            const params = new URLSearchParams({
                name: this.filter_cat.name,
            });
            var page_url = this.url_asset_cat + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_cats = res.data;

                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fetchPolicies() {
            //$("#tbbody_id").html('');
            this.loading = true;
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            let vm = this;
            this.asset_policies = Array();
            const params = new URLSearchParams({
                name: this.filter_policy.name,
            });
            var page_url = this.url_asset_policies + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success == '1') {
                        this.asset_policies = res.data;

                        // this.policies.attachment_file_del = [];

                    }
                    //// this.policies.attachment_file = [];

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        AddWarehouse() {
            var page_url = this.url_asset_warehouses;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.warehouses),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.asset_warehouses.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addAsset").modal("hide");
                            this.fetchWarehouse();
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors);

                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.warehouses.id, {
                    method: "PUT",
                    body: JSON.stringify(this.warehouses),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {

                            this.$set(this.asset_warehouses, this.warehouses.index, { ...this.warehouses });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addAsset").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            console.log(this.errors);
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            }


        },
        DeleteWarehouse(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_warehouses}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchWarehouse();
                    });
            }
        },
        EditWarehouse(warehouses) {

            let index = this.asset_warehouses.findIndex(i => {
                console.log("id" + i.id);

                return i.id == warehouses.id;
            });
            this.clearAllError();
            this.edit = true;
            this.warehouses.id = warehouses.id;
            this.warehouses.name = warehouses.name;
            this.warehouses.create_by = warehouses.create_by;
            this.warehouses.phone = warehouses.phone;
            this.warehouses.code = warehouses.code
            this.warehouses.address = warehouses.address;
            this.warehouses.user_id = warehouses.user_id;
            this.warehouses.group_id = warehouses.group_id;
            this.warehouses.created_at = warehouses.created_at;
            this.warehouses.company_id = warehouses.company_id;

            this.warehouses.index = index;
            $("#addAsset").modal("show");
        },
        AddCats() {
            var page_url = this.url_asset_cat;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.cats),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.asset_cats.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addcatasset").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.cats.id, {
                    method: "PUT",
                    body: JSON.stringify(this.cats),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.asset_cats, this.cats.index, { ...this.cats });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addcatasset").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditCats(cats) {
            let index = this.asset_cats.findIndex(i => {
                console.log("id" + i.id);
                return i.id == cats.id;
            });
            this.clearAllError();
            this.edit = true;
            this.cats.id = cats.id;
            this.cats.name = cats.name;
            this.cats.code = cats.code;
            this.cats.index = index;
            $("#addcatasset").modal("show");
        },
        DeleteCats(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_cat}/${id}`, {
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
        AddGroups() {
            var page_url = this.url_asset_groups;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.groups),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.data.errors) {
                            this.reset();
                            this.asset_groups.push(data.data);
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addgroupAsset").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.groups.id, {
                    method: "PUT",
                    body: JSON.stringify(this.groups),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            this.$set(this.asset_groups, this.groups.index, { ...this.groups });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addgroupAsset").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditGroups(groups) {
            let index = this.asset_groups.findIndex(i => {
                console.log("id" + i.id);
                return i.id == groups.id;
            });
            this.clearAllError();
            this.edit = true;
            this.groups.id = groups.id;
            this.groups.name = groups.name;
            this.groups.create_by = groups.create_by;
            this.groups.created_at = groups.created_at;
            this.groups.description = groups.description;
            this.groups.index = index;
            $("#addgroupAsset").modal("show");
        },
        DeleteGroups(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_groups}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchGroups();
                    });
            }
        },
        AddPolicies() {
            var page_url = this.url_asset_policies;
            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.policies),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.data.success == 1) {

                            this.asset_policies.push({ ...data.data.data });
                            this.showMessage('Thông báo', 'Lưu thành công');
                            $("#addpolicyAsset").modal("hide");

                        } else {
                            this.errors = data.data.errors;
                            this.showError('Thông báo', this.errors.name[0]);
                            this.showError('Thông báo', this.errors.type[0]);
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.policies.id, {
                    method: "PUT",
                    body: JSON.stringify(this.policies),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);

                        if (data.data.success == '1') {
                            let index = this.policies.index;
                            this.policies = { ...data.data.data };
                            this.policies.index = index;
                            console.log("index=" + this.policies.index);
                            //this.asset_policies[this.policies.index] = { ...this.policies };

                            this.$set(this.asset_policies, this.policies.index, { ...this.policies });
                            this.showMessage('Thông báo', 'Cập nhật thành công');
                            $("#addpolicyAsset").modal("hide");
                            this.reset();
                        } else {
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        EditPolicies(policies) {
            let index = this.asset_policies.findIndex(i => {
                return i.id == policies.id;
            });
            this.clearAllError();
            this.edit = true;
            this.policies.id = policies.id;
            this.policies.name = policies.name;
            //this.policies.attachment_file = policies.attachment_file;
            this.policies.attachment_file = [...policies.attachment_file];
            if (this.policies.attachment_file == null) {
                this.policies.attachment_file = [];
            }
            this.policies.attachment_file_del = [];
            this.policies.type = policies.type;
            this.policies.create_by = policies.create_by;
            this.policies.policy_conditions = policies.policy_conditions;
            this.policies.index = index;
            console.log("this.policies.index = " + this.policies.index);
            $("#addpolicyAsset").modal("show");
        },
        DeletePolicies(id) {
            if (confirm('Bạn muốn xoá?')) {
                fetch(`${this.url_asset_policies}/${id}`, {
                    method: 'delete',
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.showMessage('Thông báo', 'Xoá thành công');
                        this.fetchPolicies();
                    });
            }
        },
        clearAllError() {
            this.errors = {};
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.warehouses) {
                this.warehouses[field] = null;
            }
            for (let field in this.cats) {
                this.cats[field] = null;
            }
            for (let field in this.groups) {
                this.groups[field] = null;
            }
            for (let field in this.fields) {
                this.fields[field] = null;
            }
            this.edit = false;
            this.policies.id = "";
            this.policies.name = "";
            this.policies.attachment_file = [];
            this.policies.attachment_file_del = [];
            this.policies.type = "";
            this.policies.create_by = "";
            this.policies.policy_conditions = "";
            this.policies.index = -1;
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
        view() {
            this.reset();
            $("#addAsset").modal("show");

        },
        viewgroup() {
            this.reset();
            $("#addgroupAsset").modal("show");
        },
        viewpolicy() {
            this.reset();
            $("#addpolicyAsset").modal("show");

        },
        viewcat() {
            this.reset();
            $("#addcatasset").modal("show");
        },
        viewtype() {
            this.reset();
            $("#addfields").modal("show");
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
        handleHover(hovered) {
            this.isHovered = hovered
        },
        handleHovers(hovereds) {
            this.isHovereds = hovereds
        },
        handleClickInputFile() {

            this.$refs.file.click();
        },
        emitEvent(event) {

            this.policies.attachment_file = [];
            for (let index = 0; index < event.target.files.length; index++) {
                const file = event.target.files[index];
                //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = () => {
                    // console.log(event.target.files[0]);
                    const docs = {
                        name: file.name,
                        size: file.size,
                        ext: file.type.split("/").pop(),
                        lastModifiedDate: file.lastModifiedDate,
                        base64: reader.result
                    };
                    console.log(docs);

                    this.policies.attachment_file.push({ ...docs });

                };
            }

            event.target.value = "";
        },
        deleteFile(item, index) {

            if (confirm('Bạn muốn xoá file?')) {

                this.policies.attachment_file_del = this.policies.attachment_file_del || [];
                this.policies.attachment_file_del.push({ ...item });
                this.policies.attachment_file.splice(index, 1);
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
                    // For other browsers:
                    // Create a link pointing to the ObjectURL containing the blob.
                    const data = URL.createObjectURL(newBlob);
                    var link = document.createElement('a');
                    link.href = data;
                    link.download = filename;
                    link.click();

                    setTimeout(function () {
                        // For Firefox it is necessary to delay revoking the ObjectURL
                        URL.revokeObjectURL(data)
                    }, 100);
                }).catch(err => console.log(err));

        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            console.log(files);
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
            //file.target.value = "";
        },
        backsspace(e) {

            if (e.target.value === '') {
                this.form_filter.name = '';
                this.fetchWarehouse();
            }
            this.$emit('delete-or-backspace-key-pressed')

        },
        filter_datas() {
            if (this.form_filter.name == '' || this.form_filter.name !== '') {
                this.fetchWarehouse();
            }
        },
        filter_groups() {
            this.fetchGroups();
        },
        filter_policies() {
            this.fetchPolicies();
        },
        filter_cats() {
            this.fetchCats();
        },
        filter_field() {
            this.fetchField();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }
        },
        reloadPage() {
            window.location.reload();
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();

        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        // rows() {
        //     return this.departments.length;
        // },
    },
}

</script>
<style lang="scss" scoped>
.nav-tabs .nav-link {
    margin-bottom: 0px;
    border: 0px solid transparent !important;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

li>a#smokee___BV_tab_button__ {
    background: blue;
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

.sha {
    box-shadow: 1px 1px 10px lightgrey;
}

.tess {
    color: gray;
}

.mytabs {
    background: white;
}

#smoke {
    background: whitesmoke;
}

a#smoke___BV_tab_button__:active {
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
        color: rgb(173, 72, 25);
    }
}
</style>