<?php

namespace App\Controllers\Web;

use App\Services\Telegram;
use Longman\TelegramBot\Request;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;
use Vesp\Services\Eloquent;

class Test extends Controller
{
    protected Telegram $telegram;

    public function __construct(Eloquent $eloquent, Telegram $telegram)
    {
        parent::__construct($eloquent);
        $this->telegram = $telegram;
    }

    public function get(): ResponseInterface
    {
        $response = Request::getMe();
        if ($response->isOk()) {
            return $this->success($response->getResult());
        }

        return $this->failure($response->getDescription(), $response->getErrorCode());
    }
}