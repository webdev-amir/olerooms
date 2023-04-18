<?php

namespace App\Http\Middleware;

use Closure;

class SetPermissionForFrontendUsers
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
        $segment = request()->segment(1);
        if(auth()->user() && $request->method() == 'GET'){
            $response['message'] = 'User have not permission for this page access.';
            $response['type'] = 'error';
            $response['status_code'] = 500;
            if(auth()->user()->hasRole('customer') && $segment != 'customer'){
                 if($request->ajax()){
                    return response()->json($response);
                 }
                 $request->session()->flash('error', trans('User have not permission for this page access.'));
                 return back();
                 abort(404, 'User have not permission for this page access.');
            }
            if(auth()->user()->hasRole('vendor') && $segment != 'owner'){
                if($request->ajax()){
                    return response()->json($response);
                }
                $request->session()->flash('error', trans('User have not permission for this page access.'));
                return back();
                abort(404, 'User have not permission for this page access.');
            }
            if(auth()->user()->hasRole('agent') && $segment != 'agent'){
                if($request->ajax()){
                    return response()->json($response);
                }
                $request->session()->flash('error', trans('User have not permission for this page access.'));
                return back();
                abort(404, 'User have not permission for this page access.');
            }
            if(auth()->user()->hasRole('company') && $segment != 'company'){
                if($request->ajax()){
                    return response()->json($response);
                }
                $request->session()->flash('error', trans('User have not permission for this page access.'));
                return back();
                abort(404, 'User have not permission for this page access.');
            }
        }
        return $next($request);
    }
}
