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
    /**
     *
     * @var CurlHTTPClient
     */
    protected $httpClient;

    /**
     *
     * @var LINEBot
     */
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
     * 実行
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
     * TextMessage
     * @param TextMessage $event
     */
    public function textMessage(TextMessage $event)
    {
        $text = $this->umbrella($event);
        if (empty($text)) {
            $text = ['何をやってもダメ'];
        }
        $textMessageBuilder = new TextMessageBuilder(implode("\n", $text));
        $response           = $this->bot->replyMessage($event->getReplyToken(), $textMessageBuilder);
        if (!$response->isSucceeded()) {
            Util::error($response->getJSONDecodedBody());
        }
    }

    /**
     * 傘必要可否
     * @param TextMessage $event
     * @return NULL || array
     */
    public function umbrella(TextMessage $event)
    {
        if (preg_match('/傘|かさ|kasa|カサ|ｶｻ/', $event->getText()) !== 1) {
            return null;
        }

        $owm     = new OpenWeatherMap(OPEN_WEATHER_MAP_API_KEY);
        $weather = $owm->getWeather('Tokyo', 'metric', 'JP');
        $weather->lastUpdate->setTimezone(new DateTimeZone('Asia/Tokyo'));

        $text = (preg_match('/rain/i', $weather->weather->description) === 1) ? ['傘いる'] : ['傘いらない'];
        $text = array_merge($text, array(
            //$weather->weather->getIconUrl(),
            $weather->temperature->min->getValue() . '℃ ～ '  . $weather->temperature->max->getValue() . '℃',
            $weather->weather->description,
            $weather->lastUpdate->format('Y-m-d H:i')
        ));
        return $text;
    }
}
