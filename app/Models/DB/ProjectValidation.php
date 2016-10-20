<?php

use Illuminate\Eloquent\Database\Model;
use App\Models\DB\Project;

class ProjectValidation extends Model
{
	protected $fillable = ["project_id","title","started_at","ended_at","notified"];
	protected $dates = ["started_at","ended_at","created_at","updated_at"];

	public function project()
	{
		return $this->belongsTo(Project::class);
	}
}