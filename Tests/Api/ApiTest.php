<?php

namespace Ext\DirectBundle\Tests\Api;
use Ext\DirectBundle\Api\Api;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class ApiTest
 * @package Ext\DirectBundle\Tests\Api
 * @author Semyon Velichko <semyon@velichko.net>
 */
class ApiTest extends TestTemplate
{

    /**
     * @var Api
     */
    private $api;

    /**
     * @var RouteCollection
     */
    private $collection;

    public function setUp()
    {
        parent::setUp();

        $this->setUpRouteCollection();
        $this->api = new Api($this->getRouteCollection(), $this->get('router'));
        $this->api->setType('remoting');
        $this->api->setNamespace('Actions');
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return \Ext\DirectBundle\Api\Api
     */
    public function getApi()
    {
        return $this->api;
    }

    public function setUpRouteCollection()
    {
        $this->collection = new RouteCollection();

        foreach($this->getRules() as $Rule)
        {
            $this->collection->add($Rule[0]);
        }

        return;
    }

    public function testCreateApi()
    {
        $this->jsonResponseTest(
            $this->getApi()->createApi(), $this->getApi()
        );
    }

    /**
     * Ext.ns("Actions"); Actions.REMOTING_API = {"type":"remoting","namespace":"Actions","url":"\/route","actions":{"ExtDirect_Test":[{"name":"formHandler","len":1,"formHandler":true},{"name":"formHandler","len":1},{"name":"withParams","len":1},{"name":"withoutParams","len":0}],"ExtDirect_Other":[{"name":"action","len":0}]}}
     */
    public function testController()
    {
        $apiJsHeader = 'Ext.ns("Actions"); Actions.REMOTING_API = ';
        $client = static::createClient();
        foreach($this->getRules() as $Rule)
        {
            static::$kernel->getContainer()->get('ext_direct.route.collection')
                ->add($Rule[0]);
        }

        $client->request('GET', $this->get('router')->generate('ExtDirectBundle_api'));
        $response = $client->getResponse()->getContent();

        $this->assertContains($apiJsHeader, $response);
        $this->jsonResponseTest(
            json_decode(substr($response, strlen($apiJsHeader)), true), static::$kernel->getContainer()->get('ext_direct.api')
        );
    }

    /**
     * @param array $array
     * @param Api $api
     */
    public function jsonResponseTest($array, Api $api)
    {
        $this->assertArrayHasKey('type', $array);
        $this->assertEquals($api->getType(), $array['type']);

        $this->assertArrayHasKey('namespace', $array);
        $this->assertEquals($api->getNamespace(), $array['namespace']);

        $this->assertArrayHasKey('url', $array);
        $this->assertEquals($this->get('router')->generate('ExtDirectBundle_route'), $array['url']);

        $actions = $array['actions'];
        $rules = array(
            'ExtDirect_Test' => array(
                array('name' => 'formHandler', 'len' => 1, 'formHandler' => true),
                array('name' => 'formHandler2', 'len' => 1),
                array('name' => 'withParams', 'len' => 1),
                array('name' => 'withoutParams', 'len' => 0)
            ),
            'ExtDirect_Other' => array(
                array('name' => 'action', 'len' => 0)
            )
        );

        $this->assertEquals($actions, $rules);
    }

}
