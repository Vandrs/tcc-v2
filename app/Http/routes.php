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

/*Site*/
Route::get('/',['as' => 'home', 'uses' => 'SiteController@home']);
Route::get('/busca',['as' => 'search', 'uses' => 'SiteController@search']);

/*Projeto*/
Route::get('/projeto/{id}',['as' => 'site.project.view', 'uses' => 'ProjectController@view']);

/*Usuario*/
Route::get('/usuarios/perfil/{id}',['as' => 'user.view', 'uses' => 'UserController@view']);



/*Teste*/
Route::get('/teste','TesteController@index');
Route::get('/teste/login/{id}',['as' => 'test.login', 'uses' => 'TesteController@loginAs']);
Route::get('/teste/logout',['as' => 'test.logout', 'uses' => 'TesteController@logout']);
Route::get('/teste/lista/login',['as' => 'test.users', 'uses' => 'TesteController@usersLoginList']);
Route::get('/teste/lista/projetos',['as' => 'test.projects', 'uses' => 'TesteController@projectsList']);