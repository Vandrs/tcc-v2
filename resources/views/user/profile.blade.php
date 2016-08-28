@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.user.profile', Auth::user())!!}
@endsection
@section('content')
    @include('partials.view-errors')
    <div class="row margin-top-10">
        <div class="col-xs-12">
            <h2>{{$user->name}}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="2">Projetos avaliados</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->notes as $projectNote)
                    <tr>
                        <td>
                            <a href="{{$projectNote->project->url}}">{{$projectNote->project->title}}</a>
                        </td>
                        <td>&nbsp;{{$projectNote->note}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-xs-12 col-sm-6">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="2">Predições</th>
                </tr>
                </thead>
                <tbody>
                @foreach($predictions as $project)
                    <tr>
                        <td>
                            <a href="{{$project->url}}">{{$project->title}}</a>
                        </td>
                        <td>&nbsp;{{$project->preference}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-xs-12 col-sm-6 margin-top-10">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Projetos em que atua</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->projectsAsOwner() as $project)
                    <tr>
                        <td>
                            <a href="{{$project->url}}">{{$project->title}}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
