<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // abort_unless($request->user()?->isAdmin(), 403, 'Halaman ini hanya untuk admin.');

        if (!$request->user() || !$request->user()->isAdmin()) {
            // Jika bukan admin, lempar balik ke dashboard member atau ke home dengan pesan error
            return redirect()->route('member.absensi')->with('error', 'Kamu tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
