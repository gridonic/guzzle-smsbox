<?php

namespace Gridonic\Guzzle\SmsBox\Tests\Command;

/**
 * General XML request command test.
 */
class XmlRequestCommandTest extends \Guzzle\Tests\GuzzleTestCase
{
    /**
     * Test general command properties
     * @return [type] [description]
     */
    public function testCommandProperties() {
        $client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($client, 'websend/success');

        // example command, websend
        $command = $client->getCommand('websend', array(
            'cost'     => 20,
            'service'  => 'TEST',
            'receiver' => '+41790000000',
            'text'     => 'Test message',
        ));
        $command->prepare();

        // test method and resource
        $this->assertEquals('http://biz.smsbox.ch:8047/723/sms/xml', $command->getRequest()->getUrl());
        $this->assertEquals('POST', $command->getRequest()->getMethod());
        $this->assertInstanceOf('\DOMDocument', $command->getRequestXml());
        $this->assertNull($command->getRequestXml()->getElementsByTagName('test')->item(0));
    }

    /**
     * Tests if the exception is thrown when accessing the request XML without
     * preparing the command beforehand.
     *
     * @expectedException Guzzle\Service\Exception\CommandException
     */
    public function testGetRequestXMLException() {
        $client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($client, 'websend/success');

        // example command, websend
        $command = $client->getCommand('websend', array(
            'cost'     => 20,
            'service'  => 'TEST',
            'receiver' => '+41790000000',
            'text'     => 'Test message',
        ));

        $command->getRequestXml();
    }
}