<?php

namespace Przemczan\PuszekSdkBundle\Utils;


class PuszekUtils
{
    const ERROR_INVALID_HASH_DATA = 1;

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
            $config['servers']['socket']['protocol'],
            trim($config['servers']['socket']['host'], '/\\'),
            $this->config['servers']['socket']['port']
        );
    }

    /**
     * @param string $receiver
     * @param array $subscribe
     * @param integer $expire
     * @return mixed|null
     */
    public function getSocketUrl($receiver, array $subscribe, $expire = 600)
    {
        $params = [
            'receiver' => $receiver,
            'subscribe' => array_unique(array_map('trim', $subscribe)),
            'client' => $this->config['client']['name'],
            'expire' => (time() + (int)$expire) * 1000,
        ];
        $params = http_build_query($params);
        $hash = $this->hash($params);

        return sprintf('%s/hash:%s?%s', $this->baseUrl, $hash, $params);
    }

    /**
     * @param string $string
     * @return string
     */
    public function hash($string)
    {
        if (!is_string($string) or !method_exists($string, '__toString') or !is_null($string)) {
            throw new \InvalidArgumentException('Invalid has data', self::ERROR_INVALID_HASH_DATA);
        }

        return sha1($this->config['client']['key'] . (string)$string);
    }
}
