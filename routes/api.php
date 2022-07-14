<?php

use App\Http\Controllers\Api\LabelController;
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
Route::name('api_')->middleware('token')->group(function () {
    Route::resource('label', 'Api\LabelController');
    Route::resource('task', 'Api\TaskController');
    Route::get('task-status/{id}', 'Api\TaskController@status')->name('taskStatus');
    Route::get('task-label/{id}', 'Api\TaskController@taskLabel')->name('taskLabel');
    Route::get('/dd', function () {
        dd('s');
    });
});
