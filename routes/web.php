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

Route::namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm');
    Route::post('login', 'LoginController@login')->name('auth.login');
    Route::post('logout','LoginController@logout')->name('auth.logout');

    Route::get('signup', 'RegisterController@showRegistrationForm');
    Route::post('signup', 'RegisterController@register')->name('auth.register');
});

Route::get('/', function () {
    return view('index');
})->name('root');

Route::get('/home', ['middleware' => 'auth',function () {
    return view('home/index');
}])->name('home');

Route::get('/{any?}',function () {
    return view('home/index');
})->where('any','.+')->middleware('web');