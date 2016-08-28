<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Enums\EnumCapabilities;
use Gate;
use App\Models\DB\Project;

class PostManager
{
    public function handle($request, Closure $next, $guard = null)
    {	
    	$project = Project::find($request->projectId);
        if ($project && Gate::allows(EnumCapabilities::MAKE_POST_PROJECT, $project)) {
        	return $next($request);
        } else {
        	$request->session()->flash('msg', trans('custom_messages.not_allowed'));
            $request->session()->flash('class_msg', 'alert-danger');
            return back();
        }
    }
}
