<?php

namespace N4B;

use Exception;

class Error extends Exception
{
    public function __construct($message = 'BB_ERROR_UNKNOWN_USER_SPECIFIED_ERROR')
    {
        parent::__construct($message);
    }
}
