<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Dumpers\HtmlDumper;
use Cerbero\SqlDumper\Providers\SqlDumperServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Log;

/**
 * The sql dump test.
 *
 */
class SqlDumpTest extends IntegrationTestCase
{
    /**
     * Retrieve the package service providers
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SqlDumperServiceProvider::class, TestServiceProvider::class];
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(HttpKernel::class, Kernel::class);
    }

    /**
     * @test
     */
    public function dumps_via_default_dumper()
    {
        $dumpPath = $this->app->storagePath() . '/dump.html';
        $this->app->config->set('sql_dumper.' . HtmlDumper::class . '.path', $dumpPath);

        $this->get('test1')->assertJsonFragment(['id' => 1]);

        $this->assertTrue(file_exists($dumpPath));

        unlink($dumpPath);
    }

    /**
     * @test
     */
    public function dumps_via_custom_dumper()
    {
        Log::shouldReceive('debug');

        $this->get('test2')->assertJsonFragment(['id' => 1]);
    }

    /**
     * @test
     */
    public function fails_if_dumper_is_invalid()
    {
        $this->get('test3')->assertStatus(500);
    }
}
