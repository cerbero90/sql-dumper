<?php

namespace Cerbero\SqlDumper\Dumpers;

/**
 * The dumper interface.
 *
 */
interface DumperInterface
{
    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface;

    /**
     * Add the query execution time to dump
     *
     * @param float $milliseconds
     * @return DumperInterface
     */
    public function addTime(float $milliseconds): DumperInterface;

    /**
     * Add the query caller to dump
     *
     * @param string $file
     * @param int $line
     * @return DumperInterface
     */
    public function addCaller(string $file, int $line): DumperInterface;

    /**
     * Add the given explanation rows to dump
     *
     * @param array $explanationRows
     * @return DumperInterface
     */
    public function addExplanations(array $explanationRows): DumperInterface;

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface;

    /**
     * Add the execution time of all queries to dump
     *
     * @param float $milliseconds
     * @return DumperInterface
     */
    public function addTotalTime(float $milliseconds): DumperInterface;

    /**
     * Dump queries information
     *
     * @return mixed
     */
    public function dump();
}
