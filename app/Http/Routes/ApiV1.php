<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-15
 * Time: 17:35
 */

Route::get('login', 'TestController@index');
Route::get('banners', 'BannersController@index');
Route::get('notices', 'NoticesController@index');
Route::get('categories', 'CategoriesController@index');

Route::post('user/login', 'UserController@login');
Route::post('user/register', 'UserController@register');
Route::get('user/test', 'UserController@checkToken');