<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();
        $secretKey = env('JWT_SECRET_KEY');

        if (!$jwt) {
            return response()->json(['msg' => 'Akses ditolak, token tidak ditemukan'], 401);
        }

        if (!$secretKey) {
            return response()->json(['msg' => 'Konfigurasi server error, secret key tidak ditemukan'], 500);
        }

        try {
            $jwtDecoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Token tidak valid'], 401);
        }

        if ($jwtDecoded->role == 'admin') {
            return $next($request);
        }

        return response()->json(['msg' => 'Akses ditolak, role tidak memenuhi persyaratan'], 403);
    }
}
