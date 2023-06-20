<template>
  <div>
        <form @submit.prevent="AddContract"  @keydown.enter.prevent="clearError" id="form">
      <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/payment/contracts">Hợp đồng</a> </h5></li>
              
                <li class="breadcrumb-item active">
                  <span v-if="edit_contract">Chỉnh sửa</span> 
                  <span v-else>Tạo mới</span> 
                  
                  </li>
             </ol>
            </div> 
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                      <a href="/payment/contracts" class="btn btn-default ">Huỷ</a>   
                        <button type="submit"  :disabled="loading"  value="Lưu" class="btn btn-primary"> Lưu hợp đồng</button>
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
             <div class="card">
              <div class="card-header" >
                <ul class="nav nav-tabs card-header-tabs">
                  <li class="nav-item">
                      <a class="nav-link active" id="thongtinchung-tab" data-toggle="tab" href="#thongtinchung" role="tab" aria-controls="thongtinchung" aria-selected="true">
                        <i class="fas fa-edit"></i>
                          Thông tin chung
                        </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="dieukhoanthanhtoan-tab" data-toggle="tab" href="#dieukhoanthanhtoan" role="tab" aria-controls="dieukhoanthanhtoan" aria-selected="false">
                        <i class="fas fa-file-contract"></i>
                        Điều khoản thanh toán
                        </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="attachfile-tab" data-toggle="tab" href="#attachfile" role="tab" aria-controls="attachfile" aria-selected="false">
                        <i class="fas fa-file"></i> 
                         File đính kèm
                        </a>
                    </li>
                    <li class="nav-item" v-if="!contract.parent">
                      <a class="nav-link" id="phuluc-tab" data-toggle="tab" href="#phuluc" role="tab" aria-controls="phuluc" aria-selected="false">
                        <i class="fas fa-info-circle"></i>
                        Phụ lục hợp đồng
                        </a>
                    </li>
                </ul>

               <div class="card-tools" style="margin-top:-23px;margin-bottom:0px;height:10px">
                      <span v-if="contract.parent">(Phụ lục của HĐ số : <a href="#" @click.prevent.stop="viewContract(contract_parent.id)">{{contract_parent.contract_num}}</a> )</span>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="thongtinchung" role="tabpanel" aria-labelledby="thongtinchung-tab">
                    
                    <!-- <div class="row">
                      <div class="col-md-6">
                          <div class="form-group row">
                             <label  class="col-form-label-sm col-3" style="text-align:left" for="">Công ty <small class="text-red">( * )</small></label>
                            <div class="col-md-9">
                               <select class="form-control form-control-sm" 
                                  name="company_id"
                                  v-model="contract.company_id"
                                  v-bind:class="hasError('company_id')?'is-invalid':''"
                                  @change="clearError($event)"
                                  >
                                    <option
                                          v-for="company in companies"
                                          :key="company.id"
                                          v-bind:value="company.id"
                                      >
                                          {{ company.name }}
                                    </option>
                                  </select>
                                <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                              <strong>{{getError('company_id')}}</strong>
                                              
                                  </span>
                            </div>
                             
                        </div>
                      </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-form-label-sm col-3" style="text-align:left" for="">Phòng ban<small class="text-red">( * )</small></label>
                            <div class="col-md-9">
                             <select class="form-control form-control-sm" 
                                name="department_id"
                                v-model="contract.department_id"
                                v-bind:class="hasError('department_id')?'is-invalid':''"
                                  @change="clearError($event)"
                                >
                                  <option
                                        v-for="department in department_filter"
                                        :key="department.id"
                                        v-bind:value="department.id"
                                    >
                                        {{ department.name }}
                                  </option>
                                </select>
                                <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                                            <strong>{{getError('department_id')}}</strong>
                                            
                                </span>
                            </div>
                             
                        </div>  
                      </div>
                    </div> -->
                     <div class="row  ">
                      <div class="col-md-6">
                           <div class="form-group row">
                          <label  class="col-form-label-sm col-3 " style="text-align:left" for="">
                             <span v-if="!contract.parent">Số hợp đồng</span> 
                            <span v-if="contract.parent">Số phụ lục HĐ</span> 
                            <small class="text-red">( * )</small>
                          </label>
                          <div class="col-md-9">
                             <input type="text" class="form-control form-control-sm"
                              id="contract_num" 
                              v-model="contract.contract_num"
                                v-bind:class="hasError('contract_num')?'is-invalid':''" 
                                name="contract_num"
                                placeholder="" required/>
                              <span v-if="hasError('contract_num')" class="invalid-feedback" role="alert">
                                <strong>{{getError('contract_num')}}</strong>
                            </span>
                          </div>
                        </div>   
                      </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-form-label-sm col-3" style="text-align:left" for="">Loại HĐ <small class="text-red">( * )</small></label>
                            <div class="col-md-9">
                              <select class="form-control form-control-sm" 
                              v-model="contract.contract_type" 
                              v-bind:class="hasError('contract_type')?'is-invalid':''"
                                @change="clearError($event)"
                                required :disabled="edit_contract"
                              >
                                <option value="1" selected>HĐ Số tiền cố định</option>
                                <option value="2">HĐ phát sinh theo chu kỳ</option>
                                <option value="3">HĐ nguyên tắc</option>
                              </select>
                              <span v-if="hasError('contract_type')" class="invalid-feedback" role="alert">
                                          <strong>{{getError('contract_type')}}</strong>
                                    
                                </span>
                            </div>
                           
                        </div> 
                      </div>
                     </div>
                     <div class="row ">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label  class="col-form-label-sm col-3" style="text-align:left" for="">Người ký<small class="text-red">( * )</small> </label>
                          <div class="col-md-9">
                            <input type="text" v-model="contract.b_signer"  class="form-control form-control-sm"
                              v-bind:class="hasError('b_signer')?'is-invalid':''" 
                                  name="b_signer"
                                  id="b_signer"
                                  placeholder="" required/>
                                <span v-if="hasError('b_signer')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('b_signer')}}</strong>
                        </span>
                          </div>
                          
                        </div>   
                      </div>
                       <div class="col-md-6">
                        <div class="form-group row">
                          <label for=""  class="col-form-label-sm  col-3 " style="text-align:left">Chức vụ<small class="text-red">( * )</small></label>
                          <div class="col-md-9">
                             <input type="text" v-model="contract.b_position" class="form-control form-control-sm"
                              v-bind:class="hasError('b_position')?'is-invalid':''" 
                                  name="b_position"
                                  id="b_position"
                                  placeholder="" required/>
                                <span v-if="hasError('b_position')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('b_position')}}</strong>
                              </span>
                          </div>
                          
                        </div>  
                      </div>
                     </div>

                      <div class="row  ">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-form-label-sm col-3" for="" style="text-align:left">Ngày ký<small class="text-red">( * )</small> </label>
                            <div class="col-md-9">
                             <input type="date"   class="form-control form-control-sm" data-date=""    data-date-format="DD/MM/YYYY" 
                                v-model="contract.sign_date"
                                v-bind:class="hasError('sign_date')?'is-invalid':''" 
                                  name="sign_date"
                                  id="sign_date"
                                  placeholder="" 
                                @click="clearError($event)"
                                  @change="clearError($event)"
                                  required/>
                                <span v-if="hasError('sign_date')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('sign_date')}}</strong>
                              </span>
                            </div>
                           
                        </div> 
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                           <label  class="col-form-label-sm  col-3" for="" style="text-align:left">Ngày hiệu lực<small class="text-red">( * )</small> </label>
                            <div class="col-md-9">
                               <input type="date"  class="form-control form-control-sm" data-date="" data-date-format="DD/MM/YYYY" 
                                v-model="contract.date_begin"
                                v-bind:class="hasError('date_begin')?'is-invalid':''" 
                                    name="date_begin"
                                    id="date_begin"
                                    @click="clearError($event)"
                                    @change="clearError($event)"
                                    placeholder="" required/>
                                  <span v-if="hasError('date_begin')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('date_begin')}}</strong>
                                </span>
                            </div>
                        </div>
                        </div>
                       </div>

                       <div class="row  ">
                        <div class="col-md-6">
                          <div class="form-group row">
                          <label  class="col-form-label-sm col-3" for="" style="text-align:left">Loại tiền<small class="text-red">( * )</small></label>
                          <div class="col-md-2">
                            <select name="" id="" 
                             v-model="contract.money_type" 
                              v-bind:class="hasError('money_type')?'is-invalid':''"
                                @change="clearError($event)"
                                required  
                            
                            class="form-control form-control-sm">
                              <option value="1">VNĐ</option>
                              <option value="2">USD</option>
                            </select>
                             <span v-if="hasError('money_type')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('money_type')}}</strong>
                                </span>
                          </div>
                          <label  class="col-form-label-sm col-3" for="" style="text-align:left">Giá trị HĐ<small class="text-red">( * )</small></label>
                          <div class="col-md-4">
                            
                            <input type="text"   class="form-control form-control-sm"
                              v-bind:class="hasError('amount')?'is-invalid':''" 
                                  name="amount"
                                  id="amount"
                                  
                                  required 
                                  
                                  />
                                <span v-if="hasError('amount')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('amount')}}</strong>
                              </span>
                          </div>
                        </div>  
                        </div>
                        <div class="col-md-6">
                        <div class="form-group row">
                        <!-- <label  class="col-form-label-sm col-3" for="" style="text-align:left">Tiêu đề </label>
                          <div class="col-md-9">
                             <input type="text" class="form-control form-control-sm"
                              id="title" v-model="contract.title" 
                                v-bind:class="hasError('title')?'is-invalid':''" 
                                name="title"
                                placeholder="" />
                              <span v-if="hasError('title')" class="invalid-feedback" role="alert">
                                <strong>{{getError('title')}}</strong>
                            </span>
                          </div> -->
                        </div>
                        </div>
                       </div>
                     <div class="row border border-light rounded-sm pt-2">
                      <div class="col-md-6">
                         <div class="form-group row">
                          <label  class="col-form-label-sm col-3" for="" style="text-align:left">Nhà cung cấp<small class="text-red">( * )</small> </label>
                          <div class="col-md-9">
                             <select class="form-control form-control-sm"
                                v-model="contract.vendor_id"
                                name="vendor_id"
                                id="vendor_id"
                                v-bind:class="hasError('vendor_id')?'is-invalid':''"
                                @change="clearError($event)"
                                
                                >
                                <option
                                        v-for="vendor in vendor_filter"
                                        :key="vendor.id"
                                        v-bind:value="vendor.id"
                                    >
                                        {{ vendor.comp_name  }}
                                  </option>
                                </select>
                                <span v-if="hasError('vendor_id')" class="invalid-feedback" role="alert">
                                            <strong>{{getError('vendor_id')}}</strong>
                                            
                                </span>
                          </div>
                        </div>   
                         <div class="form-group row">
                          <label  class="col-form-label-sm col-3" for="" style="text-align:left">Đại diện<small class="text-red">( * )</small> </label>
                          <div class="col-md-9">
                            <input type="text" v-model="contract.a_signer" class="form-control form-control-sm"
                              v-bind:class="hasError('a_signer')?'is-invalid':''" 
                                  name="a_signer"
                                  id="a_signer"
                                  placeholder="" required/>
                                <span v-if="hasError('a_signer')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('a_signer')}}</strong>
                        </span>
                          </div>
                          
                        </div>    
                                    
                    </div>

                    <div class="col-md-6">
                         
                        <div class="form-group row">
                          <label for=""  class="col-form-label-sm col-3 " style="text-align:left">Chức vụ<small class="text-red">( * )</small></label>
                          <div class="col-md-9">
                              <input type="text" v-model="contract.a_position" class="form-control form-control-sm"
                                v-bind:class="hasError('a_position')?'is-invalid':''" 
                                    name="a_position"
                                    id="a_position"
                                    placeholder="" required/>
                                  <span v-if="hasError('a_position')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('a_position')}}</strong>
                                  </span>
                          </div>
                          
                        </div>    
                                                            
                    </div>
                  </div>  
                  <div class="row border border-light rounded-sm pt-2">
                      <div class="col-md-6">
                         <!-- <div class="form-group row">
                          <label  class="col-form-label-sm col-3" for="" style="text-align:left">Tình trạng: </label>
                          <div class="col-md-9" >
                             <select name="" id="" :disabled="true" 
                              v-model="contract.status" 
                              v-bind:class="hasError('status')?'is-invalid':''"
                                @change="clearError($event)"
                                required  
                             
                             
                             class="form-control form-control-sm">
                              <option value="1">Chưa thực hiện</option>
                              <option value="2">Đang thực hiện</option>
                              <option value="3">Đã thanh lý</option>
                              <option value="4">Đã huỷ bỏ</option>
                            </select>
                            <span v-if="hasError('status')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('status')}}</strong>
                                  </span>
                          </div>
                         
                        </div>    -->
                        <div class="form-group row">
                            <label class="col-form-label-sm col-3" for="" style="text-align:left">Ngày kết thúc: </label>
                            <div class="col-md-9">
                               <input type="date"  class="form-control form-control-sm" data-date="" data-date-format="DD/MM/YYYY"  
                                v-model="contract.date_end"
                                v-bind:class="hasError('date_end')?'is-invalid':''" 
                                  name="date_end"
                                  id="date_end"
                                  @click="clearError($event)"
                                  @change="clearError($event)"
                                  placeholder="" />
                                <span v-if="hasError('date_end')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('date_end')}}</strong>
                              </span>
                            </div>
                             
                        </div>  
                        <div class="form-group row">
                            <label class="col-form-label-sm col-3" for="" style="text-align:left">Lý do kết thúc: </label>
                            <div class="col-md-9">
                              <input 
                               v-model="contract.reason_end"
                                v-bind:class="hasError('reason_end')?'is-invalid':''" 
                              
                              type="text" class="form-control form-control-sm">
                               <span v-if="hasError('reason_end')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('reason_end')}}</strong>
                              </span>
                            </div>
                             
                        </div>    
                                    
                    </div>

                    <div class="col-md-6">
                         <div class="form-group row">
                            <label class="col-form-label-sm col-3" for="" style="text-align:left">Diễn giải</label>
                            <div class="col-md-9">
                               <textarea class="form-control  textarea"   v-model="contract.content" rows="3" placeholder=""
                                v-bind:class="hasError('content')?'is-invalid':''" 
                                    name="content"
                                    id="content"
                                    required
                                ></textarea>
                                  <span v-if="hasError('content')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('content')}}</strong>
                                </span>
                            </div>
                             
                        </div> 
                                                        
                    </div>
                  </div>               
              
                  </div>
                  <div class="tab-pane fade" id="dieukhoanthanhtoan" role="tabpanel" aria-labelledby="dieukhoanthanhtoan-tab">
                       <table id="example2" class="table table-bordered table-hover table-sm">
                          <thead>
                          <tr>
                            <th>#</th>
                            <th class="col-form-label-sm">Nội dung</th>
                            <th class="col-form-label-sm" >Ngày dự kiến</th>
                            <th class="col-form-label-sm">Số tiền</th>
                            <th class="col-form-label-sm" style="min-width:100px">Số tiền đã TT</th>
                            <th class="col-form-label-sm" style="width:100px">Ngày TT</th>
                            <th class="col-form-label-sm" style="min-width:100px" v-if="contract.contract_type == 2 || contract.contract_type == 3">Chu kỳ TT</th>
                            <th class="col-form-label-sm" style="width:100px" v-if="contract.contract_type == 2 || contract.contract_type == 3">Số đợt TT</th>
                            <th class="col-form-label-sm">Điều khoản TT</th>
                            <th class="col-form-label-sm">Thanh lý</th>
                            <th style="text-align:center">  <button type="button" class="btn btn-outline-info btn-sm right"  @click.prevent.stop="showContractTermEmpty()" ><i class="fa fa-plus"></i></button></th>
                          
                          </tr>
                          </thead>
                          <tbody>
                          <tr v-for="(term,index) in contract.contract_terms" v-bind:key="index">
                          
                            <td>{{term.terms_num}}</td>
                            <td> {{term.content}}</td>
                            <td> {{term.date_due | formatDate}}</td>
                            
                            <td> {{term.amount | numFormat('0,0')  }}</td>
                            <td>{{term.amount_paid | numFormat('0,0')}}</td>
                            <td>{{term.date_paid | formatDate}}</td>
                            <td v-if="contract.contract_type == 2 || contract.contract_type == 3">  {{chuKyTT(term.frequency,term.frequency_type)}} </td>
                            <td v-if="contract.contract_type == 2 || contract.contract_type == 3">{{term.duration }}  </td>
                            <td><span  v-html="term.term_content"></span></td>
                          <td style="text-align:center">
                            
                              <span  v-if="term.finalization == 1" >Đã thanh lý</span>

                              </td>
                            <td> <a class="btn btn-sm text-info" @click.prevent="editContractTerm(term,index)" href="#">
                              <i class="fas fa-pencil-alt">
                              </i>
                              
                          </a>
                          <a class="btn  btn-sm text-red" @click.prevent="deleteContractTerm(term,index)" href="#">
                              <i class="fas fa-trash">
                              </i>
                            
                          </a></td>
                          </tr>

                          </tbody>
                        
                        </table>
                  </div>
                  <div class="tab-pane fade" id="attachfile" role="tabpanel" aria-labelledby="attachfile-tab">

                 <table class="table table-sm">
                  <thead>
                    <tr>
                      <th class="col-form-label-sm">File Name</th>
                      <th class="col-form-label-sm">File Size</th>
                      <th>
                         <input type="file"
                        accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" 
                          
                          @change="emitEvent($event)"
                          id="ThemFile"
                          style="display:none"
                          ref="file"
                          multiple
                    
                    >
                      <button type="button" class="btn btn-info right btn-sm" 
                      v-on:click="handleClickInputFile()"
                      >  <i class="fas fa-paperclip"></i> </button>
                          </th>
                        </tr>
                      </thead>
                    <tbody>
                    <tr v-for="(file,index) in contract.attachment_file" v-bind:key="index">
                      <td>{{file.name}}</td>
                      <td> 
                        <span v-if="file.size < 1024">{{file.size | numFormat('0')}} B </span>
                        <span v-else-if="file.size < (1024 * 1024)">{{file.size/(1024) | numFormat('0.0')}} KB</span>
                         <span v-else>{{file.size/(1024 * 1024) | numFormat('0.0')}} MB</span>
                      </td>
                      <td class="text-right py-0 align-middle">
                        <div class="btn-group btn-group-sm">
                          <a href="#" v-if="file.id" class="btn text-info" @click.prevent="downloadFile(file.id,file.name)"  ><i class="fas fa-download"></i></a>
                          <a href="#" class="btn text-red"  @click.prevent="deleteFile(file,index)" ><i class="fas fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>
                   </tbody>
                </table>
                  </div>
                   <div class="tab-pane fade" id="phuluc" role="tabpanel" aria-labelledby="phuluc-tab">
                       <table id="example2" class="table table-bordered table-hover table-sm">
                  <thead>
                  <tr>
                    <th class="col-form-label-sm" style="min-width:100px">Số HĐ</th>
                    <th  class="col-form-label-sm" >#</th>
                    <th  class="col-form-label-sm" >Nội dung</th>
                    <th  class="col-form-label-sm"  style="width:100px">Ngày dự kiến</th>
                    <th  class="col-form-label-sm" >Số tiền</th>
                  
                   
                    <th  class="col-form-label-sm" >Điều khoản TT</th>
                    <th  class="col-form-label-sm" >Thanh lý</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(term,index) in contract_childs" v-bind:key="index">
                     <td>{{term.contract_num_child}}</td>
                    <td>{{term.terms_num}}</td>
                    <td> {{term.content}}</td>
                    <td> {{term.date_due | formatDate}}</td>
                    
                    <td> {{term.amount | numFormat('0,0')  }}</td>
                   
 
                    <td><span  v-html="term.term_content"></span></td>
                    <td style="text-align:center">
                   
                      <span v-if="term.finalization == 1" >Đã thanh lý</span>
                       
                      </td>
                   
                  </tr>

                  </tbody>
                
                </table>
                   </div>
                </div>
              </div>
                
            </div>
 
 
          </div>
        </div>
         </form>
        
       <!-- Modal dialog -->
       <div class="modal fade" id="themdieukhoan" tabindex="-1" role="dialog" aria-labelledby="themdieukhoan" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <form action=""  @submit.prevent="AddContractTerm()"   @keydown.enter.prevent="clearError">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm điều khoản</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="form-group row">
                     <div class="col-form-label-sm col-sm-4">
                         Nội dung <small class="text-red">( * )</small>
                     </div>
                    
                    <div class="col-sm-8">
                      <input type="text" class="form-control form-control-sm mb-1"   v-model="contract_term.content" required >
                    </div>
                  </div>
                    <div class="form-group row">
                     <div class="col-form-label-sm col-sm-4">
                         Ngày dự kiến <small class="text-red">( * )</small>
                     </div>
                    <div class="col-sm-8">
                       <input type="date" class="form-control form-control-sm mb-1" v-model="contract_term.date_due" required  data-date="" data-date-format="DD/MM/YYYY"  >
                    </div>
                  </div>
     
                  <div class="form-group row">
                     <div class="col-form-label-sm col-sm-4">
                        Số tiền (VNĐ ) <small class="text-red">( * )</small>
                     </div>
                     <div class="col-sm-8">
                        <input type="text"  class="form-control form-control-sm mb-1"  id="term_amount" required >
                     </div>
                   
                  </div>
                  <div class="form-group row" v-if="contract.contract_type == 2 || contract.contract_type == 3">
                     <div class="col-form-label-sm col-sm-4">
                           Chu kỳ thanh toán    
                     </div>
                     <div class="col-sm-4">
                        <input type="number" min="0" v-model="contract_term.frequency" value="1"  class="form-control form-control-sm mb-1" required>
                     </div>
                       <div class="col-form-label-sm col-sm-4">
                       <select 
                       v-model="contract_term.frequency_type"
                       name="" id="" class="form-control form-control-sm ">
                         <option value="1">Ngày</option>
                         <option value="2" selected>Tháng</option>
                         <option value="3">Năm</option>
                       </select>
                     </div>
                     
                  </div>
                  <div class="form-group row" v-if="contract.contract_type == 2 || contract.contract_type == 3">
                     <div class="col-form-label-sm col-sm-4">
                           Số đợt thanh toán   
                     </div>
                     <div class="col-sm-8">
                        <input type="number"  min="0"   v-model="contract_term.duration" value="" class="form-control form-control-sm mb-1" > 
                     </div>

                  </div>               
                  <div class="form-group col-form-label-sm">
                    Điều khoản TT <small class="text-red">( * )</small>
                      <textarea type="text" class="form-control form-control-sm textarea mb-1 is-invalid" id="term_content" name="term_content" required   >
                      </textarea>
                  </div>
 
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary"    >Lưu</button>
                </div>
            </div>
             </form>
          </div>
           
       </div>
  </div>
