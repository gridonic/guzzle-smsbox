<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Gridonic\Guzzle\SmsBox\Common\SmsBoxResponse;
use Gridonic\Guzzle\SmsBox\Common\SmsBoxException;
use Gridonic\Guzzle\SmsBox\Common\SmsBoxXmlException;

use Guzzle\Service\Command\AbstractCommand;

/**
 * Abstract command implementing XML calls and XML responses
 */
abstract class AbstractXmlCommand extends AbstractCommand
{
    /**
     * Create the result of the command after the request has been completed.
     * We expect the response to be an XML, so this method converts the repons
     * to a SimpleXMLElement object. Also, exceptions are thrown accordingly.
     */
    protected function process()
    {
        // Uses the response object by default
        $this->result = $this->getRequest()->getResponse();

        $contentType = $this->result->getContentType();

        if (stripos($contentType, 'xml') === false) {
            throw new SmsBoxException('API response must have the Content-Type XML set.');
        }

        // save the response body as a sms box response
        $body = trim($this->result->getBody(true));
        $this->result = new SmsBoxResponse($body);

        $this->handleResponseErrors($this->result);
    }

    /**
     * Prepares the request to the API.
     */
    protected function build()
    {
        $xml = $this->buildXML()->saveXML();
        $this->request = $this->client->post(null, null, $xml);
    }

    /**
     * Builds the XML for the request body.
     * @return DOMDocument XML in DOMDocument format
     */
    protected function buildXML() {
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;

        $request = $xml->appendChild($xml->createElement('SMSBoxXMLRequest'));

        // add command, username and password params
        $username = $xml->createElement('username', $this->client->getConfig('username'));
        $password = $xml->createElement('password', $this->client->getConfig('password'));
        $command  = $xml->createElement('command', $this->get('command'));

        $request->appendChild($username);
        $request->appendChild($password);
        $request->appendChild($command);

        $params = $request->appendChild($xml->createElement('parameters'));

        // add parameters
        foreach ($this->getApiCommand()->getParams() as $name => $arg) {
            if ($name !== 'command') {
                if ($this->get($name) === null) {
                    $params->appendChild($xml->createElement($name));
                } else {
                    $params->appendChild($xml->createElement($name, $this->get($name)));
                }
            }
        }

        // add test param if client is in test mode
        if ($this->client->getConfig('test') === true) {
            $params->appendChild($xml->createElement('test'));
        }

        return $xml;
    }

    /**
     * Checks the XML response for errors.
     * @param  SmsBoxResponse $xml XML response
     */
    protected function handleResponseErrors($xmlResponse) {
        if ($xmlResponse->hasError()) {
            throw new SmsBoxXmlException($xmlResponse);
        }
    }

    /**
     * {@inheritdoc}
     * @return SmsBoxResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }

    /**
     * Returns the response body, by default with
     * encoded HTML entities as string.
     *
     * @param  boolean $encodeEntities Encode the HTML entities on the body?
     * @return string  Response body
     */
    public function getResponseBody($encodeEntities = true) {
        $body = (string) $this->getResponse()->getBody();

        if ($encodeEntities) {
            return htmlentities($body);
        }

        return $body;
    }
}