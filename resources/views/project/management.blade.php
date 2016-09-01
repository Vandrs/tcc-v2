@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.management',$project)!!}
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 ">
            @include('partials.view-errors')
            <div class="row">
                <div class="col-xs-12 margin-top-10">
                    <div class="trelloBeedBackArea">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h2 class='form-section-title'>{{$project->title}}</h2>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-xs-12">
                    <div class="management-container">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection