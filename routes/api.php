<?php

use Illuminate\Http\Request;



Route::post('/register', [
    'uses' => 'AuthController@register'
]);
Route::post('/auth', [
    'uses' => 'AuthController@auth'
]);


Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('/сompany', [
        'uses' => 'CompanyController@companyInfo'
    ]);

    Route::post('/user', [
        'uses' => 'UserController@create'
    ])->middleware('user.access:create');

    Route::put('user/{id}', [
        'uses' => 'UserController@update'
    ])->middleware('user.access:edit')->where('id', '[0-9]+');

    Route::patch('/user/{id}', [
        'uses' => 'UserController@status'
    ])->middleware('user.access:status')->where('id', '[0-9]+');

    Route::delete('/user/{id}', [
        'uses' => 'UserController@delete'
    ])->middleware('user.access:delete')->where('id', '[0-9]+');
});
