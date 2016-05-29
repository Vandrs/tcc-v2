@extends('layouts.master')
@section('body')
<div id="wrapper">
    <div id="sidebar-wrapper">
        @include('partials.admin-menu')
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
        	<div class="row">
        		@yield('breadcrumbs')
        	</div>
        	<div class="row">
                @if(isset($page_title))
        		<div class="col-xs-12">
        			<h1>{{$page_title}}</h1>
        		</div>
                @endif
        	</div>
            @yield('content')
        </div>
    </div>
</div>
@include('partials.confirm-modal')
@endsection