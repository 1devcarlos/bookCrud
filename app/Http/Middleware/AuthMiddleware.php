<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$guards): Response
  {
    if (!Auth::guard($guards)->check()) {
      throw new HttpResponseException(response()->json(['error' => 'Unauthorized'], 401));
    }
    return $next($request);
  }
}
