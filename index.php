<?php

require_once './vendor/autoload.php';
require_once 'config.php';

use LINE\LINEBot\Constant\HTTPHeader;

$signature = $_SERVER['HTTP_' . HTTPHeader::LINE_SIGNATURE];

try {
    /*
    $events    = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
    $responder = new res($events);
    */

    $responder = new Responder(CHANNEL_ACCESS_TOKEN, CHANNEL_SECRET, $events);
    $responder->exec();
} catch (Exception $ex) {
    Util::error($ex);
}
