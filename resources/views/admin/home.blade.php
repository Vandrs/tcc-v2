@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.home')!!}
@endsection
@section('content')
	<div class="row">
		<div class="col-xs-12">
			Olá {{$user->name}}
		</div>
	</div>
@endsection