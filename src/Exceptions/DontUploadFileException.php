<?php

namespace Aqayepardakht\TicketManager\Exceptions;

use Exception;

class DontUploadFileException extends Exception
{
    public function __construct($message = 'فایل بارگذاری نشده است.')
    {
        parent::__construct($message);
    }
}
