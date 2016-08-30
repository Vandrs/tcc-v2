@extends('layouts.admin')
@section('breadcrumbs')
    {!!Breadcrumbs::render('admin.project.management',$project)!!}
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 box">
            @include('partials.view-errors')
            <div class="row">
                <div class="col-xs-12">
                    <h2 class='form-section-title'>Primeiro Acesso</h2>
                </div>
            </div>
        </div>
    </div>
@endsection