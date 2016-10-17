<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DB\Work;
use App\Utils\Utils;
use Auth;
use Log;

class WorkController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function delete(Request $request)
	{
		try {
			$user = Auth::user();
			$work = Work::where('id', '=', $request->input('id'))
									->where('user_id', '=', $user->id)
									->firstOrFail();
			$work->delete();
			return json_encode(['status' => 1, 'msg' => 'Profissão excluído com sucesso.', 'class_msg' => 'alert-success']);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}
}