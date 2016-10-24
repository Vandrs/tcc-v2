<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Answer;

class Validation extends Model
{
	protected $fillable = ['name', 'email', 'project_validation_id', 'occupation', 'gender', 'age', 'suggestion'];

	public function projectValdation()
	{
		return $this->belongsTo(ProjectValidation::class);
	}

	public function answers()
	{
		return $this->belongsTo(Answer::class);
	}
}