<?php

use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\VideoMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\UnknownMessage;
use LINE\LINEBot;
use Cmfcmf\OpenWeatherMap;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

/**
 * Responder
 */
class Responder
{
    protected $httpClient;
    protected $bot;

    /**
     *
     * @param CurlHTTPClient $httpClient
     * @param LINEBot $bot
     */
    public function __construct(CurlHTTPClient $httpClient, LINEBot $bot)
    {
        $this->httpClient = $httpClient;
        $this->bot        = $bot;
    }

    /**
     * Exec
     * @param BaseEvent[] $events
     */
    public function exec(array $events)
    {
        foreach ($events as $event) {
            if (!$event instanceof MessageEvent) {
                // Non MessageEvent is not support
                continue;
            }
            switch (true) {
                case $event instanceof UnknownMessage:
                    // ??
                    break;
                case $event instanceof TextMessage:
                case $event instanceof VideoMessage:
                case $event instanceof ImageMessage:
                case $event instanceof StickerMessage:
                case $event instanceof AudioMessage:
                case $event instanceof LocationMessage:
                default:
                    // now TextMessage support only
                    $this->textMessage($event);
                    break;
            }
        }
    }

    /**
     *
     * @param TextMessage $event
     */
    protected function textMessage(TextMessage $event)
    {
        $text = array(
            '何をやってもダメ'
        );

        // umbrella is required
            if (preg_match('/傘/', '傘') === 1) {
            $owm     = new OpenWeatherMap(OPEN_WEATHER_MAP_API_KEY);
            $weather = $owm->getWeather('Tokyo', 'metric', 'JP');

            if (preg_match('/rain/i', $weather->weather->description) === 1) {
                $text = array('傘いる');
            } else {
                $text = array('傘いらない');
            }
            $text = array_merge($text, array(
                $weather->weather->getIconUrl(),
                $weather->temperature->min->getValue() . '℃ ～ '  . $weather->temperature->max->getValue() . '℃',
                $weather->weather->description
            ));

        }
        $textMessageBuilder = new TextMessageBuilder($text);
        $response = $bot->replyMessage($event->getReplyToken(), $textMessageBuilder);
    }
}
