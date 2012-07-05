<?php

namespace Gridonic\Guzzle\SmsBox;

use Guzzle\Service\Client;
use Guzzle\Service\Inspector;
use Guzzle\Service\Description\ServiceDescription;

class SmsBoxClient extends Client
{
    /**
     * The username for the API
     * @var string
     */
    protected $username;

    /**
     * The password to use for the API
     * @var string
     */
    protected $password;

    /**
     * Sets the test mode on the client.
     * This actually still performs requests to the smsBox API
     * and retrieves the responses, without sending out messages.
     * @var boolean
     */
    protected $test;

    /**
     * Factory method to create a new SmsBoxClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *
     *    base_url - Base URL of the smsBox service endpoint
     *    username - API username
     *    password - API password
     *
     * @return SmsBoxClient
     */
    static function factory($config = array())
    {
        $default  = array('test' => false);
        $required = array('base_url', 'username', 'password');
        $config   = Inspector::prepareConfig($config, $default, $required);

        $client = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('password'),
            $config->get('service'),
            $config->get('test')
        );
        $client->setConfig($config);

        // Uncomment the following two lines to use an XML service description
        // $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.xml'));

        return $client;
    }

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     */
    public function __construct($baseUrl, $username, $password, $test)
    {
        parent::__construct($baseUrl);
        $this->username = $username;
        $this->password = $password;
        $this->test     = $test;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $request = parent::createRequest($method, $uri, $headers, $body);
        $request->setHeader('Content-Type', 'text/xml');

        return $request;
    }

    /**
     * Returns the username for this client.
     * @return string The API username.
     */
    public function getUsername() {
        return $this->username;
    }
}
