<?php

namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Tests\ControllerTest;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class ControllerResolverTest
 * @package Ext\DirectBundle\Tests\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class ControllerResolverTest extends TestTemplate
{

    /**
     * @var ControllerResolver
     */
    private $resolver;

    public function setUp()
    {
        parent::setUp();
        $this->resolver = $this->get('ext_direct.controller_resolver');
    }

    /**
     * @return ControllerResolver
     */
    private function getResolver()
    {
        return $this->resolver;
    }

    public function testGetActionForRouter()
    {
        $data = array(
            'ExtDirectBundle:Direct:route' => new \ReflectionMethod('Ext\DirectBundle\Controller\DirectController', 'routeAction'),
            'ExtDirectBundle:Test:annotationWithName' => new \ReflectionMethod('Ext\DirectBundle\Controller\TestController', 'annotationWithNameAction')
        );
        foreach($data as $result => $method)
        {
            $this->assertEquals($result, $this->getResolver()->genAction($method));
        }
    }
}