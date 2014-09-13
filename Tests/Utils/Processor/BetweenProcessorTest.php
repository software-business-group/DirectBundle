<?php

namespace Ext\DirectBundle\Tests\Utils\Processor;

use Mockery as m;
use Ext\DirectBundle\Utils\Processor\BetweenProcessor;
use Ext\DirectBundle\Utils\SearchByEntityHelper;
use Ext\DirectBundle\Model\Between;

/**
 * Class BetweenProcessorTest
 *
 * @package Ext\DirectBundle\Tests\Utils\Processor
 */
class BetweenProcessorTest extends AbstractProcessorTestCase
{
    /**
     * @var BetweenProcessor
     */
    private $processor;

    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();

        $processor = new BetweenProcessor();
        $processor->setHelper($this->getHelper());

        $this->processor = $processor;
    }

    /**
     * @return array
     */
    public function getFieldAndBetweenValue()
    {
        $dp = new Between();
        $dp->setBegin(new \DateTime('2014-05-05'));
        $dp->setEnd(new \DateTime('2014-05-09'));

        return array(
            array('create', $dp),
        );
    }

    /**
     * @param string  $field
     * @param Between $value
     *
     * @dataProvider getFieldAndBetweenValue
     */
    public function testProcessWithBetween($field, $value)
    {
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':begin_' . $field), m::mustBe($value->getBegin()))
            ->andReturn($this->queryBuilder);
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':end_' . $field), m::mustBe($value->getEnd()))
            ->andReturn($this->queryBuilder);
        $this->helper
            ->shouldReceive('setWherePart')
            ->once()
            ->with(m::mustBe($field), m::mustBe('c.'.$field.' BETWEEN :begin_'.$field.' AND :end_'.$field));
        $this->processor->process($field, $value);
    }

    /**
     * @return array
     */
    public function getFieldAndBetweenValueSingleDate()
    {
        $singleBegin = new Between();
        $singleBegin->setBegin(new \DateTime('2014-05-05'));
        $singleEnd = new Between();
        $singleEnd->setEnd(new \DateTime('2014-05-09'));

        return array(array($singleBegin), array($singleEnd));
    }

    /**
     * @param Between $value
     *
     * @dataProvider getFieldAndBetweenValueSingleDate
     */
    public function testProcessBetweenWithSingleDate($value)
    {
        if ($value->hasBegin()) {
            $this->queryBuilder
                ->shouldReceive('setParameter')
                ->once()
                ->with(m::mustBe(':begin_create'), m::mustBe($value->getBegin()))
                ->andReturn($this->queryBuilder);
            $this->helper
                ->shouldReceive('setWherePart')
                ->once()
                ->with(m::mustBe('create'), m::mustBe('c.create >= :begin_create'));
        }

        if ($value->hasEnd()) {
            $this->queryBuilder
                ->shouldReceive('setParameter')
                ->once()
                ->with(m::mustBe(':end_create'), m::mustBe($value->getEnd()))
                ->andReturn($this->queryBuilder);
            $this->helper
                ->shouldReceive('setWherePart')
                ->once()
                ->with(m::mustBe('create'), m::mustBe('c.create <= :end_create'));
        }

        $this->processor->process('create', $value);
    }

    /**
     * Test
     */
    public function testSupports()
    {
        $this->assertTrue($this->processor->supports(new Between()), 'supports');
        $this->assertFalse($this->processor->supports(new \stdClass()), 'supports');
    }
}
