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
                <div class="col-xs-12">
                    <h2 class='form-section-title'>Primeiro Acesso</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="alert alert-info">
                        <p>
                            <icon class="glyphicon glyphicon-warning"></icon>Esta é a sua primeira vez acessando a área de gerenciamento deste projeto, portanto alguns passos serão necessários.
                        </p>
                        <p>
                            O <strong>C3 - Projetos</strong> utiliza a API da ferramenta <strong>Trello</strong> para proporcionar uma melhor experiência aos seus usuários no gerenciamento de seus projetos.<br />
                            Você pode vincular uma conta já existente do Trello e caso queira pode importar seus Quadros ou criar um novo. <br />
                            Caso você não possua uma conta no Trello será possível criar uma conta no próximo passo.
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 text-right">
                    <button id="aceptTrello" class="btn btn-success btn-raised">
                        <span class="glyphicon glyphicon-thumbs-up"></span> Sim, gostaria de utilizar o Trello
                    </button>
                </div>
                <div class="col-xs-12 margin-top-10">
                    <div class="trelloBeedBackArea">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="selectBoardModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Selecionar Quadro</h4>
                </div>
                <div class="modal-body">
                    <div class="row">            
                        <div class="col-xs-12">
                            <p>
                                Por favor, selecione qual Quadro do Trello deseja vincular a teste projeto.<br/>
                                Caso queira criar um Quadro novo selecione a opção 'Criar novo Quadro'.
                            </p>
                        </div>
                    </div>
                    <div class="row">            
                        <div class="col-xs-12 control-group">
                            <label for="availableBoards" class="control-label">Meus Quadros</label>
                            <select id="availableBoards" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-xs-12">
                            <div class="boardSelectionFeedBackArea">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-raised" id="btnConfirmBoardSelection" data-route="{{route('admin.project.management.keys',['id' => $project->id])}}">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection