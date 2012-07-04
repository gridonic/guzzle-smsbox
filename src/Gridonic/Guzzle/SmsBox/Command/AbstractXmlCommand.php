<?php

namespace Gridonic\Guzzle\SmsBox\Command;

use Guzzle\Service\Command\AbstractCommand;

/**
 * Abstract command implementing XML calls and XML responses
 */
abstract class AbstractXmlCommand extends AbstractCommand
{
    protected function process()
    {
        $this->result = new \SimpleXMLElement($this->getResponse()->getBody(true));
    }

    /**
     * {@inheritdoc}
     * @return SimpleXMLElement
     */
    public function getResult()
    {
        return parent::getResult();
    }
}