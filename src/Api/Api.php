<?php

namespace Przemczan\PuszekSdkBundle\Api;

class Api
{
    const ERROR_SEND_MESSAGE_INVALID_SENDER = 1;
    const ERROR_SEND_MESSAGE_NO_RECEIVERS   = 2;

    /**
     * @var ApiConnector
     */
    protected $apiConnector;

    /**
     * @param ApiConnector $apiConnector
     */
    public function __construct(ApiConnector $apiConnector)
    {
        $this->apiConnector = $apiConnector;
    }

    /**
     * @param string $sender
     * @param string $message
     * @param array $receivers
     * @return mixed|null
     */
    public function sendMessage($sender, $message, array $receivers)
    {
        if (!is_string($sender)) {
            throw new \InvalidArgumentException(
                sprintf('Sender has to be a string, got "%s"', gettype($sender)),
                self::ERROR_SEND_MESSAGE_INVALID_SENDER
            );
        }

        $receivers = array_diff(array_unique(array_map('trim', $receivers)), ['']);
        if (!count($receivers)) {
            throw new \InvalidArgumentException('Define at least one receiver.', self::ERROR_SEND_MESSAGE_NO_RECEIVERS);
        }

        $data = [
            'receivers' => $receivers,
            'message' => is_string($message) ? $message : json_encode($message),
            'sender' => $sender,
        ];

        return $this->apiConnector->getData('send-message', $data);
    }
}
