@extends('layouts.admin')

@inject('projectInviteRoles','App\Models\Enums\EnumProject')

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
                <div class="searchUsersBackArea">
                </div>
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <form id="searchUsers" action="{{route('users.search')}}">
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
                                    <label for="work" class="control-label">Profissão/Cargos</label>
                                    <input id="work" class="form-control" type="text" name="work" maxlength="100"/>
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
                        <button type="submit" class="btn btn-primary btn-raised" id="submitUserSearch"><i class="material-icons">search</i>Buscar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 users-search-result margin-top-10">
            </div>
            <div class="col-xs-12 users-search-pagination margin-top-10 text-center">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInvite" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Convidar Usuário</h4>
      </div>
      <div class="modal-body">
        <form id="formInvite" action="{{route('admin.project.invite',['id' => $project->id])}}" method="POST">
            <input type="hidden" name="project_id" value="{{$project->id}}"/>
            <input type="hidden" name="user_id" value=""/>
            {{csrf_field()}}
            <div class="row">
                <div class="col-xs-12">
                    <div class="inviteFeedbackArea">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="user-name form-section-title"></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group margin-top-10">
                        <label for="temp_role" class="control-label">Perfil</label>
                        <select id="temp_role" class="form-control" name="temp_role">
                            <option value="">Selecione</option>
                            @foreach($projectInviteRoles::getProjectInviteRoles() as $key => $role)
                                <option value="{{$key}}">{{$role}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-raised inviteUser">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection