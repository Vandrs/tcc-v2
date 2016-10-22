@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.validations',$project)!!}
    <div class="row margin-top-20">
        <div class="col-xs-12">
            @include('project.partials.project-navigation',['project' => $project])
        </div>
    </div>
@endsection
@section('content')
    <div class="row margin-top-20">
        <div class="col-xs-12">
            <a href="{{route('admin.project.validations.create',['id' => $project->id])}}" class="btn btn-success btn-raised">
                <span class="glyphicon glyphicon-plus-sign"></span> Nova Validação
            </a>
        </div>
    </div>
    @include('partials.view-errors')
    <div class="row margin-top-20">
        <div class="col-xs-12">
            <table data-list-route="{{route('admin.project.validations.list',['id' => $project->id])}}" id="tableValidations" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Início</th>
                        <th>Término</th>
                        <th class="action-header">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection