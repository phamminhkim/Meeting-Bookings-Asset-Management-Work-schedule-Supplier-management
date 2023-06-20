<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> <i class="fad fa-file-chart-line"></i> Báo cáo</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-9">

                            </div>
                            <div class="col-sm-3">
                                <div class="float-right">
                                    <b-dropdown id="shadow_btn" right text="Xuất Excel" variant="primary">
                                        <b-dropdown-item>
                                            <download-excel  :name="fileName"
                                                :data="tableData" type="xls" :fields="fieldexcel" title="Báo cáo Tài sản/CCDC đang có người sở hữu">
                                                Xuất báo cáo Tài sản/CCDC đang có người sở hữu
                                            </download-excel>                           
                                        </b-dropdown-item>
                                        <b-dropdown-item>                            
                                            <download-excel :name="fileName_giaodich" :data="tableData_giaodich" :fields="fieldexcel_giaodich" title="Nhật ký xuất vật tư"
                                                type="xls">
                                                Xuất báo cáo Nhật ký xuất vật tư
                                            </download-excel>
                                            </b-dropdown-item>
                                            <b-dropdown-item>                            
                                            <download-excel :name="fileName_nhapxuatton" :data="tableData_nhapxuatton" :title="title_xnt" :fields="fieldexcel_nhapxuatton"
                                          
                                                type="xls">
                                                Xuất báo cáo Nhập Xuất Tồn
                                            </download-excel>
                                            </b-dropdown-item>
                                    </b-dropdown>

                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <!-- <div class="card-header">
                <div class="row">
                    <div class="col-md-12">



                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for="">
                                 <span class="text-secondary">Kho hàng: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.asset_warehouse_id">
                                    <option value="">All</option>
                                    <option v-for="slocc in asset_warehouses" v-bind:value="slocc.id">
                                        {{ slocc.name }}
                                    </option>
                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> <span
                                    class="text-secondary">Người sử dụng: </span></label>
                            <div class="col-md-3">
                                <treeselect placeholder="Người sử dụng" :disabled="edit" :disable-branch-nodes="true"
                                    :multiple="true" v-model="form_filter.user_id" :options="list_user">
                                </treeselect>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Loại: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.objectable_type">
                                    <option value="">All</option>
                                    <option value="App\Models\asset\Asset">Tài sản</option>
                                    <option value="App\Models\asset\AssetTool">Công cụ dụng cụ</option>
                                    
                                </select>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                          
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Tên giao dịch: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.trans_type">
                                    <option value="">All</option>
                                    <option value="1">Bàn giao</option>
                                    <option value="2">Thu hồi</option>
                                    <option value="3">Hủy bỏ</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            

                        </div>
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Từ ngày: </span></label>
                                 <div class="col-md-3">
                                
                                    <input  type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1"/>
                                </div>
                                    <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                               
                            <span class="text-secondary">Đến ngày: </span></label>
                            <div class="col-md-3">
                            <input type="date" v-model="form_filter.end_date" class="form-control form-control-sm mt-1" /> </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1"> 
                                 <span class="text-secondary">Tháng: </span></label>
                                 <div class="col-md-3">
                                
                                    <input  type="month" v-model="form_filter.month" class="form-control form-control-sm mt-1"/>
                                </div>
                       
                        <div class="form-group row">
                           
                                  
                        </div>
                    </div>
                        <div class="col-md-1.5">
                            <button class="btn btn-warning btn-xs mt-2" @click="btn_filter()"> <i
                                    class="fas fa-search fas-sm"></i> Tìm kiếm </button>
                        </div>
                        <div class="col-sm-3">

                        </div>




                    </div>
                </div>
            </div> -->
            <div class="card-body">
                <b-tabs active-nav-item-class="animation text-uppercase" content-class="mt-1" small>
                    <b-tab title="Đã có người sử dụng" title-link-class="animation1"  id="daco"
                    >
                        <template #title>
                            <div class="tess">
                                <strong>Đã có người sử dụng</strong>
                            </div>
                        </template>
                         <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for="">
                                 <span class="text-secondary">Kho hàng: </span></label>
                                 
                            <div class="col-md-3">
                                <treeselect placeholder="Kho hàng" :disable-branch-nodes="true"
                                             :multiple="multiple" v-model="form_filter.asset_warehouse_id"
                                            :options="tree_slocs"
                                            >
                                        </treeselect>
                              
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> <span
                                    class="text-secondary">Người sử dụng: </span></label>
                            <div class="col-md-3">
                                <treeselect placeholder="Người sử dụng" :disabled="edit" :disable-branch-nodes="true"
                                    :multiple="true" v-model="form_filter.user_id" :options="list_user">
                                </treeselect>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Loại: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter.objectable_type">
                                    <option value="">All</option>
                                    <option value="App\Models\asset\Asset">Tài sản</option>
                                    <option value="App\Models\asset\AssetTool">Công cụ dụng cụ</option>
                                    
                                </select>
                            </div>
                        </div>
                       
                       
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Từ ngày: </span></label>
                                 <div class="col-md-3">
                                
                                    <input  type="date" v-model="form_filter.start_date" class="form-control form-control-sm mt-1"/>
                                </div>
                                    <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                               
                            <span class="text-secondary">Đến ngày: </span></label>
                            <div class="col-md-3">
                            <input type="date" v-model="form_filter.end_date" class="form-control form-control-sm mt-1" /> </div>
                        
                            <div class="col-md-1.5">
                            <button class="btn btn-warning btn-xs mt-2" @click="btn_filter()"> <i
                                    class="fas fa-search fas-sm"></i> Tìm kiếm </button>
                                 
                            <button type="reset" class="btn btn-secondary btn-xs mt-2" 
                            @click="clearFilter()"> <i class="fa fa-reset"></i> Refresh </button>
                        </div>
                      
                    </div>
                       
                       



                        <div class="title" style="text-align:center;">
                                <span style="font-weight: 100; color:black; font-size:18px"> 
                                <i>Báo cáo tài sản đã có người sử dụng</i></span>
                            </div>
                  
                        <div class="header">
                            <b-table  hover :bordered="true"
                                small
                            :items="report" responsive :fields="fields_report" 

                            :current-page="current_page"
                            :per-page="per_page">
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(department.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable_type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(create_by)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(quantity)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #cell(asset_warehouse_id)="data">

                                    <span>{{ getwarehouseName(data.value) }}</span>
                                </template>
                                <template #cell(objectable_id)="data">
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">{{
                                            getAssetname(data.item.objectable_id)
                                    }}</span>
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">{{
                                            getToolname(data.item.objectable_id)
                                    }}</span>

                                </template>
                                <template #cell(objectable_type)="data">
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">Công cụ
                                        dụng
                                        cụ</span>
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">Tài sản</span>

                                </template>
                                <template #cell(user_id)="data">
                                    <span>{{ getUsername(data.item.user_id) }}</span>
                                </template>
                                <template #cell(create_by)="data">
                                    <span>{{ getUsername(data.item.create_by) }}</span>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                            </b-form-select>
                                            <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page"
                                                pills class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </b-tab>
                    <b-tab title="Giao dịch" title-link-class="animation1" id="giaodich">
                        <template #title>
                            <div class="tess">
                                <strong>Nhật ký xuất vật tư</strong>
                            </div>
                        </template>
                        <div class="form-group row">
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for="">
                                 <span class="text-secondary">Kho hàng: </span></label>
                            <div class="col-md-3">
                              
                                <treeselect placeholder="Kho hàng" :disable-branch-nodes="true"
                                             :multiple="multiple" v-model="form_filter.asset_warehouse_id"
                                            :options="tree_slocs"
                                            >
                                        </treeselect>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> <span
                                    class="text-secondary">Người sử dụng: </span></label>
                            <div class="col-md-3">
                                <treeselect placeholder="Người sử dụng" :disabled="edit" :disable-branch-nodes="true"
                                    :multiple="true" v-model="form_filter_xvt.user_id" :options="list_user">
                                </treeselect>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Loại: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter_xvt.objectable_type">
                                    <option value="">All</option>
                                    <option value="App\Models\asset\Asset">Tài sản</option>
                                    <option value="App\Models\asset\AssetTool">Công cụ dụng cụ</option>
                                    
                                </select>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                        
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Tên giao dịch: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter_xvt.trans_type">
                                    <option value="">All</option>
                                    <option value="1">Bàn giao</option>
                                    <option value="2">Thu hồi</option>
                                    <option value="3">Hủy bỏ</option>

                                </select>
                            </div>
                            <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Từ ngày: </span></label>
                                 <div class="col-md-3">
                                
                                    <input  type="date" v-model="form_filter_xvt.start_date" class="form-control form-control-sm mt-1"/>
                                </div>
                                    <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                               
                            <span class="text-secondary">Đến ngày: </span></label>
                            <div class="col-md-3">
                            <input type="date" v-model="form_filter_xvt.end_date" class="form-control form-control-sm mt-1" /> </div>
                        </div>
                        <div class="form-group row">
                        <div class="col-md-1.5">
                            <button class="btn btn-warning btn-xs mt-2" @click="btn_filter_xvt()"> <i
                                    class="fas fa-search fas-sm"></i> Tìm kiếm </button>
                                    <button type="reset" class="btn btn-secondary btn-xs mt-2" 
                            @click="clearFilter_xvt()"> <i class="fa fa-reset"></i> Refresh </button>
                        </div>
                    </div>
                       
                       


                        <div class="title" style="text-align:center;">
                                <span style="font-weight: 100; color:black; font-size:18px"> 
                                <i>Nhật ký xuất vật tư</i></span>
                            </div>

                   
                        <div class="header">
                            <b-table hover :bordered="true"
                                small :items="report_giaodich" responsive :fields="fields_report_giaodich"
                            :current-page="current_page2" :per-page="per_page2">
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_warehouse_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(department.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                
                                <template #head(created_at)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable_type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(user_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(confirm)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(asset_transaction_id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(quantity)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #cell(asset_warehouse_id)="data">

                                    <span>{{ getwarehouseName(data.value) }}</span>
                                </template>
                                <template #cell(objectable_id)="data">
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">{{
                                            getAssetname(data.item.objectable_id)
                                    }}</span>
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">{{
                                            getToolname(data.item.objectable_id)
                                    }}</span>

                                </template>
                                <template #cell(objectable_type)="data">
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">Công cụ
                                        dụng
                                        cụ</span>
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">Tài sản</span>

                                </template>
                                <template #cell(created_at)="data">
                                    <span>{{gettime(data.item.created_at)}}</span>

                                </template>
                                <template #cell(confirm)="data">

                                    <span v-if="getConfirm(data.item.asset_transaction_id) == 1" class="badge bg-success font-weight-bold">Đã
                                        xác nhận</span>
                                    <span v-if="getConfirm(data.item.asset_transaction_id) == 0" class="badge bg-danger font-weight-bold">Chưa
                                        xác nhận</span>
                                    <span v-if="getConfirm(data.item.asset_transaction_id) == null"
                                        class="badge bg-danger font-weight-bold">Đã hủy</span>
                                        <span v-if="getConfirm(data.item.asset_transaction_id) == 3" class="badge bg-warning font-weight-bold">Không cần xác nhận</span>
                                </template>
                                <template #cell(user_id)="data">
                                    <span>{{ getUsername(data.item.user_id) }}</span>
                                </template>
                                <template #cell(asset_transaction_id)="data">
                                    <span v-if="getTranstype(data.item.asset_transaction_id) == 1"
                                    class="text-success font-weight-bold">
                                        <i id="show_transaction" class="fas fa-hands"
                                            style="color:#1fc700;border-radius:30px;background:repeating-linear-gradient(110deg, gray, transparent 100px);"></i>
                                        Bàn
                                        giao</span>
                                    <span v-if="getTranstype(data.item.asset_transaction_id) == 2"
                                    class="text-warning font-weight-bold">
                                        <i id="show_recovery" class="fas fa-hand-rock"
                                            style="color:darkred;border-radius:30px;"></i>
                                        Thu hồi </span>

                                    <span v-if="getTranstype(data.item.asset_transaction_id) == 3" class="text-warning font-weight-bold">
                                    <i class="fas fa-times-circle"
                                            style="font-size:20px;color:linen;background:darkred;border-radius:30px;"></i>
                                        <span class="text-danger"><b>Hủy bỏ</b></span> </span>

                                </template>

                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="per_page2" :options="pageOptions_giaodich">
                                            </b-form-select>
                                            <b-pagination v-model="current_page2" :total-rows="rows_giaodich"
                                                :per-page="per_page2" pills class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Bảng kê nhập-xuất-tồn" title-link-class="animation1" active id="nhapxuatton" >
                        <template #title>
                            <div class="tess">
                                <strong>Bảng kê nhập-xuất-tồn</strong>
                            </div>
                        </template>
                        <div class="form-group row">
                        <label class="col-form-label-sm col-md-1.5 ml-1 mt-1"> 
                                 <span class="text-secondary">Tháng: </span></label>
                                 <div class="col-md-3">
                                
                                    <input  type="month" v-model="form_filter_xnt.month" class="form-control form-control-sm mt-1"/>
                                </div>
                                <label class="col-form-label-sm col-md-1.5 ml-1 mt-1" style="text-align:right" for=""> 
                                 <span class="text-secondary">Loại: </span></label>
                            <div class="col-md-3">
                                <select name=""
                                    class="form-control form-control-sm mt-1" id="asset_warehouse_id"
                                    v-model="form_filter_xnt.objectable_type">
                                    <option value="">All</option>
                                    <option value="App\Models\asset\Asset">Tài sản</option>
                                    <option value="App\Models\asset\AssetTool">Công cụ dụng cụ</option>
                                    
                                </select>
                            </div>
                            <div class="col-md-1.5">
                            <button class="btn btn-warning btn-xs mt-2" @click="btn_filter_xnt()"> <i
                                    class="fas fa-search fas-sm"></i> Tìm kiếm </button>
                                    <button type="reset" class="btn btn-secondary btn-xs mt-2" 
                            @click="clearFilter_xnt()"> <i class="fa fa-reset"></i> Refresh </button>
                        </div>
                            </div>
                            <div class="title" style="text-align:center;">
                                <span style="font-weight: 100; color:black; font-size:18px"> 
                                <i>{{ title_xnt}}</i></span>
                            </div>
                            
                        <div class="header">
                            <b-table hover :bordered="true"
                                small :items="report_xuatnhapton" responsive :fields="fields_xuatnhapton" 
                            :current-page="current_page3" :per-page="per_page3">
                                <template v-slot:cell(id)=data>
                                    {{ data.index + 1 }}
                                </template>
                                <template #head(id)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable.name)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                
                                <template #head(objectable.asset_code)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(start)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sum_input)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(sum_output)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(end)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #head(objectable_type)=data>
                                    <span class="text-success">{{ data.label }}</span>
                                </template>
                                <template #cell(sum_input)="data">
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\Asset'">{{data.item.sum_input}} (lần)</span>
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\AssetTool'">{{data.item.sum_input}}</span>
                                </template>
                                <template #cell(objectable_type)="data">
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\Asset'">Tài sản</span>
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\AssetTool'">Công cụ dụng cụ</span>
                                </template>
                                <template #cell(sum_output)="data">
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\Asset'">{{data.item.sum_output}} (lần)</span>
                                    <span v-if="data.item.objectable_type==='App\\Models\\asset\\AssetTool'">{{data.item.sum_output}}</span>

                                </template>
                                <!-- <template #cell(objectable_id)="data">
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\Asset'">{{
                                            getAssetname(data.item.objectable_id)
                                    }}</span>
                                    <span v-if="data.item.objectable_type == 'App\\Models\\asset\\AssetTool'">{{
                                            getToolname(data.item.objectable_id)
                                    }}</span>

                                </template> -->
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="per_page3" :options="pageOptions_xuatnhapton">
                                            </b-form-select>
                                            <b-pagination v-model="current_page3" :total-rows="rows_xuatnhapton"
                                                :per-page="per_page2" pills class="ml-1"></b-pagination>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <!-- <b-tab title="Công cụ dụng cụ trong kho" title-link-class="animation1" id="ccdc">
                        <template #title>
                            <div class="tess">
                                <strong>Công cụ dụng cụ trong kho</strong>
                            </div>
                        </template>
                        <div class="header">
                           

                        </div>
                    </b-tab> -->
                </b-tabs>
            </div>
        </div>
    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import moment from "moment";
