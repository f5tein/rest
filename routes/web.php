<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'CasesController@index');
Route::get('/{state}/{startDate}/{endDate}', 'CasesController@list');
Route::get('/cases/{state}/{startDate}/{endDate}', 'CasesController@list');