<?php

namespace Ext\DirectBundle\Router\Loader;

use Doctrine\Common\Annotations\Reader as AnnotationsReader;
use Ext\DirectBundle\Annotation\Base;
use Ext\DirectBundle\Annotation\Route;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Annotation\Reader;
use Ext\DirectBundle\Annotation\Writer;

/**
 * Class AnnotationClassLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class AnnotationClassLoader extends AbstractLoader
{

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var AnnotationsReader
     */
    private $reader;

    /**
     * @var ControllerResolver
     */
    private $resolver;

    /**
     * @param RouteCollection    $collection
     * @param AnnotationsReader  $reader
     * @param ControllerResolver $resolver
     */
    public function __construct(RouteCollection $collection, AnnotationsReader $reader, ControllerResolver $resolver)
    {
        $this->collection = $collection;
        $this->reader = $reader;
        $this->resolver = $resolver;
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @return ControllerResolver
     */
    private function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param mixed $resource
     */
    public function load($resource)
    {
        $class = new \ReflectionClass($resource);
        $methods = $class->getMethods();

        foreach ($methods as $method) {
            $this->processMethod($method);
        }
    }

    /**
     * @param \ReflectionMethod $method
     */
    private function processMethod(\ReflectionMethod $method)
    {
        $annotations = $this->getReader()->getMethodAnnotations($method);

        if (count($annotations) === 0) {
            return;
        }

        $this->sortAnnotations($annotations);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Route) {
                $controller = $this->getResolver()->genAction($method);

                $name = $annotation->getName();
                if (!$name) {
                    $name = $this->getDefaultRouteName($controller);
                }

                $rule = new Rule($name, $controller, $annotation->getIsWithParams(), $annotation->getIsFormHandler());
            }

            if ($annotation instanceof Reader && isset($rule)) {
                $rule->setReaderRoot($annotation->getRoot());
                $rule->setReaderSuccessProperty($annotation->getSuccessProperty());
                $rule->setReaderTotalProperty($annotation->getTotalProperty());
                $rule->setReaderParam('type', $annotation->getType());
            }

            if ($annotation instanceof Writer && isset($rule)) {
                $rule->setWriterParam('type', $annotation->getType());
                $rule->setWriterParam('root', $annotation->getRoot());
            }
        }

        if (isset($rule)) {
            $this->getRouteCollection()->add($rule);
        }
    }

    /**
     * @param array &$annotations
     *
     * @return bool
     */
    private function sortAnnotations(&$annotations)
    {
        return uasort($annotations, array($this, 'sortFunction'));
    }

    /**
     * @param Base $a
     * @param Base $b
     *
     * @return int
     */
    private function sortFunction(Base $a, Base $b)
    {
        if ($a === $b) {
            return 0;
        }

        if ($a instanceof Route) {
            return -1;
        }

        return 1;
    }

    /**
     * @param string $controller
     *
     * @return string
     */
    private function getDefaultRouteName($controller)
    {
        return str_replace(':', '_', $controller);
    }

    /**
     * @param mixed $resource
     * @param mixed $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        if ($type === 'annotation') {
            return true;
        }

        return false;
    }

}