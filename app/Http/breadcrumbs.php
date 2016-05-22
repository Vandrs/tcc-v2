<?php

Breadcrumbs::register('admin.home',function($breadcrumbs){
	$breadcrumbs->push('Home',route('admin.home'));
});

BreadCrumbs::register('admin.project.create',function($breadcrumbs){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push('Novo Projeto',route('admin.project.create'));
});

BreadCrumbs::register('admin.project.update',function($breadcrumbs, $project){
	$breadcrumbs->parent('admin.home');
	$breadcrumbs->push('Editar Projeto',route('admin.project.edit',['id' => $project->id]));
});