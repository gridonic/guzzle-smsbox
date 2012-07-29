<?php

namespace Gridonic\Guzzle\SmsBox\Common;

use Gridonic\Guzzle\SmsBox\Common\SmsBoxResponse;

/**
 * smsBox XML exception
 */
class SmsBoxXmlException extends SmsBoxException
{
    /**
     * XML content
     * @var SmsBoxResponse
     */
    protected $response;

    /**
     * Exception message
     * @var string
     */
    protected $message = 'Unknown exception';

    /**
     * XML API response error type
     * @var string
     */
    protected $errorType;

    /**
     * Mapping of XML API error types
     * @var array
     */
    protected static $errorTypes = array(
        'xmlparseerror'       => "The submitted XML document is not valid.",
        'dtdparseerror'       => "The submitted XML document doesn't properly follow the DTD.",
        'wrongutf8'           => "The XML request document is not properly encoded in UTF-8.",
        'userunknown'         => "The specified username is unknown.",
        'wrongpassword'       => "The specified password is incorrect.",
        'xmlapinoaccess'      => "You do not have access to any commands of the XML API.",
        'commandnoaccess'     => "You do not have access to the requested command.",
        'parammissing'        => "One of the mandatory parameters is missing.",
        'paramnomatch'        => "One of the parameter values is incorrect (typically a range or data type problem). Details indicated in error description.",
        'themenomatch'        => "The specified service does not exist.",
        'deactivatedtheme'    => "The specified service is deactivated.",
        'noaccess'            => "You do not have access to the specified service.",
        'costnomatch'         => "The specified cost is incorrect or unauthorized.",
        'badphone'            => "The phone number has a bad syntax (bad prefix, missing digits...).",
        'blacklistmember'     => "The specified end-user is blacklisted.",
        'selfblacklistmember' => "The specified end-user has blacklisted himself."
    );

    /**
     * Public constructor.
     *
     * @param string            $message Exception message
     * @param integer           $code    Exception code
     * @param SmsBoxResponse    $xmlResponse The XML response
     */
    public function __construct($xmlResponse) {
        $this->response = $xmlResponse;

        // Compose message.
        if ($xmlResponse->hasError()) {
            $this->errorType = $xmlResponse->getErrorType();

            if ($xmlResponse->getErrorValue() !== null) {
                $this->message = $xmlResponse->getErrorValue();
            } else if (array_key_exists($this->errorType, self::$errorTypes)) {
                $this->message = self::$errorTypes[$this->errorType];
            }

            $this->message .= ' ('. $this->errorType .')';
        }

        parent::__construct($this->message, $code = 0);
    }

    /**
     * Returns the error type.
     * @return string XML error type
     */
    public function getErrorType() {
        return $this->errorType;
    }

     /**
     * @return array All possible error types and associated error messages.
     */
    public static function getErrorTypes() {
      return static::$errorTypes;
    }

    /**
     * Returns the response for the XML exception.
     * @return SmsBoxXmlResponse The response object
     */
    public function getResponse() {
        return $this->response;
    }
}
