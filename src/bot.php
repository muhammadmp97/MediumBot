<?php

use TeleBot\Exceptions\TeleBotException;
use TeleBot\TeleBot;

require_once '../vendor/autoload.php';

try {
    $settings = json_decode(file_get_contents('../resources/settings.json'));
    $strings = json_decode(file_get_contents("../resources/translations/{$settings->language}.json"));

    $tg = new TeleBot($settings->bot_token);

    $tg->listen('delete_%d_%d', function ($chatId, $messageId) use ($tg, $settings, $strings) {
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
                'text' => $strings->message_deleted,
            ]);
        }
    });

    if ($tg->user->id == $settings->owner_id) {
        try {
            $messageId = $tg->copyMessage([
                'from_chat_id' => $settings->owner_id,
                'chat_id' => $tg->message->reply_to_message->forward_from->id,
                'message_id' => $tg->message->message_id,
                'protect_content' => $settings->protect_content,
            ]);
        } catch (TeleBotException $e) {
            if ($e->getMessage() == 'Forbidden: bot was blocked by the user') {
                $tg->sendMessage([
                    'chat_id' => $settings->owner_id,
                    'reply_to_message_id' => $tg->message->message_id,
                    'text' => $strings->blocked_by_user,
                ]);

                die;
            }
        }

        if (property_exists($messageId, 'message_id')) {
            $tg->sendMessage([
                'chat_id' => $settings->owner_id,
                'reply_to_message_id' => $tg->message->message_id,
                'text' => $strings->message_sent,
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
        $command = $tg->hasCallbackQuery() ?
            $tg->update->callback_query->data :
            $tg->update->message->text;

        $text = "‼️ <b>Something went wrong</b>\n\n";
        $text .= "💬 <b>Command:</b> {$command}\n";
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
