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

Route::get('products', 'ProductController@index');
Route::get('products/{id}', 'ProductController@info');

Route::post('order', 'OrderController@create');
Route::post('orderPreview', 'OrderController@orderPreview');
Route::get('user/orders', 'OrderController@userOrderList');
Route::get('user/orders/{id}', 'OrderController@userOrderDetail');