@extends('layouts.admin')
@section('content')
    <div class="row margin-top-10">
        <div class="col-xs-12 text-center">
                <h1>C3 - Projetos</h1>
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <form action="{{route('search')}}" method="GET" class="inline">
                <div class="input-group">
                  <input name="q" type="text" required class="form-control" placeholder="Buscar por tema, assunto ou categoria">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Buscar <span class="glyphicon glyphicon-search"></span></button>
                  </span>
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