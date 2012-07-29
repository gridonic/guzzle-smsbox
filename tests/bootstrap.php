<?php
// Ensure that composer has installed all dependencies
if ( ! file_exists(__DIR__.'/../composer.lock')) {
    die("Dependencies must be installed using composer:\n\ncomposer.phar install --dev\n\n"
        . "See https://github.com/composer/composer/blob/master/README.md for help with installing composer\n");
}

// Include the composer autoloader
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Gridonic\Guzzle\SmsBox\Tests', __DIR__);

// Register services with the GuzzleTestCase
Guzzle\Tests\GuzzleTestCase::setMockBasePath(__DIR__.'/mock');

Guzzle\Tests\GuzzleTestCase::setServiceBuilder(\Guzzle\Service\Builder\ServiceBuilder::factory(array(
    'test.smsbox' => array(
        'class' => 'Gridonic.Guzzle.SmsBox.SmsBoxClient',
        'params' => array(
            'base_url' => 'http://biz.smsbox.ch:8047/723/sms/xml',
            'username' => 'admin',
            'password' => 'smsbox',
        )
    )
)));
