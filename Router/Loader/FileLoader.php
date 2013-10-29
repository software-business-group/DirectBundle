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
     * @param FileLocatorInterface $locator
     */
    public function __construct(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
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
