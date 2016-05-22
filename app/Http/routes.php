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
Route::get('/pagina-nao-encontrada',['as' => 'site.404', 'uses' => 'SiteController@page404']);

/*Admin*/
Route::get('/home',['as' => 'admin.home', 'uses' => 'AdminController@home']);

/*Projeto*/
Route::get('/projeto/novo',['as' => 'admin.project.create', 'uses' => 'ProjectController@create']);
Route::get('/projeto/{id}',['as' => 'site.project.view', 'uses' => 'ProjectController@view']);
Route::post('/projeto/cadastrar',['as' => 'admin.project.store', 'uses' => 'ProjectController@store']);
Route::get('/projeto/editar/{id}',['as' => 'admin.project.edit', 'uses' => 'ProjectController@edit']);
Route::post('/projeto/salvar/{id}',['as' => 'admin.project.update', 'uses' => 'ProjectController@update']);

/*Imagem*/
Route::post('/image/temp-upload',['as' => 'image.temp-upload', 'uses' => 'ImageController@tempUpload']);
Route::get('/image/temp/{file}',['as' => 'image.temp-file', 'uses' => 'ImageController@tempFile']);
Route::post('/image/temp/delete',['as' => 'image.temp-file.delete', 'uses' => 'ImageController@deleteTempFile']);
Route::get('/image/get/{path}',['as' => 'image.get', 'uses' => 'ImageController@getImage']);

/*Arquivo*/
Route::post('/file/temp-upload',['as' => 'file.temp-upload', 'uses' => 'FileController@tempUpload']);
Route::get('/file/get/{path}',['as' => 'file.get', 'uses' => 'FileController@get']);

/*Usuario*/
Route::get('/usuarios/perfil/{id}',['as' => 'user.view', 'uses' => 'UserController@view']);

/*Teste*/
Route::get('/teste','TesteController@index');
Route::get('/teste/login/{id}',['as' => 'test.login', 'uses' => 'TesteController@loginAs']);
Route::get('/teste/logout',['as' => 'test.logout', 'uses' => 'TesteController@logout']);
Route::get('/teste/lista/login',['as' => 'test.users', 'uses' => 'TesteController@usersLoginList']);
Route::get('/teste/lista/projetos',['as' => 'test.projects', 'uses' => 'TesteController@projectsList']);