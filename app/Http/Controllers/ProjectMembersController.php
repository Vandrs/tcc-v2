<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ProjectMembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('projectManager')->except(['acceptInvitation','denyInvitation']);
    }

    public function index($id)
    {
        
    }

    public function acceptInvitation($id)
    {

    }

    public function denyInvitation($id)
    {

    }


}
