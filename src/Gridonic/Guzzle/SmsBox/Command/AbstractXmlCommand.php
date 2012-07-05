<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Guzzle\Service\Command\AbstractCommand;

/**
 * Abstract command implementing XML calls and XML responses
 */
abstract class AbstractXmlCommand extends AbstractCommand
{
    /**
     * Create the result of the command after the request has been completed.
     *
     * Sets the result as the response by default.  If the response is an XML
     * document, this will set the result as a SimpleXMLElement.  If the XML
     * response is invalid, the result will remain the Response, not XML.
     * If an application/json response is received, the result will automat-
     * ically become an array.
     */
    protected function process()
    {
        // Uses the response object by default
        $this->result = $this->getRequest()->getResponse();

        $contentType = $this->result->getContentType();

        // Is the body an JSON document?  If so, set the result to be an array
        if (stripos($contentType, 'json') !== false) {
            $body = trim($this->result->getBody(true));
            if ($body) {
                $decoded = json_decode($body, true);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new JsonException('The response body can not be decoded to JSON', json_last_error());
                }

                $this->result = $decoded;
            }
        } if (stripos($contentType, 'xml') !== false) {
            // Is the body an XML document?  If so, set the result to be a SimpleXMLElement
            // If the body is available, then parse the XML
            $body = trim($this->result->getBody(true));
            if ($body) {
                // Silently allow parsing the XML to fail
                try {
                    $xml = new \SimpleXMLElement($body);
                    $this->result = $xml;
                } catch (\Exception $e) {}
            }
        }
    }

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
     * {@inheritdoc}
     * @return SimpleXMLElement
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