@extends('layouts.master')
@section('body')
    <div class="container">
        <div class="row margin-top-10">
        <div class="col-xs-12">
            <ul>
            @foreach($users as $user)
            <li>
                <a href="{{route('user.view',['id' => $user->id])}}" title="Ver perfil">{{$user->name}}</a> | 
                <a href="{{route('test.login',['id' => $user->id])}}" title="Logar como">Logar</a>
            </li>
            @endforeach
            </ul>
        </div>
        </div>
    </div>
@endsection