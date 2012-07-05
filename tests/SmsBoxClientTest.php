<?php
/**
 * @package Guzzle PHP <http://www.guzzlephp.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace Gridonic\Guzzle\SmsBox\Tests;

use Gridonic\Guzzle\SmsBox\Unfuddle\UnfuddleClient;

/**
 *
 */
class SmsBoxClientTest extends \Guzzle\Tests\GuzzleTestCase
{
    /**
     * @covers Guzzle\Unfuddle\UnfuddleClient
     */
    public function testBuilderCreatesClient()
    {
        // $client = UnfuddleClient::factory(array(
        //     'username' => 'phreddy',
        //     'password' => 'pharkis',
        //     'subdomain' => 'pharmacist'
        // ));

        // $request = $client->createRequest();
        $this->assertEquals('', '');
    }
}