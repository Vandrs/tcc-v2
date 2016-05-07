@extends('layouts.master')
@section('body')
<div id="wrapper">
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                Menu
            </li>
            <li>
                <a href="#">Projetos</a>
            </li>
            <li>
                <a href="#">Usuários</a>
            </li>
            <li>
                <a href="#">Mensagens</a>
            </li>
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
</div>
@endsection