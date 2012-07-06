<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Gridonic\Guzzle\SmsBox\Command\AbstractXmlCommand;

/**
 * Sends an XML request to the smsBox HTTP API
 *
 * @guzzle command required="true" filters="strtoupper"
 * @guzzle receiver required="true"
 * @guzzle service required="true"
 * @guzzle text required="true"
 * @guzzle guessOperator required="false" default=""
 */
class XmlRequestCommand extends AbstractXmlCommand
{
}