<?php

use TeleBot\InlineKeyboard;

function getUndoKeyboard($chatId, $messageId) {
    return (new InlineKeyboard())
        ->addCallbackButton('❌ Undo', "delete_{$chatId}_{$messageId}")
        ->get();
}
