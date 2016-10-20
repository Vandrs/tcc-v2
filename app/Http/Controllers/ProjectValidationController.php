<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Log;
use App\Utils\Utils;
use App\Models\DB\Project;
use App\Models\DB\ProjectValidation;
use App\Asset\AssetLoader;

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
			AssetLoader::register([],['admin.css']);
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

	public function view($id)
	{

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
		dd($request->all());
	}

	public function edit($id)
	{

	}

	public function update($id)
	{

	}
}