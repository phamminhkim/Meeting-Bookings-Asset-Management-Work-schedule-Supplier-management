<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//users
Route::prefix('/user')->group(function () {
    Route::post('/login', 'api\LoginController@login');
    Route::middleware('auth:api')->get('/all', 'api\user\UserController@index');
    Route::middleware('auth:api')->get('/allnew', 'api\user\UserController@tree_user_new');

    Route::middleware('auth:api')->post('/create', 'api\user\UserController@create');
    Route::middleware('auth:api')->post('/resetPass', 'api\user\UserController@resetPass');
    Route::middleware('auth:api')->get('/userinfo/{id}', 'api\user\UserController@show');
    Route::middleware('auth:api')->get('/myinfo', 'api\user\UserController@ownerInfo');
    Route::middleware('auth:api')->post('/changepass', 'api\user\UserController@changepass');

    Route::resource('/variant', 'api\user\UserVariantController')->middleware('auth:api');
});

Route::prefix('tmdt')->middleware('auth:api')->group(function () {
    //Gọi từ APP
    Route::resource('salesorder', 'api\tmdt\SalesOrderController');
    Route::post('/createSalesOrder', 'api\tmdt\SalesOrderController@createSalesOrder'); //gọi từ SAP
    Route::post('/SetStatus', 'api\tmdt\SalesOrderController@SetStatus');
});

