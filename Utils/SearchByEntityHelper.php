<?php

namespace Ext\DirectBundle\Utils;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Ext\DirectBundle\Annotation\StrictCondition;
use Ext\DirectBundle\Utils\Processor\AbstractProcessor;
use Ext\DirectBundle\Utils\Processor\AssociationProcessor;
use Ext\DirectBundle\Utils\Processor\BetweenProcessor;
use Ext\DirectBundle\Utils\Processor\ScalarProcessor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ext\DirectBundle\Annotation\ExcludeTrim;
use Ext\DirectBundle\Model\SortItem;
use ReflectionClass;
use Doctrine\ORM\Query\QueryException;

/**
 * Class SearchByEntity
 *
 * @package Ext\DirectBundle\Utils
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class SearchByEntityHelper
{
    /**
     * @var string[]|QueryException[]
     */
    private $where = array();

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $class;

    /**
     * @var object
     */
    private $entity;

    /**
     * @var AbstractProcessor[]
     */
    private $fieldProcessors = array();

    /**
     * @var Processor\AssociationProcessor
     */
    private $associationProcessor;

    /**
     * @var Processor\BetweenProcessor
     */
    private $betweenProcessor = null;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $ignoredFields = array();

    /**
     * @var bool
     */
    private $strict;

    /**
     * @param boolean $strict
     *
     * @return $this
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getStrict()
    {
        return $this->strict;
    }

    /**
     * @param Reader $annotationReader
     */
    public function setAnnotationReader(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    private function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     *
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param string $class
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($class)
    {
        $this->queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->from($class, 'c')
            ->select('c');
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Exception
     */
    public function getQueryBuilder()
    {
        if (null === $this->queryBuilder) {
            throw new \Exception();
        }

        return $this->queryBuilder;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param object $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->setClass(get_class($entity));

        return $this;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param array $ignoredFields
     *
     * @return $this
     */
    public function setIgnoredFields($ignoredFields)
    {
        $this->ignoredFields = $ignoredFields;

        return $this;
    }

    /**
     * @return array
     */
    public function getIgnoredFields()
    {
        return $this->ignoredFields;
    }

    /**
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    public function getClassMetaData()
    {
        return $this->getEntityManager()->getClassMetadata($this->getClass());
    }

    /**
     * @param \Ext\DirectBundle\Utils\Processor\AssociationProcessor $associationProcessor
     *
     * @return $this
     */
    public function setAssociationProcessor(AssociationProcessor $associationProcessor)
    {
        $associationProcessor->setHelper($this);
        $this->associationProcessor = $associationProcessor;

        return $this;
    }

    /**
     * @return \Ext\DirectBundle\Utils\Processor\AssociationProcessor
     */
    public function getAssociationProcessor()
    {
        if ($this->associationProcessor === null) {
            $this->setAssociationProcessor(new AssociationProcessor($this));
        }

        return $this->associationProcessor;
    }

    /**
     * @param AbstractProcessor $fieldProcessor
     *
     * @return $this
     */
    public function addFieldProcessor(AbstractProcessor $fieldProcessor)
    {
        $fieldProcessor->setHelper($this);
        $this->fieldProcessors[] = $fieldProcessor;

        return $this;
    }

    /**
     * @return \Ext\DirectBundle\Utils\Processor\AbstractProcessor[]
     */
    public function getFieldProcessors()
    {
        return $this->fieldProcessors;
    }

    /**
     * @param \Ext\DirectBundle\Utils\Processor\BetweenProcessor $betweenProcessor
     */
    public function setBetweenProcessor($betweenProcessor)
    {
        $this->betweenProcessor = $betweenProcessor;
    }

    /**
     * @return \Ext\DirectBundle\Utils\Processor\BetweenProcessor
     */
    public function getBetweenProcessor()
    {
        if (is_null($this->betweenProcessor)) {
            $this->setBetweenProcessor(new BetweenProcessor($this));
        }

        return $this->betweenProcessor;
    }

    /**
     * @param object $entity
     * @param array  $ignore
     * @param array  $sort
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function createQueryBuilderByEntity($entity, array $ignore = array(), $sort = array())
    {
        $this->setEntity($entity);
        $this->setIgnoredFields($ignore);

        $this->createQueryBuilder($this->getClass());
        $this->where = array();

        $this->processFields($this->getClassMetaData()->getFieldNames(), $this->getFieldProcessors());
        $this->processFields($this->getClassMetaData()->getAssociationNames(), $this->getAssociationProcessors());
        $this->processCreateCondition();
        if (sizeof($sort)) {
            $this->addOrderBy($this->sortArrayToCollectionSorItems($sort));
        } else {
            $this->getQueryBuilder()->orderBy('c.id', 'DESC');
        }

        return $this->getQueryBuilder();
    }

    /**
     * @param SortItem[] $sort
     *
     * @throws \InvalidArgumentException
     */
    private function addOrderBy($sort)
    {
        //@TODO array => collection objects
        foreach ($sort as $field) {
            if ($field->getDestinationPath()) {
                $this->getQueryBuilder()->addOrderBy($field->getDestinationPath(), $field->getDirection());
            } else if (in_array($field->getPropertyName(), $this->getClassMetaData()->getFieldNames())) {
                $this->getQueryBuilder()->addOrderBy('c.' . $field->getPropertyName(), $field->getDirection());
            }
        }
    }

    /**
     * @param array $sort
     *
     * @return SortItem[]
     * @throws \InvalidArgumentException
     */
    private function sortArrayToCollectionSorItems(array $sort)
    {
        $result = array();
        foreach ($sort as $field) {
            if (empty($field['property']) || empty($field['direction'])) {
                throw new \InvalidArgumentException;
            }
            $item = new SortItem();
            $item->setPropertyName($field['property'])
                ->setDirection($field['direction']);

            if (! empty($field['destinationPath'])) {
                $item->setDestinationPath($field['destinationPath']);
            }
            $result[] = $item;
        }

        return $result;
    }

    private function processCreateCondition()
    {
        foreach ($this->getAnnotationReader()->getClassAnnotations($this->getClassMetaData()->getReflectionClass()) as $anotation) {
            if ($anotation instanceof StrictCondition) {
                if (empty($anotation->repositoryName) || empty($anotation->method)) {
                    throw new \LogicException;
                }
                call_user_func(array($this->getEntityManager()->getRepository($anotation->repositoryName), $anotation->method), $this);

                return;
            }
        }
        $this->createDefaultCondition();
    }

    public function createDefaultCondition()
    {
        foreach ($this->where as $condition) {
            $this->getQueryBuilder()->andWhere($condition);
        }
    }

    /**
     * @param array               $fields
     * @param AbstractProcessor[] $processors
     */
    private function processFields(array $fields, array $processors)
    {
        foreach ($fields as $field) {
            if (in_array($field, $this->getIgnoredFields())) {
                continue;
            }
            $value = $this->valueFromField($field);
            if (empty($value)) {
                continue;
            }

            $processor = $this->processorWalker($processors, $field, $value);

            $processor->setPropertyAnnotations(
                $this->getAnnotationReader()->getPropertyAnnotations(
                    $this->getClassMetaData()->getReflectionClass()->getProperty($field)
                )
            );

            $processor->process($field, $value);
        }
    }

    /**
     * @return array
     */
    private function getAssociationProcessors()
    {
        return array($this->getAssociationProcessor());
    }

    /**
     * @param string $field
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function valueFromField($field)
    {
        $getter = 'get' . ucfirst($field);
        if ($this->getClassMetaData()->getReflectionClass()->hasMethod($getter)) {
            return $this->getEntity()->{$getter}();
        } else {
            throw new \InvalidArgumentException('Class ' . $this->getClass() . ' does not have method  ' . $getter);
        }
    }

    /**
     * @param array  $processors
     * @param string $field
     * @param string $value
     *
     * @return mixed
     * @throws \LogicException
     */
    private function processorWalker(array $processors, $field, $value)
    {
        foreach ($processors as $processor) {
            $var = $processor->supports($value);
            if ($var) {
                return $processor;
            }
        }

        throw new \LogicException;
    }

    /**
     * @param object $entity
     * @param array  $ignore
     * @param array  $sort
     *
     * @return Query
     */
    public function getQueryByEntity($entity, $ignore = array(), $sort = array())
    {
        $qb = $this->createQueryBuilderByEntity($entity, $ignore, $sort);
        $res = $qb->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);

        $res->getSQL();

        return $res;
    }

    /**
     * @return Query\QueryException[]|\string[]
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param string $field
     *
     * @return QueryException|string
     * @throws \InvalidArgumentException
     */
    public function getWherePart($field)
    {
        if (!isset($this->where[$field])) {
            throw new \InvalidArgumentException;
        }

        return $this->where[$field];
    }

    /**
     * @param string $field
     * @param string $part
     *
     * @return $this
     */
    public function setWherePart($field, $part)
    {
        $this->where[$field] = $part;

        return $this;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasWherePart($field)
    {
        return isset($this->where[$field]);
    }

    /**
     * @param string $field
     */
    public function deleteWherePart($field)
    {
        if ($this->hasWherePart($field)) {
            unset($this->where[$field]);
        }
    }

}
