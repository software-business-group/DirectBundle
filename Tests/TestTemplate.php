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

    /**
     * @return array
     */
    public function getReaderParams()
    {
        return array(
            array('type' => 'json', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total'),
            array('type' => 'xml', 'root' => 'result',
                'successProperty' => 'successProperty', 'totalProperty' => 'totalProperty'),
            array('type' => 'xml', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total'),
            array('type' => 'json', 'root' => null, 'successProperty' => 'success', 'totalProperty' => 'total')
        );
    }

    /**
     * @return array
     */
    public function getWriterParams()
    {
        return array(
            array('type' => 'xml', 'root' => 'write'),
            array('type' => 'yaml'),
            array('root' => 'root'),
            array()
        );
    }

    /**
     * @return array
     */
    public function getRules()
    {
        $rules = array(
            array(new Rule('formHandler', array('controller' => 'ExtDirect_Test.formHandler'), true, true)),
            array(new Rule('formHandlerWithoutParams', array('controller' => 'ExtDirect_Test.formHandler'), true, false)),
            array(new Rule('withParams', array('controller' => 'ExtDirect_Test.withParams'), true, false)),
            array(new Rule('withoutParams', array('controller' => 'ExtDirect_Test.withoutParams'), false, false)),
        );

        foreach($rules as $Rule)
        {
            $Rule = $Rule[0];
            $readerParams = $this->getReaderParams();
            foreach($readerParams[0] as $key => $value)
                $Rule->setReaderParam($key, $value);

            $writerParams = $this->getWriterParams();
            foreach($writerParams[0] as $key => $value)
                $Rule->setWriterParam($key, $value);
        }

        return $rules;
    }

} 