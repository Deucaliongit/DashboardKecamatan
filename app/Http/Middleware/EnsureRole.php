<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Membatasi akses route berdasarkan role user yang login.
     * Pemakaian di routes: ->middleware('role:admin_kecamatan')
     * atau beberapa role sekaligus: ->middleware('role:admin_kecamatan,user_sekolah')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
