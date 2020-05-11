<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Dumpers\EmailDumper;
use Cerbero\SqlDumper\Dumpers\HtmlDumper;
use PHPUnit\Framework\TestCase;

/**
 * The HTML dumper test.
 *
 */
class HtmlDumperTest extends TestCase
{
    /**
     * @test
     */
    public function gets_html_path()
    {
        $dumper = new HtmlDumper('dump.html');

        $this->assertSame('dump.html', $dumper->getPath());
    }
}
