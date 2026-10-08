<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $branchParameter = 'branch'
    ): Response {
        $user = $request->user();

        $branchId = $request->route($branchParameter);

        if (! $user || ! $user->canAccessBranch((int) $branchId)) {
            abort(403, 'You are not authorized to access this branch.');
        }

        return $next($request);
    }
}
