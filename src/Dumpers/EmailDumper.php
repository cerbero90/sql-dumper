<?php

namespace Cerbero\SqlDumper\Dumpers;

use Cerbero\SqlDumper\Mail\SqlDumped;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

/**
 * The email dumper.
 *
 */
class EmailDumper implements DumperInterface
{
    use ConfigAware;

    /**
     * The html dumper.
     *
     * @var HtmlDumper
     */
    protected $html;

    /**
     * Instantiate the class.
     *
     * @param HtmlDumper $html
     */
    public function __construct(HtmlDumper $html)
    {
        $this->html = $html;
    }

    /**
     * Add the given query to dump
     *
     * @param string $query
     * @return DumperInterface
     */
    public function addQuery(string $query): DumperInterface
    {
        $this->html->addQuery($query);

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
        $this->html->addTime($milliseconds);

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
        $this->html->addCaller($file, $line);

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
        $this->html->addExplanations($explanationRows);

        return $this;
    }

    /**
     * Add a separator
     *
     * @return DumperInterface
     */
    public function addSeparator(): DumperInterface
    {
        $this->html->addSeparator();

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
        $this->html->addTotalTime($milliseconds);

        return $this;
    }

    /**
     * Dump queries information
     *
     * @return mixed
     */
    public function dump()
    {
        $recipient = $this->config('recipient', Config::get('mail.from.address'));

        if ($recipient === null) {
            throw new InvalidArgumentException('Missing recipient to send SQL queries dump to.');
        }

        $method = $this->config('queue') ? 'queue' : 'send';
        $template = $this->config('template', __DIR__ . '/../../stubs/template.html.stub');
        $html = str_replace('{{content}}', $this->html->getContent(), file_get_contents($template));
        $email = (new SqlDumped())->html($html);

        Mail::to($recipient)->$method($email);
    }
}
