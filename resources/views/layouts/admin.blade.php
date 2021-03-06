@extends('layouts.master')
@section('body')
@inject('loader','App\Asset\AssetLoader')
{{$loader->addAssetBundle('AdminLayout')}}
<div id="wrapper">

    <!-- Sidebar -->
    @include('partials.admin-menu')
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid well main-container">
            <div class="row">
                <div class="col-xs-12">
                @yield('breadcrumbs')
                </div>
            </div>
            <div class="row {{isset($titleRowClass) ? $titleRowClass : ''}}">
                @if(isset($page_title))
                <div class="col-xs-12">
                    <h1>{{$page_title}}</h1>
                </div>
                @endif
            </div>
            @yield('content')
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-center">
                C3 - Projetos, todos os direitos reservados | <a href="{{route('site.termos')}}">Termos de Uso e Politica de Privacidade</a>
            </div>
        </div>
    </div>
</div>
<!-- /#wrapper -->
@include('partials.confirm-modal')
@endsection