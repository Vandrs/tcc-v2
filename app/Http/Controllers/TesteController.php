<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\DB\ProjectNote;

use App\Http\Requests;

class TesteController extends Controller
{
    public function index(){
    	$note = ProjectNote::find(1);
    	dd(['Project' => $note->project->title, 'User' => $note->user->name, 'Note' => $note->note]);
    }
}
