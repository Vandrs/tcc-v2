@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.update',$project)!!}
@endsection
@section('content')
    {!!Form::model($project, ['route' => ['admin.project.update',$project->id], 'method' => 'post', 'files' => true])!!}
    <div class="row">
        <div class="col-xs-12 ">
            @include('partials.view-errors')
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Informações gerais</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 control-group {{$errors->has('title')?'has-error':''}}">
                    {!!Form::text('title', null,['class' => 'form-control', 'placeholder' => 'Título do projeto', 'maxlenth' => '50'])!!}
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 control-group {{$errors->has('description')?'has-error':''}}">
                    {!!Form::label('description','Descrição*',['class' => 'control-label'])!!}
                    {!!Form::textarea('description', null,['class' => 'form-control', 'placeholder' => 'Descrição resumida do projeto. Informe as principais características do projeto e quais os objetivos se deseja alcançar com a realização do mesmo.', 'row' => '10'])!!}
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 control-group {{$errors->has('description')?'has-error':''}}">
                    {!!Form::select('category_id', $categories, null,['class' => 'form-control', 'placeholder' => 'Categoria a qual o projeto pertence'])!!}
                </div>
            </div>
            <div class="row margin-top-20">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Galeria de fotos</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="form-group is-fileinput" id="uploadImage" data-route="{{route('image.create',['projectId' => $project->id])}}"">
                        {!!Form::file('image',['multiple'])!!}
                        <div class="input-group">
                          <input type="text" readonly="" class="form-control" placeholder="Selecionar imagens">
                            <span class="input-group-btn input-group-sm">
                              <button type="button" class="btn btn-fab btn-fab-mini">
                                <i class="material-icons">panorama</i>
                              </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-20">
                    <span class="small">Arquivos permitidos: (jpeg\jpg, png, gif) máximo 5MB por arquivo e um total de 50MB;</span>
                </div>
                <div class="col-xs-12 margin-top-10 photoFeedBack">

                </div>
                <div class="col-xs-12 margin-top-10">
                    <div class="row photos-container" data-exclude-route="{{route('image.delete',['projectId' => $project->id])}}" data-update-route="{{route('image.update',['projectId' => $project->id])}}">
                        @foreach($project->images as $image)
                            <div class='col-xs-12 col-sm-6 col-md-4 margin-top-20 photo-container'>
                                <img src='{{$image->getImageUrl()}}' class='img-responsive'/>
                                <input type='text' maxlength='50' value='{{$image->title}}' placeholder='Título da imagem' class='form-control photo-name margin-top-10' data-id='{{$image->id}}'/>
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" {{$image->cover?"checked":""}} name="cover" class="photo-cover" data-id='{{$image->id}}' /> Foto de capa
                                        <span class="circle"></span>
                                        <span class="check"></span>
                                    </label>
                                </div>
                                <button class='remove-photo btn btn-danger btn-raised full-size margin-top-10' data-id='{{$image->id}}'>
                                    Excluir <span class='glyphicon glyphicon-trash'></span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row margin-top-20">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Informações extras</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="project-urls">
                    @if($urls = Util::coalesce(old('urls',[]),$project->urls))
                        @foreach($urls as $idx => $value)
                        <div class="project-url">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">http(s)://</span>
                                        <input type="text" value="{{$value}}" class="form-control" name="urls[]" id="url_{{$idx}}}" placeholder="Adicionar url">
                                        <span class="input-group-btn">
                                            <button type="button" class="add-url btn btn-success btn-fab btn-fab-mini">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </span>
                                    @if($idx)
                                        <span class="input-group-btn">
                                            <button type='button' class='remove-url btn btn-danger btn-fab btn-fab-mini'>
                                                <i class='material-icons'>delete</i>
                                            </button>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="project-url">
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">http(s)://</span>
                                        <input type="text" class="form-control" name="urls[]" id="url_0" placeholder="Adicionar url">
                                        <span class="input-group-btn">
                                            <button type="button" class="add-url btn btn-success btn-fab btn-fab-mini">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="form-group is-fileinput" data-route="{{route('file.create',['project-id' => $project->id])}}" id="uploadFile">
                        {!!Form::file('file',['multiple'])!!}
                        <div class="input-group">
                          <input type="text" readonly="" class="form-control" placeholder="Adicionar arquivo">
                            <span class="input-group-btn input-group-sm">
                              <button type="button" class="btn btn-fab btn-fab-mini">
                                <i class="material-icons">attachment</i>
                              </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-20">
                    <span class="small">Arquivos permitidos: (pdf, doc, ppt) máximo 10MB;</span>
                </div>
                <div class="col-xs-12 margin-top-10 fileFeedBack">

                </div>
                <div class="files-container" data-exclude-route="{{route('file.delete',['projectId' => $project->id])}}">
                    @foreach($project->files as $file)
                        <div class="file-container" data-id="{{$file->id}}">
                            <div class="col-xs-12 margin-top-10">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" value="{{$file->title}}" class="form-control" readonly/>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger btn-fab btn-fab-mini remove-file">
                                                <span class="material-icons">delete</i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary btn-raised full-size-on-small">
                        <span class="glyphicon glyphicon-floppy-save"></span> Salvar
                    </button>
                    <a href="{{route('admin.project.delete',['id' => $project->id])}}" class="btn btn-danger btn-raised delete-project full-size-on-small">
                        <i class="glyphicon glyphicon-trash"></i> Excluir
                    </a>
                    <a href="{{route('admin.user.projects')}}" class="btn btn-default btn-raised full-size-on-small">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}
@endsection