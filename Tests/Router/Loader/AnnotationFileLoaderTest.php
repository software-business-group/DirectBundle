<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\Loader\AnnotationFileLoader;
use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AnnotationFileLoaderTest
 * @package Ext\DirectBundle\Tests\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationFileLoaderTest extends WebTestCase
{

    /**
     * @var
     */
    static $kernel;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FileLoader
     */
    private $fileLoader;

    /**
     * @var AnnotationClassLoader
     */
    private $annotationClassLoader;

    /**
     * @var AnnotationFileLoader
     */
    private $annotationFileLoader;

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->router = new Router();
        $this->fileLoader = new FileLoader($this->getRouter(), $this->get('file_locator'));
        $this->annotationClassLoader = new AnnotationClassLoader($this->getRouter(), $this->get('annotation_reader'));
        $this->annotationFileLoader = new AnnotationFileLoader($this->get('file_locator'), $this->getAnnotationClassLoader());

        $this->getFileLoader()->addLoader($this->getAnnotationFileLoader());
    }

    /**
     * @param $serviceId
     * @return mixed
     */
    public function get($serviceId)
    {
        return static::$kernel->getContainer()->get($serviceId);
    }

    /**
     * @return \Ext\DirectBundle\Router\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\FileLoader
     */
    public function getFileLoader()
    {
        return $this->fileLoader;
    }

    /**
     * @return AnnotationFileLoader
     */
    public function getAnnotationFileLoader()
    {
        return $this->annotationFileLoader;
    }

    /**
     * @return AnnotationClassLoader
     */
    public function getAnnotationClassLoader()
    {
        return $this->annotationClassLoader;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param $key
     * @return Rule
     * @throws \InvalidArgumentException
     */
    private function getRuleByKey($key)
    {
        if(!isset($this->getRules()[$key]))
            throw new \InvalidArgumentException();

        return $this->getRules()[$key];
    }

    public function testLoadByAnnotationFile()
    {
        return $this->getAnnotationFileLoader()->load();
    }

}