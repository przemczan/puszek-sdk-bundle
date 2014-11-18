<?php

namespace Przemczan\PuszekSdkBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class API {

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->apiBaseUrl = sprintf(
            'http%s://%s:%d',
            $config['server']['api']['use_ssl'] ? 's' : '',
            trim($config['server']['api']['host'], '/\\'),
            $this->config['server']['api']['port']
        );
    }

    protected function getClient()
    {
        static $client;

        if (!$client) {
            $client = new Client([
                    'base_url' => $this->apiBaseUrl
                ]);
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
        try {
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
            $request->setHeader('puszek-security-hash', sha1($this->config['client']['key'] . $request->getBody()));
            $response = $this->getClient()->send($request);

            return $response->json();
        } catch (RequestException $e) {
            return $e->getResponse()->json();
        }
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
            'receivers' => $receivers,
            'message' => $message,
            'sender' => $sender,
        ];

        return $this->getData('send-message', $data);
    }
}
