
<template>
<div>
   <form @submit.prevent="AddPayrequest"  @keydown.enter.prevent="clearError" >
    <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/payment/requests">{{ $t(pre_title)}}</a> </h5></li>

                <li class="breadcrumb-item active">
                  <span v-if="edit_payrequest">{{ $t('form.edit')}}</span>
                  <span v-else>{{ $t('form.create')}}</span>

                  </li>
             </ol>
            </div>
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                      <a href="/payment/requests" class="btn btn-default ">{{ $t('form.cancel')}}</a>
                        <button type="submit"   :disabled="loading"  class="btn btn-primary"> {{ $t('form.save')}}</button>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row">

    <div class="col-md-12">
            <div v-if="hasAnyError >0">

                <div class="alert alert-warning">
                    <button type="button" class="close" @click.prevent="clearAllError()">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <ul>
                    <li v-for="(err,index) in errors" v-bind:key="index">{{err}}</li>

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
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{ $t('form.document_num')}}</label>
                    <div class="col-sm-7">
                     <input type="text" class="form-control form-control-sm"
                          v-model="payrequest.serial_num"
                          readonly
                              />

                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="proposer_payment" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.proposer_payment')}} <small v-if="showValidate('proposer_payment')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">

                      <select class="form-control form-control-sm" style="width: 100%;"
                      name="proposer_payment"
                      id="proposer_payment"
                      :readonly="true"
                      :disabled="true"
                      v-model="payrequest.proposer_payment"
                      >
                        <option
                                v-for="user in users"
                                :key="user.id"
                                v-bind:value="user.id"
                            >
                                {{ user.name }}
                            </option>

                      </select>
                      <div  v-bind:class="hasError('proposer_payment')?'is-invalid':''">
                         <span v-if="hasError('proposer_payment')" class="invalid-feedback" role="alert">
                              <strong>{{getError('proposer_payment')}}</strong>
                          </span>
                      </div>

                    </div>
                  </div>
                   <div class="form-group row">
                        <label for="group_id" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.group')}}<small v-if="showValidate('group_id')"  class="text-red">( * )</small></label>
                        <div class="col-sm-7">
                            <select name="group_id" id="group_id"
                            v-model="payrequest.group_id"
                            v-bind:class="hasError('group_id')?'is-invalid':''"
                            required
                            @click="clearError($event)"
                            @change="clearError($event)"
                            class="form-control form-control-sm">
                            <option v-for="group in group_filter" v-bind:key="group.id"   v-bind:value="group.id">{{group.company_id}}-{{group.name}}</option>
                        </select>
                            <span v-if="hasError('group_id')" class="invalid-feedback" role="alert">
                            <strong>{{getError('group_id')}}</strong>
                        </span>
                        </div>
                            <div class="col-md-2">
                            <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                            </div>

                        </div>
                  <!-- /.form-group -->
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.currency')}}<small v-if="showValidate('currency')"  class="text-red">( * )</small></label>
                    <div class="col-sm-2">
                    <select class="form-control form-control-sm"
                          name="currency"
                          id="currency"
                          v-model="payrequest.currency"
                          v-bind:class="hasError('currency')?'is-invalid':''"
                          @change="sotienquidoi()"
                          >
                             <option v-for="cur in currencies" :key="cur.id" :value="cur.id">{{cur.id}}</option>

                          </select>
                        <span v-if="hasError('currency')" class="invalid-feedback" role="alert">
                                      <strong>{{getError('currency')}}</strong>

                          </span>
                  </div>
                     <label for="exchange_rate" v-show="show_exchanged_rate"
                     class="col-form-label-sm col-sm-2 col-form-label">{{$t('form.exchanged_rate')}}<small v-if="show_exchanged_rate"  class="text-red">( * )</small></label>
                     <div class="col-sm-3">
                       <input type="text" style="text-align:right" v-show="show_exchanged_rate" class="form-control form-control-sm"
                          v-bind:class="hasError('exchange_rate')?'is-invalid':''"
                              name="exchange_rate"
                              id="exchange_rate"

                               @keyup="sotienquidoi()"
                               :required="show_exchanged_rate"
                              />
                            <span v-if="hasError('exchange_rate')" class="invalid-feedback" role="alert">
                              <strong>{{getError('exchange_rate')}}</strong>
                          </span>

                     </div>

                  </div>
                  <div class="form-group row">
                    <label for="amount" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.suggested_amount')}}<small v-if="showValidate('amount')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <div class="input-group">

                        <input type="text" style="text-align:right" :readonly="readOnly('amount')"   class="form-control form-control-sm"
                          v-bind:class="hasError('amount')?'is-invalid':''"
                              name="amount"
                              id="amount"
                              @keyup="sotienquidoi()"

                              @change="sotienquidoi()"
                              required
                              />
                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">{{payrequest.currency}}</span>
                        </div>
                      </div>

                            <span v-if="hasError('amount')" class="invalid-feedback" role="alert">
                              <strong>{{getError('amount')}}</strong>
                          </span>
                    </div>
                  </div>
                 <div class="form-group row">
                    <label for="amount_exchanged" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.amount_exchanged')}}</label>
                    <div class="col-sm-7">
                       <div class="input-group">
                        <input type="text" style="text-align:right"  class="form-control form-control-sm"
                          readonly

                          v-bind:class="hasError('amount_exchanged')?'is-invalid':''"
                              name="amount_exchanged"
                              id="amount_exchanged"

                              />
                          <div class="input-group-append">
                            <span class="input-group-text form-control-sm">VND</span>
                          </div>
                       </div>

                            <span v-if="hasError('amount_exchanged')" class="invalid-feedback" role="alert">
                              <strong>{{getError('amount_exchanged')}}</strong>
                          </span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="content" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.purpose')}}<small v-if="showValidate('content')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input class="form-control form-control-sm" v-model="payrequest.content" rows="1" maxlength="255"
                          v-bind:class="hasError('content')?'is-invalid':''"
                              name="content"
                              id="content"
                              required
                          >
                            <span v-if="hasError('content')" class="invalid-feedback" role="alert">
                              <strong>{{getError('content')}}</strong>
                          </span>
                    </div>
                  </div>
                  <div class="form-group row">

                    <label for="money_receiver" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.unit_individual_receiving_money')}}<small v-if="showValidate('money_receiver')"  class="text-red">( * )</small></label>

                    <div class="col-sm-7">
                    <!-- <treeselect v-if="vendor_receiver"   :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('money_receiver')"  v-model="payrequest.vendor_id" :options="tree_vendors" ></treeselect> -->
                    <!-- <treeselect v-if="vendor_receiver"   :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('money_receiver')"  v-model="payrequest.vendor_id"
                        :options="tree_vendors"
                       :async="true"

                        :auto-load-root-options="true"
                       :load-options="loadOptions"></treeselect> -->

                     <div class="input-group-append">
                          <select  v-if="vendor_receiver" class="form-control form-control-sm "
                            v-model="payrequest.vendor_id"
                                :required="showValidate('money_receiver')"

                            style="width: 100%;">

                                <option v-for="vendor in vendors" v-bind:key="vendor.id" :value="vendor.id" >{{vendor.comp_name}}</option>
                        </select>
                           <!-- <button v-if="vendor_receiver" class="btn btn-outline-secondary btn-sm" @click="popupContractSearch()"    type="button">...</button> -->
                           <button v-if="vendor_receiver" type="button" @click="ShowVenorDialog()" :title="$t('form.select')" class="btn btn-secondary btn-sm">...</button>
                                  </div>
                    <input v-if="!vendor_receiver" class="form-control form-control-sm" v-model="payrequest.money_receiver"   maxlength="255"
                          v-bind:class="hasError('money_receiver')?'is-invalid':''"
                              name="money_receiver"
                              id="money_receiver"
                              :required="showValidate('money_receiver')"
                          >
                            <span v-if="hasError('money_receiver')" class="invalid-feedback" role="alert">
                              <strong>{{getError('money_receiver')}}</strong>
                          </span>
                           <input type="checkbox" v-model="vendor_receiver" @change="resetVendorReceiver()"> <small>{{$t('form.vendor')}}</small>
                    </div>


                  </div>
                  <div class="form-group row">
                    <label for="finish_date" class="col-form-label-sm col-sm-5 col-form-label">
                      <!-- {{$t('form.advance_payment_period')}} -->
                      <span v-html="showLabel('finish_date',$t('form.advance_payment_period'))"></span>
                      <small v-if="showValidate('finish_date')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input type="date"  class="form-control form-control-sm" data-date="" data-date-format="DD/MM/YYYY"
                            v-model="payrequest.finish_date"
                            v-bind:class="hasError('finish_date')?'is-invalid':''"
                              name="finish_date"
                              id="finish_date"
                              @click="clearError($event)"
                              @change="clearError($event)"
                              placeholder="" />
                            <span v-if="hasError('finish_date')" class="invalid-feedback" role="alert">
                              <strong>{{getError('finish_date')}}</strong>
                          </span>
                    </div>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">

                  <div class="form-group row">
                    <label for="staticEmail" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.payment_method')}}<small v-if="showValidate('method_payment')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select class="form-control form-control-sm "
                        v-model="payrequest.method_payment"
                          :required="showValidate('money_receiver')"
                          @change="resetBankInfo()"
                       style="width: 100%;">
                        <option selected value="1">{{$t('form.transfer')}}</option>
                        <option value="2">{{$t('form.cash')}}</option>

                      </select>
                    </div>
                  </div>
                  <!-- /.form-group -->

                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_id" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.bank')}}<small v-if="showValidate('bank_id')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <!-- <treeselect v-if="bank_input" :disable-branch-nodes="true" :placeholder="$t('form.select')"   :required="showValidate('bank_id')"  v-model="payrequest.bank_id" :options="tree_banks" ></treeselect> -->
                    <select class="form-control form-control-sm" v-if="bank_input"
                          name="bank_id"
                          id="bank_id"
                          v-model="payrequest.bank_id"
                            :required="showValidate('bank_id')"
                          v-bind:class="hasError('bank_id')?'is-invalid':''"
                          @change="clearError($event)"
                          >
                            <option
                                  v-for="bank in banks"
                                  :key="bank.id"
                                  v-bind:value="bank.id"
                              >
                                  {{ bank.name }}
                            </option>
                          </select>
                          <input type="text"  maxlength="50" v-if="!bank_input"
                            id="bank_name"
                            name="bank_name"
                            class="form-control form-control-sm"
                              :required="showValidate('bank_name')"
                                v-bind:class="hasError('bank_name')?'is-invalid':''"
                                  @change="clearError($event)"
                            v-model="payrequest.bank_name">
                          <input type="checkbox" v-model="bank_input" @change="resetBankInput()"> <small>{{$t('form.bank_list')}}</small>
                        <span v-if="hasError('bank_id')" class="invalid-feedback" role="alert">
                                      <strong>{{getError('bank_id')}}</strong>

                          </span>
                    </div>
                  </div>

                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_branch" class="col-form-label-sm  col-sm-5 col-form-label">{{$t('form.bank_branch')}}<small v-if="showValidate('bank_branch')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input type="text" maxlength="50"
                    id="bank_branch"
                    name="bank_branch"
                    class="form-control form-control-sm"
                      :required="showValidate('bank_branch')"
                        v-bind:class="hasError('bank_branch')?'is-invalid':''"
                          @change="clearError($event)"
                    v-model="payrequest.bank_branch">
                     <span v-if="hasError('bank_branch')" class="invalid-feedback" role="alert">
                            <strong>{{getError('bank_branch')}}</strong>

                          </span>
                    </div>
                  </div>
                  <div class="form-group row" v-if="payrequest.method_payment == 1">
                    <label for="bank_account" class="col-form-label-sm  col-sm-5 col-form-label">{{$t('form.bank_account_number')}}<small v-if="showValidate('bank_account')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input type="text"
                    id="bank_account"
                    name="bank_account"
                    class="form-control form-control-sm"
                      :required="showValidate('bank_account')"
                        v-bind:class="hasError('bank_account')?'is-invalid':''"
                          @change="clearError($event)"
                    v-model="payrequest.bank_account">
                     <span v-if="hasError('bank_account')" class="invalid-feedback" role="alert">
                            <strong>{{getError('bank_account')}}</strong>

                          </span>
                    </div>
                  </div>
                   <div class="form-group row" v-if="showControl('budget_type')">
                    <label for="budget_type" class="col-form-label-sm  col-sm-5 col-form-label">{{$t('form.budget')}}<small v-if="showValidate('budget_type')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <select name="budget_type" v-model="payrequest.budget_type"
                       id="budget_type"
                        v-bind:class="hasError('budget_type')?'is-invalid':''"
                          @click="clearError($event)"
                          @change="changeBudgetType()"
                          :required="showValidate('budget_type')"

                        class="form-control form-control-sm">
                        <option v-for="budget_type in budget_types" v-bind:key="budget_type.id" :value="budget_type.id"    >{{$t(budget_type.name)}}</option>
                      </select>
                       <span v-if="hasError('budget_type')" class="invalid-feedback" role="alert">
                            <strong>{{getError('budget_type')}}</strong>
                        </span>
                    </div>
                  </div>
                   <div class="form-group row"  v-show="showControl('out_budget') && payrequest.budget_type == 1"  >
                    <label for="out_budget" class="col-form-label-sm col-sm-5 col-form-label"> <small v-if="showValidate('out_budget')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input type="checkbox" v-model="out_budget_chk" @change="resetOutBudget()" :title="$t('form.out_budget')"> <small>{{$t('form.out_budget')}}</small>


                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('amount_out_budget') && out_budget_chk && payrequest.budget_type == 1" >
                    <label for="amount" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.amount_out_budget')}}<small v-if="showValidate('amount_out_budget')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                      <div class="input-group">

                          <input type="text" style="text-align:right"    class="form-control form-control-sm"
                          v-bind:class="hasError('amount')?'is-invalid':''"
                              name="amount_out_budget"
                              id="amount_out_budget"
                              @keyup="sotienquidoi_vuotngansach()"
                              @change="sotienquidoi_vuotngansach()"

                              />


                        <div class="input-group-append">
                          <span class="input-group-text form-control-sm">{{payrequest.currency}}</span>
                        </div>
                      </div>

                            <span v-if="hasError('amount_out_budget')" class="invalid-feedback" role="alert">
                              <strong>{{getError('amount_out_budget')}}</strong>
                          </span>
                    </div>
                  </div>
                  <div class="form-group row" v-show="showControl('amount_out_exchanged') && out_budget_chk  && payrequest.budget_type == 1" >
                    <label for="amount_out_exchanged" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.amount_out_exchanged')}}</label>
                    <div class="col-sm-7">
                       <div class="input-group">
                        <input type="text" style="text-align:right"  class="form-control form-control-sm"
                          readonly

                          v-bind:class="hasError('amount_out_exchanged')?'is-invalid':''"
                              name="amount_out_exchanged"
                              id="amount_out_exchanged"

                              />
                          <div class="input-group-append">
                            <span class="input-group-text form-control-sm">VND</span>
                          </div>
                       </div>

                            <span v-if="hasError('amount_out_exchanged')" class="invalid-feedback" role="alert">
                              <strong>{{getError('amount_out_exchanged')}}</strong>
                          </span>
                    </div>
                  </div>

                  <div class="form-group row"  v-if="showControl('budget_num') && payrequest.budget_type == 2"  >
                    <label for="budget_num" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.budget_code')}}<small v-if="showValidate('budget_num')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input class="form-control form-control-sm"
                     v-model="payrequest.budget_num"
                       :required="showValidate('budget_num')  && payrequest.budget_type == 2"
                          v-bind:class="hasError('bugdet_num')?'is-invalid':''"
                              name="bugdet_num"
                              id="bugdet_num"

                          >
                            <span v-if="hasError('bugdet_num')" class="invalid-feedback" role="alert">
                              <strong>{{getError('bugdet_num')}}</strong>
                          </span>
                    </div>
                  </div>
                  <div class="form-group row"  v-if="showControl('doc_reference')">
                    <label for="doc_reference" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.document_reference')}}<small v-if="showValidate('doc_reference')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <input class="form-control form-control-sm"
                    v-model="payrequest.doc_reference"
                    :required="showValidate('doc_reference')"
                    maxlength="50"
                          v-bind:class="hasError('doc_reference')?'is-invalid':''"
                              name="doc_reference"
                              id="doc_reference"

                          >
                            <span v-if="hasError('doc_reference')" class="invalid-feedback" role="alert">
                              <strong>{{getError('doc_reference')}}</strong>
                          </span>
                    </div>
                  </div>
                   <div class="form-group row"   >
                    <label for="payment_type_id" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.type')}}<small v-if="showValidate('payment_type_id')"  class="text-red">( * )</small></label>
                      <div class="col-sm-7">
                      <select name="payment_type_id" v-model="payrequest.payment_type_id"
                       id="payment_type_id"
                        v-bind:class="hasError('payment_type_id')?'is-invalid':''"
                          @click="clearError($event)"
                          @change="clearError($event)"
                          :required="showValidate('payment_type_id')"
                        class="form-control form-control-sm">
                        <option v-for="payment_type in paymentTypes" v-bind:key="payment_type.id" :value="payment_type.id"    >{{$t(payment_type.name)}}</option>
                      </select>
                       <span v-if="hasError('payment_type_id')" class="invalid-feedback" role="alert">
                            <strong>{{getError('payment_type_id')}}</strong>
                        </span>
                    </div>
                  </div>
                  <div class="form-group row" >
                    <label for="note" class="col-form-label-sm  col-sm-5 col-form-label">{{$t('form.note')}}<small v-if="showValidate('note')"  class="text-red">( * )</small></label>
                    <div class="col-sm-7">
                    <textarea type="text"
                    id="note"
                    name="note"
                    class="form-control form-control-sm"
                      :required="showValidate('note')"
                        v-bind:class="hasError('note')?'is-invalid':''"
                          @change="clearError($event)"
                    v-model="payrequest.note" maxlength="150"></textarea>
                     <span v-if="hasError('note')" class="invalid-feedback" role="alert">
                            <strong>{{getError('note')}}</strong>

                          </span>
                    </div>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
              </div>
              <div class="row mt-1">
                <div class="col-md-12">

                  <b-card no-body  >
                    <b-tabs card small  >
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
                      <b-tab :title="$t('form.advance_payment_vourcher')" v-if="showControl('payment_advance_moneys')" >
                         <div class="form-group row">
                          <!-- <label for="staticEmail" class="col-form-label-sm  col-form-label">{{$t('form.document_num')}}</label> -->
                          <!-- <div class="col-sm-3">
                          <input type="text" class="form-control form-control-sm" v-model="requests_serial_num"  >
                          </div> -->
                          <!-- <button type="button" class="btn btn-success  btn-sm" @click.prevent="getTamUng()"><i class="fa fa-plus"></i> </button> -->
                          <button type="button" v-on:click="popupListTamUng()" class="btn  btn-outline-secondary btn-sm ml-1"   >{{$t('form.select')}}   <i class="fas fa-search"></i></button>
                        </div>
                        <b-table striped hover responsive :bordered="true"   :sticky-header="false"   small
                           :items="payrequest.payment_advance_moneys"
                             selectable

                           :fields="fields">

                           <template #cell(action)="data">
                              <a href="#" class="btn btn-sm text-red"  @click.prevent="deletePaymentAdvanceMoney(data.item)">
                                      <i class="fas fa-trash"></i>
                                    </a>
                            </template>
                            <template  #cell(company_id)="data">

                                <span v-if="data.item.refer" >{{data.item.refer.company_id}}</span>
                                <span v-else >{{data.value}}</span>
                            </template>

                      </b-table>

                      </b-tab>
                      <b-tab :title="$t('form.contract')" v-if="showControl('contract_term')" >
                        <b-card-text>
                        <div class="row mt-2">
                          <div class="col-md-6 ">
                            <div class="form-group row">
                              <label for="" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.contract_num')}}</label>
                              <div class="col-sm-7">
                              <div class="input-group">
                                  <input type="text" class="form-control form-control-sm" id="contract_num" v-model="contract.contract_num" :placeholder="$t('form.contract_num')"   >
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm" @click="popupContractSearch()"    type="button">...</button>
                                  </div>
                              </div>

                              </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group row" >
                              <label for="staticEmail" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.contract_term')}}</label>
                              <div class="col-sm-7">
                                <select class="form-control form-control-sm"
                                style="width: 100%;"
                                id="contract_term"
                                name="contract_term"
                                v-model="payrequest.contract_term_id"
                                @change="change_contract_term($event)"

                                >
                                  <option
                                            v-for="(term) in contract.contract_terms"
                                            :key="term.id"
                                            v-bind:value="term.id"
                                        >
                                            {{ term.terms_num }} / {{contract.contract_terms.length}}
                                      </option>

                                </select>
                              </div>
                            </div>

                            <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                            <div class="col-md-6 ">
                              <div class="form-group row">
                                <label for="staticEmail" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.sign_date')}}</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control form-control-sm" readonly v-model="contract.sign_date" >
                                </div>
                              </div>
                              <!-- /.form-group -->
                              <div class="form-group row">
                                <label for="staticEmail" class="col-form-label-sm col-sm-5 col-form-label">{{$t('form.date_due')}}</label>
                                <div class="col-sm-7">
                                <input type="date" class="form-control form-control-sm" readonly v-model="contract_term.date_due">
                                </div>
                              </div>

                              <!-- /.form-group -->
                            </div>
                        </div>
                        <div class="row">

                          <label for="staticEmail" class="col-form-label-sm col-form-label">&nbsp;&nbsp; {{$t('form.term_of_payment')}}: </label>
                           <div class="col-sm-8 mt-1">
                                 <span v-html="contract_term.term_content"></span>
                           </div>

                        </div>

                        </b-card-text>
                      </b-tab>
                      <b-tab :title="$t('form.payment_voucher')" v-if="showControl('payment_vouchers')" >
                        <b-card-text>
                        <div class="row mt-2">
                          <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                               <button class="btn   btn-outline-info  btn-sm mb-1" @click.prevent.stop="showPaymentVouchersEmpty()" ><i class="fa fa-file"></i> {{$t('form.add_doc')}}</button>
                               <span> {{$t('form.sum')}} : {{payment_voucher_total_amount.toLocaleString(locale_format) }}</span>
                            </div>

                            <input type="file"
                              accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.msg,.eml"
                                @input="emitEvent_voucher($event)"
                                @change="emitEvent_voucher($event)"
                                id="ThemFileVoucher"
                                style="display:none"
                                ref="fileVoucher"
                              multiple

                                >

                            <table class="table table-bordered table-sm">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>{{$t('form.content')}}</th>
                                  <th>{{$t('form.attached_doc')}}</th>
                                  <th>{{$t('form.amount')}}</th>
                                  <th>{{$t('form.cost_center')}}</th>
                                  <th>{{$t('form.gl_account')}}</th>
                                  <th>PR/PO</th>
                                  <th>File</th>
                                  <th>action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(payment_voucher,index) in payrequest.payment_vouchers" v-bind:key="index">
                                  <td scope="row">{{index+1}}</td>
                                  <td>{{payment_voucher.content}}</td>
                                  <td>
                                     <span >{{$t(getPaymentVoucherTypeName(payment_voucher.type))}}:</span>
                                      <!-- <span v-if="payment_voucher.type==1">{{$t('form.bill')}}:</span>
                                      <span v-if="payment_voucher.type==2">{{$t('form.voucher')}}:</span>  -->
                                      {{payment_voucher.doc_num}} - {{$t('form.date')}}: {{payment_voucher.doc_date | formatDate}}
                                  </td>
                                  <td>{{payment_voucher.amount.toLocaleString(locale_format) }}
                                  </td>
                                  <td>
                                      {{payment_voucher.costcenter}}
                                  </td>
                                   <td>
                                      {{payment_voucher.gl_account}}
                                  </td>
                                  <td>
                                      {{payment_voucher.prpo_type}}:
                                      {{payment_voucher.prpo_num}}
                                  </td>
                                  <td>


                                    <div class="d-flex justify-content-between">
                                      <ul class="list-unstyled">
                                        <li v-for="(file,findex) in payment_voucher.attachment_file" v-bind:key="findex" class="itemfile">
                                          <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-xs"> {{file.name}}</button>
                                          <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteFile_vouchers(file,index,findex)" ><i class="far fa-times-circle "></i></button>
                                          <button type="button" v-if="file.id" class="btn btn-default btn-xs" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></button>
                                        </div>
                                        </li>
                                      </ul>
                                      <div>
                                        <button type="button"  title="chọn file" class="btn btn-xs btn-outline " v-on:click="handleClickInputFile_voucher(index)">
                                          <i  title="chọn file" class="fas fa-paperclip"></i></button>

                                        <!-- <input type="button" v-on:click="handleClickInputFile(index)" value="..."> -->

                                      </div>
                                    </div>

                                  </td>
                                  <td>
                                    <a href="#" class="btn btn-sm text-info" @click.prevent="editPaymentVoucher(payment_voucher,index)">
                                      <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm text-red"  @click.prevent="deletePaymentVoucher(payment_voucher,index)">
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
                       <b-tab :title="$t('form.attached_doc')"  v-if="showControl('payment_attacheds')" >
                        <b-card-text>
                      <div class="row mt-2 mb-2">
                        <div class="col-md-12">
                           <!-- <button class="btn btn-outline-info btn-sm mb-2 " @click.prevent.stop="popupPaycatsetSearch()" ><i class="fa fa-file" ></i> Bộ danh mục chứng từ thanh toán</button> -->
                          <input type="file"
                          accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                            @input="emitEvent_attached($event)"
                            @change="emitEvent_attached($event)"
                            id="ThemFile"
                            style="display:none"
                            ref="file"
                          multiple

                            >
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

                              <tr v-for="(payment_attached,index) in payrequest.payment_attacheds" v-bind:key="index">
                                <td scope="row" >{{index+1}}</td>
                                <td @click.prevent.stop="changeGridEdit($event)">
                                  <!-- {{payment_attached.name}}  -->
                                  <!-- <div class="cellgrid" style="display:inlin-block"   > -->
                                   <span @click.prevent.stop="changeGridViewToEdit($event)" >{{payment_attached.name}}</span>
                                   <input type="text"  maxlength="50" @blur.prevent.self="changeGridView($event)"     v-model="payment_attached.name" class="form-control" placeholder="..."  style="display:none">
                                  <!-- </div> -->

                                </td>
                                <td >

                                  <div class="d-flex justify-content-between">
                                    <ul class="list-unstyled">
                                      <li v-for="(file,findex) in payment_attached.attachment_file" v-bind:key="findex" class="itemfile">
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
                                      <span @click="changeGridViewToEdit($event)" >{{payment_attached.note}}</span>
                                    <!-- <button  type="button" title="thêm ghi chú" @click="showDialogAddNote(index)" class="btn btn-sm btn-outline" ><i title="thêm ghi chú" class="far fa-sticky-note"></i></button> -->
                                    <input style="display:none" @blur.prevent.self="changeGridView($event)"  v-model="payment_attached.note"   type="text"    class="form-control" >
                                    <!-- </div> -->
                                </td>
                                <!-- <td style="text-align: center;"><div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">

                                </div>
                                </td> -->
                                <td>
                                      <button type="button"  :title="$t('form.delete')" class="btn btn-sm btn-outline text-red" v-on:click="deleteInputFile(payment_attached,index)">
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
    <dialogContractSearch v-on:selectedItem="selectedItem"></dialogContractSearch>
    <dialogPaycatsetSearch v-on:selectedPaycatset="selectedPaycatset"></dialogPaycatsetSearch>

     <!-- Modal dialog -->
       <div class="modal fade" id="themchungtuthanhtoan" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="themchungtuthanhtoan" aria-hidden="true">
          <div class="modal-dialog" role="document">
             <form action=""    @submit.prevent.enter="AddPaymentVoucher()"  @keydown.enter.prevent="clearError" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$t('form.payment_voucher')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="form-group row">
                    <label for="payment_voucher_type" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.type')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                      <!-- <select class="form-control form-control-sm" required v-model="payment_voucher.type" name="" id="payment_voucher_type"  >
                      <option value="1" selected>{{$t('form.bill')}}</option>
                      <option value="2">{{$t('form.voucher')}}</option>
                    </select> -->
                     <select name="payment_voucher_type" v-model="payment_voucher.type"
                       id="payment_voucher_type"
                          required
                        class="form-control form-control-sm">
                        <option v-for="payment_vourcher_type in paymentVourcherTypes" v-bind:key="payment_vourcher_type.id" :value="payment_vourcher_type.id"    >{{$t(payment_vourcher_type.name)}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="voucher_doc_num" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.number')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                       <input type="text"  required v-model="payment_voucher.doc_num" class="form-control form-control-sm"  id="voucher_doc_num" >
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="voucher_date"  class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.date')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                        <input type="date" required v-model="payment_voucher.doc_date" id="voucher_date" class="form-control form-control-sm"     >
                    </div>
                  </div>
                 <div class="form-group row">
                    <label for="voucher_amount" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.amount')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                         <input type="text" required  class="form-control form-control-sm"  id="voucher_amount"  >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="voucher_costcenter" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.cost_center')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                          <input type="text" v-model="payment_voucher.costcenter" required class="form-control form-control-sm"  id="voucher_costcenter"  >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="voucher_gl_account" class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.gl_account')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                          <input type="text" v-model="payment_voucher.gl_account" required class="form-control form-control-sm"  id="voucher_gl_account"  >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="prpo_type" class="col-form-label-sm col-sm-4 col-form-label">PR/PO</label>
                    <div class="col-sm-2">
                        <select name="prpo_type" id="prpo_type"
                         v-model="payment_voucher.prpo_type"
                        class="form-control form-control-sm" >
                          <option value="PR" selected>PR</option>
                          <option value="PO">PO</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                          <input type="text" v-model="payment_voucher.prpo_num" class="form-control form-control-sm"  id="prpo_num"  >
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="voucher_content" required class="col-form-label-sm col-sm-4 col-form-label">{{$t('form.content')}} <small class="text-red">( * )</small></label>
                    <div class="col-sm-8">
                           <textarea type="text" maxlength="255" required  v-model="payment_voucher.content" id="" class="form-control form-control-sm"    ></textarea>
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
        <!-- Modal dialog add note -->
        <div class="modal fade" id="themghichu" tabindex="-1" role="dialog">
           <div class="modal-dialog" role="document">
              <div class="modal-content">
                 <div class="modal-header">
                   <h5 class="modal-title">{{$t('form.add_note')}}</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>

                 </div>
                 <div class="modal-body">
                   <textarea class="form-control" name="" maxlength="50" id="ghichu" rows="1"></textarea>
                 </div>
                 <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">{{$t('form.close')}}</button>
                     <button type="button" @click="addNote()" data-dismiss="modal" class="btn btn-primary" >{{$t('form.save')}} </button>
                 </div>
              </div>
           </div>
        </div>

         <!-- dialog -->
         <list-choose :url="dialog_url"
            :fields="dialog_fields"
            v-on:selectedDialog="selectedDialog"
            :eventname="selectedDialogName"
            :name="dialog_name">
        </list-choose>
        <!-- <list-choose :url="page_url_advance_money"
        :fields="fields_dialog"
         v-on:selectedTamUng="selectedTamUng"
        eventname="selectedTamUng"
        :name="$t('form.advance_payment_vourcher')">

        </list-choose> -->
