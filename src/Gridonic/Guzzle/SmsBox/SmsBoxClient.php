<?php

namespace Gridonic\Guzzle\SmsBox;

use Guzzle\Service\Client;
use Guzzle\Service\Inspector;
use Guzzle\Service\Description\ServiceDescription;

class SmsBoxClient extends Client
{
    /**
     * Factory method to create a new SmsBoxClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *
     *    base_url - Base URL of web service
     *    scheme - URI scheme: http or https
     *    username - API username
     *    password - API password
     *
     * @return SmsBoxClient
     */
    static function factory($config = array())
    {
        $default = array();
        $required = array('base_url');
        $config = Inspector::prepareConfig($config, $default, $required);

        $client = new self($config->get('base_url'));
        $client->setConfig($config);

        // Uncomment the following two lines to use an XML service description
        $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.xml'));

        return $client;
    }

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     */
    public function __construct($baseUrl)
    {
        parent::__construct($baseUrl);
    }
}
