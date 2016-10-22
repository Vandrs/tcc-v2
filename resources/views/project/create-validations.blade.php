@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.validations.create',$project)!!}
    <div class="row margin-top-20">
        <div class="col-xs-12">
            @include('project.partials.project-navigation',['project' => $project])
        </div>
    </div>
@endsection
@section('content')
    @include('partials.view-errors')
    {!!Form::open(['route' => ['admin.project.validations.save', $project->id], 'method' => 'post'])!!}
    <div class="row">
        <div class="col-xs-12">
            <h2 class="form-section-title">Informações gerais</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group {{$errors->has('title') ? 'has-error':''}}">
                <label class="control-label" for="title">Título*</label>
                <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" placeholder="Título do questionário" maxlength="120"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="form-group {{$errors->has('started_at') ? 'has-error':''}}">
                <label class="control-label" for="started_at">Data de Início*</label>
                <input type="text" class="form-control date" id="started_at" name="started_at" value="{{old('started_at')}}" placeholder="Data de início do questionário" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group {{$errors->has('ended_at') ? 'has-error':''}}">
                <label class="control-label" for="ended_at">Data de Término*</label>
                <input type="text" class="form-control date" id="ended_at" name="ended_at" value="{{old('ended_at')}}" placeholder="Data limite para realizar o questionário" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h2 class="form-section-title">Questões</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-left">
            <button type="button" class="btn btn-success btn-raised" id="addQuestion">
                <span class="glyphicon glyphicon-plus-sign"></span> Adicionar Questão
            </button>
        </div>
    </div>
    <div class="row questions-container">
        @if(old('question',null))
            @foreach(old('question') as $idx => $question)
            <div class="col-xs-12 question" data-idx="{{$idx}}">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="question[{{$idx}}][title]" class="form-control" value="{{$question['title']}}" placeholder="Título da Questão"/>
                        <span class="input-group-btn">
                            <button class="btn btn-fab btn-fab-mini deleteQuestion">
                                <i class="material-icons">delete</i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            <button type="submit" class="btn btn-primary btn-raised">
                <i class="material-icons">check</i> Criar Validação
            </button>
        </div>
    </div>
    {!!Form::close()!!}
@endsection