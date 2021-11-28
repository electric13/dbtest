<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIRefDataController;
use App\Http\Controllers\APIRegistrator;
use App\Http\Controllers\APIBasketController;

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

Route::get('register',     [APIRegistrator::class, 'getKey']);

Route::post('basket',         [APIBasketController::class, 'apiIndex']);
Route::post('basket/clear',   [APIBasketController::class, 'apiClear']);
Route::post('basket/add',     [APIBasketController::class, 'apiAdd']);
Route::post('basket/del',     [APIBasketController::class, 'apiDel']);
Route::post('basket/update',  [APIBasketController::class, 'apiUpd']);


Route::get('materials',    [APIRefDataController::class, 'apiMatIndex']);
Route::get('products',     [APIRefDataController::class, 'apiProdIndex']);
Route::get('items',        [APIRefDataController::class, 'apiItemsIndex']);
Route::get('groups',       [APIRefDataController::class, 'apiGroupsIndex']);