Route::prefix('category')->middleware('auth:api')->group(function () {
    //Gọi từ APP
    Route::resource('roles', 'api\category\RoleController');
    Route::resource('permissions', 'api\category\PermissionController');
    Route::resource('users', 'api\category\UserController');
    Route::resource('vendors', 'api\category\VendorController');
    Route::resource('departments', 'api\category\DepartmentController');
    Route::resource('workshops', 'api\category\WorkshopController');
    Route::resource('parties', 'api\category\PartyController');
    Route::resource('employees', 'api\category\EmployeeController');
    Route::post('employeesupload', 'api\category\EmployeeController@uploadData');
    Route::resource('resigndatas', 'api\category\ResignDataController');
    Route::resource('directtypes', 'api\category\DirectTypeController');
    Route::resource('employeetypes', 'api\category\EmployeeTypeController');
    Route::resource('employmenttypes', 'api\category\EmploymentTypeController');
    Route::resource('jobtitles', 'api\category\JobTitleController');
    Route::resource('resigntypes', 'api\category\ResignTypeController');
    Route::resource('hrconfigtype', 'api\category\HrconfigTypeController');
    Route::resource('hrconfigprice', 'api\category\HrconfigPriceController');
    Route::get('hrconfigtype_index_form', 'api\category\HrconfigTypeController@index_form');
    Route::get('hrconfigprice_index_form', 'api\category\HrconfigPriceController@index_form');
    
    Route::resource('positions', 'api\category\PositionController');
    Route::resource('positionapps', 'api\category\PositionApproveController');
    Route::get('/list_position_apps', 'api\category\PositionApproveController@get_position_approve');
    Route::resource('carerrors', 'api\category\CarErrorController');
    Route::resource('typecars', 'api\category\TypeCarController');
    Route::resource('standards', 'api\category\StandardController');
    Route::get('/car_error_qas', 'api\category\CarErrorController@get_car_error_qa');
    Route::get('/car_error_qcs', 'api\category\CarErrorController@get_car_error_qc');
    Route::resource('documenttypes', 'api\category\DocumentTypeController');
    Route::resource('documentgroups', 'api\category\DocumentGroupController');
    Route::resource('docbrowsertypes', 'api\category\DocbrowserTypeController');
    Route::resource('groups', 'api\category\GroupController');
    Route::resource('wfmains', 'api\category\WfmainController');
    Route::resource('wfsteps', 'api\category\WfstepController');
    Route::get('/lists', 'api\category\WfstepController@list');
    Route::get('/get_workflow', 'api\category\WfstepController@get_workflow');
    Route::resource('wfusertypes', 'api\category\WfusertypeController');
    Route::resource('wfapptypes', 'api\category\WfapptypeController');
    Route::resource('wfstepdetails', 'api\category\WfstepdetailController');
    Route::resource('doctypes', 'api\category\DocTypeController');
    Route::resource('approvestep', 'api\category\ApproveStepController');
    Route::resource('approvelimit', 'api\category\ApproveLimitController');
    Route::get('approvelimit_index_form', 'api\category\ApproveLimitController@index_form');
    Route::resource('approveroutings', 'api\category\ApproveRoutingController');
    Route::resource('steps', 'api\category\StepController');
    Route::resource('companies', 'api\category\CompanyController');
    Route::resource('currencies', 'api\category\CurrencyController');
    Route::resource('budgettypes', 'api\category\BudgetTypeController');
    Route::resource('modules', 'api\category\ModuleController');
    Route::resource('uniqueids', 'api\category\UniqueIdController');
    Route::resource('teams', 'api\category\TeamController');
    Route::resource('paymenttypes', 'api\category\PaymentTypeController');
    Route::resource('paymentvourchertypes', 'api\category\PaymentVourcherTypeController');
    Route::get('documenttype/submenu', 'api\category\DocumentTypeController@sub_menu');

    Route::resource('products', 'api\category\ProductController');
    Route::resource('banks', 'api\category\BankController');
    Route::resource('workflowtypes', 'api\category\WlworkflowTypeController');
    Route::resource('workflowjobtypes', 'api\category\WlworkflowJobTypeController');
    Route::resource('paycatsets', 'api\category\PaycatsetController');
    Route::resource('paycattypes', 'api\category\PaycattypeController');
    Route::resource('payrequest_types', 'api\category\PayrequestTypeController');
    Route::resource('serviceCategories', 'api\category\ServiceCategoryController');
    Route::post('/assignServiceToTeam', 'api\category\ServiceCategoryController@assignServiceToTeams');
    Route::post('/assignPayrequestTypeToTeam', 'api\category\PayrequestTypeController@assignPayrequestTypeToTeams');
    Route::get('/search_user', 'api\category\TeamController@search_user');
    Route::get('/search_paycatset', 'api\category\PaycatsetController@search_paycatset');
    Route::post('/assign_user_team', 'api\category\TeamController@assign_user');
    Route::post('/detach_user_team', 'api\category\TeamController@detach_user');
    Route::post('/assign_user_group', 'api\category\GroupController@assign_user');
    Route::post('/detach_user_group', 'api\category\GroupController@detach_user');
    Route::post('/assign_usercc_team', 'api\category\TeamController@assign_user_cc');
    Route::post('/detach_usercc_team', 'api\category\TeamController@detach_user_cc');
    Route::post('/replace_user_team', 'api\category\TeamController@replace_user');
    Route::post('/remove_user_team', 'api\category\TeamController@remove_user');

    Route::post('/batchadd_usercc_team', 'api\category\TeamController@batchadd_usercc');
    Route::post('/batchreplace_usercc_team', 'api\category\TeamController@batchreplace_usercc');
    Route::post('/batchremove_usercc_team', 'api\category\TeamController@batchremove_usercc');
    Route::post('/batchreview_user_team', 'api\category\TeamController@batchreview_user');

    Route::post('/batchadd_approveroutings', 'api\category\TeamController@batchadd_routing');
    
    Route::post('/create-team-for-group', 'api\category\TeamController@createTeamForGroup');
});
Route::middleware('auth:api')->group(function () {
    Route::post('/upload_avatar', 'api\user\UserController@upload_avatar');
    Route::post('/user_config', 'api\user\UserController@user_config');
    Route::resource('notifications', 'api\service_sys\NotificationController');
    Route::resource('reminders', 'api\reminder\ReminderController');
    Route::resource('shareds', 'api\shared\SharedController');
    Route::resource('documents', 'api\document\DocumentController');
    Route::resource('issues', 'api\issue\IssueController');
    Route::post('documents/update_cancel', 'api\document\DocumentController@update_cancel');
    Route::get('dashboard', 'api\dashboard\DashboardController@index');
    Route::resource('comments', 'api\comment\CommentController');
    Route::resource('commentvotes', 'api\comment\CommentVoteController');
    Route::get('test_performance', 'api\document\DocumentController@test_performance');

    Route::get('search', 'api\SearchController@search');
    Route::get('get_menu_statistics', 'api\MenuStatisticController@get');

});

