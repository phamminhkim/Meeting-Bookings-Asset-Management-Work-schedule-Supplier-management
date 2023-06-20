<template>
    <div>
        <div class="content-header">
            <div class="container-fluid ml-0">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
                        <ol class="breadcrumb ">
                            <li class="breadcrumb-item">
                                <h6 class="m-0 text-dark" style="padding-top: 8px;">
                                    <i
                                        class="fa fa-angle-double-right fa-xs" style="color: #74b7c9;"
                                    ></i>
                                    <a href="#" @click.prevent="backToList()">{{
                                        $t(pre_title)
                                    }}</a>
                                </h6>
                            </li>

                            <li class="breadcrumb-item active" style="padding-top: 3px; font-size: 14px;">
                                <span>{{ $t("form.detail") }}</span>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right " style="margin-top:-5px; ">
                            <button v-if="this.car.status===2"
                                class="btn btn-default"
                                @click.prevent="print()"
                            >
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ----------------- -->
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <span class="col-form-label col-5 "
                                        >{{ $t("form.company") }}:</span
                                    >
                                    <div class="col-7">
                                        <label
                                            class="col-form-label"
                                            v-if="car.company"
                                            >{{ car.company.name }}</label
                                        >
                                        <label
                                            class="col-form-label"
                                            v-if="!car.company"
                                            >&nbsp;</label
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.department") }}:</span
                                    >
                                    <div class="col-7">
                                        <label
                                            class="col-form-label"
                                            v-if="car.department"
                                            >{{ car.department.name }}</label
                                        >
                                        <label
                                            class="col-form-label"
                                            v-if="!car.department"
                                            >&nbsp;</label
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.car_num") }}:</span
                                    >
                                    <div class="col-7">
                                        <label class="col-form-label">
                                            {{ car.serial_num }}
                                        </label>
                                    </div>
                                </div>
                                 <div class="form-group row" v-if="car.type_car_id">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.type_car") }}:</span
                                    >
                                    <div class="col-7">
                                        <label class="col-form-label">
                                            {{ car.type_car.name }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="car.standard_id">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.standard") }}:</span
                                    >
                                    <div class="col-7">
                                        <label class="col-form-label">
                                            {{ car.standard.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.issue_count") }}:</span
                                    >
                                    <div class="col-7">
                                        <label class="col-form-label">
                                            {{ car.issue_count }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.issue_date") }}:</span
                                    >
                                    <div class="col-7">
                                        <label class="col-form-label">
                                            {{ car.issue_date | formatDB }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-form-label col-5"
                                        >{{ $t("form.car_proposer") }}:</span
                                    >
                                    <div class="col-7">
                                        <label
                                            class="col-form-label"
                                            v-if="car.users"
                                            >{{ car.users.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <table
                                class="table table-hover dataTable dtr-inline"
                            >
                                <tr>
                                    <td colspan="4" style="border:none;">
                                        <b class="col-form-label"
                                            >Mô tả chi tiết sự không phù hợp:</b
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="border:none;">
                                        <span style="text-align: justify;"
                                            class="col-form-label"
                                            v-html="car.content"
                                        ></span>
                                    </td>
                                </tr>
                                <tr v-if="this.other_attacheds>0">
                                    <td style="border:none;">
                                        <table class="table-bordered collapsed">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th
                                                        class="col-form-label text-nowrap"
                                                    >
                                                        {{ $t("form.name") }}
                                                    </th>
                                                    <th
                                                        class="col-form-label text-nowrap"
                                                    >
                                                        Tệp
                                                    </th>
                                                    <th
                                                        class="col-form-label text-nowrap"
                                                    >
                                                        {{ $t("form.note") }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(car,
                                                    index) in car.other_attacheds"
                                                    v-bind:key="index"
                                                >
                                                    <td scope="row">{{index+1}}</td>
                                                    <td class="text-nowrap">
                                                        {{ car.name }}
                                                    </td>
                                                    <td>
                                                        <ul
                                                            class="list-unstyled mt-0"
                                                        >
                                                            <li
                                                                v-for="(file,
                                                                findex) in car.attachment_file"
                                                                v-bind:key="
                                                                    findex
                                                                "
                                                                class="itemfile"
                                                            >
                                                                <a
                                                                    href="#"
                                                                    @click.prevent="
                                                                        downloadFile(
                                                                            file.id,
                                                                            file.name
                                                                        )
                                                                    "
                                                                    class="btn-link text-secondary"
                                                                    >
                                                                    {{
                                                                        file.name
                                                                    }}</a
                                                                >
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <span>{{
                                                            car.note
                                                        }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table
                                class="table dtr-inline"
                                v-if="car.car_error_id"
                            >
                                <tr>
                                    <td colspan="2" style="border: none;">
                                        <b class="col-form-label"
                                            >Mức độ lỗi:</b
                                        >
                                    </td>
                                    <td style="border: none;" v-if="car.cause"> <b class="col-form-label"
                                            >Xác định rủi ro:</b> </td>
                                    <td style="border: none;" v-if="car.risk"> <b class="col-form-label"
                                            >Nhận diện cơ hội:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: none;" v-if="this.car.document_type.code=='SCAR'">
                                        <div
                                            v-for="carerror in this.carerrors"
                                            :key="carerror.id"
                                        >
                                            <div
                                                class="custom-control custom-radio"
                                            >
                                            <input  class="custom-control-input mucdoloiapdung" v-if="carerror.id === car.car_error_id" checked type="radio" >
                                            <input  class="custom-control-input mucdoloiapdung" v-else-if="carerror.id != car.car_error_id" disabled="true" >
                                                <label
                                                    class="custom-control-label" style="font-weight: 500;"
                                                    :for="carerror.id"
                                                    >{{ carerror.name }}</label
                                                >
                                            </div>
                                        </div>
                                    </td>
                                     <td colspan="2" style="border: none;" v-if="this.car.document_type.code=='PCAR'">
                                        <div
                                            v-for="carerror in this.carerrors_qc"
                                            :key="carerror.id"
                                        >
                                            <div
                                                class="custom-control custom-radio"
                                            >
                                            <input  class="custom-control-input mucdoloiapdung" v-if="carerror.id === car.car_error_id" checked type="radio" >
                                            <input  class="custom-control-input mucdoloiapdung" v-else-if="carerror.id != car.car_error_id" disabled="true" >
                                                <label
                                                    class="custom-control-label" style="font-weight: 500;"
                                                    :for="carerror.id"
                                                    >{{ carerror.name }}</label
                                                >
                                            </div>
                                        </div>
                                             <label class="label" style="margin-bottom: 0px!important;font-weight: 500;padding-top:20px;padding-bottom:10px;"><b>Phân tích nguyên nhân và đưa BP KPPN</b></label>
                                            <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="cause_measure1"  :value="car.cause" v-if="car.is_cause_measure==1" checked>
                                            <label for="cause_measure1" class="custom-control-label" style="font-weight: 500;">Có</label>
                                            </div>
                                            
                                            <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="cause_measure2"  v-if="car.is_cause_measure==0" checked>
                                            <label for="cause_measure2" class="custom-control-label" style="font-weight: 500;">Không</label>
                                            </div>
                                    </td>
                                    <td style="border: none;"  v-if="car.cause">
                                    <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" :value="car.cause" v-if="car.cause==1" checked>
                                    <label for="xacdinhruiro1" class="custom-control-label" style="font-weight: 500;">Có</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" v-if="car.cause==0" checked >
                                    <label for="xacdinhruiro2" class="custom-control-label" style="font-weight: 500;">Không</label>
                                    </div>
                                    </td>
                                    <td style="border: none;"  v-if="car.risk">
                                    <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" v-if="car.risk==1" checked>
                                    <label for="nhandiencohoi1" class="custom-control-label" style="font-weight: 500;">Có</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" v-if="car.risk==0" checked>
                                    <label for="nhandiencohoi2" class="custom-control-label" style="font-weight: 500;">Không</label>
                                    </div>
                                   
                                    </td>
                                </tr>
                            </table>
            <div class="callout callout-info" v-if="this.fast_process>0">	
			<h6><b>XỬ LÝ TỨC THỜI</b></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table id="example" class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Hành động xử lý tức thời</th>
					<th>Thời hạn</th>
                    <th>Người phụ trách</th>
                    <th>Tệp đính kèm</th>
                    <th  v-if="car.proposer.id==user_id && car.status!=2">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr  v-for="(process, index) in this.car.fast_process"
                                                    v-bind:key="index">
                    <td>{{index+1}}</td>
					<td v-html="process.content"> </td>
                     <td>{{process.date| formatDB}} </td>
                    <td>{{process.person_in_charge}} </td>
                    <td>

					<table  v-if="process.other_attacheds[0]">
                    <div  v-for="(other_attached,index) in process.other_attacheds" v-bind:key="index">
					<tr v-for="(file, index1) in other_attached.attachment_file"
                                                    v-bind:key="index1">
					<td>{{index+1}}</td>
					<td>
					 <a
                         href="#"
                             @click.prevent="
                             downloadFile(
                            file.id,
                            file.name
                            )
                            "
                            class="btn-link text-secondary"
                            >
                            {{
                         file.name
                         }}</a>
					</td>
					</tr>
                    </div>
					</table>
                    </td>
				
                     <td v-if="(car.proposer.id==user_id && car.status!=2) || (user_assign===true && car.status!=2)">
                     <a href="#" :title="$t('form.edit')" class="btn btn-sm"   @click.prevent="fastprocessAction(process.id,'EDIT')"> <i class="fas fa-edit"></i> </a>
                     <a href="#" :title="$t('form.delete')" class="btn btn-sm"  v-if="(info.is_fast_process==false && info.is_cause==false  && car.proposer.id==user_id) || (info.is_type_car=='PCAR' && info.is_checkout_step2==false  && car.proposer.id==user_id)" @click.prevent="fastprocessedel(process.id)"><i class="fas fa-trash"></i></a>
                     </td>
                  </tr>
                  </tbody>
                </table>
				</div>
			  </div>

            <div class="callout callout-info" v-if="this.cause_measure>0">	
			<h6><b>NGUYÊN NHÂN VÀ BIỆN PHÁP KHẮC PHỤC</b></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
					<th>STT</th>
                    <th>Nguyên nhân</th>
					<th>Biện pháp khắc phục</th>
					<th>Thời gian thực hiện</th>
                     <th>Người phụ trách</th>
                    <th>Tệp đính kèm</th>
                    <th  v-if="car.proposer.id==user_id && car.status!=2">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(cause, index) in this.car.cause_measure"
                                                    v-bind:key="index">
				   <td>{{index+1}}</td>
                   <td v-html="cause.cause"> </td>
                   <td v-html="cause.measure"> </td>
                  <td>{{cause.date| formatDB}}</td>
                  <td>{{cause.person_in_charge}}</td>
                  <td>
                  <table v-if="cause.other_attacheds.length>0">
                       <div  v-for="(other_attached,index) in cause.other_attacheds" v-bind:key="index">
					<tr v-for="(file, list) in other_attached.attachment_file"
                                                    v-bind:key="list">
					<td>{{index+1}}</td>
					<td>
                    <a
                         href="#"
                             @click.prevent="
                             downloadFile(
                            file.id,
                            file.name
                            )
                            "
                            class="btn-link text-secondary"
                            >
                            {{
                         file.name
                         }}</a>
					</td>
					</tr>
                    </div>
					</table>
                  </td>
                    <td v-if="(car.proposer.id==user_id && car.status!=2) || (user_assign===true && car.status!=2)">
                     <a href="#" :title="$t('form.edit')" class="btn btn-sm"   @click.prevent="causeAction(cause.id,'EDIT')"> <i class="fas fa-edit"></i> </a>
                     <a href="#" :title="$t('form.delete')" class="btn btn-sm" v-if="(info.is_monitor==false && info.is_checkout_step2==false && car.proposer.id==user_id)|| (info.is_type_car=='SCAR' && info.is_checkout_step2==false && car.proposer.id==user_id) || (info.is_type_car=='PCAR' && info.is_checkout_step3==false && car.proposer.id==user_id)" @click.prevent="causedel(cause.id)"><i class="fas fa-trash"></i></a>
                  </td>
                  </tr>
                  </tbody>
                </table>
				</div>
			  </div>
            <div class="callout callout-info" v-if="this.monitor_implementation>0">	
			<h6><b>GIÁM SÁT THỰC HIỆN</b></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
					<th>STT</th>
                    <th style="with:50%;">Biện pháp khắc phục</th>
					<th>Kết quả</th>
					<th>Ngày đánh giá</th>
                    <th>Ngày hoàn thành</th>
                    <th v-if="car.proposer.id==user_id && car.status!=2">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr   v-for="(cause, index) in this.car.cause_measure"
                                                    v-bind:key="index">
				   <td>{{index+1}}</td>
                  <td style="text-align: justify;">{{cause.measure}}</td>
				  <td v-if="cause.monitor_implementation[0].result==1">Chưa hoàn thành</td>
                  <td v-else-if="cause.monitor_implementation[0].result==0">Hoàn thành</td>
                  <td>{{cause.monitor_implementation[0].date| formatDB}}</td>
                  <td>{{cause.monitor_implementation[0].finished_date| formatDB}}</td>
                  <td v-if="car.proposer.id==user_id && car.status!=2">
                     <a href="#" :title="$t('form.edit')" class="btn btn-sm" @click.prevent="monitorAction(cause.id,'EDIT')"> <i class="fas fa-edit"></i> </a>
                     <!-- <a href="#" :title="$t('form.delete')" class="btn btn-sm"  @click.prevent="monitordel(cause.id)"><i class="fas fa-trash"></i></a> -->
                    </td>
                  </tr>
                  </tbody>
                </table>
				</div>
			  </div>
            <div class="callout callout-info" v-if="this.result_evaluation>0">	
			<h6><b>ĐÁNH GIÁ KẾT QUẢ</b></h6>
			<span class="description"></span> 
			    <div class="card-body table-responsive">
				<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
					<th>STT</th>
                    <th style="text-align: justify;">Nội dung</th>
					<th>Kết quả</th>
					<th>Ngày</th>
                    <th>Tệp đính kèm</th>
                    <th v-if="car.proposer.id==user_id && car.status!=2">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr   v-for="(evaluation, index) in this.car.result_evaluation"
                                                    v-bind:key="index">
				   <td>{{index+1}}</td>
                  <td v-html="evaluation.content"></td>
				  <td v-if="evaluation.result==0">Đạt</td>
                  <td v-else-if="evaluation.result==1">Không đạt</td>
                   <td v-else-if="evaluation.result==2">Không có dữ liệu</td>
                  <td>{{evaluation.date| formatDB}}</td>
                  <td>
                  <table v-if="evaluation.other_attacheds.length>0">
                       <div  v-for="(other_attached,index) in evaluation.other_attacheds" v-bind:key="index">
					<tr v-for="(file, index1) in other_attached.attachment_file"
                                                    v-bind:key="index1">
					<td>{{index+1}}</td>
					<td>
                    <a
                         href="#"
                             @click.prevent="
                             downloadFile(
                            file.id,
                            file.name
                            )
                            "
                            class="btn-link text-secondary"
                            >
                            {{
                         file.name
                         }}</a>
					
					</td>
					</tr>
                    </div>
					</table>
                  </td>
                   <td v-if="car.proposer.id==user_id && car.status!=2">
                     <a href="#" :title="$t('form.edit')" class="btn btn-sm"   @click.prevent="resultAction(evaluation.id,'EDIT')"> <i class="fas fa-edit"></i> </a>
                     <a href="#" :title="$t('form.delete')" class="btn btn-sm"  @click.prevent="resultdel(evaluation.id)"><i class="fas fa-trash"></i></a>
                    </td>
                  </tr>
                  </tbody>
                </table>
				</div>
			  </div>
                </div>
                 <!-- card-body -->
                    </div>
                    <loading :loading="loading"></loading>
                </div>

                <causes-list
                    v-bind:object="car"
                    :items_props="car.causes"
                    v-on:fastprocessAction="fastprocessAction"
                    v-on:causeAction="causeAction"
                    v-on:monitorAction="monitorAction"
                    v-on:resultAction="resultAction"
                    v-on:assignAction="assignAction"
                    v-on:getcar="getcar"
                    :user_id="user_id"
                    :type="'CARS'"
                ></causes-list>
                 <create-fast-process-dialog
                    :object_id="object_id"
                    v-on:fromFastProcess="fromFastProcess"
                    v-on:getcar="getcar"
                    :id="fast_process_id"
                    module="CARS"
                ></create-fast-process-dialog>
                <create-causes-dialog
                    :object_id="object_id"
                    v-on:fromCause="fromCause"
                    v-on:getcar="getcar"
                    :id="cause_id"
                    module="CARS"
                ></create-causes-dialog>
                <car-assign-dialog
                    :object_id="object_id"
                    v-on:fromAssign="fromAssign"
                    v-on:getcar="getcar"
                    :id="assign_id"
                    module="CARS"
                ></car-assign-dialog>
                <create-monitor-dialog
                    :object_id="object_id"
                    v-on:fromMonitor="fromMonitor"
                    v-on:getcar="getcar"
                    :id="monitor_id"
                    module="CARS"
                ></create-monitor-dialog>
                <create-result-dialog
                    :object_id="object_id"
                    v-on:fromResult="fromResult"
                    v-on:getcar="getcar"
                    :id="result_id"
                    module="CARS"
                ></create-result-dialog>
                <date-limit-list-car v-if="car.proposer.id==user_id && this.info.is_step1_finished==true"
                    v-bind:object="car"
                    :items_props="car.datelimits"
                    v-on:datelimitAction="datelimitAction"
                    :type="'CARS'"
                ></date-limit-list-car>
                 <reminder-list v-if="car.proposer.id==user_id && this.info.is_step1_finished==true"
                    v-bind:object="car"
                    :items_props="car.reminders"
                    v-on:reminderAction="reminderAction"
                    :type="'CARS'"
                ></reminder-list>
                 <!-- <comment-list v-if="this.info.is_step1_finished==true"
                     :object_id="object_id"
                     :datacomments="car.comments"
                     :user_id="user_id"
                     :type="'CARS'"
                      module="CARS"
                     v-on:getcar="getcar"
               ></comment-list> -->
                <timeline-list
                    :list="car.timelines"
                    :showicon="true"
                ></timeline-list>
            </div>
          
            <approve-systemcar v-if="scar_type"
                v-bind:object="car"
                showstep=""
                :type="'CARS'"
                :user_id="user_id"
                :object_id="object_id"
            ></approve-systemcar>
           <approve-productcar v-if="pcar_type"
                v-bind:object="car"
                showstep=""
                :type="'CARS'"
                :user_id="user_id"
                :object_id="object_id"
            ></approve-productcar>
            
        </div>
        <create-date-limit-dialog-car v-if="car.proposer.id==user_id && this.info.is_step1_finished==true"
            :object_id="object_id"
            :id="datelimit_id"
            module="CARS"
        ></create-date-limit-dialog-car>
        <create-event-dialog v-if="car.proposer.id==user_id && this.info.is_step1_finished==true"
            :object_id="object_id"
            v-on:fromReminder="fromReminder"
            :id="reminder_id"
            module="CARS"
        ></create-event-dialog>
    </div>
</template>

<script>
import Loading from "../shared/Loading.vue";
import TimelineList from "../timeline/TimelineList.vue";
export default {
    watch: {
        car() {
            this.object_id.push(this.car.id);
            const array = [];
            for(let $i=0;$i<this.shareds.length;$i++){
            if(this.shareds[$i]['type']==4){
                array.push(this.shareds[$i]['assign']['id'])
            }
           }
           if(array.includes(Number(this.user_id))){
           this.user_assign = true;
           }
           //console.log(array);
            this.get_info_approved();
        }
    },
    props: {
        id: String,
        user_id: String,
        pre_url: "",
        pre_title: "",
        module_id: String,
        notification_id: String,
        layout: Object
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        // this.getUserInfo();
        this.getcar();
        this.getCarErrorQA();
        this.getCarErrorQC();
    },
    data() {
        return {
            car: {
                proposer: this.user_id,
                document_type_id: this.doctype_id
            },
            other_attached_curr_index: "",
            other_attached: {
                paycattype_id: "",
                name: "",
                note: "",
                attachment_file: [],
                attachment_file_removed: [],
                other_attacheds: [],
                other_attacheds_del: []
            },
            user_assign:false,
            shareds:[],
            info:[],
            carerrors: [],
            carerrors_qc:[],
            car_error_id: "",
            fast_process:0,
            monitor_implementation:0,
            result_evaluation:0,
            cause_measure:0,
            other_attacheds:0,
            scar_type:1,
            pcar_type:1,
            disabled: true,
            object_id: [],
            reminder_id: "",
            cause_id: "",
            assign_id:"",
            monitor_id: "",
            result_id: "",
            fast_process_id:"",
            datelimit_id:"",
            locale_format: "de-DE",
            companies: [],
            departments: [],
            users: [],
            // vendors_combobox:[],
            errors: {},
            loading: false,
            edit_car: false,
            token: "",
            tabIndex: 0,
            page_url_car: "/api/car/systems",
            page_url_car_print: "/car/systems/print",
            page_url_car_error_qa: "api/category/car_error_qas",
            page_url_car_error_qc: "api/category/car_error_qcs",
            page_url_fast_processe : "api/car/fast_processes",
            page_url_cause: 'api/car/cause_measures',
            page_url_result_evaluation : "api/car/result_evaluations",
            page_url_monitor_implementation : "api/car/monitor_implementations",
            page_url_approve_car: "api/approvewf",
        };
    },
    computed: {},
    methods: {
        get_info_approved() {
            //console.log(this.module_id);
            this.loading = true;
            var page_url =
                this.page_url_approve_car +
                "/info?type=CARS" +
                "&id=" +
               this.object_id[0];

            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.info = data.data.info;
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
                    this.carerrors_qc = res.data;
                    //console.log(this.carerrors_qc);
                })
                .catch(err => console.log(err));
        },

        fromFastProcess(obj) {
            let index = this.car.fast_process.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.fast_process[index] = obj;

               // this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.fast_process.push(obj);
            }
        },
        fromCause(obj) {
            let index = this.car.causes.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.causes[index] = obj;

                //this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.causes.push(obj);
            }
        },
        fromAssign(obj) {
            let index = this.car.assign.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.assign[index] = obj;

                //this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.assign.push(obj);
            }
        },
        fromMonitor(obj) {
            let index = this.car.monitors.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.monitors[index] = obj;

               // this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.monitos.push(obj);
            }
        },
        fromResult(obj) {
            let index = this.car.results.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.results[index] = obj;

               // this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.results.push(obj);
            }
        },
        fromReminder(obj) {
            let index = this.car.reminders.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.reminders[index] = obj;

                this.$root.$emit("bv::refresh::table", "reminderRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.reminders.push(obj);
            }
        },
        fromDatelimit(obj) {
            let index = this.car.datelimits.findIndex(item => {
                return item.id == obj.id;
            });

            if (index != -1) {
                this.car.datelimits[index] = obj;

              //  this.$root.$emit("bv::refresh::table", "datelimitRef"); //refresh data trong danh sách reminder -> ở form khác
            } else {
                this.car.datelimits.push(obj);
            }
        },
        reminderAction(obj, type) {
            var index = "";
            switch (type) {
                case "EDIT":
                    index = this.car.reminders.findIndex(
                        data => data.id == obj.id
                    );
                    this.reminder_id = obj.id;
                    $("#createEventModal").modal("show");
                    break;
                case "DELETE":
                    index = this.car.reminders.findIndex(
                        data => data.id == obj.id
                    );
                    this.car.reminders.splice(index, 1);
                    this.reminder_id = "";
                    //gọi ham delete
                    break;
                case "SHOW":
                    this.reminder_id = "";
                    $("#createEventModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
        fastprocessAction(obj, type) {
            var index = "";
            switch (type) {
                case "EDIT":
                    // index = this.car.fast_process.findIndex(
                    //     data => data.id == obj.id
                    // );
                    this.fast_process_id = obj;
                   // console.log(obj);
                    $("#createFastProcessModal").modal("show");
                    break;
                case "DELETE":
                     this.fast_process_id = obj;
                    break;
                case "SHOW":
                    this.fast_process_id = "";
                    $("#createFastProcessModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
        causeAction(obj, type) {
            var index = "";
            switch (type) {
                case "EDIT":
                    // index = this.car.causes.findIndex(
                    //     data => data.id == obj.id
                    // );
                    this.cause_id = obj;
                   // console.log(obj);
                    $("#createCauseModal").modal("show");
                    break;
                case "DELETE":
                     this.cause_id = obj;
                    break;
                case "SHOW":
                    this.cause_id = "";
                    $("#createCauseModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
         assignAction(obj, type) {
            switch (type) {
                case "EDIT":
                    this.assign_id = obj;
                   // console.log(obj);
                    $("#createAssignModal").modal("show");
                    break;
                case "DELETE":
                     this.assign_id = obj;
                    break;
                case "SHOW":
                    this.assign_id = "";
                    $("#createAssignModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
        monitorAction(obj, type) {
            switch (type) {
                case "EDIT":
                    this.monitor_id = obj;
                    $("#createMonitorModal").modal("show");
                    break;
                case "DELETE":
                    this.monitor_id = obj;
                    //gọi ham delete
                    break;
                case "SHOW":
                    this.monitor_id = "";
                    $("#createMonitorModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
        resultAction(obj, type) {
            var index = "";
            switch (type) {
                case "EDIT":
                    this.result_id = obj;
                    $("#createResultModal").modal("show");
                    break;
                case "DELETE":
                    this.result_id = obj;
                    break;
                case "SHOW":
                    this.result_id = "";
                    $("#createResultModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
     datelimitAction(obj, type) {
            var index = "";
            switch (type) {
                case "EDIT":
                    this.datelimit_id = obj;
                    $("#createDateLimitModal").modal("show");
                    break;
                case "DELETE":
                    this.datelimit_id = obj;
                    break;
                case "SHOW":
                    this.datelimit_id = "";
                    $("#createDateLimitModal").modal("show");
                    //gọi ham delete
                    break;
                default:
                    break;
            }
        },
        getcar() {
            // console.log(this.id);
            if (this.id != "") {
                this.loading = true;
            }

            var page_url =
                this.page_url_car +
                "/" +
                this.id +
                "?notification_id=" +
                this.notification_id;
           // console.log(page_url);
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log(res);
                    if (res.data.success == "1") {
                        this.car = res.data;
                        //car_error_id = this.car.car_error_id;
                        if(this.car.other_attacheds.length>0)
                        this.other_attacheds = 1;
                        else
                        this.other_attacheds = 0;
                        if(this.car.cause_measure.length>0)
                        this.cause_measure = 1;
                        else
                        this.cause_measure = 0;
                        if(this.car.monitor_implementation.length>0)
                        this.monitor_implementation = 1;
                        else
                        this.monitor_implementation = 0;
                        if(this.car.result_evaluation.length>0)
                         this.result_evaluation = 1;
                        else
                          this.result_evaluation = 0;
                        if(this.car.fast_process.length>0)
                        this.fast_process = 1;
                        else
                        this.fast_process = 0;
                        if(this.car.document_type.code=='SCAR')
                        this.scar_type = 1;
                        else
                        this.scar_type = 0;
                         if(this.car.document_type.code=='PCAR')
                        this.pcar_type = 1;
                        else
                        this.pcar_type = 0;
                        this.shareds =  this.car.shareds;
                       //console.log(this.car_types );
                        //console.log(this.car.monitor_implementation);
                        //console.log(this.car.car_error_id);
                        this.car.other_attacheds_del = [];

                        //khởi tạo biến mảng attachment_file_removed

                        this.car.other_attacheds.forEach(element => {
                            element.other_attacheds_del = [];
                        });

                        this.loading = false;
                    }
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        print() {
            window.location.href =
                this.page_url_car_print + "/" + this.car.id;
        },
        downloadFile(idfile, filename) {
            var page_url = "api/car/downloadFile/" + idfile;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                responseType: "arraybuffer",
                method: "post"
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
                    var link = document.createElement("a");
                    link.href = data;
                    link.download = filename;
                    // link.target = '_blank';
                    link.click();

                    setTimeout(function() {
                        // For Firefox it is necessary to delay revoking the ObjectURL
                        URL.revokeObjectURL(data);
                    }, 100);
                })
                .catch(err => console.log(err));
        },
        getIcon(ext) {
            return getIconFile(ext);
        },
        showLabel(fieldName, value) {
            if (fieldName in this.layout) {
                if (this.layout[fieldName]["has_custom_label"]) {
                    return this.layout[fieldName]["custom_label_text"];
                }
            }
            return value;
        },
        showControl(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]["isVisible"];
            }
            return false;
        },
        backToList() {
            //console.log("this.pre_url="+this.pre_url);
            window.location.href = this.pre_url;
        },
       fastprocessedel(id){
         //  console.log(this.user_id);
          var page_url = this.page_url_fast_processe+"/"+id;
          this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+ '?',
           {
               okVariant:"danger",
               okTitle:"Ok",
               cancelTitle:"Cancel",
               centered:true
           }).then(value=>{
               if(value){

                     fetch(page_url,{
                        method:'DELETE',
                        body:JSON.stringify({'id':this.id}),
                        headers:{
                            Authorization:this.token,
                            'Content-Type':'application/json',
                            "Accept": "application/json",
                            'X-Requested-With':'XMLHttpRequest'
                        }
                        }).then(res=>res.json())
                        .then(res=>{
                                if(res.data.success == '1'){
                                    toastr.success(this.$t('form.delete_success'),"");
                                    this.getcar();
                                    //this.refresh();
                                    //this.$emit('fastprocessAction',reminder,'DELETE');
                                }
                                 if(res.data.success == '0'){
                                    toastr.warning(res.data.message,"");
                                }
                        }).catch(err=>{
                            console.log(err);
                            toastr.warning(this.$t('form.delete_error'),"");
                        });
               }
           });
      },
      causedel(id){
         //  console.log(this.user_id);
          var page_url = this.page_url_cause+"/"+id;
          this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+ '?',
           {
               okVariant:"danger",
               okTitle:"Ok",
               cancelTitle:"Cancel",
               centered:true
           }).then(value=>{
               if(value){

                     fetch(page_url,{
                        method:'DELETE',
                        body:JSON.stringify({'id':this.id}),
                        headers:{
                            Authorization:this.token,
                            'Content-Type':'application/json',
                            "Accept": "application/json",
                            'X-Requested-With':'XMLHttpRequest'
                        }
                        }).then(res=>res.json())
                        .then(res=>{
                                if(res.data.success == '1'){
                                    toastr.success(this.$t('form.delete_success'),"");
                                    this.getcar();
                                    //this.refresh();
                                    //this.$emit('fastprocessAction',reminder,'DELETE');
                                }
                                 if(res.data.success == '0'){
                                    toastr.warning(res.data.message,"");
                                }
                        }).catch(err=>{
                            console.log(err);
                            toastr.warning(this.$t('form.delete_error'),"");
                        });
               }
           });
      },
      monitordel(id){
         //  console.log(this.user_id);
          var page_url = this.page_url_monitor_implementation+"/"+id;
          this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+ '?',
           {
               okVariant:"danger",
               okTitle:"Ok",
               cancelTitle:"Cancel",
               centered:true
           }).then(value=>{
               if(value){

                     fetch(page_url,{
                        method:'DELETE',
                        body:JSON.stringify({'id':this.id}),
                        headers:{
                            Authorization:this.token,
                            'Content-Type':'application/json',
                            "Accept": "application/json",
                            'X-Requested-With':'XMLHttpRequest'
                        }
                        }).then(res=>res.json())
                        .then(res=>{
                                if(res.data.success == '1'){
                                    toastr.success(this.$t('form.delete_success'),"");
                                    this.getcar();
                                    //this.refresh();
                                    //this.$emit('fastprocessAction',reminder,'DELETE');
                                }
                                 if(res.data.success == '0'){
                                    toastr.warning(res.data.message,"");
                                }
                        }).catch(err=>{
                            console.log(err);
                            toastr.warning(this.$t('form.delete_error'),"");
                        });
               }
           });
      },
     resultdel(id){
         //  console.log(this.user_id);
          var page_url = this.page_url_result_evaluation+"/"+id;
          this.$bvModal.msgBoxConfirm(this.$t('Xác nhận xoá')+ '?',
           {
               okVariant:"danger",
               okTitle:"Ok",
               cancelTitle:"Cancel",
               centered:true
           }).then(value=>{
               if(value){

                     fetch(page_url,{
                        method:'DELETE',
                        body:JSON.stringify({'id':this.id}),
                        headers:{
                            Authorization:this.token,
                            'Content-Type':'application/json',
                            "Accept": "application/json",
                            'X-Requested-With':'XMLHttpRequest'
                        }
                        }).then(res=>res.json())
                        .then(res=>{
                                if(res.data.success == '1'){
                                    toastr.success(this.$t('form.delete_success'),"");
                                    this.getcar();
                                    //this.$emit('fastprocessAction',reminder,'DELETE');
                                }
                                 if(res.data.success == '0'){
                                    toastr.warning(res.data.message,"");
                                }
                        }).catch(err=>{
                            console.log(err);
                            toastr.warning(this.$t('form.delete_error'),"");
                        });
               }
           });
      },
           refresh() {
             location.reload();
     },
    }
};
</script>
<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
    margin-top: 1px !important;
}
.my-custom-scrollbar {
position: relative;
max-height: 650px;
overflow: auto;
}
.table-wrapper-scroll-y {
display: block;
}
.callout.callout-info {
    border-left-color: #e9ecef;
}
</style>
