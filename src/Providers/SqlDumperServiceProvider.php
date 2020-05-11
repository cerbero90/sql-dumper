<?php

namespace Cerbero\SqlDumper\Providers;

use Cerbero\SqlDumper\Dumpers\DumperInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * The SQL dumper service provider.
 *
 */
class SqlDumperServiceProvider extends ServiceProvider
{
    /**
     * The package configuration path.
     *
     * @var string
     */
    protected const CONFIG = __DIR__ . '/../../config/sql_dumper.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([static::CONFIG => $this->app->configPath('sql_dumper.php')], 'sql-dumper');
    }

    /**
     * Register the bindings
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(static::CONFIG, 'sql_dumper');

        $this->app->bind(DumperInterface::class, function (Application $app) {
            $dumper = $this->config('default_dumper');

            return $app->make($dumper, $this->config($dumper, []));
        });
    }

    /**
     * Retrieve a configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return $this->app->config->get("sql_dumper.{$key}", $default);
    }

    /**
     * Determine if the provider is deferred.
     *
     * @return bool
     */
    public function isDeferred()
    {
        return $this->defer;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [DumperInterface::class];
    }
}
