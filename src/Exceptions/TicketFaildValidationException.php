<?php

namespace Aqayepardakht\TicketManager\Exceptions;

use Illuminate\Validation\ValidationException;

class TicketFaildValidationException extends ValidationException
{
    public function __construct($validator)
    {
        $message = $validator->getMessageBag()->toArray();
        parent::__construct($message);
    }
}
