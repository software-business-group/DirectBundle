<?php

namespace Ext\DirectBundle\Tests\Annotation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Annotation\Driver;

/**
 * Class DriverTest
 * @package Ext\DirectBundle\Tests\Annotation
 * @author Semyon Velichko <semyon@velichko.net>
 */
class DriverTest extends WebTestCase
{

    static $kernel;
    static $container;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Driver
     */
    private $driver;

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        self::$container = self::$kernel->getContainer();

        $this->setUpRouter();
        $this->setUpDriver();
    }

    /**
     * @param $name
     * @return mixed
     */
    private function get($name)
    {
        return self::$container->get($name);
    }

    private function setUpRouter()
    {
        $this->router = new Router();
    }

    /**
     * @return Router
     */
    private function getRouter()
    {
        return $this->router;
    }

    private function setUpDriver()
    {
        $this->driver = new Driver($this->get('annotation_reader'), $this->getRouter());
    }

    /**
     * @return Driver
     */
    private function getDriver()
    {
        return $this->driver;
    }

    public function testReadAnnotations()
    {
        //$this->getDriver()->readAnnotations( __DIR__ . '/../../Controller/ForTestingController.php' );
        //$this->assertTrue(true);
    }

}