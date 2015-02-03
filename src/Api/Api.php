<?php

namespace Przemczan\PuszekSdkBundle\Api;

use GuzzleHttp\Client;
use Przemczan\PuszekSdkBundle\Logger\LoggerInterface;
use Przemczan\PuszekSdkBundle\Utils\PuszekUtils;

class Api {

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var PuszekUtils
     */
    protected $puszekUtils;

    /**
     * @var SubscriberInterface
     */
    protected $logger;

    /**
     * @param array $config
     * @param PuszekUtils $puszekUtils
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $config, PuszekUtils $puszekUtils, LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->puszekUtils = $puszekUtils;
        $this->logger = $logger;

        $this->apiBaseUrl = sprintf(
            'http%s://%s:%d',
            $config['servers']['api']['use_ssl'] ? 's' : '',
            trim($config['servers']['api']['host'], '/\\'),
            $this->config['servers']['api']['port']
        );
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        static $client;

        if (!$client) {
            $client = new Client([
                'base_url' => $this->apiBaseUrl
            ]);
            if ($this->logger) {
                $client->getEmitter()->attach($this->logger);
            }
        }

        return $client;
    }

    /**
     * @param $path
     * @param array $params
     * @param string $method
     * @param array $requestOptions
     * @return \GuzzleHttp\Message\RequestInterface
     */
    public function getRequest($path, array $params = [], $method = 'GET', $requestOptions = [])
    {
        $url = trim($path . '?' . http_build_query($params), '?');
        return $this->getClient()->createRequest($method, $url, $requestOptions);
    }

    /**
     * @param $path
     * @param array $data
     * @param string $method
     * @param array $params
     * @param array $requestOptions
     * @return mixed|null
     */
    public function getData($path, array $data = null, $method = 'POST', array $params = [], $requestOptions = [])
    {
        $requestOptions = array_merge_recursive(
            [
                'body' => json_encode($data),
                'headers' => [
                    'puszek-client-name' => $this->config['client']['name'],
                    'Content-type' => 'application/json',
                ]
            ],
            $requestOptions
        );
        $request = $this->getRequest($path, $params, $method, $requestOptions);
        $hash = $this->puszekUtils->hash($request->getBody());
        $request->setHeader('puszek-security-hash', $hash);

        return $this->getClient()->send($request)->json();
    }

    /**
     * @param $sender
     * @param $message
     * @param $receivers
     * @return mixed|null
     */
    public function sendMessage($sender, $message, array $receivers)
    {
        $data = [
            'receivers' => array_unique(array_map('trim', $receivers)),
            'message' => is_string($message) ? $message : json_encode($message),
            'sender' => $sender,
        ];

        return $this->getData('send-message', $data);
    }
}
