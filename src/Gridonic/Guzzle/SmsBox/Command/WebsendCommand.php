<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Gridonic\Guzzle\SmsBox\Command\AbstractXmlCommand;

use Guzzle\Http\EntityBody as EntityBody;

/**
 * Sends an XML request to the smsBox HTTP API
 *
 * @guzzle username required="true" default="bla"
 */
class WebsendCommand extends AbstractXmlCommand
{
    protected function build()
    {
        $args = $this->getApiCommand()->getParams();

        // echo('<pre>');
        // print_r($args);
        // echo('</pre>');

        // echo('<pre>');
        // print_r($this->data);
        // echo('</pre>');

        // build XML
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<SMSBoxXMLRequest>
    <username>myuser</username>
    <password>mypass</password>
    <command>WEBSEND</command>
    <parameters>
        <receiver>+41761234567</receiver>
        <service>ULTIMATE</service>
        <text>This is a message from us!</text>
        <guessOperator/>
    </parameters>
</SMSBoxXMLRequest>';

        echo('<pre>');
        print_r(htmlentities($xml));
        echo('</pre>');

        $this->request = $this->client->post(null, null, $xml);
        // $this->request->setBody($xml);
    }
}