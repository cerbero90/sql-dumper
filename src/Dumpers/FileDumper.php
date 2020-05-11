<?php

namespace Cerbero\SqlDumper\Dumpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Abstract implementation of a file dumper.
 *
 */
abstract class FileDumper implements DumperInterface
{
    /**
     * The path of the file to generate.
     *
     * @var string
     */
    protected $path;

    /**
     * The file extension.
     *
     * @var string
     */
    protected $extension;

    /**
     * The file content.
     *
     * @var string
     */
    protected $content = '';

    /**
     * Instantiate the class.
     *
     * @param string $path
     */
    public function __construct(string $path = null)
    {
        $this->path = $path ?: $this->getDefaultPath();
    }

    /**
     * Retrieve the default file path
     *
     * @return string
     */
    protected function getDefaultPath(): string
    {
        if ($path = Config::get('sql_dumper.' . static::class . '.path')) {
            return $path;
        }

        return App::storagePath() . '/sql_dump_' . time() . '.' . $this->getExtension();
    }

    /**
     * Retrieve the file extension
     *
     * @return string
     */
    protected function getExtension(): string
    {
        return (string) $this->extension;
    }

    /**
     * Retrieve the file path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieve the file content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Dump queries information
     *
     * @return mixed
     */
    public function dump()
    {
        file_put_contents($this->path, $this->content);

        $this->content = '';
    }
}
