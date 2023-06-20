<template>
  <div>
    <div class="content-header ">
      <div class="container-fluid ml-0">
        <div class="row">
          <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
            <ol class="breadcrumb ">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/payment/requests">{{
                    $t(pre_title)
                }}</a> </h5>
              </li>

              <li class="breadcrumb-item active">
                <span v-if="edit_payrequest">{{ $t('form.edit') }}</span>
                <span v-else>{{ $t('form.create') }}</span>

              </li>
            </ol>
          </div>
          <div class="col-md-6">
            <div class="float-sm-right " style="margin-top:-5px; ">
              <a href="/payment/requests" class="btn btn-default ">{{ $t('form.cancel') }}</a>
              <button type="submit" :disabled="loading" class="btn btn-primary"> {{ $t('form.save') }}</button>
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
              <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>

            </ul>
          </div>
        </div>
        <div class="card card-default">
          <!-- <div class="card-header">
            </div> -->
          <!-- /.card-header -->
          <b-overlay :show="loading" rounded="sm">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.document_num') }}</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control form-control-sm" v-model="payrequest.serial_num"
                        readonly />

                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="proposer_payment"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.proposer_payment') }} <small
                        v-if="showValidate('proposer_payment')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">

                      <select class="form-control form-control-sm" style="width: 100%;" name="proposer_payment"
                        id="proposer_payment" :readonly="true" :disabled="true" v-model="payrequest.proposer_payment">
                        <option v-for="user in users" :key="user.id" v-bind:value="user.id">
                          {{ user.name }}
                        </option>

                      </select>
                      <div v-bind:class="hasError('proposer_payment') ? 'is-invalid' : ''">
                        <span v-if="hasError('proposer_payment')" class="invalid-feedback" role="alert">
                          <strong>{{ getError('proposer_payment') }}</strong>
                        </span>
                      </div>

                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="group_id" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.group') }}<small
                        v-if="showValidate('group_id')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select name="group_id" id="group_id" v-model="payrequest.group_id"
                        v-bind:class="hasError('group_id') ? 'is-invalid' : ''" required @click="clearError($event)"
                        @change="clearError($event)" class="form-control form-control-sm">
                        <option v-for="group in group_filter" v-bind:key="group.id" v-bind:value="group.id">
                          {{ group.company_id }}-{{ group.name }}</option>
                      </select>
                      <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('group_id') }}</strong>
                      </span>
                    </div>
                    <div class="col-md-2">
                      <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                    </div>

                  </div>
                  <!-- /.form-group -->
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.currency') }}<small
                        v-if="showValidate('currency')" class="text-red">( * )</small></label>
                    <div class="col-sm-2">
                      <select class="form-control form-control-sm" name="currency" id="currency"
                        v-model="payrequest.currency" v-bind:class="hasError('currency') ? 'is-invalid' : ''"
                        @change="sotienquidoi()">
                        <option v-for="cur in currencies" :key="cur.id" :value="cur.id">{{ cur.id }}</option>

                      </select>
                      <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('currency') }}</strong>

                      </span>
                    </div>
                    <label for="exchange_rate" v-show="show_exchanged_rate"
                      class="col-form-label-sm col-sm-2 col-form-label">{{ $t('form.exchanged_rate') }}<small
                        v-if="show_exchanged_rate" class="text-red">( * )</small></label>
                    <div class="col-sm-3">
                      <input type="text" style="text-align:right" v-show="show_exchanged_rate"
                        class="form-control form-control-sm" v-bind:class="hasError('exchange_rate') ? 'is-invalid' : ''"
                        name="exchange_rate" id="exchange_rate" @keyup="sotienquidoi()"
                        :required="show_exchanged_rate" />
                      <span v-if="hasError('exchange_rate')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('exchange_rate') }}</strong>
                      </span>

                    </div>

                  </div>
                  <div class="form-group row">
                    <label for="amount"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.suggested_amount') }}<small
                        v-if="showValidate('amount')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <div class="input-group">

                        <input type="text" style="text-align:right" :readonly="readOnly('amount')"
                          class="form-control form-control-sm" v-bind:class="hasError('amount') ? 'is-invalid' : ''"
                          name="amount" id="amount" @keyup="sotienquidoi()" @change="sotienquidoi()" required />
                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">{{ payrequest.currency }}</span>
                        </div>
                      </div>

                      <span v-if="hasError('amount')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('amount') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="amount_exchanged"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.amount_exchanged') }}</label>
                    <div class="col-sm-7">
                      <div class="input-group">
                        <input type="text" style="text-align:right" class="form-control form-control-sm" readonly
                          v-bind:class="hasError('amount_exchanged') ? 'is-invalid' : ''" name="amount_exchanged"
                          id="amount_exchanged" />
                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">VND</span>
                        </div>
                      </div>

                      <span v-if="hasError('amount_exchanged')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('amount_exchanged') }}</strong>
                      </span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="content" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.purpose') }}<small
                        v-if="showValidate('content')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input class="form-control form-control-sm" v-model="payrequest.content" rows="1" maxlength="255"
                        v-bind:class="hasError('content') ? 'is-invalid' : ''" name="content" id="content" required>
                      <span v-if="hasError('content')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('content') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">

                    <label for="money_receiver"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.unit_individual_receiving_money') }}<small
                        v-if="showValidate('money_receiver')" class="text-red">( * )</small></label>

                    <div class="col-sm-7">
                      <!-- <treeselect v-if="vendor_receiver"   :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('money_receiver')"  v-model="payrequest.vendor_id" :options="tree_vendors" ></treeselect> -->
                      <!-- <treeselect v-if="vendor_receiver"   :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('money_receiver')"  v-model="payrequest.vendor_id"
                        :options="tree_vendors"
                       :async="true"

                        :auto-load-root-options="true"
                       :load-options="loadOptions"></treeselect> -->

                      <div class="input-group-append">
                        <select v-if="vendor_receiver" class="form-control form-control-sm "
                          v-model="payrequest.vendor_id" :required="showValidate('money_receiver')"
                          style="width: 100%;">

                          <option v-for="vendor in vendors" v-bind:key="vendor.id" :value="vendor.id">
                            {{ vendor.comp_name }}</option>
                        </select>
                        <!-- <button v-if="vendor_receiver" class="btn btn-outline-secondary btn-sm" @click="popupContractSearch()"    type="button">...</button> -->
                        <button v-if="vendor_receiver" type="button" @click="ShowVenorDialog()"
                          :title="$t('form.select')" class="btn btn-secondary btn-sm">...</button>
                      </div>
                      <input v-if="!vendor_receiver" class="form-control form-control-sm"
                        v-model="payrequest.money_receiver" maxlength="255"
                        v-bind:class="hasError('money_receiver') ? 'is-invalid' : ''" name="money_receiver"
                        id="money_receiver" :required="showValidate('money_receiver')">
                      <span v-if="hasError('money_receiver')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('money_receiver') }}</strong>
                      </span>
                      <input type="checkbox" v-model="vendor_receiver" @change="resetVendorReceiver()">
                      <small>{{ $t('form.vendor') }}</small>
                    </div>


                  </div>
                  <div class="form-group row">
                    <label for="finish_date" class="col-form-label-sm col-sm-5 col-form-label">
                      <!-- {{$t('form.advance_payment_period')}} -->
                      <span v-html="showLabel('finish_date', $t('form.advance_payment_period'))"></span>
                      <small v-if="showValidate('finish_date')" class="text-red">( * )</small>
                    </label>
                    <div class="col-sm-7">
                      <input type="date" class="form-control form-control-sm" data-date="" data-date-format="DD/MM/YYYY"
                        v-model="payrequest.finish_date" v-bind:class="hasError('finish_date') ? 'is-invalid' : ''"
                        name="finish_date" id="finish_date" @click="clearError($event)" @change="clearError($event)"
                        placeholder="" />
                      <span v-if="hasError('finish_date')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('finish_date') }}</strong>
                      </span>
                    </div>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">

                  <div class="form-group row">
                    <label for="staticEmail"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.payment_method') }}<small
                        v-if="showValidate('method_payment')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select class="form-control form-control-sm " v-model="payrequest.method_payment"
                        :required="showValidate('money_receiver')" @change="resetBankInfo()" style="width: 100%;">
                        <option selected value="1">{{ $t('form.transfer') }}</option>
                        <option value="2">{{ $t('form.cash') }}</option>

                      </select>
                    </div>
                  </div>
                  <!-- /.form-group -->

                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_id" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.bank') }}<small
                        v-if="showValidate('bank_id')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <!-- <treeselect v-if="bank_input" :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('bank_id')"  v-model="payrequest.bank_id" :options="tree_banks" ></treeselect> -->
                      <select class="form-control form-control-sm" v-if="bank_input" name="bank_id" id="bank_id"
                        v-model="payrequest.bank_id" :required="showValidate('bank_id')"
                        v-bind:class="hasError('bank_id') ? 'is-invalid' : ''" @change="clearError($event)">
                        <option v-for="bank in banks" :key="bank.id" v-bind:value="bank.id">
                          {{ bank.name }}
                        </option>
                      </select>
                      <input type="text" maxlength="50" v-if="!bank_input" id="bank_name" name="bank_name"
                        class="form-control form-control-sm" :required="showValidate('bank_name')"
                        v-bind:class="hasError('bank_name') ? 'is-invalid' : ''" @change="clearError($event)"
                        v-model="payrequest.bank_name">
                      <input type="checkbox" v-model="bank_input" @change="resetBankInput()">
                      <small>{{ $t('form.bank_list') }}</small>
                      <span v-if="hasError('bank_id')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('bank_id') }}</strong>

                      </span>
                    </div>
                  </div>

                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_branch"
                      class="col-form-label-sm  col-sm-5 col-form-label">{{ $t('form.bank_branch') }}<small
                        v-if="showValidate('bank_branch')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input type="text" maxlength="50" id="bank_branch" name="bank_branch"
                        class="form-control form-control-sm" :required="showValidate('bank_branch')"
                        v-bind:class="hasError('bank_branch') ? 'is-invalid' : ''" @change="clearError($event)"
                        v-model="payrequest.bank_branch">
                      <span v-if="hasError('bank_branch')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('bank_branch') }}</strong>

                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_account"
                      class="col-form-label-sm  col-sm-5 col-form-label">{{ $t('form.bank_account_number') }}<small
                        v-if="showValidate('bank_account')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input type="text" id="bank_account" name="bank_account" class="form-control form-control-sm"
                        :required="showValidate('bank_account')" v-bind:class="hasError('bank_account') ? 'is-invalid' : ''"
                        @change="clearError($event)" v-model="payrequest.bank_account">
                      <span v-if="hasError('bank_account')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('bank_account') }}</strong>

                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="showControl('budget_type')">
                    <label for="budget_type"
                      class="col-form-label-sm  col-sm-5 col-form-label">{{ $t('form.budget') }}<small
                        v-if="showValidate('budget_type')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select name="budget_type" v-model="payrequest.budget_type" id="budget_type"
                        v-bind:class="hasError('budget_type') ? 'is-invalid' : ''" @click="clearError($event)"
                        @change="changeBudgetType()" :required="showValidate('budget_type')"
                        class="form-control form-control-sm">
                        <option v-for="budget_type in budget_types" v-bind:key="budget_type.id" :value="budget_type.id">
                          {{ $t(budget_type.name) }}</option>
                      </select>
                      <span v-if="hasError('budget_type')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('budget_type') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('out_budget') && payrequest.budget_type == 1">
                    <label for="out_budget" class="col-form-label-sm col-sm-5 col-form-label"> <small
                        v-if="showValidate('out_budget')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input type="checkbox" v-model="out_budget_chk" @change="resetOutBudget()"
                        :title="$t('form.out_budget')"> <small>{{ $t('form.out_budget') }}</small>


                    </div>
                  </div>
                  <div class="form-group row"
                    v-show="showControl('amount_out_budget') && out_budget_chk && payrequest.budget_type == 1">
                    <label for="amount"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.amount_out_budget') }}<small
                        v-if="showValidate('amount_out_budget')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <div class="input-group">

                        <input type="text" style="text-align:right" class="form-control form-control-sm"
                          v-bind:class="hasError('amount') ? 'is-invalid' : ''" name="amount_out_budget"
                          id="amount_out_budget" @keyup="sotienquidoi_vuotngansach()"
                          @change="sotienquidoi_vuotngansach()" />


                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">{{ payrequest.currency }}</span>
                        </div>
                      </div>

                      <span v-if="hasError('amount_out_budget')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('amount_out_budget') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row"
                    v-show="showControl('amount_out_exchanged') && out_budget_chk && payrequest.budget_type == 1">
                    <label for="amount_out_exchanged"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.amount_out_exchanged') }}</label>
                    <div class="col-sm-7">
                      <div class="input-group">
                        <input type="text" style="text-align:right" class="form-control form-control-sm" readonly
                          v-bind:class="hasError('amount_out_exchanged') ? 'is-invalid' : ''" name="amount_out_exchanged"
                          id="amount_out_exchanged" />
                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">VND</span>
                        </div>
                      </div>

                      <span v-if="hasError('amount_out_exchanged')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('amount_out_exchanged') }}</strong>
                      </span>
                    </div>
                  </div>

                  <div class="form-group row" v-if="showControl('budget_num') && payrequest.budget_type == 2">
                    <label for="budget_num"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.budget_code') }}<small
                        v-if="showValidate('budget_num')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input class="form-control form-control-sm" v-model="payrequest.budget_num"
                        :required="showValidate('budget_num') && payrequest.budget_type == 2"
                        v-bind:class="hasError('bugdet_num') ? 'is-invalid' : ''" name="bugdet_num" id="bugdet_num">
                      <span v-if="hasError('bugdet_num')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('bugdet_num') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="showControl('doc_reference')">
                    <label for="doc_reference"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.document_reference') }}<small
                        v-if="showValidate('doc_reference')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <input class="form-control form-control-sm" v-model="payrequest.doc_reference"
                        :required="showValidate('doc_reference')" maxlength="50"
                        v-bind:class="hasError('doc_reference') ? 'is-invalid' : ''" name="doc_reference"
                        id="doc_reference">
                      <span v-if="hasError('doc_reference')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('doc_reference') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="payment_type_id"
                      class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.type') }}<small
                        v-if="showValidate('payment_type_id')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select name="payment_type_id" v-model="payrequest.payment_type_id" id="payment_type_id"
                        v-bind:class="hasError('payment_type_id') ? 'is-invalid' : ''" @click="clearError($event)"
                        @change="clearError($event)" :required="showValidate('payment_type_id')"
                        class="form-control form-control-sm">
                        <option v-for="payment_type in paymentTypes" v-bind:key="payment_type.id"
                          :value="payment_type.id">{{ $t(payment_type.name) }}</option>
                      </select>
                      <span v-if="hasError('payment_type_id')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('payment_type_id') }}</strong>
                      </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="note" class="col-form-label-sm  col-sm-5 col-form-label">{{ $t('form.note') }}<small
                        v-if="showValidate('note')" class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <textarea type="text" id="note" name="note" class="form-control form-control-sm"
                        :required="showValidate('note')" v-bind:class="hasError('note') ? 'is-invalid' : ''"
                        @change="clearError($event)" v-model="payrequest.note" maxlength="150"></textarea>
                      <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                        <strong>{{ getError('note') }}</strong>

                      </span>
                    </div>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
              </div>
              <div class="row mt-1">
                <div class="col-md-12">

                  <b-card no-body>
                    <b-tabs card small>
                      <!-- <b-tab :title="$t('form.general_information')" active >

                         <div class="row mt-2 mb-2">
                          <div class="col-md-12">
                              <div class="form-group row">
                                <label for="group_id" class="col-form-label-sm">{{$t('form.group')}}<small v-if="showValidate('group_id')"  class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="group_id" id="group_id"
                                  v-model="payrequest.group_id"
                                    v-bind:class="hasError('group_id')?'is-invalid':''"

                                    @click="clearError($event)"
                                    @change="clearError($event)"
                                  class="form-control form-control-sm">
                                  <option v-for="group in group_filter" v-bind:key="group.id"   v-bind:value="group.id">{{group.name}}</option>
                                </select>
                                 <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('group_id')}}</strong>
                                </span>
                                </div>


                              </div>

                          </div>

                      </div>

                      </b-tab> -->
                      <b-tab :title="$t('form.advance_payment_vourcher')" v-if="showControl('payment_advance_moneys')">
                        <div class="form-group row">
                          <!-- <label for="staticEmail" class="col-form-label-sm  col-form-label">{{$t('form.document_num')}}</label> -->
                          <!-- <div class="col-sm-3">
                          <input type="text" class="form-control form-control-sm" v-model="requests_serial_num"  >
                          </div> -->
                          <!-- <button type="button" class="btn btn-success  btn-sm" @click.prevent="getTamUng()"><i class="fa fa-plus"></i> </button> -->
                          <button type="button" v-on:click="popupListTamUng()"
                            class="btn  btn-outline-secondary btn-sm ml-1">{{ $t('form.select') }} <i
                              class="fas fa-search"></i></button>
                        </div>
                        <b-table striped hover responsive :bordered="true" :sticky-header="false" small
                          :items="payrequest.payment_advance_moneys" selectable :fields="fields">

                          <template #cell(action)="data">
                            <a href="#" class="btn btn-sm text-red"
                              @click.prevent="deletePaymentAdvanceMoney(data.item)">
                              <i class="fas fa-trash"></i>
                            </a>
                          </template>
                          <template #cell(company_id)="data">

                            <span v-if="data.item.refer">{{ data.item.refer.company_id }}</span>
                            <span v-else>{{ data.value }}</span>
                          </template>

                        </b-table>

                      </b-tab>
                      <b-tab :title="$t('form.contract')" v-if="showControl('contract_term')">
                        <b-card-text>
                          <div class="row mt-2">
                            <div class="col-md-6 ">
                              <div class="form-group row">
                                <label for=""
                                  class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.contract_num') }}</label>
                                <div class="col-sm-7">
                                  <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="contract_num"
                                      v-model="contract.contract_num" :placeholder="$t('form.contract_num')">
                                    <div class="input-group-append">
                                      <button class="btn btn-outline-secondary btn-sm" @click="popupContractSearch()"
                                        type="button">...</button>
                                    </div>
                                  </div>

                                </div>
                              </div>
                              <!-- /.form-group -->
                              <div class="form-group row">
                                <label for="staticEmail"
                                  class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.contract_term') }}</label>
                                <div class="col-sm-7">
                                  <select class="form-control form-control-sm" style="width: 100%;" id="contract_term"
                                    name="contract_term" v-model="payrequest.contract_term_id"
                                    @change="change_contract_term($event)">
                                    <option v-for="(term) in contract.contract_terms" :key="term.id"
                                      v-bind:value="term.id">
                                      {{ term.terms_num }} / {{ contract.contract_terms.length }}
                                    </option>

                                  </select>
                                </div>
                              </div>

                              <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6 ">
                              <div class="form-group row">
                                <label for="staticEmail"
                                  class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.sign_date') }}</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control form-control-sm" readonly
                                    v-model="contract.sign_date">
                                </div>
                              </div>
                              <!-- /.form-group -->
                              <div class="form-group row">
                                <label for="staticEmail"
                                  class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.date_due') }}</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control form-control-sm" readonly
                                    v-model="contract_term.date_due">
                                </div>
                              </div>

                              <!-- /.form-group -->
                            </div>
                          </div>
                          <div class="row">

                            <label for="staticEmail" class="col-form-label-sm col-form-label">&nbsp;&nbsp;
                              {{ $t('form.term_of_payment') }}: </label>
                            <div class="col-sm-8 mt-1">
                              <span v-html="contract_term.term_content"></span>
                            </div>

                          </div>

                        </b-card-text>
                      </b-tab>
                      <b-tab :title="$t('form.payment_voucher')" v-if="showControl('payment_vouchers')">
                        <b-card-text>
                          <div class="row mt-2">
                            <div class="col-md-12">
                              <div class="d-flex justify-content-between">
                                <button class="btn   btn-outline-info  btn-sm mb-1"
                                  @click.prevent.stop="showPaymentVouchersEmpty()"><i class="fa fa-file"></i>
                                  {{ $t('form.add_doc') }}</button>
                                <span> {{ $t('form.sum') }} : {{ payment_voucher_total_amount.toLocaleString(locale_format)
                                }}</span>
                              </div>

                              <input type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                @input="emitEvent_voucher($event)" @change="emitEvent_voucher($event)"
                                id="ThemFileVoucher" style="display:none" ref="fileVoucher" multiple>

                              <table class="table table-bordered table-sm">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>{{ $t('form.content') }}</th>
                                    <th>{{ $t('form.attached_doc') }}</th>
                                    <th>{{ $t('form.amount') }}</th>
                                    <th>{{ $t('form.cost_center') }}</th>
                                    <th>{{ $t('form.gl_account') }}</th>
                                    <th>PR/PO</th>
                                    <th>File</th>
                                    <th>action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="(payment_voucher, index) in payrequest.payment_vouchers" v-bind:key="index">
                                    <td scope="row">{{ index + 1 }}</td>
                                    <td>{{ payment_voucher.content }}</td>
                                    <td>
                                      <span>{{ $t(getPaymentVoucherTypeName(payment_voucher.type)) }}:</span>
                                      <!-- <span v-if="payment_voucher.type==1">{{$t('form.bill')}}:</span>
                                      <span v-if="payment_voucher.type==2">{{$t('form.voucher')}}:</span>  -->
                                      {{ payment_voucher.doc_num }} - {{ $t('form.date') }}: {{ payment_voucher.doc_date |
                                          formatDate
                                      }}
                                    </td>
                                    <td>{{ payment_voucher.amount.toLocaleString(locale_format) }}
                                    </td>
                                    <td>
                                      {{ payment_voucher.costcenter }}
                                    </td>
                                    <td>
                                      {{ payment_voucher.gl_account }}
                                    </td>
                                    <td>
                                      {{ payment_voucher.prpo_type }}:
                                      {{ payment_voucher.prpo_num }}
                                    </td>
                                    <td>


                                      <div class="d-flex justify-content-between">
                                        <ul class="list-unstyled">
                                          <li v-for="(file, findex) in payment_voucher.attachment_file"
                                            v-bind:key="findex" class="itemfile">
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-default btn-xs">
                                                {{ file.name }}</button>
                                              <button type="button" class="btn btn-default btn-xs text-red"
                                                @click.prevent="deleteFile_vouchers(file, index, findex)"><i
                                                  class="far fa-times-circle "></i></button>
                                              <button type="button" v-if="file.id" class="btn btn-default btn-xs"
                                                @click.prevent="downloadFile(file.id, file.name)"><i
                                                  class="fas fa-download"></i></button>
                                            </div>
                                          </li>
                                        </ul>
                                        <div>
                                          <button type="button" title="chọn file" class="btn btn-xs btn-outline "
                                            v-on:click="handleClickInputFile_voucher(index)">
                                            <i title="chọn file" class="fas fa-paperclip"></i></button>

                                          <!-- <input type="button" v-on:click="handleClickInputFile(index)" value="..."> -->

                                        </div>
                                      </div>

                                    </td>
                                    <td>
                                      <a href="#" class="btn btn-sm text-info"
                                        @click.prevent="editPaymentVoucher(payment_voucher, index)">
                                        <i class="fas fa-pencil-alt"></i>
                                      </a>
                                      <a href="#" class="btn btn-sm text-red"
                                        @click.prevent="deletePaymentVoucher(payment_voucher, index)">
                                        <i class="fas fa-trash"></i>
                                      </a>
                                    </td>
                                  </tr>

                                </tbody>
                              </table>
                            </div>
                          </div>

                        </b-card-text>
                      </b-tab>
                      <b-tab :title="$t('form.attached_doc')" v-if="showControl('payment_attacheds')">
                        <b-card-text>
                          <div class="row mt-2 mb-2">
                            <div class="col-md-12">
                              <!-- <button class="btn btn-outline-info btn-sm mb-2 " @click.prevent.stop="popupPaycatsetSearch()" ><i class="fa fa-file" ></i> Bộ danh mục chứng từ thanh toán</button> -->
                              <input type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                                @input="emitEvent_attached($event)" @change="emitEvent_attached($event)" id="ThemFile"
                                style="display:none" ref="file" multiple>
                              <table class="table table-bordered table-sm">
                                <thead>
                                  <tr>
                                    <th style="width:100px">#</th>
                                    <th style="width:250px">{{ $t('form.name') }}</th>
                                    <th>File</th>
                                    <th style="width:150px">{{ $t('form.note') }}</th>

                                    <th style="width: 5px;"></th>
                                  </tr>
                                </thead>
                                <tbody>

                                  <tr v-for="(payment_attached, index) in payrequest.payment_attacheds"
                                    v-bind:key="index">
                                    <td scope="row">{{ index + 1 }}</td>
                                    <td @click.prevent.stop="changeGridEdit($event)">
                                      <!-- {{payment_attached.name}}  -->
                                      <!-- <div class="cellgrid" style="display:inlin-block"   > -->
                                      <span
                                        @click.prevent.stop="changeGridViewToEdit($event)">{{ payment_attached.name }}</span>
                                      <input type="text" maxlength="50" @blur.prevent.self="changeGridView($event)"
                                        v-model="payment_attached.name" class="form-control" placeholder="..."
                                        style="display:none">
                                      <!-- </div> -->

                                    </td>
                                    <td>

                                      <div class="d-flex justify-content-between">
                                        <ul class="list-unstyled">
                                          <li v-for="(file, findex) in payment_attached.attachment_file"
                                            v-bind:key="findex" class="itemfile">
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-default btn-xs">
                                                {{ file.name }}</button>
                                              <button type="button" class="btn btn-default btn-xs text-red"
                                                @click.prevent="deleteFile(file, index, findex)"><i
                                                  class="far fa-times-circle "></i></button>
                                              <button type="button" v-if="file.id" class="btn btn-default btn-xs "
                                                @click.prevent="downloadFile(file.id, file.name)"><i
                                                  class="fas fa-download"></i></button>
                                            </div>
                                          </li>
                                        </ul>
                                        <div>
                                          <button type="button" :title="$t('form.choose_file')"
                                            class="btn btn-xs btn-outline " v-on:click="handleClickInputFile(index)">
                                            <i :title="$t('form.choose_file')" class="fas fa-paperclip"></i></button>

                                          <!-- <input type="button" v-on:click="handleClickInputFile(index)" value="..."> -->

                                        </div>
                                      </div>
                                    </td>
                                    <td @click="changeGridEdit($event)">
                                      <!-- <div class="d-flex justify-content-between"> -->
                                      <span @click="changeGridViewToEdit($event)">{{ payment_attached.note }}</span>
                                      <!-- <button  type="button" title="thêm ghi chú" @click="showDialogAddNote(index)" class="btn btn-sm btn-outline" ><i title="thêm ghi chú" class="far fa-sticky-note"></i></button> -->
                                      <input style="display:none" @blur.prevent.self="changeGridView($event)"
                                        v-model="payment_attached.note" type="text" class="form-control">
                                      <!-- </div> -->
                                    </td>
                                    <!-- <td style="text-align: center;"><div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">

                                </div>
                                </td> -->
                                    <td>
                                      <button type="button" :title="$t('form.delete')"
                                        class="btn btn-sm btn-outline text-red"
                                        v-on:click="deleteInputFile(payment_attached, index)">
                                        <i title="Xoá" class="fas fa-trash"></i></button>

                                      <!-- <span  class="text-red"><i class="fa fa-trash"></i></span> -->
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <a href="#" @click.prevent.stop="AddNewRow()"> <i class="fa fa-plus-circle"></i>
                                        <i> {{ $t('form.new_row') }} </i> </a>
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
          </b-overlay>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>
</style>
