<?php

namespace Aqayepardakht\TicketManager\Traits;

use Aqayepardakht\TicketManager\Utils\ConfigHelper;
use Aqayepardakht\TicketManager\Exceptions\ReplyNotFoundException;
use Aqayepardakht\TicketManager\Exceptions\TicketNotFoundException;

trait TicketInteractionTrait
{
    public function replies()
    {
        return $this->hasMany(
            ConfigHelper::getReplyModel(),
            ConfigHelper::getTicketRelationKey(),
            'id'
        );
    }

    public function users()
    {
        return $this->hasMany(
            ConfigHelper::getUserModel(),
            ConfigHelper::getUserRelationKey(),
            'id'
        );
    }

    public function registerTicket(array $ticketData)
    {
        return $this->create($ticketData);
    }

    public function registerReply(array $replyData)
    {
        return $this->replies()->create($replyData);
    }

    public function saveFiles(array $filesData)
    {
        foreach($filesData as $file){
            $this->replies()->create($file);
        }
    }

    public function getTicketById(int $id)
    {
        $ticketModel = $this->withTrashed()->find($id);
        if (!$ticketModel) {
            throw new TicketNotFoundException();
        }

        return $ticketModel;
    }

    public function getReplyById(int $id)
    {
        $replyModel = $this->replies()
            ->withTrashed()
            ->find($id);
        if (!$replyModel) {
            throw new ReplyNotFoundException();
        }

        return $replyModel;
    }

    public function getFilesWithParentId(int $replyId)
    {
        return $this->replies()->withTrashed()
            ->where('parent_id', $replyId)
            ->pluck('file', 'id');
    }

    public function updateTicketFields(array $ticketData)
    {
        return $this->update($ticketData);
    }

    public function updateReplyFields($replyId, array $replyData)
    {
        $replyModel = $this->getReplyById($replyId);
        $replyModel->update($replyData);

        return $replyModel;
    }

    public function scopeAllTickets($query, $deleted)
    {
        if ($deleted) {
            return $query->onlyTrashed()->latest();
        }
        return $query->where('deleted_at', null)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'closed');
    }

    public function scopeUserTickets($query, int $userId)
    {
        return $query->where(
            ConfigHelper::getUserRelationKey(),
            $userId
        );
    }

    public function getPaginate($query, $perPage = null)
    {
        return $query->paginate($perPage);
    }
}