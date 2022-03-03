<?php

namespace App\Controllers\Web;

use App\Services\Telegram;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Vesp\Controllers\Controller;
use Vesp\Services\Eloquent;

class Webhook extends Controller
{
    protected Telegram $telegram;

    public function __construct(Eloquent $eloquent, Telegram $telegram)
    {
        parent::__construct($eloquent);
        $this->telegram = $telegram;
    }

    /*
    public function get(): ResponseInterface
    {
        // $response = $this->telegram->deleteWebhook();
        $url = (string)$this->request->getUri()->withQuery('');
        $response = $this->telegram->setWebhook();
        if ($response->isOk()) {
            $this->success($response->getResult());
        }

        return $this->failure($response->getDescription(), $response->getErrorCode() ?? 500);
    }
    */

    public function post(): ResponseInterface
    {
        try {
            $this->telegram->handle();
        } catch (Throwable $e) {
            // Можно логировать ошибки
        }

        return $this->success();
    }
}