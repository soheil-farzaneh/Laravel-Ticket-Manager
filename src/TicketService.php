<?php

namespace Aqayepardakht\TicketManager;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Aqayepardakht\TicketManager\Utils\AuthHelper;
use Aqayepardakht\TicketManager\Utils\ConfigHelper;
use Aqayepardakht\TicketManager\Contracts\InterfaceTicketService;
use Aqayepardakht\TicketManager\Exceptions\TicketClosedException;

class TicketService implements InterfaceTicketService
{

    public $ticketModel;
    public $replyModel;

    public function __construct(protected HandlerRequest $handlerRequest)
    {
        $this->initializeModel();
    }

    private function initializeModel()
    {
        $modelNamespace = ConfigHelper::getModel();
        $this->ticketModel = new $modelNamespace();
    }

    public function createTicket($request)
    {

        try {

            $ticketData        = $this->handlerRequest->getTicketData($request);
            $this->ticketModel = $this->ticketModel->registerTicket($ticketData);

            $this->saveReply($request, 'waiting');

            return $this->ticketModel;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function updateTicket(array $data)
    {
        try {
            return $this->ticketModel->updateTicketFields($data);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function saveReply($request, $status = null, $replyId = null)
    {
        try {
            $actionMethod = $replyId ? 'updateReplyFields' : 'registerReply';

            $this->ensureTicketNotClosed();

            $this->changeTicketStatus($status);

            $replyValues = $this->handlerRequest->getReplyData($request);

            $this->manageReply($replyValues, $actionMethod, $replyId);

            return $this->replyModel;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    protected function manageReply($replyValues, $actionMethod, $replyId = null)
    {
        $replyWithoutFiles = isset($replyValues[ConfigHelper::getFileField()])
            ? Arr::except($replyValues, [ConfigHelper::getFileField()])
            : $replyValues;

        $params = $replyId ? [$replyId, $replyWithoutFiles] : [$replyWithoutFiles];
        $this->replyModel = call_user_func_array([$this->ticketModel, $actionMethod], $params);

        if (isset($replyValues[ConfigHelper::getTextField()])) {
            $replyValues = Arr::except($replyValues, [ConfigHelper::getTextField()]);
        }

        FileManagerService::manageFile($this->replyModel, $this->ticketModel, $replyValues);
    }

    public function loadTicketById(int $id)
    {
        try {
            $this->ticketModel = $this->ticketModel->getTicketById($id);

            return $this;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function loadReplyById(int $id)
    {
        try {
            return $this->ticketModel->getReplyById($id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function loadAllTickets($deleted = false)
    {
        return $this->ticketModel::allTickets($deleted);
    }

    private function changeTicketStatus($status)
    {
        $status = $status ?? (AuthHelper::getCreatedBy() == 'admin' ? 'answered' : 'waiting');
        $this->updateTicket([ConfigHelper::getStatusField() => $status]);
    }

    private function ensureTicketNotClosed()
    {
        if (!AuthHelper::isUserAdmin() && $this->ticketModel->{ConfigHelper::getStatusField()} == 'closed') throw new TicketClosedException();
    }

    public function uploader(UploadedFile $file, int $localId)
    {
        $name = "{$localId}.{$file->hashName()}";
        $file->storeAs(ConfigHelper::getAttachmentPath() . "/", $name);

        return $name;
    }
}