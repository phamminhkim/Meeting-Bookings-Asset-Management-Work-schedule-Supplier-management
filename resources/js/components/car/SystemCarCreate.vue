<template>
  <div>
        <form @submit.prevent="AddCar"  @keydown.enter.prevent="clearError" id="form">
      <div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h6 class="m-0 text-dark" style="padding-top: 8px;"> <i
                                        class="fa fa-angle-double-right" style="color: #74b7c9;"
                                    ></i> <a href="car/systems">{{ $t(pre_title)}}</a> </h6></li>
              
                <li class="breadcrumb-item active" style="padding-top: 3px; font-size: 14px;">
                  <span v-if="edit">{{ $t('form.edit')}}</span> 
                  <span v-else>Tạo mới</span> 
                  
                  </li>
             </ol>
            </div> 
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                      <a href="car/systems" class="btn btn-default "><i class="fas fa-times"></i> Đóng</a>   
                        <button type="submit"  :disabled="loading"  value="Lưu" class="btn btn-info"><i class="fas fa-save"></i> Lưu </button>
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
                       
                          Thông tin chung
                        </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="attachfile-tab" data-toggle="tab" href="#attachfile" role="tab" aria-controls="attachfile" aria-selected="false">
                      
                         File đính kèm
                        </a>
                    </li>
                </ul>

               <div class="card-tools" style="margin-top:-23px;margin-bottom:0px;height:10px">
                      <span v-if="car.parent">(Phụ lục của HĐ số : <a href="#" @click.prevent.stop="viewCar(contract_parent.id)">{{contract_parent.contract_num}}</a> )</span>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="thongtinchung" role="tabpanel" aria-labelledby="thongtinchung-tab">
                     <div class="row  ">
                      <div class="col-md-6">
                           <div class="form-group row">
                          <label  class="col-form-label-sm col-3 " style="text-align:left" for="">
                             <span>Số lần tái diễn</span> 
                            <small class="text-red">( * )</small>
                          </label>
                          <div class="col-md-9">
                             <input type="number" class="form-control form-control-sm"
                              id="issue_count" 
                                v-model="car.issue_count"
                                v-bind:class="hasError('issue_count')?'is-invalid':''" 
                                name="issue_count"
                                placeholder=""
                                min="0"
                                v-on:keypress="NumbersOnly" />
                              <span v-if="hasError('issue_count')" class="invalid-feedback" role="alert">
                                <strong>{{getError('issue_count')}}</strong>
                            </span>
                          </div>
                        </div>   
                      </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-form-label-sm col-3" style="text-align:left" for="">Công ty <small class="text-red">( * )</small></label>
                            <div class="col-md-9">
                                    <select name="" id="" v-model="car.company_id" v-bind:class="hasError('company_id')?'is-invalid':''" class="form-control form-control-sm mt-1">
                                        <option
                                            v-for="company in companies"
                                                :key="company.id"
                                                v-bind:value="company.id"
                                            >
                                                {{ company.name }}
                                            </option>
                                        <option value="">All</option>
                                    </select>
                            
                              <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                          <strong>{{getError('company_id')}}</strong>
                                    
                                </span>
                            </div>
                           
                        </div> 
                      </div>
                     </div>
                     <div class="row ">
                       <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-form-label-sm col-3" for="" style="text-align:left">Ngày<small class="text-red">( * )</small> </label>
                            <div class="col-md-9">
                             <input type="date"   class="form-control form-control-sm" data-date=""  data-date-format="DD/MM/YYYY" 
                                v-model="car.issue_date"
                                v-bind:class="hasError('issue_date')?'is-invalid':''" 
                                  name="issue_date"
                                  id="issue_date"
                                  placeholder="" 
                                @click="clearError($event)"
                                  @change="clearError($event)"
                                  />
                                <span v-if="hasError('issue_date')" class="invalid-feedback" role="alert">
                                  <strong>{{getError('issue_date')}}</strong>
                              </span>
                            </div>
                           
                        </div> 
                        </div>
                       <div class="col-md-6">
                         <div class="form-group row">
                          <label for=""  class="col-form-label-sm col-3 " style="text-align:left">Phòng ban<small class="text-red">( * )</small></label>
                          <div class="col-md-9">
                         
                            <select name="id_bophan" id="id_bophan" v-model="car.department_id" v-bind:class="hasError('department_id')?'is-invalid':''"  class="form-control form-control-sm mt-1">
                                <option
                                    v-for="department in department_filter"
                                    :key="department.id"
                                    v-bind:value="department.id"
                                    >
                                    {{ department.name }}
                                    </option>
                                <option value="">All</option>
                            </select>
                                  <span v-if="hasError('department_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('department_id')}}</strong>
                                  </span>
                          </div>
                          
                        </div>   
                      </div>
                     </div>


                    <div class="row  ">
                        <div class="col-md-6" v-if="this.doctype=='PCAR'">
                        <div class="form-group row">
                                <label for="group_id" class="col-form-label-sm col-3">Loại <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="type_car_id" id="type_car_id" v-model="car.type_car_id"   v-bind:class="hasError('type_car_id')?'is-invalid':''"  class="form-control form-control-sm mt-1">
                                  <option
                                      v-for="typecar in typecars"
                                      :key="typecar.id"
                                      v-bind:value="typecar.id"
                                      >
                                      {{ typecar.name }}
                                      </option>
                                  <option value="">All</option>
                                </select>
                                 <span v-if="hasError('type_car_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('type_car_id')}}</strong>
                                </span>
                                </div>
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>  
                                                           
                    </div>
                      <div class="col-md-6" v-if="this.doctype=='SCAR'">
                          <div class="form-group row">
                                <label for="group_id" class="col-form-label-sm col-3">Tiêu chuẩn <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="standard_id" id="standard_id" v-model="car.standard_id"   v-bind:class="hasError('standard_id')?'is-invalid':''"  class="form-control form-control-sm mt-1">
                                  <option
                                      v-for="standard in standards"
                                      :key="standard.id"
                                      v-bind:value="standard.id"
                                      >
                                      {{ standard.name }}
                                      </option>
                                  <option value="">All</option>
                                </select>
                                 <span v-if="hasError('standard_id')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('standard_id')}}</strong>
                                </span>
                                </div>
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>
                        </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                                <label for="group_id" class="col-form-label-sm col-3">Nhóm <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="group_id" id="group_id" 
                                    v-model="car.group_id" 
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
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>  
                                                           
                    </div>
                  </div> 
                   <div class="row" v-if="car.user_id !=user_id && car.user_id">
                      <div class="col-md-6">
                          <div class="form-group row">
                                <label for="group_id" class="col-form-label-sm col-3">Trạng thái <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="status" id="status" v-model="car.status"   v-bind:class="hasError('status')?'is-invalid':''"  class="form-control form-control-sm mt-1">
                                      <option v-bind:value = 2 >{{$t('Duyệt hoàn tất')}}</option>
                                      <option v-bind:value = 1 >{{$t('Đang xử lý')}} </option>
                                      <option v-bind:value = -1>{{$t('Đã huỷ')}} </option>
                                      <option v-bind:value = -2 >{{$t('Từ chối')}} </option>
                                      <option v-bind:value = 0>{{$t('Yêu cầu mới')}} </option>
                                      <option v-bind:value = -10>{{$t('Ẩn')}} </option>
                                </select>
                                 <span v-if="hasError('status')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('status')}}</strong>
                                </span>
                                </div>
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>
                        </div>
                    <div class="col-md-6" v-if="this.doctype=='PCAR' ">
                        <div class="form-group row">
                                <label for="proposer" class="col-form-label-sm col-3">Nhân viên QA <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                  <select name="proposer" id="proposer" 
                                    v-model="car.proposer" 
                                    v-bind:class="hasError('proposer')?'is-invalid':''" 
                                    
                                    @click="clearError($event)"
                                    @change="clearError($event)"
                                  class="form-control form-control-sm">
                                  <option v-for="proposer in list_qas" v-bind:key="proposer.id"   v-bind:value="proposer.id">{{proposer.text}}</option>
                                </select>
                                 <span v-if="hasError('proposer')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('proposer')}}</strong>
                                </span>
                                </div>
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>                              
                    </div>
                  </div>
                  <div class="row" v-if="car.user_id !=user_id  && car.user_id">
                      <div class="col-md-6">
                          <div class="form-group row">
                                <label for="serial_num" class="col-form-label-sm col-3">Số phiếu <small class="text-red">( * )</small></label>
                                <div class="col-md-4">
                                <input class="form-control form-control-sm" v-model="car.serial_num" v-bind:class="hasError('serial_num')?'is-invalid':''" >
                                 <span v-if="hasError('serial_num')" class="invalid-feedback" role="alert">
                                    <strong>{{getError('serial_num')}}</strong>
                                </span>
                                </div>
                                 <div class="col-md-2">
                                    <!-- <button type="button" class="btn  btn-info btn-sm">Thông tin duyệt</button> -->
                                 </div>
                               
                              </div>
                        </div>
                    <div class="col-md-6">                             
                    </div>
                  </div>          
                  <div class="row">
                    <div class="col-md-12">
             
                             <ckeditor
                              v-model="car.content"
                              v-bind:config="editorConfig"
                              v-bind:class="editorClass"
                              name="content"
                              id="content"
                              
                            ></ckeditor>  
  
                             <span v-if="hasError('content')" class="invalid-feedback" role="alert">
                              <strong>{{getError('content')}}</strong>
                          </span>
                           
                                                            
                    </div>
                  </div>              
                  </div>

                  <div class="tab-pane fade" id="attachfile" role="tabpanel" aria-labelledby="attachfile-tab">

                              <div class="col-md-12">
                                                <!-- <button class="btn btn-outline-info btn-sm mb-2 " @click.prevent.stop="popupPaycatsetSearch()" ><i class="fa fa-file" ></i> Bộ danh mục chứng từ thanh toán</button> -->
                                                <input type="file"
                                                    accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf"
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

                                                    <tr v-for="(other_attached,index) in car.other_attacheds" v-bind:key="index">
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
                                                        <a href="#" @click.prevent.stop="AddNewRow()"> <i class="fa fa-plus-circle"></i></a>
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
                </div>
              </div>
                
            </div>
 
 
          </div>
        </div>
         </form>
  </div>
