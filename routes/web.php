<?php

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
Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('pages/main');
})->name('home');

Route::get('/about-smartcontract', function () {
    return view('pages/about-smartcontract');
});

Route::get('/profile', function () {
    return view('pages/profile', ['page' => '/']);
})->middleware('auth')->middleware('verified')->name('profile');

Route::get('/profile{pattern}', function ($page) {
    return view('pages/profile', ['page' => $page]);
})->middleware('auth')->middleware('verified')->where('pattern', '.*');

Route::get('/test', function () {
    return view('pages/test');
});

Route::post('/contract/get_instruction/', 'ContractController@getInstruction');
Route::post('/contract/remove/', 'ContractController@removeContract');
Route::post('/contract/get_contracts/', 'ContractController@getContracts');
Route::post('/contract/create/', 'ContractController@createContract');
Route::post('/contract/get_list_templates/', 'ContractController@getListTemplates');
Route::post('/contract/get_template/', 'ContractController@getTemplate');
Route::post('/contract/check_fields/', 'ContractController@checkFields');