</template>

<script>
export default {
  props:{
    id:String,
    parent:String,
    company_id:String,
    
  },

  data () {
    return {
      contract_parent:{},
      contract:{
        parent_id:"",
        contract_type:"1",
        contract_num:"",
        sign_date:"",
        a_signer:"",
        a_position:"",
        b_signer:"",
        b_position:"",
        vendor_id:"",
        date_begin:"",
        date_end:"",
        content:" ",
        amount:"",
        budget_id: "",
        title:"",
        company_id:"",
        department_id:"",
        status:"1",
        money_type:"1",
        reason_end:"",
        contract_terms:[],
        contract_terms_del:[],
        attachment_file:[],
        attachment_file_removed:[],
        parent_contract_terms:[],//danh sách contract_term parent đã được thanh lý
        
      },
      contract_term:{
        date_due:"",
        amount:"",
        content:"",
        note:"",
        id:"",
        term_content:"",
        frequency:"1",
        frequency_type:"2",
        duration:"",
        contract_num_child:"",

        index:""
      },
      parent_contract_term:{
        contract_term_id:"",                
        finalization:"",
         
      },
      
      companies:[],
      departments:[],
      vendors:[],
      contract_childs:[],
      parent_contract_terms_online:[],
     // vendors_combobox:[],
      errors:{},
      loading: false,
      edit_term: false,
      edit_contract:false,
      token:"",
      page_url_contracts : "api/payment/contracts",
      page_url_contract_finalization : "api/payment/contract_finalization",
      page_url_contract_term_finalization : "api/payment/contract_term_finalization",
      page_url_department : "/api/category/departments",
      page_url_company:"/api/category/companies",
      page_url_vendors:"/api/category/vendors",
      
    }
  },
   
  created () {
     this.token = "Bearer " + window.Laravel.access_token;
      this.fetCompany();
      this.fetDepartment();
      this.fetVendor();
      this.fetContractParent();
      this.getContract();
  },
   methods: {
      fetContractParent() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
             if( this.parent != ''){
                this.loading = true;
               var page_url = this.page_url_contracts+"/"+this.parent;
              fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                   this.loading = false;
                   //console.log("Xin chao"+res.data);
                   if(res.data.success == 1){
                    this.contract_parent = res.data;

                    //copy dữ liệu hợp đồng cha và xoá dữ liệu 1 số thuộc tính 
                     this.contract = {...this.contract_parent};
                     this.contract.parent_id = this.parent;
                    //Xoá thuộc tính id vừa copy 
                     this.$delete(this.contract, 'id')
                     this.contract.contract_num = this.contract.contract_num +"/"+(this.contract_parent.childs_count+1) ;
                     this.contract.title = "PL: " + this.contract.title;
                     this.contract.parent = {...this.contract_parent};
                     AutoNumeric.set('#amount', this.contract.amount).options.allowDecimalPadding(false)
                     $('#content').summernote('code',this.contract.content);
                     this.contract.contract_terms = [];
                     this.contract.attachment_file = [];
                    //lọc danh sách điều khoản cha chưa thanh lý
                        //    if(this.contract_parent && this.contract_parent.contract_terms ){
                        //     this.contract_parent.contract_terms.forEach(pterm => {
                        //       if(pterm.finalization == 0){
                        //         this.parent_contract_terms_online.push({...pterm});  
                        //       }
                              
                        //     });
                        // }
                    this.get_parent_contract_term_online();
        
                   }

                })
                .catch(err => {
                  console.log(err);
                   this.loading = false;
                });
             }
            
        },
      get_parent_contract_term_online(){
        if(this.contract_parent && this.contract_parent.contract_terms ){
            this.contract_parent.contract_terms.forEach(pterm => {
              if(pterm.finalization == 0){
                this.parent_contract_terms_online.push({...pterm});  
              }
              
            });
        }
      },
      getContract(){
       // console.log(this.id);
        if( this.id != ''){
           console.log("load id");
            this.loading = true;
             var page_url = this.page_url_contracts+"/"+this.id;
            fetch(page_url,{ headers: { Authorization: this.token } })
            .then(res=>res.json())
            .then(res=>{
              //console.log(res);
              if(res.data.success == '1'){
                  this.contract = res.data;

                  this.contract.contract_terms_del = [];
                  this.contract.attachment_file_removed = [];
                 
                  this.edit_contract = true;
                  this.loading = false;

                  //nếu có parent
                  if(this.contract.parent){
                    this.contract_parent = this.contract.parent;
                    this.get_parent_contract_term_online();
                  }
                    

                //  $('#amount').val(this.contract.amount);
                  AutoNumeric.set('#amount', this.contract.amount).options.allowDecimalPadding(false)
                  $('#content').summernote('code',this.contract.content);
                
                  //lấy danh sách phụ lục
                  if(this.contract && this.contract.childs){
                    this.contract.childs.forEach(child => {
                      child.contract_terms.forEach(term => {
                        
                        term.contract_num_child = child.contract_num;
                        this.contract_childs.push(term);
                        
                        
                    });
                  });
                  }

                // this.vendors_combobox = this.vendor_filter('6000');
                  }else{
                    this.loading = false;
                  }
              
                }).catch(err=>{
                    this.loading = false;
                    console.log(err);
                });

        }else{
         
           
        }
        
     
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
         fetVendor() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_vendors;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.vendors = res.data;
                })
                .catch(err => console.log(err));
        },
        finalizationContract(){

            if(confirm('Xác nhận thanh lý?')){
                this.loading = true;
                  var page_url = this.page_url_contract_finalization;
                  fetch(page_url,{
                    method:"post",
                    body:JSON.stringify({'id':this.contract.id}),
                    headers:{
                        Authorization: this.token,
                            "content-type": "application/json"
                    }
                  })
                  .then(res=>res.json())
                  .then(data =>{
                      this.loading= false;
                      console.log(data);
                      if(data.data.success == 1){
                          toastr.success('Thanh lý thành công','Thông báo');
                      }else{
                        toastr.warning('Thanh lý bị lỗi','Thông báo');
                      }
                  }).catch(err => {
                          this.loading= false;
                          console.log(err);
                  });
            }

          
        },
        setStatusContractTerm(status,term_id,contract_id,index){
          let confirmText = "Xác nhận thanh lý điều khoản";
          let value = status === 1?1: 0;
          if(value == 0)
          {
            confirmText = "Xác nhận huỷ thanh lý điều khoản";
          }
          if(confirm(confirmText)){
            this.loading = true;
            var page_url = this.page_url_contract_term_finalization;
            fetch(page_url,{
              method:"post",
              body:JSON.stringify({'id':term_id,'contract_id':contract_id,'status':value}),
              headers:{
                Authorization:this.token,
                "content-type": "application/json"
              }
            })
            .then(res=>res.json())
            .then(data => {
                  if(data.data.success == 1){
                         
                     let row =  this.contract_childs[index] ;
                     row.finalization = value;
                     this.$set(this.contract_childs,index,{...row});
                      if(data.data.status == '1'){

                          toastr.success('Thanh lý thành công',"Thông báo");
                      }else{
                          toastr.success('Huỷ thanh lý tất toán thành công',"Thông báo");
                      }
                  }else{
                    toastr.warning('Thanh lý bị lỗi','Thông báo');
                  }
            })
            .catch(err=>{
              console.log(err);
              this.loading = false;
            })
          }
         },
         
        AddContract() {
            this.loading= true;
             var page_url = this.page_url_contracts;// "/api/category/serviceCategorys";
             this.contract.amount = AutoNumeric.getNumber('#amount'); //$('#amount').val();
             this.contract.content =  $('#content').summernote('code');
             //kiểm tra nếu có thanh lý điều khoản cha
              this.contract.parent_contract_terms = [];
             if(this.parent_contract_terms_online ){
                this.parent_contract_terms_online.forEach(pterm => {
                  this.parent_contract_term = {};

                 this.parent_contract_term.finalization = pterm.finalization;
                 this.parent_contract_term.contract_term_id = pterm.id;
                 this.contract.parent_contract_terms.push({...this.parent_contract_term});
                });
             }
             if(this.contract.contract_type == 1){
               this.contract.has_date_end = true;
             }else{
                 this.contract.has_date_end = false;
             }
             //console.log(this.contract.amount);
            if(this.edit_contract === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.contract),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                   
                    if (!data.data.errors) {
                        // this.reset();
                      //  this.serviceCategorys.push(data.data);            
                         toastr.success('Lưu thành công',"Thông báo");
                         window.location.href = "/payment/contracts";
                        //$("#AddServiceCategory").modal("hide");
                       
                    }else{

                        this.errors = data.data.errors;
                         
                    }
                    this.loading= false;
                })
                .catch(err => {
                  this.loading= false;
                  console.log(err);
                  });
            }else{
                //update
                 fetch(page_url+"/"+this.contract.id, {
                method: "PUT",
                body: JSON.stringify(this.contract),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                  //  console.log(data);
                    if (!data.data.errors) {
                         toastr.success('Cập nhật thành công',"Thông báo");
                         window.location.href = "/payment/contracts";
                    }else{
                        this.errors = data.data.errors;   
                    }
                     this.loading= false;
                })
               .catch(err => {
                  this.loading= false;
                  console.log(err);
                  });
            }
          
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
        AddContractTerm(){

          this.contract_term.amount = AutoNumeric.getNumber('#term_amount');// $('#term_amount').val();
          this.contract_term.term_content = $('#term_content').summernote('code');
          if( this.edit_term == false){
            
            this.contract_term.terms_num = this.contract.contract_terms.length + 1;
            this.contract.contract_terms.push({...this.contract_term});
            this.resetContractTerm();
          }else{
              this.$set(this.contract.contract_terms, this.contract_term.index, {...this.contract_term});
              this.resetContractTerm();
              this.edit_term = false;
          }

             $("#themdieukhoan").modal("hide");
             $("#dieukhoanhopdong").collapse("show");
             
          
        },
        showContractTermEmpty(){
           this.edit_term = false;

             $('#term_content').summernote('code','' ) ;
            this.resetContractTerm();
            this.contract_term.frequency = "1";
            this.contract_term.frequency_type = "2";

              $("#themdieukhoan").modal("show");
        },
        editContractTerm(item,index){
          
            this.edit_term = true;
            this.contract_term.id           = item.id;
            this.contract_term.terms_num    = item.terms_num;
            this.contract_term.content      = item.content;
            this.contract_term.date_due     = item.date_due;
            this.contract_term.amount         = item.amount;
            this.contract_term.note         = item.note;
            this.contract_term.term_content = item.term_content;
            this.contract_term.frequency = item.frequency;
            this.contract_term.frequency_type = item.frequency_type;
            this.contract_term.duration = item.duration;
            this.contract_term.index        = index;
            //$('#term_amount').val(this.contract_term.amount);
            AutoNumeric.set('#term_amount',this.contract_term.amount).options.allowDecimalPadding(false);
            $('#term_content').summernote('code',this.contract_term.term_content ) ;
              $("#themdieukhoan").modal("show");
        },
        deleteContractTerm(item,index){

            if(confirm('Xác nhận xoá?')){
                this.contract.contract_terms_del.push({...item});
                this.contract.contract_terms.splice(index,1);
            }

        },
        resetContractTerm(){

           //$('#term_amount').val('');
           AutoNumeric.set('#term_amount',0).options.allowDecimalPadding(false);
          for(let field in this.contract_term){
                this.contract_term[field] = null;
            }
        },
         handleClickInputFile() {
            
            this.$refs.file.click();
     
        },
        emitEvent(event) {
           
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
                 // console.log(docs);
                  this.contract.attachment_file.push({...docs});

              };
            }

              $("#filedinhkem").collapse("show");
           
        },
        deleteFile(item,index){
           if(confirm('Bạn muốn xoá file?')){
                
                this.contract.attachment_file_removed.push({...item});
                this.contract.attachment_file.splice(index,1);
            }
        },
         viewContract(id){
        window.location.href= "payment/contracts?type=view&id="+id;
        },
        chuKyTT(frequency,type){
          let txt = "";
          if(frequency > 0){

            if(type == 1)
              txt = "ngày";
              else if(type == 2)
              txt =  "tháng";
              else txt =  "năm";

            txt = frequency + " "+ txt ;
          }

            return txt;

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
          // console.log(event.target.name);
        },
        reset(){
            // this.clearAllError();
            // this.edit = false;
            // for(let field in this.team){
            //     this.team[field] = null;
            // }
        },
        clearAllError(){
            this.errors = {};
        },
                    
            
  },
   computed: {
        hasAnyError(){
            return Object.keys(this.errors).length > 0;
        },
        //  contract_childs(){
        //   let child_terms  = [];
        //   if(this.contract && this.contract.childs){
        //   this.contract.childs.forEach(child => {
        //      child.contract_terms.forEach(term => {
        //        term.contract_num_child = child.contract_num;
        //        child_terms.push(term);
        //      });
        //   });
        //   }

        //   return child_terms;
        // },
        department_filter(){

            let company_id ="";
          if(this.edit_contract){
             company_id = this.contract.company_id;
          }else{
              company_id = this.company_id;
          }
         
         // this.contract.department_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
        vendor_filter(){
          let company_id ="";
            if(this.edit_contract){
             company_id = this.contract.company_id;
          }else{
              company_id = this.company_id;
          }
          // let company_id = this.contract.company_id;
         // this.contract.vendor_id = "";
          return this.vendors.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        }
    },

}


</script>
<style scoped>
 .form-group {
    margin-bottom: 1px  !important;
}

</style>
 