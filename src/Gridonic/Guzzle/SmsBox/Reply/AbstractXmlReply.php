<?php

namespace Gridonic\Guzzle\SmsBox\Reply;

/**
 * Abstract XML reply object
 */
abstract class AbstractXmlReply
{
    /**
     * The XML object used in the reply
     * @var DOMDocument
     */
    protected $replyXml;

    /**
     * Reply messages
     * @var array
     */
    protected $messages = array();

    /**
     * Initializes a XML reply object.
     * @param array|Collection    $parameters Collection of parameters to set on the command
     */
    public function __construct($params = null)
    {
        $this->replyXml = $this->buildXml();
    }

    /**
     * Builds the internal XML object based on the parameters
     * passed to the constructor of this object.
     */
    protected function buildXml()
    {
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;

        $request = $xml->appendChild($xml->createElement('NotificationReply'));

        foreach ($this->messages as $message) {
            $msgXml = $request->appendChild($xml->createElement('message'));
            $params = $message->getAll();

            foreach ($params as $key => $value) {
                $msgXml->appendChild($xml->createElement($key, $value));
            }
        }

        return $xml;
    }

    /**
     * Adds a message to the XML reply
     */
    public function addMessage(XmlReplyMessage $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Returns the messages for this reply object
     * @return array An array of messages
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Returns the XML object which is built upon calling this funciton.
     * @return SimpleXMLElement The XML object
     */
    public function getXml()
    {
        $this->replyXml = $this->buildXml();
        return $this->replyXml;
    }

    /**
     * @{@inheritdoc}
     */
    public function __toString()
    {
        return $this->getXml()->saveXML();
    }
}