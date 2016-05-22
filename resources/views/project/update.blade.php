@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.update',$project)!!}
@endsection
@section('content')
    {!!Form::model($project, ['route' => ['admin.project.update',$project->id], 'method' => 'post', 'files' => true])!!}
    <div class="row">
        <div class="col-xs-12 box">
            @include('partials.view-errors')
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="form-section-title">Informações gerais</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 control-group {{$errors->has('title')?'has-error':''}}">
                    {!!Form::label('title','Título*',['class' => 'control-label'])!!}
                    {!!Form::text('title', null,['class' => 'form-control', 'placeholder' => 'Título do projeto', 'maxlenth' => '100'])!!}
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
                    {!!Form::label('category_id','Categoria*',['class' => 'control-label'])!!}
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
					<span class="input-file-conteiner">
						<button type="button" data-route="{{route('image.create',['projectId' => $project->id])}}" id="uploadImage" class="btn btn-primary full-size-on-small">
							Selecionar imagens <span class="glyphicon glyphicon-picture"></span>
						</button>
                        <input type="file" name="image" multiple />
					</span>
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
                                <div class="checkbox">
                                    <label>
                                        <input type="radio" {{$image->cover?"checked":""}} name="cover" class="photo-cover" data-id='{{$image->id}}' /> Foto de capa
                                    </label>
                                </div>
                                <button class='remove-photo btn btn-danger full-size margin-top-10' data-id='{{$image->id}}'>
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
                <div class="col-xs-12">
                    <label class="control-label" for="url_0">
                        Urls onde se pode saber mais sobre o projeto
                    </label>
                </div>
                <div class="project-urls">
                    @if($urls = Util::coalesce(old('urls',[]),$project->urls))
                        @foreach($urls as $idx => $value)
                        <div class="project-url">
                            <div class="col-xs-9 col-md-10 margin-top-10">
                                <div class="input-group">
                                    <span class="input-group-addon">http(s)://</span>
                                    <input type="text" value="{{$value}}" class="form-control" name="urls[]" id="url_{{$idx}}}">
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-2">
                                <button type="button" class="add-url btn btn-success margin-top-10 margin-right-5">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                                @if($idx)
                                    <button type='button' class='remove-url btn btn-danger margin-top-10'>
                                        <span class='glyphicon glyphicon-trash'></span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="project-url">
                            <div class="col-xs-12 col-sm-8 col-md-10 margin-top-10">
                                <div class="input-group">
                                    <span class="input-group-addon">http(s)://</span>
                                    <input type="text" class="form-control" name="urls[]" id="url_0">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <button type="button" class="add-url full-size-on-small btn btn-success margin-top-10">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <label class="control-label" for="file">
                        Anexos
                    </label>
                </div>
                <div class="col-xs-12">
					<span class="input-file-conteiner">
						<button type="button" data-route="{{route('file.create',['project-id' => $project->id])}}" id="uploadFile" class="btn btn-primary full-size-on-small">
							Selecionar Arquivos <span class="glyphicon glyphicon-file"></span>
						</button>
                        {!!Form::file('file',['multiple'])!!}
					</span>
                </div>
                <div class="col-xs-12 margin-top-20">
                    <span class="small">Arquivos permitidos: (pdf, doc, ppt) máximo 10MB;</span>
                </div>
                <div class="col-xs-12 margin-top-10 fileFeedBack">

                </div>
                <div class="files-container" data-exclude-route="{{route('file.delete',['projectId' => $project->id])}}">
                    @foreach($project->files as $file)
                        <div class="file-container" data-id="{{$file->id}}">
                            <div class="col-xs-10 col-md-11 margin-top-10">
                                <input type="text" value="{{$file->title}}" class="form-control" readonly/>
                            </div>
                            <div class="col-xs-2 col-md-1 margin-top-10">
                                <button type="button" class="btn btn-danger remove-file">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 text-right">
                    {!!Form::submit('Salvar',['class' => 'btn btn-primary full-size-on-small'])!!}
                    <a href="#" class="btn btn-default full-size-on-small">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}
@endsection