<?php

declare(strict_types=1);

namespace Atendwa\Honeypot\Http\Middleware;

use Atendwa\Honeypot\Honeypot;
use Closure;
use Illuminate\Http\Request;
use Throwable;

final class PreventSpam
{
    private Honeypot $honeypot;

    public function __construct()
    {
        $this->honeypot = new Honeypot();
    }

    /**
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->honeypot->request($request)->detectSpam();

        return $next($request);
    }
}
