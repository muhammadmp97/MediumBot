<?php

use TeleBot\TeleBot;

require_once '../vendor/autoload.php';

$settings = json_decode(file_get_contents('../resources/settings.json'));

try {
    $tg = new TeleBot($settings->bot_token);

    $tg->listen('delete_%d_%d', function ($chatId, $messageId) use ($tg, $settings) {
        if ($tg->user->id != $settings->owner_id) {
            return;
        }

        $result = $tg->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);

        if ($result) {
            $tg->editMessageText([
                'chat_id' => $tg->user->id,
                'message_id' => $tg->message->message_id,
                'text' => 'This message was deleted!',
            ]);
        }
    });

    if ($tg->user->id == $settings->owner_id) {
        $messageId = $tg->copyMessage([
            'from_chat_id' => $settings->owner_id,
            'chat_id' => $tg->message->reply_to_message->forward_from->id,
            'message_id' => $tg->message->message_id,
            'protect_content' => $settings->protect_content,
        ]);

        if (property_exists($messageId, 'message_id')) {
            $tg->sendMessage([
                'chat_id' => $settings->owner_id,
                'reply_to_message_id' => $tg->message->message_id,
                'text' => 'Your message has been sent!',
                'reply_markup' => getUndoKeyboard($tg->message->reply_to_message->forward_from->id, $messageId->message_id),
            ]);
        }
    } else {
        $tg->forwardMessage([
            'from_chat_id' => $tg->user->id,
            'chat_id' => $settings->owner_id,
            'message_id' => $tg->message->message_id,
        ]);
    }
} catch (Throwable $th) {
    if ($settings->debug_mode) {
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
}
