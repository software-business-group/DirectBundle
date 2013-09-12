<?php

namespace Ext\DirectBundle\Router\Loader;

use Ext\DirectBundle\Router\Router;
use Symfony\Component\Config\FileLocatorInterface;

class FileLoader
{

    private $loaders = array();

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    private $locator;

    /**
     * @param Router $router
     * @param FileLocatorInterface $locator
     */
    public function __construct(Router $router, FileLocatorInterface $locator)
    {
        $this->router = $router;
        $this->locator = $locator;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function addLoader(LoaderInterface $loader)
    {
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
     * @return LoaderInterface
     */
    private function getLoader($index)
    {
        return $this->loaders[$index];
    }

    /**
     * @return Router
     */
    private function getRouter()
    {
        return $this->router;
    }

    /**
     * @return \Symfony\Component\Config\FileLocatorInterface
     */
    public function getLocator()
    {
        return $this->locator;
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

}