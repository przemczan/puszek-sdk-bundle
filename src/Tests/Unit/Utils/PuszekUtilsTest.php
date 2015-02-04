<?php

namespace Przemczan\PuszekSdkBundle\Tests\Unit\Utils;

use Przemczan\PuszekSdkBundle\Utils\PuszekUtils;

class PuszekUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'servers' => [
                'api' => [
                    'host' => 'localhost',
                    'port' => '5001',
                    'use_ssl' => false,
                ],
                'socket' => [
                    'host' => 'localhost',
                    'port' => '5001',
                    'protocol' => 'ws',
                ]
            ],
            'client' => [
                'name' => 'name',
                'key' => 'key'
            ]
        ];
    }

    public function testHash()
    {
        $utils = new PuszekUtils($this->getConfig());
        $this->assertEquals('f6a69294790ffec7c03f42d9a4211a86dd6f3809', $utils->hash('test'));
        $this->assertEquals('7118b7bf6c203357f4d326c0b3cdd05e7adf82f6', $utils->hash('abcd1234'));
        $this->assertEquals('a62f2225bf70bfaccbc7f1ef2a397836717377de', $utils->hash(null));
        $object = new TestObject();
        $this->assertEquals('71e12aed58e898a24fd8a9c13751a242c5d09335', $utils->hash($object));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode Przemczan\PuszekSdkBundle\Utils\PuszekUtils::ERROR_INVALID_HASH_DATA
     */
    public function testHashInvalidData()
    {
        $utils = new PuszekUtils($this->getConfig());
        $utils->hash([]);
    }

    /**
     *
     */
    public function testGetSocketUrl()
    {
        $utils = new PuszekUtils($this->getConfig());
        $this->assertRegExp(
            '#ws://localhost:5001/hash:[\w]+\?receiver=receiver&subscribe%5B0%5D=receiver1&subscribe%5B1%5D=user2&client=name&expire=#',
            $utils->getSocketUrl('receiver', ['receiver1', 'user2'], 450)
        );
    }
}

class TestObject
{
    public function __toString()
    {
        return 'object';
    }
}