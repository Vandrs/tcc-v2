@extends('emails.layout')
@section('content')
Olá {{$view_data['user_name']}}
<br />
Este é um email de teste.
@endsection