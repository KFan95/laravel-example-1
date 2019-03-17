<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web Routes for your application. These
| Routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/', [
        'as' => 'video.index',
        'uses' => 'VideoController@index'
    ]);

    Route::get('/video/{id}', [
        'as' => 'video.detail',
        'uses' => 'VideoController@detail'
    ])->where('id', '(\d+)');

    Route::get('/video/{id}/state', [
        'as' => 'video.state',
        'uses' => 'VideoController@state'
    ])->where('id', '(\d+)');

    Route::post('/video/upload', [
        'as' => 'video.upload',
        'uses' => 'VideoController@upload'
    ]);
});
