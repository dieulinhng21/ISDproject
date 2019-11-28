<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'admin'], function() {
    Route::resource('admin/project','ProjectController');
    Route::resource('admin/contract', 'ContractController');
    Route::resource('admin/apartment','ApartmentController');
    Route::resource('admin/flat','FlatController');
    Route::resource('admin/manager','ManagerController');
    Route::resource('admin/customer','CustomerController');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
