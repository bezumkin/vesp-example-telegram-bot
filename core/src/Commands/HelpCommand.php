<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class HelpCommand extends UserCommand
{
    protected $name = 'help';
    protected $description = 'Вывод сообщения со списком команд';
    protected $usage = '/help';
    protected $private_only = true;

    public function execute(): ServerResponse
    {
        $data = [
            'Вот все доступные команды:',
            '',
        ];

        /** @var UserCommand[] $commands */
        $commands = $this->telegram->getCommandsList();
        foreach ($commands as $command) {
            if ($command->showInHelp() && $command->getUsage()) {
                $data[] = $command->getUsage() . ' ' . $command->getDescription();
            }
        }

        return $this->replyToChat(implode(PHP_EOL, $data));
    }
}