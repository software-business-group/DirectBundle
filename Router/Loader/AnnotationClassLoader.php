<?php

namespace Ext\DirectBundle\Router\Loader;

use Doctrine\Common\Annotations\Reader;
use Ext\DirectBundle\Router\Router;

/**
 * Class ControllerAnnotationLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationClassLoader implements LoaderInterface
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @param Router $router
     * @param Reader $reader
     */
    public function __construct(Router $router, Reader $reader)
    {
        $this->router = $router;
        $this->reader = $reader;
    }

    /**
     * @return \Ext\DirectBundle\Router\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param $resource
     */
    public function load($resource)
    {

    }

    public function supports($resource, $type = null)
    {
        if($type === 'annotation')
            return true;

        return false;
    }

}