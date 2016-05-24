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
	$breadcrumbs->push('Editar Projeto',route('admin.project.edit',['id' => $project->id]));
});

BreadCrumbs::register('admin.user.projects',function($breadcrumbs){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push('Meus projetos',route('admin.user.projects'));
});

BreadCrumbs::register('admin.user.profile',function($breadcrumbs, $user){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push($user->name, route('admin.user.profile') );
});