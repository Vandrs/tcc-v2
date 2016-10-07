<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model{
	protected $fillable = ['user_id','project_id','role', 'temp_role','creator'];	
}