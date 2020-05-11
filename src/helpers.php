<?php

use Cerbero\SqlDumper\SqlDumper;

if (!function_exists('ds')) {
    /**
     * Dump SQL queries executed within the given callback via the default dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function ds(callable $callback)
    {
        return SqlDumper::default($callback);
    }
}

if (!function_exists('dsConsole')) {
    /**
     * Dump SQL queries executed within the given callback via the console dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function dsConsole(callable $callback)
    {
        return SqlDumper::console($callback);
    }
}

if (!function_exists('dsEmail')) {
    /**
     * Dump SQL queries executed within the given callback via the email dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function dsEmail(callable $callback)
    {
        return SqlDumper::email($callback);
    }
}

if (!function_exists('dsHtml')) {
    /**
     * Dump SQL queries executed within the given callback via the HTML dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function dsHtml(callable $callback)
    {
        return SqlDumper::html($callback);
    }
}

if (!function_exists('dsLog')) {
    /**
     * Dump SQL queries executed within the given callback via the log dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function dsLog(callable $callback)
    {
        return SqlDumper::log($callback);
    }
}

if (!function_exists('dsMarkdown')) {
    /**
     * Dump SQL queries executed within the given callback via the markdown dumper.
     *
     * @param callable $callback
     * @return mixed
     */
    function dsMarkdown(callable $callback)
    {
        return SqlDumper::markdown($callback);
    }
}
