@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.home')!!}
@endsection
@section('content')
	@include('partials.view-errors')
	<div class="row">
		<div class="col-xs-12">
			Olá <strong>{{$user->name}}</strong>, visualize seus projeto abaixo.
		</div>
	</div>
	
	<div class="row margin-top-10">
        @foreach($user->projectsAsOwner() as $idx => $project)
            @if($idx > 0 && ($idx % 3 == 0) )
            </div>
            <div class="row margin-top-10">
            @endif
            <div class="col-xs-12 col-sm-6 col-md-4 margin-top-10">
            @include('partials.project-card',['project' => $project])
            </div>
        @endforeach
    </div>
	
@endsection

