@extends('layouts.admin')
@inject('enumUser','App\Models\Enums\EnumUser')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.validations.reports', $project, $projectValidation)!!}
    <div class="row margin-top-20">
        <div class="col-xs-12">
            @include('project.partials.project-navigation',['project' => $project])
        </div>
    </div>
@endsection
@section('content')
    @include('partials.view-errors')
    <div class="row">
        <form id='filterForm' action="#" >
            <div class="col-xs-12 col-md-4 col-sm-6">
                <div class="form-group">
                    <label for="gender" class="control-label">Sexo</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="">Selecione</option>
                        @foreach($enumUser::getGenderLabels() as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 col-sm-6">
                <div class="form-group">
                    <label for="min_age" class="control-label">Idade Mínima</label>
                    <input type="number" name="min_age" id="min_age" min="0" class="form-control"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 col-sm-6">
                <div class="form-group">
                    <label for="max_age" class="control-label">Idade Máxima</label>
                    <input type="number" name="max_age" id="max_age" min="0" class="form-control"/>
                </div>
            </div>
        </form>
    </div>
    <div class="row margin-top-20" id="reportsContainer">
        <div class="col-xs-12">
            <div class="row generalReport">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Geral</h2>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10">
                    <canvas class="generalPercentual"></canvas>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10 ">
                    <canvas class="generalQuantity"></canvas>
                </div>
            </div>
            @foreach($projectValidation->questions as $question)
            <div class="row questionReport margin-top-20" data-question-id="{{$question->id}}">
                <div class="col-xs-12">
                    <h2 class="form-section-title">{{$question->title}}</h2>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10">
                    <canvas class="percentual"></canvas>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10 ">
                    <canvas class="quantity"></canvas>
                </div>
            </div>
            @endforeach
            <div class="row recommendReport margin-top-20">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Você utilizaria e/ou recomendaria o sistema para outra pessoa?</h2>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10">
                    <canvas class="percentual"></canvas>
                </div>
                <div class="col-xs-12 col-md-6 margin-top-10 ">
                    <canvas class="quantity"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h2 class="form-section-title">Sugestões e comentários</h2>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-xs-12">
            <table data-list-route="{{route('admin.project.validations.reports.suggestion',['id' => $project->id, 'validationId' => $projectValidation->id])}}" id="suggestionsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection