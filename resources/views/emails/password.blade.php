@extends('emails.layout')
@section('content')
Clique aqui para recuperar a sua senha: {{ url('password/reset/'.$token) }}
@endsection