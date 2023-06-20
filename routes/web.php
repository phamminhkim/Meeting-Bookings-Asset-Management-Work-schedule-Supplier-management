<?php

use App\Http\Controllers\frontend\document\DocumentFrontend;
use App\Http\Controllers\frontend\managerprice\PriceAppReqFontend;
use App\Http\Controllers\frontend\payment\PayRequestController;
use App\Jobs\SendEmail;
use App\Mail\EmailNoti;
use App\Mail\EmailPayequest;
use App\Mail\WelcomeMail;
use App\Models\document\Document;
use App\Models\managerprice\PriceReq;
use App\Models\payment\Payrequest;
use App\Models\shared\PrintedDoc;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     // return view('welcome');
//     return redirect()->route('home');
// });


Auth::routes();

Route::get('/printdoc/{id}', function (Request $request) {

    //Lưu html chứng từ vào bảng - > câp nhật


    $list = Payrequest::where('status', '>=', '2')->where('id', $request->id)->get();

    //dd($id);
    foreach ($list as   $object) {
        $con = new PayRequestController;
        try {
            $html =  $con->print(new Request, $object->id);
        } catch (\Throwable $th) {
            //throw $th;
        }


        $printDoc = new PrintedDoc();
        $printDoc->content = $html;

        foreach ($object->printedDocs as  $value) {
            $value->delete();
            //dd("đã xóa");
        }
        $object->printedDocs()->save($printDoc);
    }

    echo "DONE: Payrequest:" . $request->id;

    // $list = Document::where('status','2')->get();
    // foreach ($list as   $object) {
    //     $con = new DocumentFrontend;
    //     try {
    //         $html =  $con->print(new Request ,$object->id);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }


    //     $printDoc = new PrintedDoc();
    //     $printDoc->content = $html;
    //     $object->printedDocs()->save($printDoc);
    // }

    // echo "DONE: Document"  ;

    // $list = PriceReq::where('status','2')->get();
    // foreach ($list as   $object) {
    //     $con = new PriceAppReqFontend;
    //     try {
    //         $html =  $con->print(new Request ,$object->id);
    //     } catch (\Throwable $th) {
    //         // throw $th;
    //     }


    //     $printDoc = new PrintedDoc();
    //     $printDoc->content = $html;
    //     $object->printedDocs()->save($printDoc);
    // }

    // echo "DONE: PriceReq"  ;

});
Route::get('/email', function (Request $request) {


    //     $data = new NotiBaseModel;
    //     $data->title = __('form.reminder') ;
    //     $data->icon = "far fa-bell";
    //     $data->content = "noi dung";
    //     $data->url = "url";


    //   //Gửi cho người tạo nhắc nhở
    //     $user =  User::find(Auth()->user()->id);

    //    $email = new EmailPayequest($data,$user);
    //    $to = 'hung.nn@thienlongvn.com';
    //    //dd($request->to);
    //    if($request->to != ''){
    //     $to = $request->to;
    //    }
    //   // dd($to);
    //    $sendEmailJob = new SendEmail($email ,$to );
    //    dispatch($sendEmailJob);
    //     return $email;

});
//Group Thương Mại Điện Tử



Route::get('/uplmavanchuyen', 'tmdt\TmdtController@upload_mavanchuyen');
Route::get('/updonhang', 'tmdt\TmdtController@upload_donhang');
Route::post('importdonhang', 'tmdt\TmdtController@import_donhang')->name('importdonhang');
Route::get('/upuser', 'tmdt\TmdtController@upload_user');
Route::post('importuser', 'tmdt\TmdtController@import_user')->name('importuser');
Route::post('importmavanchuyen', 'tmdt\TmdtController@import_mavanchuyen')->name('importmavanchuyen');
Route::get('phieugiaohang', 'tmdt\TmdtController@get_phieugiaohang')->name('get_phieugiaohang');
Route::post('in_phieugiao', 'tmdt\TmdtController@in_phieugiao')->name('in_phieugiao');

Route::post('changepass', 'UserProfile\ProfileController@changepass');

Route::post('update_nestedtree', 'admin\MenuController@update_nestedtree');

