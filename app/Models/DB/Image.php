<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Image extends Model{
	protected $fillable = ['project_id','title','file','cover'];
}