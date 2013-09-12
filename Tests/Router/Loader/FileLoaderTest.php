<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\Loader\YamlLoader;
use Ext\DirectBundle\Router\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileLoaderTest extends WebTestCase
{

    /**
     * @var
     */
    static $kernel;

    /**
     * @var string
     */
    private $resource;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FileLoader
     */
    private $fileLoader;

    /**
     * @var YamlLoader
     */
    private $ymlLoader;

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->resource = ( __DIR__ . '/routing.yml' );
        $this->router = new Router();
        $this->fileLoader = new FileLoader($this->getRouter(), $this->get('file_locator'));
        $this->ymlLoader = new YamlLoader($this->getRouter(), $this->getFileLoader());

        $this->getFileLoader()->addLoader($this->getYmlLoader());
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
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\YamlLoader
     */
    public function getYmlLoader()
    {
        return $this->ymlLoader;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\FileLoader
     */
    public function getFileLoader()
    {
        return $this->fileLoader;
    }

    public function testLoad()
    {

        $this->getYmlLoader()->load($this->getResource());
        $this->assertTrue(true);
    }

}