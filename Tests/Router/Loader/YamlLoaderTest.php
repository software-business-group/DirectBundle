<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\YamlLoader;
use Ext\DirectBundle\Router\Router;

class YamlLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    private $resource;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var YamlLoader
     */
    private $loader;

    protected function setUp()
    {
        $this->resource = ( __DIR__ . '/routing.yml' );
        $this->router = new Router();
        $this->loader = new YamlLoader($this->router);
    }

    /**
     * @return \Ext\DirectBundle\Router\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\YamlLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    public function testLoad()
    {
        $this->getLoader()->load($this->getResource());
        $this->assertTrue(true);
    }

}