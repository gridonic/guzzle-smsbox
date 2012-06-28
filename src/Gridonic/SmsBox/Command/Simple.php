<?php

namespace Gridonic\SmsBox\Command;

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
        $this->request->setAuth($this->client->getUsername(), $this->client->getPassword());
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