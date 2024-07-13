<?php

namespace Aqayepardakht\TicketManager\Exceptions;

use Exception;

class TicketClosedException extends Exception
{
    public function __construct($message = 'تیکت مورد نظر بسته شده است.')
    {
        parent::__construct($message);
    }
}
