<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function notAllowed($msg = null){
        if(is_null($msg)){
            $msg = trans('custom_messages.not_allowed');
        }
        $request = request();
        $request->session()->flash('msg', $msg);
        $request->session()->flash('class_msg', 'alert-danger');
        return back();
    }

    public function ajaxNotAllowed($msg = null){
        if(is_null($msg)){
            $msg = trans('custom_messages.not_allowed');
        }
        return json_encode([
            'status' => 0, 'msg' => $msg, 'class_msg' => 'alert-danger'
        ]);
    }

    public function notFound(){
        return redirect()->route('site.404');
    }

    public function unexpectedError($msg = null){
        if(is_null($msg)){
            $msg = trans('custom_messages.unexpected_error');
        }
        $request = request();
        $request->session()->flash('msg', $msg);
        $request->session()->flash('class_msg', 'alert-danger');
        return back()->withInput();
    }

    public function ajaxUnexpectedError($msg = null, $except = null){
        if(is_null($msg)){
            $msg = trans('custom_messages.unexpected_error');
        }
        return json_encode([
            'status' => 0, 'msg' => $msg, 'class_msg' => 'alert-danger', 'except' => $except
        ]);
    }
}
