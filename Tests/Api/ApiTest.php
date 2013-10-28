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
            $this->get('ext_direct.route.collection')
                ->add($Rule[0]);
        }
    }

    public function testCreateApi()
    {
        $json = $this->getApi()->createApi();
        return;
    }



    public function testController()
    {
        $client = static::createClient();;


        $crawler = $client->request('GET', $this->get('router')->generate('ExtDirectBundle_api'));
        return;
    }

} 