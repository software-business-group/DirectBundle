<?php


namespace Ext\DirectBundle\Router\Loader;

use Symfony\Component\Routing\Loader\AnnotationFileLoader as RouterAnnotationFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface as ConfigLoaderInterface;

class AnnotationFileLoader extends RouterAnnotationFileLoader implements LoaderInterface, ConfigLoaderInterface
{

    public function load($resource, $type = null)
    {

    }

}