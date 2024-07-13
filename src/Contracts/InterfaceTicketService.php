<?php

namespace Aqayepardakht\TicketManager\Contracts;

use Illuminate\Http\UploadedFile;

interface InterfaceTicketService
{
    public function createTicket($request);

    public function saveReply($request, $status = null, $replyId = null);

    public function updateTicket(array $data);

    public function loadTicketById(int $id);

    public function loadReplyById(int $id);

    public function loadAllTickets($deleted);

    public function uploader(UploadedFile $file, int $localId);
}
