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
     * @var string
     */
    protected $name;

    /**
     * @param PsrLoggerInterface $logger
     */
    public function __construct(PsrLoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    protected function formatMessage()
    {
        return trim(sprintf('%s [%s]', $this->name, join(', ', func_get_args())));
    }

    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event)
    {
        if ($this->logger) {
            $request = $event->getRequest();
            $this->logger->info($this->formatMessage($request->getMethod(), $request->getUrl()), [
                'requestHeaders' => $request->getHeaders(),
                'requestBody' => (string)$request->getBody()
            ]);
        }
    }

    /**
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event)
    {
        if ($this->logger) {
            $request = $event->getRequest();
            $response = $event->getResponse();
            $responseBody = $response ? (string)$response->getBody() : null;
            $responseHeaders = $response ? $response->getHeaders() : null;

            $this->logger->info($this->formatMessage($request->getMethod(), $request->getUrl()), [
                'requestHeaders' => $request->getHeaders(),
                'requestBody' => (string)$request->getBody(),
                'responseHeaders' => $responseHeaders,
                'responseBody' => $responseBody,
            ]);
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