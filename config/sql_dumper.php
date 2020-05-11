<?php

use Cerbero\SqlDumper\Dumpers\EmailDumper;
use Cerbero\SqlDumper\Dumpers\HtmlDumper;
use Cerbero\SqlDumper\Dumpers\MarkdownDumper;

return [
    /*
    |--------------------------------------------------------------------------
    | Default SQL dumper
    |--------------------------------------------------------------------------
    |
    | The class name of the dumper that is invoked by default when dumping SQL
    | statements by calling the `ds()` helper function. By default the HTML
    | dumper is used, but feel free to pick any of the available dumpers
    |
    */
    'default_dumper' => HtmlDumper::class,

    /*
    |--------------------------------------------------------------------------
    | Dumpers fine-tuning
    |--------------------------------------------------------------------------
    |
    | Some dumpers rely on sensible defaults to run properly without requiring
    | the end user to set parameters manually. However you can set your own
    | values here by defining the dumpers and the parameters to override
    |
    */
    HtmlDumper::class => [
        // The path of the HTML file to generate e.g. storage_path('foo.html')
        'path' => null,
        // The path of the template that the HTML file should use
        'template' => null,
    ],
    MarkdownDumper::class => [
        // The path of the markdown file to generate e.g. storage_path('foo.md')
        'path' => null,
    ],
    EmailDumper::class => [
        // The path of the HTML template that the email should use
        'template' => null,
        // The email recipient. If not specified, the configuration "mail.from.address" is used
        'recipient' => null,
        // Whether the email should be queued
        'queue' => false,
    ],
];
