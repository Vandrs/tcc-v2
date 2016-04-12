<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\ProjectNote;

class Project extends Model{
	protected $fillable = ['title', 'description'];


	public function notes(){
		return $this->hasMany(ProjectNote::class);
	}
	
}