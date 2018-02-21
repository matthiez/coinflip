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

Route::get('/', function() {
    if (Auth::guest()) {
        return view('login');
    }
    return view('coinflip');
})->name('home');

Route::get('/steamlogin', 'AuthController@steamLogin')->name('steamlogin');

Route::group(['middleware' => 'auth.steam'], function () {
    Route::get('/coinflip', function() {
        return view('coinflip');
    })->name('coinflip');

    Route::get('/deposit', function() {
        return view('deposit');
    })->name('deposit');

    Route::get('/withdraw', function() {
        return view('withdraw');
    })->name('withdraw');

    Route::get('/settings', function() {
        return view('settings');
    })->name('settings');

    Route::get('/admin/generateHash', 'AdminController@generateHash')->name('adminGenerateHash');

    Route::post('/auth', 'UserController@auth')->name('auth');
    Route::post('/getToken', 'UserController@getToken')->name('getToken');

    Route::post('/withdraw/getInventory', 'WithdrawalController@getInventory')->name('withdrawGetInventory');
    Route::post('/withdraw/start', 'WithdrawalController@start')->name('withdrawStart');

    Route::group(['middleware' => ['XSS']], function () {
        Route::post('/set/timezone', 'SettingsController@timezone')->name('setTimezone');
        Route::post('/set/tradelink', 'SettingsController@tradelink')->name('setTradelink');

        Route::post('/coinflip/create', 'CoinflipController@create')->name('coinflipCreate');
        Route::post('/coinflip/join', 'CoinflipController@join')->name('coinflipJoin');
    });

    Route::post('/logout', function() {
        Request::session()->flush();
        Cookie::queue('token', '', time() - 3600, '/', '', true, false);
        Auth::logout();
        return redirect()->route('home');
    })->name('logout');

});