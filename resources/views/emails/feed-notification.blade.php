@extends('emails.layout')
@section('content')
Olá <strong>{{$view_data['user_name']}}</strong>
<br />
<br />
O projeto <strong>{{$view_data['project_name']}}</strong> acaba de ser atualizado.
<br />
Para saber mais sobre as novidades do projeto clique <a href="{{$view_data['project_url']}}"><strong>aqui</strong></a>
@endsection
