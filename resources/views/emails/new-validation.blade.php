@extends('emails.layout')
@section('content')
<br />
Olá <strong>{{$view_data['user_name']}}</strong>
<br />
<br />
O projeto <strong>{{$view_data['project_name']}}</strong> acaba de ser atualizado e uma nova validação está disponível. 
<br />
Para realizar a validação e ajudar os criadores do projeto clique <a href="{{$view_data['validation_url']}}"><b>aqui</b></a>
<br /> Ou clique <a href="{{$view_data['project_url']}}"><b>aqui</b></a> para acessar o projeto.
@endsection
