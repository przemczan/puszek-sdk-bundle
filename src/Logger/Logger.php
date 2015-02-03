<?php

namespace Przemczan\PuszekSdkBundle\Logger;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\ErrorEvent;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class Logger implements LoggerInterface
{
    /**
     * @var PsrLoggerInterface
     */
    protected $logger;

    /**
     * @param PsrLoggerInterface $logger
     */
    public function __construct(PsrLoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event)
    {
        if ($this->logger) {
            // TODO: implement
        }
    }

    /**
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event)
    {
        if ($this->logger) {
            // TODO: implement
        }
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