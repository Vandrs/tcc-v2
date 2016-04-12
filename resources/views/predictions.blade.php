@extends('layouts.master')
@section('body')
	<div class="container">
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <h2>{{$user->name}}</h2>
            </div>
        </div>
        <div class="row">
        	<div class="col-xs-12 col-sm-6">
        		<table>
        			<thead>
        				<tr>
        					<th colspan="2">Projetos avaliados</th>	
        				</tr>	
        			</thead>
        			<tbody>
        				@foreach($user->notes as $projectNote)		
        					<tr>
        						<td>{{$projectNote->project->title}}</td>
        						<td>{{$projectNote->note}}</td>
        					</tr>
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        	<div class="col-xs-12 col-sm-6">
        		<table>
        			<thead>
        				<tr>
        					<th colspan="2">Predições</th>	
        				</tr>	
        			</thead>
        			<tbody>
        				@foreach($predictions as $project)		
        					<tr>
        						<td>{{$project->title}}</td>
        						<td>{{$project->preference}}</td>
        					</tr>
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
@endsection