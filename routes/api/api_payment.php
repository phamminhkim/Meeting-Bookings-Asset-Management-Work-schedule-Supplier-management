<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('payment')->middleware('auth:api')->group(function () {
    Route::resource('contracts', 'api\payment\ContractController');
    Route::resource('contract_liquidations', 'api\payment\ContractLiquidationController');
    Route::resource('requests', 'api\payment\PayRequestController');
    Route::get('advance_money', 'api\payment\PayRequestController@advance_money');
    Route::post('update_paid', 'api\payment\PayRequestController@update_paid');
    Route::post('update_cancel', 'api\payment\PayRequestController@update_cancel');

    Route::get('settlement_doc/{id}', 'api\payment\PayRequestController@get_settlement_doc');



    Route::post('/downloadFile/{idfile}','api\payment\ContractController@downloadFile');
    Route::get('/search_contract','api\payment\ContractController@search_contract');
    Route::post('/payment_attached_check','api\payment\PayRequestController@payment_attached_check');
    Route::post('/miss_invoice_check','api\payment\PayRequestController@miss_invoice_check');
    Route::post('/update_hard_doc','api\payment\PayRequestController@update_hard_doc');
    Route::post('/printed_check','api\payment\PayRequestController@printed_check');
    Route::post('/contract_finalization','api\payment\ContractController@contract_finalization');
    Route::post('/contract_term_finalization','api\payment\ContractController@contract_term_finalization');

    Route::get('/run_plan','api\payment\PaymentPlanController@run_plan');
    Route::get('statistics', 'api\payment\PayRequestController@statistics');

});
Route::prefix('approve')->middleware('auth:api')->group(function () {

        Route::get('/', 'api\approve\ApproveController@index');
        Route::post('agree', 'api\approve\ApproveController@approve_payment_agree');
        Route::post('multipleagree', 'api\approve\ApproveController@approve_payment_multipleagree');
        Route::post('refuse', 'api\approve\ApproveController@approve_payment_refuse');
        Route::post('send','api\approve\ApproveController@approve_payment_send');
        Route::get('info','api\approve\ApproveController@get_approve_info');
        Route::post('changeuser','api\approve\ApproveController@approve_change_user');

    // Route::prefix('payment')->group(function () {
    //     Route::get('/', 'api\approve\payment\ApproveController@index');
    //     Route::post('agree', 'api\approve\payment\ApproveController@approve_payment_agree');
    //     Route::post('refuse', 'api\approve\payment\ApproveController@approve_payment_refuse');
    //     Route::post('send','api\approve\payment\ApproveController@approve_payment_send');
    // });



});
Route::prefix('approvewf')->middleware('auth:api')->group(function () {

    Route::get('/', 'api\approvewf\ApproveController@index');
    Route::post('agree', 'api\approvewf\ApproveController@approve_car_agree');
    Route::post('refuse', 'api\approvewf\ApproveController@approve_car_refuse');
    Route::post('send','api\approvewf\ApproveController@approve_car_send');
    Route::get('info','api\approvewf\ApproveController@get_approve_info');
   // Route::post('store_app', 'api\approvewf\ApproveController@store_app_car');


});
Route::prefix('price')->middleware('auth:api')->group(function () {

    Route::resource('requests', 'api\managerprice\PriceAppReqContoller');
    Route::post('update_cancel', 'api\managerprice\PriceAppReqContoller@update_cancel');
});
