<?php 

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'cronJob'
], function () {
    Route::get('createClass', 'CronJobController@createClass');
    Route::get('createStudent', 'CronJobController@createStudent');
    Route::get('get-phone-tgdd', 'CrawlingController@getPhoneTgdd');
    Route::get('get-doanh-nghiep', 'CrawlingController@getDoanhNghiep');
});
