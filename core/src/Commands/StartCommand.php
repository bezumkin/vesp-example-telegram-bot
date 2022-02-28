<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Запуск бота';
    protected $usage = '/start';

    public function execute(): ServerResponse
    {
        $user = $this->getMessage()->getFrom();
        $data = [
            'Привет, ' . ($user->getFirstName()) . '!',
            'Это тренировочный бот, написанный в целях обучения на https://bezumkin.ru/sections/vesp-telegram.',
            'На данный момент бот отвечает раз в минуту. Используй /help, чтобы увидеть все доступные команды.',
        ];

        return $this->replyToChat(implode(PHP_EOL . PHP_EOL, $data));
    }
}