@extends('layouts.admin')
@inject('enumUser','App\Models\Enums\EnumUser')
@inject('enumLikert','App\Models\Enums\EnumLikert')
@section('content')
	<div class="project-container">
		<form action="{{route('site.validation.save',['validationId' => $projectValidation->id])}}" method="POST">
		{{ csrf_field() }}
		<div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
            @include('partials.view-errors')
			<div class="row margin-top-10">
	        	<div class="col-xs-12 text-left">
	        		<h2 class="form-section-title">Informaçoes gerais</h2>
	        	</div>
    		</div>
    		<div class="row margin-top-10">
    			<div class="col-xs-12 col-md-6">
    				<div class="form-group {{$errors->has('name') ? "has-error": ""}}">
    					<label for="name" class="control-label">Nome*</label>
    					@if($user)
    					<input id="name" type="text" name="name" class="form-control" value="{{Util::coalesce(old('name'),$user->name)}}"/>
    					@else
    					<input id="name" type="text" name="name" class="form-control" value="{{old('name')}}"/>
    					@endif	
    				</div>
    			</div>
    			<div class="col-xs-12 col-md-6">
    				<div class="form-group {{$errors->has('occupation') ? "has-error": ""}}" >
    					<label for="occupation" class="control-label">Ocupação*</label>
    					@if($user && $word = $user->getCurrentOrLastWork())
    					<input id="occupation" type="text" name="occupation" class="form-control" value="{{Util::coalesce(old('occupation'),$word->title)}}" />
    					@else
    					<input id="occupation" type="text" name="occupation" class="form-control" value="{{old('occupation')}}" />
    					@endif
    				</div>
    			</div>
    		</div>
    		<div class="row margin-top-10">
    			<div class="col-xs-12 col-md-4">
    				<div class="form-group {{$errors->has('gender') ? "has-error": ""}}">
    					<label for="gender" class="control-label">Sexo*</label>
    					<select name="gender" id="gender" class="form-control">
    						<option value="">Selecione</option>
                        @foreach($enumUser::getGenderLabels() as $value => $label)
    						@if($user)
    						<option value="{{$value}}" {{$value == Util::coalesce(old('gender'),$user->gender) ? "selected" : ""}}>{{$label}}</option>
    						@else
    						<option value="{{$value}}" {{$value == old('gender') ? "selected" : ""}}>{{$label}}</option>
    						@endif
    					@endforeach
    					</select>
    				</div>
    			</div>
    			<div class="col-xs-12 col-md-4">
    				<div class="form-group {{$errors->has('age') ? "has-error": ""}}">
    					<label for="age" class="control-label">Idade*</label>
    					@if($user)
    					<input id="age" type="number" name="age" min="14" class="form-control" value="{{Util::coalesce(old('age'),DateUtil::calcAge($user->birth_date))}}"/>
    					@else
    					<input id="age" type="number" name="age" min="14" class="form-control" value="{{old('age')}}"/>
    					@endif
    				</div>
    			</div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group {{$errors->has('email') ? "has-error": ""}}">
                        <label for="age" class="control-label">E-mail</label>
                        @if($user)
                        <input id="email" type="text" name="email" min="14" class="form-control" value="{{Util::coalesce(old('email'),$user->email)}}"/>
                        @else
                        <input id="email" type="text" name="email" min="14" class="form-control" value="{{old('email')}}"/>
                        @endif
                    </div>
                </div>
    		</div>
	    	<div class="row margin-top-20">
	        	<div class="col-xs-12 text-left margin-top-20">
	        		<h2 class="form-section-title">{{$projectValidation->title}}</h2>
	        	</div>
	    	</div>
	    	<div class="row margin-top-10">
	    		@foreach($projectValidation->questions as $question)					
	    			<div class="col-xs-12">
	    				<div class="form-group {{($errors->has('question') || ($errors->has('empty_question') && empty(old('question.'.$question->id))) ) ? "has-error": ""}}">
	    					<label class="control-label questionLabel">{{$question->title}}*</label>
	    					@foreach($enumLikert::getLabels() as $value => $label)
	    					<div class="radio">
	    					<label>
	    						<input type="radio" name="question[{{$question->id}}]" {{ old('question.'.$question->id) == $value ? "checked" : "" }} value="{{$value}}">{{$label}}
	    					</label>
	    					</div>
	    					@endforeach
    					</div>
	    			</div>
	    		@endforeach
	    	</div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="form-group {{ $errors->has('recommend') ? "has-error": ""}}">
                        <label class="control-label questionLabel">Você utilizaria e/ou recomendária o sistema para outra pessoa?*</label>
                        <div class="radio">
                        <label>
                            <input type="radio" name="recommend" {{ old('recommend') == 1 ? "checked" : "" }} value="1">Sim
                        </label>
                        <label>
                            <input type="radio" name="recommend" {{ old('recommend') === "0" ? "checked" : "" }} value="0">Não
                        </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="suggestion" class="control-label questionLabel">Deixe suas sugestões e comentários.</label>
                        <textarea id="suggestion" class="form-control" name="suggestion" placeholder="Deixe qualquer mensagem para os integrantes do projeto">{{old('suggestion')}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12 margin-right">
                    <button type="submit" class="btn btn-primary btn-raised">
                        <i class="material-icons">check</i> Enviar
                    </button>
                    <a class="btn btn-default" href="{{$project->url}}"> 
                        Voltar para o projeto 
                    </a>
                </div>
            </div>
		</div>
		</form>
    </div>
@endsection