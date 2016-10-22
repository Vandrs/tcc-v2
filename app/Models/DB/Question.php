<?php 

namespace App\Models\DB;
		  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DB\ProjectValidation;

class Question extends Model
{
	use SoftDeletes;

	protected $fillable = ['project_validation_id', 'title'];

	public function projectValidation()
	{
		return $this->belongsTo(ProjectValidation::class);
	}
}