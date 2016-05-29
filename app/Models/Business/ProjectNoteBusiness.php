<?php

namespace App\Models\Business;
use App\Models\DB\ProjectNote;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\Business\CrudProjectBusiness;


class ProjectNoteBusiness
{
    public function rateProject(Project $project, User $user, $note){
        $projectNote = ProjectNote::where('project_id', '=', $project->id)
                                  ->where('user_id', '=', $user->id)
                                  ->first();
        if($projectNote){
            $projectNote->update(['note' => $note]);
        } else {
            $projectNote = ProjectNote::create([
                'project_id' => $project->id,
                'user_id'    => $user->id,
                'note'       => $note
            ]);
        }
        CrudProjectBusiness::dispathElasticJob($project);
        return $projectNote;
    }
}