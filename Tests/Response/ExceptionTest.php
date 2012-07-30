<?php
namespace Ext\DirectBundle\Tests\Response;

use Ext\DirectBundle\Tests\ControllerTest;
use Ext\DirectBundle\Tests\Router;

/**
 * Testing basic function.
 *
 * @author Semyon Velichko <semyon@velichko.net>
 */
class ExceptionTest extends ControllerTest
{
    public function testExceptionResponse()
    {
        $postRawArray = array('action' => 'ExtDirect_ForTesting',
                             'method' => 'testException',
                             'data' => array(),
                             'type' => 'rpc',
                             'tid' => rand(1, 10));
        $postRawData = json_encode($postRawArray);
        
        $client = static::createClient();
        $crawler = $client->request('POST',
                                    $this->get('router')->generate('ExtDirectBundle_route'),
                                    array(),
                                    array(),
                                    array(),
                                    $postRawData);
        
        
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);
        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);
        
        $this->assertArrayHasKey('type', $arrayResult);
        $this->assertEquals('exception', $arrayResult['type']);
        
        $this->assertArrayHasKey('message', $arrayResult);
        $this->assertArrayHasKey('where', $arrayResult);
        
        $this->assertRegExp('/testExceptionAction/', $arrayResult['message']);
        $this->assertRegExp('/ForTestingController\.php/', $arrayResult['where']);
    }
}