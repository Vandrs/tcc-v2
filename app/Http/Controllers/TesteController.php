<?php

namespace App\Http\Controllers;

class TesteController extends Controller
{
    public function index()
    {	
    	$data = ['view_data' => ['user_name' => 'Vanderson']];
    	return view('emails.teste',$data);
    }
}
