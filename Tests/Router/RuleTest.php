<?php

namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getValidRules()
    {
        return ValidRules::getValidRules();
    }

    /**
     * @param Rule $Rule
     * @dataProvider getValidRules
     */
    public function testControllerName(Rule $Rule)
    {
        if(is_string($Rule->getController()))
            $this->assertRegExp('/.+:.+(:.+)?/', $Rule->getController());

        if(is_callable($Rule->getController()))
            $this->assertEquals('OK', call_user_func($Rule->getController()));
    }

}