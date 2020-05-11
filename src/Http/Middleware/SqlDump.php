<?php

namespace Cerbero\SqlDumper\Http\Middleware;

use Cerbero\SqlDumper\SqlDumper;
use Closure;
use InvalidArgumentException;

/**
 * The middleware to dump SQL queries.
 *
 */
class SqlDump
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $dumper
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function handle($request, Closure $next, string $dumper = 'default')
    {
        if (!method_exists(SqlDumper::class, $dumper)) {
            throw new InvalidArgumentException("The dumper [$dumper] does not exist.");
        }

        return SqlDumper::$dumper(function () use ($next, $request) {
            return $next($request);
        });
    }
}
