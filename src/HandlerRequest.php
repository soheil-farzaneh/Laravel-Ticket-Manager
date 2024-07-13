<?php

namespace Aqayepardakht\TicketManager;

use Aqayepardakht\TicketManager\Utils\AuthHelper;
use Aqayepardakht\TicketManager\Utils\configHelper;

class HandlerRequest
{

    public $ticketData;
    public $replyData;

    public function getTicketData($request)
    {
        $confingKey = 'fields';
        return $this->prepareData($request, $confingKey, false);
    }

    public function getReplyData($request)
    {
        $confingKey = 'reply.fields';
        $replyData = $this->prepareData($request, $confingKey, true);

        $replyData[configHelper::getTextField()]       = $replyData[configHelper::getTextField()] ?? null;
        $replyData[configHelper::getFileField()]       = $replyData[configHelper::getFileField()] ?? null;
        $replyData[configHelper::getCreatedByField()]  = AuthHelper::getCreatedBy();

        return $replyData;
    }

    private function prepareData($request, $confingKey, bool $isReply)
    {
        $fieldsConfig  = configHelper::getConfig($confingKey);
        $data          = $request->only(array_keys($fieldsConfig));

        $data[configHelper::getIpField()]     = AuthHelper::getClientIp($request);
        $data[ConfigHelper::getUserIdField()] = AuthHelper::getUserId($data, $isReply);

        return $data;
    }
}
