<?php 
Route::get('/file-import-export', [
    'uses' => 'ReportController@fileImportExport',
    'as' => 'file-import-export'
]);
Route::post('/file-import', [
    'uses' => 'ReportController@fileImport',
    'as' => 'file-import'
]);
Route::get('/file-export', [
    'uses' => 'ReportController@fileExport',
    'as' => 'file-export'
]);

