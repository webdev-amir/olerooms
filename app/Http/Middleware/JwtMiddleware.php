<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        $headers = apache_request_headers(); //get header
        $request->headers->set('Authorization', $headers['Authorization']);// set header in request
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                 return response()->json(['status_code'=> \Config::get('custom.token-expire-code'),'message' => 'Token is Invalid'], \Config::get('custom.token-expire-code'));
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                 return response()->json(['status_code'=> \Config::get('custom.token-expire-code'),'message' => 'Token is Expired'], \Config::get('custom.token-expire-code'));
            }else{
                 return response()->json(['status_code'=> \Config::get('custom.token-expire-code'),'message' => 'Authorization Token not found'], \Config::get('custom.token-expire-code'));
            }
        }
        return $next($request);
    }
}