<?php

namespace Ext\DirectBundle\Router\Loader;

/**
 * Class AnnotationFileLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationFileLoader implements LoaderInterface
{

    /**
     * @var AnnotationClassLoader
     */
    private $loader;

    /**
     * @param AnnotationClassLoader $loader
     */
    public function __construct(AnnotationClassLoader $loader)
    {
        $this->loader = $loader;
    }

    public function load($resource)
    {
        if ($class = $this->findClass($resource))
            return $this->getLoader()->load($resource);

        return false;
    }

    /**
     * @return \Symfony\Component\Routing\Loader\AnnotationClassLoader
     */
    private function getLoader()
    {
        return $this->loader;
    }

    /**
     * @param $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return 'annotation' === $type || preg_match('/^\.php$/', $resource);
    }

    /**
     * @param $resource
     * @return string|bool
     */
    private function findClass($resource)
    {
        $namespace = false;
        $class = false;

        $content = file_get_contents($resource);
        $tokens = token_get_all($content);

        for($n = 0; $n < count($tokens); $n++)
        {
            $token = $tokens[$n];

            if($namespace === false && $token[0] === T_NAMESPACE)
            {
                $namespace = null;
                continue;
            }

            if($namespace === null && $token[0] === T_STRING)
            {
                $namespace = $token[1];
                continue;
            }

            if($class === false && $token[0] === T_CLASS)
            {
                $class = null;
                continue;
            }

            if($class === null && $token[0] === T_STRING)
            {
                $class = $token[1];
                continue;
            }

            if(is_string($namespace) && is_string($class))
                return $namespace . $class;
        }

        return;
    }

}