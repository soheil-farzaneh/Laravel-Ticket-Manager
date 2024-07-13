<?php

namespace Aqayepardakht\TicketManager\Utils;

use Illuminate\Support\Facades\Auth;
use Aqayepardakht\TicketManager\Utils\ConfigHelper;

class AuthHelper
{

    public static function getCreatedBy()
    {
        $user = Auth::user();
        return ($user && $user->token()->name === ConfigHelper::getTokenName()) ? 'admin' : 'user';
    }

    public static function getClientIp($request)
    {
        return $request->ip();
    }

    public static function getUserId(array $data, bool $isReply)
    {
        $authenticatedUserId = Auth::id();
        return $isReply ? $authenticatedUserId : ($data[ConfigHelper::getUserIdField()] ?? $authenticatedUserId);
    }

    public static function isUserAdmin()
    {
        return self::getCreatedBy();
    }
}
