<?php

namespace Przemczan\PuszekSdkBundle\Twig;

use Przemczan\PuszekSdkBundle\Utils\PuszekUtils;

class PuszekExtension extends \Twig_Extension
{
    /**
     * @var PuszekUtils
     */
    protected $puszekUtils;

    /**
     * @param PuszekUtils $puszekUtils
     */
    public function __construct(PuszekUtils $puszekUtils)
    {
        $this->puszekUtils = $puszekUtils;
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
        return $this->puszekUtils->getSocketUrl($receiver, $subscribe, $expire);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'przemczan_puszek_sdk';
    }
}
