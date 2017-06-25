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
        // 傘判定
        // 次の電車の発車時刻2つ分
        // 買い物メモ(追加削除)
        // 実装して欲しいメモ登録
        Util::dump($event);
    }
}
