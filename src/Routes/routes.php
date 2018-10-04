<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 10/3/2018
 * Time: 1:07 PM
 */


//Route::get('activity_log',['as' => 'activity_log', 'uses' => 'ActivityLogController@index']);

Route::group(['namespace' => 'Mondovo\Debugger\Controllers'], function()
{
    Route::get('activity_log', ['uses' => 'ActivityLogController@index']);
    Route::post('ajax/activity-log-data', ['as' => 'activity_log_data', 'uses' => 'ActivityLogController@activityLogData']);
    Route::get('debugger_log_details/{id?}',['as' => 'debugger_log_details', 'uses' => 'ActivityLogController@debuggerLogDetails']);
});