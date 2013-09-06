<?php

namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Router\Rule;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Router
     */
    private static $Router;

    public static function setUpBeforeClass()
    {
        self::$Router = new Router();
    }

    public static function tearDownAfterClass()
    {
        self::$Router = null;
    }

    public function testRouteConstruct()
    {
        $this->assertInstanceOf('\Ext\DirectBundle\Router\Router', self::$Router);
        return self::$Router;
    }

    /**
     * @return array
     */
    public function getValidRules()
    {
        return ValidRules::getValidRules();
    }

    /**
     * @dataProvider getValidRules
     * @depends testRouteConstruct
     */
    public function testAddRules(Rule $Rule, Router $Router)
    {
        try {
            $Router->add($Rule);
        } catch (\Exception $e)
        {
        }

        $this->assertFalse(isset($e));
    }

    /**
     * @dataProvider getValidRules
     */
    public function testHasRules(Rule $Rule)
    {
        $this->assertTrue(self::$Router->has($Rule->getAlias()));
        $this->assertEquals(self::$Router->get($Rule->getAlias()), $Rule);
    }

}