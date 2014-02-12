<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class AnnotationClassLoaderTest
 *
 * @package Ext\DirectBundle\Tests\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->collection = new RouteCollection();
        $this->loader = new AnnotationClassLoader(
            $this->getCollection(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );

        $this->getLoader()->load('Ext\DirectBundle\Controller\TestController');
        $this->collection = $this->getLoader()->getRouteCollection();
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

    public function testLoadAnnotationWithName()
    {
        $this->assertTrue($this->collection->has('annotation_action_with_name'));
        $Rule = $this->collection->get('annotation_action_with_name');
        $this->assertEquals('annotation_action_with_name', $Rule->getAlias());
        $this->assertEquals('ExtDirectBundle:Test:annotationWithName', $Rule->getController());
        $this->assertEquals('xml', $Rule->getReaderParam('type'));
        $this->assertEquals('read', $Rule->getReaderParam('root'));
        $this->assertEquals('successProperty', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('totalProperty', $Rule->getReaderParam('totalProperty'));
        $this->assertEquals('xml', $Rule->getWriterParam('type'));
        $this->assertEquals('write', $Rule->getWriterParam('root'));
    }

    public function testLoadAnnotationWithoutName()
    {
        $this->assertTrue($this->collection->has('ExtDirectBundle_Test_annotationWithoutName'));
        $Rule = $this->collection->get('ExtDirectBundle_Test_annotationWithoutName');
        $this->assertEquals('ExtDirectBundle_Test_annotationWithoutName', $Rule->getAlias());
        $this->assertEquals('ExtDirectBundle:Test:annotationWithoutName', $Rule->getController());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
        $this->assertEquals('xml', $Rule->getWriterParam('type'));
        $this->assertEquals('write', $Rule->getWriterParam('root'));
    }

}