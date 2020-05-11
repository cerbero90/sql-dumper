<?php

namespace Cerbero\SqlDumper;

use Cerbero\SqlDumper\Dumpers\ConsoleDumper;
use Cerbero\SqlDumper\Dumpers\DumperInterface;
use Cerbero\SqlDumper\Dumpers\EmailDumper;
use Cerbero\SqlDumper\Dumpers\HtmlDumper;
use Cerbero\SqlDumper\Dumpers\LogDumper;
use Cerbero\SqlDumper\Dumpers\MarkdownDumper;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The SQL queries dumper.
 *
 */
class SqlDumper
{
    /**
     * The dumper.
     *
     * @var DumperInterface
     */
    protected $dumper;

    /**
     * The total time spent to run queries.
     *
     * @var int
     */
    protected $totalTimeInMs = 0;

    /**
     * Set the dependencies.
     *
     * @param DumperInterface $dumper
     */
    public function __construct(DumperInterface $dumper)
    {
        $this->dumper = $dumper;
    }

    /**
     * Retrieve the dumper
     *
     * @return DumperInterface
     */
    public function getDumper(): DumperInterface
    {
        return $this->dumper;
    }

    /**
     * Dump all queries run in the callback
     *
     * @param callable $callback
     * @return mixed
     */
    public function dump(callable $callback)
    {
        $dispatcher = DB::getEventDispatcher();

        DB::setEventDispatcher($this->getListeningDispatcher());

        $result = $callback();

        DB::setEventDispatcher($dispatcher);

        $this->dumper->addTotalTime($this->totalTimeInMs)->dump();
        $this->totalTimeInMs = 0;

        return $result;
    }

    /**
     * Retrieve the event dispatcher with a listener for SQL queries
     *
     * @return Dispatcher
     */
    protected function getListeningDispatcher(): Dispatcher
    {
        $dispatcher = clone DB::getEventDispatcher();

        $dispatcher->listen(QueryExecuted::class, function (QueryExecuted $event) {
            $frame = $this->getCallerFrame();
            $this->totalTimeInMs += $event->time;
            $query = $this->getQueryFromEvent($event);
            $dispatcher = $event->connection->getEventDispatcher();

            $event->connection->unsetEventDispatcher();
            $explanationRows = $event->connection->select('EXPLAIN ' . $event->sql, $event->bindings);
            $event->connection->setEventDispatcher($dispatcher);

            $this->dumper->addQuery($query)->addTime($event->time);

            if (isset($frame['file'], $frame['line'])) {
                $this->dumper->addCaller($frame['file'], $frame['line']);
            }

            $this->dumper->addExplanations($explanationRows)->addSeparator();
        });

        return $dispatcher;
    }

    /**
     * Retrieve the query caller frame from the backtrace
     *
     * @return array
     */
    protected function getCallerFrame(): array
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $queryCallers = [Model::class, Builder::class, EloquentBuilder::class];

        for ($i = count($backtrace) - 1; $i > 0; $i--) {
            if (in_array($backtrace[$i]['class'] ?? null, $queryCallers)) {
                return $backtrace[$i];
            }
        }
    }

    /**
     * Retrieve the SQL query from the given event
     *
     * @param QueryExecuted $event
     * @return string
     */
    protected function getQueryFromEvent(QueryExecuted $event): string
    {
        $bindings = array_map(function ($binding) {
            return is_numeric($binding) ? $binding : "\"{$binding}\"";
        }, $event->bindings);

        return Str::replaceArray('?', $bindings, $event->sql);
    }

    /**
     * Dump SQL queries via the default dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function default(callable $callback)
    {
        return Container::getInstance()->make(static::class)->dump($callback);
    }

    /**
     * Dump SQL queries via the console dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function console(callable $callback)
    {
        return (new static(new ConsoleDumper()))->dump($callback);
    }

    /**
     * Dump SQL queries via the email dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function email(callable $callback)
    {
        return (new static(new EmailDumper(new HtmlDumper())))->dump($callback);
    }

    /**
     * Dump SQL queries via the html dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function html(callable $callback)
    {
        return (new static(new HtmlDumper()))->dump($callback);
    }

    /**
     * Dump SQL queries via the log dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function log(callable $callback)
    {
        return (new static(new LogDumper()))->dump($callback);
    }

    /**
     * Dump SQL queries via the markdown dumper
     *
     * @param callable $callback
     * @return mixed
     */
    public static function markdown(callable $callback)
    {
        return (new static(new MarkdownDumper()))->dump($callback);
    }
}
