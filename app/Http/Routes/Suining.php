<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-09-06
 * Time: 09:30
 */

Route::get('welfare/list', 'WelfareController@index');
Route::get('welfare/{welfareId}', 'WelfareController@info');
Route::get('shop/{shopId}', 'ShopController@info');