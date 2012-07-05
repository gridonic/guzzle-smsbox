<?php

namespace Gridonic\Guzzle\SmsBox\Tests\Command;

/**
 * Main client test
 * @todo  Comment
 */
class WebSendTest extends \Guzzle\Tests\GuzzleTestCase
{
    /**
     * [$command description]
     * @var [type]
     */
    protected $command;

    /**
     * [$client description]
     * @var [type]
     */
    protected $client;


    /**
     * Sets up the client
     */
    protected function setUpClient() {
        $this->client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($this->client, 'websend/success');
    }

    /**
     * Sets up the command to perform
     */
    protected function setUpCommand() {
        $this->command = $this->client->getCommand('xml_request_command', array(
            'command'  => 'websend',
            'service'  => 'TEST',
            'receiver' => '+41790000000',
            'text'     => 'Test message',
        ));
        $this->command->prepare();
    }

    /**
     * [testCommandProperties description]
     * @return [type] [description]
     */
    public function testCommandProperties() {
        $this->setupClient();
        $this->setupCommand();

        // test method and resource
        $this->assertEquals('http://biz.smsbox.ch:8047/723/sms/xml', $this->command->getRequest()->getUrl());
        $this->assertEquals('POST', $this->command->getRequest()->getMethod());
    }

    /**
     * [testSuccessfull description]
     * @return [type] [description]
     */
    public function testSuccessfull() {
        $this->setupClient();
        $this->setupCommand();

        $this->client->execute($this->command);
        $result = $this->command->getResult();

        $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxResponse', $result);
        $this->assertFalse($result->hasError());
    }

    /**
     * [testCommandNoAccess description]
     * @return [type] [description]
     */
    public function testCommandNoAccess() {
        $this->client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($this->client, 'websend/failure_wrong_command');

        $this->setupCommand();

        try {
            $this->client->execute($this->command);
        } catch (\Exception $e) {
            $result = $this->command->getResult();

            $this->assertTrue($result->hasError(), 'Accessing a wrong command needs to have the error set');
            $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxXmlException', $e, 'Wrong exception class for invalid commands');
            $this->assertEquals($e->getErrorType(), 'commandnoaccess');
            $this->assertEquals($e->getMessage(), 'You do not have access to the requested command.');
            $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxResponse', $e->getResponse());

            return;
        }

        $this->fail('An exception should be thrown for invalid commands.');
    }
}