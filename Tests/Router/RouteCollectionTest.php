<?php


namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class RouteCollectionTest
 *
 * @package Ext\DirectBundle\Tests\Router
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class RouteCollectionTest extends TestTemplate
{

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->collection = new RouteCollection();
    }

    /**
     * @param Rule $rule
     *
     * @dataProvider getRules
     */
    public function testAddAndRemove(Rule $rule)
    {
        $this->collection->add($rule);
        $this->assertEquals(1, count($this->collection));
        $this->assertTrue($this->collection->has($rule->getAlias()));
        $this->assertTrue($this->collection->offsetExists($rule->getAlias()));
        $this->assertEquals($rule, $this->collection->get($rule->getAlias()));

        $this->collection->remove($rule);
        $this->assertFalse($this->collection->has($rule->getAlias()));
        $this->assertFalse($this->collection->offsetExists($rule->getAlias()));
    }

    /**
     * Testing RouteCollection serialize and unserialize
     */
    public function testSerializeAndUnserialize()
    {
        foreach ($this->getRules() as $rule) {
            $this->collection->add($rule[0]);
        }

        $unserCollection = unserialize(serialize($this->collection));
        $this->assertEquals($this->collection, $unserCollection);
    }
}
