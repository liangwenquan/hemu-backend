<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-08-12
 * Time: 17:37
 */

Route::get('list', 'DetailController@index');
Route::post('detail/create', 'DetailController@create');
Route::get('detail/{id}', 'DetailController@info');