<?php


namespace Ext\DirectBundle\Tests\Controller;

use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class TestControllerTest
 * @package Ext\DirectBundle\Tests\Controller
 * @author Semyon Velichko <semyon@velichko.net>
 */
class TestControllerTest extends TestTemplate
{

    public function setUp()
    {
        parent::setUp();

        static::$kernel->getContainer()->get('ext_direct.yml.loader')
            ->load( __DIR__ . '/../Router/Loader/routing.yml');
    }

    // generic array response
    public function testGeneralArrayResponse()
    {
        $postRawArray = array('action' => 'ExtDirect_Test',
            'method' => 'testArrayResponse',
            'data' => array(array('page' => rand(1,10), 'start' => rand(10,20), 'limit' => rand(100,9999))),
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

        $this->assertArrayHasKey('result', $arrayResult);

        $this->assertArrayHasKey('page', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['page'], $arrayResult['result']['page']);

        $this->assertArrayHasKey('start', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['start'], $arrayResult['result']['start']);

        $this->assertArrayHasKey('limit', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['limit'], $arrayResult['result']['limit']);
    }

    // Ext\DirectBundle\Response\Response;
    public function testGeneralObjectResponse()
    {
        $postRawArray = array('action' => 'ExtDirect_ForTesting',
            'method' => 'testObjectResponse',
            'data' => array(array('page' => rand(1,10), 'start' => rand(10,20), 'limit' => rand(100,9999))),
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

        $this->assertArrayHasKey('result', $arrayResult);

        $this->assertArrayHasKey('page', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['page'], $arrayResult['result']['page']);

        $this->assertArrayHasKey('start', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['start'], $arrayResult['result']['start']);

        $this->assertArrayHasKey('limit', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['limit'], $arrayResult['result']['limit']);
    }

    // Ext\DirectBundle\Response\Response;
    public function testObjectResponseWithConfiguredReader()
    {
        $postRawArray = array('action' => 'ExtDirect_ForTesting',
            'method' => 'testResponseWithConfiguredReader',
            'data' => array(array('page' => rand(1,10), 'start' => rand(10,20), 'limit' => rand(100,9999))),
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

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('root', $arrayResult['result']);

        $this->assertArrayHasKey('page', $arrayResult['result']['root']);
        $this->assertEquals($postRawArray['data'][0]['page'], $arrayResult['result']['root']['page']);

        $this->assertArrayHasKey('start', $arrayResult['result']['root']);
        $this->assertEquals($postRawArray['data'][0]['start'], $arrayResult['result']['root']['start']);

        $this->assertArrayHasKey('limit', $arrayResult['result']['root']);
        $this->assertEquals($postRawArray['data'][0]['limit'], $arrayResult['result']['root']['limit']);

        $this->assertArrayHasKey('successProperty', $arrayResult['result']);
        $this->assertEquals(true, $arrayResult['result']['successProperty']);

        $this->assertArrayHasKey('totalProperty', $arrayResult['result']);
        $this->assertEquals(100, $arrayResult['result']['totalProperty']);
    }

    // Ext\DirectBundle\Response\Response;
    public function testFormHandlerResponse()
    {
        $postArray = array('extAction' => 'ExtDirect_ForTesting',
            'extMethod' => 'testFormHandlerResponse',
            'extType' => 'rpc',
            'extTID' => rand(1, 10),
            'extUpload' => false,

            'id' => rand(1,99),
            'name' => 'Joker',
            'count' => rand(100,200));

        $client = static::createClient();
        $crawler = $client->request('POST', $this->get('router')->generate('ExtDirectBundle_route'), $postArray);
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);

        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('data', $arrayResult['result']);

        $this->assertArrayHasKey('success', $arrayResult['result']);
        $this->assertEquals(true, $arrayResult['result']['success']);

        foreach(array('id', 'name', 'count') as $key) {
            $this->assertArrayHasKey($key, $arrayResult['result']['data']);
            $this->assertEquals($postArray[$key], $arrayResult['result']['data'][$key]);
        }

    }

    // Ext\DirectBundle\Response\Response;
    // Ext\DirectBundle\Response\FormError;
    public function testFormValidationResponse()
    {
        $postArray = array('extAction' => 'ExtDirect_ForTesting',
            'extMethod' => 'testFormValidationResponse',
            'extType' => 'rpc',
            'extTID' => rand(1, 10),
            'extUpload' => false,

            'id' => rand(1,99),
            'name' => 'Joker',
            'count' => rand(1, 99));

        $client = static::createClient();
        $crawler = $client->request('POST', $this->get('router')->generate('ExtDirectBundle_route'), $postArray);
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);

        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('success', $arrayResult['result']);
        $this->assertEquals(true, $arrayResult['result']['success']);

        // catch errors from form
        $postArray['name'] = '';
        $postArray['count'] = -100;

        $crawler = $client->request('POST', $this->get('router')->generate('ExtDirectBundle_route'), $postArray);
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);

        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('success', $arrayResult['result']);
        $this->assertEquals(false, $arrayResult['result']['success']);

        $this->assertArrayHasKey('msg', $arrayResult['result']);
        $this->assertRegExp('/This value should not be blank/', $arrayResult['result']['msg']);
        $this->assertRegExp('/This value should be 0 or more/', $arrayResult['result']['msg']);
    }

    // Ext\DirectBundle\Response\Response;
    // Ext\DirectBundle\Response\Validator;
    public function testFormEntityValidationResponse()
    {
        $postArray = array('extAction' => 'ExtDirect_ForTesting',
            'extMethod' => 'testFormEntityValidationResponse',
            'extType' => 'rpc',
            'extTID' => rand(1, 10),
            'extUpload' => false,

            'id' => rand(1,99),
            'name' => 'Joker',
            'count' => rand(1, 99));

        $client = static::createClient();
        $crawler = $client->request('POST', $this->get('router')->generate('ExtDirectBundle_route'), $postArray);
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);

        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('success', $arrayResult['result']);
        $this->assertEquals(true, $arrayResult['result']['success']);

        // catch errors from form
        $postArray['name'] = '';
        $postArray['count'] = -100;

        $crawler = $client->request('POST', $this->get('router')->generate('ExtDirectBundle_route'), $postArray);
        $jsonResult = $client->getResponse()->getContent();
        $arrayResult = json_decode($jsonResult, true);

        $this->assertInternalType('array', $arrayResult);
        $this->assertArrayHasKey(0, $arrayResult);
        $arrayResult = array_shift($arrayResult);

        $this->assertArrayHasKey('result', $arrayResult);
        $this->assertArrayHasKey('success', $arrayResult['result']);
        $this->assertEquals(false, $arrayResult['result']['success']);

        $this->assertArrayHasKey('msg', $arrayResult['result']);
        $this->assertRegExp('/This value should not be blank/', $arrayResult['result']['msg']);
        $this->assertRegExp('/This value should be 0 or more/', $arrayResult['result']['msg']);
    }

    public function testActionAsService()
    {
        $postRawArray = array('action' => 'ext_direct_test_service',
            'method' => 'testActionAsService',
            'data' => array(array('page' => rand(1,10), 'start' => rand(10,20), 'limit' => rand(100,9999))),
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

        $this->assertArrayHasKey('result', $arrayResult);

        $this->assertArrayHasKey('page', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['page'], $arrayResult['result']['page']);

        $this->assertArrayHasKey('start', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['start'], $arrayResult['result']['start']);

        $this->assertArrayHasKey('limit', $arrayResult['result']);
        $this->assertEquals($postRawArray['data'][0]['limit'], $arrayResult['result']['limit']);
    }

} 