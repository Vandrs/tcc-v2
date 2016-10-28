@extends('layouts.admin')
@inject('projectFollower','App\Models\Business\ProjectFollowerBusiness')
@section('content')
	<div class="project-container">
        <div class="row margin-top-10">
            <div class="col-xs-12 ">
                @include('partials.view-errors')
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
                        <div class="gallery-button-container">
                            <button class="btn btn-primary btn-raised showGallery">
                                <i class="material-icons">image</i> Ver Imagens
                            </button>
                        </div>
                        @endif
                        <h2>Sobre o Projeto</h2>
                        <div class="project-description project-box">
                            {!!$project->description!!}
                        </div>
                        @if($project->getPosts()->count())
                        <div class="updates-area">
                            <h2>Últimas atualizações</h2>
                            @foreach($project->getPosts()->sortByDesc('created_at') as $post)
                            <div class="post-area project-box">
                                <h3>{{$post->title}}</h3>
                                <div class="post-text">
                                    {!!$post->text!!}
                                </div>
                                <div class="post-info-area text-right margin-top-10">
                                    Por: <strong>{{$post->createUser['name']}}</strong> em {{DateUtil::strDbDateToBrDate($post->created_at,false)}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
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
                        <div class="comments-area">
                            <h3>Comentários</h3>
                            <script>
                                DISQUS_PAGE_URL = '{{$disqus_page_url}}';
                                DISQUS_PAGE_IDENTIFIER = '{{$discus_page_id}}';
                            </script>
                            <div id="disqus_thread"></div>
                        </div>
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
                                Compartilhar
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                <div class="addthis_inline_share_toolbox"></div>
                            </div>
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-xs-12 project-section-label">
                                Validações
                            </div>
                            @if($project->currentValidations()->count())
                            <div class="col-xs-12 margin-top-10">
                                <ul class="simple-list project-validations">
                                    @foreach($project->currentValidations() as $validation)
                                        <li><a href="{{$validation->url}}">{{$validation->title}} <i class="material-icons">check</i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @else
                            <div class="col-xs-12 margin-top-10">
                                Nenhuma validação disponível no momento.
                            </div>
                            @endif
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-xs-12 project-section-label">
                                Membros
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                <ul class="simple-list project-members">
                                    @foreach($project->members as $member)
                                        <li><a href="#" class="viewModalProfile" data-id="{{$member->id}}">{{$member->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-xs-12 project-section-label">
                                Seguidores
                            </div>
                            <div class="col-xs-12 margin-top-10 followers-area">
                                @include('project.partials.followers',['followers' => $project->followers])
                            </div>
                            <div class="col-xs-12 margin-top-10">
                                @if(Auth::check())
                                    @if(!$project->isMember(Auth::user()))
                                        <button class="btn btn-default btn-follow {{ $projectFollower::isUserFollowingProject(Auth::user(), $project)  ? "following" : "" }}" 
                                                data-follow-route="{{route('site.project.follow',['id' => $project->id])}}"
                                                data-unfollow-route="{{route('site.project.unfollow',['id' => $project->id])}}">
                                            <span class="glyphicon glyphicon-heart"></span> Seguir
                                        </button>
                                    @endif
                                @else
                                <button data-toggle="tooltip" class="btn btn-default disabled" title="Você deve estar logado para poder seguir projetos">
                                    <span class="glyphicon glyphicon-heart"></span> Seguir
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-xs-12">
                                <div class="project-follow-feedBack">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection