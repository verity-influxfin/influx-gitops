<?php

namespace App\Http\Middleware;

class FeatureFunction
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    ];

    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (Throwable $e) {
            
        }
    }
}
