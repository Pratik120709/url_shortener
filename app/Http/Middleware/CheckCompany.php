<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && !$user->company_id) {
            abort(403, 'You are not associated with any company.');
        }

        return $next($request);
    }
}
