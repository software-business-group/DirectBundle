<?php

namespace Ext\DirectBundle\Router\Loader;

use Symfony\Component\Config\FileLocatorInterface;

/**
 * Class FileLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class FileLoader
{

    private $loaders = array();

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    private $locator;

    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @var string
     */
    private $initialResource;

    /**
     * @param FileLocatorInterface $locator
     * @param CacheProvider        $cache
     */
    public function __construct(FileLocatorInterface $locator, CacheProvider $cache)
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
     * @param mixed $index
     *
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
     * @return \Ext\DirectBundle\Router\Loader\CacheProvider
     */
    private function getCache()
    {
        return $this->cache;
    }

    /**
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool
     */
    public function load($resource, $type = null)
    {
        if (empty($resource)) {
            return false;
        }

        foreach (array_keys($this->getLoaders()) as $index) {
            $resource = $this->getLocator()->locate($resource);

            $loader = $this->getLoader($index);
            if ($loader->supports($resource, $type)) {
                $loader->load($resource);
                continue;
            }
        }

        return true;
    }

    /**
     * @param mixed $initialResource
     *
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
        if (!$this->getCache()->load($this->getInitialResource())) {
            $this->load($this->getInitialResource());
            $this->getCache()->write();
        }

        return true;
    }

}
