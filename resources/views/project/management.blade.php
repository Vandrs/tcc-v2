@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.management',$project)!!}
    <div class="row margin-top-20">
        <div class="col-xs-12">
            @include('project.partials.project-navigation',['project' => $project])
        </div>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12 ">
        @include('partials.view-errors')
        <div class="row">
            <div class="col-xs-12 margin-top-10">
                <div class="trelloBeedBackArea">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h2 class='form-section-title'>{{$project->title}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="button" class="btn btn-info btn-raised" id="newList">
                    <i class="material-icons">list</i> Nova Lista
                </button>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="management-container">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modals-area">
    <div class="modal fade" tabindex="-1" id="modalList">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <input type="text" name="name"/>
                    <input type="hidden" name="listId"/>
                    <input type="hidden" name="listAction"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success ok-action"><i class="material-icons">save</i> Salvar</button>
                    <button type="button" class="btn btn-default cancel-action" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection