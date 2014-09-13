<?php

namespace Ext\DirectBundle\Utils\Processor;

use Doctrine\Common\Collections\Collection;

/**
 * Class ArrayProcessor
 *
 * @package Ext\DirectBundle\Utils\Processor
 */
class ArrayProcessor extends AbstractProcessor
{
    /**
     * @param string $field
     * @param string $value
     *
     * @return mixed|void
     */
    public function process($field, $value)
    {
        $qb = $this->getQueryBuilder();

        $fullField = $this->getAlias().'.'.$field;
        $valueAlias = ':'.$field;

        $this->getHelper()->setWherePart($field, $qb->expr()->in($fullField, $valueAlias));

        $qb->setParameter($valueAlias, $value);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function supports($value)
    {
        return is_array($value);
    }
}
