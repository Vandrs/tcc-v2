<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Enums\EnumCapabilities;
use Gate;
use App\Models\DB\Project;

class MakeProjectValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $project = Project::find($request->id);
        if ($project && Gate::allows(EnumCapabilities::MAKE_PROJECT_VALIDATION, $project)) {
            return $next($request);
        } else {
            $request->session()->flash('msg', trans('custom_messages.not_allowed'));
            $request->session()->flash('class_msg', 'alert-danger');
            return back();
        }
    }
}


