<?php

namespace Cerbero\SqlDumper\Dumpers;

use SqlFormatter;

/**
 * The HTML dumper.
 *
 */
class HtmlDumper extends FileDumper
{
    use ConfigAware;

    /**
     * The file extension.
     *
     * @var string
     */
    protected $extension = 'html';

    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface
    {
        $this->content .= '<h3>Query</h3>';
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
        SqlFormatter::$cli = false;

        $formattedQuery = SqlFormatter::format($query, true);

        return "<code class='sql'>{$formattedQuery}</code>";
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

        $this->content .= "<p>Execution time in seconds: <strong>{$seconds}</strong></p>";

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
        $this->content .= "<p>Executed in file <code>{$file}</code> on line <strong>{$line}</strong></p>";

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
        $this->content .= '<h3>Explanation</h3>';

        $headers = array_keys((array) $explanationRows[0]);

        $this->content .= '<div style="overflow:auto">' . $this->formatTable($headers, $explanationRows) . '</div>';

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
        $table = '<table class="table table-bordered table-striped"><thead><tr>';

        foreach ($headers as $header) {
            $table .= "<th scope='col'>{$header}</th>";
        }

        $table .= '</tr></thead><tbody>';

        foreach ($rows as $row) {
            $table .= '<tr>';

            foreach ((array) $row as $value) {
                $table .= "<td>{$value}</td>";
            }

            $table .= '</tr>';
        }

        return $table . '</tbody></table>';
    }

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface
    {
        $this->content .= '<hr>';

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

        $this->content .= "<p>Total queries execution time in seconds: <strong>{$seconds}</strong></p>";

        return $this;
    }

    /**
     * Dump queries information
     *
     * @return mixed
     */
    public function dump()
    {
        $template = $this->config('template', __DIR__ . '/../../stubs/template.html.stub');
        $this->content = str_replace('{{content}}', $this->content, file_get_contents($template));

        parent::dump();
    }
}
