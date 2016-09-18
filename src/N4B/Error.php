<?php

namespace N4B;

use Exception;

class Error extends Exception
{
    public function __construct($message = 'BB_ERROR_UNKNOWN_USER_SPECIFIED_ERROR', $code = 200, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
