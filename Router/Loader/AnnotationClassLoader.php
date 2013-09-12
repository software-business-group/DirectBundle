<?php

namespace Ext\DirectBundle\Router\Loader;

use Doctrine\Common\Annotations\Reader;

/**
 * Class ControllerAnnotationLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationClassLoader implements LoaderInterface
{

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

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