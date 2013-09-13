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
            $this->getLoader()->load($resource);
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
        $namespace = null;
        $class = null;

        $content = file_get_contents($resource);
        $tokens = token_get_all($content);

        for($n = 0; $n < count($tokens); $n++)
        {
            $token = $tokens[$n];

            if($namespace === null && $token[0] === T_NAMESPACE)
                $namespace = $token[1];

            if($class === $class && $token[0] === T_CLASS)
                $class = $token[1];

            if($namespace && $class)
                return $namespace . $class;
        }

        return;
    }

}