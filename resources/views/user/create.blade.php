@extends('layouts.admin')
@inject('enumUser','App\Models\Enums\EnumUser')
@section('content')
    <form action="#" id="photoForm" method="post" enctype="multipart/form-data">
    </form>
    <div class="row margin-top-10">
        <div class="col-xs-12 text-left">
                <h1>Atualizar Perfil</h1>
        </div>
    </div>
    @include('partials.view-errors')
    <form id="frmUser" method="POST" action="{{route('user.save')}}">
        {{csrf_field()}}
    <div class="row">
        <div class="col-xs-12">
            <h2 class='form-section-title'>Informaçoes gerais</h2>
        </div>
    </div>
    <div class="row"> 
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group {{$errors->has('name')?'has-error':''}}">
                        <label class="control-label" for="name">Nome*</label>
                        <input type="text" id="name" name='User[name]' class="form-control" value="{{isset($user['name']) ? $user['name'] : null}}"/>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group {{$errors->has('email')?'has-error':''}}">
                        <label class="control-label" for="email">Email*</label>
                        <input type="text" id="email" name='User[email]' class="form-control" value="{{isset($user['email']) ? $user['email'] : null}}"/>
                    </div>
                </div>        
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label" for="email">Foto</label>
                        <div class="profile-image-area">
                        @if(isset($user['photo']) && $user['photo'])
                        <img id='photoProfile' src="{{$user['photo']}}"/>
                        @endif
                        </div>
                        <div class="userPhotoControls margin-top-10">
                            <button type="button" class="btn btn-fab btn-fab-mini addPhoto margin-right-5" data-toggle="tooltip" title="Adicionar Foto">
                                <i class="material-icons">panorama</i>
                            </button>
                            <button type="button" class="btn btn-fab btn-danger btn-fab-mini removePhoto" data-toggle="tooltip" title="Remover Foto">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                        <input type="file" class="form-control hidden" placeholder="Selecionar imagens" id="imageUpload" data-upload-route="{{route('image.upload')}}"/>
                        <input type="hidden" name="User[photo]" value="{{isset($user['photo']) ? $user['photo'] : null}}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($user['social_id']) && $user['social_id'])
        <input type="hidden" name="User[social_id]" value="{{$user['social_id']}}"/>
        <input type="hidden" name="User[social_driver]" value="{{$user['social_driver']}}"/>
    @else
    <div class="row"> 
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('password')?'has-error':''}}">
                <label class="control-label" for="password">Senha*</label>
                <input type="password" id="password" name='User[password]' class="form-control" value="{{isset($user['password']) ? $user['password'] : null}}"/>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('password')?'has-error':''}}">
                <label class="control-label" for="password_confirmation">Confirmar Senha*</label>
                <input type="password" id="password_confirmation" name='User[password_confirmation]' class="form-control" value="{{isset($user['password_confirmation']) ? $user['password_confirmation'] : null}}"/>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('gender')?'has-error':''}}">
                <label class="control-label" for="gender">Sexo*</label>
                <select class="form-control" id="gender" name="User[gender]">
                    <option>Selecione</option>
                    @foreach($enumUser::getGenderLabels() as $value => $label)
                        <option value="{{$value}}" @if(isset($user['gender']) && $user['gender'] == $value) {{"selected"}} @endif>{{$label}}</option>
                    @endforeach
                </select>                
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('birth_date')?'has-error':''}}">
                <label class="control-label" for="birth_date">Data de nascimento*</label>
                <div class="input-group">
                <input type="text" id="birth_date" name='User[birth_date]' class="form-control date " value="{{isset($user['birth_date']) ? $user['birth_date'] : null}}"/>
                <span class='input-group-btn'>
                    <button class="btn btn-fab-mini btn-fab showDatePicker"><i class="material-icons">event</i></button>
                </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group {{$errors->has('skills')?'has-error':''}}">
                <label for="skills" class="control-label">Habilidades (valores separados por ,)</label>
                <textarea id="skills" class="form-control" name="User[skills]">{{isset($user['skills']) ? $user['skills'] : "" }}</textarea>
            </div>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-xs-12">
            <h2 class='form-section-title'>Informaçoes Profissionais <button class="addWork btn btn-info btn-fab btn-fab-mini btn-raised"><i class="material-icons">add</i></button></h2>
        </div>
        <div class="col-xs-12 works-container">
            @if(isset($user['works']) && $user['works'])
                @foreach($user['works'] as $key => $work)
                    <div class="row work box" data-idx="{{$key}}">
                        <div class="col-xs-12 text-right margin-top-10">
                            <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-work margin-right-5" title="Remover"><i class="material-icons">delete</i></button>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_title_{{$key}}" class="control-label">Cargo*</label>
                                <input class="form-control" type="text" name="User[works][{{$key}}][title]" id="work_title_{{$key}}" value="{{ isset($work['title']) ? $work['title'] : "" }}"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_company_{{$key}}" class="control-label">Empresa*</label>
                                <input class="form-control" type="text" name="User[works][{{$key}}][company]" id="work_company_{{$key}}" value="{{isset($work['company']) ? $work['company'] : "" }}"/>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="work_description_{{$key}}" class="control-label">Principais atividades*</label>
                                <textarea id="work_description_{{$key}}" class="form-control" name="User[works][{{$key}}][description]">{{isset($work['description']) ? $work['description'] : ""}}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_started_at_{{$key}}" class="control-label">Inicio*</label>
                                <div class="input-group">
                                    <input class="form-control date " type="text" name="User[works][{{$key}}][started_at]" id="work_started_at_{{$key}}" value="{{isset($work['started_at']) ? $work['started_at'] : "" }}"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-fab btn-fab-mini showDatePicker"><i class="material-icons">event</i></button>
                                    </span>                
                                </div>                
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_ended_at_{{$key}}" class="control-label">Fim* (Não preencher se for o emprego atual)</label>
                                <div class="input-group">
                                    <input class="form-control date " type="text" name="User[works][{{$key}}][ended_at]" id="work_ended_at_{{$key}}" value="{{isset($work['ended_at']) ? $work['ended_at'] : "" }}"/>        
                                    <span class="input-group-btn">
                                        <button class="btn btn-fab btn-fab-mini showDatePicker"><i class="material-icons">event</i></button>
                                    </span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-xs-12">
            <h2 class='form-section-title'>Informaçoes Acadêmicas <button class="addGraduation btn btn-info btn-fab btn-fab-mini btn-raised"><i class="material-icons">add</i></button></h2>
        </div>
        <div class="col-xs-12 graduations-container">
            @if(isset($user['graduations']) && $user['graduations'])
                @foreach($user['graduations'] as $key =>  $graduation)
                <div class="row graduation box" data-idx="{{$key}}">
                    <div class="col-xs-12 text-right margin-top-10">
                        <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-graduation btn-danger" title="Remover"><i class="material-icons">delete</i></button>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_course_{{$key}}">Curso*</label>
                            <input class="form-control" type="text" name="User[graduations][{{$key}}][course]" id="grad_course_{{$key}}" value="{{isset($graduation['course']) ? $graduation['course'] : ""}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_institution_{{$key}}">Instituição de Ensino*</label>
                            <input class="form-control" type="text" name="User[graduations][{{$key}}][institution]" id="grad_institution_{{$key}}" value="{{isset($graduation['institution']) ? $graduation['institution'] : ""}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_conclusion_at_{{$key}}">Data de Conclusão*</label>
                            <div class="input-group">
                                <input class="form-control date " type="text" name="User[graduations][{{$key}}][conclusion_at]" id="grad_conclusion_at_{{$key}}" value="{{isset($graduation['conclusion_at']) ? $graduation['conclusion_at'] : ""}}"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-fab btn-fab-mini showDatePicker"><i class="material-icons">event</i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row margin-top-10 text-right">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-raised">
                <i class='material-icons'>save</i> Cadastrar
            </button>
        </div>
    </div>
    </form>
@endsection