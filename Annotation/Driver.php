<?php

namespace Ext\DirectBundle\Annotation;

use Doctrine\Common\Annotations\Reader;
use Ext\DirectBundle\Router\Router;

/**
 * Class Driver
 * @package Ext\DirectBundle\Annotation
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Driver
{

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \Ext\DirectBundle\Router\Router
     */
    private $router;

    /**
     * @param Reader $reader
     * @param Router $router
     */
    public function __construct(Reader $reader, Router $router)
    {
        $this->reader = $reader;
        $this->router = $router;
    }

    /**
     * @return Reader
     */
    private function getReader()
    {
        return $this->reader;
    }

    /**
     * @return Router
     */
    private function getRouter()
    {
        return $this->router;
    }

    /**
     * @param $resource
     * @throws \InvalidArgumentException
     */
    public function load($resource)
    {
        if(!file_exists($resource))
            throw new \InvalidArgumentException('Resource does not exist');



        $annotations = $this->getReader()->getClassAnnotations();

        return;
    }


}