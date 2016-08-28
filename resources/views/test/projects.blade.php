@extends('layouts.master')
@section('body')
    <div class="container">
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <h2>Projetos</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table>
                    <tbody>
                        @foreach($projects as $project)       
                            <tr>
                                <td>
                                <a href="{{$project->url}}">{{$project->title}}</a>
                                </td>
                                <td>&nbsp; Média {{$project->getAvgNote()}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection