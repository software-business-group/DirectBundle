<?php

namespace Ext\DirectBundle\Tests\Utils\Processor;

use Ext\DirectBundle\Utils\SearchByEntityHelper;
use \Mockery as m;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;

/**
 * Class AbstractProcessorTestCase
 *
 * @package Ext\DirectBundle\Tests\Utils\Processor
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class AbstractProcessorTestCase extends \PHPUnit_Framework_TestCase
{

    static protected $kernel;

    /**
     * @var \Mockery\MockInterface|EntityManager
     */
    protected $entityManager;

    /**
     * @var \Mockery\MockInterface|QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var \Mockery\MockInterface|\Ext\DirectBundle\Utils\SearchByEntityHelper
     */
    protected $helper;

    /**
     * @var \Mockery\MockInterface|\Doctrine\ORM\Query\Expr
     */
    protected $expr;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->helper = m::mock(new SearchByEntityHelper());
        $this->entityManager = m::mock('\Doctrine\ORM\EntityManager');
        $this->queryBuilder = m::mock('\Doctrine\ORM\QueryBuilder');
        $this->queryBuilder->shouldIgnoreMissing(true);
        $this->entityManager->shouldReceive('createQueryBuilder')->andReturn($this->queryBuilder);
        $this->expr = m::mock('\Doctrine\ORM\Query\Expr');
        $this->queryBuilder->shouldReceive('expr')->andReturn($this->expr);
        $this->queryBuilder
            ->shouldReceive('expr')
            ->andReturn($this->expr);
        $this->helper->shouldReceive('getQueryBuilder')->andReturn($this->createQueryBuilder());
    }

    /**
     * @return \Mockery\MockInterface|\Ext\DirectBundle\Utils\SearchByEntityHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from('test', 'c');

        return $qb;
    }

}
