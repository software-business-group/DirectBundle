<?php

namespace Ext\DirectBundle\Router\Loader;

use Doctrine\Common\Annotations\Reader as AnnotationsReader;
use Ext\DirectBundle\Annotation\Route;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Annotation\Reader;
use Ext\DirectBundle\Annotation\Writer;

/**
 * Class ControllerAnnotationLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AnnotationClassLoader implements LoaderInterface
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
     * @param RouteCollection $collection
     * @param AnnotationsReader $reader
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
     * @param $resource
     */
    public function load($resource)
    {
        $class = new \ReflectionClass($resource);
        $methods = $class->getMethods();

        foreach($methods as $method)
            $this->processMethod($method);
    }

    /**
     * @param \ReflectionMethod $method
     */
    private function processMethod(\ReflectionMethod $method)
    {
        $annotations = $this->getReader()->getMethodAnnotations($method);

        $isProcessing = false;
        for($n = 0; $n < count($annotations); $n++)
        {
            $annotation = $annotations[$n];
            if($annotation instanceof Route)
            {
                if(!$isProcessing)
                    $n = 0;
                $isProcessing = true;

                $name = $method->name;
                if(!$name)
                    $name = $this->getDefaultRouteName($annotation);

                $controller = $this->getResolver()->genAction($method);
                $Rule = new Rule($name, $controller, $annotation->getIsWithParams(), $annotation->getIsFormHandler());
            }

            if($annotation instanceof Reader && isset($Rule))
            {
                $Rule->setReaderRoot($annotation->getRoot());
                $Rule->setReaderSuccessProperty($annotation->getSuccessProperty());
                $Rule->setReaderTotalProperty($annotation->getTotalProperty());
                $Rule->setReaderParam('type', $annotation->getType());
            }
        }

        if(isset($Rule))
            $this->getRouteCollection()->add($Rule);
    }

    /**
     * @param Route $route
     * @return string
     */
    private function getDefaultRouteName(Route $route)
    {
        return '';
    }

    /**
     * @param $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        if($type === 'class')
            return true;

        return false;
    }

}