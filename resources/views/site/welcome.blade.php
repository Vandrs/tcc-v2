@extends('layouts.admin')
@section('content')
    <div class="row margin-top-10">
        <div class="col-xs-12 text-center">
                <h1>C3 - Projetos</h1>
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <form action="{{route('search')}}" method="GET" class="bs-component">
                <div class="form-group label-floating is-empty">
		            <label class="control-label" for="q">Buscar por tema, assunto ou categoria</label>
		            <div class="input-group">
		              	<input type="text" id="q" name="q" required class="form-control">
		              	<span class="input-group-btn">
		                	<button type="submit" class="btn btn-fab btn-info">
		                  		<i class="material-icons">search</i>
		                	</button>
		              	</span>
		            </div>
	          	</div>
            </form>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-xs-12 text-center">
            <h2>Projetos em destaque</h2>
        </div>
    </div>
    <div class="row margin-top-10">
        @foreach($projects as $idx => $project)
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