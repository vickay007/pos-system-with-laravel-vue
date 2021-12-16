<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\CustomerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
	'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('signup', [App\Http\Controllers\AuthController::class, 'signup']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('me', [App\Http\Controllers\AuthController::class, 'me']);
    // Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);

});

Route::ApiResource('/employee', EmployeeController::class);
Route::ApiResource('/supplier', SupplierController::class);
Route::ApiResource('/category', CategoryController::class);
Route::ApiResource('/product', ProductController::class);
Route::ApiResource('/expense', ExpenseController::class);
Route::ApiResource('/customer', CustomerController::class);

Route::post('/salary/paid/{id}', [App\Http\Controllers\Api\SalaryController::class, 'paid']);

Route::get('/salary', [App\Http\Controllers\Api\SalaryController::class, 'allSalary']);

Route::get('/salary/view/{id}', [App\Http\Controllers\Api\SalaryController::class, 'viewSalary']);

Route::get('/salary/edit/{id}', [App\Http\Controllers\Api\SalaryController::class, 'editSalary']);

Route::post('/salary/update/{id}', [App\Http\Controllers\Api\SalaryController::class, 'updateSalary']);

Route::post('/stock/update/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateStock']);

Route::get('/getting/product/{id}', [App\Http\Controllers\Api\PosController::class, 'getProduct']);

//Cart Route
Route::get('/addToCart/{id}', [App\Http\Controllers\Api\CartController::class, 'addToCart']);

Route::get('/cart/product', [App\Http\Controllers\Api\CartController::class, 'cartProduct']);

Route::get('/remove/cart/{id}', [App\Http\Controllers\Api\CartController::class, 'removeCart']);

Route::get('/increment/{id}', [App\Http\Controllers\Api\CartController::class, 'increment']);

Route::get('/decrement/{id}', [App\Http\Controllers\Api\CartController::class, 'decrement']);

//Vat route
Route::get('/vats', [App\Http\Controllers\Api\CartController::class, 'vat']);

Route::post('/orderdone', [App\Http\Controllers\Api\PosController::class, 'orderDone']);

//Order Route
Route::get('/orders', [App\Http\Controllers\Api\OrderController::class,'TodayOrder']);

Route::get('/order/details/{id}', [App\Http\Controllers\Api\OrderController::class,'orderDetails']);

Route::get('/order/orderdetails/{id}', [App\Http\Controllers\Api\OrderController::class,'orderDetailsAll']);

Route::post('/search/order', [App\Http\Controllers\Api\PosController::class,'searchOrderDate']);

//Admin Dashboard Sale
Route::get('/today/sales', [App\Http\Controllers\Api\PosController::class,'todaySale']);

Route::get('/today/income', [App\Http\Controllers\Api\PosController::class,'todayIncome']);

Route::get('/today/due', [App\Http\Controllers\Api\PosController::class,'todayDue']);

Route::get('/today/expense', [App\Http\Controllers\Api\PosController::class,'todayExpense']);

Route::get('/stockout', [App\Http\Controllers\Api\PosController::class,'stockOut']);


