<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Elastic\ElasticSearch;

class TesteController extends Controller
{
    public function index(){
		$elasticSearch = new ElasticSearch;   	
		$elasticSearch->mapAllTypes();
    }
}
