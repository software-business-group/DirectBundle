<?php

namespace Ext\DirectBundle\Utils\Processor;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AssociationProcessor
 *
 * @package Ext\DirectBundle\Utils\Processor
 */
class AssociationProcessor extends AbstractProcessor
{
    /**
     * @param string $field
     * @param string $value
     *
     * @return mixed|void
     */
    public function process($field, $value)
    {
        $metaData = $this->getClassMetaData();
        $qb = $this->getHelper()->getQueryBuilder();

        if ($metaData->hasAssociation($field)) {
            if (count($value) === 0) {
                return;
            }

            $association = $metaData->getAssociationMapping($field);
            $alias = sprintf('alias_%s', $association['fieldName']);
            $qb->join($this->getAlias().'.'.$association['fieldName'], $alias);
            if (is_array($value) || $value instanceof ArrayCollection) {
                $where = $this->getQueryBuilder()->expr()->orX();
                foreach ($value as $index => $item) {
                    $valueAlias = sprintf(':value_%s_%s', $index, $alias);
                    $qb->setParameter($valueAlias, is_scalar($item) ? $item: $item->getId());
                    $where->add($qb->expr()->in($alias, $valueAlias));
                }
                $this->getHelper()->setWherePart($field, $where);
            } else if (is_object($value)) {
                $valueAlias = sprintf(':value_%s', $alias);
                $qb->setParameter($valueAlias, $value);
                $this->getHelper()->setWherePart($field, $qb->expr()->in($alias, $valueAlias));
            }
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool|void
     */
    public function supports($value)
    {
        if (is_object($value)) {
            return true;
        } else if (is_array($value)) {
            foreach ($value as $item) {
                if (! is_object($item)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAssocitaionProcessor()
    {
        return true;
    }
}
