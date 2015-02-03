<?php

namespace Przemczan\PuszekSdkBundle\Logger;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\ErrorEvent;

class Logger implements LoggerInterface
{
    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event)
    {
        // TODO: implement
    }

    /**
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event)
    {
        // TODO: implement
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'before' => ['onBefore'],
            'error' => ['onError'],
        ];
    }
}