<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MainController;
use \App\Http\Controllers\AddItemController;
use \App\Http\Controllers\APIRefDataController;
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

Route::get('/order/{id}',  [MainController::class, 'order']);
Route::get('/vue',  [MainController::class, 'vueTest']);

Route::get('/basket',       [MainController::class, 'showBasket']);

Route::get('/basket/add',   [AddItemController::class, 'add']);
Route::get('/basket/clear', [AddItemController::class, 'clear']);
Route::post('/basket/del',  [AddItemController::class, 'delete']);

Route::get('/api/basket',       [AddItemController::class, 'apiIndex']);
Route::get('/api/basket/clear', [AddItemController::class, 'apiClear']);
Route::post('/api/basket/add',  [AddItemController::class, 'apiAdd']);
Route::post('/api/basket/del',  [AddItemController::class, 'apiDel']);
Route::get('/api/materials',    [APIRefDataController::class, 'apiMatIndex']);
Route::get('/api/products',     [APIRefDataController::class, 'apiProdIndex']);

Route::get('/', function () {
    return view('welcome'); //, ['contacts'=> $contacts]);
});