Route::prefix('works')->middleware('auth:api')->group(function () {
    Route::resource('workflows', 'api\work\workflow\runtime\WorkflowController');
    Route::resource('workflowsdefine', 'api\work\workflow\define\WorkflowDefineController');
    Route::resource('phases', 'api\work\workflow\define\PhaseController');
    Route::resource('jobs', 'api\work\workflow\define\WlJobController');
    Route::resource('navigate-jobs', 'api\work\workflow\define\WlJobNavigateController');
    Route::resource('jobdependencies', 'api\work\workflow\define\WlJobDependencyController');
    Route::resource('dataobjects', 'api\work\workflow\define\DataObjectController');
    Route::resource('configs', 'api\work\workflow\define\WlConfigController');
   
    Route::post('preview-data-objects', 'api\work\workflow\define\DataObjectController@previewDataObjects');
    Route::post('get-create-template', 'api\work\workflow\runtime\WorkflowController@getCreateTemplate');
    Route::get('can-edit-document', 'api\work\workflow\runtime\WorkflowController@canEditDocument');
    Route::get('can-cancel-document', 'api\work\workflow\runtime\WorkflowController@canCancelDocument');

    Route::post('confirm-finish-phase', 'api\work\workflow\runtime\WorkflowController@updatePhaseFinish');
    Route::post('submit-single-report/{id}', 'api\work\workflow\runtime\WorkflowController@submitReport');
    Route::post('submit-job-reports', 'api\work\workflow\runtime\WorkflowController@submitReports');
    Route::post('cancel-workflow', 'api\work\workflow\runtime\WorkflowController@cancelWorkflow');
    Route::post('assign-job', 'api\work\workflow\runtime\WorkflowController@assignJob');
    Route::post('take-job', 'api\work\workflow\runtime\WorkflowController@takeJob');
    Route::post('abandon-job', 'api\work\workflow\runtime\WorkflowController@abandonJob');
    Route::post('update-phase-order', 'api\work\workflow\define\PhaseController@updatePhaseOrders');
    Route::post('update-workflow-approve-setting', 'api\work\workflow\runtime\WorkflowController@updateWorkflowApproveSetting');
    Route::post('manual-change-phase', 'api\work\workflow\runtime\WorkflowController@manualChangePhase');
    Route::get('get-workflow-structure', 'api\work\workflow\runtime\WorkflowController@getWorkflowStructure');
    Route::get('get-workflow-value', 'api\work\workflow\runtime\WorkflowController@getWorkflowValue');
    Route::post('submit-approvements', 'api\work\workflow\runtime\WorkflowController@submitApprovements');
    Route::post('create-new-job', 'api\work\workflow\runtime\WorkflowController@createNewJob');
    Route::post('navigating-jobs', 'api\work\workflow\runtime\WorkflowController@navigatingJobs');
    Route::post('send-approvement', 'api\work\workflow\runtime\WorkflowController@sendApprovement');
    Route::post('send-new-response', 'api\work\workflow\runtime\WorkflowController@sendNewResponse');
    
    Route::post('clone-workflow', 'api\work\workflow\define\WorkflowDefineController@copy');
    // Route::post('workflowdefine/store', 'api\work\workflow\define\WorkflowDefineController@store');
});

Route::prefix('payment')->middleware('auth:api')->group(function () {
    Route::resource('contracts', 'api\payment\ContractController');
    //download từ API
    Route::post('/downloadFile/{idfile}', 'api\payment\ContractController@downloadFile');
});
Route::prefix('productivity')->middleware('auth:api')->group(function () {
    Route::resource('addmark', 'api\productivity\HrAddMarkController');
    Route::resource('dayoff', 'api\productivity\HrDayoffController');
    Route::resource('salaryinfo', 'api\productivity\HrSalaryInfoController');
    Route::resource('documents', 'api\productivity\HrDocumentController');
    Route::resource('records', 'api\productivity\HrRecordController');
    
    Route::post('dayoffUpload', 'api\productivity\HrDayoffController@uploadData');
    Route::post('daysalaryUpload', 'api\productivity\HrSalaryInfoController@uploadData');
    Route::get('final', 'api\productivity\HrDocumentController@final_data');
    
});
Route::prefix('car')->middleware('auth:api')->group(function () {
    Route::resource('systems', 'api\car\SystemCarController');
    Route::resource('cause_measures', 'api\car\CauseMeasureController');
    Route::get('check_cause_measures/{id}', 'api\car\CauseMeasureController@check');
    Route::resource('monitor_implementations', 'api\car\MonitorImplementationController');
    Route::resource('result_evaluations', 'api\car\ResultEvaluationController');
    Route::resource('fast_processes', 'api\car\FastProcessController');
    Route::get('check_fast_processes/{id}', 'api\car\FastProcessController@check');
    Route::get('info_approve/{id}', 'api\car\SystemCarController@get_info_approve');
    Route::post('systems/update_cancel', 'api\car\SystemCarController@update_cancel');

    Route::get('statistics', 'api\car\SystemCarController@statistics');
    Route::get('index_statistics', 'api\car\SystemCarController@index_statistic');
    Route::get('statistic_excels', 'api\car\SystemCarController@statistic_excel');

    Route::get('index_statistics', 'api\car\SystemCarController@index_statistic');
    Route::get('statistic_status', 'api\car\SystemCarController@statistic_status');

    Route::get('statistic_status', 'api\car\SystemCarController@statistic_status');
    Route::get('log_approves', 'api\approvewf\ApproveController@log_approve');
    Route::get('get_user_create', 'api\car\SystemCarController@get_user_create');
    Route::post('update_date_limit', 'api\car\SystemCarController@update_date_limit');
    Route::put('update_date_limit/{id}', 'api\car\SystemCarController@update_date_limit');
    Route::post('/downloadFile/{idfile}', 'api\car\SystemCarController@downloadFile');
});

