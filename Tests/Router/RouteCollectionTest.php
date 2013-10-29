<?php


namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Tests\TestTemplate;


/**
 * Class RouteCollection
 * @package Ext\DirectBundle\Tests\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class RouteCollectionTest extends TestTemplate
{

    /**
     * @var RouteCollection
     */
    private $collection;

    public function setUp()
    {
        parent::setUp();
        $this->collection = new RouteCollection();
    }

    /**
     * @param Rule $Rule
     * @dataProvider getRules
     */
    public function testAddAndRemove(Rule $Rule)
    {
        $this->collection->add($Rule);
        $this->assertEquals(1, count($this->collection));
        $this->assertTrue($this->collection->has($Rule->getAlias()));
        $this->assertTrue($this->collection->offsetExists($Rule->getAlias()));
        $this->assertEquals($Rule, $this->collection->get($Rule->getAlias()));

        $this->collection->remove($Rule);
        $this->assertFalse($this->collection->has($Rule->getAlias()));
        $this->assertFalse($this->collection->offsetExists($Rule->getAlias()));
    }

    public function testSerializeAndUnserialize()
    {
        foreach($this->getRules() as $Rule)
            $this->collection->add($Rule[0]);

        $unserCollection = unserialize(serialize($this->collection));
        $this->assertEquals($this->collection, $unserCollection);
    }
} 