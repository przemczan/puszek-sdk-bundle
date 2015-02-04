<?php

namespace Przemczan\PuszekSdkBundle\Tests\Unit\Api;

use Przemczan\PuszekSdkBundle\Api\Api;
use Przemczan\PuszekSdkBundle\Api\ApiConnector;
use Przemczan\PuszekSdkBundle\Utils\PuszekUtils;

class ApiTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getApiConnectorMock()
    {
        $config = $this->getConfig();
        $mock = $this->getMock(ApiConnector::class, [], [$config, new PuszekUtils($config), null]);

        return $mock;
    }

    public function testSendMessage()
    {
        $connectorMock = $this->getApiConnectorMock();
        $connectorMock
            ->expects($this->once())
            ->method('getData')
            ->with('send-message', ['receivers' => ['receiver1', 'receiver2'], 'message' => 'message', 'sender' => 'sender'])
            ->willReturn('response');

        $api = new Api($connectorMock);
        $response = $api->sendMessage('sender', 'message', ['receiver1', 'receiver2']);
        $this->assertEquals('response', $response);
    }

    public function testSendMessageWithJson()
    {
        $connectorMock = $this->getApiConnectorMock();
        $connectorMock
            ->expects($this->once())
            ->method('getData')
            ->with('send-message', ['receivers' => ['receiver1', 'receiver2'], 'message' => json_encode(['message']), 'sender' => 'sender'])
            ->willReturn('response');

        $api = new Api($connectorMock);
        $api->sendMessage('sender', ['message'], ['receiver1', 'receiver2']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode Przemczan\PuszekSdkBundle\Api\Api::ERROR_SEND_MESSAGE_INVALID_SENDER
     */
    public function testSendMessageInvalidSender()
    {
        $connectorMock = $this->getApiConnectorMock();
        $connectorMock
            ->expects($this->never())
            ->method('getData');

        $api = new Api($connectorMock);
        $api->sendMessage(123, 'message', ['receiver1']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode Przemczan\PuszekSdkBundle\Api\Api::ERROR_SEND_MESSAGE_INVALID_SENDER
     */
    public function testSendMessageInvalidSender2()
    {
        $connectorMock = $this->getApiConnectorMock();
        $connectorMock
            ->expects($this->never())
            ->method('getData');

        $api = new Api($connectorMock);
        $api->sendMessage([], 'message', ['receiver1']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode Przemczan\PuszekSdkBundle\Api\Api::ERROR_SEND_MESSAGE_NO_RECEIVERS
     */
    public function testSendMessageInvalidReceivers()
    {
        $connectorMock = $this->getApiConnectorMock();
        $connectorMock
            ->expects($this->never())
            ->method('getData');

        $api = new Api($connectorMock);
        $api->sendMessage('sender', 'message', ['']);
    }
}