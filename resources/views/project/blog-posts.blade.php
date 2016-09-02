@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.project.blog.post-list', $project)!!}
@endsection
@section('content')
	<div class="row margin-top-10">
		<div class="col-xs-12 ">
			@include('partials.view-errors')
			<div class="row margin-top-10">
				<div class="col-xs-12 margin-top-10">
					<a href="{{route('admin.project.post.create',['projectId' => $project->id])}}" class="btn btn-success btn-raised">
						<span class="glyphicon glyphicon-plus-sign"></span> Novo post
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h2 class="form-section-title">Filtrar</h2>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<table id="postsTable" class="table table-bordered table-striped" data-list-route="{{route('admin.project.posts.list',['id' => $project->id])}}">
						<thead>
							<tr>
								<th>Título</th>	
								<th>Criado em</th>
								<th>Criado por</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection