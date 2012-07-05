<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Gridonic\Guzzle\SmsBox\Command\AbstractXmlCommand;

use Guzzle\Http\EntityBody as EntityBody;

/**
 * Sends an XML request to the smsBox HTTP API
 *
 * @guzzle command required="true"
 * @guzzle receiver required="true"
 * @guzzle service required="true"
 * @guzzle text required="true"
 * @guzzle guessOperator required="false" default=""
 */
class XMLRequestCommand extends AbstractXmlCommand
{
}