<?php

namespace Aqayepardakht\TicketManager\Traits;

use Aqayepardakht\Ticket\Utils\ConfigHelper;

trait ReplyInteractionTrait
{
    public function tickets()
    {
        return $this->belongsTo(ConfigHelper::getModel());
    }

    public function users()
    {
        return $this->belongsTo(
            ConfigHelper::getUserModel(),
            ConfigHelper::getUserRelationKey(),
            'id'
        );
    }
}