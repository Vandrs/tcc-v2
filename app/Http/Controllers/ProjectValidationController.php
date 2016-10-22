<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Log;
use App\Utils\Utils;
use App\Utils\UrlUtil;
use App\Models\DB\Project;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Question;
use App\Models\Business\ProjectValidationBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Asset\AssetLoader;
use Yajra\Datatables\Datatables;
use Illuminate\Database\ModelNotFoundException;


class ProjectValidationController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth')->except('view');
		$this->middleware('makeProjectValidation')->except('view');
	}	

	public function index($id)
	{
		try {
			$project = Project::findOrFail($id);
			AssetLoader::register(['listValidation.js'],['admin.css'],['DataTables']);
			$data = [
				"page_title" => "Lista de Validações",
				"project"	 => $project
			];
			return view('project.validations',$data);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function list($id)
	{
		try {
			$project = Project::findOrFail($id);
			$dataSet = ProjectValidation::where('project_id', '=', $project->id);
			return Datatables::of($dataSet)
							 ->editcolumn('title', function($projectValidation) use ($project) {
							 	$editRoute = route('admin.project.validations.edit',['id' => $project->id, 'validationId' => $projectValidation->id]);
							 	return '<a href="'.$editRoute.'">'.$projectValidation->title.'</a>';
							 })
							 ->editColumn('started_at', function($projectValidation){
							 	return $projectValidation->started_at->format('d/m/Y');
							 })
							 ->editColumn('ended_at', function($projectValidation){
							 	return $projectValidation->ended_at->format('d/m/Y');
							 })
							 ->addColumn('actions', function($projectValidation) use ($project) {
							 	$deleteRoute = route('admin.project.validations.delete',['id' => $project->id, 'validationId' => $projectValidation->id]);
							 	$editRoute = route('admin.project.validations.edit',['id' => $project->id, 'validationId' => $projectValidation->id]);
							 	$html  = '<a href="'.$editRoute.'" class="btn btn-fab btn-fab-mini margin-right-5" title="Editar" data-toggle="tooltip"><i class="material-icons">edit</i></a>';
							 	$html .= '<a href="'.$deleteRoute.'" class="btn btn-fab btn-fab-mini deleteValidation" title="Excluir" data-toggle="tooltip"><i class="material-icons">delete</i></a>';
								return $html;							 	
							 })
							 ->make(true);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}

	public function view($path, $validation_path)
	{
		$id = UrlUtil::getIdByUrlPath($path);
		$validationId = UrlUtil::getIdByUrlPath($validation_path);
        $project = Project::findOrFail($id);
        $projectValidation = ProjectValidation::findOrFail($validationId);
        dd($projectValidation);
	}

	public function create($id)
	{
		try {
			$project = Project::findOrFail($id);
			AssetLoader::register(['createValidation.js'],['admin.css'],['AirDatePicker']);
			$data = [
				"page_title" => "Nova Validação",
				"project"	 => $project
			];
			return view('project.create-validations',$data);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function save(Request $request, $id)
	{
		try {
			$project = Project::findOrFail($id);
			$data = $request->except('_token');	
			$data['project_id'] = $project->id;
			$projectValidationBusiness = new ProjectValidationBusiness;
			if ($projectValidation = $projectValidationBusiness->create($data)) {
				CrudProjectBusiness::dispathElasticJob($project);
				$request->session()->flash('msg', 'Questionário criado com sucesso!');			
				$request->session()->flash('class_msg', 'alert-success');
				return redirect()->route('admin.project.validations',['id' => $project->id]);
			} else {
				$validator = $projectValidationBusiness->getValidator();
				return back()->withInput()->withErrors($validator);
			}
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function edit($id, $validationId)
	{
		try {
			$projectValidation = ProjectValidation::where('id', '=', $validationId)
							 					  ->where('project_id', '=', $id)
							 					  ->firstOrFail();
			AssetLoader::register(['editValidation.js'],['admin.css'],['AirDatePicker']);
			$data = [
				'validation' => $projectValidation,
				'project' 	 => $projectValidation->project,
				'page_title' => 'Editar Validação',
			];
			return view('project.edit-validation',$data);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function update(Request $request, $id, $validationId)
	{
		try {
			$project = Project::findOrFail($id);
			$projectValidation = ProjectValidation::where('id', '=', $validationId)
							 				  ->where('project_id', '=', $id)
							 				  ->firstOrFail();
			$projectValidationBusiness = new ProjectValidationBusiness;
			if ($projectValidationBusiness->update($projectValidation, $request->except('_token'))) {
				CrudProjectBusiness::dispathElasticJob($project);
				$request->session()->flash('msg','Questionário alterado com sucesso!');
				$request->session()->flash('class_msg','alert-success');
				return redirect()->route('admin.project.validations',['id' => $id]);
			}  else {
				$validator = $projectValidationBusiness->getValidator();
				return back()->withInput()->withErrors($validator);
			}
		} catch (\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
		
	}

	public function delete(Request $request, $id, $validationId)
	{
		try {
			$project = Project::findOrFail($id);
			$projectValidation = ProjectValidation::where('id', '=', $validationId)
							 					  ->where('project_id', '=', $id)
							 					  ->firstOrFail();
			if ($projectValidation->delete()) {
				CrudProjectBusiness::dispathElasticJob($project);
				$request->session()->flash('msg','Questionário excluído com sucesso!');
				$request->session()->flash('class_msg','alert-success');
				return redirect()->route('admin.project.validations',['id' => $id]);
			} else {
				throw new \Exception('Falha ao tentar exluir validação de projeto: '.$validationId);
			}
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function deleteQuestion(Request $request, $id, $validationId)
	{
		try {
			$project = Project::findOrFail($id);
			$projectValidation = ProjectValidation::where('id', '=', $validationId)
							 					  ->where('project_id', '=', $id)
							 					  ->firstOrFail();
			$question = Question::where('id','=',$request->input('question_id'))
								->where('project_validation_id','=', $projectValidation->id)
								->firstOrFail();
			if ($question->delete()) {
				CrudProjectBusiness::dispathElasticJob($project);
				$request->session()->flash('msg','Questionário excluído com sucesso!');
				$request->session()->flash('class_msg','alert-success');
				return json_encode(['status' => 1]);
			} else {
				throw new \Exception('Falha ao tentar exluir validação de projeto: '.$validationId);
			}
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}
}