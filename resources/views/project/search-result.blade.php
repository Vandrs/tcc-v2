@extends('layouts.master')
@section('body')
    <div class="container">
        <div class="row margin-top-30">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <form action="{{route('search')}}" method="GET" class="row">
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

                @if($idx > 0 && ($idx % 3 == 0))
                </div>
                <div class="row margin-top-10">
                @endif

                <div class="col-xs-12 col-sm-6 col-md-4 margin-top-10">
                @include('partials.project-card',['project' => $project])
                </div>
            @endforeach
        </div>
        <div class="row margin-top-10">
            <div class="col-xs-12 text-center">
                {!! $projects->links() !!}
            </div>
        </div>
    </div>
@endsection