<?php

namespace Ext\DirectBundle\Tests\Utils\Processor;

use Doctrine\Common\Collections\ArrayCollection;
use Ext\DirectBundle\Utils\Processor\AssociationProcessor;
use Doctrine\Common\Collections\Collection;

use \Mockery as m;

/**
 * Class AssociationProcessorTest
 *
 * @package Ext\DirectBundle\Tests\Utils\Processor
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class AssociationProcessorTest extends AbstractProcessorTestCase
{
    /**
     * @var AssociationProcessor $processor
     */
    private $processor;
    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->processor = new AssociationProcessor();
        $this->processor->setHelper($this->getHelper());

        $classMetadataMock = m::mock('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMock->shouldReceive('hasAssociation')->andReturn(true);
        $classMetadataMock->shouldReceive('getAssociationMapping')
            ->andReturn(array(
                'fieldName' => 'relation',
                'type' => 8
            ));

        $this->getHelper()->shouldReceive('getClassMetaData')->andReturn($classMetadataMock);
    }

    /**
     * Test
     */
    public function testProcess()
    {
        $first = m::mock('\stdClass');
        $first->shouldReceive('getId')->andReturn(1);
        $first->shouldReceive('getValue')->andReturn('first');

        $collection = new ArrayCollection();
        $collection->add($first);

        $this->queryBuilder
            ->shouldReceive('join')
            ->with(m::mustBe('c.relation'), m::mustBe('alias_relation'));
        $this->queryBuilder
            ->shouldReceive('setParameter')
            ->once()
            ->with(m::mustBe(':value_alias_relation'), m::mustBe($first));
        $this->expr
            ->shouldReceive('in')
            ->once()
            ->with(m::mustBe('alias_relation'), m::mustBe(':value_alias_relation'));

        $this->processor->process('relation', $first);
    }

    /**
     * Test
     */
    public function testSupports()
    {
        $obj = new \stdClass();
        $obj->students = array('Kalle', 'Ross', 'Felipe');
        $this->assertTrue($this->processor->supports($obj), 'This not class');
    }

}
