<?php

namespace CKSource\CKFinderBridge;

use Closure;

class CKFinderMiddleware
{
    public function handle($request, Closure $next)
    {
        config(['ckfinder.authentication' => function() use ($request) {

            return false;
        }] );

        return $next($request);
    }
}
