<?php

namespace Cerbero\SqlDumper\Dumpers;

use Illuminate\Support\Facades\Config;

/**
 * The trait for configurable dumpers.
 *
 */
trait ConfigAware
{
    /**
     * Retrieve the configuration value for the current dumper.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return Config::get('sql_dumper.' . static::class . ".{$key}") ?: $default;
    }
}
