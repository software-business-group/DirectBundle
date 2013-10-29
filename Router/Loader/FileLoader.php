<?php

namespace Ext\DirectBundle\Router\Loader;

use Symfony\Component\Config\FileLocatorInterface;

class FileLoader
{

    private $loaders = array();

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    private $locator;

    /**
     * @var CacheLoader
     */
    private $cache;

    /**
     * @var string
     */
    private $initialResource;

    /**
     * @param FileLocatorInterface $locator
     * @param CacheLoader $cache
     */
    public function __construct(FileLocatorInterface $locator, CacheLoader $cache)
    {
        $this->locator = $locator;
        $this->cache = $cache;
    }

    /**
     * @param AbstractLoader $loader
     */
    public function addLoader(AbstractLoader $loader)
    {
        $loader->setFileLoader($this);
        $this->loaders[] = $loader;
    }

    /**
     * @return array
     */
    public function getLoaders()
    {
        return $this->loaders;
    }

    /**
     * @param $index
     * @return AbstractLoader
     */
    private function getLoader($index)
    {
        return $this->loaders[$index];
    }

    /**
     * @return \Symfony\Component\Config\FileLocatorInterface
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\CacheLoader
     */
    private function getCache()
    {
        return $this->cache;
    }

    /**
     * @param $resource
     * @param null $type
     * @return bool
     */
    public function load($resource, $type = null)
    {
        foreach(array_keys($this->getLoaders()) as $index)
        {
            $resource = $this->getLocator()->locate($resource);

            $loader = $this->getLoader($index);
            if($loader->supports($resource, $type))
            {
                $loader->load($resource);
                continue;
            }
        }

        return true;
    }

    /**
     * @param $initialResource
     * @return $this
     */
    public function setInitialResource($initialResource)
    {
        $this->initialResource = $initialResource;
        return $this;
    }

    /**
     * @return string
     */
    private function getInitialResource()
    {
        return $this->initialResource;
    }

    /**
     * @return bool
     */
    public function loadInitialResource()
    {
        if(!$this->getCache()->load($this->getInitialResource()))
        {
            $this->load($this->getInitialResource());
            $this->getCache()->write();
        }

        return true;
    }

}
