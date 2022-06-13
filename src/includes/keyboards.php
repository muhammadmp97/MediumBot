<?php

use TeleBot\InlineKeyboard;

function getUndoKeyboard($title, $chatId, $messageId) {
    return (new InlineKeyboard())
        ->addCallbackButton($title, "delete_{$chatId}_{$messageId}")
        ->get();
}
