<?php

// LINE_CONSTANT
define('CHANNEL_ACCESS_TOKEN', '');
define('CHANNEL_SECRET', '');

if (empty(CHANNEL_ACCESS_TOKEN)) {
    Util::error('CHANNEL_ACCESS_TOKEN is required');
}

if (empty(CHANNEL_SECRET)) {
    Util::error('CHANNEL_SECRET is required');
}
