<?php

namespace Cerbero\SqlDumper\Dumpers;

use SqlFormatter;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The console dumper.
 *
 */
class ConsoleDumper implements DumperInterface
{
    /**
     * The console output.
     *
     * @var SymfonyStyle
     */
    protected $output;

    /**
     * Instantiate the class.
     *
     * @param SymfonyStyle $output
     */
    public function __construct(SymfonyStyle $output = null)
    {
        $this->output = $output ?: $this->getDefaultOutput();
    }

    /**
     * Retrieve the default console output
     *
     * @return SymfonyStyle
     */
    protected function getDefaultOutput(): SymfonyStyle
    {
        return new SymfonyStyle(new ArgvInput([]), new ConsoleOutput());
    }

    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface
    {
        SqlFormatter::$cli = true;

        $this->output->title('Query');
        $this->output->block(SqlFormatter::format($query));

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

        $this->output->text("Execution time in seconds: {$seconds}");

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
        $this->output->newLine();
        $this->output->text("Executed in file <info>{$file}</info> on line <info>{$line}</info>");

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
        $headers = array_keys((array) $explanationRows[0]);
        $rows = array_map('get_object_vars', $explanationRows);

        $this->output->title('Explanation');
        $this->output->table($headers, $rows);

        return $this;
    }

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface
    {
        $this->output->newLine();

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

        $this->output->block("Total queries execution time in seconds: {$seconds}", null, 'fg=green;bg=default');

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
