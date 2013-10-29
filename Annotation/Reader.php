<?php

namespace Ext\DirectBundle\Annotation;

/**
 * Class Reader
 * @package Ext\DirectBundle\Annotation
 * @author Semyon Velichko <semyon@velichko.net>
 * @Annotation
 * @Target("METHOD")
 */
class Reader extends Base
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $successProperty;

    /**
     * @var string
     */
    private $totalProperty;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $successProperty
     */
    public function setSuccessProperty($successProperty)
    {
        $this->successProperty = $successProperty;
    }

    /**
     * @return string
     */
    public function getSuccessProperty()
    {
        return $this->successProperty;
    }

    /**
     * @param string $totalProperty
     */
    public function setTotalProperty($totalProperty)
    {
        $this->totalProperty = $totalProperty;
    }

    /**
     * @return string
     */
    public function getTotalProperty()
    {
        return $this->totalProperty;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
