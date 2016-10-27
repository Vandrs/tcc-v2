<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Utils;
use App\Models\DB\Project;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Validation;
use App\Models\Business\ValidationReportBusiness;
use App\Asset\AssetLoader;
use Log;
use DB;
use Yajra\Datatables\Datatables;

class ValidationReportsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('makeProjectValidation');
	}

	public function index(Request $request, $id, $validationId)
	{
		try {
			$project = Project::findOrFail($id);
			$projectValidation = ProjectValidation::where('project_id', '=', $id)
												 ->where('id', '=', $validationId)
												 ->firstOrFail(); 
			$data = [
				'project' 			=> $project,
				'projectValidation' => $projectValidation,
				'page_title' 		=> "Relatório: ".$projectValidation->title,
				'js_variables'      => [
					'REPORT_ROUTE'  => route('admin.project.validations.reports.get',['id' => $project->id, 'validationId' => $projectValidation->id]),
					'RECOMMEND_REPORT_ROUTE' => route('admin.project.validations.recommend-report.get',['id' => $project->id, 'validationId' => $projectValidation->id])
				]
			];
			AssetLoader::register(['validationReports.js'],['admin.css'],['DataTables','ChartJS']);
			return view('project.validation-reports', $data);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
            return $this->unexpectedError();
		}
	}

	public function getReport(Request $request, $id, $validationId)
	{
		try {
			$projectValidation = ProjectValidation::where('project_id', '=', $id)
												  ->where('id', '=', $validationId)
												  ->firstOrFail();
			$reportBusiness = new ValidationReportBusiness();

			$reportData = $reportBusiness->getReport(
				$validationId, 
				$request->input('question_id', null), 
				$request->input('gender', null), 
				$request->input('min_age', null), 
				$request->input('max_age', null)
			);
			$reportData['status'] = 1;
			return json_encode($reportData);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
		}
	}

	public function getRecommendReport(Request $request, $id, $validationId)
	{
		try {
			$projectValidation = ProjectValidation::where('project_id', '=', $id)
													  ->where('id', '=', $validationId)
													  ->firstOrFail();
			$reportBusiness = new ValidationReportBusiness();
			$reportData = $reportBusiness->recommendReport(
				$validationId, 
				$request->input('gender', null), 
				$request->input('min_age', null), 
				$request->input('max_age', null)
			);
			$reportData['status'] = 1;
			return json_encode($reportData);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
		}
	}

	public function listSuggestions(Request $request, $id, $validationId)
	{
		try {
			$project = Project::findOrFail($id);
			$projectValidation = ProjectValidation::where('project_id', '=', $id)
												 ->where('id', '=', $validationId)
												 ->firstOrFail(); 

			$dataSet = Validation::whereNotNull('suggestion')
								 ->where('suggestion','<>', DB::raw("''"))
								 ->where('project_validation_id', '=', $projectValidation->id)
								 ->orderBy('name','ASC');

			return DataTables::of($dataSet)->addColumn('resume',function($validation){
				$html  = "<b>Nome: </b>".$validation->name."<br />";
				$html .= "<b>Ocupação: </b>".$validation->occupation."<br />";
				if ($validation->email) {
					$html .= "<b>E-mail: </b>".$validation->email."<br />";
				}
				$html .= "<b>Mensagem: </b>".$validation->suggestion."<br />";
				return $html;
			})->make(true);

		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}
}