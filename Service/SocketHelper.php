<?php

namespace Przemczan\PuszekSDKBundle\Service;


class SocketHelper {

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

        $this->baseUrl = sprintf(
            '%s://%s:%d',
            $config['server']['socket']['protocol'],
            trim($config['server']['socket']['host'], '/\\'),
            $this->config['server']['socket']['port']
        );
    }

    /**
     * @param string $receiver
     * @param array $subscribe
     * @param integer $expire
     * @return mixed|null
     */
    public function getSocketUrl($receiver, array $subscribe, $expire)
    {
        $params = [
            'receiver' => $receiver,
            'subscribe' => $subscribe,
            'client' => $this->config['client']['name'],
            'expire' => (time() + (int)$expire) * 1000,
        ];
        $params = http_build_query($params);

        $hash = sha1($this->config['client']['key'] . $params);

        return sprintf('%s/hash:%s?%s', $this->baseUrl, $hash, $params);
    }
}