</template>

<script>
export default {
  props:{
    id:String,
    parent:String,
    company_id:String,
    doctype_id:String,
    user_id:String,
    wfmain_id:String,
    pre_title:String,
    doctype:String,
    
  },
  data () {
    return {
      contract_parent:{},
      edit:false,
      car:{
        proposer:this.user_id,
        issue_count:"",
        issue_date:"",
        company_id:"",
        department_id:"",
        content:"",
        document_type_id:this.doctype_id,
        wfmain_id:this.wfmain_id,
        group_id:"",
        standard_id:"",
        other_attacheds:[],
        other_attacheds_del:[],
      },
       other_attached_curr_index:"",
        other_attached:{
            id:"0",
            name:"",
            note:"",
            attachment_file:[],
            attachment_file_removed:[],

        },
       editorClass : "col-lg-12",   
               editorConfig: {
                    toolbarGroups : [
                        // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                        // { name: 'forms', groups: [ 'forms' ] },
                        // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                        // { name: 'links', groups: [ 'links' ] },
                        { name: 'insert', groups: [ 'insert' ] },
                        // { name: 'styles', groups: [ 'styles' ] },
                        { name: 'colors', groups: [ 'colors' ] },
                        // { name: 'tools', groups: [ 'tools' ] },
                        // { name: 'others', groups: [ 'others' ] },
                        // { name: 'about', groups: [ 'about' ] }              
                    ],
                    removeButtons: 'NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                },
      companies:[],
      departments:[],
      typecars:[],
      standards:[],
      users:[],
      list_qas:[],
      nameapproves: [],
      errors:{},
      loading: false,
      edit_term: false,
      token:"",
      page_url_cars : "api/car/systems",
      page_url_department : "/api/category/groups",
      page_url_company:"/api/category/companies",
      page_url_users:"api/user/all",
      page_url_typecars:"api/category/typecars",
      page_url_standards:"api/category/standards",
      page_url_name_approve: "api/category/positionapps",
      
    }
  },
   
  created () {
     this.token = "Bearer " + window.Laravel.access_token;
      this.getUser();
      this.fetCompany();
      this.fetDepartment();
      this.getContract();
      this.fetTypeCar();
      this.fetStandard();
      this.fetNameApprove();
     //console.log(this.doctype);
  },
   methods: {
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
      getContract(){
       // console.log(this.id);
        if( this.id != ''){
          // console.log("load id");
            this.loading = true;
             var page_url = this. page_url_cars+"/"+this.id+"/edit";
             console.log(this.id);
            fetch(page_url,{ headers: { Authorization: this.token } })
            .then(res=>res.json())
            .then(res=>{
              //console.log(res);
              if(res.data.success == '1'){
                  this.car = res.data;
                  this.car.other_attacheds_del = [];
                

               //khởi tạo biến mảng attachment_file_removed

               this.car.other_attacheds.forEach(element => {
                 element.attachment_file_removed = [];
               });
                  this.edit = true;
                  this.loading = false;

                  //nếu có parent
     
                    

                //  $('#amount').val(this.car.amount);
             
                 // $('#content').summernote('code',this.car.content);
                
                  //lấy danh sách phụ lục


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
      //Tab other attached file
          AddNewRow(){

            this.other_attached.name = "";
            this.other_attached.attachment_file = [];
            this.other_attached.attachment_file_removed = [];
            this.car.other_attacheds.push({...this.other_attached});
        },
        AddNewRow_Detail(){

             this.details.push({...this.detail});
        },
        AddNewRow_Other(){

             this.others.push({...this.other});
        },
         //Thêm file other_attached
        handleClickInputFile(index) {

            this.$refs.file.click();
            this.other_attached_curr_index = index;

        },
        //Xóa dòng  orther_attaches
         deleteInputFile(item,index){
          if(confirm(this.$t('form.confirm_delete') +'?')){
            this.car.other_attacheds_del.push({...item});
            this.car.other_attacheds.splice(index,1);
          }
        },
        //xoá file trong other_attacheds
        deleteFile(item,index,findex){
           if(confirm(this.$t('form.confirm_delete') +'?')){

              //  console.log("index="+index);


                this.car.other_attacheds[index].attachment_file_removed.push({...item});
                this.car.other_attacheds[index].attachment_file.splice(findex,1);
            }
        },
        addNote(){
          this.car.other_attacheds[this.other_attached_curr_index].note =  $('#ghichu').val();
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
                 this.car.other_attacheds[this.other_attached_curr_index].attachment_file.push({...docs});

              };
            }
            //reset file control
            event.target.value = '';

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
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },
       fetTypeCar() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_typecars;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.typecars = res.data;
                })
                .catch(err => console.log(err));
        },
       fetStandard() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_standards;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.standards = res.data;
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
        AddCar() {
            this.loading= true;
             var page_url = this. page_url_cars;// "/api/category/serviceCategorys";
            if(this.edit === false){
                fetch(page_url, {
                method: "POST",
                body: JSON.stringify(this.car),
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
                         window.location.href = "/car/systems";
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
                 fetch(page_url+"/"+this.car.id, {
                method: "PUT",
                body: JSON.stringify(this.car),
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
                         window.location.href = "/car/systems";
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
        showValidate(fieldName){
          if(fieldName in this.layout){
             return this.layout[fieldName]['require_validation'];
          }
          return false;
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
        NumbersOnly(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
              evt.preventDefault();;
            } else {
              return true;
            }
          },
         viewCar(id){
        window.location.href= "car/systems?type=view&id="+id;
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
        group_filter(){
          var list= [];
          let user = this.users.find(x=>x.id == this.user_id);
          if(user){
            list = user.groups;
          }
         
          return list;
        },
        department_filter(){
          let company_id = this.car.company_id;
         // this.contract.department_id = "";
          return this.departments.filter(function(item){
              if(item.company_id == company_id){
                return true;
              }
          });
        },
    },

}


</script>
<style scoped>
 .form-group {
    margin-bottom: 1px  !important;
}
</style>
 