<?php

namespace Aqayepardakht\TicketManager\Requests;

use App\Http\Requests\BaseRequest;
use Aqayepardakht\Ticket\Utils\ConfigHelper;

class TicketRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ConfigHelper::validatedTicketData();
    }
}