Vue.use(moment);
export default {
    components: {
        Treeselect,
    },
    data() {
        return {
            change: false,
            multiple:true,
            edit: false,
            fields_xuatnhapton:[{
                key: 'id',
                    label: '#',
            },{
                key: 'objectable.name',
                    label: 'Tài sản/CCDC',
            },{
                key: 'objectable.asset_code',
                    label: 'Mã TS/CCDC',
            },{
                key: 'objectable_type',
                    label: 'Loại',
            },{
                key: 'start',
                    label: 'Tồn đầu kỳ',
            },
            {
                key: 'sum_input',
                    label: 'Xuất trong kỳ (Bàn giao)',
            },
            {
                key: 'sum_output',
                    label: 'Nhập trong kỳ (Thu hồi)',
            },
            {
                key: 'end',
                    label: 'Tồn cuối kỳ',
            },
            ],
            fields_report_giaodich: [
                {
                    key: 'id',
                    label: '#',

                }, {
                    key: 'asset_transaction_id',
                    label: 'Loại giao dịch',

                },
                {
                    key: 'created_at',
                    label: 'Ngày',

                }, {
                    key: 'objectable_id',
                    label: 'Tài sản/CCDC',

                },
                {
                    key: 'user_id',
                    label: 'Người sử dụng',

                }, {
                    key: 'department.name',
                    label: 'Phòng ban sử dụng',

                }, {
                    key: 'objectable_type',
                    label: 'Loại',

                },
                {
                    key: 'quantity',
                    label: 'Số lượng',

                }, {
                    key: 'asset_warehouse_id',
                    label: 'Kho hàng',

                }, {
                    key: 'confirm',
                    label: 'Xác nhận',

                },
            ],
            fields_report: [{
                key: 'id',
                label: '#',

            }, {
                key: 'user_id',
                label: 'Người sử dụng',

            },{
                key: 'department.name',
                label: 'Phòng ban sử dụng',

            }, {
                key: 'objectable_id',
                label: 'Tài sản/CCDC',
            }, {
                key: 'objectable_type',
                label: 'Loại',
            }, {
                key: 'asset_warehouse_id',
                label: 'Kho hàng',
            },
            {
                key: 'quantity',
                label: 'Số lượng',
            }, {
                key: 'create_by',
                label: 'Người tạo',
            },
            ],
            month:moment(Date()).format("MM"),
            fileName: "Danh_Sach_Tai_San_Da_Co_Nguoi_Su_Dung_" + moment(Date()).format("MM/DD/YYYY"),
            fileName_giaodich: "Nhat_Ky_Xuat_Vat_Tu_" + moment(Date()).format("MM/DD/YYYY"),
            form_filter_xnt:{
                month:'',
                objectable_type:'',
            },
            form_filter: {
                asset_warehouse_id: [],
                user_id: [],
                objectable_type:'',
               
                start_date:'',
                end_date:'',
               
            },
            url_tree_slocs: "api/asset/wall",
            tree_slocs: [],
            form_filter_xvt: {
                asset_warehouse_id: [],
                user_id: [],
                objectable_type:'',
                trans_type:'',
                start_date:'',
                end_date:'',
              
            },
            tableData: [],
            tableData_giaodich: [],
            tableData_nhapxuatton:[],
            fileName_nhapxuatton:"Bang_Ke_Nhap_Xuat_Ton_" + moment(Date()).format("MM/DD/YYYY"),
            fieldexcel_nhapxuatton:{
                "Số thứ tự": "So_thu_tu",
                "Tài sản/CCDC": "Ten",
                "Mã TS/CCDC": "Ma",
                "Loại":"Loai_xnt",
                "Tồn đầu kỳ":"dauky",
                "Xuất trong kỳ":"xuattrongky",
                "Nhập trong kỳ":"nhaptrongky",
                "Tồn cuối kỳ":"cuoiky",



            },
            fieldexcel: {
                "Số thứ tự": "So_thu_tu",
                "Tài sản/CCDC": "Ten",
                "Kho hàng": "Kho_Hang",
                "Người sử dụng": "Nguoi_Su_Dung",
                "Phòng ban sử dụng": "PB_Su_Dung",
                "Người tạo": "Nguoi_Tao",
                "Số lượng": "So_luong",
                "Loại": "Loai",
            },
            fieldexcel_giaodich: {
                "Số thứ tự": "So_thu_tu",
                "Loại giao dịch":"Loai_GD",
                "Ngày":"Ngay",
                "Tài sản/CCDC": "Ten",
                "Loại": "Loai",
                "Người sử dụng": "Nguoi_Su_Dung",
                "Phòng ban sử dụng": "PB_Su_Dung",
                "Kho hàng": "Kho_Hang",
               
                
                "Số lượng": "So_luong",
                "Xác nhận": "Xac_nhan",
              
            },
            page_url_usersnew: "api/user/allnew",
            page_url_slocs: "api/asset/warehouse",
            url_report_giaodich: "api/asset/report_giaodich",
            url_report_xuatnhapton: "api/asset/nhapxuatton",
            report_xuatnhapton:[],
            url_report: "/api/asset/report",
            report: [],
            report_giaodich: [],
            list_user: [],
            loading: false,
            asset_warehouses: [],
            pageOptions: [10, 50, 100, 500, { value: this.rows, text: "All" }],
            pageOptions_giaodich: [10, 50, 100, 500, { value: this.rows_giaodich, text: "All" }],
            pageOptions_xuatnhapton: [10, 50, 100, 500, { value: this.rows_xuatnhapton, text: "All" }],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
            },
            per_page: 10,
            per_page2: 10,
            per_page3: 10,

            // variant_name:"asset_AssetReport",
            current_page: 1,
            current_page2: 1,
            current_page3: 1,

            // variant_data: [],
            // save_filter: false,

            data_warehouses: [],
            data_users: [],
            data_assets: [],
            data_tools: [],
            data_transactions: [],
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });
        this.fetchReport();
        this.fetchWarehouse();
        this.getUser_tree();
        this.fetchReport_Giaodich();
        this.fetchReport_XuatNhapTon();
        this.fetWarehouse_Tree();

    },
    methods: {
       
        clearFilter() {
            for (let field in this.form_filter) {
                this.form_filter[field] = "";
            }
            
         
            this.fetchReport();
          

        },
        clearFilter_xvt() {
            for (let field in this.form_filter_xvt) {
                this.form_filter_xvt[field] = "";
            }
            
         
            this.fetchReport_Giaodich();
          

        },
        clearFilter_xnt() {
            for (let field in this.form_filter_xnt) {
                this.form_filter_xnt[field] = "";
            }
            
         
            this.fetchReport_XuatNhapTon();
          

        },
        fetWarehouse_Tree() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.url_tree_slocs + "?type=tree_combobox"; //"/api/category/companies";
            fetch(page_url, { headers: { Authorization: this.token } })
                .then((res) => res.json())
                .then((data) => {
                    //console.log("Xin chao");
                    this.tree_slocs = data.data;
                }).catch(err => {

                    console.log(err);
                });
        },
        showA(){
            console.log('a');
        },
        gettime(created_at){
            return moment(created_at).format('YYYY-MM-DD')
        },
        getUser_tree() {
            var page_url = this.page_url_usersnew + "?type=tree_combobox";
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.list_user = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        getUsername(user_id) {
            var obj = this.data_users.find(x => x.id == user_id);
            return obj != null ? obj.name : '';
        },
        getAssetname(objectable_id) {
            var obj = this.data_assets.find(x => x.id == objectable_id);
            return obj != null ? obj.name : '';
        },
        getToolname(objectable_id) {
            var obj = this.data_tools.find(x => x.id == objectable_id);
            return obj != null ? obj.name : '';
        },
        getwarehouseName(asset_warehouse_id) {
            var obj = this.asset_warehouses.find(x => x.id == asset_warehouse_id);
            return obj != null ? obj.name : '';

        },
        getConfirm(asset_transaction_id) {
            var obj = this.data_transactions.find(x => x.id == asset_transaction_id);
            return obj != null ? obj.confirm : '';

        },
        getTranstype(asset_transaction_id) {
            var obj = this.data_transactions.find(x => x.id == asset_transaction_id);
            return obj != null ? obj.trans_type : '';

        },
        btn_filter() {
         
              
                this.fetchReport();
              
        },
        btn_filter_xvt() {
         
         this.fetchReport_Giaodich();
       
 },
        btn_filter_xnt(){
            this.fetchReport_XuatNhapTon();
        },
        fetchReport() {
            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;
            this.report = Array();
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter.asset_warehouse_id,
                user_id: this.form_filter.user_id,
                objectable_type:this.form_filter.objectable_type,
                start_date:this.form_filter.start_date,
                end_date:this.form_filter.end_date,
            });
            var page_url = this.url_report + '?' + params.toString();
            //  console.log(page_url);

            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.report = data.data;

                    this.data_warehouses = data.data_warehouse;
                    this.data_users = data.data_user;
                    this.data_assets = data.data_asset;
                    this.data_tools = data.data_tool;
                    var list = [];
                    for (let i = 0; i < this.report.length; i++) {
                        var type = '';
                        var obj_warehouse = this.data_warehouses.find(x => x.id == this.report[i].asset_warehouse_id);
                        var obj_user_id = this.data_users.find(x => x.id == this.report[i].user_id);
                        var obj_create_by = this.data_users.find(x => x.id == this.report[i].create_by);
                        if (this.report[i].objectable_type == 'App\\Models\\asset\\AssetTool') {
                            type = 'Công cụ dụng cụ';
                            var obj_name = this.data_tools.find(x => x.id == this.report[i].objectable_id);
                        }
                        if (this.report[i].objectable_type == 'App\\Models\\asset\\Asset') {
                            type = 'Tài sản';
                            var obj_name = this.data_assets.find(x => x.id == this.report[i].objectable_id);
                        }
                        var name='';
                       
                       if(this.report[i].department_id==null){
                           name='';
                       }else{
                           name=this.report[i].department.name;
                       }
                        list.push({
                            So_thu_tu: i,
                            Ten: obj_name != null ? obj_name.name : '',
                            Kho_Hang: obj_warehouse != null ? obj_warehouse.name : '',
                            Loai: type,
                            So_luong: this.report[i].quantity,
                            Nguoi_Su_Dung: obj_user_id != null ? obj_user_id.name : '',
                            PB_Su_Dung:name,
                            Nguoi_Tao: obj_create_by != null ? obj_create_by.name : '',
                            


                        });

                    }
                    this.tableData = list;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchReport_Giaodich() {

            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;
            this.report_giaodich = Array();
            const params = new URLSearchParams({
                asset_warehouse_id: this.form_filter_xvt.asset_warehouse_id,
                user_id: this.form_filter_xvt.user_id,
                objectable_type:this.form_filter_xvt.objectable_type,
                trans_type:this.form_filter_xvt.trans_type,
                start_date:this.form_filter_xvt.start_date,
                end_date:this.form_filter_xvt.end_date,
            });
            var page_url = this.url_report_giaodich + '?' + params.toString();
            //  console.log(page_url);

            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.report_giaodich = data.data;
                    this.data_transactions = data.data_transaction;
                    // this.query_transactions=data.query_transaction;
                    // console.log(this.data_users);
                    // this.data_warehouses = data.data_warehouse;
                    // this.data_users = data.data_user;
                    // this.data_assets = data.data_asset;
                    // this.data_tools = data.data_tool;
                    var list_giaodich = [];
                    for (let i = 0; i < this.report_giaodich.length; i++) {
                        var type = '';
                        var trans_type_name='';
                        var confirm_name='';
                        var obj_warehouse = this.data_warehouses.find(x => x.id == this.report_giaodich[i].asset_warehouse_id);
                        var obj_user_id = this.data_users.find(x => x.id == this.report_giaodich[i].user_id);
                        if (this.report_giaodich[i].objectable_type == 'App\\Models\\asset\\AssetTool') {
                            type = 'Công cụ dụng cụ';
                            var obj_name = this.data_tools.find(x => x.id == this.report_giaodich[i].objectable_id);
                        }
                        if (this.report_giaodich[i].objectable_type == 'App\\Models\\asset\\Asset') {
                            type = 'Tài sản';
                            var obj_name = this.data_assets.find(x => x.id == this.report_giaodich[i].objectable_id);
                        }
                        var obj_transactions = this.data_transactions.find(x => x.id == this.report_giaodich[i].asset_transaction_id);
                        var obj_transname=obj_transactions != null ? obj_transactions.trans_type : '';
                        var obj_transconfirm =obj_transactions != null ? obj_transactions.confirm : ''
                        if (obj_transname==1) {
                           trans_type_name = 'Bàn giao';
                            
                        }
                        if (obj_transname==2) {
                            trans_type_name = 'Thu hồi';
                            
                        }
                        if (obj_transname==3) {
                             trans_type_name = 'Hủy bỏ';
                            
                        }
                        if (obj_transconfirm==1) {
                            confirm_name = 'Đã xác nhận';
                            
                        }
                        if (obj_transconfirm==0) {
                            confirm_name = 'Chưa xác nhận';
                            
                        }
                        if (obj_transconfirm==null) {
                            confirm_name = 'Đã hủy';
                            
                        }
                        var name='';
                       
                        if(this.report_giaodich[i].department_id==null){
                            name='';
                        }else{
                            name=this.report_giaodich[i].department.name;
                        }
                        let ngay = moment(this.report_giaodich[i].created_at).format('YYYY-MM-DD [] h:mm:ss');
                        list_giaodich.push({
                            So_thu_tu: i,
                            Loai_GD:trans_type_name,
                            Ngay:ngay,
                            Nguoi_Su_Dung: obj_user_id != null ? obj_user_id.name : '',
                            PB_Su_Dung:name,

                            Ten: obj_name != null ? obj_name.name : '',
                            Loai: type,
                            Kho_Hang: obj_warehouse != null ? obj_warehouse.name : '',
                            So_luong: this.report_giaodich[i].quantity,
                            Xac_nhan:confirm_name,
                           
            
                        });

                    }
                    this.tableData_giaodich = list_giaodich;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchReport_XuatNhapTon() {

            //$("#tbbody_id").html('');
            this.loading = true;
            let vm = this;
            this.report_xuatnhapton = Array();
            const params = new URLSearchParams({
                month:this.form_filter_xnt.month,
                objectable_type:this.form_filter_xnt.objectable_type,

            });
            // "Số thứ tự": "So_thu_tu",
            //     "Tài sản/CCDC": "Ten",
            //     "Loại":"Loai_xnt",
            //     "Tồn đầu kỳ":"dauky",
            //     "Xuất trong kỳ":"xuattrongky",
            //     "Nhập trong kỳ":"nhaptrongky",
            //     "Tồn cuối kỳ":"cuoiky",
            var page_url = this.url_report_xuatnhapton + '?' + params.toString();
            //  console.log(page_url);

            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.report_xuatnhapton = data.data;
                    var list_xuatnhapton = [];
                    for (let i = 0; i < this.report_xuatnhapton.length; i++) {
                        var type='';
                        // var name='';
                        if (this.report_xuatnhapton[i].objectable_type == 'App\\Models\\asset\\AssetTool') {
                            type = 'Công cụ dụng cụ';
                          

                        }
                        if (this.report_xuatnhapton[i].objectable_type == 'App\\Models\\asset\\Asset' && this.report_xuatnhapton[i].objectable.seri) {
                            type = 'Tài sản';
                         

                        }
                        list_xuatnhapton.push({
                            So_thu_tu: i,
                            Ten:this.report_xuatnhapton[i].objectable.name,
                            Ma:this.report_xuatnhapton[i].objectable.asset_code,
                            Loai_xnt:type,

                            dauky: this.report_xuatnhapton[i].start,
                            xuattrongky: this.report_xuatnhapton[i].sum_input,
                            nhaptrongky: this.report_xuatnhapton[i].sum_output,
                            cuoiky: this.report_xuatnhapton[i].end,
                           
            
                        });
                    }
                    this.tableData_nhapxuatton = list_xuatnhapton;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
            },
        fetchWarehouse() {

            var page_url = this.page_url_slocs;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,

                }
            }).then(res => res.json())
                .then(res => {
                    this.asset_warehouses = res.data;

                }).catch(err => {

                    console.log(err);
                });

        },
      
        // filter_asset_warehouse_id() {
        //     this.fetchReport();
        //     this.fetchWarehouse();
        //     this.fetchReport_Giaodich();
        // },
        // filter_user() {
        //     this.fetchReport();
        //     this.fetchReport_Giaodich();

        // },
    },
    computed: {
        title_xnt() {
            if(this.form_filter_xnt.month !=""){
                return "Bảng kê nhập xuất tồn tháng "+moment(this.form_filter_xnt.month).format('MM')+" năm "+moment(this.form_filter_xnt.month).format('yyyy');
            }else{
                return "Bảng kê nhập xuất tồn";
            }
           
            },
            rows() {
                return this.report.length;
            },
            rows_giaodich() {
                return this.report_giaodich.length;
            },
            rows_xuatnhapton(){
                return this.report_xuatnhapton.length;

            }
    },
}
</script>

<style lang="scss" scoped>
.tess {
    color: gray;
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
</style>