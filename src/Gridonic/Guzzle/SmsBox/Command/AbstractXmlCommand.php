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
        $args = $this->getApiCommand()->getParams();
        $name = $this->get('guessOperator');

        echo('<pre>');
        var_dump($name);
        echo('</pre>');

        // echo('<pre>');
        // print_r($args);
        // echo('</pre>');

        // echo('<pre>');
        // print_r($this->data);
        // echo('</pre>');

        // build XML
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<SMSBoxXMLRequest>
    <username>'. $this->client->getConfig('username') .'</username>
    <password>'. $this->client->getConfig('password') .'</password>
    <command>'. $this->get('command') .'</command>
    <parameters>
        <receiver>'. $this->get('receiver') .'</receiver>
        <service>'. $this->client->getConfig('service') .'</service>
        <text>'. $this->get('text') .'</text>
        <cost>0</cost>
        <guessOperator/>
        <test/>
    </parameters>
</SMSBoxXMLRequest>';

        echo('<pre>');
        print_r(htmlentities($xml));
        echo('</pre>');

        $this->request = $this->client->post(null, null, $xml);
        // $this->request->setBody($xml);
    }

    // /**
    //  * Compose the XML prolog that will be prepended to the
    //  * message body.
    //  * @return  string The XML prolog.
    //  */
    // abstract protected function buildXMLProlog();

    // /**
    //  * Build the XML body that will be used
    //  * @return string The XML body.
    //  */
    // abstract protected function buildXMLBody();

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