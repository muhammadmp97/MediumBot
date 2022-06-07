<?php

use TeleBot\TeleBot;

require_once '../vendor/autoload.php';

$settings = json_decode(file_get_contents('../resources/settings.json'));

try {
    $tg = new TeleBot($settings->bot_token);

    if ($tg->user->id == $settings->owner_id) {
        $tg->copyMessage([
            'from_chat_id' => $settings->owner_id,
            'chat_id' => $tg->message->reply_to_message->forward_from->id,
            'message_id' => $tg->message->message_id,
            'protect_content' => $settings->protect_content,
        ]);
    } else {
        $tg->forwardMessage([
            'from_chat_id' => $tg->user->id,
            'chat_id' => $settings->owner_id,
            'message_id' => $tg->message->message_id,
        ]);
    }
} catch (Throwable $th) {
    $text = "‼️ <b>Something went wrong</b>\n\n";
    $text .= "🔻 <b>Message:</b> {$th->getMessage()}\n";
    $text .= "📃 <b>File:</b> <code>{$th->getFile()}</code>\n";
    $text .= "⤵️ <b>Line:</b> {$th->getLine()}";

    $tg->sendMessage([
        'chat_id' => $settings->owner_id,
        'text' => $text,
        'parse_mode' => 'html',
    ]);
}
