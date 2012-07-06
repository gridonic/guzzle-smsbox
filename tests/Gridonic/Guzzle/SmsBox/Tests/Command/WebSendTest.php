<?php

namespace Gridonic\Guzzle\SmsBox\Tests\Command;

use Gridonic\Guzzle\SmsBox\Common\SmsBoxException;
use Gridonic\Guzzle\SmsBox\Common\SmsBoxXmlException;

use Guzzle\Service\Exception\ValidationException;

/**
 * Main client test
 * @todo  Comment
 */
class WebsendTest extends \Guzzle\Tests\GuzzleTestCase
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
        $this->command = $this->client->getCommand('websend', array(
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
        $response_body = $this->command->getResponse()->getBody();

        $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxResponse', $result);
        $this->assertFalse($result->hasError());
        $this->assertNull($result->getErrorType());
        $this->assertEquals($response_body, $this->command->getResponseBody(false));
        $this->assertEquals(htmlentities($response_body), $this->command->getResponseBody(true));
    }

    /**
     * Testing a wrong content type in the reponse.
     */
    public function testWrongContentType() {
        $this->client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($this->client, 'websend/failure_wrong_content_type');

        $this->setupCommand();

        try {
            $this->client->execute($this->command);
        } catch (SmsBoxException $e) {
            $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxException', $e, 'Wrong exception class for invalid content types.');
            return;
        }

        $this->fail('An exception has to be thrown for non-xml content types in the response.');
    }

    /**
     * Tests a wrong XML content as a request to the API
     */
    public function testCommandXmlParseError() {
        $this->client = $this->getServiceBuilder()->get('test.smsbox');
        $this->setMockResponse($this->client, 'websend/failure_xml_parse_content');

        $this->setupCommand();

        $this->command->prepare();
        $this->command->getRequest()->setBody('Some invalid content as the request body...');

        try {
            $result = $this->client->execute($this->command);
        } catch (SmsBoxXmlException $e) {
            $result = $this->command->getResult();

            $this->assertTrue($result->hasError(), 'Sending an invalid XML needs to have the error set');
            $this->assertInstanceOf('Gridonic\Guzzle\SmsBox\Common\SmsBoxXmlException', $e, 'Wrong exception class for invalid XML content');
            $this->assertEquals($e->getErrorType(), 'xmlparseerror');
            $this->assertEquals($e->getMessage(), 'Parse error at line 1: Content is not allowed in prolog.');
            $this->assertNotEquals($e->getMessage(), 'The submitted XML document is not valid.');

            return;
        }

        $this->fail('An exception has to be thrown for invalid XML content in the request.');
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
        } catch (SmsBoxXmlException $e) {
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

    /**
     * Tests the wrong receiver parameter format.
     */
    public function testWrongReceiver() {
        $this->client = $this->getServiceBuilder()->get('test.smsbox');
        $this->command = $this->client->getCommand('websend', array(
            'command'  => 'websend',
            'service'  => 'TEST',
            'receiver' => '123456789',
            'text'     => 'Test message',
        ));

        try {
            $this->command->prepare();
        } catch (ValidationException $e) {
            $this->assertInstanceOf('Guzzle\Service\Exception\ValidationException', $e, 'Wrong exception class for invalid receiver parameter');
            $this->assertEquals($e->getMessage(), 'Validation errors: receiver: 123456789 does not match the regular expression');

            return;
        }

        $this->fail('A wrong receiver format needs to throw a validation exception');
    }
}