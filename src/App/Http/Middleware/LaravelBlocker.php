<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use jeremykenedy\LaravelBlocker\App\Http\Traits\IpAddressDetails;
use jeremykenedy\LaravelBlocker\App\Http\Traits\LaravelCheckBlockedTrait;

class LaravelBlocker
{
    use LaravelCheckBlockedTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $check = null)
    {
        if (config('laravelblocker.laravelBlockerEnabled')) {
            LaravelCheckBlockedTrait::checkBlocked($check);
        }

        return $next($request);
    }
}
