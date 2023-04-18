<?php

use Illuminate\Http\Request;
use Modules\Invoice\Http\Controllers\InvoiceController;

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
Route::group(['namespace' => 'Modules\Invoice\Http\Controllers','middleware' => []], function () {
   Route::get('create-invoices',[InvoiceController::class,'createOrderInvoicesAndUploadOnS3Bucket']);

   //create test purpose only
   //Route::get('run-and-check-schedules',[CommunicationsController::class,'runAndCheckSchedules']);
   //Route::get('run-and-check-update-communication-details',[CommunicationsController::class,'updateAlltypesUserCommunicationsDetailsFromMasteresTable']);
});
