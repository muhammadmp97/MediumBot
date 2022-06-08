<?php

use TeleBot\InlineKeyboard;

function getUndoKeyboard($chatId, $messageId) {
    (new InlineKeyboard())
        ->addCallbackButton('❌ Undo', "delete_{$chatId}_{$messageId}")
        ->get();
}