//Ticket
Route::group(['prefix' => 'service'], function () {
    Route::get('index', 'services\ServiceController@index')->name('service.index');
    Route::get('create', 'services\ServiceController@create')->name('service.create');
    Route::get('edit/{id}', 'services\ServiceController@edit')->name('service.edit');
    Route::get('detail/{id}', 'services\ServiceController@show')->name('service.detail');
    Route::get('report/{id}', 'services\ServiceController@report')->name('service.report');
    Route::get('assign', 'services\ServiceController@assign');
});

//Group khác

Route::namespace('Admin')->prefix('admin')->name('admin')->middleware('can:manage-systems')->group(function () {

    //Group Admin cho Thương Mại Điện Tử

    Route::resource('users', 'UserController');
    Route::resource('menus', 'MenuController');
    Route::resource('home', 'HomeController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('companies', 'CompanyController');
    Route::resource('configsyss', 'ConfigSysController');
    Route::resource('maillogs', 'MailLogController');
    Route::post('update_tree', 'MenuController@update_tree')->name('update_tree');
    Route::get('get_by_company', 'DepartmentController@get_by_company');
    Route::get('get_quyen', 'RoleController@get_quyen');
    Route::post('search_quyen', 'RoleController@search_quyen');
    Route::get('search_quyen', 'RoleController@search_quyen');
    Route::get('search_configsys', 'ConfigSysController@search_all');

    // Route::get('get_sloc', 'RoleController@get_sloc');
    // Route::post('search_sloc', 'RoleController@search_sloc');
    // Route::get('search_sloc', 'RoleController@search_sloc');

    //search user
    Route::get('search_user', 'UserController@search_user');
    //search user
    Route::get('search_role', 'RoleController@search_role');
    //Group khác
    Route::get('search_company', 'CompanyController@search_all');
    //Mail log
    Route::get('search_maillog', 'MailLogController@search_all');
    //Resend email error
    Route::get('resend_maillog/{id}', 'MailLogController@resend')->name('resend_maillog');
    //search permission
    Route::get('searchPermission', 'PermissionController@search');

    Route::get('disable_user', 'UserController@disable_user');


    Route::get('view_user_groups/{username}', 'UserController@view_groups');
   
    Route::get('audit-users', 'Audit\UserAuditController@auditUsers');
    Route::get('audit-roles', 'Audit\UserAuditController@auditRoles');

    Route::get('routes', function () {
        $routeCollection = Route::getRoutes();

        echo "<table style='width:100%'>";
        echo "<tr>";
        echo "<td width='10%'><h4>HTTP Method</h4></td>";
        echo "<td width='10%'><h4>Route</h4></td>";
        echo "<td width='10%'><h4>Name</h4></td>";
        echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
            echo "<td>" . $value->methods()[0] . "</td>";
            echo "<td>" . $value->uri() . "</td>";
            echo "<td>" . $value->getName() . "</td>";
            echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    });
});
// Route::get('token', function () {
//     echo csrf_token();
// });

//test
$router->post('product', 'ProductController@createProduct');   //for creating product test

$router->get('product/{id}', 'ProductController@updateProduct'); //for updating product test

$router->post('product/{id}', 'ProductController@deleteProduct');  // for deleting product test

$router->get('product', 'ProductController@index'); // for retrieving product test

$router->get('testNoti', 'ProductController@testNoti'); //test noti


Route::group(['middleware' => 'locale'], function () {

    Route::get('category/role',  'frontend\CategoryController@role');
    Route::get('category/permission',  'frontend\CategoryController@permission');
    Route::get('category/user',  'frontend\CategoryController@user');
    Route::get('category/department',  'frontend\CategoryController@department');
    Route::get('category/company',  'frontend\CategoryController@company');
    Route::get('category/workshop',  'frontend\CategoryController@workshop');
    Route::get('category/party',  'frontend\CategoryController@party');
    Route::get('category/employee',  'frontend\CategoryController@employee');
    Route::get('category/resigndata',  'frontend\CategoryController@resigndata');
    Route::get('category/directtype',  'frontend\CategoryController@directtype');
    Route::get('category/employeetype',  'frontend\CategoryController@employeetype');
    Route::get('category/employmenttype',  'frontend\CategoryController@employmenttype');
    Route::get('category/jobtitle',  'frontend\CategoryController@jobtitle');
    Route::get('category/resigntype',  'frontend\CategoryController@resigntype');

    Route::get('category/vendor',  'frontend\CategoryController@vendor');
    Route::get('help',  'frontend\help\HelpController@index');

    Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index');
    Route::get('search', 'HomeController@search');


    
    Route::get('category/payrequesttype',  'frontend\CategoryController@payrequestType');
    Route::get('new', 'ProductController@testLaytout');

    Route::get('change-language/{language}', 'HomeController@changeLanguage')
        ->name('user.change-language');

    Route::get('category/team',  'frontend\CategoryController@team');
    Route::get('category/servicecategory',  'frontend\CategoryController@serviceCategory');
    Route::get('category/approvestep',  'frontend\CategoryController@approveStep');
    Route::get('category/documenttype',  'frontend\CategoryController@docmentType');
    Route::get('category/documentgroup',  'frontend\CategoryController@documentGroup');
    Route::get('category/docbrowsertype',  'frontend\CategoryController@docbrowserType');
    Route::get('category/group',  'frontend\CategoryController@group');
    Route::get('category/workflow',  'frontend\CategoryController@workflow');
    Route::get('category/workflowstep',  'frontend\CategoryController@workflowstep');
    Route::get('category/wftusertype',  'frontend\CategoryController@wftusertype');
    Route::get('category/wfapptype',  'frontend\CategoryController@wfapptype');
    Route::get('category/wfstepdetail',  'frontend\CategoryController@wfstepdetail');
    Route::get('category/position',  'frontend\CategoryController@position');
    Route::get('category/positionapp',  'frontend\CategoryController@positionapp');
    Route::get('category/carerror',  'frontend\CategoryController@carerror');
    Route::get('category/typecar',  'frontend\CategoryController@typecar');
    Route::get('category/standard',  'frontend\CategoryController@standard');
    Route::get('category/approvelimit',  'frontend\CategoryController@approveLimit');
    Route::get('category/approverouting',  'frontend\CategoryController@approveRouting');
    Route::get('category/product',  'frontend\CategoryController@product');
    Route::get('category/hrconfigtype',  'frontend\CategoryController@hrconfigtype');
    Route::get('category/hrconfigprice',  'frontend\CategoryController@hrconfigprice');
    Route::get('category/workflowtype',  'frontend\CategoryController@workflowtype');
    Route::get('category/workflowjobtype',  'frontend\CategoryController@workflowjobtype');
    Route::get('category/workflow-variable',  'frontend\CategoryController@workflowVariable');
    Route::get('category/workflow-condition',  'frontend\CategoryController@workflowCondition');
    Route::get('category/module',  'frontend\CategoryController@module');
    Route::get('category/uniqueid',  'frontend\CategoryController@uniqueid');

    Route::resource('profile', 'UserProfile\ProfileController');

    Route::resource('payment/contracts', 'frontend\payment\ContractController');
    Route::resource('payment/requests', 'frontend\payment\PayRequestController');
    Route::resource('price/requests', 'frontend\managerprice\PriceAppReqContoller');
    Route::get('price/savedoc', 'frontend\managerprice\PriceAppReqContoller@update_printed_docs');
    Route::resource('payment/plans', 'frontend\payment\PaymentPlanController');
    Route::resource('approve/request', 'frontend\approve\payment\ApproveController');
    Route::resource('approve/document', 'frontend\approve\document\ApproveController');
    Route::resource('approve/price', 'frontend\approve\price\ApproveController');
    Route::resource('approve/productivity', 'frontend\approve\productivity\ApproveController');
    Route::resource('notifications', 'frontend\notification\NotificationController');

    Route::get('payment/print/{id}', 'frontend\payment\PayRequestController@print');
    Route::get('price/print/{id}', 'frontend\managerprice\PriceAppReqContoller@print');

    Route::resource('documents', 'frontend\document\DocumentController');
    Route::resource('issues', 'frontend\issue\IssueController');
    Route::get('document/print/{id}', 'frontend\document\DocumentController@print');
    Route::get('issue/print/{id}', 'frontend\issue\IssueController@print');
    Route::get('payment/statistics', 'frontend\payment\PayRequestController@statistics');
    //download từ web
    Route::get('downloadFile/{idfile}', 'api\payment\ContractController@downloadFile');

    //export excel
    Route::get('export', 'api\category\ApproveRoutingController@export')->name('export');

    Route::resource('car/systems',  'frontend\car\SystemCarController');
    Route::get('car/systems/print/{id}', 'frontend\car\SystemCarController@print');
    Route::get('car/statistical', 'frontend\car\SystemCarController@statistical');
    Route::get('car/carlog', 'frontend\car\SystemCarController@carlog');
    Route::resource('car/test',  'frontend\test\TestController');
    Route::resource('approve/car/systems', 'frontend\approve\car\ApproveController');
    // Route::get('events','EventController@index');
    Route::resource('workdefines', 'frontend\work\workflow\define\WorkDefineController');
    //Route::resource('works/list/{current_workflow_code}', 'frontend\work\workflow\runtime\WorkRuntimeController');
    Route::get('works/list/{current_workflow_code}', 'frontend\work\workflow\runtime\WorkRuntimeController@index');
    // Route::get('works/add/{wlid}', 'frontend\work\workflow\runtime\WorkRuntimeController@index');
    // Route::get('works/edit/{id}', 'frontend\work\workflow\runtime\WorkRuntimeController@index');
    // Route::get('works/view/{id}', 'frontend\work\workflow\runtime\WorkRuntimeController@index');

    Route::group(['prefix' => 'sloc'], function () {
        Route::resource('gooddocus','frontend\sloc\GoodSlocController'); 
        Route::get('goodtypes',  'frontend\CategoryController@goodtypes');
        Route::get('goodunits',  'frontend\CategoryController@goodunits');
        Route::get('goods',  'frontend\CategoryController@goods');
        Route::get('goodslist',  'frontend\CategoryController@goodslist');
    });
    Route::group(['prefix' => 'calendar'], function () {
        Route::get('type',  'frontend\CategoryController@calendartype');
        Route::get('holiday',  'frontend\CategoryController@calendarholiday');
        Route::resource('calendars','frontend\calendar\CalendarsController'); 

    });
    Route::group(['prefix' => 'asset'], function () {
        // Route::get('asset',  'frontend\');
        Route::get('assetss',  'frontend\CategoryController@assets');
        Route::get('assettype',  'frontend\CategoryController@assettype');
        Route::get('stock',  'frontend\CategoryController@assetstock');
        Route::get('change',  'frontend\CategoryController@assetchange');
        Route::get('setting',  'frontend\CategoryController@assetsetting');
        Route::get('group',  'frontend\CategoryController@assetgroup');
        Route::get('inventario',  'frontend\CategoryController@assetinventario');
        Route::get('members',  'frontend\CategoryController@assetmembers');
        Route::get('report',  'frontend\CategoryController@assetreport');
        Route::get('status',  'frontend\CategoryController@assetstatus');
        Route::get('my',  'frontend\CategoryController@assetmy');
        Route::get('print',  'frontend\CategoryController@assetrecord');
    });

    Route::group(['prefix' => 'qa'], function () {
        Route::get('pageQuestion',  'frontend\CategoryController@pageQuestion');
    });
    Route::get('demo',  'frontend\CategoryController@demo');

});
Route::get('/download-template', function() {
    $filePath = public_path('templates/item_asset.xlsx');
    return response()->download($filePath, 'Item_asset.xlsx');
});

Route::group(['prefix' => 'productivity'], function () {
    Route::get('recordlist', 'frontend\productivity\ProductivityController@getRecordList');
    Route::get('document', 'frontend\productivity\ProductivityController@getDocument');
    Route::get('final', 'frontend\productivity\ProductivityController@getFinal');
    // Route::get('data', 'services\ServiceController@index')->name('service.index');
    // Route::get('process', 'services\ServiceController@create')->name('service.create');
    // Route::get('approve', 'services\ServiceController@edit')->name('service.edit');
    // Route::get('export', 'services\ServiceController@show')->name('service.detail');

    Route::get('addmark', 'frontend\productivity\HrAddMarkController@index');
    Route::get('dayoff', 'frontend\productivity\HrDayoffController@index');
    Route::get('salaryinfo', 'frontend\productivity\HrSalaryInfoController@index');
});

Route::resource('ticket', 'TicketController');
$router->get('test', 'TicketController@test');
$router->get('errorpage', 'frontend\errors\HandlerErrorsController@index');
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');