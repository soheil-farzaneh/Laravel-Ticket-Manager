<?php

namespace Aqayepardakht\TicketManager\Utils;

class ConfigHelper
{
    private const CONFIG_PATH = 'ticket';

    public static function getConfig($key, $default = null)
    {
        return config(self::CONFIG_PATH . ".$key", $default);
    }

    public static function validatedTicketData()
    {
        return self::getConfig('rules');
    }

    public function validateReplyData()
    {
        return self::getConfig('reply.rules');
    }

    public static function getUserIdField()
    {
        return self::getConfig('fields.user_id', 'user_id');
    }

    public static function getCreatedByField()
    {
        return self::getConfig('reply.fields.created_by', 'created_by');
    }

    public static function getTextField()
    {
        return self::getConfig('reply.fields.text', 'text');
    }

    public static function getFileField()
    {
        return self::getConfig('reply.fields.files', 'files');
    }

    public static function getModel()
    {
        return self::getConfig('model', Ticket::class);
    }

    public static function getReplyModel()
    {
        return self::getConfig('reply.model', TicketReply::class);
    }

    public static function getUserModel()
    {
        return self::getConfig('user_model', User::class);
    }

    public static function getUserRelationKey()
    {
        return self::getConfig('relations_foreign_key.user');
    }

    public static function getTicketRelationKey()
    {
        return self::getConfig('relations_foreign_key.ticket', TicketReply::class);
    }

    public static function getStatusField()
    {
        return self::getConfig('fields.status', 'status');
    }

    public static function getAttachmentPath()
    {
        return self::getConfig('attachment_path');
    }

    public static function getTokenName()
    {
        return self::getConfig('token_name');
    }

    public static function getParentIdField()
    {
        return self::getConfig('reply.fields.parent_id', 'parent_id');
    }

    public static function getTicketId()
    {
        return self::getConfig('reply.fields.ticket_id', 'ticket_id');
    }

    public static function getReplyUserIdField()
    {
        return self::getConfig('reply.fields.user_id', 'user_id');
    }

    public static function getIpField()
    {
        return self::getConfig('fields.ip', 'ip');
    }

    public static function getTicketTable()
    {
        return self::getConfig('table', 'tickets');
    }

    public static function getReplyTable()
    {
        return self::getConfig('reply.table', 'ticket_messages');
    }
}