</div>

</template>

<script>
import ListChoose from '../../shared/ListChoose.vue';
import dialogContractSearch from "./DialogSearchContract.vue";
import dialogPaycatsetSearch from "./DialogSearchPaycatset.vue";
 import Treeselect from '@riophae/vue-treeselect'
import { ASYNC_SEARCH } from '@riophae/vue-treeselect'
const simulateAsyncOperation = fn => {
  setTimeout(fn, 2000)
}

 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
   components:{
       dialogContractSearch,
       dialogPaycatsetSearch,
       ListChoose,
       Treeselect

   },
  props: {
      id:String,
      user_id:String,
      doctype:String,
      doctype_id:String,
      pre_title:String,
      layout:Object,
  },

  data () {
    return {
         //Dialog
            dialog_url:"",
            dialog_fields:[],
            selectedDialog:function() {

            },
            dialog_name:"",
            selectedDialogName:"selectedDialog",
        //end Dialog
       payrequest:{
        proposer_payment:this.user_id,
        method_payment:"1",
        amount:"",
        bank_id:"",
        group_id:"",
        bank_account:"",
        content:"",
        finish_date:"",
        payrequest_type_id:"",
        date_end:"",
        company_id:" ",
        contract_id:"",
        contract_term_id:"",
        payment_vouchers:[],
        payment_vouchers_del:[],
        payment_attacheds:[],
        payment_attacheds_del:[],
        payment_advance_moneys:[],
        payment_advance_moneys_del:[],
        paycatset_id:"",
        budget_type:"1",
        payment_type_id:"1",
        budget_num:"",//thêm
        money_receiver:"",//cá nhân hoặc đơn vị nhận tiền
        vendor_id:null,//chọn đơn vị nhận tiền
        amount_exchanged:"",//số tiền qui đổi
        amount_out_exchanged:"",
        amount_out_budget:"",
        out_budget:"",
        exchange_rate:"",//tỉ giá
        doc_reference:"",//chứng từ tham chiếu

        currency:"VND",

        document_type_id:this.doctype_id,
      },
       locale_format:'de-DE',
       companies:[],
       paymentTypes:[],
       paymentVourcherTypes:[],
       vendorAll:[],
       tree_vendors:[],
       currencies:[],
      departments:[],
       budget_types:[],
       paymentTypes:[],

      requests_dntu:{
        id:"",
        company_id:"",
        payrequest_id:"",
        advance_money_id:"",
        serial_num:"",
        serial_date:"",
        amount:"",
        finished:""
      },
      requests_serial_num:"",
      users:[],
      payrequest_types:[],
      banks:[],
      paycattypes:[],
      contract:{
        contract_num:"",
        sign_date:"",
        id:""
      },
      vendor_receiver:false,
      bank_input:false,
      out_budget_chk:false,
      contract_term:{
        id:"",
        date_due:""

      },
       payment_voucher:{
        id:"",
        payrequest_id:"",
        type:"",
        doc_num:"",
        doc_date:"",
        amount:"",
        content:"",
        costcenter:"",
        gl_account:"",
        prpo_type:"PR",
        prpo_num:"",
        attachment_file:[],
        attachment_file_removed:[],
        index:""
      },
      //lưu bộ danh mục chứng từ thanh  toán
     // paycatset_id:"",
      payment_attached_curr_index:"",
      payment_vouchers_curr_index:"",
      payment_attached:{
        paycattype_id:"",
        name:"",
        note:"",
        attachment_file:[],
        attachment_file_removed:[],

      },
      fields_dialog: [
          {
              key: 'selected',
              label:'',
              stickyColumn: true
            },
             {
            key: 'company_id',
            label:this.$t('form.company'),
            sortable: true,
            stickyColumn: true
          },
           {
            key: 'serial_num',
            label:this.$t('form.document_num'),
            sortable: true,
            stickyColumn: true
          },

           {
            key: 'amount',
            label:this.$t('form.amount'),
            sortable: true,
            stickyColumn: true,
            formatter(value){
               // console.log(value);
                var locale_format = 'de-DE';
                if(value != null ){
                   return value.toLocaleString(locale_format);
                }
              return 0;
            }
          },

           {
            key: 'created_at',
            label:this.$t('form.date'),
            sortable: true,
            stickyColumn: true,
            formatter(value) {

              return moment(String(value)).format('DD/MM/YYYY')   ;
              }
          }
      ],
      fields: [
          {
              key: 'selected',
              label:'',
              stickyColumn: true
            },
           {
            key: 'serial_num',
            label:this.$t('form.document_num'),
            sortable: true,
            stickyColumn: true
          },
           {
            key: 'company_id',
            label:this.$t('form.company'),
            sortable: true,
            stickyColumn: true
          },

           {
            key: 'amount',
            label:this.$t('form.amount'),
            sortable: true,
            stickyColumn: true,
            formatter(value){
               // console.log(value);
                var locale_format = 'de-DE';
                if(value != null ){
                   return value.toLocaleString(locale_format);
                }
              return 0;
            }
          },

           {
            key: 'serial_date',
            label:this.$t('form.date'),
            sortable: true,
            stickyColumn: true,
            formatter(value) {

              return moment(String(value)).format('DD/MM/YYYY')   ;
              }
          },
            {
            key: 'action',
            label:'',
            sortable: false,
            stickyColumn: true,

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

     // vendors_combobox:[],
      errors:{},
      loading: false,
      edit_voucher: false,
      edit_payrequest:false,
      token:"",
      tabIndex: 0,
      page_url_contracts : "api/payment/contracts",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      page_url_currency:"/api/category/currencies",
      page_url_users:"api/user/all",
      page_url_banks:"/api/category/banks",
      page_url_vendors:"/api/category/vendors",
      page_url_payrequest_types:"/api/category/payrequest_types",
      page_url_payrequest:"/api/payment/requests",
      page_url_advance_money:"/api/payment/advance_money",//danh sách tạm ứng đã được duyệt của User
      page_url_paycattype :'/api/category/paycattypes',
      page_url_budget_type:"/api/category/budgettypes",
      page_url_paymentType:"/api/category/paymenttypes",
      page_url_paymentVourcherType:"/api/category/paymentvourchertypes",
      url_tamung:"",


    }
  },
  created () {
     this.token = "Bearer " + window.Laravel.access_token;
      this.getUser();
      this.fetCompany();
      this.fetVendor();
      this.fetCurrency();
      this.fetDepartment();
      this.fetPayrequestType();
      this.fetBank();
      this.fetBudgetType();
      this.getPaymentType();
      this.getPaymentVourcherType();
      this.fetPaycattype();
      this.getPayrequest();


  },

  methods:{
     selectedVendor(data){

          data.forEach(element => {
               this.payrequest.vendor_id =  element.id;
               return;
          });

        // this.payment_advance_moneys.push([...data]);
      },
       ShowVenorDialog(){
          //let group = this.group_filter.find(x=>x.id == this.praprequest.group_id);
        //   let company_id = group?group.id :"";

         let group_id   = this.payrequest.group_id;
          let group = this.group_filter.find(x=>x.id == group_id);
          let company_id = group?group.company_id:"";

         this.dialog_url = this.page_url_vendors+"?company_id="+company_id;
          this.dialog_fields = [...this.vendor_fields];
          this.selectedDialog = this.selectedVendor;
          this.dialog_name = this.$t('form.vendor');
           $("#modal-user").modal("show");
      },
         loadOptions({ action, searchQuery, callback }) {
      if (action === ASYNC_SEARCH) {
        simulateAsyncOperation(() => {
          let count = 0;
          var list = this.vendors.filter(function(item){
              if(

                (item.comp_name.toLowerCase().includes(searchQuery.toLowerCase())
                && count < 10)){
                count++;
                return true;
              }else{
                return false;
              }

          });

          const options = list.map(i => ({
            id: `${i.id}`,
            label: `${i.comp_name}`,
          }))
          // if(!options && this.payrequest && this.payrequest.vendor_id != ''){

          // }

          callback(null, options)
        })
      }
    },
        getPaymentVoucherTypeName(id){
          var obj = this.paymentVourcherTypes.find(x=>x.id == id );
          return obj?obj.name:'';
        },
        resetBankInfo(){
          this.payrequest.bank_id = null;
          this.payrequest.bank_account = "";
        },
        getNameCurrency(currency){
         var cur=   this.currencies.find(x=>x.id == currency);
         return cur?cur.name:"";

        },
        resetVendorReceiver(){
           this.payrequest.money_receiver = "";
           this.payrequest.vendor_id = null;
        },
        //reset số tiền ngoài/vượt ngân sách, khi checkbox thay đổi
        changeBudgetType(){
          this.out_budget_chk = false;
          this.resetOutBudget();
        },
         resetOutBudget(){

           this.payrequest.amount_out_budget = "";
           this.payrequest.amount_out_exchanged = "";
            if (AutoNumeric.getAutoNumericElement("#amount_out_budget") !== null) {

                //var anObject = new AutoNumeric(document.querySelector(inputSel), autoNumericSettings);
                AutoNumeric.set('#amount_out_budget', this.payrequest.amount_out_budget).options.allowDecimalPadding(false)

            }
            if (AutoNumeric.getAutoNumericElement("#amount_out_exchanged") !== null) {

                //var anObject = new AutoNumeric(document.querySelector(inputSel), autoNumericSettings);
                AutoNumeric.set('#amount_out_exchanged', this.payrequest.amount_out_exchanged).options.allowDecimalPadding(false)

            }


           this.payrequest.out_budget = "0";

           $('#amount_out_exchanged').attr('readonly','true');
        },
        resetBankInput(){
           this.payrequest.bank_name = "";
           this.payrequest.bank_id = null;
        },
        linkClass(idx) {
        if (this.tabIndex === idx) {
          return ['bg-primary', 'text-light']
        } else {
          return ['bg-light', 'text-info']
        }
      },

      getPayrequest(){
       // console.log(this.id);

        if( this.id != ""){
          this.loading = true;

           var page_url = this.page_url_payrequest+"/"+this.id+"/edit";
           fetch(page_url,{ headers: { Authorization: this.token } })
          .then(res=>res.json())
          .then(res=>{

          if(res.statuscode =="403"){
                 window.location.href = "/errorpage?statuscode="+res.statuscode;
            }
          if(res.data.success == '1'){
               this.payrequest = res.data;
               this.payrequest.payment_vouchers_del = [];
               this.payrequest.payment_attacheds_del = [];
               this.payrequest.payment_advance_moneys_del = [];

               if(this.payrequest.contract){
                 this.contract = this.payrequest.contract;
                 if(this.payrequest.contract_term){
                    this.contract_term = this.payrequest.contract_term;
                  }else{

                      //xử lý trường hợp đồng ghi thiếu điều khoản thì mặc định
                      if(this.contract && this.contract.contract_terms && this.contract.contract_terms.length >0){

                         this.contract_term = this.contract.contract_terms[0];
                        //  console.log('tesst'+this.contract_term.id);
                      }
                  }
               }
               if(this.payrequest.vendor_id != null)
               {
                 this.vendor_receiver = true;
               }
               if(this.payrequest.bank_id != null)
               {
                 this.bank_input = true;
               }
                //console.log(this.payrequest.out_budget);
                if(this.payrequest.out_budget == "1")
               {
                 this.out_budget_chk = true;
               }
               this.payrequest.paycatset_id = "";
               //khởi tạo biến mảng attachment_file_removed

               this.payrequest.payment_attacheds.forEach(element => {
                 element.attachment_file_removed = [];
                   this.payrequest.paycatset_id = element.paycatset_id;

               });
               this.payrequest.payment_vouchers.forEach(element => {
                 element.attachment_file_removed = [];
               });


              this.edit_payrequest = true;
              this.loading = false;
               //hiển thị số và không hiển thị số thập phân
              AutoNumeric.set('#amount', this.payrequest.amount).options.allowDecimalPadding(false)
              AutoNumeric.set('#amount_exchanged', this.payrequest.amount_exchanged).options.allowDecimalPadding(false)

              AutoNumeric.set('#amount_out_budget', this.payrequest.amount_out_budget).options.allowDecimalPadding(false)
              AutoNumeric.set('#amount_out_exchanged', this.payrequest.amount_out_exchanged).options.allowDecimalPadding(false)

              $('#amount_exchanged').attr('readonly','true');
               $('#amount_out_exchanged').attr('readonly','true');

              if(this.readOnly('amount')){
                $('#amount').attr('readonly','true');
              }
              AutoNumeric.set('#exchange_rate', this.payrequest.exchange_rate).options.allowDecimalPadding(false)

          }

        }).catch(err=>{
            this.loading = false;
            console.log(err);
        });
        }


      },
      AddPayrequest() {
          this.loading= true;
            var page_url = this.page_url_payrequest;// "/api/category/serviceCategorys";
            this.payrequest.amount = AutoNumeric.getNumber('#amount'); //$('#amount').val();
            this.payrequest.amount_exchanged = AutoNumeric.getNumber('#amount_exchanged'); //$('#amount').val();

             this.payrequest.amount_out_exchanged = AutoNumeric.getNumber('#amount_out_exchanged'); //$('#amount').val();
             this.payrequest.amount_out_budget = AutoNumeric.getNumber('#amount_out_budget'); //$('#amount').val();
            this.payrequest.exchange_rate = AutoNumeric.getNumber('#exchange_rate'); //$('#amount').val();
            this.payrequest.proposer_payment = $('#proposer_payment').val();

            this.payrequest.out_budget = this.out_budget_chk?"1":"0";


            // if(this.payrequest.amount < this.payment_voucher_total_amount){
            //     this.loading= false;

            //       this.$bvModal.msgBoxConfirm(this.$t('Số tiền đề nghị nhỏ hơn số tiền chứng từ !'),{
            //       title: this.$t('form.message'),
            //       size: 'sm',
            //       buttonSize: 'sm',
            //       okVariant: 'danger',
            //       okTitle: 'OK',
            //       cancelTitle: 'Cancel',
            //       footerClass: 'p-2',
            //       hideHeaderClose: false,
            //       centered: true
            //     })

            //       //  this.$bvModal.msgBoxOk(this.$t('Số tiền đề nghị nhỏ hơn số tiền chứng từ !'), {
            //       //     title: this.$t('form.message'),
            //       //     size: 'sm',
            //       //     buttonSize: 'sm',
            //       //     okVariant: 'warning',
            //       //     headerClass: 'p-2 border-bottom-0',
            //       //     footerClass: 'p-2 border-top-0',
            //       //     centered: true
            //       //   })
            //           .then(value => {

            //           })
            //           .catch(err => {
            //             // An error occurred
            //           })

            //   return ;
            // }

            if( this.contract.id != ''){
               this.payrequest.has_contract = true;
               this.payrequest.contract_id = this.contract.id;
               this.payrequest.contract_term_id = this.contract_term.id;
            }else{
              this.payrequest.has_contract = false;
                this.payrequest.contract_id = null;
               this.payrequest.contract_term_id = null;
            }
            //console.log(this.payrequest.amount);
          if(this.edit_payrequest === false){
              fetch(page_url, {
              method: "POST",
              body: JSON.stringify(this.payrequest),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json"
              }
          })
              .then(res => res.json())
              .then(data => {
                //alert(data.statuscode);
                  if(data.statuscode =="403"){

                    window.location.href = "/errorpage?statuscode="+data.statuscode;
                  }
                   this.loading= false;
                  if (!data.data.errors) {
                      // this.resetPayementVoucher();
                    //  this.serviceCategorys.push(data.data);
                        toastr.success(this.$t('form.save_success'),"");
                        window.location.href = "/payment/requests";
                      //$("#AddServiceCategory").modal("hide");

                  }else{
                      //console.log(this.errors);

                      this.errors = data.data.errors;

                  }

              })
              .catch(err => {
                this.loading= false;

                });
          }else{
              //update
             // console.log(this.payrequest);
                fetch(page_url+"/"+this.payrequest.id, {
              method: "PUT",
              body: JSON.stringify(this.payrequest),
              headers: {
                  Authorization: this.token,
                  "content-type": "application/json"
              }
          })
              .then(res => res.json())
              .then(data => {
                //  console.log(data);
                  if (!data.data.errors) {
                         toastr.success(this.$t('form.update_success'),"");
                        window.location.href = "/payment/requests";
                  }else{
                      this.errors = data.data.errors;
                  }
                    this.loading= false;
              })
              .catch(err => {
                this.loading= false;

                });
          }

      },
      fetPaycattype(){
         var page_url = this.page_url_paycattype;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                   // console.log( res.data);
                     this.paycattypes = res.data;
                })
                .catch(err => console.log(err));
      },
      fetCompany() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
      fetVendor() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_vendors;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.vendorAll = res.data;


                })
                .catch(err => console.log(err));
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
         fetDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.departments = res.data.data;
                })
                .catch(err => console.log(err));
        },
        getPaymentType(){

            var page_url = this.page_url_paymentType;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("paymentTypesXin chao");
                    this.paymentTypes = res.data;
                })
                .catch(err => console.log(err));
        },
        getPaymentVourcherType(){

            var page_url = this.page_url_paymentVourcherType;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("paymentTypesXin chao");
                    this.paymentVourcherTypes = res.data;
                })
                .catch(err => console.log(err));
        },
        fetBudgetType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_budget_type;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.budget_types = res.data;
                })
                .catch(err => console.log(err));
        },
       fetBank() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_banks;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.banks = res.data.data;
                })
                .catch(err => console.log(err));
        },
         fetPayrequestType() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_payrequest_types;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.payrequest_types = res.data.data;
                })
                .catch(err => console.log(err));
        },
      popupContractSearch(){

            $("#modal-contract").modal("show");
         },
       popupPaycatsetSearch(){

            $("#modal-paycatset").modal("show");
         },

      //Chọn hợp đồng trên form search
      selectedItem(data){

         // console.log(  data[0]);
          this.contract = data[0];
          if(this.contract.contract_terms.length >0){
            this.contract_term = this.contract.contract_terms[0];

          }else{
            //  this.contract_term
          }


      },
      selectedTamUng(data){
        //console.log(data);

          data.forEach(element => {

            this.requests_dntu = {};

            this.requests_dntu.advance_money_id = element.id;
            this.requests_dntu.company_id = element.company_id;
            // this.requests_dntu.id = element.id;
            this.requests_dntu.serial_num = element.serial_num;

            this.requests_dntu.serial_date =  moment(String( element.created_at)).format('YYYY-MM-DD H:M:S');
            this.requests_dntu.amount = element.amount;

            if(!this.payrequest.payment_advance_moneys.find(x=>x.id ==element.id )){
              this.payrequest.payment_advance_moneys.push({...this.requests_dntu});
            }


          });

        // this.payment_advance_moneys.push([...data]);
      },
      selectedPaycatset(data){


          if(this.payrequest.paycatset_id != data[0].id){

             this.payrequest.paycatset_id = data[0].id;
            // console.log(this.payrequest.paycatset_id);
            this.payrequest.payment_attacheds= [];
            //
            data[0].paycattypes.forEach(element => {

            this.payment_attached.paycattype_id = this.payrequest.paycatset_id;
            this.payment_attached.name = element.name;
            this.payment_attached.attachment_file = [];
            this.payment_attached.attachment_file_removed = [];


             this.payrequest.payment_attacheds.push({...this.payment_attached});
          });
          }

          $('#noidungfiledinhkem').collapse('show');


      },
      //Thay đổi điều khoản
      change_contract_term(event){


         this.contract.contract_terms.forEach(term => {
           if(term.id ==  event.target.value )
            this.contract_term = {...term};
        });
        //this.contract_term = this.contract.contract_terms[index];


      },
      getUser(){

         var  page_url = this.page_url_users
        //  console.log(page_url);
          fetch(page_url,{
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
          }).then(res=>res.json())
          .then(data=>{
              this.users = data.data;
          }) .catch(err => {

                    console.log(err);
                });

      },
        AddPaymentVoucher(){

          this.payment_voucher.amount = AutoNumeric.getNumber('#voucher_amount');// $('#voucher_amount').val();

          if( this.edit_voucher == false){


            this.payrequest.payment_vouchers.push({...this.payment_voucher});


            this.resetPayementVoucher();
          }else{
              this.$set(this.payrequest.payment_vouchers, this.payment_voucher.index, {...this.payment_voucher});
              this.resetPayementVoucher();
              this.edit_voucher = false;
          }
           $("#themchungtuthanhtoan").modal("hide");
          $('#noidungchungtuthanhtoan').collapse('show');

        },
        editPaymentVoucher(item,index){

            this.edit_voucher = true;
            this.payment_voucher.id               = item.id;
            this.payment_voucher.payrequest_id    = item.payrequest_id;
            this.payment_voucher.type             = item.type;
            this.payment_voucher.doc_num          = item.doc_num;
            this.payment_voucher.doc_date         = item.doc_date;
            this.payment_voucher.amount           = item.amount;
            this.payment_voucher.content          = item.content;
            this.payment_voucher.costcenter       = item.costcenter;
            this.payment_voucher.gl_account       = item.gl_account;
            this.payment_voucher.prpo_num       = item.prpo_num;
            this.payment_voucher.prpo_type       = item.prpo_type;
             this.payment_voucher.attachment_file = item.attachment_file;
             this.payment_voucher.attachment_file_removed = item.attachment_file_removed;
            this.payment_voucher.index            = index;

           // $('#voucher_amount').val(this.payment_voucher.amount);
               AutoNumeric.set('#voucher_amount', this.payment_voucher.amount).options.allowDecimalPadding(false);

              $("#themchungtuthanhtoan").modal("show");
        },
      //hiển thị dialog để nhập chứng từ thanh toán
      showPaymentVouchersEmpty(){
           this.edit_voucher = false;
            this.resetPayementVoucher();
              $("#themchungtuthanhtoan").modal("show");
        },
        deletePaymentVoucher(item,index){

            if(confirm( this.$t('form.confirm_delete') +'?')){
                this.payrequest.payment_vouchers_del.push({...item});
                this.payrequest.payment_vouchers.splice(index,1);
            }

        },
        deletePaymentAdvanceMoney(item){

            if(confirm(this.$t('form.confirm_delete') +'?')){
               let index =  this.payrequest.payment_advance_moneys.findIndex(i => {
                    return i.advance_money_id == item.advance_money_id;
                  });

                this.payrequest.payment_advance_moneys_del.push({...item});
                this.payrequest.payment_advance_moneys.splice(index,1);
            }

        },
        //Thêm file trong payment_attached
        handleClickInputFile(index) {

            this.$refs.file.click();
            this.payment_attached_curr_index = index;

        },
        //Thêm file trong payment_attached
        handleClickInputFile_voucher(index) {

            this.$refs.fileVoucher.click();
            this.payment_vouchers_curr_index = index;

        },
        deleteInputFile(item,index){
          if(confirm(this.$t('form.confirm_delete') +'?')){
            this.payrequest.payment_attacheds_del.push({...item});
            this.payrequest.payment_attacheds.splice(index,1);
          }
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
                 console.log(docs);
                 this.payrequest.payment_attacheds[this.payment_attached_curr_index].attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';

        },
        //Sự kiện chọn file voucher
        emitEvent_voucher(event) {

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
                 this.payrequest.payment_vouchers[this.payment_vouchers_curr_index].attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';

        },
        //xoá file trong payment_attacheds
        deleteFile(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){

              //  console.log("index="+index);


                this.payrequest.payment_attacheds[index].attachment_file_removed.push({...item});
                this.payrequest.payment_attacheds[index].attachment_file.splice(findex,1);
            }
        },
        //xoá file trong payment_vouchers
        deleteFile_vouchers(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){

              //  console.log("index="+index);


                this.payrequest.payment_vouchers[index].attachment_file_removed.push({...item});
                this.payrequest.payment_vouchers[index].attachment_file.splice(findex,1);
            }
        },
        //Thêm ghi chú
        showDialogAddNote(index){
          this.payment_attached_curr_index = index;
         $('#ghichu').val( this.payrequest.payment_attacheds[this.payment_attached_curr_index].note);

          $("#themghichu").modal('show');
        },
        AddNewRow(){


            this.payment_attached.paycattype_id = "";
            this.payment_attached.name = "";
            this.payment_attached.attachment_file = [];
            this.payment_attached.attachment_file_removed = [];


             this.payrequest.payment_attacheds.push({...this.payment_attached});
        },
        addNote(){
          this.payrequest.payment_attacheds[this.payment_attached_curr_index].note =  $('#ghichu').val();


        },
        popupListTamUng(){

          this.dialog_url =  this.page_url_advance_money;
          this.dialog_fields = [...this.fields_dialog];
          this.selectedDialog = this.selectedTamUng;
          this.dialog_name = this.$t('form.advance_payment_vourcher');
           $("#modal-user").modal("show");
         // this.url_tamung = this.page_url_advance_money ;
         //  $("#modal-user").modal("show");
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
       getTamUng(){
        //alert(this.requests_serial_num);
       if(this.requests_serial_num.trim() == ""){
         return;
       }
       var page_url = this.page_url_advance_money+"?serial_num="+this.requests_serial_num;
         fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                   // console.log(res.data);
                   // this.banks = res.data.data;
                  //  if( this.payrequest_dntus == null){
                  //     this.payrequest_dntus = [];
                  //  }
                  if(res.data &&  res.data.length > 0){
                     res.data.forEach(element => {
                       if(!this.payrequest.payment_advance_moneys.find(x=>x.id == element.id)){
                         this.payrequest.payment_advance_moneys.push({...element});
                       }

                        this.requests_serial_num="";

                   });
                  }else{
                    alert(this.$t('form.data_not_found')+ ":" + this.requests_serial_num);
                  }

                })
                .catch(err => console.log(err));
      },
        sotienquidoi(){
          var exchange_rate =0;
           var amount = 0;
            var amount_exchanged =0;
         try {
              exchange_rate = AutoNumeric.getNumber('#exchange_rate');
              amount = AutoNumeric.getNumber('#amount');

            if(this.payrequest && this.payrequest.currency != 'VND'){

                amount_exchanged =  amount * exchange_rate;
            }else{

              amount_exchanged =   amount ;
            }
              AutoNumeric.set('#amount_exchanged', amount_exchanged) ;
            } catch (error) {
               AutoNumeric.set('#amount_exchanged', 0) ;

         }

       },
      sotienquidoi_vuotngansach(){
          var exchange_rate =0;
           var amount = 0;
            var amount_exchanged =0;
         try {
              exchange_rate = AutoNumeric.getNumber('#exchange_rate');
              amount = AutoNumeric.getNumber('#amount_out_budget');

            if(this.payrequest && this.payrequest.currency != 'VND'){

                amount_exchanged =  amount * exchange_rate;
            }else{

              amount_exchanged =   amount ;
            }
              AutoNumeric.set('#amount_out_exchanged', amount_exchanged) ;
            } catch (error) {
               AutoNumeric.set('#amount_out_exchanged', 0) ;

         }
         $('#amount_out_exchanged').attr('readonly','true');

       },
       showLabel(fieldName,value){
          if(fieldName in this.layout){
             if(this.layout[fieldName]['has_custom_label']){
               return this.layout[fieldName]['custom_label_text'];
             }
          }
          return value;
       },
       showValidate(fieldName){
          if(fieldName in this.layout){
             return this.layout[fieldName]['require_validation'];
          }
          return false;
       },
       readOnly(fieldName){
          if(fieldName in this.layout){
             return this.layout[fieldName]['is_read_only'];
          }
          return false;
       },
       showControl(fieldName){
          if(fieldName in this.layout){

             return this.layout[fieldName]['isVisible'];
          }
          return false;
       },
       hasError(fieldName){
            return (fieldName in this.errors);
        },
        getError(fieldName){
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        },
        clearError(event){
             Vue.delete( this.errors,event.target.name);
           //console.log(event.target.name);
        },
        resetPayementVoucher(){
            $('#voucher_amount').val('');
            for(let field in this.payment_voucher){
                this.payment_voucher[field] = null;

            }
             this.payment_voucher.attachment_file = [];
             this.payment_voucher.attachment_file_removed = [];
        },
        clearAllError(){
            this.errors = {};
        },
        // Nhập trên lưới
        changeGridEdit(event){
         let span = $(event.target).children('span');
         $(span).hide();
         console.log($(event.target).children('input').show() );

        },
        changeGridView(event){
           console.log( $(event.target).hide());
          console.log( $(event.target.parentElement).children('span').show());
        },
        changeGridViewToEdit(event){
          console.log( $(event.target).hide());
          console.log( $(event.target.parentElement).children('input').show());
        },
  },
  computed:{

      vendors(){
          let group_id   = this.payrequest.group_id;
          let group = this.group_filter.find(x=>x.id == group_id);
          let company_id = group?group.company_id:"";
         // this.contract.vendor_id = "";
          return this.vendorAll.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
     tree_banks(){
      var list =[];

      this.banks.forEach(bank => {
        list.push({'id':bank.id,'label':bank.name})
      });
      return list;
    },
    tree_vendors(){
      var list =[];

      this.vendors.forEach(vendor => {
        list.push({'id':vendor.id,'label':vendor.comp_name})
      });
      return list;
    },
    // tree_vendors(){
    //   var list =[];
    //   if(this.payrequest ){
    //     var vendor =   this.vendors.find( x=>x.id == this.payrequest.vendor_id);
    //     if(vendor){
    //        list.push({'id':vendor.id,'label':vendor.comp_name})
    //     }
    //   }

    //   return list;
    // },
     hasAnyError(){
            return Object.keys(this.errors).length > 0;
        },
       department_filter(){
          let company_id = this.payrequest.company_id;
         // this.contract.department_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
        group_filter(){
          var list= [];
          let user = this.users.find(x=>x.id == this.user_id);
          if(user){
            list = user.groups;
           // this.payrequest.group_id = user.groups[0]['id'];
          }
          return list;
        },
       payment_voucher_total_amount(){

         var total  = 0.0;
         this.payrequest.payment_vouchers.forEach(element => {
           total = total +  element.amount;
         });
        //  this.payrequest.amount = total;
        //   AutoNumeric.set('#amount', total).options.allowDecimalPadding(false)
         return total;
       },
       show_exchanged_rate(){
         return this.payrequest.currency == 'VND'?false:true;
       },

       payrequest_type_filter(){
          let company_id = this.payrequest.company_id;
         // this.contract.department_id = "";
          return this.payrequest_types.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
  }

}

</script>

<style lang="scss" scoped >
 .form-group {
  margin-bottom: 5px  !important;
}
.itemfile:hover{

      cursor: pointer;
    }
    .itemfile span{
      display: none;
    }
    .itemfile:hover span{

      display: inline-block;
      cursor: pointer;
    }
      /* fix select css bị thiếu */
     .select2-selection--single{
      height: 38px  !important;
    }

     a#thongtinchung-tab.nav-link.active {
       border-left:1px solid gray;
    }


</style>
