<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DB\Graduation;
use App\Utils\Utils;
use Auth;
use Log;

class GraduationController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function delete(Request $request)
	{
		try {
			$user = Auth::user();
			$graduation = Graduation::where('id', '=', $request->input('id'))
									->where('user_id', '=', $user->id)
									->firstOrFail();
			$graduation->delete();
			return json_encode(['status' => 1, 'msg' => 'Curso excluído com sucesso.', 'class_msg' => 'alert-success']);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}
}