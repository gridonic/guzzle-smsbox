<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Guzzle\Service\Command\AbstractCommand;

/**
 * Sends a simple API request to an example web service
 *
 * @guzzle operator doc="The guessed operator" required="true" default="swisscom"
 */
class Simple extends AbstractCommand
{
    protected function build()
    {
        $this->request = $this->client->get(array('user/websend', $this->data));
        $this->request->setHeader('X-Header', $this->get('other_value'));

        echo('<pre>');
        print_r($this->data);
        echo('</pre>');

        echo('<pre>');
        print_r($this->getRequestHeaders());
        echo('</pre>');
    }

    protected function process()
    {
        $this->result = $this->getResponse();
    }

    /**
     * {@inheritdoc}
     * @return AwesomeObject
     */
    public function getResult()
    {
        return parent::getResult();
    }
}