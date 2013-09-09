<?php

namespace Ext\DirectBundle\Annotation;

/**
 * Class Route
 * @package Ext\DirectBundle\Annotation
 * @author Semyon Velichko <semyon@velichko.net>
 * @Annotation
 * @Target("METHOD")
 */
class Route extends Base
{

    /**
     * @var boolean
     */
    private $isWithParams;

    /**
     * @var boolean
     */
    private $isFormHandler;

    /**
     * @param mixed $isFormHandler
     */
    public function setIsFormHandler($isFormHandler)
    {
        $this->isFormHandler = $isFormHandler;
    }

    /**
     * @return mixed
     */
    public function getIsFormHandler()
    {
        return $this->isFormHandler;
    }

    /**
     * @param mixed $isWithParams
     */
    public function setIsWithParams($isWithParams)
    {
        $this->isWithParams = $isWithParams;
    }

    /**
     * @return mixed
     */
    public function getIsWithParams()
    {
        return $this->isWithParams;
    }

}