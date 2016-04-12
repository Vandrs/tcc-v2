@extends('layouts.master')
@section('body')
    <div class="container">
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="well">
                    <h1>C3 - Projetos</h1>
                </div> 
            </div>
        </div>
        <div class="row margin-top-10">
        	<div class="col-xs-12">
        		<ul>
        			@foreach($users as $user)
        				<li>
        					<a href="{{route('project.predictions',['userId' => $user->id])}}">{{$user->name}}</a>
        				</li>
        			@endforeach
        		</ul>
        	</div>
        </div>
    </div>
@endsection