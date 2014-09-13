<?php

namespace Ext\DirectBundle\Utils\Processor;

use Doctrine\Common\Collections\Collection;
use Ext\DirectBundle\Annotation\ExcludeTrim;

/**
 * Class ScalarProcessor
 *
 * @package Ext\DirectBundle\Utils\Processor
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ScalarProcessor extends AbstractProcessor
{
    /**
     * @var bool
     */
    private $stringPreprocessing = true;

    /**
     * @param bool  $stringPreprocessing
     *
     * @return $this
     */
    public function useStringPreprocessing($stringPreprocessing)
    {
        $this->stringPreprocessing = $stringPreprocessing;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Annotations\Annotation[] $propertyAnnotations
     *
     * @return $this|void
     */
    public function setPropertyAnnotations($propertyAnnotations)
    {
        parent::setPropertyAnnotations($propertyAnnotations);

        foreach ($propertyAnnotations as $propertyAnnotation) {
            if ($propertyAnnotation instanceof ExcludeTrim) {
                $this->useStringPreprocessing(!$propertyAnnotation->exclude);
            }
        }
    }

    /**
     * @return boolean
     */
    private function isStringPreprocessing()
    {
        return $this->stringPreprocessing;
    }

    private function defaults ()
    {
        $this->stringPreprocessing = true;
    }
    /**
     * @param string    $field
     * @param string    $value
     *
     * @return mixed|void
     */
    public function process($field, $value)
    {
        $qb = $this->getQueryBuilder();

        $fullField = $this->getAlias().'.'.$field;
        $valueAlias = ':'.$field;

        if (is_string($value)) {
            if ($this->isStringPreprocessing()) {
                $fullField = $qb->expr()->lower($qb->expr()->trim($fullField));
                $value =  mb_strtolower($value);
            }
        }
            //$qb->andWhere($qb->expr()->eq($fullField, $valueAlias));
        $this->getHelper()->setWherePart($field, $qb->expr()->eq($fullField, $valueAlias));

        $qb->setParameter($valueAlias, $value);
        $this->defaults();

    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function supports($value)
    {
        return is_scalar($value);
    }
}
