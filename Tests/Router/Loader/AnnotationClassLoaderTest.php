<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\Loader\AnnotationFileLoader;
use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Router\Rule;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnnotationClassLoaderTest extends WebTestCase
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
     * @var AnnotationClassLoader
     */
    private $annotationClassLoader;

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->router = new Router();
        $this->annotationClassLoader = new AnnotationClassLoader($this->getRouter(), $this->get('annotation_reader'));
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

    public function getLoadAnnotationClass()
    {
        return array(
            array('Ext\DirectBundle\Controller\ForTestingController')
        );
    }

    /**
     * @dataProvider getLoadAnnotationClass
     */
    public function testLoadAnnotationFromClass($class)
    {
        $this->getAnnotationClassLoader()->load($class);
    }

}