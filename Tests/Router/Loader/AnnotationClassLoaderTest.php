<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Tests\TestTemplate;

class AnnotationClassLoaderTest extends TestTemplate
{

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var AnnotationClassLoader
     */
    private $loader;

    public function setUp()
    {
        parent::setUp();
        $this->collection = new RouteCollection();
        $this->loader = new AnnotationClassLoader(
            $this->getCollection(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\AnnotationClassLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    public function getLoadAnnotationClass()
    {
        return array(
            array('Ext\DirectBundle\Controller\TestController')
        );
    }

    /**
     * @dataProvider getLoadAnnotationClass
     */
    public function testLoadAnnotationFromTestController($class)
    {
        $this->getLoader()->load($class);

        $collection = $this->getLoader()->getRouteCollection();

        $this->assertTrue($collection->has('annotationWithNameAction'));
        $Rule = $collection->get('annotationWithNameAction');
        $this->assertEquals('annotationWithNameAction', $Rule->getAlias());
        $this->assertEquals('ExtDirect_Test.annotationWithName', $Rule->getController());
        $this->assertEquals('xml', $Rule->getReaderParam('type'));
        $this->assertEquals('read', $Rule->getReaderParam('root'));
        $this->assertEquals('successProperty', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('totalProperty', $Rule->getReaderParam('totalProperty'));
        $this->assertEquals('totalProperty', $Rule->getReaderParam('totalProperty'));



        return;
    }

}