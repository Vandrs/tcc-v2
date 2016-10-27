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

/*Admin*/
Route::get('/home', ['as' => 'admin.home', 'uses' => 'AdminController@home'] );
Route::get('/termos-e-polica-de-privacidade', ['as' => 'site.termos', 'uses' => 'SiteController@termos'] );

/*Site*/
Route::get('/',['as' => 'home', 'uses' => 'SiteController@home']);
Route::get('/busca',['as' => 'search', 'uses' => 'SiteController@search']);
Route::get('/pagina-nao-encontrada',['as' => 'site.404', 'uses' => 'SiteController@page404']);
Route::get('/erro',['as' => 'site.error', 'uses' => 'SiteController@error']);

/*Logins*/
Route::get('/login/fb',['as' => 'login.fb', 'uses' => 'SocialAuthController@fbLogin']);
Route::get('/login/gp',['as' => 'login.gp', 'uses' => 'SocialAuthController@gpLogin']);
Route::get('/login/linkedin',['as' => 'login.linkedin', 'uses' => 'SocialAuthController@linkedinLogin']);
Route::get('/login/fb/callback',['as' => 'login.fb.callback', 'uses' => 'SocialAuthController@fbLoginCallback']);
Route::get('/login/gp/callback',['as' => 'login.gp.callback', 'uses' => 'SocialAuthController@gpLoginCallback']);
Route::get('/login/linkedin/callback',['as' => 'login.linkedin.callback', 'uses' => 'SocialAuthController@linkedinLoginCallback']);

/*Projeto*/
Route::get('/meus-projetos',['as' => 'admin.user.projects', 'uses' => 'ProjectController@userProjects']);
Route::get('/projeto/novo',['as' => 'admin.project.create', 'uses' => 'ProjectController@create']);
Route::get('/projeto/{path}',['as' => 'site.project.view', 'uses' => 'ProjectController@view']);
Route::post('/projeto/cadastrar',['as' => 'admin.project.store', 'uses' => 'ProjectController@store']);
Route::get('/projeto/editar/{id}',['as' => 'admin.project.edit', 'uses' => 'ProjectController@edit']);
Route::post('/projeto/salvar/{id}',['as' => 'admin.project.update', 'uses' => 'ProjectController@update']);
Route::get('/projeto/deletar/{id}',['as' => 'admin.project.delete', 'uses' => 'ProjectController@delete']);
Route::post('/projeto/follow/{id}',['as' => 'site.project.follow', 'uses' => 'ProjectController@follow']);
Route::post('/projeto/unfollow/{id}',['as' => 'site.project.unfollow', 'uses' => 'ProjectController@unfollow']);

/*Post Projeto*/
Route::get('/projeto/posts/{projectId}',['as' => 'admin.project.posts', 'uses' => 'BlogController@posts']);
Route::get('/projeto/posts/list/{projectId}',['as' => 'admin.project.posts.list', 'uses' => 'BlogController@getPosts']);
Route::get('/projeto/post/novo/{projectId}',['as' => 'admin.project.post.create', 'uses' => 'BlogController@createPost']);
Route::post('/projeto/post/save/{projectId}',['as' => 'admin.project.post.save', 'uses' => 'BlogController@savePost']);
Route::get('/projeto/post/editar/{projectId}/{id}',['as' => 'admin.project.post.edit', 'uses' => 'BlogController@editPost']);
Route::post('/projeto/post/update/{projectId}/{id}',['as' => 'admin.project.post.update', 'uses' => 'BlogController@updatePost']);
Route::get('/projeto/post/deletar/{projectId}/{id}',['as' => 'admin.project.post.delete', 'uses' => 'BlogController@deletePost']);

/*Avaliação Projeto*/
Route::get('/projeto/minha-avaliacao/{projectId}',['as' => 'user.note.project','uses' => 'ProjectNotesController@getUserActualNote']);
Route::post('/projeto/avaliar/{projectId}',['as' => 'project.rate','uses' => 'ProjectNotesController@rateProject']);

/*Gerenciamento Projeto*/
Route::get('/project/gerenciar/{id}',['as' => 'admin.project.management', 'uses' => 'ProjectManagementController@index']);
Route::get('/project/gerenciar/primeiro-acesso/{id}',['as' => 'admin.project.management.first', 'uses' => 'ProjectManagementController@firstTimeAccess']);
Route::post('/project/gerenciar/assign-board/{id}',['as' => 'admin.project.management.keys', 'uses' => 'ProjectManagementController@assignKeys']);

/*Gerenciar Usuários Projeto*/
Route::get('/project/gerenciar-usuarios/{id}',['as' => 'admin.project.users', 'uses' => 'ProjectMembersController@index']);
Route::get('/project/listar-membros/{id}',['as' => 'admin.project.members', 'uses' => 'ProjectMembersController@listMembers']);
Route::post('/project/invite/{id}',['as' => 'admin.project.invite', 'uses' => 'ProjectMembersController@invite']);
Route::post('/project/change-role/{id}',['as' => 'admin.project.change-role', 'uses' => 'ProjectMembersController@changeRole']);
Route::post('/project/remove/{id}',['as' => 'admin.project.remove-member', 'uses' => 'ProjectMembersController@remove']);
Route::post('/project/invite/accept/{id}',['as' => 'admin.project.invidation.accept', 'uses' => 'ProjectMembersController@acceptInvitation']);
Route::post('/project/invite/deny/{id}',['as' => 'admin.project.invidation.deny', 'uses' => 'ProjectMembersController@denyInvitation']);
Route::post('/project/board/assigned/{id}', ['as' => 'admin.project.board-assigned', 'uses' => 'ProjectMembersController@userAssignedBoard']);
Route::get('/convites',['as' => 'project.invitations', 'uses' => 'ProjectMembersController@invitations']);
Route::get('/convites/lista',['as' => 'project.invitations.list', 'uses' => 'ProjectMembersController@listInvitations']);

