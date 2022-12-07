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

    // UnSet webhook
    // Unset / delete the webhook
    $result = $telegram->deleteWebhook();

    echo $result->getDescription();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     echo $e->getMessage();
}
