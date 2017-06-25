<?php

require_once './vendor/autoload.php';
require_once 'config.php';

use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
try {
    $httpClient = new CurlHTTPClient(CHANNEL_ACCESS_TOKEN);
    $bot        = new LINEBot($httpClient, array(
        'channelSecret' => CHANNEL_SECRET
    ));

    $signature = $_SERVER['HTTP_' . HTTPHeader::LINE_SIGNATURE];
    $events    = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
    $responder = new Responder($httpClient, $bot);
    $responder->exec($events);
} catch (Exception $ex) {
    Util::error($ex);
}
