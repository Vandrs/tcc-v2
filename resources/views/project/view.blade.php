@extends('layouts.master')
@section('body')
	<div class="container project-container">
        <div class="row margin-top-20">
            <div class="col-xs-12 text-center">
                <h1>{{$project->title}}</h1>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-xs-12 box">
                <div class="row">
                    <div class="col-xs-12 col-md-8 margin-top-10">
                        @if($project->images->count())
                        <div id="gallery" class="full-size">
                            @foreach($project->images->sortByDesc(function($image){return $image->cover;})->values() as $idx => $image)
                                <a href="{{$image->getImageUrl()}}" title="{{$image->title}}" {{$idx ? "class=hidden" : "" }} >
                                    <img src="{{$image->getImageUrl()}}" class="img-center img-responsive"/>
                                </a>
                            @endforeach
                        </div>
                        @endif
                        <h2>Sobre o Projeto</h2>
                        <div class="project-description project-box">
                            {{$project->description}}
                        </div>
                        @if( count($project->urls) || $project->files->count())
                        <div class="project-extra">
                            <h2>Sobre mais sobre o projeto</h2>
                            <ul class="simple-list project-extra-items">
                                @foreach($project->urls as $url)
                                    <li><a href="{{UrlUtil::makeExternal($url)}}" target="_blank"><i class="glyphicon glyphicon-link"></i> {{$url}}</a></li>
                                @endforeach
                                @foreach($project->files as $file)
                                    <li><a href="{{route('file.get',['id' => $file->file])}}"><i class="glyphicon glyphicon-file"></i> {{$file->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="col-xs-12 col-md-4 margin-top-30">
                        <div class="row">
                            <div class="col-xs-12 project-section-label">
                                Categoria
                            </div>
                            <div class="col-xs-12 project-section-title">
                                <h2><a href="{{route('search',['category_id' => $project->category->id])}}">{{$project->category->name}}</a></h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 project-section-label">
                                Nota Geral
                            </div>
                            <div class="col-xs-12">
                                @if($project->avg_note)
                                    <input readonly class="project-note-value" type="number" value="{{$project->avg_note}}" min="{{config('starrating.min')}}" max="{{config('starrating.max')}}"/>
                                @else
                                    <div class="margin-top-10">Seja o primeiro a avaliar</div>
                                @endif
                            </div>
                            <div class="col-xs-12 margin-top-10 margin-bottom-10">
                                @if(Auth::check())
                                <button type="button" class="btn btn-default" id="rate-project"
                                        data-route="{{route('user.note.project',['projectId' => $project->id])}}"
                                        data-rate-route="{{route('project.rate',['projectId' => $project->id])}}">
                                    <span class="glyphicon glyphicon-star"></span> Avaliar
                                </button>
                                @else
                                <button data-toggle="tooltip" type="button" class="btn btn-default disabled" title="Você deve estar logado para realizar avaliações">
                                    <span class="glyphicon glyphicon-star"></span> Avaliar
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="project-rate-feedBack">
                                </div>
                            </div>
                            <div class="col-xs-12 rate-project-area hidden">

                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-xs-12 project-section-label">
                                Membros
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                <ul class="simple-list project-members">
                                    @foreach($project->members as $member)
                                        <li><a href="{{route('user.view',['id' => $member->id])}}">{{$member->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection