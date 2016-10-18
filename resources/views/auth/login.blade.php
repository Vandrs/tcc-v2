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
        <div class="container-fluid">
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
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-md-offset-1">
                        <div class="panel panel-login">
                            <div class="panel-heading">Login</div>
                            <div class="panel-body">
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-Mail</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Senha</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password">

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember"> Lembrar
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary btn-raised">
                                                <i class="material-icons">account_box</i> Entrar
                                            </button>
                                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Esqueceu a senha?</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->
@include('partials.confirm-modal')
@endsection