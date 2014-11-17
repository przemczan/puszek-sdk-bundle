<?php

namespace Przemczan\PuszekSDKBundle\Twig;

use Przemczan\PuszekSDKBundle\Service\SocketHelper;

class SocketHelperExtension extends \Twig_Extension
{
    /**
     * @var SocketHelper
     */
    protected $socketHelper;

    /**
     * @param SocketHelper $socketHelper
     */
    public function __construct(SocketHelper $socketHelper)
    {
        $this->socketHelper = $socketHelper;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('przemczan_puszek_socket_url', array($this, 'getSocketUrl')),
        ];
    }

    /**
     * @param $receiver
     * @param array $subscribe
     * @param int $expire
     * @return string
     */
    public function getSocketUrl($receiver, array $subscribe, $expire = 600)
    {
        return $this->socketHelper->getSocketUrl($receiver, $subscribe, $expire);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'przemczan_puszek_sdk_socket';
    }
}