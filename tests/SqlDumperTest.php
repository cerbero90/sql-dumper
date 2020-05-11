<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Database\Models\User;
use Cerbero\SqlDumper\Dumpers\HtmlDumper;
use Cerbero\SqlDumper\Dumpers\MarkdownDumper;
use Cerbero\SqlDumper\Mail\SqlDumped;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

/**
 * The SQL dumper test.
 *
 */
class SqlDumperTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function gets_the_dumper()
    {
        $sqlDumper = new SqlDumper($dumper = new HtmlDumper());

        $this->assertSame($dumper, $sqlDumper->getDumper());
    }

    /**
     * @test
     */
    public function dumps_via_default_dumper()
    {
        $dumpPath = $this->app->storagePath() . '/dump.html';
        $this->app->config->set('sql_dumper.' . HtmlDumper::class . '.path', $dumpPath);

        $user = SqlDumper::default(function () {
            return User::first();
        });

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(file_exists($dumpPath));

        unlink($dumpPath);
    }

    /**
     * @test
     */
    public function dumps_via_console_dumper()
    {
        $user = SqlDumper::console(function () {
            return User::first();
        });

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function dumps_via_email_dumper()
    {
        Mail::fake();

        $user = SqlDumper::email(function () {
            return User::find(1);
        });

        $this->assertInstanceOf(User::class, $user);

        Mail::assertSent(SqlDumped::class);
    }

    /**
     * @test
     */
    public function fails_if_recipient_is_missing()
    {
        $from = $this->app->config->get('mail.from.address');
        $this->app->config->set('mail.from.address', null);

        $this->expectExceptionObject(new InvalidArgumentException('Missing recipient to send SQL queries dump to.'));

        $user = SqlDumper::email(function () {
            return User::find(1);
        });

        $this->app->config->set('mail.from.address', $from);
    }

    /**
     * @test
     */
    public function dumps_via_html_dumper()
    {
        $dumpPath = $this->app->storagePath() . '/dump.html';
        $this->app->config->set('sql_dumper.' . HtmlDumper::class . '.path', $dumpPath);

        $user = SqlDumper::html(function () {
            return User::first();
        });

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(file_exists($dumpPath));

        unlink($dumpPath);
    }

    /**
     * @test
     */
    public function dumps_via_log_dumper()
    {
        Log::shouldReceive('debug');

        $user = SqlDumper::log(function () {
            return User::first();
        });

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function dumps_via_markdown_dumper()
    {
        $dumpPath = $this->app->storagePath() . '/dump.md';
        $this->app->config->set('sql_dumper.' . MarkdownDumper::class . '.path', $dumpPath);

        $user = SqlDumper::markdown(function () {
            return User::first();
        });

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(file_exists($dumpPath));

        unlink($dumpPath);
    }
}
