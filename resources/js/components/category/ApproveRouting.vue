<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark">{{ $t(title) }}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                            <button class="btn btn-info btn-sm" @click="showApproveRouting()">
                                <i class="fa fa-plus"></i> {{ $t("form.create") }}
                            </button>
                            <button class="btn btn-info btn-sm" @click="showApproveRoutingMultiple()">
                                <i class="fa fa-plus"></i> {{ $t("form.create") }} hàng loạt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-0">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    @click="showSearch()">
                                                    <span v-if="!show_search">Hiện tìm kiếm</span>
                                                    <span v-if="show_search">Ẩn tìm kiếm</span>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    @click="showSearch()">
                                                    <i v-if="show_search" class="fas fa-angle-up"></i>
                                                    <i v-else class="fas fa-angle-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row"></div>
                                    </div>
                                </div>
                                <div class="row" v-if="show_search"
                                    style="border: 1px solid #e5e5e5; border-radius: 5px">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                title="Group" for="">Group</label>
                                            <div class="col-md-5">
                                                <treeselect placeholder="Tất cả"
                                                    :value-consists-of="'LEAF_PRIORITY'"
                                                    :multiple="true" v-model="form_filter.group_id"
                                                    :options="tree_groups" />
                                            </div>

                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="">Loại CT</label>
                                            <div class="col-md-5">
                                                <treeselect placeholder="Tất cả" :disabled="edit"
                                                    :disable-branch-nodes="true" v-model="form_filter.document_type_id"
                                                    :options="tree_documentTypes" :multiple="true" required />
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="">Trạng thái</label>
                                            <div class="col-md-4">
                                                <treeselect placeholder="Tất cả" :disabled="edit"
                                                    :disable-branch-nodes="true" v-model="form_filter.active"
                                                    :options="status_option" :multiple="true" required />
                                            </div>
                                             <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="">Loại hình</label>
                                            <div class="col-md-3">
                                                <treeselect placeholder="Tất cả" :disabled="edit"
                                                    :disable-branch-nodes="true" v-model="form_filter.payment_type_id"
                                                    :options="tree_paymentTypes" :multiple="true" required />
                                            </div>
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="approved_limit_code">Hạn mức</label>
                                            <div class="col-md-2">
                                                <treeselect placeholder="Tất cả" :disabled="edit"
                                                    :disable-branch-nodes="true" v-model="form_filter.approved_limit_code"
                                                    :options="tree_limitTypes" :multiple="true" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="id_bophan">User
                                            </label>
                                            <div class="col-md-4">
                                                <treeselect placeholder="Tất cả" v-model="form_filter.user_id"
                                                    :disable-branch-nodes="true" :multiple="true" :options="tree_users"></treeselect>
                                            </div>
                                            <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                for="id_bophan">User CC
                                            </label>
                                            <div class="col-md-4">
                                                <treeselect placeholder="Tất cả" v-model="form_filter.usercc_id"
                                                    :disable-branch-nodes="true" :multiple="true" :options="tree_users"></treeselect>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-warning btn-sm mt-1"
                                                    @click="filter_data()">
                                                    <i class="fa fa-search"></i> Tìm
                                                </button>
                                                <button type="reset" class="btn btn-secondary btn-sm mt-1"
                                                    @click="clearFilter()">
                                                    <i class="fa fa-reset"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1" style="background-color: #f4f6f9">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <button type="button" class="btn btn-primary btn-sm ml-3 mt-1"
                                                @click="showReplaceUserTeam()" title="Thay thế người duyệt từ A->B">
                                                <i class="fas fa-people-arrows"> Thay thế người team duyệt</i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm ml-3 mt-1"
                                                @click="showRemoveUserTeam()" title="Xóa user trong Team">
                                                <i class="fas fa-user-minus"> Xóa người team duyệt</i>
                                            </button>
                                        </div>
                                        <div class="form-group row">
                                            <button type="button" class="btn btn-warning btn-sm ml-3 mt-1"
                                                @click="showAddUserccTeam()" title="Thêm thành viên UserCC">
                                                <i class="fas fa-user-plus"> Thêm UserCC</i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm ml-3 mt-1"
                                                @click="showReplaceUserccTeam()" title="Thay đổi thành viên UserCC">
                                                <i class="fas fa-user-friends"> Cập nhật UserCC</i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm ml-3 mt-1"
                                                @click="showRemoveUserccTeam()" title="Loại bỏ thành viên UserCC">
                                                <i class="fas fa-user-minus"> Xóa UserCC</i>
                                            </button>
                                        </div>
                                        <div class="form-group row">
                                            <button type="button" class="btn btn-primary btn-sm ml-3 mt-1"
                                                @click="showTurnOnReviewTeam()" title="Bật chế độ Review cho User">
                                                <i class="fas fa-user-friends"> Bật Review</i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm ml-3 mt-1"
                                                @click="showTurnOffReviewTeam()" title="Tắt chế độ Review cho User">
                                                <i class="fas fa-user-minus"> Tắt Review</i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row mt-1">
                                            <div class="input-group input-group-sm col-12">
                                                <!-- <input type="search" class="form-control form-control-navbar" placeholder="Search" aria-label="Search"> -->
                                                <input class="form-control form-control-navbar mb-1" id="search"
                                                    type="search" v-model="filter"
                                                    placeholder="Nhập thông tin tìm kiếm.." aria-label="Search" />
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-default btn-flat mb-1">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <!-- <button type="button" @click="searchContact()" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button> -->
                                                </span>
                                                <!-- <download-excel
                                    :data   = "contracts" title="Eport Excel"  class="ml-2" >
                                    <span style="cursor: pointer;"><i class="fa fa-download"></i></span>
                                </download-excel> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <b-table striped hover responsive :bordered="true" head-variant="light"
                                        :sticky-header="false" small :items="approveRoutings"
                                        :current-page="current_page" :per-page="per_page" :filter="filter"
                                        :fields="fields" selectable ref="selectableTable">
                                        <template #head(selected)>
                                            <!-- {{selected}} -->
                                            <b-form-checkbox class="ml-1" v-model="selectAll" id="chkAll"
                                                @change="select"></b-form-checkbox>
                                        </template>

                                        <template v-slot:cell(selected)="data">
                                            <b-form-checkbox class="ml-1" :value="data.item.id" v-model="selected">
                                            </b-form-checkbox>
                                        </template>
                                        <template #cell(document_type_id)="data">
                                            <span>{{ document_type_name(data.value) }}</span>
                                        </template>
                                        <template #cell(payment_type_id)="data">
                                            <span>{{ payment_type_name(data.value) }}</span>
                                        </template>
                                        <template #cell(budget_type)="data">
                                            <span>{{ budget_type_name(data.item.approved_limit_code) }}</span>
                                        </template>
                                        <template #cell(currency)="data">
                                            <span>{{ currency_name(data.item.approved_limit_code) }}</span>
                                        </template>
                                        <template #cell(approved_limit_code)="data">
                                            <span>{{ limit_name(data.value) }}</span>
                                        </template>
                                        <template #cell(group_id)="data">
                                            <span>{{ group_name(data.value) }}</span>
                                        </template>
                                        <template #cell(team_id)="data">
                                            <span v-if="data.item.team" class="mr-3">
                                                <a v-bind:href="assign_user_into_team(data.item.team.id)">{{
                                                        data.item.team.name
                                                }}
                                                    <!-- <i  v-if="data.item.team.users_count>0" class="fas fa-users-cog "></i> -->
                                                    <span v-if="data.item.team.users_count > 0"
                                                        class="badge badge-danger navbar-badge float-right">{{
                                                                data.item.team.users_count
                                                        }}</span>
                                                </a>
                                            </span>
                                        </template>
                                        <template #cell(user_cc)="data">
                                            <span v-if="data.item.team" class="mr-3">
                                                <a v-bind:href="assign_usercc_into_team(data.item.team.id)">user cc

                                                    <!-- <i  v-if="data.item.team.userccs_count>0" class="fas fa-users-cog"></i> -->
                                                    <span v-if="data.item.team.userccs_count > 0"
                                                        class="badge badge-danger navbar-badge float-right">{{
                                                                data.item.team.userccs_count
                                                        }}</span>
                                                </a>
                                            </span>
                                        </template>
                                        <template #cell(active)="data">
                                            <span class="badge bg-success" v-if="data.item.active == 1">{{
                                                    $t("form.active")
                                            }}</span>
                                            <span class="badge bg-warning" v-if="data.item.active == 0">{{
                                                    $t("form.inactive")
                                            }}</span>
                                        </template>

                                        <template #cell(action)="data">
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <button @click="editGroup(data.item)" class="btn btn-xs mr-3"
                                                    title="Edit">
                                                    <i class="fa fa-edit text-green bigger-120"></i>
                                                </button>

                                                <button @click="deleteGroup(data.item.id)" class="btn btn-xs"
                                                    title="Delete">
                                                    <i class="ace-icon text-red fa fa-trash bigger-120"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </b-table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-md-4" style="text-align: left"
                                                    for="">Per page:</label>
                                                <div class="col-md-3">
                                                    <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                                    </b-form-select>
                                                </div>
                                                <label class="col-form-label-sm col-md-1" style="text-align: left"
                                                    for=""></label>
                                                <div class="col-md-3">
                                                    <b-pagination v-model="current_page" :total-rows="rows"
                                                        :per-page="per_page" align="fill" size="sm" class="my-0">
                                                    </b-pagination>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Loading (remove the following to stop the loading)-->

                            <div class="center overlay" v-if="loading">
                                <i class="fa fa-spinner fa-spin fa-4x fa-fw gray center"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                            <!-- end loading -->
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="showRemoveUserTeam" tabindex="-2" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="removeUserTeam">
                            <div class="modal-header">
                                <h4 class="modal-title">Xóa User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_user_team.old_user_id" :disable-branch-nodes="true"
                                            :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="showReplaceUserTeam" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="replaceUserTeam">
                            <div class="modal-header">
                                <h4 class="modal-title">Thay thế User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <div class="col-md-8 ml-2">
                                        <p style="font-size: 9pt">
                                            <i class="fas fa-info mr-2"></i><i>Thay thế user A thành user B trong team
                                                duyệt. Chú ý là sẽ giữ
                                                lại 1 user B duy nhất.</i>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User hiện tại <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_user_team.old_user_id" :disable-branch-nodes="true"
                                            :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User mới <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_user_team.new_user_id" :disable-branch-nodes="true"
                                            :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showAddUserccTeam" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchaddUserccTeam">
                            <div class="modal-header">
                                <h4 class="modal-title">Thêm User vào UserCC</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <div class="col-md-8 ml-2">
                                        <p style="font-size: 9pt">
                                            <i class="fas fa-info mr-2"></i><i>Thêm user vào danh sách UserCC các cây
                                                duyệt được chọn.</i>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User được thêm: <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_usercc_team.new_usercc_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showReplaceUserccTeam" tabindex="-2" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchreplaceUserccTeam">
                            <div class="modal-header">
                                <h4 class="modal-title">Thay thế User trong nhóm CC</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <div class="col-md-8 ml-2">
                                        <p style="font-size: 9pt">
                                            <i class="fas fa-info mr-2"></i><i>Thay thế user cc trong các team được
                                                chọn.</i>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User cũ <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_usercc_team.old_usercc_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User mới <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_usercc_team.new_usercc_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showRemoveUserccTeam" tabindex="-3" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchremoveUserccTeam">
                            <div class="modal-header">
                                <h4 class="modal-title">Xóa User khỏi nhóm CC</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_usercc_team.old_usercc_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="AddApproveRouting" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="AddApproveRouting" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t("form.add") }} Routing</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group row">
                                    <label for="group_id" class="col-form-label-sm col-4">Group<small
                                            class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <treeselect id="group_id" :disabled="edit" :disable-branch-nodes="true"
                                            v-model="approveRouting.group_id"
                                            v-bind:class="hasError('group_id') ? 'is-invalid' : ''"
                                            :options="tree_groups" required />
                                        <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("group_id") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="document_type_id" class="col-form-label-sm col-4 mt-1">Loại chứng
                                        từ<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select required id="document_type_id" :disabled="edit"
                                            class="form-control form-control-sm"
                                            v-model="approveRouting.document_type_id"
                                            v-bind:class="hasError('document_type_id') ? 'is-invalid' : ''">
                                            <option v-for="documentType in documentTypes" v-bind:key="documentType.id"
                                                v-bind:value="documentType.id">
                                                {{ documentType.name }}
                                            </option>
                                            <option value=""></option>
                                        </select>
                                    </div>

                                    <span v-if="hasError('document_type_id')" class="invalid-feedback" role="alert">
                                        <strong>{{ getError("document_type_id") }}</strong>
                                    </span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left"
                                        for="budget_type">Loại ngân sách<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select name="budget_type" :disabled="edit" id="budget_type"
                                            v-model="approveRouting.budget_type" @click="clearError($event)"
                                            v-bind:class="hasError('budget_type') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm">
                                            <option v-for="bud in budget_types" :key="bud.id" :value="bud.id">
                                                {{ bud.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('budget_type')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("budget_type") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left"
                                        for="payment_type_id">Loại thanh toán<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select name="payment_type_id" :disabled="edit" id="payment_type_id"
                                            v-model="approveRouting.payment_type_id" @click="clearError($event)"
                                            v-bind:class="hasError('payment_type_id') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm mt-1">
                                            <option v-for="pay in paymentTypes" :key="pay.id" :value="pay.id">
                                                {{ pay.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('payment_type_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("payment_type_id") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left" for="currency">Loại
                                        tiền<small class="text-red">(*)</small> &nbsp; &nbsp; &nbsp;
                                        &nbsp; &nbsp;
                                    </label>
                                    <div class="col-md-8">
                                        <select name="currency" :disabled="edit" id="currency"
                                            v-model="approveRouting.currency" @click="clearError($event)"
                                            v-bind:class="hasError('currency') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm mt-1">
                                            <option v-for="cur in currencies" :key="cur.id" :value="cur.id">
                                                {{ cur.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("currency") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="approved_limit_code" class="col-form-label-sm col-4">Hạn mức<small
                                            class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select id="approved_limit_code" :disabled="edit"
                                            class="form-control form-control-sm"
                                            v-model="approveRouting.approved_limit_code"
                                            v-bind:class="hasError('approved_limit_code') ? 'is-invalid' : ''">
                                            <option v-for="approveLimit in approveLimit_filter_form"
                                                v-bind:key="approveLimit.code" v-bind:value="approveLimit.code">
                                                {{ approveLimit.name }}
                                            </option>
                                            <option value=""></option>
                                        </select>
                                        <span v-if="hasError('approved_limit_code')" class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ getError("approved_limit_code") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                  <label for="team_id"  class="col-form-label-sm col-4"
                                    >Team duyệt<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                     <treeselect placeholder ="All"     v-model="approveRouting.team_id" :disable-branch-nodes="true" :options="teams_cbo" v-bind:class="hasError('team_id')?'is-invalid':''"></treeselect>

                                        <span v-if="hasError('team_id')" class="invalid-feedback" role="alert">
                                        <strong>{{getError('team_id')}}</strong>
                                        </span>
                                    </div>

                             </div>   -->

                                <div class="form-group row">
                                    <label for="description" class="col-form-label-sm col-4">{{ $t("form.description")
                                    }}<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <textarea v-model="approveRouting.description" type="text"
                                            class="form-control form-control-sm"
                                            v-bind:class="hasError('description') ? 'is-invalid' : ''" id="description"
                                            name="description" placeholder="" required />
                                        <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("description") }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="active" class="col-form-label-sm col-4">{{ $t("form.active") }}<small
                                            class="text-red">(*)</small></label>
                                    <div class="col-md-8 mt-1">
                                        <select class="form-control form-control-sm" v-model="approveRouting.active"
                                            name="active" id="active">
                                            <option value="1" selected>{{ $t("form.active") }}</option>
                                            <option value="0">{{ $t("form.inactive") }}</option>
                                        </select>

                                        <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("level") }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showTurnOnReviewTeam" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchturnOnUserReview">
                            <div class="modal-header">
                                <h4 class="modal-title">Bật Review cho User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <div class="col-md-8 ml-2">
                                        <p style="font-size: 9pt">
                                            <i class="fas fa-info mr-2"></i><i>Bật chế độ review cho user các cây duyệt
                                                được chọn.</i>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User bật Review: <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_userreview_team.turnonreview_user_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showTurnOffReviewTeam" tabindex="-2" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchturnOffUserReview">
                            <div class="modal-header">
                                <h4 class="modal-title">Tắt Review cho User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="moda-body">
                                <div class="form-group-row">
                                    <label for="" class="col-form-label-sm col-4">
                                        User tắt Review: <small class="text-red">(*)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <treeselect v-model="process_userreview_team.turnoffreview_user_id"
                                            :disable-branch-nodes="true" :options="tree_users" required></treeselect>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="AddApproveRoutingMultiple" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form @submit.prevent="batchAddRoutingMultiple" @keydown="clearError">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t("form.add") }} hàng loạt Routing</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group row">
                                    <label for="group_ids" class="col-form-label-sm col-4">Group<small
                                            class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <treeselect id="group_ids" :disabled="edit" :value-consists-of="'LEAF_PRIORITY'"
                                            v-model="addMultiApproveRoutings.group_ids"
                                            v-bind:class="hasError('group_ids') ? 'is-invalid' : ''"
                                            :options="tree_groups" :multiple="true" required />
                                        <span v-if="hasError('group_ids')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("group_ids") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="document_type_ids" class="col-form-label-sm col-4 mt-1">Loại chứng
                                        từ<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <treeselect id="document_type_ids" :disabled="edit" :disable-branch-nodes="true"
                                            v-model="addMultiApproveRoutings.document_type_ids"
                                            v-bind:class="hasError('document_type_ids') ? 'is-invalid' : ''"
                                            :options="tree_documentTypes" :multiple="true" required />
                                        <span v-if="hasError('document_type_ids')" class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ getError("document_type_ids") }}</strong>
                                        </span>
                                    </div>


                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left"
                                        for="budget_type">Loại ngân sách<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select name="budget_type" :disabled="edit" id="budget_type"
                                            v-model="addMultiApproveRoutings.budget_type" @click="clearError($event)"
                                            v-bind:class="hasError('budget_type') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm">
                                            <option v-for="bud in budget_types" :key="bud.id" :value="bud.id">
                                                {{ bud.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('budget_type')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("budget_type") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left"
                                        for="payment_type_id">Loại thanh toán<small class="text-red">(*)</small></label>
                                    <div class="col-md-8">
                                        <select name="payment_type_id" :disabled="edit" id="payment_type_id"
                                            v-model="addMultiApproveRoutings.payment_type_id"
                                            @click="clearError($event)"
                                            v-bind:class="hasError('payment_type_id') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm mt-1">
                                            <option v-for="pay in paymentTypes" :key="pay.id" :value="pay.id">
                                                {{ pay.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('payment_type_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("payment_type_id") }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label-sm col-4" style="text-align: left" for="currency">Loại
                                        tiền<small class="text-red">(*)</small> &nbsp; &nbsp; &nbsp;
                                        &nbsp; &nbsp;
                                    </label>
                                    <div class="col-md-8">
                                        <select name="currency" :disabled="edit" id="currency"
                                            v-model="addMultiApproveRoutings.currency" @click="clearError($event)"
                                            v-bind:class="hasError('currency') ? 'is-invalid' : ''"
                                            class="form-control form-control-sm mt-1">
                                            <option v-for="cur in currencies" :key="cur.id" :value="cur.id">
                                                {{ cur.name }}
                                            </option>
                                        </select>
                                        <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("currency") }}</strong>
                                        </span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="active" class="col-form-label-sm col-4">{{ $t("form.active") }}<small
                                            class="text-red">(*)</small></label>
                                    <div class="col-md-8 mt-1">
                                        <select class="form-control form-control-sm"
                                            v-model="addMultiApproveRoutings.active" name="active" id="active">
                                            <option value="1" selected>{{ $t("form.active") }}</option>
                                            <option value="0">{{ $t("form.inactive") }}</option>
                                        </select>

                                        <span v-if="hasError('level')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError("level") }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t("form.back") }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
// import Pagination from "../shared/Pagination.vue";

export default {
    props: {
        title: "",
    },
    components: {
        Treeselect,
    },
    data() {
        return {
            tree_limitTypes: [],
            approveRoutings: [],
            pagesNumber: [],

            departments: [],
            groups: [],
            tree_groups: [],
            approveLimits: [],
            currencies: [],
            budget_types: [],
            tree_budgetTypes: [],
            paymentTypes: [],
            tree_paymentTypes: [],
            teams: [],
            documentTypes: [],
            tree_documentTypes: [],
            addMultiApproveRoutings: {
                group_ids: [],
                document_type_ids: [],
                payment_type_id: "",
                currency: "VND",
                budget_type: "",
                active: 1
            },
            approveRouting: {
                document_type_id: "",
                payment_type_id: "",
                approved_limit_code: "",
                group_id: "",
                currency: "",
                budget_type: "-1",
                team_id: "",
                description: "",
                active: "",
                index: "",
            },
            process_user_team: {
                team_ids: "",
                old_user_id: null,
                new_user_id: null,
            },
            process_usercc_team: {
                team_ids: "",
                old_usercc_id: null,
                new_usercc_id: null,
            },
            process_userreview_team: {
                team_ids: "",
                turnonreview_user_id: null,
                turnoffreview_user_id: null,
            },
            companies: [],
            total: 0,
            per_page: 10,
            from: 1,
            to: 0,
            current_page: 1,
            filter: "",
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],

            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_approvelimit: "api/category/approvelimit",
            page_url_document_type: "/api/category/documenttypes",
            page_url_group: "/api/category/groups",
            page_url_team: "/api/category/teams",
            page_url_approve_routing: "/api/category/approveroutings",
            page_url_add_approve_routing: "/api/category/batchadd_approveroutings",
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            page_url_currency: "/api/category/currencies",
            page_url_budget_type: "/api/category/budgettypes",
            page_url_paymentType: "/api/category/paymenttypes",
            page_url_replace_user: "/api/category/replace_user_team",
            page_url_remove_user: "/api/category/remove_user_team",
            page_url_add_usercc: "/api/category/batchadd_usercc_team",
            page_url_replace_usercc: "/api/category/batchreplace_usercc_team",
            page_url_remove_usercc: "/api/category/batchremove_usercc_team",
            page_url_review_user: "/api/category/batchreview_user_team",
            page_url_users: "api/user/all",
            show_search: false,
            form_filter: {
                group_id: null,
                document_type_id: null,
                payment_type_id: null,
                active: null,
                approved_limit_code: null,
                team_id: null,
                user_id: null,
                usercc_id: null,
            },
            status_option: [
                { id: 1, label: this.$t("Đang hoạt động") },
                { id: 0, label: this.$t("Không hoạt động") }

            ],
            fields: [
                {
                    key: "selected",
                    label: "All",
                    stickyColumn: true,
                },
                {
                    key: "document_type_id",
                    label: "Loại chứng từ",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "budget_type",
                    label: "Loại ngân sách",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "payment_type_id",
                    label: "Loại thanh toán",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "currency",
                    label: "currency",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "approved_limit_code",
                    label: "Hạn mức",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "group_id",
                    label: "Group",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "team_id",
                    label: "Team duyệt",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "user_cc",
                    label: "User cc",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "description",
                    label: "Mô tả",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
                {
                    key: "active",
                    label: "Trạng thái",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },

                {
                    key: "action",
                    label: "action",
                    sortable: true,
                    stickyColumn: true,
                    class: "text-nowrap",
                },
            ],
            selected: [],
            selectAll: false,
            tree_users: [],
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.getUserTree();
        this.fetCurrency();
        this.fetBudgetType();
        this.fetCompany();
        this.fetDepartment();
        this.fetDocumentType();
        this.getPaymentType();
        this.fetGroup();
        this.fetGroup_Tree();
        this.fetApproveLimit();
        this.fetTeam();
        this.fetchApproveRouting();
    },
    mounted() {
        // this.fetchApproveRouting(this.pagination.current_page);
    },

    methods: {
        checkApproveLimitCodeAll(a) {
            console.log(a);
        },
        getUserTree() {
            var page_url = this.page_url_users + "?type=tree_combobox";
            console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.tree_users = data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        showRemoveUserTeam() {
            $("#showRemoveUserTeam").modal("show");
        },
        showReplaceUserTeam() {
            $("#showReplaceUserTeam").modal("show");
        },
        showAddUserccTeam() {
            $("#showAddUserccTeam").modal("show");
        },
        showReplaceUserccTeam() {
            $("#showReplaceUserccTeam").modal("show");
        },
        showRemoveUserccTeam() {
            $("#showRemoveUserccTeam").modal("show");
        },
        showTurnOnReviewTeam() {
            $("#showTurnOnReviewTeam").modal("show");
        },
        showTurnOffReviewTeam() {
            $("#showTurnOffReviewTeam").modal("show");
        },
        replaceUserTeam() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn thay đổi?")) {
                this.loading = true;
                var page_url = this.page_url_replace_user;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        old_user_id: this.process_user_team.old_user_id,
                        new_user_id: this.process_user_team.new_user_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_user_team.old_user_id = null;
                            this.process_user_team.new_user_id = null;
                            $("#showReplaceUserTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        removeUserTeam() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn xóa user?")) {
                this.loading = true;
                var page_url = this.page_url_remove_user;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        user_id: this.process_user_team.old_user_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_user_team.old_user_id = null;
                            this.process_user_team.new_user_id = null;
                            $("#showRemoveUserTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        batchreplaceUserccTeam() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn thay đổi?")) {
                this.loading = true;
                var page_url = this.page_url_replace_usercc; 
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        old_usercc_id: this.process_usercc_team.old_usercc_id,
                        new_usercc_id: this.process_usercc_team.new_usercc_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_usercc_team.old_usercc_id = null;
                            this.process_usercc_team.new_usercc_id = null;
                            $("#showReplaceUserccTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        batchaddUserccTeam() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn thêm mới?")) {
                this.loading = true;
                var page_url = this.page_url_add_usercc; 
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        new_usercc_id: this.process_usercc_team.new_usercc_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_usercc_team.old_usercc_id = null;
                            this.process_usercc_team.new_usercc_id = null;
                            $("#showAddUserccTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        batchremoveUserccTeam() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn loại bỏ user?")) {
                this.loading = true;
                var page_url = this.page_url_remove_usercc;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        old_usercc_id: this.process_usercc_team.old_usercc_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_usercc_team.old_usercc_id = null;
                            this.process_usercc_team.new_usercc_id = null;
                            $("#showRemoveUserccTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        batchturnOnUserReview() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn bật review user?")) {
                this.loading = true;
                var page_url = this.page_url_review_user;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        turnon: 1,
                        review_user_id: this.process_userreview_team.turnonreview_user_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_userreview_team.turnonreview_user_id = null;
                            this.process_userreview_team.turnoffreview_user_id = null;
                            $("#showTurnOnReviewTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        batchturnOffUserReview() {
            if (this.selected.length < 1) {
                toastr.info(this.$t("Vui lòng chọn 1 dòng dữ liệu"), "");
                return;
            }

            if (confirm("Bạn muốn tắt review user?")) {
                this.loading = true;
                var page_url = this.page_url_review_user;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        routing_ids: this.selected.toString(),
                        turnon: 0,
                        review_user_id: this.process_userreview_team.turnoffreview_user_id,
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            this.selected = [];
                            this.process_userreview_team.turnonreview_user_id = null;
                            this.process_userreview_team.turnoffreview_user_id = null;
                            $("#showTurnOffReviewTeam").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        fetCurrency() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_currency;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.currencies = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetBudgetType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_budget_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.budget_types = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetDocumentType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_document_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.documentTypes = res.data;

                    this.tree_documentTypes = [];
                    this.documentTypes.forEach(document => {
                        let item = [];
                        item['label'] = document.name;
                        item['id'] = document.id;
                        this.tree_documentTypes.push(item);
                    });
                })
                .catch((err) => console.log(err));
        },
        getPaymentType() {
            var page_url = this.page_url_paymentType;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("paymentTypesXin chao");
                    this.paymentTypes = res.data;

                    this.tree_paymentTypes = [];
                    this.paymentTypes.forEach(payment => {
                        let item = [];
                        item['label'] = payment.name;
                        item['id'] = payment.id;
                        this.tree_paymentTypes.push(item);
                    });
                })
                .catch((err) => console.log(err));
        },
        fetGroup() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_group;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.groups = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetGroup_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_group + "?type=tree_combobox";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.tree_groups = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetApproveLimit() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_approvelimit;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.approveLimits = res.data;

                    let existedLimits=  [];
                    this.tree_limitTypes = [];
                    this.approveLimits.forEach(limit => {
                        if (!existedLimits.includes(limit.name)) {
                            let item = [];
                            item['label'] = limit.name;
                            item['id'] = limit.name;
                            this.tree_limitTypes.push(item);
                            existedLimits.push(limit.name);
                        }
                    });

                    this.tree_limitTypes.sort();
                })
                .catch((err) => console.log(err));
        },
        fetTeam() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_team;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.teams = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.companies = res.data;
                })
                .catch((err) => console.log(err));
        },
        fetDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    //console.log("Xin chao");
                    this.departments = res.data.data;
                })
                .catch((err) => console.log(err));
        },

        fetchApproveRouting() {
            //$("#tbbody_id").html('');

            this.loading = true;

            this.approveRoutings = Array();
            const params = new URLSearchParams({
                group_id: this.form_filter.group_id,
                document_type_id: this.form_filter.document_type_id,
                payment_type_id: this.form_filter.payment_type_id,
                active: this.form_filter.active,
                approved_limit_code: this.form_filter.approved_limit_code,
                team_id: this.form_filter.team_id,
                user_id: this.form_filter.user_id,
                usercc_id: this.form_filter.usercc_id,
            });
            var page_url = this.page_url_approve_routing + "?" + params.toString();

            let vm = this;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((res) => {
                    this.approveRoutings = res.data;

                    // this.pagination = res.data;
                    this.loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.loading = false;
                });
            this.edit = false;
        },

        showApproveRouting() {
            this.reset();
            this.approveRouting.active = 1;

            $("#AddApproveRouting").modal("show");
        },
        showApproveRoutingMultiple() {
            this.reset();
            //this.approveRouting.active = 1;

            $("#AddApproveRoutingMultiple").modal("show");
        },
        batchAddRoutingMultiple() {
            var page_url = this.page_url_add_approve_routing; // "/api/category/approveRoutings";

            fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.addMultiApproveRoutings),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (!data.data.errors) {
                        this.reset();
                        let totalCreateNew = data.data.totalCreateNew;
                        let totalExisted = data.data.totalExisted;
                        //this.approveRoutings.push(data.data);
                        this.showMessage("Thông báo", "Tạo mới thành công " + totalCreateNew + " routings, đã tồn tại " + totalExisted + " routings.");
                        $("#AddApproveRoutingMultiple").modal("hide");
                    } else {
                        if (data.data.message != "") {
                            toastr.warning(data.data.message, "Thông báo");
                        }
                        this.errors = data.data.errors;
                    }
                })
                .catch((err) => console.log(err));
        },
        AddApproveRouting() {
            var page_url = this.page_url_approve_routing; // "/api/category/approveRoutings";

            if (this.edit === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.approveRouting),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.data.errors) {
                            this.reset();
                            this.approveRoutings.push(data.data);
                            this.showMessage("Thông báo", "Lưu thành công");
                            $("#AddApproveRouting").modal("hide");
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                    })
                    .catch((err) => console.log(err));
            } else {
                //update
                fetch(page_url + "/" + this.approveRouting.id, {
                    method: "PUT",
                    body: JSON.stringify(this.approveRouting),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (!data.errors) {
                            this.approveRouting.team = data.data.team;
                            //  this.approveRoutings.push(data.data);
                            // this.approveRoutings[this.approveRouting.index]=this.approveRouting;
                            this.$set(this.approveRoutings, this.approveRouting.index, {
                                ...this.approveRouting,
                            });
                            this.showMessage("Thông báo", "Cập nhật thành công");
                            $("#AddApproveRouting").modal("hide");
                            this.reset();
                        } else {
                            if (data.data.message != "") {
                                toastr.warning(data.data.message, "Thông báo");
                            }
                            this.errors = data.data.errors;
                        }
                    })
                    .catch((err) => console.log(err));
            }
        },
        editGroup(approveRouting) {
            let index = this.approveRoutings.findIndex((i) => {
                return i.id == approveRouting.id;
            });
            // var approveRouting={..._obj};
            this.edit = true;
            this.approveRouting.id = approveRouting.id;
            this.approveRouting.active = approveRouting.active;
            this.approveRouting.description = approveRouting.description;
            this.approveRouting.document_type_id = approveRouting.document_type_id;
            this.approveRouting.payment_type_id = approveRouting.payment_type_id;
            this.approveRouting.approved_limit_code = approveRouting.approved_limit_code;
            this.approveRouting.team_id = approveRouting.team_id;
            this.approveRouting.index = index;
            //get info
            let approveLimit = this.approveLimits.find(
                (x) => x.code == approveRouting.approved_limit_code
            );
            this.approveRouting.group_id = approveRouting.group_id;
            //console.log(approveLimit);
            this.approveRouting.currency = approveLimit.currency;
            this.approveRouting.budget_type = approveLimit.budget_type;

            $("#AddApproveRouting").modal("show");
        },
        deleteGroup(id) {
            if (confirm("Bạn muốn xoá?")) {
                fetch(`${this.page_url_approve_routing}/${id}`, {
                    method: "delete",
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        this.showMessage("Thông báo", "Xoá thành công");
                        this.fetchApproveRouting();
                    });
            }
        },
        assign_user_into_team(id) {
            return "/category/team?type=assign&id=" + id;
        },
        assign_usercc_into_team(id) {
            return "/category/team?type=assigncc&id=" + id;
        },
        assignUserShow(approveRouting) {
            return "/category/approveRouting?type=assign&id=" + approveRouting.id;
        },

        select() {
            this.selected = [];
            if (this.selectAll) {
                for (let i in this.approveRoutings) {
                    this.selected.push(this.approveRoutings[i].id);
                }
            }
        },
        filter_data() {
            this.fetchApproveRouting();
        },
        showSearch() {
            this.show_search = !this.show_search;
            //this.clearFilter();
        },
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }

            // this.contract_filter =this.contracts;
        },
        company_name(company_id) {
            var obj = this.companies.find((x) => x.id == company_id);

            if (obj) return obj.name;
            else return "";
        },
        document_type_name(document_type_id) {
            var obj = this.documentTypes.find((x) => x.id == document_type_id);

            if (obj) return obj.name;
            else return "";
        },
        payment_type_name(payment_type_id) {
            var obj = this.paymentTypes.find((x) => x.id == payment_type_id);

            if (obj) return obj.name;
            else return "";
        },
        group_name(group_id) {
            var obj = this.groups.find((x) => x.id == group_id);

            if (obj) return obj.name;
            else return "";
        },
        limit_name(limit_code) {
            var obj = this.approveLimits.find((x) => x.code == limit_code);

            if (obj) return obj.name;
            else return "";
        },
        budget_type_name(approved_limit_code) {
            var obj = this.approveLimits.find((x) => x.code == approved_limit_code);
            var name = "";
            if (obj) {
                var budgettype = this.budget_types.find((x) => x.id == obj.budget_type);
                name = budgettype.name;
            }

            return name;
        },
        currency_name(approved_limit_code) {
            var obj = this.approveLimits.find((x) => x.code == approved_limit_code);
            var name = "";
            if (obj) {
                name = obj.currency;
            }

            return name;
        },
        team_name(team_id) {
            var obj = this.teams.find((x) => x.id == team_id);

            if (obj) return obj.name;
            else return "";
        },
        department_name(department_id) {
            var obj = this.departments.find((x) => x.id == department_id);
            if (obj) return obj.name;
            else return "";
        },

        hasError(fieldName) {
            return fieldName in this.errors;
        },
        getError(fieldName) {
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];
        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        },
        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.approveRouting) {
                this.approveRouting[field] = null;
            }
        },
        clearAllError() {
            this.errors = {};
        },
        showMessage(title, message) {
            if (!title) title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right",
            };

            toastr.success(message, title);
        },
    },
    computed: {
        teams_cbo() {
            var list = [];
            var item = {};
            this.teams.forEach((team) => {
                item = {};
                item.id = team.id;
                item.label = team.name;
                list.push(item);
            });

            return list;
        },
        rows() {
            return this.approveRoutings.length;
        },
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
        department_filter() {
            let company_id = this.form_filter.company_id;
            // this.contract.department_id = "";
            return this.departments.filter(function (item) {
                if (item.company_id == company_id) {
                    return true;
                }
            });
        },

        approveLimit_filter_form() {
            let group_id = this.approveRouting.group_id;
            let group = this.groups.find((x) => x.id == group_id);
            let document_type_id = this.approveRouting.document_type_id;
            let payment_type_id = this.approveRouting.payment_type_id;
            let company_id = group ? group.company_id : "";
            let currency = this.approveRouting.currency;
            let budget_type = this.approveRouting.budget_type;

            // this.contract.department_id = "";
            return this.approveLimits.filter(function (item) {
                if (
                    item.company_id == company_id &&
                    item.document_type_id == document_type_id &&
                    item.currency == currency &&
                    item.budget_type == budget_type &&
                    item.payment_type_id == payment_type_id
                ) {
                    return true;
                }
            });
        },
        //  company_name(){

        //       var obj =  this.companies.find(x=>x.id == this.company_id);
        //       if(obj)
        //         return obj.name;

        //  },
        //   department_name(){

        //       var obj =  this.departments.find(x=>x.id == this.department_id);
        //       if(obj)
        //         return obj.name;

        //  },
    },

    mounted() {
        console.log("Component mounted.");
    },
};
</script>
<style scoped>
/* fix khoảng cách bên dưới table so với phân trang */

.table {
    margin-bottom: 0px;
}
</style>
