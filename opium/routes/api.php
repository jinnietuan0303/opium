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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (){
    return "Đây là kết quả trả về từ server";
});

Route::apiResource('/category', 'Api\CategoryController');
Route::apiResource('/post', 'Api\PostController');
Route::get('/post-top-5', 'Api\PostController@getTop5');
