<?php
// Load VESP Environments
require dirname(__DIR__) . '/bootstrap.php';

$bot_api_key  = getenv('TG_ACCESS_TOKEN');
$bot_username = getenv('TG_BOT_USERNAME');
$hook_url     = getenv('TG_HOOK_URL');
echo $bot_api_key.'!'.PHP_EOL;
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     echo $e->getMessage();
}
