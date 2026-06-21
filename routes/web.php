<?php

use App\Http\Controllers\EcommerceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [EcommerceController::class, 'app']);
Route::get('/api/state', [EcommerceController::class, 'state']);
Route::post('/api/login', [EcommerceController::class, 'login']);
Route::post('/api/logout', [EcommerceController::class, 'logout']);
Route::post('/api/checkout', [EcommerceController::class, 'checkout']);
Route::put('/api/orders/{id}/status', [EcommerceController::class, 'updateOrderStatus']);
Route::put('/api/orders/{id}', [EcommerceController::class, 'updateOrder']);
Route::delete('/api/orders/{id}', [EcommerceController::class, 'deleteOrder']);
Route::post('/api/banner', [EcommerceController::class, 'updateBanner']);
Route::post('/api/categories', [EcommerceController::class, 'storeCategory']);
Route::put('/api/categories/{id}', [EcommerceController::class, 'updateCategory']);
Route::delete('/api/categories/{id}', [EcommerceController::class, 'deleteCategory']);
Route::post('/api/subcategories', [EcommerceController::class, 'storeSubcategory']);
Route::put('/api/subcategories/{id}', [EcommerceController::class, 'updateSubcategory']);
Route::delete('/api/subcategories/{id}', [EcommerceController::class, 'deleteSubcategory']);
Route::post('/api/products', [EcommerceController::class, 'storeProduct']);
Route::put('/api/products/{id}', [EcommerceController::class, 'updateProduct']);
Route::delete('/api/products/{id}', [EcommerceController::class, 'deleteProduct']);
