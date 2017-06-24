<?php
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;

$httpClient = new CurlHTTPClient(
        '');
$bot = new LINEBot($httpClient,
        [
            'channelSecret' => ''
        ]);

$signature = $_SERVER['HTTP_' . HTTPHeader::LINE_SIGNATURE];

try {
    $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch (\LINE\LINEBot\Exception\InvalidSignatureException $e) {
    error_log(
            'parseEventRequest failed. InvalidSignatureException => ' .
                     var_export($e, true));
} catch (\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
    error_log(
            'parseEventRequest failed. UnknownEventTypeException => ' .
                     var_export($e, true));
} catch (\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
    error_log(
            'parseEventRequest failed. UnknownMessageTypeException => ' .
                     var_export($e, true));
} catch (\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
    error_log(
            'parseEventRequest failed. InvalidEventRequestException => ' .
                     var_export($e, true));
}

foreach ($events as $event) {
    $bot->replyText($event->getReplyToken(), 'aieo');
}
// $response = $bot->replyText($replyToken, $text)
