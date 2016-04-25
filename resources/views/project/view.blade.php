@extends('layouts.master')
@section('body')
	<div class="container">
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <h2>{{$project->title}}</h2>
            </div>
        </div>
        <div class="row">
        	<div class="col-xs-12">
        		<table>
        			<thead>
        				<tr>
        					<th colspan="2">Notas</th>	
        				</tr>	
        			</thead>
        			<tbody>
        				@foreach($project->notes as $projectNote)		
        					<tr>
        						<td>
        						<a href="{{route('user.view',['id' => $projectNote->user->id])}}">{{$projectNote->user->name}}</a>
        						</td>
        						<td>&nbsp;{{$projectNote->note}}</td>
        					</tr>
        				@endforeach
                        <tr>
                            <td>Média </td>
                            <td>&nbsp;{{$project->getAvgNote()}}</td>
                        </tr>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
@endsection