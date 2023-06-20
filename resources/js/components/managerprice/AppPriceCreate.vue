<template>
    <div>
        <form
            @submit.prevent="addPraprequest"
            @keydown.enter.prevent="clearError"
        >
            <div class="content-header">
                <div class="container-fluid ml-0">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <h5 class="m-0 text-dark">
                                        <i  class="nav-icon fas fa-file-contract" ></i>
                                        <a href="/price/requests">{{$t(pre_title)}}</a>
                                    </h5>
                                </li>

                                <li class="breadcrumb-item active">
                                    <span v-if="edit_praprequest">{{$t("form.edit")}}</span>
                                    <span v-else>{{ $t("form.create") }}</span>
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <div class="float-sm-right" style="margin-top: -5px">
                                <a href="/price/requests" class="btn btn-default" >{{ $t("form.cancel") }}</a>
                                <button type="submit" :disabled="loading" class="btn btn-primary">
                                    {{ $t("form.save") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div v-if="hasAnyError > 0">
                        <div class="alert alert-warning">
                            <button type="button" class="close" @click.prevent="clearAllError()">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <ul>
                                <li v-for="(err, index) in errors" v-bind:key="index" >
                                    {{ err }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-default">
                        <!-- <div class="card-header">
                        </div> -->
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{$t("form.document_num")}}</label>
                                        <div class="col-sm-7">
                                            <input
                                                type="text"
                                                class="form-control form-control-sm"
                                                v-model="praprequest.serial_num"
                                                readonly
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="proposer"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.proposer") }}
                                            <small
                                                v-if="showValidate('proposer')"
                                                class="text-red"
                                                >( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <select
                                                class="form-control form-control-sm"
                                                style="width: 100%"
                                                name="proposer"
                                                id="proposer"
                                                :readonly="true"
                                                :disabled="true"
                                                v-model="praprequest.proposer"
                                            >
                                                <option
                                                    v-for="user in users"
                                                    :key="user.id"
                                                    v-bind:value="user.id"
                                                >
                                                    {{ user.name }}
                                                </option>
                                            </select>
                                            <div v-bind:class="hasError('proposer')? 'is-invalid': ''">
                                                <span v-if="hasError('proposer')" class="invalid-feedback" role="alert">
                                                    <strong>{{getError("proposer")}}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                     <div
                                        class="form-group row"
                                      >

                                        <label  for="group_id" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{$t("form.group")}}
                                            <small v-if="showValidate('group_id')"
                                                class="text-red">( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <select
                                                name="group_id"
                                                id="group_id"
                                                v-model="praprequest.group_id"
                                                v-bind:class="hasError('group_id')? 'is-invalid': ''"
                                                @click="clearError($event)"
                                                @change="clearError($event)"
                                                required
                                                class="form-control form-control-sm"
                                            >
                                                <option
                                                    v-for="group in group_filter"
                                                    v-bind:key="group.id"
                                                    v-bind:value="group.id"
                                                >
                                                    {{group.company_id}}-{{group.name}}
                                                </option>
                                            </select>
                                            <span
                                                v-if="hasError('group_id')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{getError("group_id")}}</strong>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="money_receiver" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{ $t("form.vendor")}}<small   class="text-red">( * )</small></label>

                                        <div class="col-sm-7 btn-group" role="group">
                                             <!-- <treeselect placeholder ="All" v-model="praprequest.vendor_id" :disable-branch-nodes="true" :options="vendor_cbo"></treeselect> -->
                                            <select
                                                class="form-control form-control-sm max-width:20px"
                                                v-model="praprequest.vendor_id"
                                                :required="true"
                                                style="width: 100%"
                                            >
                                                <option
                                                    v-for="vendor in vendors"
                                                    v-bind:key="vendor.id"
                                                    :value="vendor.id"
                                                >
                                                    {{ vendor.comp_name }}
                                                </option>
                                            </select>
                                            <button type="button" @click="ShowVenorDialog()" :title="$t('form.select')" class="btn btn-secondary btn-sm">...</button>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="title" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{ $t("form.title")}}<small   class="text-red" >( * )</small></label>
                                        <div class="col-sm-7">
                                            <input
                                                class="form-control form-control-sm"
                                                v-model="praprequest.title"
                                                rows="1"
                                                maxlength="255"
                                                v-bind:class="hasError('title')? 'is-invalid': ''"
                                                name="title"
                                                id="title"
                                                required
                                            />
                                            <span
                                                v-if="hasError('title')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{getError("title")}}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="effective_date" class="col-form-label-sm col-sm-5 col-form-label">
                                            <span v-html="showLabel('effective_date',$t('form.effective_date'))"></span>
                                            <small   class="text-red" >( * )</small></label>
                                        <div class="col-sm-7">
                                            <input
                                                type="date"
                                                class="form-control form-control-sm"
                                                data-date=""
                                                data-date-format="DD/MM/YYYY"
                                                v-model="praprequest.effective_date"
                                                v-bind:class="hasError('effective_date')? 'is-invalid': ''"
                                                name="effective_date"
                                                id="effective_date"
                                                @click="clearError($event)"
                                                @change="clearError($event)"
                                                placeholder=""
                                                :required="true"
                                            />
                                            <span v-if="hasError('effective_date')" class="invalid-feedback" role="alert">
                                                <strong>{{getError("effective_date")}}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="purchase_note" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{ $t("form.purchase_note")}}<small class="text-red">( * )</small></label>
                                        <div class="col-sm-7">

                                             <ckeditor
                                                v-model="praprequest.purchase_note"
                                                :required="showValidate('purchase_note')"
                                                v-bind:config="editorConfig"
                                                v-bind:class="editorClass"
                                                name="purchase_note"
                                                id="purchase_note"



                                            ></ckeditor>
                                            <span
                                                v-if="hasError('purchase_note')"
                                                class="invalid-feedback"
                                                role="alert">
                                                <strong>{{
                                                    getError("purchase_note")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                         <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.currency')}}<small  class="text-red">( * )</small></label>
                                        <div class="col-sm-2">
                                        <select class="form-control form-control-sm"
                                            name="currency"
                                            id="currency"
                                            v-model="praprequest.currency"
                                            v-bind:class="hasError('currency')?'is-invalid':''"

                                            >
                                                <option v-for="cur in currencies" :key="cur.id" :value="cur.id">{{cur.id}}</option>

                                            </select>
                                            <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                                                        <strong>{{getError('currency')}}</strong>

                                            </span>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="price_approval_file" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{ $t("form.price_approval_file")}}
                                            <small v-if="showValidate('price_approval_file')" class="text-red">( * )</small></label>
                                        <div class="col-sm-7">
                                            <button
                                                type="button"
                                                v-on:click="handleClick_FileDuyetGia()"
                                                title="Chọn file"
                                                class="btn btn-xs btn-outline"
                                            >
                                                <i
                                                    title="Chọn file"
                                                    class="fas fa-paperclip"
                                                ></i>
                                            </button>
                                            <div
                                                class="d-flex justify-content-between">
                                                <ul class="list-unstyled">
                                                    <li
                                                        v-for="(file,
                                                        index) in praprequest.attachment_file"
                                                        v-bind:key="index"
                                                        class="itemfile"
                                                    >
                                                        <div class="btn-group">
                                                            <button
                                                                type="button"
                                                                class="btn btn-default btn-xs"
                                                            >
                                                                {{ file.name }}
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="btn btn-default btn-xs text-red"
                                                                @click.prevent="deleteFile_DuyetGia(file,index)">
                                                                <i class="far fa-times-circle" ></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                v-if="file.id"
                                                                class="btn btn-default btn-xs"
                                                                @click.prevent="downloadFile(file.id,file.name)"
                                                            >
                                                                <i class="fas fa-download"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <input
                                                    type="file"
                                                    accept=".doc,.docx,.pdf,.msg,.eml"
                                                    id="add_price_approve_file"
                                                    style="display:none"
                                                    ref="price_approve_file"
                                                    @input="emitEvent_FileDuyetGia($event)"
                                                >
                                            </div>

                                            <!-- <input
                                                type="file"
                                                id="price_approval_file"
                                                name="price_approval_file"
                                                accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                            /> -->
                                            <span v-if="hasError('price_approval_file')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{getError("price_approval_file")}}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="material_type_name" class="col-form-label-sm col-sm-5 col-form-label">
                                            {{ $t("form.material_type_name")}}
                                            <small v-if="showValidate('material_type_name')" class="text-red">( * )</small>
                                        </label>
                                        <div class="col-sm-7">
                                            <input
                                                type="text"
                                                maxlength="50"
                                                id="material_type_name"
                                                name="material_type_name"
                                                class="form-control form-control-sm"
                                                :required="showValidate('material_type_name')"
                                                v-bind:class="hasError('material_type_name')? 'is-invalid': ''"
                                                @change="clearError($event)"
                                                v-model="praprequest.material_type_name"
                                            />
                                            <span
                                                v-if="hasError('material_type_name')"
                                                class="invalid-feedback"
                                                role="alert">
                                                <strong>{{getError("material_type_name")}}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->

                                    <div class="form-group row">
                                        <label
                                            for="method_payment_name"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.method_payment_name")
                                            }}<small v-if="showValidate('method_payment_name')" class="text-red">( * )</small>
                                        </label>
                                        <div class="col-sm-7">
                                            <textarea  class="form-control form-control-sm"  cols="30"
                                                 v-model="praprequest.method_payment_name"
                                                :required="showValidate('method_payment_name')"
                                                v-bind:class="hasError('method_payment_name')? 'is-invalid': ''"
                                                name="method_payment_name"
                                                id="method_payment_name"

                                                maxlength="255"
                                               ></textarea>

                                            <span
                                                v-if="hasError('method_payment_name')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{getError("method_payment_name")}}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="contract_num"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.principle_contract")
                                            }}<small
                                                v-if="showValidate('contract_num')"
                                                class="text-red">( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <input
                                                class="form-control form-control-sm"
                                                v-model="praprequest.contract_num"
                                                :required="showValidate('contract_num')"
                                                maxlength="50"
                                                v-bind:class="hasError('contract_num')? 'is-invalid': ''"
                                                name="contract_num"
                                                id="contract_num"
                                            />
                                            <span
                                                v-if="hasError('contract_num')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{
                                                    getError("contract_num")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="diff_info"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.diff_info")
                                            }}<small
                                                v-if="showValidate('diff_info')"
                                                class="text-red"
                                                >( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <textarea
                                                type="text"
                                                id="diff_info"
                                                name="diff_info"
                                                class="form-control form-control-sm"
                                                :required="
                                                    showValidate('diff_info')
                                                "
                                                v-bind:class="
                                                    hasError('diff_info')
                                                        ? 'is-invalid'
                                                        : ''
                                                "
                                                @change="clearError($event)"
                                                v-model="praprequest.diff_info"
                                                multiline=true
                                                maxlength="150"
                                            ></textarea>
                                            <span
                                                v-if="hasError('diff_info')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{
                                                    getError("diff_info")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="is_order"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.is_order")
                                            }}<small
                                                v-if="showValidate('is_order')"
                                                class="text-red"
                                                >( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <input
                                                type="checkbox"
                                                id="is_order"
                                                name="is_order"
                                                v-model="praprequest.is_order"
                                            />
                                            <span
                                                v-if="hasError('is_order')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{
                                                    getError("is_order")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="another_idea"
                                            class="col-form-label-sm col-sm-5 col-form-label"
                                            >{{ $t("form.another_idea")
                                            }}<small
                                                v-if="
                                                    showValidate('another_idea')
                                                "
                                                class="text-red"
                                                >( * )</small
                                            ></label
                                        >
                                        <div class="col-sm-7">
                                            <textarea
                                                type="text"
                                                id="another_idea"
                                                name="another_idea"
                                                class="form-control form-control-sm"
                                                :required="
                                                    showValidate('another_idea')
                                                "
                                                v-bind:class="
                                                    hasError('another_idea')
                                                        ? 'is-invalid'
                                                        : ''
                                                "
                                                @change="clearError($event)"
                                                v-model="praprequest.another_idea"
                                                multiline=true
                                                maxlength="150"
                                            ></textarea>
                                            <span
                                                v-if="hasError('another_idea')"
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{
                                                    getError("another_idea")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <div class="row">
                                 <div class="col-md-12">
                                      <div class="form-group row">
                                        <label for="purchase_note" class="col-form-label-sm col-sm-2 col-form-label">
                                            {{ $t("form.purchase_note")}}<small class="text-red">( * )</small></label>
                                        <div class="col-sm-10">

                                             <ckeditor
                                                v-model="praprequest.purchase_note"
                                                :required="showValidate('purchase_note')"
                                                v-bind:config="editorConfig"
                                                v-bind:class="editorClass"
                                                name="purchase_note"
                                                id="purchase_note"



                                            ></ckeditor>
                                            <span
                                                v-if="hasError('purchase_note')"
                                                class="invalid-feedback"
                                                role="alert">
                                                <strong>{{
                                                    getError("purchase_note")
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <b-card no-body>
                                        <b-tabs card small>
                                            <!-- <b-tab :title="$t('form.general_information')" active >
                                                <div class="row mt-2 mb-2">
                                                    <div class="col-md-12">

                                                    </div>
                                                </div>
                                            </b-tab> -->
                                            <b-tab :title="$t('form.product_service')"   >
                                                <b-card-text>
                                                <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <div class="d-flex ">
                                                    <button class="btn   btn-outline-info  btn-sm mb-1" @click.prevent.stop="popupListMaterial()" ><i class="fa fa-search"></i> {{$t('form.select')}}</button>
                                                    <button class="btn   btn-outline-info  btn-sm mb-1 ml-1" @click.prevent.stop="showProductEmpty()" ><i class="fas fa-plus"></i> {{$t('form.new_row')}}</button>

                                                    <!-- <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Nhiều nhà cung cấp
                                                        </label>
                                                    </div> -->
                                                    <!-- <span> {{$t('form.sum')}} : {{product_total_amount.toLocaleString(locale_format) }}</span> -->
                                                    </div>

                                                    <input type="file"
                                                    accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.msg"
                                                        @input="emitEvent_voucher($event)"
                                                        @change="emitEvent_voucher($event)"
                                                        id="ThemFileVoucher"
                                                        style="display:none"
                                                        ref="fileVoucher"
                                                    multiple
                                                        >
                                                     <b-table responsive striped hover bordered  :items="praprequest.products"
                                                       :fields="product_fields" small>


                                                     <template #cell(quantity)="data">
                                                           <ul  class="list-unstyled">
                                                                 <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                                     <span v-if="detail.quantity!=null && detail.vendor_id == praprequest.vendor_id">{{detail.quantity.toLocaleString(local_format)}}</span>
                                                                 </li>

                                                             </ul>
                                                     </template>
                                                      <template #cell(price)="data">
                                                           <ul  class="list-unstyled">
                                                                 <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                                    <span v-if="detail.price !=null && detail.price !='0' && detail.vendor_id == praprequest.vendor_id">{{detail.price.toLocaleString(local_format, { minimumFractionDigits: 4 })}}</span>
                                                                 </li>

                                                             </ul>
                                                     </template>
                                                       <template #cell(price_old)="data">
                                                           <ul  class="list-unstyled">
                                                                 <li style="width:100%; text-align:right" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                                    <span v-if="detail.price_old !=null  && detail.price_old !='0' && detail.vendor_id == praprequest.vendor_id">{{detail.price_old.toLocaleString(local_format, { minimumFractionDigits: 4 })}}</span>
                                                                 </li>

                                                             </ul>
                                                     </template>
                                                     <template #cell(brand)="data">
                                                           <ul  class="list-unstyled">
                                                                 <li style="width:100%;" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                                    <span v-if="detail.vendor_id == praprequest.vendor_id">{{detail.brand}}</span>
                                                                 </li>

                                                             </ul>
                                                     </template>
                                                     <template #cell(is_vat)="data"  >
                                                           <ul  class="list-unstyled">
                                                                 <li style="width:100%; text-align:center" v-for="(detail,index) in data.item.details" v-bind:key="index">
                                                                    <!-- <span v-if="detail.is_vat == 1">X</span> -->
                                                                    <span v-if="detail.vendor_id == praprequest.vendor_id && detail.is_vat !='-2' && detail.is_vat !='-1' &&  detail.is_vat !='X'">{{detail.is_vat}}%</span>
                                                                    <span v-if="detail.vendor_id == praprequest.vendor_id && detail.is_vat =='-2'">Đã có VAT</span>

                                                                 </li>

                                                             </ul>
                                                     </template>
                                                     <template #cell(action)="data">
                                                          <a href="#" class="btn btn-sm text-info" @click.prevent="editProduct(data.item)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <a href="#" class="btn btn-sm text-red"  @click.prevent="deleteProduct(data.item)">
                                                            <i class="fas fa-trash"></i>
                                                            </a>
                                                     </template>
                                                     <template v-slot:cell(product_specs)="data">
                                                         <div class="d-flex justify-content-between">
                                                             <ul  class="list-unstyled">
                                                                 <li v-for="(spec,index) in data.item.specs" v-bind:key="index">
                                                                      {{spec.name}}
                                                                     <span v-for="(other,other_index) in spec.others" v-bind:key="other_index">
                                                                         <span v-if="other.vendor_id == praprequest.vendor_id">:{{other.value}}</span>
                                                                     </span>

                                                                 </li>

                                                             </ul>
                                                         </div>
                                                     </template>


                                                    <template #row-details="row">
                                                        <b-card>
                                                           <b-table striped hover :items="row.item.details"></b-table>

                                                        <b-button size="sm" @click="row.toggleDetails">Hide Details</b-button>
                                                        </b-card>
                                                    </template>
                                                     </b-table>

                                                </div>
                                                </div>

                                                </b-card-text>
                                            </b-tab>
                                            <b-tab :title="$t('form.detail_price')">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                         <div class="form-group row">
                                                            <span class="small mt-1">{{$t('form.product_service')}}</span>
                                                            <div class="col-md-7">
                                                                <select class="form-control form-control-sm" v-model="product_index" @change="choose_product_for_detail()">
                                                                        <option v-for="(product,index) in praprequest.products" v-bind:key="index" v-bind:value="index" >
                                                                            {{product.name}}
                                                                        </option>
                                                                    </select>
                                                            </div>

                                                         </div>

                                                <div >
                                                    </div>
                                                </div>

                                                   <b-table  striped bordered hover   :items="details" :fields="detail_fields">


                                                      <template #cell(index)="data">
                                                            {{data.index + 1}}
                                                        </template>

                                                          <template #cell(vendor_id)="data">
                                                               <input type="checkbox"


                                                                    v-model="data.item.vendor_id"
                                                                    @change="setVendorDetail($event,data.item)">
                                                              <!-- <input   type="checkbox" @change="setVendorDetail(this, data.item.vendor_id)"> -->

                                                            <!-- <select class="form-control form-control-sm" v-model="data.item.vendor_id">
                                                                <option value=""></option>
                                                                <option v-for="(vendor,index) in vendors" v-bind:key="index" v-bind:value="vendor.id" >
                                                                    {{vendor.comp_name}}
                                                                </option>
                                                            </select> -->
                                                            <!-- <treeselect placeholder ="All" v-model="data.item.vendor_id" openDirection="bottom" :zIndex="100000"  :max-height="100" :disable-branch-nodes="true" :options="vendor_cbo"></treeselect> -->
                                                        </template>
                                                          <template #cell(vendor_display)="data">
                                                              <input type="text" v-model="data.item.vendor_display" class="form-control form-control-sm">
                                                        </template>
                                                         <template #cell(quantity)="data">

                                                               <vue-numeric  :empty-value="1" :separator="separator"  v-model="data.item.quantity" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                                                        </template>
                                                          <template #cell(price_old)="data">

                                                              <vue-numeric  v-bind:precision="4" empty-value="0" :separator="separator"  v-model="data.item.price_old" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                                                        </template>
                                                        <template #cell(price)="data">

                                                              <vue-numeric v-bind:precision="4" :empty-value="0" :separator="separator"  v-model="data.item.price" style="text-align:right" class="form-control form-control-sm"></vue-numeric>
                                                        </template>
                                                        <template #cell(is_vat)="data"  >
                                                              <select name="" id=""  v-model="data.item.is_vat" class="form-control form-control-sm" style="width:80px">
                                                                  <option value="-1" title="" selected></option>
                                                                  <option value="0" title="0%" selected>0% </option>
                                                                  <option value="5" title="5%">5% </option>
                                                                  <option value="8" title="8%">8% </option>
                                                                  <option value="10" title="10%">10% </option>
                                                                  <option value="-2" title="Đã có vAT">Đã có VAT </option>
                                                              </select>
                                                        </template>
                                                          <template #cell(brand)="data">
                                                              <input type="text" v-model="data.item.brand" class="form-control form-control-sm">
                                                        </template>
                                                        <template #cell(action)="data">
                                                            <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red"
                                                                        v-on:click="deleteDetail(data.item,data.index)" >
                                                                        <i  title="Xoá" class="fas fa-trash"></i></button>

                                                        </template>
                                                          <!-- <template #cell(other)="data">
                                                              <button @click="showDialogOther(data.item,data.index)"> ...</button>
                                                        </template> -->
                                                     <template slot="bottom-row" scope="">
                                                        <td colspan="2" > <a href="#" :title="$t('form.new_row')" @click.prevent.stop="AddNewRow_Detail()"><i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i></a></td>

                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>

                                                    </template>
                                                </b-table>
                                                </div>


                                            </b-tab>
                                            <b-tab :title="$t('form.info_product')">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                      <div class="form-group row">
                                                            <label class="col-form-label-sm col-sm-2 col-form-label" >{{$t('form.product_service')}} </label>
                                                            <div class="col-md-3">
                                                                 <select class="form-control form-control-sm" v-model="product_other_index" @change="choose_product_for_detail_other()">
                                                                    <option v-for="(product,index) in praprequest.products" v-bind:key="index" v-bind:value="index" >
                                                                        {{product.name}}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <label class="col-form-label-sm col-sm-2 col-form-label" >{{$t('form.info_product')}} </label>
                                                            <div class="col-md-3">
                                                                 <select class="form-control form-control-sm" v-model="product_other_spec_index" @change="choose_product_for_detail_other_spec()">
                                                                    <option v-for="(cbo_spec,index) in other_specs" v-bind:key="index" v-bind:value="index" >
                                                                        {{cbo_spec.name}}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                         </div>
                                                <div >
                                                    </div>
                                                </div>

                                                   <b-table  responsive striped bordered hover  head-variant="light"  :sticky-header="true" small :items="others" :fields="other_fields">

                                                      <template #cell(index)="data">
                                                            {{data.index + 1}}
                                                        </template>
                                                          <template #cell(vendor_id)="data">
                                                          <input type="checkbox"
                                                                    v-model="data.item.vendor_id"
                                                                    @change="setVendorDetail($event,data.item)">
                                                            <!-- <select class="form-control form-control-sm" v-model="data.item.vendor_id">
                                                                <option value=""></option>
                                                                <option v-for="(vendor) in vendors" v-bind:key="vendor.id" v-bind:value="vendor.id" >
                                                                    {{vendor.comp_name}}
                                                                </option>
                                                            </select> -->
                                                        </template>
                                                          <template #cell(value)="data">
                                                              <input type="text" v-model="data.item.value" class="form-control form-control-sm">
                                                        </template>
                                                         <template #cell(vendor_display)="data">
                                                              <input type="text" v-model="data.item.vendor_display" class="form-control form-control-sm">
                                                        </template>
                                                        <template #cell(action)="data">
                                                            <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red"
                                                                        v-on:click="deleteOther(data.item,data.index)" >
                                                                        <i  title="Xoá" class="fas fa-trash"></i></button>

                                                        </template>
                                                     <template slot="bottom-row" scope="">
                                                        <td colspan="2" > <a href="#" :title="$t('form.new_row')" @click.prevent.stop="AddNewRow_Other()"><i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i></a></td>

                                                        <td></td>
                                                        <td></td>
                                                    </template>
                                                </b-table>
                                                </div>


                                            </b-tab>
                                             <b-tab title="Preview">
                                                 <price-approve-request-review v-if="vendor_count == 1"  :data="praprequest"></price-approve-request-review>
                                                 <price-approve-request-review-vendor v-if="vendor_count > 1 && !showSpec()"  :data="praprequest"></price-approve-request-review-vendor>
                                                 <price-approve-request-review-spec-vendor v-if="vendor_count > 1 && showSpec()"  :data="praprequest"></price-approve-request-review-spec-vendor>

                                             </b-tab>
                                             <b-tab :title="$t('form.attached_doc')"   >
                                                <b-card-text>
                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-12">
                                                <!-- <button class="btn btn-outline-info btn-sm mb-2 " @click.prevent.stop="popupPaycatsetSearch()" ><i class="fa fa-file" ></i> Bộ danh mục chứng từ thanh toán</button> -->
                                                <input type="file"
                                                    accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.msg"
                                                    @input="emitEvent_attached($event)"
                                                    @change="emitEvent_attached($event)"
                                                    id="ThemFile"
                                                    style="display:none"
                                                    ref="file"
                                                multiple />
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:100px" >#</th>
                                                        <th style="width:250px">{{$t('form.name')}}</th>
                                                        <th >File</th>
                                                        <th style="width:150px" >{{$t('form.note')}}</th>

                                                        <th style="width: 5px;"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr v-for="(other_attached,index) in praprequest.other_attacheds" v-bind:key="index">
                                                        <td scope="row" >{{index+1}}</td>
                                                        <td @click.prevent.stop="changeGridEdit($event)">
                                                        <!-- {{other_attached.name}}  -->
                                                        <!-- <div class="cellgrid" style="display:inlin-block"   > -->
                                                        <span @click.prevent.stop="changeGridViewToEdit($event)" >{{other_attached.name}}</span>
                                                        <input type="text"  maxlength="50" @blur.prevent.self="changeGridView($event)"     v-model="other_attached.name" class="form-control" placeholder="..."  style="display:none">
                                                        <!-- </div> -->

                                                        </td>
                                                        <td >

                                                        <div class="d-flex justify-content-between">
                                                            <ul class="list-unstyled">
                                                            <li v-for="(file,findex) in other_attached.attachment_file" v-bind:key="findex" class="itemfile">
                                                                <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                                                                <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile(file,index,findex)" ><i class="far fa-times-circle "></i></button>
                                                                <button type="button" v-if="file.id" class="btn btn-default btn-xs " @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>
                                                            </div>
                                                            </li>
                                                            </ul>
                                                            <div>
                                                            <button type="button"  :title="$t('form.choose_file')" class="btn btn-xs btn-outline " v-on:click="handleClickInputFile(index)">
                                                                <i  :title="$t('form.choose_file')" class="fas fa-paperclip"></i></button>

                                                            <!-- <input type="button" v-on:click="handleClickInputFile(index)" value="..."> -->

                                                            </div>
                                                        </div>
                                                        </td>
                                                        <td  @click="changeGridEdit($event)">
                                                            <!-- <div class="d-flex justify-content-between"> -->
                                                            <span @click="changeGridViewToEdit($event)" >{{other_attached.note}}</span>
                                                            <!-- <button  type="button" title="thêm ghi chú" @click="showDialogAddNote(index)" class="btn btn-sm btn-outline" ><i title="thêm ghi chú" class="far fa-sticky-note"></i></button> -->
                                                            <input style="display:none" @blur.prevent.self="changeGridView($event)"  v-model="other_attached.note"   type="text"    class="form-control" >
                                                            <!-- </div> -->
                                                        </td>
                                                        <!-- <td style="text-align: center;"><div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">

                                                        </div>
                                                        </td> -->
                                                        <td>
                                                            <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red" v-on:click="deleteInputFile(other_attached,index)">
                                                                <i  title="Xoá" class="fas fa-trash"></i></button>

                                                        <!-- <span  class="text-red"><i class="fa fa-trash"></i></span> -->
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <a href="#" @click.prevent.stop="AddNewRow()"> <i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i> </a>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    </tbody>

                                                </table>

                                                </div>

                                            </div>

                                                </b-card-text>
                                            </b-tab>
                                        </b-tabs>
                                    </b-card>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <loading :loading="loading"></loading>
                    </div>
                </div>
            </div>
        </form>

         <!-- Modal dialog -->
       <div class="modal fade" id="themsanphamdichvu" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="themsanphamdichvu" aria-hidden="true">
          <div class="modal-dialog" role="document">
             <form action=""    @submit.prevent.enter="AddProduct()"  @keydown.enter.prevent="clearError" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$t('form.add')}} {{$t('form.product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                  <div class="form-group row">
                    <label for="code_sap" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.code_sap')}} </label>
                    <div class="col-sm-8">
                       <input type="text"  v-model="product.code_sap" class="form-control form-control-sm"  id="code_sap" >
                    </div>
                  </div>

                   <div class="form-group row">
                    <label for="name" required class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.name')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                           <input type="text" maxlength="255" required  v-model="product.name" id="" class="form-control form-control-sm"    >
                    </div>
                  </div>
                    <div class="form-group row">
                    <label for="unit" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.unit')}}<small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                       <input type="text"  required v-model="product.unit" class="form-control form-control-sm"  id="code_sap" >
                    </div>
                  </div>
                  <div class="form-group row">
                      <label for="" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.info_product')}}</label>
                      <div class="col-sm-12">
                          <b-table   :items="product.specs" :fields="spec_fields" responsive  bordered small hover sticky-header="180px" head-variant="light">
                              <template #cell(index)="data">
                                  {{data.index + 1}}
                              </template>
                               <template #cell(name)="data">
                                  <input class="form-control form-control-sm"  type="text"  maxlength="50"      v-model="data.item.name"   placeholder=""   >
                              </template>
                               <template #cell(action)="data">
                                  <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red"
                                            v-on:click="deleteSpec(data.item,data.index)" >
                                            <i  title="Xoá" class="fas fa-trash"></i></button>

                              </template>
                           <template slot="bottom-row" scope="">
                                <td colspan="2" > <a href="#" :title="$t('form.new_row')" @click.prevent.stop="AddNewRow_Specs()"><i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i></a></td>

                                <td></td>

                            </template>
                          </b-table>

                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary " data-dismiss="modal">{{$t('form.close')}}</button>
                    <button type="submit" class="btn btn-primary" >{{$t('form.save')}}</button>
                </div>
            </div>
            </form>
          </div>

       </div>
         <div class="modal fade" id="nhapthongsosanpham" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="nhapthongsosanpham" aria-hidden="true">
          <div class="modal-dialog" role="document">
             <form action=""   @submit.prevent.enter="AddProductOtherByVendor()"  @keydown.enter.prevent="clearError" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông số sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                  <div class="form-group row">
                    <label for="code_sap" class="col-form-label-sm col-sm-4 col-form-label">sản phẩm/ dịch vụ </label>
                    <div class="col-sm-8">
                      <span>But tlg (10002)</span>
                    </div>
                  </div>

                   <div class="form-group row">
                    <label for="name" required class="col-form-label-sm col-sm-4 col-form-label"> Nhà cung cấp</label>
                    <div class="col-sm-8">
                           <span>FPT</span>
                    </div>
                  </div>

                  <div class="form-group row">
                      <label for="" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.info_product')}}</label>
                      <div class="col-sm-12">
                          <b-table  :items="spec.others" :fields="spec_other_fields" responsive  bordered small hover sticky-header="180px" head-variant="light">
                              <template #cell(index)="data">
                                  {{data.index + 1}}
                              </template>
                               <template #cell(name)="data">
                                  <input class="form-control form-control-sm"  type="text"  maxlength="50"      v-model="data.item.name"   placeholder=""   >
                              </template>
                                 <template #cell(value)="data">
                                  <input class="form-control form-control-sm"  type="text"  maxlength="50"      v-model="data.item.value"   placeholder=""   >
                              </template>
                               <template #cell(action)="data">
                                  <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red"
                                            v-on:click="deleteSpec(data.item,data.index)" >
                                            <i  title="Xoá" class="fas fa-trash"></i></button>

                              </template>
                           <template slot="bottom-row" scope="">
                                <td colspan="2" > <a href="#" :title="$t('form.new_row')" @click.prevent.stop="AddNewRow_Specs()"><i class="fa fa-plus-circle"></i> <i> {{$t('form.new_row')}} </i></a></td>

                                <td></td>

                            </template>
                          </b-table>

                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary " data-dismiss="modal">{{$t('form.close')}}</button>
                    <button type="submit" class="btn btn-primary" >{{$t('form.save')}}</button>
                </div>
            </div>
            </form>
          </div>

       </div>

        <list-choose :url="dialog_url"
        :fields="dialog_fields"
         v-on:selectedDialog="selectedDialog"
        :eventname="selectedDialogName"
        :name="dialog_name">

        </list-choose>
    </div>
</template>

<script>
import ListChoose from '../shared/ListChoose.vue';
 import Treeselect from '@riophae/vue-treeselect'
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    components:{

      ListChoose,Treeselect
    },
    watch: {

    },
    props: {
        id: String,
        user_id: String,
        doctype: String,
        doctype_id: String,
        pre_title: String,
        layout: Object
    },
    data() {
        return {
            //Dialog
            dialog_url:"",
            dialog_fields:[],
            selectedDialog:function() {

            },
            dialog_name:"",
            selectedDialogName:"selectedDialog",
            //Data
            praprequest: {
                proposer: this.user_id,
                vendor_id: null,
                group_id:"",
                currency: "VND",
                document_type_id: this.doctype_id,
                attachment_file: [],
                attachment_file_removed: [],
                other_attacheds:[],
                other_attacheds_del:[],
                products:[],
                products_del:[],

            },
            other_attached_curr_index:"",
            other_attached:{
                paycattype_id:"",
                name:"",
                note:"",
                attachment_file:[],
                attachment_file_removed:[],

            },
            product_index:"",
            product_other_index:"",
            product_other_spec_index:"",
            product:{
                id:"",
                price_req_id:"",
                code_sap:"",
                name:"",
                unit:"",
                details:[],
                details_del:[],
                specs:[],
                specs_del:[],
                index:""
            },
            details:[],
            details_del:[],
            detail:{
                id:"",
                price_product_id:"",
                product_id:"",
                vendor_id:"",
                vendor_display:"",
                quantity:"1",
                price_old:"0",
                price:"0",
                choose:"",
                brand:"",
                is_vat:"-1",
                specs:[],
                specs_del:[],
            },

            specs:[],
            spec:{
                id:"",
                name:"",
                others:[],
                others_del:[],
            },
            others:[],
            others_del:[],
            other:{
                id:"",
                price_product_id:"",
                price_spec_id:"",
                vendor_id:"",
                vendor_display:"",
                value:"",
            },
            material_fields:[
                 {
                    key: 'selected',
                    label:'',
                    stickyColumn: true
                    },
                 {
                    key: 'name',
                    label:this.$t('form.product_name'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'code_sap',
                    label:this.$t('form.code_sap'),
                    sortable: true,
                    stickyColumn: true
                },

            ],
            vendor_fields:[
                 {
                    key: 'selected',
                    label:'',
                    stickyColumn: true
                    },
                 {
                    key: 'comp_name',
                    label:this.$t('form.company'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'tax_code',
                    label:this.$t('form.tax_code'),
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'contact',
                    label:this.$t('form.contact'),
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'phone',
                    label:this.$t('form.phone'),
                    sortable: true,
                    stickyColumn: true
                },

            ],
            other_specs:[],
            product_fields: [

                {
                    key: 'code_sap',
                    label:this.$t('form.code_sap'),
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'name',
                    label:this.$t('form.product_name_service'),
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'unit',
                    label:this.$t('form.unit'),
                    sortable: true,
                    stickyColumn: true,


                },
                {
                    key: 'quantity',
                    label:this.$t('form.quantity'),
                    sortable: true,
                    stickyColumn: true
                 },
                  {
                    key: 'price',
                    label:this.$t('form.new_price'),
                    sortable: true,
                    stickyColumn: true
                 },

                  {
                    key: 'price_old',
                    label:this.$t('form.current_price'),
                    sortable: true,
                    stickyColumn: true
                 },
                  {
                    key: 'is_vat',
                    label:'VAT',
                    sortable: true,
                    stickyColumn: true
                 },
                   {
                    key: 'brand',
                    label:this.$t('form.brand'),
                    sortable: true,
                    stickyColumn: true
                 },
                {
                    key: 'product_specs',
                    label:this.$t('form.info_product'),
                    sortable: true,
                    stickyColumn: true
                 },


                 {
                    key: 'action',
                    label:'action',

                    stickyColumn: true
                },

            ],
            spec_fields: [
                 {
                    key: 'index',
                    label:'#',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label:this.$t('form.content'),
                    sortable: true,
                    stickyColumn: true
                 },
                 {
                    key: 'action',
                    label:'action',
                     stickyColumn: true

                },


            ],
            spec_other_fields: [
                 {
                    key: 'index',
                    label:'#',
                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'name',
                    label:this.$t('form.content'),
                    sortable: true,
                    stickyColumn: true
                 },
                 {
                    key: 'value',
                    label:'Value',
                    sortable: true,
                    stickyColumn: true
                },
                 {
                    key: 'action',
                    label:'action',
                     stickyColumn: true

                },


            ],
            detail_fields: [

                {
                    key: 'vendor_id',
                    label:"Nhà cung cấp chính",

                    //sortable: true,
                    stickyColumn: true,

                },
                {
                    key: 'vendor_display',
                    label:this.$t('form.short_vendor'),
                   // sortable: true,
                    stickyColumn: true
                },


                 {
                    key: 'quantity',
                    label:this.$t('form.quantity'),
                   // sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'price_old',
                    label:this.$t('form.current_price'),
                    //sortable: true,
                    stickyColumn: true
                 },
                 {
                    key: 'price',
                    label:this.$t('form.new_price'),
                   // sortable: true,
                    stickyColumn: true
                },

                {
                    key: 'is_vat',
                    label:'VAT ',
                   // sortable: true,
                    stickyColumn: true,
                    class:"text-nowrap",
                    thStyle:"width:70px"

                },
                 {
                    key: 'brand',
                    label:this.$t('form.brand'),
                   // sortable: true,
                    stickyColumn: true
                },


                 {
                    key: 'action',
                    label:'action',

                    stickyColumn: true
                },
            ],
            other_fields: [

                {
                    key: 'vendor_id',
                    label:'Nhà cung cấp chính',

                    sortable: true,
                    stickyColumn: true
                },
                {
                    key: 'vendor_display',
                    label:this.$t('form.short_vendor'),
                    sortable: true,
                    stickyColumn: true
                },

                 {
                    key: 'value',
                    label:'Value',
                    sortable: true,
                    stickyColumn: true
                },


                 {
                    key: 'action',
                    label:'action',

                    stickyColumn: true
                },
            ],
              editorClass : "col-lg-12",
               editorConfig: {
                    toolbarGroups : [
                       // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: "clipboard", groups: ["clipboard", "undo"] },
                        { name: "editing", groups: ["find", "selection", "spellchecker", "editing"] },
                        // { name: 'forms', groups: [ 'forms' ] },
                        // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        {
                            name: "paragraph",
                            groups: ["list", "indent", "blocks", "align", "bidi", "paragraph"],
                        },
                        // { name: 'links', groups: [ 'links' ] },
                        { name: "insert", groups: ["insert"] },
                        // { name: 'styles', groups: [ 'styles' ] },
                        { name: "colors", groups: ["colors"] },
                        // { name: 'tools', groups: [ 'tools' ] },
                        // { name: 'others', groups: [ 'others' ] },
                        // { name: 'about', groups: [ 'about' ] }
                    ],
                    removeButtons:   "NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image",
                    //'clipboard,editing,paragraph,insert,colors, NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                },
            separator:'.',
            local_format: "de-DE",
            companies: [],

            vendorAll: [],
            //tree_vendors:[],
            currencies: [],
            departments: [],
            requests_serial_num: "",
            users: [],
            selected:[],
            selectAll: false,
            // vendors_combobox:[],
            errors: {},
            loading: false,
            edit_praprequest: false,
            edit_product: false,
            token: "",
            tabIndex: 0,
            page_url_department: "/api/category/departments",
            page_url_currency: "/api/category/currencies",
            page_url_users: "api/user/all",
            page_url_vendors: "/api/category/vendors",
            page_url_praprequest: "/api/price/requests",
            page_url_materials: "/api/category/products",
             options: [ {
                id: 'company',
                label: 'Company',
                children: [],
                } ],
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.getUser();
        this.fetVendor();
        this.fetCurrency();
        this.getPraprequest();
    },
    methods: {
        setVendorDetail(event,item){

            item.vendor_id = event.target.checked==true? this.praprequest.vendor_id:null;
            // console.log( event.target.checked+"="+item.vendor_id);
        },

         fetCurrency() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_currency;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.currencies = res.data;
                })
                .catch(err => console.log(err));
        },
        selectedMaterial(data){
        //console.log(data);

          data.forEach(element => {
            //   console.log(element);
               this.resetProduct();
               this.edit_product == false;
               this.product.name = element.name;
               this.product.code_sap = element.code_sap;
               this.product.unit = element.unit;
               this.praprequest.products.push({...this.product});

          });

        // this.payment_advance_moneys.push([...data]);
      },
        selectedVendor(data){

          data.forEach(element => {
               this.praprequest.vendor_id =  element.id;
               return;
          });

        // this.payment_advance_moneys.push([...data]);
      },
      showDialogOther(data,index){

            console.log(data);
            this.product = this.praprequest.products[this.product_index];
            //  this.other_specs = [...this.praprequest.products[this.product_index].specs ];
           try {
                    // this.others = data //this.praprequest.products[this.product_other_index].specs[this.product_other_spec_index].others ;
                   // this.others_del =  this.praprequest.products[this.product_other_index].specs[this.product_other_spec_index].others_del ;

                } catch (error) {

                }
           $("#nhapthongsosanpham").modal("show");

      },
      ShowVenorDialog(){
          //let group = this.group_filter.find(x=>x.id == this.praprequest.group_id);
        //   let company_id = group?group.id :"";

         let group_id   = this.praprequest.group_id;
          let group = this.group_filter.find(x=>x.id == group_id);
          let company_id = group?group.company_id:"";

         this.dialog_url = this.page_url_vendors+"?company_id="+company_id;
          this.dialog_fields = [...this.vendor_fields];
          this.selectedDialog = this.selectedVendor;
          this.dialog_name = this.$t('form.vendor');
           $("#modal-user").modal("show");
      },
      popupListMaterial(){
          //if(this.doctype == 'QTTU')
          this.dialog_url = this.page_url_materials;
          this.dialog_fields = [...this.material_fields];
          this.selectedDialog = this.selectedMaterial;
          this.dialog_name = this.$t('form.product');
           $("#modal-user").modal("show");
         },

        listVendor(){
          var vendors = new Array;
          var vendor_key = "";
          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {
                  vendor_key = detail.vendor_id  +"@"+ detail.vendor_display;
                   if (!vendors.includes(vendor_key)) {
                    vendors.push(vendor_key);
              }
              });

          });

          return vendors;
        },
        showSpec(){
          var flag = false;
          var check_quan = true;
          this.praprequest.products.forEach(product => {
              product.details.forEach(detail => {

                  //Do đặc thù spec nên số lượng nhập phải là 1 hoặc không nhập
                  if(detail.quantity > 1 )
                  {
                      check_quan = false;
                  }
              });
              product.specs.forEach(spec => {
                  spec.others.forEach(other => {
                   //Chỉ cần có nhập spec cho nhà cung cấp chính
                   if (other.vendor_id === this.praprequest.vendor_id) {

                       flag =  true;
                    }
                  });

              });

          });

          return flag && check_quan;
        },
         deleteOther(item,index){

          if(confirm(this.$t('form.confirm_delete') +'?')){


            this.others_del.push({...item});
            this.others.splice(index,1);
          }
        },

         deleteSpec(item,index){

          if(confirm(this.$t('form.confirm_delete') +'?')){

             this.product.specs_del.push({...item});
             this.product.specs.splice(index,1);
          }
        },
         deleteDetail(item,index){

          if(confirm(this.$t('form.confirm_delete') +'?')){

             this.details_del.push({...item});
            this.details.splice(index,1);


          }
        },
        choose_product_for_detail(){

            this.details =  this.praprequest.products[this.product_index].details ;

            this.details_del =  this.praprequest.products[this.product_index].details_del ;
        },
        choose_product_for_detail_other(){

            this.other_specs = [...this.praprequest.products[this.product_other_index].specs ];
            if(this.other_specs && this.other_specs.length >0)
             {
                 this.product_other_spec_index = "0";
                 }
             else{
                 this.others = [];
             }
            this.choose_product_for_detail_other_spec();
        },
         choose_product_for_detail_other_spec(){
                try {
                     this.others =  this.praprequest.products[this.product_other_index].specs[this.product_other_spec_index].others ;
                    this.others_del =  this.praprequest.products[this.product_other_index].specs[this.product_other_spec_index].others_del ;

                } catch (error) {

                }



        },


        // select() {
        //     this.selected = [];
        //     if (this.selectAll) {
        //     for (let i in this.praprequest) {
        //         this.selected.push(this.praprequest[i].id);

        //     }
        //     }
        // },
        resetProduct(){

            this.product.id = "";
            this.product.price_req_id = "";
            this.product.product_id = "";
            this.product.code_sap = "";
            this.product.name = "";
            this.product.unit = "";
            this.product.details = [],
            this.product.details_del =[],
            this.product.specs =[],
            this.product.specs_del = [],
            this.product.index  = "";

        },
         showProductEmpty(){
              this.resetProduct();
              this.edit_product = false;
              this.product.details = [];
              this.product.specs = [];

            // this.resetProduct();
              $("#themsanphamdichvu").modal("show");
        },
        showPriceEmpty(){
           this.edit_product_price = false;
            // this.resetProduct();
              $("#themsanphamdichvu_chitietgia").modal("show");
        },
         AddProduct(){

          if( this.edit_product == false){

               this.praprequest.products.push({...this.product});
               this.resetProduct();
          }else{

              this.$set(this.praprequest.products, this.product.index, {...this.product});


              this.choose_product_for_detail_other();
                this.resetProduct();
                 this.edit_product = false;
          }
          $("#themsanphamdichvu").modal("hide");
        //   $('#noidungchungtuthanhtoan').collapse('show');
        },

        editProduct(item){
            let index = this.praprequest.products.findIndex(x=>x == item);
            this.edit_product = true;
            this.product.id                          = item.id;
            this.product.price_req_id                = item.price_req_id;
            this.product.code_sap                    = item.code_sap;
            this.product.name                        = item.name;
            this.product.unit                        = item.unit;
            this.product.specs                       = [...item.specs];
            this.product.specs_del                   = [...item.specs_del];
            this.product.details                     = [...item.details];

            this.product.index                      = index;
             this.product_other_index               = 0;
             $("#themsanphamdichvu").modal("show");
        },
         deleteProduct(item){

            if(confirm( this.$t('form.confirm_delete') +'?')){
                let index = this.praprequest.products.findIndex(x=>x == item);
                this.praprequest.products_del.push({...item});
                this.praprequest.products.splice(index,1);
            }

        },
         // Nhập trên lưới
        changeGridEdit(event){
         let span = $(event.target).children('span');
         $(span).hide();
         console.log($(event.target).children('input').show() );

        },
        changeGridView(event){
          $(event.target).hide();
           $(event.target.parentElement).children('span').show();
        },
        changeGridViewToEdit(event){

           $(event.target).hide();
           $(event.target.parentElement).children('input').show();
        },
         //Tab other attached file
          AddNewRow(){

            this.other_attached.name = "";
            this.other_attached.attachment_file = [];
            this.other_attached.attachment_file_removed = [];
             this.praprequest.other_attacheds.push({...this.other_attached});
        },
        AddNewRow_Detail(){
             let specs = [...this.praprequest.products[this.product_index].specs];
            //  this.detail.specs = specs;

             this.details.push({...this.detail});
        },
        AddNewRow_Other(){

             this.others.push({...this.other});
        },
        //Thêm dòng mới trong thông số sản phẩm
        AddNewRow_Specs(){
            this.spec.id = "";
            this.spec.name = "";
            this.spec.others  = [],
            this.spec. others_del = [],
            this.product.specs.push({...this.spec});
        },
        //Thêm file duyệt giá
         handleClick_FileDuyetGia(index) {

            if (this.praprequest.attachment_file.length > 0 ) {
                alert("Vui lòng xóa file cũ");
            }else{
                this.$refs.price_approve_file.click();
            }



        },
        emitEvent_FileDuyetGia(event) {

            for (let index = 0; index < event.target.files.length; index++) {
              const file = event.target.files[index];
              //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
              let reader = new FileReader();
              reader.readAsDataURL(file);

              reader.onload = () => {
                  // console.log(event.target.files[0]);
                  const docs = {
                      name: file.name,
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                      base64: reader.result
                  };
                 //console.log(docs);
                 this.praprequest.attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';

        },
         //xoá file trong other_attacheds
        deleteFile_DuyetGia(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){

              //  console.log("index="+index);


                this.praprequest.attachment_file_removed.push({...item});
                this.praprequest.attachment_file.splice(findex,1);
            }
        },
        //Thêm file other_attached
        handleClickInputFile(index) {

            this.$refs.file.click();
            this.other_attached_curr_index = index;

        },
        //Xóa dòng  orther_attaches
         deleteInputFile(item,index){
          if(confirm(this.$t('form.confirm_delete') +'?')){
            this.praprequest.other_attacheds_del.push({...item});
            this.praprequest.other_attacheds.splice(index,1);
          }
        },
        //xoá file trong other_attacheds
        deleteFile(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){

              //  console.log("index="+index);


                this.praprequest.other_attacheds[index].attachment_file_removed.push({...item});
                this.praprequest.other_attacheds[index].attachment_file.splice(findex,1);
            }
        },
        addNote(){
          this.praprequest.other_attacheds[this.other_attached_curr_index].note =  $('#ghichu').val();
        },
         emitEvent_attached(event) {

            for (let index = 0; index < event.target.files.length; index++) {
              const file = event.target.files[index];
              //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
              let reader = new FileReader();
              reader.readAsDataURL(file);

              reader.onload = () => {
                  // console.log(event.target.files[0]);
                  const docs = {
                      name: file.name,
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                      base64: reader.result
                  };
                 //console.log(docs);
                 this.praprequest.other_attacheds[this.other_attached_curr_index].attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';

        },
        downloadFile(idfile,filename){
          var page_url = 'api/payment/downloadFile/'+idfile;
          fetch(page_url, {
             headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                responseType: 'arraybuffer',
                method:'post'
           })
            .then( res => res.blob() )
           .then(res=>{

              var newBlob = new Blob([res], {type: "octet/stream"});
              if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
              }
              // For other browsers:
              // Create a link pointing to the ObjectURL containing the blob.
              const data = URL.createObjectURL(newBlob);
              var link = document.createElement('a');
              link.href = data;
              link.download = filename  ;
              link.click();

              setTimeout(function () {
                // For Firefox it is necessary to delay revoking the ObjectURL
                URL.revokeObjectURL(data)
              }, 100);
           }).catch(err=>console.log(err));

        },
        fetVendor() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_vendors;
            fetch(page_url, {
                headers: {
                    Authorization: this.token
                }
            })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.vendorAll = res.data;
                })
                .catch(err => console.log(err));
        },
        getUser() {
            var page_url = this.page_url_users;
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.users = data.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getPraprequest(){
       // console.log(this.id);

        if( this.id != ""){
          this.loading = true;

           var page_url = this.page_url_praprequest+"/"+this.id+"/edit";
           fetch(page_url,{ headers: { Authorization: this.token } })
          .then(res=>res.json())
          .then(res=>{

          if(res.statuscode =="403"){
                 window.location.href = "/errorpage?statuscode="+res.statuscode;
            }
          if(res.data.success == '1'){
               this.praprequest = res.data;
               this.praprequest.attachment_file_removed = [];
               this.praprequest.other_attacheds_del = [];
                this.praprequest.products_del = [];

               //khởi tạo biến mảng attachment_file_removed

               this.praprequest.other_attacheds.forEach(element => {
                 element.attachment_file_removed = [];
               });

                this.praprequest.products.forEach(product => {
                    product.details_del = [];
                    product.specs_del = [];
                    product.specs.forEach(spec => {
                        spec.others_del = [];
                    });
               });


              this.edit_praprequest = true;
              this.loading = false;
            //   this.listVendor();

          }

        }).catch(err=>{
            this.loading = false;
            console.log(err);
        });
        }


      },
        addPraprequest() {
            this.loading = true;
            var page_url = this.page_url_praprequest; // "/api/category/serviceCategorys";
            // this.praprequest.amount = AutoNumeric.getNumber('#amount'); //$('#amount').val();
            // this.praprequest.amount_exchanged = AutoNumeric.getNumber('#amount_exchanged'); //$('#amount').val();
            //  this.praprequest.amount_out_exchanged = AutoNumeric.getNumber('#amount_out_exchanged'); //$('#amount').val();
            //  this.praprequest.amount_out_budget = AutoNumeric.getNumber('#amount_out_budget'); //$('#amount').val();
            // this.praprequest.exchange_rate = AutoNumeric.getNumber('#exchange_rate'); //$('#amount').val();
            // this.praprequest.proposer = $("#proposer").val();
            // this.praprequest.out_budget = this.out_budget_chk?"1":"0";
            //console.log(this.praprequest.amount);
            if (this.edit_praprequest === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.praprequest),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.statuscode == "403") {
                            window.location.href =
                                "/errorpage?statuscode=" + data.statuscode;
                        }
                        this.loading = false;
                        if (!data.data.errors) {
                            // this.resetProduct();
                            //  this.serviceCategorys.push(data.data);
                            toastr.success(this.$t("form.save_success"), "");
                            window.location.href = "/price/requests";
                            //$("#AddServiceCategory").modal("hide");
                        } else {
                            //console.log(this.errors);
                            this.errors = data.data.errors;
                        }
                    })
                    .catch(err => {

                        this.loading = false;
                         console.log(err);
                    });
            } else {
                //update
                // console.log(this.praprequest);
                fetch(page_url + "/" + this.praprequest.id, {
                    method: "PUT",
                    body: JSON.stringify(this.praprequest),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                          if(data.statuscode =="403"){

                                window.location.href = "/errorpage?statuscode="+data.data.statuscode;
                            }
                            if(data.statuscode =="422"){

                                window.location.href = "/errorpage?statuscode="+data.data.statuscode;
                            }
                             if(data.statuscode =="500"){

                                window.location.href = "/errorpage?statuscode="+data.data.statuscode;
                            }
                            this.loading= false;

                        if (!data.data.errors) {
                            toastr.success(this.$t("form.update_success"), "");
                            window.location.href = "/price/requests";
                        } else {
                            this.errors = data.data.errors;
                        }
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                    });
            }
        },


        showLabel(fieldName, value) {
            if (fieldName in this.layout) {
                if (this.layout[fieldName]["is_sesetCustomLabelt_label"]) {
                    return this.layout[fieldName]["custom_label_text"];
                }
            }
            return value;
        },
        showValidate(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]["require_validation"];
            }
            return false;
        },
        showControl(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]["isVisible"];
            }
            return false;
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
            //console.log(event.target.name);
        },
        clearAllError(){
            this.errors = {};
        },
    },
    computed: {
        vendor_cbo(){

           let group_id   = this.praprequest.group_id;
          let group = this.group_filter.find(x=>x.id == group_id);
          let company_id = group?group.company_id:"";
            var list = [];
            var item = {};

            this.options[0].id = company_id;
             this.options[0].label = this.$t('form.company_code')  +' '+ company_id;
             this.options[0].children = [];
            this.vendorAll.forEach(vendor => {

                item = {};
                if(vendor.company_id == company_id){
                      item.id = vendor.id;
                item.label = vendor.comp_name;
                list.push(item);
                this.options[0].children.push(item);
              }


            });

            return this.options;
        },
         vendors(){
          let group_id   = this.praprequest.group_id;
          let group = this.group_filter.find(x=>x.id == group_id);
          let company_id = group?group.company_id:"";
         // this.contract.vendor_id = "";
          return this.vendorAll.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
        vendor_count(){
            var list = this.listVendor();
            return list.length;
        },

        group_filter() {
            var list = [];
            let user = this.users.find(x => x.id == this.user_id);
            if (user) {
                list = user.groups;
                // this.praprequest.group_id = user.groups[0]['id'];
            }
            return list;
        },
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        }
    },

};
</script>

<style lang="scss" scoped>
.form-group {
    margin-bottom: 5px !important;
}
    .view {
      display: block;
    }
    .edit {
      display: none;
    }
    .editing .edit {
      display: block
    }
    .editing .view {
      display: none;
    }
</style>
