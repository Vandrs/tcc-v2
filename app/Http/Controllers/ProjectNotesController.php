<?php

namespace App\Http\Controllers;

use App\Models\Business\ProjectNoteBusiness;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Config;
use Log;
use App\Models\DB\ProjectNote;
use App\Models\DB\Project;
use App\Utils\Utils;


class ProjectNotesController extends Controller
{
    public function getUserActualNote($projectId){
        try{
            if(!Auth::check()){
                return $this->ajaxNotAllowed('Você deve estar logado para realizar avaliações.');
            }
            $projectNote = ProjectNote::where('project_id','=',$projectId)
                                      ->where('user_id','=',Auth::user()->id)
                                      ->firstOrFail();
            $actualNote = $projectNote->note;
        } catch(ModelNotFoundException $e){
            $actualNote = 0;
        } catch(\Exception $e){
            return $this->ajaxUnexpectedError();
        }
        return json_encode([
            "status" => 1,
            "note" => [
                "min" => Config::get('starrating.min'),
                "max" => Config::get('starrating.max'),
                "actual" => $actualNote
            ]
        ]);
    }

    public function rateProject(Request $request, $projectId){
        try{
            if(!Auth::check()){
                return $this->ajaxNotAllowed('Você deve estar logado para realizar avaliações.');
            }
            $note = $request->get('note',0);
            $project = Project::findOrFail($projectId);
            $projectNoteBusiness = new ProjectNoteBusiness();
            if($projectNote = $projectNoteBusiness->rateProject($project, Auth::user(), $note)){
                return json_encode([
                    'status'   => 1,
                    'note'     => $projectNote->note,
                    'avg_note' => $project->getAvgNote(),
                    'msg' => 'Avaliação realizada com sucesso!',
                    'class_msg' => 'alert-success'
                ]);
            } else {
                throw new \Exception("Erro ao avaliar projeto");
            }
        } catch(\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }
}
