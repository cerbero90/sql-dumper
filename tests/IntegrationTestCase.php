<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Providers\SqlDumperServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase;

/**
 * The test case for integration tests.
 *
 */
abstract class IntegrationTestCase extends TestCase
{
    /**
     * Setup the test environment.
     * 
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * Retrieve the package service providers
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SqlDumperServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('database.default', 'testbench');

        $app->config->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
