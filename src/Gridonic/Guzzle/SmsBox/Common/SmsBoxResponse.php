<?php

namespace Gridonic\Guzzle\SmsBox\Common;

/**
 * A smsBox XML response wrapper
 */
class SmsBoxResponse
{
    /**
     * The SimpleXMLElement object from the response.
     * @var SimpleXMLElement
     */
    protected $xmlElement;

    /**
     * Public constructor.
     *
     * @param SimpleXMLElement  $xmlResponse The XML response
     */
    public function __construct($xmlString) {
        // try parsing the XML and throw a custom error
        try {
            $this->xmlElement = new \SimpleXMLElement($xmlString);
        } catch (\Exception $e) {
            throw new SmsBoxException('Could not parse the XML response.');
        }
    }

    /**
     * Checks whether the XML response contains any errors.
     * @return boolean True if the XML response has errors, false else.
     */
    public function hasError() {
        return isset($this->xmlElement->error);
    }

    /**
     * Returns the error tyoe of the error or null
     * @return string Error code as string
     */
    public function getErrorType() {
        if ($this->hasError()) {
            $attributes = $this->xmlElement->error->attributes();

            if (isset($attributes->type)) {
                return (string) $attributes->type;
            }
        }
        return null;
    }
}
