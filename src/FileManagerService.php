<?php

namespace Aqayepardakht\TicketManager;

use Illuminate\Support\Facades\Storage;
use Aqayepardakht\TicketManager\Utils\ConfigHelper;
use Aqayepardakht\TicketManager\Exceptions\DontUploadFileException;

class FileManagerService
{

    public static function manageFile($replyModel, $ticketModel, $data)
    {
        try {
            $replyId        = $replyModel->id;
            $ticketId       = $replyModel->ticket_id;
            $existingFiles  = $ticketModel->getFilesWithParentId($replyId);

            if ($existingFiles) self::restoreDeletedFiles($ticketModel, $existingFiles);

            $filesToKeep    = [];
            $dataToInsert   = [];
            $seenFilePaths  = [];

            foreach ($data[ConfigHelper::getFileField()] as $file) {
                $filePath =  ConfigHelper::getAttachmentPath() . "/" . $file;

                if (!Storage::exists($filePath)) {
                    throw new DontUploadFileException();
                }

                if (!$existingFiles->contains($filePath) && !in_array($filePath, $seenFilePaths)) {
                    $dataToInsert[]  = self::prepareFileData($replyId, $ticketId, $filePath, $data);
                    $seenFilePaths[] = $filePath;
                } else {
                    $filesToKeep[] = $filePath;
                }
            }

            self::saveFiles($ticketModel, $dataToInsert);
            self::deleteOldFiles($replyModel, $existingFiles, $filesToKeep);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private static function saveFiles($ticketModel, array $dataToInsert)
    {
        if (!empty($dataToInsert)) {
            $ticketModel->saveFiles($dataToInsert);
        }
    }

    private static function deleteOldFiles($replyModel, $existingFiles, $filesToKeep)
    {
        $filesToDelete = $existingFiles->filter(function ($file) use ($filesToKeep) {
            return !in_array($file, $filesToKeep);
        });

        if ($filesToDelete->isNotEmpty()) {
            $replyModel->whereIn('id', array_keys($filesToDelete->toArray()))
                ->delete();
        }
    }

    private static function restoreDeletedFiles($ticketModel, $existingFiles)
    {
        foreach ($existingFiles->toArray() as $key => $value) {
            $replyModel = $ticketModel->getReplyById($key);
            if ($replyModel->deleted_at) {
                $replyModel->deleted_at = null;
                $replyModel->save();
            }
        }
    }

    private static function prepareFileData($replyId, $ticketId, $filePath, $data)
    {
        return [
            'file'                              => $filePath,
            ConfigHelper::getParentIdField()    => $replyId,
            ConfigHelper::getTicketId()         => $ticketId,
            ConfigHelper::getIpField()          => $data[ConfigHelper::getIpField()],
            ConfigHelper::getReplyUserIdField() => $data[ConfigHelper::getReplyUserIdField()],
            ConfigHelper::getCreatedByField()   => $data[ConfigHelper::getCreatedByField()],
        ];
    }
}