<?php

namespace Ext\DirectBundle\Tests\Utils\Processor;

use Ext\DirectBundle\Utils\Processor\ScalarProcessor;
use Mockery as m;

/**
 * Class ScalarProcessorTest
 *
 * @package Ext\DirectBundle\Tests\Utils\Processor
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ScalarProcessorTest extends AbstractProcessorTestCase
{

    /**
     * @var ScalarProcessor
     */
    private $processor;

    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();

        $processor = new ScalarProcessor();
        $processor->setHelper($this->getHelper());

        $this->processor = $processor;
    }

    /**
     * @return array
     */
    public function getFieldAndIntAndBoolValue()
    {
        return array(
            array('fieldB', 123, array('c.fieldB', '=', ':fieldB')),
            array('isActive', true),
            array('isEnabled', false)
        );
    }

    /**
     * @return array
     */
    public function getFieldAndStringValue()
    {
        return array(
            array('fieldA', 'string1')
        );
    }

    /**
     * @param string  $field
     * @param integer $value
     *
     * @dataProvider getFieldAndIntAndBoolValue
     */
    public function testProcessWithIntAndBool($field, $value)
    {
        $this->expr
            ->shouldReceive('eq')
            ->once()
            ->with('c.'.$field, ':'.$field);
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':'.$field), m::mustBe($value));

        $this->processor->process($field, $value);

    }

    /**
     * @param string $field
     * @param string $value
     *
     * @dataProvider getFieldAndStringValue
     */
    public function testProcessWithString($field, $value)
    {
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':'.$field), m::mustBe($value));
        $this->expr
            ->shouldReceive('trim')
            ->with(m::mustBe('c.'.$field))
            ->once()
            ->andReturn('TRIM(c.'.$field.')');
        $this->expr
            ->shouldReceive('lower')
            ->once()
            ->andReturn('LOWER(TRIM(c.'.$field.'))');
        $this->expr
            ->shouldReceive('eq')
            ->once()
            ->with('LOWER(TRIM(c.'.$field.'))', ':'.$field);

        $this->processor->process($field, $value);

        return;
    }

    /**
     * @return array
     */
    public function getScalarValue()
    {
        return array(
            array(5),
            array(1.234),
            array('string'),
            array(true),
        );
    }

    /**
     * @param mixed $scalar
     *
     * @dataProvider getScalarValue
     */
    public function testSupports($scalar)
    {
        $this->assertTrue($this->processor->supports($scalar), 'This not scalar');
    }
}
