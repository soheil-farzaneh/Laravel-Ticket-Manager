<?php

namespace Aqayepardakht\TicketManager\Exceptions;

use Exception;

class ReplyNotFoundException extends Exception
{
    public function __construct($message = 'پیام مورد نظر یافت نشد.')
    {
        parent::__construct($message);
    }
}

