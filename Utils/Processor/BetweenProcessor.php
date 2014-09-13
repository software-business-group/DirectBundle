<?php

namespace Ext\DirectBundle\Utils\Processor;

use Doctrine\Common\Collections\Collection;
use Ext\DirectBundle\Model\Between;

/**
 * Class BetweenProcessor
 *
 * @package Ext\DirectBundle\Utils\Processor
 */
class BetweenProcessor extends AbstractProcessor
{
    /**
     * @param string        $field
     * @param Between       $value
     *
     * @return mixed|void
     */
    public function process($field, $value)
    {
        $qb = $this->getQueryBuilder();
        $fullField = $this->getAlias() . '.' . $field;

        if ($value->hasBegin()) {
            $qb->setParameter(':begin_' . $field, $value->getBegin());
        }

        if ($value->hasEnd()) {
            $qb->setParameter(':end_' . $field, $value->getEnd());
        }

        if ($value->hasBetween()) {
            $this->getHelper()->setWherePart($field, sprintf('%1$s BETWEEN :begin_%2$s AND :end_%2$s', $fullField, $field));
        } else {
            if ($value->hasBegin()) {
                $this->getHelper()->setWherePart($field, sprintf('%1$s >= :begin_%2$s', $fullField, $field));
            }
            if ($value->hasEnd()) {
                $this->getHelper()->setWherePart($field, sprintf('%1$s <= :end_%2$s', $fullField, $field));
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
        return $value instanceof Between;
    }
}
