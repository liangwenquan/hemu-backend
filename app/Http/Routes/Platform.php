<?php/** * Created by PhpStorm. * User: mytoken * Date: 2019-04-17 * Time: 10:32 */Route::post('platform/login', 'AdminUserController@login');Route::post('adminUser', 'AdminUserController@create');Route::get('userInfo', 'AdminUserController@info');Route::get('product/list', 'ProductController@index');