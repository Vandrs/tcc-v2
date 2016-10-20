<?php

Breadcrumbs::register('admin.home',function($breadcrumbs){
	$breadcrumbs->push('Home',route('admin.home'));
});

BreadCrumbs::register('admin.project.create',function($breadcrumbs){
	$breadcrumbs->parent('admin.user.projects');
	$breadcrumbs->push('Novo Projeto',route('admin.project.create'));
});

BreadCrumbs::register('admin.project.update',function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.user.projects');
	$breadcrumbs->push($project->title,route('admin.project.edit',['id' => $project->id]));
});

BreadCrumbs::register('admin.user.projects',function($breadcrumbs){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push('Meus projetos',route('admin.user.projects'));
});

BreadCrumbs::register('project.invitations',function($breadcrumbs){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push('Convites',route('project.invitations'));
});

BreadCrumbs::register('admin.user.profile',function($breadcrumbs, $user){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push($user->name, route('admin.user.profile') );
});

BreadCrumbs::register('admin.project.blog.post-list', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.update', $project);
	$breadcrumbs->push('Lista de Posts', route('admin.project.posts', ['projectId' => $project->id]));
});

BreadCrumbs::register('admin.project.blog.post.create', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.blog.post-list', $project);
	$breadcrumbs->push('Novo Post', route('admin.project.post.create', ['projectId' => $project->id]));
});

BreadCrumbs::register('admin.project.blog.post.update', function($breadcrumbs, $project, $post){
	$breadcrumbs->parent('admin.project.blog.post-list', $project);
	$breadcrumbs->push('Editar Post', route('admin.project.post.edit', ['projectId' => $project->id, 'id' => $post->id]));
});

BreadCrumbs::register('admin.project.management', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.update', $project);
	$breadcrumbs->push('Gerenciamento', route('admin.project.management', ['id' => $project->id]));
});

BreadCrumbs::register('admin.project.users', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.update', $project);
	$breadcrumbs->push('Gerenciamento de Usuários', route('admin.project.users', ['id' => $project->id]));
});

BreadCrumbs::register('admin.project.validations', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.update', $project);
	$breadcrumbs->push('Validações do Projeto', route('admin.project.validations', ['id' => $project->id]));
});

BreadCrumbs::register('admin.project.validations.create', function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.project.validations', $project);
	$breadcrumbs->push('Nova Validação', route('admin.project.validations.create', ['id' => $project->id]));
});

