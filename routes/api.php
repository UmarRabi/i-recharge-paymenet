<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\CustomersController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('payments')->group(function(){
	Route::post('/checkout/{id}', [PaymentsController::class, 'initiate']);
	Route::post('/validate', [PaymentsController::class, 'validateCharge']);
});

Route::prefix('customers')->group(function () {
    Route::post('/create', [CustomersController::class, 'createCustomer']);
    Route::get('/get/{id}', [CustomersController::class, 'getCustomer']);
    Route::get('/all', [CustomersController::class, 'allCustomer']);
});
Route::prefix('cards')->group(function () {
    Route::post('/create/{id}', [CustomersController::class, 'createCard']);
    Route::put('/update/{id}', [CustomersController::class, 'updateCard']);
    Route::get('/delete/{id}', [CustomersController::class, 'deleteCard']);
    Route::get('/get-by-customer/{id}', [CustomersController::class, 'getCustomerCards']);
});

