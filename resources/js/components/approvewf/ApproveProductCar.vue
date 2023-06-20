<template>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between ">
                    <h3 class="card-title">
                        {{ $t("form.approval_information") }}
                    </h3>
                    <span
                        class="badge badge-primary badge-success mr-1 "
                        :title="$t('form.complete_approval')"
                    >
                    </span>
                </div>
            </div>

            <div class="card-body">
            <div class="row align-items-center how-it-works d-flex" v-for="(step, index) in info.info_user_approve"
                    v-bind:key="index">






                <div  class="col-2 text-center d-inline-flex justify-content-center align-items-center"
                >
                    <div class="circle">{{index+1}}</div>
                </div>



              <p style="padding-top: 5px;">
                   <span > {{
                        $t(step.user.name)
                   }}</span> <br/>
                   <span v-if="step.checkout === null && step.finished===0 && step.release===null">{{step.expected_time|formatDB}}</span>
                   <span v-if="step.checkout != null && step.finished===1 && step.release===2">{{step.checkout|formatDT}} <i class="fa fa-check-circle fa-xs" style="color:#07ba07;"></i></span>
                   <span v-if="step.checkout != null && step.finished===0 && step.release===-2">{{step.checkout|formatDT}} <i class="fa fa-times fa-xs" style="color:#f3051c;"></i></span>
                </p>




            </div>
                <div v-if="this.info.is_create && this.info.is_car_cancel==false">
                <span
                    style="padding:3px;"
                    v-for="(workflowstep, index) in this.workflowsteps"
                    v-bind:key="index"
                >
                    <button
                        v-if="(workflowstep.step === 1 && info.is_step_exist.includes(1)===false) || (workflowstep.step === 1 && info.info_user_approve[0].release===-2) || (workflowstep.step === 1 && info.info_user_approve[1].release===-2) || (workflowstep.step === 1 && info.info_user_approve[2].release===-2)"
                        type="button"
                        class="btn btn-success  btn-block"
                        @click="
                            showDialogChooseApprove(
                                'nguoixacnhan',
                                workflowstep.step
                            )
                        "
                    > <i class="fas fa-check"> </i> Gửi duyệt
                    </button>
                     <button
                        v-if="(info.is_fast_process === true && workflowstep.step === 2 && info.is_checkout_step1===true && info.is_cause===false && info.is_step_exist.includes(2)===false) || (info.is_fast_process === true && info.is_checkout_step1===true && workflowstep.step === 2 && info.is_refuse_step2===true) "
                        type="button"
                        class="btn btn-success  btn-block"
                        @click="
                            showDialogChooseApprove(
                                'xulytucthoi',
                                workflowstep.step
                            )
                        "
                    >
                      <i class="fas fa-check"> </i> Gửi duyệt
                    </button>
                    <button
                        v-if="(workflowstep.step === 3 && info.is_checkout_step2===true && info.is_cause===true && info.is_monitor===false && info.is_evaluation===false && info.is_step_exist.includes(3)===false) || ( info.is_checkout_step2===true && workflowstep.step === 3 && info.is_refuse_step3===true && info.is_cause===true) "
                        type="button"
                        class="btn btn-success  btn-block"
                        @click="
                            showDialogChooseApprove(
                                'nguoixacnhannguyennhan',
                                workflowstep.step
                            )
                        "
                    >
                      <i class="fas fa-check"> </i> Gửi duyệt
                    </button>
                    <button
                        v-if="(workflowstep.step === 4 && info.is_step_exist.includes(3)===true && step_apps!=3 && info.is_monitor===true  && info.is_evaluation===true && info.is_step_exist.includes(4)===false) || ( info.is_refuse_step3===false && workflowstep.step === 4 && info.is_refuse_step4===true && info.is_evaluation===true)"
                        type="button"
                        class="btn btn-success  btn-block"
                        @click="
                            showDialogChooseApprove(
                                'nguoikyduyet',
                                workflowstep.step
                            )
                        "
                    >
                       <i class="fas fa-check"> </i> Gửi duyệt
                    </button>
                </span>
            </div>
            <loading :loading="loading"></loading>
            </div>
            <div class="card-footer" v-if="info.is_car_cancel">
                <span class="btn btn-block text-red">{{
                    $t("form.car_cancel")
                }}</span>
             <button
                    type="button"
                    onclick="goBack()"
                    class="btn btn-default btn-block"
                    :title="$t('form.back')"
                >
                    <i class="fas fa-long-arrow-alt-left"></i>
                </button>
            </div>

            <div class="card-footer" v-if="!info.is_car_cancel">

                <button v-if="info.is_approve && step_apps===1 && info.info_user_approve[1].finished ==1 && info.info_user_approve[2].finished==0"
                    type="button"
                    class="btn btn-success  btn-block"
                    :disabled="loading"
                      @click="
                            showDialogError(
                                'mucdoloi',
                                workflowstep.step
                            )  "
                >
                    <i class="fas fa-check"> </i> {{ $t("form.approve") }}
                </button>
                <button
                    type="button"
                    class="btn btn-success  btn-block"
                    :disabled="loading"
                    v-if="info.is_approve && ((step_apps===1 && (info.info_user_approve[0].finished ==0 || info.info_user_approve[1].finished== 0)) || step_apps===2 || step_apps===3)"
                    @click="approveCarAgree()"
                >
                    <i class="fas fa-check"> </i> {{ $t("form.approve") }}
                </button>
                <button v-if="info.is_approve && step_apps===4"
                    type="button"
                    class="btn btn-success  btn-block"
                    :disabled="loading"
                      @click="
                            showDialogRisk(
                                'xacdinhruiro',
                                workflowstep.step
                            )  "
                >
                    <i class="fas fa-check"> </i> {{ $t("form.approve") }}
                </button>
                <button
                    type="button"
                    class="btn btn-danger  btn-block"
                    @click="showDialogAddFeedback()"
                    v-if="info.is_approve"
                >
                    {{ $t("form.reject_feedback") }}
                </button>

                <button
                    type="button"
                    onclick="goBack()"
                    class="btn btn-default btn-block"
                    :title="$t('form.back')"
                >
                    <i class="fas fa-long-arrow-alt-left"></i>
                </button>
            </div>
        </div>
        <div class="card direct-chat direct-chat-primary">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">{{ $t("form.info_feedback") }}</h3>
                <div class="card-tools">
                  <span data-toggle="tooltip" :title="info.count_feedbacks" class="badge badge-primary" style="background-color: #ec0015;">{{info.count_feedbacks}}</span>
                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                  </button>

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="max-height:300px;">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages"  v-for="(feedback, index) in info.feedbacks"
                v-bind:key="index">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">{{ feedback.name }}</span>
                      <span class="direct-chat-timestamp float-right">{{ feedback.date | formatDateTime }}</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="img/avata-default.png" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                     {{ feedback.content }}
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>

                </div>

              </div>

            </div>

             <car-share
              :object_id="object_id"
               v-bind:object="object"
              module="CARS"
            ></car-share>
            <car-assign-list
              :object_id="object_id"
               v-bind:object="object"
              module="CARS"
            ></car-assign-list>
        <div class="modal fade" id="ykienphanhoi" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            {{ $t("form.opinion_feedback") }}
                        </h6>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea
                            class="form-control"
                            name=""
                            id="feedback"
                            required
                            maxlength="254"
                            rows="3"
                        ></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarRefuse()"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        >
                           <i class="fa fa-paper-plane"></i> {{ $t("form.saveandsend") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="nguoixacnhan" role="dialog">
            <div class="modal-dialog" role="scar" style="max-width:600px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> Chọn người xác nhận</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tr>
                                <td>
                                    <label class="label"
                                        >Trưởng Phòng QC:
                                    </label>
                                     <small class="text-red">( * )</small>
                                </td>
                                <td>
                                    <select2 v-model="wfapproved.user_id" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                 <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_time"  class="form-control"/>
                                </td>
                            </tr>
                             <tr>
                                <td style="padding-top: 20px;">
                                    <label class="label"
                                        >Nhân viên QA:
                                    </label>
                                     <small class="text-red">( * )</small>
                                </td>
                                <td style="padding-top: 20px;">
                                   <select2 v-model="wfapproved.staff_qa" :options="list_qas"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                 <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_time_qa"  class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px;">
                                    <label class="label"
                                        >Trưởng Phòng QA:
                                    </label>
                                     <small class="text-red">( * )</small>
                                </td>
                                <td style="padding-top: 20px;">
                                    <select2 v-model="wfapproved.user_id_tpqa" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                 <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_time_tpqa"  class="form-control"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarSend()"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        >
                           <i class="fa fa-paper-plane"></i> {{ $t("form.saveandsend") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="modal fade"
            id="nguoixacnhannguyennhan"

            role="dialog"
        >
            <div class="modal-dialog" role="pcar">
                <div class="modal-content"  style="min-width:700px;">
                    <div class="modal-header">
                        <h5 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> Chọn người duyệt</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tr>
                                <td style="padding:10px;">
                                    <label class="label"
                                        >Người xác nhận:
                                    </label>
                                    <small class="text-red">( * )</small>
                                </td>
                                <td>
                                    <select2 v-model="wfapproved.user_id" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />

                                </td>
                                 <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_time"  class="form-control"/>
                                </td>
                                <td>
                                    <a
                                        href="#"
                                        @click.prevent.stop="add_new_row()"
                                    >
                                        <i class="fa fa-plus-circle"></i
                                    ></a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b-table
                                        hover
                                        responsive
                                        :bordered="true"
                                        thead-tr-class="d-none"
                                        small
                                        :items="infos.steps"
                                        :fields="fields"
                                    >
                                        <template #cell(step)="data">
                                            <select2  v-model="data.item.user_id" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                        </template>
                                         <!-- <template #cell(expected_time)="data">
                                        <input class="form-control"  type="date" v-model="data.item.expected_time" />
                                        </template> -->
                                        <template #cell(action)="data">
                                            <button
                                                @click="
                                                    delete_row(
                                                        data.item,
                                                        data.index
                                                    )
                                                "
                                                class="btn btn-xs "
                                                title="Delete"
                                            >
                                                <i
                                                   class="text-red fa fa-minus-circle"
                                                ></i>
                                            </button>
                                        </template>
                                    </b-table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">
                                    <label class="label"
                                        >Người xem xét:
                                    </label>
                                </td>
                                <td>
                                   <select2 v-model="wfapproved.user_idxx" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                 <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timexx"  class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">
                                    <label class="label"
                                        >Người phê duyệt:
                                    </label>
                                    <small class="text-red">( * )</small>
                                </td>
                                <td>
                                   <select2 v-model="wfapproved.user_idpd" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timepd"  class="form-control"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarSend()"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        >
                          <i class="fa fa-paper-plane"></i> {{ $t("form.saveandsend") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="nguoikyduyet" role="dialog">
            <div class="modal-dialog" role="scar">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> Chọn người ký duyệt</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tr>
                                <td>
                                    <label class="label"
                                        >Người ký duyệt:
                                    </label>
                                     <small class="text-red">( * )</small>
                                </td>
                                <td>
                                <select2 v-model="wfapproved.user_id" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                            </tr>
                            <tr hidden>
                            <td style="padding-top:15px;">
                                    <label class="label"
                                        >Ngày mong đợi:
                                    </label>
                                </td>
                                <td style="padding-top:15px;">
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_time"  class="form-control"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarSend()"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        ><i class="fa fa-paper-plane"></i> {{ $t("form.saveandsend") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="xulytucthoi" role="dialog">
            <div class="modal-dialog" role="pcar" style="max-width:600px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> Chọn người phê duyệt</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tr>
                                <td>
                                    <label class="label"
                                        >Người xác định:
                                    </label>
                                    <small class="text-red">( * )</small>
                                </td>
                                <td>
                                <select2 v-model="wfapproved.user_idxd" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                <td hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timexd"  class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:15px;">
                                    <label class="label"
                                        >Người xác nhận:
                                    </label>
                                    <small class="text-red">( * )</small>
                                </td>
                                <td style="padding-top:15px;">
                                <select2 v-model="wfapproved.user_idxn" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                               <td style="padding-top:15px;" hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timexn"  class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:15px;">
                                    <label class="label"
                                        >Người xem xét:
                                    </label>
                                </td>
                                <td style="padding-top:15px;">
                                <select2 v-model="wfapproved.user_idxx" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                <td style="padding-top:15px;" hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timexx"  class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:15px;">
                                    <label class="label"
                                        >Người phê duyệt:
                                    </label>
                                    <small class="text-red">( * )</small>
                                </td>
                                <td style="padding-top:15px;">
                                <select2 v-model="wfapproved.user_idpd" :options="multOption"  placeholder="Nhập tên cần tìm" :settings="{ settingOption: value, settingOption: value }" />
                                </td>
                                <td style="padding-top:15px;" hidden>
                                     <input type="date" style="width: 170px;" v-model="wfapproved.expected_timepd"  class="form-control"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarSend()"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        ><i class="fa fa-paper-plane"></i> {{ $t("form.saveandsend") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="mucdoloi" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="mucdoloi">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> {{ $t("form.determine_the_error_level") }}</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div v-for="carerror in this.carerrorsqc"
                                            :key="carerror.id">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input mucdoloiapdung" type="radio" name="car_error_id" v-model="car_error_qc_id" :id="carerror.id" v-bind:value="carerror.id" required>
                          <label  class="custom-control-label" :for="carerror.id" style="font-weight: 500;">{{carerror.name}}</label>
                     </div>
                     </div>

                        <label class="label" style="margin-bottom: 0px!important;font-weight: 500;padding-top:20px;padding-bottom:10px;"><b>Phân tích nguyên nhân và đưa BP KPPN</b></label>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="cause_measure1"  value="1" v-model="is_cause_measure">
                          <label for="cause_measure1" class="custom-control-label" style="font-weight: 500;">Có</label>
                        </div>

						<div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="cause_measure2" v-model="is_cause_measure" value="0">
                          <label for="cause_measure2" class="custom-control-label" style="font-weight: 500;">Không</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                         <button
                            type="button"
                            @click="approvePCarAgree()"
                            v-if="info.is_approve"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        >
                          <i class="fas fa-save"></i>  {{ $t("form.save") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
         <div class="modal fade" id="xacdinhruiro" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="xacdinhruiro">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i style="color: #17a2b8; padding-right:10px;" class="fas fa-info-circle"></i> {{ $t("form.risk_identification") }}</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
						<table class="table table-bordered collapsed">
						<tr>
						<td style="padding-right:10px;"><label class="label" style="margin-bottom: 0px!important;font-weight: 500;">Xác định rủi ro</label>
						</td>
						<td>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="xacdinhruiro1" value="1" v-model="cause">
                          <label for="xacdinhruiro1" class="custom-control-label" style="font-weight: 500;">Có</label>
                        </div>
						</td>
						<td style="padding-left:10px;">
						<div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="xacdinhruiro2" v-model="cause" value="0">
                          <label for="xacdinhruiro2" class="custom-control-label" style="font-weight: 500;">Không</label>
                        </div>
						</td>
					  </tr>
					  <tr>
						<td style="padding-right:10px;"><label class="label" style="margin-bottom: 0px!important;font-weight: 500;">Nhận diện cơ hội</label>
						</td>
						<td>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="nhandiencohoi1" v-model="risk" value="1">
                          <label for="nhandiencohoi1" class="custom-control-label" style="font-weight: 500;">Có</label>
                        </div>
						</td>
						<td style="padding-left:10px;">
						<div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="nhandiencohoi2" v-model="risk" value="0">
                          <label for="nhandiencohoi2" class="custom-control-label" style="font-weight: 500;">Không</label>
                        </div>
						</td>
					  </tr>
					  </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                         <i class="fas fa-times"></i> {{ $t("form.close") }}
                        </button>
                        <button
                            type="button"
                            @click="approveCarAgree()"
                            v-if="info.is_approve"
                            data-dismiss="modal"
                            class="btn btn-primary"
                        >
                          <i class="fas fa-save"></i>  {{ $t("form.save") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Loading from "../shared/Loading.vue";
import Select2 from "v-select2-component";
export default {
    props: {
        object: Object,
        type: String,
        user_id: String,
        showstep: String,
        object_id:"",
    },
    components: {
        Select2
    },
    data() {
        return {
            value: [],
            options: [],
            index:1,
            date:'',
            car_error_id:4,
            car_error_qc_id:7,
            is_cause_measure:0,
            is_type_car:"PCAR",
            cause:0,
            risk:0,
            disabled: true,
            wfapproved: {
                user_id: "",
                wfstep_id: "",
                staff_qa:"",
                user_id_tpqa:"",
                type_car:"PCAR",
                user_idxx:"",
                expected_time:"",
                expected_timexd:"",
                expected_timexn:"",
                expected_timexx:"",
                expected_timepd:"",
                expected_time_qa:"",
                expected_time_tpqa:""
            },
            info: {
                is_send: false,
                finished: false,
                user_wait: {},
                next_user: {},
                list_user: [],
                steps: [],
                step_approve:[],
                list_user_approve:[]
            },
            infos: {
                wfmain_id: "",
                steps: [],
                steps_del: []
            },
            steps: [],
            nameapprove: "",
            nameapproves: [],
            workflowapps: [],
            workflowsteps: {
                id: "",
                name: "",
                step: ""
            },
            step_apps:[],
            list_qas:[],
            carerrors:[],
            carerrorsqc:[],
            fields: [
                {
                    key: "step",
                    label: this.$t("form.person_confirm")
                },
                 {
                    key: "expected_time",
                    label: this.$t("form.expected_time")
                },
                {
                    key: "action",
                    lable: ""
                }
            ],
            workflowstep: "",
            name: "",
            after_send: false,
            loading: false,
            token: "",
            page_url_approve_car: "api/approvewf",
            page_url_name_approve: "api/category/positionapps",
            page_url_wfstep: "api/category/get_workflow/?code=PCAR",
            page_url_approvewf: "api/approvewf",
            page_url_car_error_qa: "api/category/car_error_qas",
            page_url_car_error_qc: "api/category/car_error_qcs"
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.fetNameApprove();
        this.fetWorkFlowStep();
        this.get_step();
        //this.fetWorkFlowApproved();
        this.getCarErrorQA();
        this.getCarErrorQC();
    },
    computed:{
        multOption(){
          this.nameapproves?.map((e) => {
          let o={
            text: e.user.name+'('+e.user.username+')',
            id: e.user.id
          }
          this.options.push(o);

      });

      return this.options;

      }
    },
    watch: {
        object: function(value) {
            //   console.log('Prop changed: ', newVal, ' | was: ', oldVal)
            this.get_info_approved();
        }
    },
    methods: {
        fetWorkFlowApproved() {
            var page_url = this.page_url_approve_car;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.workflowapps = res.data;
                    //console.log(this.workflowapps);
                })
                .catch(err => console.log(err));
        },
        get_step() {
            this.loading = true;
            var page_url = this.page_url_name_approve;
            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    // console.log(data);
                    if (data.success == 1) {
                        var obj = {};
                        data.data.forEach(element => {
                            obj = {};
                            obj.value = element.user_id;
                            obj.text = element.user.name;
                            this.steps.push({ ...obj });
                        });
                    }
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        fetWorkFlowStep() {
            var page_url = this.page_url_wfstep;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.workflowsteps = res.data;
                    //console.log(this.workflowsteps);
                })
                .catch(err => console.log(err));
        },
        fetNameApprove() {
            var page_url = this.page_url_name_approve;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.nameapproves = res.data;
                    var obj = {};
                    this.nameapproves.forEach(element => {
                            if(element.description=='QA'){
                             let o={
                                    text: element.user.name+'('+element.user.username+')',
                                    id: element.user_id
                                }
                                this.list_qas.push(o);
                            }
                        });
                    //console.log(this.list_qas);
                })
                .catch(err => console.log(err));
        },
        get_info_approved() {
            this.loading = true;
            var page_url =
                this.page_url_approve_car +
                "/info?type=" +
                this.type +
                "&id=" +
                this.object.id;

            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                     //  console.log(data.data.info.step_approve);
                    this.info = data.data.info;
                    //console.log( this.info.step_approve.includes(3));
                    if(this.info.step_approve)
                    this.step_apps =  this.info.step_approve;
                    else
                    this.step_apps =  array();

                   //  console.log(this.info.info_date_approve);
                  // console.log(this.info.info_user_approve);
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
         getCarErrorQA() {
            var page_url = this.page_url_car_error_qa;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.carerrors = res.data;
                    //console.log(this.workflowapps);
                })
                .catch(err => console.log(err));
        },
        getCarErrorQC() {
            var page_url = this.page_url_car_error_qc;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.carerrorsqc = res.data;
                    //console.log(this.workflowapps);
                })
                .catch(err => console.log(err));
        },
        //Gửi yêu cầu duyệt
        approveCarSend() {
            this.loading = true;
            fetch(this.page_url_approve_car + "/send", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    wfapproved: this.wfapproved,
                    infos: this.infos.steps
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   // console.log(data);
                    this.loading = false;
                    if (data.data.success == "1") {
                         this.get_info_approved();

                        toastr.success(
                            this.$t("form.send_approved_success"),
                            ""
                        );

                    } else {
                         toastr.error("Người duyệt không được trống.");
                        // if (
                        //     data.data.errors != null &&
                        //     !Array.isArray(data.data.errors)
                        // ) {
                        //     toastr.success(data.data.errors, "");
                        // }

                        // toastr.success(this.$t("form.send_approved_error"), "");
                    }
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        approveCarAgree() {
            this.loading = true;
            fetch(this.page_url_approve_car + "/agree", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    user_id: this.wfapproved,
                    car_error_id:this.car_error_id,
                    cause:this.cause,
                    risk:this.risk,
                    is_cause_measure:this.is_cause_measure,
                    is_type:this.is_type_car
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    // console.log(data);
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t("form.approved_success"), "");
                    } else {
                        toastr.error(this.$t("form.approved_error"), "");
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        approvePCarAgree() {
            this.loading = true;
            fetch(this.page_url_approve_car + "/agree", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    user_id: this.wfapproved,
                    car_error_id:this.car_error_qc_id,
                    cause:this.cause,
                    risk:this.risk,
                    is_cause_measure:this.is_cause_measure
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    // console.log(data);
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t("form.approved_success"), "");
                    } else {
                        toastr.error(this.$t("form.approved_error"), "");
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        add_new_row() {
            var info = {};
            info.step = "";
            info.expected_time = "";
            this.infos.steps.push({ ...info });
        },
        delete_row(item, index) {
            if (confirm("Xác nhận xoá?")) {
                this.infos.steps_del.push({ ...item });
                this.infos.steps.splice(index, 1);
            }
        },
        //Ghi ý kiến phản hồi
        showDialogAddFeedback() {
            $("#ykienphanhoi").modal("show");
        },
        showDialogChooseApprove(id_dom, step) {
            $("#" + id_dom + "").modal("show");
            this.wfapproved.wfstep_id = step;
        },
        showDialogError(id_dom, step) {
            $("#" + id_dom + "").modal("show");
            this.wfapproved.wfstep_id = step;
        },
        showDialogRisk(id_dom, step) {
            $("#" + id_dom + "").modal("show");
            this.wfapproved.wfstep_id = step;
        },
        approveCarRefuse() {
            fetch(this.page_url_approve_car + "/refuse", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    feedback: $("#feedback").val()
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    //  console.log(data);
                    if (data.data.success == "1") {
                        this.get_info_approved();

                        toastr.success(this.$t("form.feedback_success"), "");
                    } else {
                        toastr.error(this.$t("form.feedback_error"), "");
                    }
                })
                .catch(err => console.log(err));
        }
    }
};
</script>

<style lang="css">
.select2-container--default .select2-selection--single {
  border: 1px solid #ced4da;
  padding: 0.46875rem 0.75rem;
  height: calc(2.25rem + 2px);
}
.select2-container--default.select2-container--focus .select2-selection--single, .select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #80bdff;
    height: 40px;
    width: auto;
}
.select2-container {
    width: 100%!important;
    height: calc(2.25rem + 2px)!important;
}
.list-group-item {
    position: relative;
    display: block;
    padding: 0.35rem 0.15rem;
    background-color: #fff;
    /* border: 1px solid rgba(184, 26, 26, 0.125); */
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.circle {
    padding: 0px 8px;
    border-radius: 50%;
    background-color: #6c757d;
    color: #fff;

    z-index: 2;
}
.btn-primary {
    color: #ffffff;
}
.direct-chat-messages {
    transform: translate(0, 0);
    height: unset;
    overflow: auto;
    padding: 10px;
}


</style>
