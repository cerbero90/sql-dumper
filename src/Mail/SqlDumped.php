<?php

namespace Cerbero\SqlDumper\Mail;

use Illuminate\Mail\Mailable;

/**
 * The email to send when SQL queries are dumped.
 *
 */
class SqlDumped extends Mailable
{
    /**
     * Build the email to send
     *
     * @return self
     */
    public function build(): self
    {
        return $this->subject('SQL Dump');
    }
}
