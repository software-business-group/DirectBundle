<?php

namespace Ext\DirectBundle\Tests\Utils\Processor;

use Mockery as m;
use Doctrine\Common\Annotations\AnnotationReader;
use Ext\DirectBundle\Utils\Processor\ArrayProcessor;
use Ext\DirectBundle\Utils\SearchByEntityHelper;


/**
 * Class ArrayProcessorTest
 *
 * @package Ext\DirectBundle\Tests\Utils\Processor
 */
class ArrayProcessorTest extends AbstractProcessorTestCase
{
    /**
     * @var ArrayProcessor
     */
    private $processor;

    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();

        $processor = new ArrayProcessor();
        $processor->setHelper($this->getHelper());

        $this->processor = $processor;
    }

    /**
     * @return array
     */
    public function getFieldAndArrayValue()
    {
        return array(
            array('fieldA', array('string1', 123))
        );
    }

    /**
     * @param string $field
     * @param array  $value
     *
     * @dataProvider getFieldAndArrayValue
     */
    public function testProcessWithArray($field, $value)
    {
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':'.$field), m::mustBe($value));
        $this->expr
            ->shouldReceive('in')
            ->once()
            ->with(m::mustBe('c.'.$field), m::mustBe(':'.$field));

        $this->processor->process($field, $value);
    }

    /**
     * Test
     */
    public function testSupports()
    {
        $this->assertTrue($this->processor->supports(array()), 'This not array');
    }
}
