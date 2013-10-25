<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\Loader\AnnotationFileLoader;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class AnnotationFileLoaderTest
 * @package Ext\DirectBundle\Tests\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationFileLoaderTest extends TestTemplate
{

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var AnnotationFileLoader
     */
    private $loader;

    /**
     * @var AnnotationClassLoader
     */
    private $classLoader;

    public function setUp()
    {
        parent::setUp();

        $this->collection = new RouteCollection();
        $this->classLoader = new AnnotationClassLoader(
            $this->getRouteCollection(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );
        $this->loader = new AnnotationFileLoader($this->getClassLoader());
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\AnnotationClassLoader
     */
    public function getClassLoader()
    {
        return $this->classLoader;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\AnnotationFileLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    public function testFindClassAnnotationFile()
    {
        $file = $this->get('file_locator')->locate('@ExtDirectBundle/Controller/TestController.php');
        $this->assertEquals(
            'Ext\DirectBundle\Controller\TestController',
            $this->getLoader()->findClass($file)
        );
    }

    /**
     * @return array
     */
    public function getSupportedResources()
    {
        return array(
            array('ExtDirectBundle/Controller/TestController.php'),
            array('ExtDirectBundle/Controller/TestController.php', 'annotation'),
            array('ExtDirectBundle/Controller/TestController.inc', 'annotation')
        );
    }

    /**
     * @param $resource
     * @param null|string $type
     * @dataProvider getSupportedResources
     */
    public function testIsSupported($resource, $type = null)
    {
        $this->assertTrue(
            $this->getLoader()->supports($resource, $type)
        );
    }

    public function getNotSupportedResources()
    {
        return array(
            array('ExtDirectBundle/Resources/config/routing.yml'),
            array('ExtDirectBundle/Resources/config/routing.yml', 'yml'),
            array('ExtDirectBundle/Resources/config/routing.php', 'xml'),
            array('ExtDirectBundle/Resources/config/routing.xml'),
        );
    }

    /**
     * @param $resource
     * @param null|string $type
     * @dataProvider getNotSupportedResources
     */
    public function testIsNotSupported($resource, $type = null)
    {
        $this->assertFalse(
            $this->getLoader()->supports($resource, $type)
        );
    }

}