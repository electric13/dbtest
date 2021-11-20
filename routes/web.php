<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MainController;
use \App\Http\Controllers\AddItemController;
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

Route::get('/', function () {
    return view('welcome'); //, ['contacts'=> $contacts]);
});
