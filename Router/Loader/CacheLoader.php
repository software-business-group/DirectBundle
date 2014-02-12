<?php

namespace Ext\DirectBundle\Router\Loader;

use Ext\DirectBundle\Router\RouteCollection;

/**
 * Class CacheLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class CacheLoader
{

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @var \Ext\DirectBundle\Router\RouteCollection
     */
    private $collection;

    /**
     * @param RouteCollection $collection
     * @param string          $cacheDir
     * @param boolean         $isDebug
     */
    public function __construct(RouteCollection $collection, $cacheDir, $isDebug)
    {
        $this->cacheDir = $cacheDir;
        $this->isDebug = $isDebug;
        $this->collection = $collection;
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    private function getRouterCollection()
    {
        return $this->collection;
    }

    /**
     * @param array $collection
     */
    private function mergeRouterCollection($collection)
    {
        foreach ($collection as $rule) {
            $this->getRouterCollection()->add($rule);
        }
    }

    /**
     * @return string
     */
    private function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @return boolean
     */
    private function getIsDebug()
    {
        return $this->isDebug;
    }

    private function getCacheFileName()
    {
        return $this->getCacheDir() . '/extDirectRules.cache';
    }

    /**
     * @param mixed $resource
     *
     * @return bool
     */
    public function load($resource)
    {
        if (!$this->getIsDebug() && file_exists($this->getCacheFileName())) {
            $this->mergeRouterCollection(
                unserialize(file_get_contents($this->getCacheFileName()))
            );

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function write()
    {
        file_put_contents($this->getCacheFileName(), serialize($this->getRouterCollection()));

        $umask = $mode = 0666 & ~umask();
        chmod($this->getCacheFileName(), $umask);

        return true;
    }

}
