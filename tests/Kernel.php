<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Http\Middleware\SqlDump;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Throwable;

/**
 * The kernel.
 *
 */
class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'sql.dump' => SqlDump::class,
    ];
}
