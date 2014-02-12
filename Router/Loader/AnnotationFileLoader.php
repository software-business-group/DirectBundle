<?php

namespace Ext\DirectBundle\Router\Loader;

/**
 * Class AnnotationFileLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class AnnotationFileLoader extends AbstractLoader
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

    /**
     * @param mixed $resource
     *
     * @return bool|\Symfony\Component\Routing\RouteCollection
     */
    public function load($resource)
    {
        if ($class = $this->findClass($resource)) {
            return $this->getLoader()->load($class);
        }

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
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool|mixed
     */
    public function supports($resource, $type = null)
    {
        return 'annotation' === $type || (preg_match('/\.php$/', $resource) && is_null($type));
    }

    /**
     * @param mixed $resource
     *
     * @return bool|string
     */
    public function findClass($resource)
    {
        $namespace = false;
        $class = false;

        $content = file_get_contents($resource);
        $tokens = token_get_all($content);

        for ($n = 0; $n < count($tokens); $n++) {
            $token = $tokens[$n];
            if (!is_array($token)) {
                continue;
            }

            if ($namespace === false && $token[0] === T_NAMESPACE) {
                $namespace = true;
            }

            if ($namespace === true && $token[0] === T_STRING) {
                $namespace = '';
                do {
                    $token = $tokens[$n++];
                    if (is_array($token)) {
                        $namespace .= $token[1];
                    }
                } while (is_array($token) && in_array($token[0], array(T_STRING, T_NS_SEPARATOR)));
            }

            if ($class === false && $token[0] === T_CLASS) {
                $class = true;
            }

            if ($class === true && $token[0] === T_STRING) {
                $class = $token[1];
            }

            if (is_string($namespace) && is_string($class)) {
                return $namespace . '\\' . $class;
            }
        }

        return false;
    }

}
