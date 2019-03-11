<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use jeremykenedy\LaravelBlocker\App\Traits\LaravelCheckBlockedTrait;

class LaravelBlocker
{
    use LaravelCheckBlockedTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('laravelblocker.laravelBlockerEnabled')) {
            LaravelCheckBlockedTrait::checkBlocked();
        }

        return $next($request);
    }
}
