<?php

namespace Gridonic\Guzzle\SmsBox\Command;

/**
 * Abstract command implementing command requests
 */
abstract class AbstractXmlRequestCommand extends AbstractXmlCommand
{
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
        $command  = $xml->createElement('command', strtoupper($this->getName()));

        $request->appendChild($username);
        $request->appendChild($password);
        $request->appendChild($command);

        $params = $request->appendChild($xml->createElement('parameters'));

        // add parameters
        foreach ($this->getApiCommand()->getParams() as $name => $arg) {
            if ($this->get($name) === true) {
                $params->appendChild($xml->createElement($name));
            } else if (!is_null($this->get($name)) && $this->get($name) !== false) {
                $params->appendChild($xml->createElement($name, $this->get($name)));
            }
        }

        return $xml;
    }
}