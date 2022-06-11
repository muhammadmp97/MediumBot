<?php

use TeleBot\TeleBot;

TeleBot::extend('isReply', function () {
    return property_exists($this->message, 'reply_to_message');
});
