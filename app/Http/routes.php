<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
//预定 no need auth
Route::get('book/{jingdian?}/{shangjia?}', 'BookController@getBook')->where(['jingdian' => '[0-9]+', 'shangjia' => '[0-9]+']);
Route::post('book', 'BookController@postBook');
//微信
Route::group(array('prefix' => 'wechat'),function()
{
    Route::any('/', 'WechatController@serve');
    Route::get('/setMenu', 'WechatController@setMenu');
});
//404
Route::get('404', function()
{
    App::abort(404);
});
