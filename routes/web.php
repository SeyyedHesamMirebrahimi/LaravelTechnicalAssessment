<?php

use App\Model\Label;
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

Route::get('/', function () {var_dump(count(Label::all()));die;return view('layouts.panel');});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dev', 'HomeController@dev')->name('dev');
Route::get('/user', function (){var_dump(\Illuminate\Support\Facades\Auth::user()->token);die;})->name('home');
Route::group(['prefix' => '/panel'] , function (){
    Route::resource('label', 'Panel\LabelController');
    Route::resource('task', 'Panel\TaskController');
});
