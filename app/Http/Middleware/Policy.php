<?php

namespace App\Http\Middleware;

use App\Models\compte;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class Policy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $token = true;
        // Récupérer le payload du token
        // $payload = JWTAuth::getPayload($token);

        // Récupérer la valeur de la clé 'user_id' du payload
        // $compte = compte::find($payload->get('sub'));
        if ($compte->roles()->where('nom', $role)->exists()) {
            return $next($request);
        }
        abort(403);
    }
}