/*Validação Projeto*/
Route::get('/projeto/validacoes/{id}',['as' => 'admin.project.validations', 'uses' => 'ProjectValidationController@index']);
Route::get('/projeto/validacao/nova/{id}',['as' => 'admin.project.validations.create', 'uses' => 'ProjectValidationController@create']);
Route::get('/project/validations/list/{id}',['as' => 'admin.project.validations.list', 'uses' => 'ProjectValidationController@list']);
Route::post('/project/validations/save/{id}',['as' => 'admin.project.validations.save', 'uses' => 'ProjectValidationController@save']);
Route::get('/projeto/validacao/editar/{id}/{validationId}',['as' => 'admin.project.validations.edit', 'uses' => 'ProjectValidationController@edit']);
Route::post('/project/validations/update/{id}/{validationId}',['as' => 'admin.project.validations.update', 'uses' => 'ProjectValidationController@update']);
Route::get('/project/validations/delete/{id}/{validationId}',['as' => 'admin.project.validations.delete', 'uses' => 'ProjectValidationController@delete']);
Route::post('/project/validations/question/delete/{id}/{validationId}',['as' => 'admin.project.validations.question.delete', 'uses' => 'ProjectValidationController@deleteQuestion']);
Route::get('/projeto/{path}/validacao/{validation_path}',['as' => 'site.project.validation', 'uses' => 'ProjectValidationController@view']);
Route::post('/project/validation/user/save/{validationId}', ['as' => 'site.validation.save', 'uses' => 'ProjectValidationController@saveUserValidation']);

/*Relatório Validação Projeto*/
Route::get('/project/validations/reports/{id}/{validationId}',['as' => 'admin.project.validations.reports', 'uses' => 'ValidationReportsController@index']);
Route::get('/project/validations/reports/{id}/{validationId}/report',['as' => 'admin.project.validations.reports.get', 'uses' => 'ValidationReportsController@getReport']);
Route::get('/project/validations/reports/{id}/{validationId}/recommend-report',['as' => 'admin.project.validations.recommend-report.get', 'uses' => 'ValidationReportsController@getRecommendReport']);
Route::get('/project/validations/reports/{id}/{validationId}/suggestion',['as' => 'admin.project.validations.reports.suggestion', 'uses' => 'ValidationReportsController@listSuggestions']);


/*Imagem*/
Route::post('/image/temp-upload',['as' => 'image.temp-upload', 'uses' => 'ImageController@tempUpload']);
Route::get('/image/temp/{file}',['as' => 'image.temp-file', 'uses' => 'ImageController@tempFile']);
Route::post('/image/temp/delete',['as' => 'image.temp-file.delete', 'uses' => 'ImageController@deleteTempFile']);
Route::get('/image/get/{path}',['as' => 'image.get', 'uses' => 'ImageController@getImage']);
Route::post('/image/create/{projectId}',['as' => 'image.create', 'uses' => 'ImageController@create']);
Route::post('/image/update/{projectId}',['as' => 'image.update', 'uses' => 'ImageController@update']);
Route::post('/image/delete/{projectId}',['as' => 'image.delete', 'uses' => 'ImageController@delete']);
Route::post('/image/upload',['as' => 'image.upload', 'uses' => 'ImageController@simpleUpload']);

/*Arquivo*/
Route::post('/file/temp-upload',['as' => 'file.temp-upload', 'uses' => 'FileController@tempUpload']);
Route::get('/file/get/{path}',['as' => 'file.get', 'uses' => 'FileController@get']);
Route::post('/file/create/{projectId}',['as' => 'file.create', 'uses' => 'FileController@create']);
Route::post('/file/delete/{projectId}',['as' => 'file.delete', 'uses' => 'FileController@delete']);

/*Usuario*/
Route::get('/usuarios/perfil/{id}',['as' => 'user.view', 'uses' => 'UserController@view']);
Route::get('/usuarios/modal-perfil',['as' => 'user.view.modal', 'uses' => 'UserController@viewModal']);
Route::get('/usuarios/meu-perfil',['as' => 'admin.user.profile', 'uses' => 'UserController@profile']);
Route::post('/usuarios/atualizar-perfil',['as' => 'admin.user.update.profile', 'uses' => 'UserController@updateProfile']);
Route::get('/usuarios/busca',['as' => 'users.search', 'uses' => 'UserController@search']);
Route::get('/cadastro',['as' => 'user.create', 'uses' => 'UserController@create']);
Route::post('/usuarios/add/trello-id',['as' => 'users.add.trello-id', 'uses' => 'UserController@addTrelloId']);
Route::post('/usuario/cadastrar',['as' => 'user.save', 'uses' => 'UserController@save']);

/*Curso */
Route::post('/graduation/delete', ['as' => 'graduation.delete', 'uses' => 'GraduationController@delete']);

/*Profissão*/
Route::post('/work/delete',['as' => 'work.delete', 'uses' => 'WorkController@delete']);

/*Teste*/
Route::get('/teste','TesteController@index');
Route::get('/teste/login/{id}',['as' => 'test.login', 'uses' => 'TesteController@loginAs']);
Route::get('/teste/logout',['as' => 'test.logout', 'uses' => 'TesteController@logout']);
Route::get('/teste/lista/login',['as' => 'test.users', 'uses' => 'TesteController@usersLoginList']);
Route::get('/teste/lista/projetos',['as' => 'test.projects', 'uses' => 'TesteController@projectsList']);

Route::auth();