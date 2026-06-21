<?php

namespace App\Exceptions;

use Exception;

class DeleteRestrictionException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message Translation key or direct message
     */
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
