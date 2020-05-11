<?php

namespace Cerbero\SqlDumper\Dumpers;

use SqlFormatter;

/**
 * The markdown dumper.
 *
 */
class MarkdownDumper extends FileDumper
{
    /**
     * The file extension.
     *
     * @var string
     */
    protected $extension = 'md';

    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface
    {
        $this->content .= '### Query' . PHP_EOL;
        $this->content .= $this->formatSqlBlock($query);

        return $this;
    }

    /**
     * Format an SQL code block for the given query
     *
     * @param string $query
     * @return string
     */
    protected function formatSqlBlock(string $query): string
    {
        $formattedQuery = SqlFormatter::format($query, false);

        return '```sql' . PHP_EOL . $formattedQuery . PHP_EOL . '```' . PHP_EOL;
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

        $this->content .= "Execution time in seconds: **{$seconds}**" . PHP_EOL;

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
        $this->content .= "Executed in file `{$file}` on line **{$line}**" . PHP_EOL;

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
        $this->content .= '### Explanation' . PHP_EOL;

        $headers = array_keys((array) $explanationRows[0]);

        $this->content .= $this->formatTable($headers, $explanationRows);

        return $this;
    }

    /**
     * Format a markdown table with the given headers and rows
     *
     * @param array $headers
     * @param array $rows
     * @return string
     */
    protected function formatTable(array $headers, array $rows): string
    {
        $table = implode(' | ', $headers) . PHP_EOL;
        $table .= str_repeat('---|', count($headers)) . PHP_EOL;

        foreach ($rows as $row) {
            $table .= implode(' | ', (array) $row) . PHP_EOL;
        }

        return $table;
    }

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface
    {
        $this->content .= '---' . PHP_EOL;

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

        $this->content .= "Total queries execution time in seconds: **{$seconds}**" . PHP_EOL;

        return $this;
    }
}
