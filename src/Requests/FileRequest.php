<?php

namespace Aqayepardakht\TicketManager\Requests;

use App\Http\Requests\BaseRequest;

class FileRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|max:2048|mimes:doc,docx,pdf,jpeg,jpg,png,svg',
        ];
    }
}