<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $payload = auth()->payload();
            $user = auth()->user();
            // then you can access the claims directly e.g.
            // $payload->get('sub'); // = 123
            // $payload['jti']; // = 'asfe4fq434asdf'
            // $payload('exp'); // = 123456
            var_dump($payload);
            // if (auth()->validate($payload)) {
            //     // credentials are valid
            //     echo "aaaa";
            // }
            die();
            // $token = JWTAuth::getToken();
            // $apy = JWTAuth::getPayload($token)->toArray();
            if (!$apy) { 
                return response('Unauthorized.', 401);
            }
            // attempt to verify the credentials and create a token for the user
            return $next($request);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    
            return response()->json(['token_expired'], 500);
    
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    
            return response()->json(['token_invalid'], 500);
    
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    
            return response()->json(['token_absent' => $e->getMessage()], 500);
    
        }
    }
}
