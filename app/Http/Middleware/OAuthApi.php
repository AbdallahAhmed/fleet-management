<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Missing authorization_token in header
        if (!$token = $this->getAuthorizationToken($request)) {
            return response('Authorization Token is missing.', 401);
        }

        // Check if authenticated
        if (!$this->authenticate($token)) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }


    /**
     * Check Authentication
     * @param $token
     * @return bool
     */
    private function authenticate($token)
    {
        $user = User::where('api_token', $token)->first(['id']);
        if (!isset($user)) {
            return false;
        }
        Auth::guard('api')->onceUsingId($user->id);
        return true;
    }

    /**
     * Get api token form request
     * @param $request
     * @return null
     */
    private function getAuthorizationToken($request)
    {
        $header = $request->header('authorization');
        if (empty($header) || strlen($header) <= 8 || !Str::contains($header, 'Bearer ')) {
            return null;
        }
        // 7 is the number of chars of 'Bearer '
        return (substr($header, 7));
    }
}