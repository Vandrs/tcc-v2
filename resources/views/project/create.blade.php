@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('admin.project.create')!!}
@endsection
@section('content')
	{!!Form::open(['route' => 'admin.project.store', 'method' => 'post', 'files' => true])!!}
	{!!Form::token()!!}
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
				{!!Form::select('category_id', $categories,null,['class' => 'form-control', 'placeholder' => 'Categoria a qual o projeto pertence'])!!}
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
						<button type="button" data-route="{{route('image.temp-upload')}}" id="uploadImage" class="btn btn-primary full-size-on-small">
							Selecionar imagens <span class="glyphicon glyphicon-picture"></span>
						</button>
						{!!Form::file('image',['multiple'])!!}
						{!!Form::hidden('image_files','[]')!!}
					</span>
				</div>
				<div class="col-xs-12 margin-top-20">
					<span class="small">Arquivos permitidos: (jpeg\jpg, png, gif) máximo 5MB por arquivo e um total de 50MB;</span>
				</div>
				<div class="col-xs-12 margin-top-10 photoFeedBack">

				</div>
				<div class="col-xs-12 margin-top-10">
					<div class="row photos-container" data-exclude-route="{{route('image.temp-file.delete')}}">
					@if(!empty(old('image_files')))
					@foreach(json_decode(old('image_files'),true) as $tempImage)
						<div class='col-xs-12 col-sm-6 col-md-4 margin-top-20'>
							<img src='{{$tempImage["url"]}}' class='img-responsive'/>
							<input type='text' maxlength='50' value='{{$tempImage["name"]}}' placeholder='Título da imagem' class='form-control photo-name margin-top-10' data-id='{{$tempImage["id"]}}'/>
							<div class="checkbox">
    						<label>
      							<input type="radio" {{$tempImage["cover"]?"checked":""}} name="cover" class="photo-cover" data-id='{{$tempImage["id"]}}' /> Foto de capa 
    						</label>
  							</div>
							<button class='remove-photo btn btn-danger full-size margin-top-10' data-id='{{$tempImage["id"]}}'>
								Excluir <span class='glyphicon glyphicon-trash'></span>
							</button>
	    				</div>
	    			@endforeach
					@endif
					</div>
				</div>
			</div>
			<div class="row margin-top-20">
				<div class="col-xs-12">
					<h2 class="form-section-title">Informações extras</h2>
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