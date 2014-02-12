<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Tests\TestTemplate;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Loader\AnnotationDirectoryLoader;
use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;

/**
 * Class AnnotationDirectoryLoader
 * @package Ext\DirectBundle\Tests\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationDirectoryLoaderTest extends AnnotationClassLoaderTest
{

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var AnnotationDirectoryLoader
     */
    private $directoryLoader;

    /**
     * @var AnnotationClassLoader
     */
    private $classLoader;

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\AnnotationDirectoryLoader
     */
    public function getDirectoryLoader()
    {
        return $this->directoryLoader;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\AnnotationClassLoader
     */
    public function getClassLoader()
    {
        return $this->classLoader;
    }

    public function setUp()
    {
        parent::setUp();

        $this->collection = new RouteCollection();
        $this->classLoader = new AnnotationClassLoader(
            $this->getRouteCollection(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );
        $this->directoryLoader = new AnnotationDirectoryLoader($this->getClassLoader());

        $this->getDirectoryLoader()->load( __DIR__ . '/../../../Controller/' );
    }

} 