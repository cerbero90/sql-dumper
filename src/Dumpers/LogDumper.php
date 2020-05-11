<?php

namespace Cerbero\SqlDumper\Dumpers;

use Illuminate\Support\Facades\Log;

/**
 * The log dumper.
 *
 */
class LogDumper implements DumperInterface
{
    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface
    {
        Log::debug("Query: {$query}");

        return $this;
    }

    /**
     * Add the query execution time to dump
     *
     * @param float $milliseconds
     * @return DumperInterface
     */
    public function addTime(float $milliseconds): DumperInterface
    {
        $seconds = $milliseconds / 1000;

        Log::debug("Execution time in seconds: {$seconds}");

        return $this;
    }

    /**
     * Add the query caller to dump
     *
     * @param string $file
     * @param int $line
     * @return DumperInterface
     */
    public function addCaller(string $file, int $line): DumperInterface
    {
        Log::debug("Executed in file {$file} on line {$line}");

        return $this;
    }

    /**
     * Add the given explanation rows to dump
     *
     * @param array $explanationRows
     * @return DumperInterface
     */
    public function addExplanations(array $explanationRows): DumperInterface
    {
        $context = array_map('get_object_vars', $explanationRows);

        Log::debug('Explanations', $context);

        return $this;
    }

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface
    {
        Log::debug(null);

        return $this;
    }

    /**
     * Add the execution time of all queries to dump
     *
     * @param float $milliseconds
     * @return DumperInterface
     */
    public function addTotalTime(float $milliseconds): DumperInterface
    {
        $seconds = $milliseconds / 1000;

        Log::debug("Total queries execution time in seconds: {$seconds}");

        return $this;
    }

    /**
     * Dump queries information
     *
     * @return mixed
     */
    public function dump()
    {
        //
    }
}
