<?php

namespace Ext\DirectBundle\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Router\Rule;

/**
 * Class TestTemplate
 * @package Ext\DirectBundle\Tests
 * @author Semyon Velichko <semyon@velichko.net>
 */
class TestTemplate extends WebTestCase
{

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    public function get($alias)
    {
        return static::$kernel->getContainer()->get($alias);
    }

    public function getReaderParams()
    {
        return array(
            array(true, array('type' => 'json', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total')),
            array(false, array('type' => 'xml', 'root' => 'result',
                'successProperty' => 'successProperty', 'totalProperty' => 'totalProperty')),
            array(false, array('type' => 'xml', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total')),
            array(false, array('type' => 'json', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total'))
        );
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return array(
            array(new Rule('formHandler', array('controller' => 'ExtDirect_Test.formHandler'), true, true)),
            array(new Rule('formHandlerWithoutParams', array('controller' => 'ExtDirect_Test.formHandler'), true, false)),
            array(new Rule('withParams', array('controller' => 'ExtDirect_Test.withParams'), true, false)),
            array(new Rule('withoutParams', array('controller' => 'ExtDirect_Test.withoutParams'), false, false)),
        );
    }

} 