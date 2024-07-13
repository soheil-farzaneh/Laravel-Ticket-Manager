<?php

namespace Aqayepardakht\TicketManager\Exceptions;

use Exception;

class TicketNotFoundException extends Exception
{
    public function __construct($message = 'تیکت مورد نظر یافت نشد.')
    {
        parent::__construct($message);
    }
}
