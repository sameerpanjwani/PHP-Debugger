<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 10/3/2018
 * Time: 1:07 PM
 */


Route::get('activity_log',['as' => 'activity_log', 'uses' => 'ActivityLogController@index']);