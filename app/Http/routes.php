<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',['as' => 'users', 'uses' => 'UserController@all']);
Route::get('/usuario/login/{id}',['as' => 'users', 'uses' => 'UserController@loginAs']);
Route::get('/projetos/recomendar/{userId}',['as' => 'project.predictions', 'uses' => 'ProjectController@getPredictions']);
Route::get('/projeto/{id}',['as' => 'site.project.view', 'uses' => 'ProjectController@view']);
Route::get('/teste','TesteController@index');
