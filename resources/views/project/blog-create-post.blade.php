@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.project.blog.post.create',$project)!!}
@endsection
@section('content')
	{!!Form::open( ['route' => ['admin.project.post.save', 'projectId' => $project->id] , 'method' => 'post'])!!}
	{!!Form::token()!!}
	<div class="row">
		<div class="col-xs-12 ">
			@include('partials.view-errors')
			<div class="row margin-top-10">
				<div class="col-xs-12 control-group {{$errors->has('title')?'has-error':''}}">
				{!!Form::text('title', null,['class' => 'form-control', 'placeholder' => 'Título do Post', 'maxlenth' => '100'])!!}
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 control-group {{$errors->has('text')?'has-error':''}}">
				{!!Form::label('text','Texto*',['class' => 'control-label'])!!}
				{!!Form::textarea('text', null,['class' => 'form-control', 'placeholder' => 'Texto do post.', 'row' => '10'])!!}
				</div>
			</div>
			<div class='hidden'>
				<input type='file' id='imageUpload' name="imageUpload" data-upload-route="{{route('image.upload')}}"/>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12 text-right">
					<button type="submit" class="btn btn-primary btn-raised full-size-on-small">
						<span class="glyphicon glyphicon-floppy-save"></span> Salvar
					</button>
					<a href="{{route('admin.project.posts',['projectId' => $project->id])}}" class="btn btn-default btn-raised full-size-on-small">Cancelar</a>
				</div>
			</div>
		</div>
	</div>
	{!!Form::close()!!}
@endsection