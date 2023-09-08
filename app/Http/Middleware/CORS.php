<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-headers: Content-type,X-Auth-Token,Authorization,Origin');

        return $next($request);
    }
}
