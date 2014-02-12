<?php

namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Tests\TestTemplate;

/**
 * Class RuleTest
 *
 * @package Ext\DirectBundle\Tests\Router
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class RuleTest extends TestTemplate
{

    /**
     * @param Rule $rule
     *
     * @dataProvider getRules
     */
    public function testSerializeAndUnserialize(Rule $rule)
    {

        $serialized = serialize($rule);
        $unserRule = unserialize($serialized);

        $this->assertEquals($rule, $unserRule);
    }

}
