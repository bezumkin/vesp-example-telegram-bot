<?php

namespace App\Commands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends \Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand
{
    public function execute(): ServerResponse
    {
        $callback = $this->getCallbackQuery();
        $message = $callback->getMessage();

        if ($data = explode('::', $callback->getData())) {
            $command = array_shift($data);
            if (strpos($command, '/') === 0) {
                return $this->getTelegram()->executeCommand(substr($command, 1));
            }
        }

        return Request::sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $data,
        ]);
    }
}