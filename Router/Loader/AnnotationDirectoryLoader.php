<?php

namespace Ext\DirectBundle\Router\Loader;

/**
 * Class AnnotationDirectoryLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationDirectoryLoader extends AnnotationFileLoader
{

    /**
     * @param AnnotationClassLoader $loader
     */
    public function __construct(AnnotationClassLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param $resource
     * @return bool|\Symfony\Component\Routing\RouteCollection|void
     */
    public function load($resource)
    {
        $ls = scandir($resource);
        foreach($ls as $file)
        {
            if(!preg_match('/\.php$/', $file))
                continue;

            $class = $this->findClass($resource . '/' . $file);
            $this->getLoader()->load($class);
        }
    }

    /**
     * @param string $resource
     * @param string|null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && is_dir($resource) && (is_null($type) || $type === 'annotation');
    }


} 