Route::prefix('sloc')->middleware('auth:api')->group(function () {
    Route::resource('gooddocuss', 'api\sloc\GoodDocusController');
    Route::resource('goods', 'api\sloc\GoodsController');
    Route::resource('goodtypes', 'api\sloc\GoodtypesController');
    Route::resource('goodunits', 'api\sloc\GoodunitsController');
    Route::resource('slocs', 'api\sloc\SlocsController');
    Route::resource('goodlist','api\sloc\GoodListController');
    Route::resource('demolist','api\sloc\DemoListController');

});
  

Route::prefix('calendar')->middleware('auth:api')->group(function () {
    Route::resource('calendartype', 'api\calendar\CalendarTypeController');
    Route::resource('calendarholiday', 'api\calendar\CalendarHolidayController');
    Route::resource('calendars', 'api\calendar\CalendarsController');


});
Route::prefix('asset')->group(function () {
    Route::middleware('auth:api')->get('/all', 'api\asset\AssetStockController@gettree');
    Route::middleware('auth:api')->get('/wall', 'api\asset\WarehouseController@gettreewarehouse');
    Route::middleware('auth:api')->get('/chuabangiao/{page}', 'api\asset\AssetController@chuabangiao');
    Route::middleware('auth:api')->get('/dabangiao/{page}', 'api\asset\AssetController@dabangiao');
    Route::middleware('auth:api')->get('/search', 'api\asset\AssetController@search');
    
    // Filter cho module tài sản
    Route::middleware('auth:api')->get('/serach_transaction_asset_tool', 'api\asset\AssetFilterController@serach_transaction_asset_tool');
    Route::middleware('auth:api')->get('/search_asset_type', 'api\asset\AssetFilterController@search_asset_type');


    Route::middleware('auth:api')->get('/cothebangiao/{page}', 'api\asset\AssetStockController@cothebangiao');
    Route::middleware('auth:api')->get('/changeTool/{page}', 'api\asset\AssetStockController@changeTool');
    Route::middleware('auth:api')->get('/thanhly/{page}', 'api\asset\AssetController@thanhly');
    Route::middleware('auth:api')->get('/dahet/{page}', 'api\asset\AssetStockController@dahet');
    Route::middleware('auth:api')->get('/lichsubangiao', 'api\asset\AssetStockController@lichsubangiao');
    Route::middleware('auth:api')->get('/taisan', 'api\asset\AssetTypeController@taisan');
    Route::middleware('auth:api')->get('/congcudungcu', 'api\asset\AssetTypeController@congcudungcu');
    Route::middleware('auth:api')->get('/taisandangnam', 'api\asset\AssetMyController@tai_san_dang_nam');
    Route::middleware('auth:api')->get('/notifycation', 'api\asset\AssetMyController@notifycation');
    Route::middleware('auth:api')->get('/pbsudung', 'api\asset\AssetMembersController@pbsudung');
    Route::middleware('auth:api')->get('/kiemke/{id}/{inven}/{ware_house}', 'api\asset\AssetInventarioController@list');
    Route::middleware('auth:api')->get('/lichsubangiaotaisan', 'api\asset\AssetController@lichsubangiaotaisan');
    Route::middleware('auth:api')->get('/detail', 'api\asset\AssetInventarioController@detail');
    Route::middleware('auth:api')->get('/waiting', 'api\asset\AssetUseController@index');
    Route::middleware('auth:api')->get('/xulitype', 'api\asset\AssetTypeController@xulitype');
    Route::middleware('auth:api')->get('/xulitype_2', 'api\asset\AssetTypeController@xulitype_2');
    Route::middleware('auth:api')->get('/report_giaodich', 'api\asset\AssetReportController@report_giaodich');
    Route::middleware('auth:api')->get('/gettreeDepartment', 'api\asset\AssetController@gettreeDepartment');
    Route::middleware('auth:api')->get('/export_excel/{attribute}', 'api\asset\ExportExcelAssetController@exportToExcel');
    // Route::get('export_excel/{attribute}','api\asset\ExportExcelAssetController@exportToExcel');

    Route::middleware('auth:api')->get('/detaill', 'api\asset\AssetTypeController@detaill');
    Route::middleware('auth:api')->get('/nhapxuatton', 'api\asset\AssetReportController@report_nhapxuatton');
    Route::middleware('auth:api')->get('/gettreeassettype', 'api\asset\AssetTypeController@gettreeassettype');
    Route::middleware('auth:api')->get('/gettreeassettypets', 'api\asset\AssetTypeController@gettreeassettypets');
    Route::middleware('auth:api')->get('/gettreevendos', 'api\asset\AssetTypeController@gettreevendos');
    
    // Route::middleware('auth:api')->get('/all', 'api\user\UserController@index');
});
Route::prefix('asset')->middleware('auth:api')->group(function () {
    // Route::middleware('auth:api')->get('/all', 'api\asset\AssetStockController@gettree');
    Route::get('getDataExcel', 'api\asset\AssetController@getDataExcel'); 
    Route::resource('assets', 'api\asset\AssetController');
    Route::get('assetPage/{page}', 'api\asset\AssetController@getPage'); 
    Route::resource('stock', 'api\asset\AssetStockController');
    Route::get('stockPage/{page}', 'api\asset\AssetStockController@getPage'); 
    Route::resource('warehouse', 'api\asset\WarehouseController');
    Route::resource('assettype', 'api\asset\AssetTypeController');
    Route::resource('group', 'api\asset\AssetGroupController');
    Route::resource('policy', 'api\asset\AssetPolicyController');
    Route::resource('status', 'api\asset\AssetStatusController');
    Route::resource('type', 'api\asset\AssetTypeController');

    Route::resource('transaction', 'api\asset\AssetTransactionController');
    Route::get('get-transaction/{page}', 'api\asset\AssetTransactionController@index');

    Route::resource('recovery', 'api\asset\AssetRecoveryController');
    Route::resource('assetuse', 'api\asset\AssetUseController');
    Route::resource('cancel', 'api\asset\AssetCancelController');
    Route::resource('my', 'api\asset\AssetMyController');
    Route::resource('mycount', 'api\asset\AssetMyCountController');
    Route::resource('cat', 'api\asset\AssetCatController');
    Route::resource('members', 'api\asset\AssetMembersController');
    Route::resource('inventario', 'api\asset\AssetInventarioController');
    Route::resource('inventario_detail', 'api\asset\AssetInventarioDetailController');
    Route::resource('inventario_history', 'api\asset\AssetInventarioHistoryController');
    Route::resource('type_details', 'api\asset\AssetTypeDetailController');
    Route::resource('report', 'api\asset\AssetReportController');
    Route::resource('asset_field', 'api\asset\AssetFieldController');
    Route::resource('asset_import', 'api\asset\AssetImportExcelController');
    Route::resource('asset_import_item', 'api\asset\AssetItemImportController');
    Route::resource('asset_change_item', 'api\asset\AssetChangeItemExcelController');
  
    Route::resource('asset_tool_import', 'api\asset\AssetToolImportController');

    Route::post('transaction_repair', 'api\asset\AssetTransactionRepairController@store');

    Route::get('asset-history-v2', 'api\asset\AssetController@historyAssetTransactionV_2');

});

Route::prefix('qa')->middleware('auth:api')->group(function () {
    Route::get('pageQuestion', 'api\qa\PageQuestionController@index');
    Route::get('pageQuestion/{id}/{question_id}', 'api\qa\PageQuestionController@show'); 
    Route::get('category', 'api\qa\CategoryController@index');
    Route::get('questionTag', 'api\qa\QuestionTagController@index'); 

});



Route::group(['middleware' => 'locale'], function () {
    Route::get('/language/{language}', 'api\LangController@setLanguage');
});

Route::prefix('admin')->middleware('auth:api')->group(function () {
    //Gọi từ APP
    Route::get('audit-users', 'api\admin\AuditController@auditUsers');
    Route::get('audit-roles', 'api\admin\AuditController@auditRoles');
});