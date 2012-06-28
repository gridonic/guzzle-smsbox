<?php

namespace Gridonic\SmsBox;

use Guzzle\Service\Client;
use Guzzle\Service\Inspector;
use Guzzle\Service\Description\ServiceDescription;

class SmsBoxClient extends Client
{
    /**
     * @var string Username
     */
    protected $username;

    /**
     * @var string Password
     */
    protected $password;

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
        $required = array('base_url', 'username', 'password');
        $config = Inspector::prepareConfig($config, $default, $required);

        $client = new self(
            $config->get('base_url'),
            $config->get('username'),
            $config->get('password')

        );
        $client->setConfig($config);

        // Uncomment the following two lines to use an XML service description
        // $client->setDescription(ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . 'client.xml'));

        return $client;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     * @param string $username API username
     * @param string $password API password
     */
    public function __construct($baseUrl, $username, $password)
    {
        parent::__construct($baseUrl);
        $this->username = $username;
        $this->password = $password;
    }
}
