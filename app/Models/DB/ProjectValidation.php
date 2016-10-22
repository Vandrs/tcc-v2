<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DB\Project;
use App\Models\DB\Question;
use App\Utils\StringUtil;

class ProjectValidation extends Model
{

	use SoftDeletes;

	protected $fillable = ["project_id","title","started_at","ended_at","notified"];
	protected $dates = ["started_at","ended_at","created_at","updated_at", "deleted_at"];

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function questions()
	{
		return $this->hasMany(Question::class);
	}

	public function getUrlAttribute()
	{
		$projectPath = StringUtil::toUrl($this->project->title."-".$this->project->id);
		$validationPath = StringUtil::toUrl($this->title."-".$this->id);
		return route('site.project.validation',['path' => $projectPath, 'validation_path' => $validationPath]);
	}
}