<?php

namespace Ext\DirectBundle\Utils\Processor;

use Doctrine\ORM\QueryBuilder;
use Ext\DirectBundle\Utils\SearchByEntityHelper;
use Doctrine\Common\Annotations\Annotation;

/**
 * Class AbstractReflector
 *
 * @package Ext\DirectBundle\Utils\Processor
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
abstract class AbstractProcessor
{
    /**
     * @var SearchByEntityHelper
     */
    private $helper;

    /**
     * @var string
     */
    private $alias = 'c';

    /**
     * @var Annotation[]
     */
    private $propertyAnnotations = array();

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param SearchByEntityHelper $helper
     *
     * @return $this
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;

        return $this;
    }

    /**
     * @return SearchByEntityHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param Annotation[] $propertyAnnotations
     *
     * @return $this
     */
    public function setPropertyAnnotations($propertyAnnotations)
    {
        $this->propertyAnnotations = $propertyAnnotations;

        return $this;
    }

    /**
     * @return Annotation[]
     */
    public function getPropertyAnnotations()
    {
        return $this->propertyAnnotations;
    }

    /**
     * @return QueryBuilder
     * @throws \Exception
     */
    public function getQueryBuilder()
    {
        return $this->getHelper()->getQueryBuilder();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getHelper()->getEntityManager();
    }

    /**
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    public function getClassMetaData()
    {
        return $this->getHelper()->getClassMetaData();
    }

    /**
     * @param string    $field
     * @param string    $value
     *
     * @return mixed
     */
    abstract public function process($field, $value);

    /**
     * @param mixed    $value
     *
     * @return bool
     */
    abstract public function supports($value);

    /**
     * @return bool
     */
    public function isAssocitaionProcessor()
    {
        return false;
    }

    /**
     * @param QueryBuilder  $qb
     * @param mixed         $value
     */
    public function addWhere($qb, $value)
    {
        if ($this->getHelper()->getStrict()) {
            $qb->andWhere($value);
        } else {
            $qb->orWhere($value);
        }
    }

}
