@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.home')!!}
@endsection
@section('content')
	@include('partials.view-errors')
	<div class="row">
		<div class="col-xs-12">
			Olá <strong>{{$user->name}}</strong>, bem-vindo ao C3-Projetos.
		</div>
	</div>

    @if($userProjects->count())
    <div class="row margin-top-30">
        <div class="col-xs-12">
           <h4>Últimos projetos com atividades</h4>
        </div>
    </div>
	<div class="row margin-top-10">
        @foreach($userProjects as $idx => $project)
            @if($idx > 0 && ($idx % 4 == 0) )
            </div>
            <div class="row margin-top-10">
            @endif
            <div class="col-xs-12 col-sm-6 col-md-3 margin-top-10">
            @include('partials.project-card',['project' => $project, 'showControls' => true])
            </div>
        @endforeach
    </div>
    @else
        <div class="row margin-top-20">
            <div class="col-xs-12">
                <h4>Você ainda não possui projetos. Clique <a href="{{route('admin.project.create')}}" class="btn btn-primary">aqui</a> para iniciar um projeto agora mesmo.</h4>
            </div>
        </div>
    @endif

    @if($featuredProjects->count())
    <div class="row margin-top-30">
        <div class="col-xs-12">
            <h4>Projetos que podem ser de seu interesse</h4>
        </div>
    </div>
    <div class="row margin-top-10">
        @foreach($featuredProjects as $idx => $project)
            @if($idx > 0 && ($idx % 4 == 0) )
            </div>
            <div class="row margin-top-10">
        @endif
        <div class="col-xs-12 col-sm-6 col-md-3 margin-top-10">
            @include('partials.project-card',['project' => $project])
        </div>
        @endforeach
    </div>
    @endif
	
@endsection

