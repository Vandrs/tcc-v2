@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.project.create')!!}
@endsection
@section('content')
	{!!Form::open(['route' => 'admin.project.store', 'method' => 'post'])!!}
	{!!Form::token()!!}
	<div class="row">
		<div class="col-xs-12 box">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="form-section-title">Informações gerais</h2>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 control-group {{$errors->has('title')?'has-error':''}}">
				{!!Form::label('title','Título*',['class' => 'control-label'])!!}
				{!!Form::text('title', null,['class' => 'form-control', 'placeholder' => 'Título do projeto', 'maxlenth' => '100'])!!}
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 control-group {{$errors->has('description')?'has-error':''}}">
				{!!Form::label('description','Descrição*',['class' => 'control-label'])!!}
				{!!Form::textarea('description', null,['class' => 'form-control', 'placeholder' => 'Descrição resumida do projeto. Informe as principais características do projeto e quais os objetivos se deseja alcançar com a realização do mesmo.', 'row' => '10'])!!}
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 control-group {{$errors->has('description')?'has-error':''}}">
				{!!Form::label('category_id','Categoria*',['class' => 'control-label'])!!}
				{!!Form::select('category_id', $categories,null,['class' => 'form-control', 'placeholder' => 'Categoria a qual o projeto pertence'])!!}
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 text-right">
					{!!Form::submit('Salvar',['class' => 'btn btn-primary'])!!}
					<a href="#" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</div>
	</div>
	{!!Form::close()!!}
@endsection