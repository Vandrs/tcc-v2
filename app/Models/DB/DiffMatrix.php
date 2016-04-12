<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class DiffMatrix extends Model{
	protected $table = "diff_matrix";
	protected $fillable = ['project_a','project_b','diff'];
}