<?php

use TeleBot\TeleBot;

require_once '../vendor/autoload.php';

$settings = json_decode(file_get_contents('../resources/settings.json'));

try {
    $tg = new TeleBot($settings['bot_token']);
} catch (Throwable $th) {
    $text = "‼️ <b>Something went wrong</b>\n\n";
    $text .= "🔻 <b>Message:</b> {$th->getMessage()}\n";
    $text .= "📃 <b>File:</b> <code>{$th->getFile()}</code>\n";
    $text .= "⤵️ <b>Line:</b> {$th->getLine()}";

    $tg->sendMessage([
        'chat_id' => $settings['owner_id'],
        'text' => $text,
        'parse_mode' => 'html',
    ]);
}
