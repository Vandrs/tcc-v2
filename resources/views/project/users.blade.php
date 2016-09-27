@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.users',$project)!!}
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
                <div class="projectUsersBackArea">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h2 class="form-section-title">Membros do Projeto</h2>
            </div>
            <div class="col-xs-12 margin-top-10">
                <table id="membersTable" class="table table-bordered table-striped" data-list-route="{{route('admin.project.members',['id' => $project->id])}}">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Perfil</th>
                            <th style="width:100px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 margin-top-10">
                <h2 class="form-section-title">Encontrar usuários</h2>
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <form id="searchUsers" action="#">
                    <div class="col-xs-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group label-floating">
                                    <label for="name" class="control-label">Nome</label>
                                    <input id="name" class="form-control" type="text" name="name" maxlength="100"/>
                                </div>
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group label-floating">
                                    <label for="graduation" class="control-label">Formação</label>
                                    <input id="graduation" class="form-control" type="text" name="graduation" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group label-floating">
                                    <label for="works" class="control-label">Profissão/Cargos</label>
                                    <input id="works" class="form-control" type="text" name="works" maxlength="100"/>
                                </div>
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group label-floating">
                                    <label for="skills" class="control-label">Habilidades (valores separados por ,)</label>
                                    <textarea id="skills" class="form-control" name="skills" rols="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 margin-top-10">
                        <button type="submit" class="btn btn-primary btn-raised"><i class="material-icons">search</i>Buscar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection