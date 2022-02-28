<?php

namespace App\Services;

class Telegram extends \Longman\TelegramBot\Telegram
{
    public function __construct()
    {
        parent::__construct(getenv('BOT_API_KEY'), getenv('BOT_USERNAME'));
        $this->addCommandsPath(BASE_DIR . 'core/src/Commands');
    }
}