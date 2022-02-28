<?php

use App\Services\Telegram;

require dirname(__DIR__) . '/bootstrap.php';

try {
    $telegram = new Telegram();
    $telegram->useGetUpdatesWithoutDatabase();
    $telegram->handleGetUpdates();
} catch (Throwable $e) {
    echo $e->getMessage();
}