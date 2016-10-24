<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\Validation;
use App\Models\DB\Question;

class Answer extends Model
{
	protected $fillable =  ['validation_id', 'question_id', 'option'];

	public function validation()
	{
		return $this->belongsTo(Validation::class);
	}

	public function question()
	{
		return $this->belongsTo(Question::class);
	}

}