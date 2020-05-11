<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Database\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * The test service provider.
 *
 */
class TestServiceProvider extends ServiceProvider
{
    /**
     * Boot up services
     *
     * @return void
     */
    public function boot(): void
    {
        Route::middleware('sql.dump')->get('test1', function () {
            return User::first();
        });

        Route::middleware('sql.dump:log')->get('test2', function () {
            return User::first();
        });

        Route::middleware('sql.dump:invalid')->get('test3', function () {
            return User::first();
        });
    }
}
