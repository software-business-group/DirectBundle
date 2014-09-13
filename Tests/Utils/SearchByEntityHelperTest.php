<?php

namespace Ext\DirectBundle\Tests\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use Mockery as m;
use Ext\DirectBundle\Annotation\StrictCondition;
use Ext\DirectBundle\Utils\SearchByEntityHelper;
use Ext\DirectBundle\Tests\Direct\Utils\Processor\AbstractProcessorTest;

/**
 * Class SearchByEntityHelperTest
 *
 * @package Ext\DirectBundle\Tests\Direct\Utils
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class SearchByEntityHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $entity
     *
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getEntityManagerMockWithStub($entity)
    {
        $reflection = m::mock(new \ReflectionClass($entity));
        $reflection->shouldReceive('hasMethod')->andReturn(true);
        $reflection->shouldReceive('getProperty')->andReturn(m::mock('\ReflectionProperty'));

        $metaData = m::mock('Doctrine\ORM\Mapping\ClassMetadataInfo');
        $fields = array('field1', 'field2', 'field3');
        $associations = array('association1', 'association2', 'association3');
        $metaData->shouldReceive('getFieldNames')->andReturn($fields);
        $metaData->shouldReceive('getAssociationNames')->andReturn($associations);
        $metaData->shouldReceive('getReflectionClass')->andReturn($reflection);
        $metaData->shouldReceive('hasField')->andReturnUsing(
            function ($field) use ($fields) {
                return in_array($field, $fields);
            });
        $metaData->shouldReceive('hasAssociation')->andReturnUsing(
            function ($field) use ($associations) {
                return in_array($field, $associations);
            });

        $queryBuilder = m::mock('Doctrine\ORM\QueryBuilder');
        $queryBuilder->shouldReceive('from')->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('select')->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('orderBy')->andReturn($queryBuilder);
        $queryBuilder->shouldIgnoreMissing(true);

        $entityManager = m::mock('\Doctrine\ORM\EntityManager', array(
            'getClassMetadata' => $metaData,
            'getConfiguration' => null,
            'createQueryBuilder' => $queryBuilder,
            'getConnection' => $this->getConnectionMock(),
            'newHydrator' => $this->getHydratorMockWithStub($entity),
        ));

        return $entityManager;
    }

    /**
     * (@inheritdoc)
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getConnectionMock()
    {
        $connection = m::mock('\Doctrine\DBAL\Connection', array(
            'executeQuery' => null
        ));

        return $connection;
    }

    /**
     * @param m\MockInterface|\Yay_MockObject $stub
     *
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getHydratorMockWithStub($stub)
    {
        $hydrator = m::mock('\Doctrine\ORM\Internal\Hydration\AbstractHydrator', array(
            'hydrateAll' => $stub
        ));

        return $hydrator;
    }

    public function testCreateQueryBuilderByEntity()
    {

        $ar = m::mock('Doctrine\Common\Annotations\AnnotationReader');
        $ar->shouldReceive('getPropertyAnnotations')->andReturn(array());
        $ar->shouldReceive('getClassAnnotations')->andReturn(array());

        //$ar = new AnnotationReader();

        $entity = $this->getStdClassEntity();

        $helper = new SearchByEntityHelper();
        $helper->setEntityManager($this->getEntityManagerMockWithStub($entity));

        $helper->setAnnotationReader($ar);

        foreach ($this->getProcessors() as $processor) {
            $helper->addFieldProcessor($processor);
        }
        $helper->setAssociationProcessor($this->getAssociationProcessor());
        $helper->createQueryBuilderByEntity($entity);
        $this->assertTrue(true);
    }

    /**
     * Test
     */
    public function testClassAnnotations()
    {
        $strictCondition = new StrictCondition(array());
        $strictCondition->repositoryName = 'PaywebIndexBundle:Transaction';
        $strictCondition->method = 'createSearchCondition';
        $transactionRepository = m::mock('Ext\DirectBundle\Repository\TransactionRepository');
        $transactionRepository->shouldReceive($strictCondition->method)->times(1);
        $ar = m::mock('Doctrine\Common\Annotations\AnnotationReader');
        $ar->shouldReceive('getPropertyAnnotations')->andReturn(array());
        $ar->shouldReceive('getClassAnnotations')->andReturn(array(
            $strictCondition
        ));
        $entity = $this->getStdClassEntity();
        $entityManager = $this->getEntityManagerMockWithStub($entity);
        $entityManager->shouldReceive('getRepository')
            ->with(m::mustBe($strictCondition->repositoryName))
        ->andReturn($transactionRepository);

        $helper = new SearchByEntityHelper();
        $helper->setEntityManager($entityManager);

        $helper->setAnnotationReader($ar);

        foreach ($this->getProcessors() as $processor) {
            $helper->addFieldProcessor($processor);
        }
        $helper->setAssociationProcessor($this->getAssociationProcessor());
        $helper->createQueryBuilderByEntity($entity);

    }
    /**
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getStdClassEntity()
    {
        $entity = m::mock('\stdClass');
        $entity->shouldReceive('getField1')->once()->andReturn('field1');
        $entity->shouldReceive('getField2')->once()->andReturn('field2');
        $entity->shouldReceive('getField3')->once()->andReturn('field3');
        $entity->shouldReceive('getAssociation1')->once()->andReturn('association1');
        $entity->shouldReceive('getAssociation2')->once()->andReturn('association2');
        $entity->shouldReceive('getAssociation3')->once()->andReturn('association3');

        return $entity;
    }

    /**
     * @return m\MockInterface|\Yay_MockObject[]
     */
    private function getProcessors()
    {
        return array(
            $this->getScalarProcessor(),
            $this->getArrayProcessor(),
            //$this->getBetweenProcessor(),
        );
    }

    /**
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getAssociationProcessor()
    {
        $processor = m::mock('\Ext\DirectBundle\Utils\Processor\AssociationProcessor');
        $processor->shouldReceive('supports')->andReturn(true);
        $processor->shouldIgnoreMissing();
        $processor->shouldReceive('process')->times(3);
        $processor->shouldReceive('setHelper')->times(1);

        return $processor;
    }

    /**
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getScalarProcessor()
    {
        $processor = m::mock('\Ext\DirectBundle\Utils\Processor\ScalarProcessor');
        $processor->shouldIgnoreMissing();
        $processor->shouldReceive('process')->times(1);
        $processor->shouldReceive('setHelper')->times(1);
        $processor->shouldReceive('supports')->andReturnValues(array(false, false, true))->times(3);

        return $processor;
    }

    /**
     * @return m\MockInterface|\Yay_MockObject
     */
    private function getArrayProcessor()
    {
        $processor = m::mock('\Ext\DirectBundle\Utils\Processor\ArrayProcessor');
        $processor->shouldIgnoreMissing();
        $processor->shouldReceive('process')->times(2);
        $processor->shouldReceive('setHelper')->times(1);
        $processor->shouldReceive('supports')->andReturnValues(array(true, true))->times(2);

        return $processor;
    }

    private function getBetweenProcessor()
    {
        $processor = m::mock('\Ext\DirectBundle\Utils\Processor\BetweenProcessor');
        $processor->shouldIgnoreMissing();
        $processor->shouldReceive('process')->times(2);
        $processor->shouldReceive('setHelper')->times(1);
        $processor->shouldReceive('supports')->andReturnValues(array(true, true))->times(2);

        return $processor;
    }


}
