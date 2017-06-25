<?php

// LINE_CONSTANT
define('CHANNEL_ACCESS_TOKEN', '');
define('CHANNEL_SECRET', '');

define('OPEN_WEATHER_MAP_API_KEY', '');

if (empty(CHANNEL_ACCESS_TOKEN)) {
    Util::error('CHANNEL_ACCESS_TOKEN is required');
}

if (empty(CHANNEL_SECRET)) {
    Util::error('CHANNEL_SECRET is required');
}

if (empty(OPEN_WEATHER_MAP_API_KEY)) {
    Util::error('OPEN_WEATHER_MAP_API_KEY is required');
}
