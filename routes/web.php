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



// Route::get('/', function () {
//     // return view('welcome');
//     return view('index');
// });
// Route::get('/table', function () {
//     // return view('welcome');
//     return view('table');
// });

// Route::get('/auth', function () {
//     return view('login');
// });

Route::get('/', 'AuthController@index')->name('login');
Route::post('/', 'AuthController@store')->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::resource('/setting-global', 'SettingGlobalController',  [
        'uses' => ['index', 'store']
    ]);
    Route::resource('/user', 'UserController',  [
        'uses' => ['index', 'store']
    ]);
});
