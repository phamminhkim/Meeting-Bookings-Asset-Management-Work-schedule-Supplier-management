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
Route::prefix('service')->group(function () {
    //Gọi từ APP
  //  Route::resource('tickets', 'api\service\TicketController');
  // Route::get('/showall', 'api\service\TicketController@showall');
    Route::get('/tickets/{id}', 'api\service\TicketController@show');
    // Route::get('/listForUserAssign', 'api\service\TicketController@listForUserAssign');
    Route::get('/listForUserCreate', 'api\service\TicketController@listForUserCreate');
    Route::get('/listComment', 'api\service\TicketController@listComment');
    Route::get('/listreviewsRating', 'api\service\TicketController@listreviewsRating');



    Route::post('/post', 'api\service\TicketController@listForUserCreate');
    
 
    
});

Route::prefix('service')->middleware('auth:api')->group(function () {
  Route::post('/comment', 'api\service\TicketController@comment');
 Route::get('/showall', 'api\service\TicketController@showall');
 Route::post('/reportResult', 'api\service\TicketController@reportResult');
 Route::get('ticketbyId', 'api\service\TicketController@getbyTicketId');
	//Route::post('/assignTicket', 'api\service\ServiceController@assignTicket');
  Route::get('/listForUserAssign', 'api\service\TicketController@listForUserAssign');  
 
 
});

Route::prefix('service')->middleware('auth:api')->group(function () {
Route::resource('ticket', 'api\service\ServiceController');
Route::get('/userTeam/{id}', 'api\service\ServiceController@userTeam');
Route::post('/assignTicket', 'api\service\ServiceController@assignTicket');
Route::get('/serviceCategory', 'api\service\ServiceController@serviceCategory');
Route::get('/selectserviceCategory/{id}', 'api\service\ServiceController@selectserviceCategory');
Route::post('/reviewsRating', 'api\service\TicketController@reviewsRating');


});
