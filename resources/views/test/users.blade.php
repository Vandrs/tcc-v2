@extends('layouts.master')
@section('body')
    <div class="container">
        <div class="row margin-top-10">
        <div class="col-xs-12">
            <table class="table table-bordered table-stripped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Qtd. Avaliações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="{{route('user.view',['id' => $user->id])}}" title="Ver perfil">{{$user->name}}</a> 
                        </td>
                        <td>
                            {{$user->qtdEvaluatedProjects()}}
                        </td>
                        <td>
                            <a href="{{route('test.login',['id' => $user->id])}}" title="Logar como">Logar</a>
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
        </div>
    </div>
@endsection