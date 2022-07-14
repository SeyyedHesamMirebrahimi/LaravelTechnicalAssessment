<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Token extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->headers->get('authorization')) {
            $token = str_replace('Bearer ' , '' , $request->headers->get('authorization'));
            $user = User::where('token' , $token)->first();
            if ($user) {
                return $next($request);
            } else {
                return new Response([
                    'success' => false,
                    'message' => 'No User Found With This Token'
                ] , 403);
            }
        }
        return new Response([
            'success' => false,
            'message' => 'No Token Provided'
        ] , 400);
    }
}
