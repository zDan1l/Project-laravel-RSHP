<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsResepsionis
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if user has Dokter role
        $isUser = session('user_role');

        
        if ($isUser == 4) {
            return $next($request);
        }else{
            return back()->with('error', 'Akses ditolak. Anda tidak memiliki hak akses Resepsionis.');
        }
    }
}
