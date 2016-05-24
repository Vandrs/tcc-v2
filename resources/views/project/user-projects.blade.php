@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.user.projects')!!}
@endsection
@section('content')
    @include('partials.view-errors')

    <div class="row margin-top-20">
    </div>
    @if($projects->count())
        <div class="row margin-bottom-20">
            <div class="col-xs-12 col-md-8">
                <form action="{{route('admin.user.projects')}}" method="GET" class="row">
                    <div class="col-xs-4">
                        <select name='category_id' class="form-control" placeholder="Selecionar categoria">
                            <option value="">Selecionar categoria</option>
                            @foreach($categories as $category)
                                <option value={{$category->id}} {{$category->id == $selectedCategoryId?"selected":""}}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group col-xs-8">
                        <input name="q" type="text" value="{{$searchTerm}}" class="form-control" placeholder="Buscar por tema, assunto ou categoria">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Buscar <span class="glyphicon glyphicon-search"></span></button>
                      </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="row margin-top-10">
            @foreach($projects as $idx => $project)
                @if($idx > 0 && ($idx % 4 == 0) )
        </div>
        <div class="row margin-top-10">
            @endif
            <div class="col-xs-12 col-sm-6 col-md-3 margin-top-10">
                @include('partials.project-card',['project' => $project, 'showControls' => true])
            </div>
            @endforeach
        </div>
        <div class="row margin-top-20">
            <div class="col-xs-12 text-center margin-top-30">
                {!! $projects->render() !!}
            </div>
        </div>
    @else
        <div class="row margin-top-20">
            <div class="col-xs-12">
               <h4>Nenhum projeto encontrado. Clique <a href="{{route('admin.project.create')}}" class="btn btn-primary">aqui</a> para iniciar um projeto agora mesmo.</h4>
            </div>
        </div>
    @endif

@endsection

