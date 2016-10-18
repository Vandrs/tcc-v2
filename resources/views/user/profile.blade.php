@extends('layouts.admin')
@inject('enumUser','App\Models\Enums\EnumUser')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.user.profile', Auth::user())!!}
@endsection
@section('content')
    @include('partials.view-errors')
    <div class="row">
        <div class="col-xs-12 profileFeedBack">
        </div>
    </div>
    <form id="frmUser" method="POST" action="{{route('admin.user.update.profile')}}" 
          data-delete-graduation-route="{{route('graduation.delete')}}"
          data-delete-work-route="{{route('work.delete')}}">
        {{csrf_field()}}
    <div class="row">
        <div class="col-xs-12">
            <h2 class='form-section-title'>Informaçoes gerais</h2>
        </div>
    </div>
    <div class="row"> 
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('name')?'has-error':''}}">
                <label class="control-label" for="name">Nome*</label>
                <input type="text" id="name" name='name' class="form-control" value="{{Util::coalesce(old('name'),$user->name)}}"/>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('email')?'has-error':''}}">
                <label class="control-label" for="email">Email*</label>
                <input type="text" id="email" name='email' class="form-control" value="{{Util::coalesce(old('email'),$user->email)}}"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('gender')?'has-error':''}}">
                <label class="control-label" for="gender">Sexo*</label>
                <select class="form-control" id="gender" name="gender">
                    <option>Selecione</option>
                    @foreach($enumUser::getGenderLabels() as $value => $label)
                        <option value="{{$value}}" @if(Util::coalesce(old('gender', $user->gender)) == $value) {{"selected"}} @endif>{{$label}}</option>
                    @endforeach
                </select>                
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group {{$errors->has('birth_date')?'has-error':''}}">
                <label class="control-label" for="birth_date">Data de nascimento*</label>
                <div class="input-group">
                <input type="text" id="birth_date" name='birth_date' class="form-control date " value="{{Util::coalesce(old('birth_date', $user->birth_date ? $user->birth_date->format('d/m/Y') : ""))}}"/>
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
                <textarea id="skills" class="form-control" name="skills">{{Util::coalesce(old('skills',$user->skills ? implode(', ',$user->skills) : "" ))}}</textarea>
            </div>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-xs-12">
            <h2 class='form-section-title'>Informaçoes Profissionais <button class="addWork btn btn-info btn-fab btn-fab-mini btn-raised"><i class="material-icons">add</i></button></h2>
        </div>
        <div class="col-xs-12 works-container">
            @if(old('works'))
                @foreach(old('works') as $key => $work)
                    <div class="row work box" data-idx="{{$key}}">
                        <div class="col-xs-12 text-right margin-top-10">
                            <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-work margin-right-5" title="Remover"><i class="material-icons">delete</i></button>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_title_{{$key}}" class="control-label">Cargo*</label>
                                <input class="form-control" type="text" name="works[{{$key}}][title]" id="work_title_{{$key}}" value="{{ isset($work['title']) ? $work['title'] : "" }}"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_company_{{$key}}" class="control-label">Empresa*</label>
                                <input class="form-control" type="text" name="works[{{$key}}][company]" id="work_company_{{$key}}" value="{{isset($work['company']) ? $work['company'] : "" }}"/>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="work_description_{{$key}}" class="control-label">Principais atividades*</label>
                                <textarea id="work_description_{{$key}}" class="form-control" name="works[{{$key}}][description]">{{isset($work['description']) ? $work['description'] : ""}}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_started_at_{{$key}}" class="control-label">Inicio*</label>
                                <div class="input-group">
                                    <input class="form-control date " type="text" name="works[{{$key}}][started_at]" id="work_started_at_{{$key}}" value="{{isset($work['started_at']) ? $work['started_at'] : "" }}"/>
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
                                    <input class="form-control date " type="text" name="works[{{$key}}][ended_at]" id="work_ended_at_{{$key}}" value="{{isset($work['ended_at']) ? $work['ended_at'] : "" }}"/>        
                                    <span class="input-group-btn">
                                        <button class="btn btn-fab btn-fab-mini showDatePicker"><i class="material-icons">event</i></button>
                                    </span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                @endforeach
            @else
                @foreach($user->works as $key => $work)
                    <div class="row work box" data-idx="{{$key}}" data-id="{{$work->id}}">
                        <input type="hidden" name="works[{{$key}}][id]" value="{{$work->id}}" />
                        <div class="col-xs-12 text-right margin-top-10">
                            <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-work margin-right-5" title="Remover"><i class="material-icons">delete</i></button>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_title_{{$key}}" class="control-label">Cargo*</label>
                                <input class="form-control" type="text" name="works[{{$key}}][title]" id="work_title_{{$key}}" value="{{$work->title}}"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_company_{{$key}}" class="control-label">Empresa*</label>
                                <input class="form-control" type="text" name="works[{{$key}}][company]" id="work_company_{{$key}}" value="{{$work->company}}"/>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="work_description_{{$key}}" class="control-label">Principais atividades*</label>
                                <textarea id="work_description_{{$key}}" class="form-control" name="works[{{$key}}][description]">{{$work->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="work_started_at_{{$key}}" class="control-label">Inicio*</label>
                                <div class="input-group">
                                    <input class="form-control date " type="text" name="works[{{$key}}][started_at]" id="work_started_at_{{$key}}" value="{{$work->started_at->format('d/m/Y')}}"/>
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
                                    <input class="form-control date " type="text" name="works[{{$key}}][ended_at]" id="work_ended_at_{{$key}}" value="{{$work->ended_at ? $work->ended_at->format('d/m/Y') : ""}}"/>        
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
            @if(old('graduations'))
                @foreach(old('graduations') as $key => $graduation)
                <div class="row graduation box" data-idx="{{$key}}">
                    <div class="col-xs-12 text-right margin-top-10">
                        <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-graduation btn-danger" title="Remover"><i class="material-icons">delete</i></button>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_course_{{$key}}">Curso*</label>
                            <input class="form-control" type="text" name="graduations[{{$key}}][course]" id="grad_course_{{$key}}" value="{{isset($graduation['course']) ? $graduation['course'] : ""}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_institution_{{$key}}">Instituição de Ensino*</label>
                            <input class="form-control" type="text" name="graduations[{{$key}}][institution]" id="grad_institution_{{$key}}" value="{{isset($graduation['institution']) ? $graduation['institution'] : ""}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_conclusion_at_{{$key}}">Data de Conclusão*</label>
                            <div class="input-group">
                                <input class="form-control date " type="text" name="graduations[{{$key}}][conclusion_at]" id="grad_conclusion_at_{{$key}}" value="{{isset($graduation['conclusion_at']) ? $graduation['conclusion_at'] : ""}}"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-fab btn-fab-mini showDatePicker"><i class="material-icons">event</i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                @foreach($user->graduations as $key => $graduation)
                <div class="row graduation box" data-idx="{{$key}}" data-id="{{$graduation->id}}">
                    <input type="hidden" name="graduations[{{$key}}][id]" value="{{$graduation->id}}"/>
                    <div class="col-xs-12 text-right margin-top-10">
                        <button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-graduation btn-danger" title="Remover"><i class="material-icons">delete</i></button>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_course_{{$key}}">Curso*</label>
                            <input class="form-control" type="text" name="graduations[{{$key}}][course]" id="grad_course_{{$key}}" value="{{$graduation->course}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_institution_{{$key}}">Instituição de Ensino*</label>
                            <input class="form-control" type="text" name="graduations[{{$key}}][institution]" id="grad_institution_{{$key}}" value="{{$graduation->institution}}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="grad_conclusion_at_{{$key}}">Data de Conclusão*</label>
                            <div class="input-group">
                                <input class="form-control date " type="text" name="graduations[{{$key}}][conclusion_at]" id="grad_conclusion_at_{{$key}}" value="{{$graduation->conclusion_at->format('d/m/Y')}}"/>
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
                <i class='material-icons'>save</i> Salvar
            </button>
        </div>
    </div>
    </form>
@endsection